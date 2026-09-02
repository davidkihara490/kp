<?php

namespace App\Livewire\Clients\Parcels;

use Livewire\Component;
use App\Models\Parcel;
use App\Models\Payment;
use App\Services\MpesaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Parcels extends Component
{
    public $parcels = [];
    public $selectedParcel = null;
    public $showParcelDetail = false;
    public $searchTerm = '';
    public $statusFilter = '';
    public $dateRange = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'overview';
    
    // Payment Modal Properties
    public $showPaymentModal = false;
    public $paymentAmount = 0;
    public $paymentMethod = 'mpesa';
    public $paymentPhone = '';
    public $paymentNotes = '';
    public $isProcessing = false;

    // M-Pesa Specific Properties
    public $checkoutRequestId = '';
    public $paymentStatus = '';
    public $paymentStatusMessage = '';
    public $paymentStatusType = '';
    public $showMpesaStatus = false;
    public $paymentStatusIcon = '';
    public $mpesaReceiptNumber = '';
    public $mpesaTransactionId = null;
    public $statusCheckCount = 0;
    public $maxStatusChecks = 60;
    public $pollingEnabled = false;

    // Payment response
    public $paymentResponse = null;
    public $paymentResponseType = '';
    public $paymentResponseMessage = '';

    // Payment Overlay Properties
    public $showPaymentOverlay = false;
    public $paymentOverlayStatus = 'loading';
    public $paymentOverlayTitle = 'Processing Payment';
    public $paymentOverlayMessage = 'Please wait while we initiate your payment...';

    protected $mpesaService;

    public function boot(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateRange' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->loadParcels();
    }

    public function loadParcels()
    {
        $query = Parcel::where('customer_id', Auth::guard('customer')->id())
            ->with(['senderTown', 'receiverTown', 'latestStatus', 'payments']);

        // Search filter
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('parcel_id', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sender_name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('receiver_name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('parcel_type', 'LIKE', '%' . $this->searchTerm . '%');
            });
        }

        // Status filter
        if (!empty($this->statusFilter)) {
            $query->where('current_status', $this->statusFilter);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $date = now();
            switch ($this->dateRange) {
                case 'today':
                    $query->whereDate('created_at', $date->today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$date->startOfWeek(), $date->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', $date->year);
                    break;
            }
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $this->parcels = $query->get();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['searchTerm', 'statusFilter', 'dateRange', 'sortBy', 'sortDirection'])) {
            $this->loadParcels();
        }
    }

    public function viewParcel($parcelId)
    {
        $this->selectedParcel = Parcel::with([
            'senderTown',
            'receiverTown',
            'statuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments',
            'latestStatus',
            'transportPartner'
        ])->findOrFail($parcelId);

        $this->activeTab = 'overview';
        $this->showParcelDetail = true;
        $this->paymentResponse = null;
        $this->showPaymentOverlay = false;
    }

    public function closeParcelDetail()
    {
        $this->showParcelDetail = false;
        $this->selectedParcel = null;
        $this->activeTab = 'overview';
        $this->showPaymentModal = false;
        $this->showPaymentOverlay = false;
        $this->resetPaymentModal();
        $this->paymentResponse = null;
        $this->stopPolling();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    // ===== PAYMENT METHODS =====

    public function openPaymentModal()
    {
        Log::info('Opening payment modal', ['parcel_id' => $this->selectedParcel?->id]);

        if (!$this->selectedParcel) {
            return;
        }

        $this->resetPaymentModal();
        $this->showPaymentOverlay = false;

        $totalPaid = $this->selectedParcel->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $this->paymentAmount = $this->selectedParcel->total_amount - $totalPaid;

        $this->paymentPhone = $this->selectedParcel->sender_phone;

        Log::info('Payment modal data prepared', [
            'remaining_amount' => $this->paymentAmount,
            'default_phone' => $this->paymentPhone
        ]);

        $this->showPaymentModal = true;
        $this->paymentResponse = null;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->showPaymentOverlay = false;
        $this->resetPaymentModal();
        $this->stopPolling();
    }

    protected function resetPaymentModal()
    {
        $this->stopPolling();
        $this->reset([
            'paymentMethod',
            'paymentNotes',
            'isProcessing',
            'checkoutRequestId',
            'paymentStatus',
            'paymentStatusMessage',
            'paymentStatusType',
            'showMpesaStatus',
            'paymentStatusIcon',
            'mpesaReceiptNumber',
            'mpesaTransactionId',
            'statusCheckCount',
            'pollingEnabled',
        ]);
        $this->showPaymentOverlay = false;
    }

    public function updatedPaymentMethod($value)
    {
        Log::info('Payment method updated', ['method' => $value]);

        if ($value === 'mpesa') {
            $this->paymentPhone = $this->selectedParcel?->sender_phone ?? '';
        }
    }

    public function processPayment()
    {
        if (!$this->selectedParcel) {
            $this->setPaymentResponse('error', 'No parcel selected.');
            return;
        }

        $totalPaid = $this->selectedParcel->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $remainingBalance = $this->selectedParcel->total_amount - $totalPaid;

        $this->validate([
            'paymentAmount' => 'required|numeric|min:1|max:' . $remainingBalance,
            'paymentMethod' => 'required|in:mpesa',
            // 'paymentPhone' => [
            //     'required_if:paymentMethod,mpesa',
            //     'regex:/^(\+254|0)[0-9]{9}$/',
            // ],
        ], [
            'paymentAmount.required' => 'Please enter payment amount',
            'paymentAmount.min' => 'Amount must be at least 1',
            'paymentAmount.max' => 'Amount cannot exceed the remaining balance of ' . number_format($remainingBalance, 2),
            'paymentMethod.required' => 'Please select a payment method',
            // 'paymentPhone.required_if' => 'Phone number is required for M-Pesa payments',
            // 'paymentPhone.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678)',
        ]);

        $this->isProcessing = true;
        $this->paymentResponse = null;
        
        // Show overlay
        $this->showPaymentOverlay = true;
        $this->paymentOverlayStatus = 'loading';
        $this->paymentOverlayTitle = 'Initiating Payment';
        $this->paymentOverlayMessage = 'Please wait while we initiate your payment...';

        try {
            if ($this->paymentMethod === 'mpesa') {
                $this->processMpesaPayment();
            }
        } catch (\Exception $e) {
            Log::error('Payment processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->paymentStatus = 'error';
            $this->paymentStatusType = 'danger';
            $this->paymentStatusIcon = 'bi-exclamation-triangle';
            $this->paymentStatusMessage = 'Failed to process payment: ' . $e->getMessage();
            $this->showMpesaStatus = true;
            $this->setPaymentResponse('error', $e->getMessage());
            
            $this->paymentOverlayStatus = 'failed';
            $this->paymentOverlayTitle = 'Payment Failed';
            $this->paymentOverlayMessage = $e->getMessage();
        } finally {
            $this->isProcessing = false;
        }
    }

    protected function processMpesaPayment()
    {
        DB::beginTransaction();

        try {
            // Format phone number for M-Pesa API
            $phone = $this->formatPhoneNumber($this->paymentPhone);
            
            $accountReference = $this->selectedParcel->parcel_id;
            $transactionDesc = 'Payment for parcel No:' . $this->selectedParcel->parcel_id;

            Log::info('Initiating M-Pesa STK Push', [
                'phone' => $phone,
                'amount' => $this->paymentAmount,
                'parcel_id' => $this->selectedParcel->parcel_id
            ]);

            $result = $this->mpesaService->stkPush(
                $phone,
                $this->paymentAmount,
                $accountReference,
                $transactionDesc,
                $this->selectedParcel->id,
                Auth::guard('customer')->id(),
            );

            Log::info('M-Pesa STK Push result', $result);

            if ($result['success']) {
                $this->checkoutRequestId = $result['checkout_request_id'];
                $this->mpesaTransactionId = $result['transaction_id'] ?? null;

                // Create payment record
                $payment = Payment::create([
                    'reference_number' => $this->generateReferenceNumber(),
                    'parcel_id' => $this->selectedParcel->id,
                    'amount' => $this->paymentAmount,
                    'payment_method' => 'mpesa',
                    'payment_date' => now(),
                    'status' => 'pending',
                    'phone' => $this->paymentPhone,
                    'notes' => $this->paymentNotes,
                    'mpesa_transaction_id' => $this->mpesaTransactionId,
                    'checkout_request_id' => $this->checkoutRequestId,
                    'paid_by' => Auth::guard('customer')->id(),
                ]);

                Log::info('Payment record created', ['payment_id' => $payment->id]);

                DB::commit();

                $this->paymentStatus = 'waiting_pin';
                $this->paymentStatusType = 'info';
                $this->paymentStatusIcon = 'bi-phone';
                $this->paymentStatusMessage = 'STK Push sent! Please check your phone and enter your M-Pesa PIN to complete payment.';
                $this->showMpesaStatus = true;
                $this->statusCheckCount = 0;
                
                $this->paymentOverlayStatus = 'waiting';
                $this->paymentOverlayTitle = 'Check Your Phone';
                $this->paymentOverlayMessage = 'Enter your M-PESA PIN to complete the payment.';

                // Start polling for payment status
                $this->startPolling();

                Log::info('M-Pesa payment initiated successfully', [
                    'checkout_request_id' => $this->checkoutRequestId,
                    'transaction_id' => $result['transaction_id'] ?? null
                ]);
            } else {
                DB::rollBack();

                Log::error('M-Pesa initiation failed', [
                    'message' => $result['message'],
                    'error_code' => $result['error_code'] ?? null
                ]);

                $this->paymentStatus = 'initiation_failed';
                $this->paymentStatusType = 'danger';
                $this->paymentStatusIcon = 'bi-exclamation-circle';
                $this->paymentStatusMessage = $result['message'] ?? 'Failed to initiate payment.';
                $this->showMpesaStatus = true;
                $this->stopPolling();
                $this->setPaymentResponse('error', $result['message'] ?? 'Payment initiation failed.');
                
                $this->paymentOverlayStatus = 'failed';
                $this->paymentOverlayTitle = 'Payment Initiation Failed';
                $this->paymentOverlayMessage = $result['message'] ?? 'Please try again.';
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->stopPolling();
            Log::error('M-Pesa payment processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->paymentOverlayStatus = 'failed';
            $this->paymentOverlayTitle = 'Error';
            $this->paymentOverlayMessage = $e->getMessage();
            throw $e;
        }
    }

    /**
     * Format phone number for M-Pesa API
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If starts with 0, replace with 254
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        // If starts with +254, remove the +
        if (substr($phone, 0, 4) === '254') {
            $phone = '254' . substr($phone, 3);
        }
        
        return $phone;
    }

    public function checkMpesaStatus()
    {
        if (!$this->pollingEnabled) {
            Log::info('Polling is disabled, skipping status check');
            return;
        }

        if (!$this->checkoutRequestId) {
            Log::warning('No checkout request ID for status check');
            $this->stopPolling();
            return;
        }

        $this->statusCheckCount++;

        Log::info('Checking M-Pesa payment status', [
            'checkout_request_id' => $this->checkoutRequestId,
            'attempt' => $this->statusCheckCount
        ]);

        try {
            $result = $this->mpesaService->checkStkStatus($this->checkoutRequestId);

            Log::info('M-Pesa status check result', [
                'result_code' => $result['result_code'] ?? null,
                'result_desc' => $result['result_desc'] ?? null
            ]);

            if (isset($result['result_code'])) {
                $resultCode = $result['result_code'];

                switch ($resultCode) {
                    case 0:
                        $this->handlePaymentSuccess($result);
                        $this->stopPolling();
                        break;

                    case 1032:
                        $this->handlePaymentCancelled($result);
                        $this->stopPolling();
                        break;

                    case 1037:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentTimeout($result);
                            $this->stopPolling();
                        }
                        break;

                    case 1:
                        $this->handlePaymentFailed($result, 'insufficient_funds');
                        $this->stopPolling();
                        break;

                    case 1019:
                        $this->handlePaymentFailed($result, 'wrong_pin');
                        $this->stopPolling();
                        break;

                    case 1036:
                    case 2001:
                    case 1031:
                    case 1026:
                        $this->handlePaymentFailed($result, 'failed');
                        $this->stopPolling();
                        break;

                    default:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentUnknown($result);
                            $this->stopPolling();
                        }
                        break;
                }
            } else {
                // No result code yet, keep polling
                $this->paymentOverlayMessage = 'Waiting for confirmation... (' . $this->statusCheckCount . '/' . $this->maxStatusChecks . ')';
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $this->checkoutRequestId
            ]);

            if ($this->statusCheckCount >= $this->maxStatusChecks) {
                $this->paymentStatus = 'check_failed';
                $this->paymentStatusType = 'warning';
                $this->paymentStatusIcon = 'bi-exclamation-triangle';
                $this->paymentStatusMessage = 'Unable to verify payment status. Please check transaction history or contact support.';
                $this->stopPolling();
                $this->setPaymentResponse('warning', 'Unable to verify payment status. Please check transaction history.');
                
                $this->paymentOverlayStatus = 'failed';
                $this->paymentOverlayTitle = 'Payment Status Unknown';
                $this->paymentOverlayMessage = 'Unable to verify payment status. Please check transaction history.';
            }
        }
    }

    public function startPolling()
    {
        $this->pollingEnabled = true;
        $this->dispatch('start-mpesa-polling');
    }

    public function stopPolling()
    {
        $this->pollingEnabled = false;
        $this->dispatch('stop-mpesa-polling');
    }

    private function handlePaymentSuccess($result)
    {
        $this->paymentStatus = 'success';
        $this->paymentStatusType = 'success';
        $this->paymentStatusIcon = 'bi-check-circle-fill';
        $this->paymentStatusMessage = $result['user_message'] ?? 'Payment completed successfully!';

        if (isset($result['response']['CallbackMetadata']['Item'])) {
            foreach ($result['response']['CallbackMetadata']['Item'] as $item) {
                if ($item['Name'] === 'MpesaReceiptNumber') {
                    $this->mpesaReceiptNumber = $item['Value'];
                    break;
                }
            }
        }

        // Update payment record
        if ($this->mpesaTransactionId) {
            Payment::where('mpesa_transaction_id', $this->mpesaTransactionId)
                ->update([
                    'status' => 'completed',
                    'mpesa_receipt_number' => $this->mpesaReceiptNumber,
                ]);
        }

        // Update parcel payment status
        $this->updateParcelPaymentStatus();
        $this->loadParcels();
        
        $this->setPaymentResponse('success', 'Payment completed successfully! Receipt: ' . $this->mpesaReceiptNumber);
        
        $this->paymentOverlayStatus = 'success';
        $this->paymentOverlayTitle = 'Payment Successful!';
        $this->paymentOverlayMessage = 'Payment completed successfully! Receipt: ' . $this->mpesaReceiptNumber;
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Payment completed successfully!'
        ]);
        
        // Close overlay after 3 seconds
        $this->dispatch('close-overlay-after', ['seconds' => 3]);
    }

    private function handlePaymentCancelled($result)
    {
        $this->paymentStatus = 'cancelled';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-x-circle';
        $this->paymentStatusMessage = $result['user_message'] ?? 'Transaction cancelled. You did not enter your M-Pesa PIN.';

        $this->updatePaymentRecord('failed', 'Transaction cancelled by user');
        $this->setPaymentResponse('warning', 'Payment cancelled. Please try again.');
        
        $this->paymentOverlayStatus = 'failed';
        $this->paymentOverlayTitle = 'Payment Cancelled';
        $this->paymentOverlayMessage = 'You cancelled the payment. Please try again.';
    }

    private function handlePaymentTimeout($result)
    {
        $this->paymentStatus = 'timeout';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-clock-history';
        $this->paymentStatusMessage = $result['user_message'] ?? 'Payment timeout. You took too long to enter your PIN. Please try again.';

        $this->updatePaymentRecord('failed', 'Payment timeout');
        $this->setPaymentResponse('warning', 'Payment timeout. Please try again.');
        
        $this->paymentOverlayStatus = 'failed';
        $this->paymentOverlayTitle = 'Payment Timeout';
        $this->paymentOverlayMessage = 'You took too long to enter your PIN. Please try again.';
    }

    private function handlePaymentFailed($result, $failureType)
    {
        $failureMessages = [
            'insufficient_funds' => 'Insufficient funds in your M-Pesa account. Please ensure you have enough balance and try again.',
            'wrong_pin' => 'Wrong PIN entered. Please check your M-Pesa PIN and try again.',
            'failed' => 'Payment failed. Please try again or use a different payment method.'
        ];

        $this->paymentStatus = 'failed';
        $this->paymentStatusType = 'danger';
        $this->paymentStatusIcon = 'bi-exclamation-circle';
        $this->paymentStatusMessage = $result['user_message'] ?? $failureMessages[$failureType];

        $this->updatePaymentRecord('failed', $this->paymentStatusMessage);
        $this->setPaymentResponse('error', $failureMessages[$failureType]);
        
        $this->paymentOverlayStatus = 'failed';
        $this->paymentOverlayTitle = 'Payment Failed';
        $this->paymentOverlayMessage = $failureMessages[$failureType];
    }

    private function handlePaymentUnknown($result)
    {
        $this->paymentStatus = 'unknown';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-question-circle';
        $this->paymentStatusMessage = 'Payment status unknown. Please check transaction history or contact support.';

        $this->setPaymentResponse('warning', 'Payment status unknown. Please check transaction history.');
        
        $this->paymentOverlayStatus = 'failed';
        $this->paymentOverlayTitle = 'Payment Status Unknown';
        $this->paymentOverlayMessage = 'Please check transaction history or contact support.';
    }

    private function updatePaymentRecord($status, $notes)
    {
        if ($this->mpesaTransactionId) {
            Payment::where('mpesa_transaction_id', $this->mpesaTransactionId)
                ->update([
                    'status' => $status,
                    'notes' => 'M-Pesa: ' . $notes
                ]);
        }
    }

    private function updateParcelPaymentStatus()
    {
        if (!$this->selectedParcel) return;

        $totalPaid = Payment::where('parcel_id', $this->selectedParcel->id)
            ->where('status', 'completed')
            ->sum('amount');

        if ($totalPaid >= $this->selectedParcel->total_amount) {
            $this->selectedParcel->payment_status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->selectedParcel->payment_status = 'partially_paid';
        } else {
            $this->selectedParcel->payment_status = 'pending';
        }

        $this->selectedParcel->save();

        // Reload the parcel
        $this->selectedParcel = Parcel::with([
            'senderTown',
            'receiverTown',
            'statuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments',
            'latestStatus',
            'transportPartner'
        ])->find($this->selectedParcel->id);
    }

    protected function generateReferenceNumber()
    {
        $prefix = 'PAY';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . $timestamp . $random;
    }

    public function retryPayment()
    {
        $this->resetPaymentModal();
        $this->showPaymentModal = true;
        $this->showMpesaStatus = false;
        $this->paymentResponse = null;
        $this->showPaymentOverlay = false;
    }

    public function closePaymentOverlay()
    {
        $this->showPaymentOverlay = false;
        $this->paymentOverlayStatus = 'loading';
        $this->paymentOverlayTitle = 'Processing Payment';
        $this->paymentOverlayMessage = 'Please wait while we initiate your payment...';
        $this->stopPolling();
    }

    public function checkPaymentStatus()
    {
        if (!$this->selectedParcel) {
            return;
        }

        // Refresh the parcel data
        $this->selectedParcel = Parcel::with([
            'senderTown',
            'receiverTown',
            'statuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments',
            'latestStatus',
            'transportPartner'
        ])->find($this->selectedParcel->id);
        
        $this->loadParcels();
        
        if ($this->selectedParcel->payment_status === 'paid') {
            $this->setPaymentResponse('success', 'Payment confirmed! Your parcel is now booked.');
        } else if ($this->selectedParcel->payment_status === 'partially_paid') {
            $remaining = $this->selectedParcel->total_amount - $this->selectedParcel->payments()->where('status', 'completed')->sum('amount');
            $this->setPaymentResponse('warning', 'Partial payment received. Remaining balance: KES ' . number_format($remaining, 2));
        } else {
            $this->setPaymentResponse('info', 'No payment found. Please complete the payment.');
        }
    }

    private function setPaymentResponse($type, $message)
    {
        $this->paymentResponse = $type;
        $this->paymentResponseType = $type;
        $this->paymentResponseMessage = $message;
    }

    // ===== STATUS HELPERS =====

    public function getStatusColor($status)
    {
        return match ($status) {
            'created' => 'secondary',
            'booked' => 'info',
            'accepted' => 'primary',
            'assigned' => 'warning',
            'in_transit' => 'primary',
            'pending' => 'warning',
            'warehouse' => 'info',
            'arrived_at_destination' => 'success',
            'picked' => 'info',
            'delivered' => 'success',
            'failed' => 'danger',
            'returned' => 'warning',
            default => 'secondary',
        };
    }

    public function getStatusIcon($status)
    {
        return match ($status) {
            'created' => 'bi-clock',
            'booked' => 'bi-check-circle',
            'accepted' => 'bi-check-circle',
            'assigned' => 'bi-person-check',
            'in_transit' => 'bi-truck',
            'pending' => 'bi-clock-history',
            'warehouse' => 'bi-building',
            'arrived_at_destination' => 'bi-geo-alt',
            'picked' => 'bi-box-arrow-up',
            'delivered' => 'bi-check-circle-fill',
            'failed' => 'bi-x-circle',
            'returned' => 'bi-arrow-return-left',
            default => 'bi-question-circle',
        };
    }

    public function getStatusLabel($status)
    {
        return str_replace('_', ' ', ucfirst($status));
    }

    public function getPaymentStatusBadge($status)
    {
        $badges = [
            'paid' => ['color' => 'success', 'icon' => 'bi-check-circle-fill'],
            'pending' => ['color' => 'warning', 'icon' => 'bi-clock-history'],
            'partially_paid' => ['color' => 'info', 'icon' => 'bi-hourglass-split'],
            'failed' => ['color' => 'danger', 'icon' => 'bi-exclamation-circle'],
            'refunded' => ['color' => 'secondary', 'icon' => 'bi-arrow-return-left'],
        ];

        return $badges[$status] ?? ['color' => 'secondary', 'icon' => 'bi-question-circle'];
    }

    public function render()
    {
        return view('livewire.clients.parcels.parcels');
    }
}