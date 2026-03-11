<?php

namespace App\Livewire\Partners\Parcels;

use App\Models\Parcel;
use App\Models\Payment;
use App\Models\MpesaTransaction;
use App\Services\MpesaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Exception;

class ViewParcel extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $parcelId;
    public $parcel;
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
    public $paymentStatusType = ''; // 'info', 'success', 'warning', 'danger'
    public $showMpesaStatus = false;
    public $paymentStatusIcon = '';
    public $mpesaReceiptNumber = '';
    public $mpesaTransactionId = null;
    public $statusCheckCount = 0;
    public $maxStatusChecks = 60; // 5 minutes (60 * 5 seconds)

    // Filters
    public $paymentStatusFilter = '';
    public $dateRange = '';

    // Driver and Pickup Properties
    public $selectedDriver = null;
    public $driverCode = '';
    public $driver_code = '';
    public $driverVerificationError = '';
    public $showDriverVerificationModal = false;
    public $showPickUpModal = false;
    public $latestStatus;
    public $pickup_person_type = 'owner';
    public $picker_name = '';
    public $picker_phone = '';
    public $picker_id_number = '';
    public $picker_relationship = '';
    public $picker_reason = '';
    public $confirm_terms = false;
    public $pickup_code = '';
    public $pickupVerificationError = '';

    protected $mpesaService;

    public function boot(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    public function mount($id)
    {
        $this->parcelId = $id;
        $this->loadParcel();
        $this->latestStatus = $this->parcel->statuses()
            ->whereNotNull('driver_id')
            ->with('driver')
            ->latest()
            ->first();
    }

    public function resetPickupForm()
    {
        $this->pickup_person_type = 'owner';
        $this->picker_name = '';
        $this->picker_phone = '';
        $this->picker_id_number = '';
        $this->picker_relationship = '';
        $this->picker_reason = '';
        $this->pickup_code = '';
        $this->pickupVerificationError = '';
        $this->confirm_terms = false;
    }

    protected function rulesForPickup()
    {
        $rules = [
            'pickup_code' => 'required|string|size:6',
            'pickup_person_type' => 'required|in:owner,other',
            'confirm_terms' => 'required|accepted',
        ];

        if ($this->pickup_person_type === 'other') {
            $rules = array_merge($rules, [
                'picker_name' => 'required|string|min:3|max:255',
                'picker_phone' => 'required|string|min:10|max:20',
                'picker_id_number' => 'required|string|min:3|max:50',
                'picker_relationship' => 'nullable|string|max:100',
                'picker_reason' => 'nullable|string|max:500',
            ]);
        }

        return $rules;
    }

    public function verifyPickup()
    {
        $this->validate($this->rulesForPickup());

        try {
            DB::beginTransaction();

            $this->latestStatus = $this->parcel->statuses()
                ->whereNotNull('driver_id')
                ->with('driver')
                ->latest()
                ->first();

            $this->selectedDriver = $this->latestStatus?->driver;

            $verified = $this->verifyPickupCode($this->pickup_code);

            if (!$verified) {
                $this->pickupVerificationError = 'Invalid verification code';
                return;
            }

            $pickupData = [
                'pickup_person_type' => $this->pickup_person_type,
                'pickup_verified_by' => Auth::guard('partner')->user()->id,
                'pickup_verified_at' => Carbon::now(),
                'pickup_code' => $this->pickup_code,
            ];

            if ($this->pickup_person_type === 'owner') {
                $pickupData['pickup_person_name'] = $this->parcel->receiver_name;
                $pickupData['pickup_person_phone'] = $this->parcel->receiver_phone;
                $pickupData['pickup_person_id'] = $this->parcel->receiver_id_number;
            } else {
                $pickupData['pickup_person_name'] = $this->picker_name;
                $pickupData['pickup_person_phone'] = $this->picker_phone;
                $pickupData['pickup_person_id'] = $this->picker_id_number;
                $pickupData['pickup_person_relationship'] = $this->picker_relationship;
                $pickupData['pickup_reason'] = $this->picker_reason;
            }

            $this->parcel->update([
                'current_status' => Parcel::STATUS_PICKED,
            ]);

            $this->parcel->parcelPickUp()->create($pickupData);

            $notes = "Parcel picked up by: ";
            if ($this->pickup_person_type === 'owner') {
                $notes .= "Owner - {$this->parcel->receiver_name}";
            } else {
                $notes .= "{$this->picker_name} (ID: {$this->picker_id_number})";
                if ($this->picker_relationship) {
                    $notes .= " - Relationship: {$this->picker_relationship}";
                }
            }

            $this->parcel->updateParcelStatus(
                Parcel::STATUS_PICKED,
                Auth::guard('partner')->user()->id,
                'pha',
                $notes,
                NULL,
                NULL,
            );

            DB::commit();

            $this->closePickUpModal();
            session()->flash('success', 'Parcel pickup verified successfully');
            $this->dispatch('parcel-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Pickup verification failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to verify pickup: ' . $e->getMessage());
        }
    }

    private function verifyPickupCode($code)
    {
        // Implement your verification logic here
        return true;
    }

    public function openDriverVerificationModal()
    {
        $this->selectedDriver = $this->latestStatus?->driver;
        $this->showDriverVerificationModal = true;
    }

    public function closeDriverVerificationModal()
    {
        $this->showDriverVerificationModal = false;
    }

    public function closePickUpModal()
    {
        $this->showPickUpModal = false;
        $this->resetPickupForm();
    }

    public function openPickUpModal()
    {
        $this->resetPickupForm();
        $this->showPickUpModal = true;
    }

    public function verifyDriverCode()
    {
        $this->validate([
            'driver_code' => 'required'
        ]);

        if ($this->latestStatus->otp == $this->driver_code) {
            DB::beginTransaction();

            $this->latestStatus->otp_verified = true;
            $this->latestStatus->save();

            $this->parcel->updateParcelStatus(
                Parcel::STATUS_IN_TRANSIT,
                Auth::guard('partner')->user()->id,
                'pha',
                'Parcel picked by driver',
                $this->selectedDriver->id,
                $this->parcel->generateDeliveryOtp(),
            );

            $this->parcel->current_status = Parcel::STATUS_IN_TRANSIT;
            $this->parcel->driver_id = $this->selectedDriver->id;
            $this->parcel->save();
            DB::commit();

            $this->closeDriverVerificationModal();
        } else {
            $this->driverVerificationError = "Could not verify the code. Please check again!";
        }
    }

    public function receiveParcelFromDriver()
    {
        $this->latestStatus = $this->parcel->statuses()
            ->whereNotNull('driver_id')
            ->with('driver')
            ->latest()
            ->first();

        $this->selectedDriver = $this->latestStatus?->driver;

        DB::beginTransaction();

        $this->latestStatus->otp_verified = true;
        $this->latestStatus->save();

        $this->parcel->updateParcelStatus(
            Parcel::STATUS_ARRIVED_AT_DESTINATION,
            Auth::guard('partner')->user()->id,
            'pha',
            'Parcel delivered to pick-up point',
            $this->selectedDriver->id,
            $this->parcel->generateDeliveryOtp(),
        );

        $this->parcel->current_status = Parcel::STATUS_ARRIVED_AT_DESTINATION;
        $this->parcel->driver_id = $this->selectedDriver->id;
        $this->parcel->save();
        DB::commit();
    }

    protected function loadParcel()
    {
        Log::info('Loading parcel data', ['parcel_id' => $this->parcelId]);
        $this->parcel = Parcel::with([
            'customer',
            'sender',
            'receiver',
            'driver',
            'payments' => function ($query) {
                $query->latest();
            },
        ])->findOrFail($this->parcelId);
        Log::info('Parcel loaded', [
            'parcel_number' => $this->parcel->parcel_number,
            'total_amount' => $this->parcel->total_amount,
            'payment_status' => $this->parcel->payment_status
        ]);
    }

    public function render()
    {
        Log::info('Rendering ViewParcel', [
            'parcel_id' => $this->parcelId,
            'active_tab' => $this->activeTab
        ]);

        return view('livewire.partners.parcels.view-parcel', [
            'parcel' => $this->parcel,
            'payments' => $this->getPayments(),
            'paymentMethods' => [
                'cash' => 'Cash',
                'mpesa' => 'M-Pesa',
                'card' => 'Card',
                'bank_transfer' => 'Bank Transfer',
                'wallet' => 'Wallet',
            ],
        ]);
    }

    protected function getPayments()
    {
        $query = Payment::where('parcel_id', $this->parcelId)
            ->orderBy('created_at', 'desc');

        if ($this->paymentStatusFilter) {
            $query->where('status', $this->paymentStatusFilter);
            Log::info('Applied payment status filter', ['filter' => $this->paymentStatusFilter]);
        }

        return $query->paginate(10);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        Log::info('Tab changed', ['new_tab' => $tab]);
    }

    public function openPaymentModal()
    {
        Log::info('Opening payment modal', ['parcel_id' => $this->parcelId]);

        $this->resetPaymentModal();

        $totalPaid = $this->parcel->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $this->paymentAmount = $this->parcel->total_amount - $totalPaid;
        $this->paymentPhone = $this->parcel->sender_phone;

        Log::info('Payment modal data prepared', [
            'remaining_amount' => $this->paymentAmount,
            'default_phone' => $this->paymentPhone
        ]);

        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->resetPaymentModal();

        // Stop polling
        $this->dispatch('stop-mpesa-polling');
    }

    protected function resetPaymentModal()
    {
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
        ]);
    }

    public function updatedPaymentMethod($value)
    {
        Log::info('Payment method updated', ['method' => $value]);

        if ($value === 'mpesa') {
            $this->paymentPhone = $this->parcel->sender_phone;
            Log::info('M-Pesa selected, phone set', ['phone' => $this->paymentPhone]);
        }
    }

    public function processPayment()
    {
        $totalPaid = $this->parcel->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $remainingBalance = $this->parcel->total_amount - $totalPaid;
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1|max:' . $remainingBalance,
            'paymentMethod' => 'required|in:cash,mpesa,card,bank_transfer,wallet',
            'paymentPhone' => [
                'required_if:paymentMethod,mpesa',
                'regex:/^(\+254|0)[0-9]{9}$/',
            ],
        ], [
            'paymentAmount.required' => 'Please enter payment amount',
            'paymentAmount.min' => 'Amount must be at least 1',
            'paymentAmount.max' => 'Amount cannot exceed the remaining balance of ' . number_format($remainingBalance, 2),
            'paymentMethod.required' => 'Please select a payment method',
            'paymentPhone.required_if' => 'Phone number is required for M-Pesa payments',
            'paymentPhone.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678)',
        ]);

        $this->isProcessing = true;

        try {
            if ($this->paymentMethod === 'mpesa') {
                $this->processMpesaPayment();
            } else {
                $this->processOtherPayment();
            }
        } catch (Exception $e) {
            Log::error('Payment processing exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->paymentStatus = 'error';
            $this->paymentStatusType = 'danger';
            $this->paymentStatusIcon = 'bi-exclamation-triangle';
            $this->paymentStatusMessage = 'Failed to process payment: ' . $e->getMessage();
            $this->showMpesaStatus = true;
        } finally {
            $this->isProcessing = false;
            Log::info('=== Payment Processing Completed ===');
        }
    }

    protected function processMpesaPayment()
    {
        DB::beginTransaction();

        try {
            $accountReference = $this->parcel->parcel_id;
            $transactionDesc = 'Payment for parcel No:' . $this->parcel->parcel_id;

            $result = $this->mpesaService->stkPush(
                $this->paymentPhone,
                $this->paymentAmount,
                $accountReference,
                $transactionDesc,
                $this->parcelId,
                Auth::guard('partner')->id()
            );

            Log::info('M-Pesa STK Push result', $result);

            if ($result['success']) {
                $this->checkoutRequestId = $result['checkout_request_id'];
                $this->mpesaTransactionId = $result['transaction_id'];

                DB::commit();

                $this->paymentStatus = 'waiting_pin';
                $this->paymentStatusType = 'info';
                $this->paymentStatusIcon = 'bi-phone';
                $this->paymentStatusMessage = 'STK Push sent! Please check your phone and enter your M-Pesa PIN to complete payment.';
                $this->showMpesaStatus = true;
                $this->statusCheckCount = 0;

                $this->dispatch('start-mpesa-polling');

                Log::info('M-Pesa payment initiated successfully', [
                    'checkout_request_id' => $this->checkoutRequestId,
                    'transaction_id' => $result['transaction_id']
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
                $this->paymentStatusMessage = $result['message'];
                $this->showMpesaStatus = true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function processOtherPayment()
    {
        Log::info('Processing non-M-Pesa payment', [
            'method' => $this->paymentMethod,
            'amount' => $this->paymentAmount
        ]);

        DB::beginTransaction();

        try {
            $payment = Payment::create([
                'reference_number' => $this->generateReferenceNumber(),
                'parcel_id' => $this->parcelId,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => now(),
                'status' => 'completed',
                'phone' => $this->paymentMethod === 'mpesa' ? $this->paymentPhone : null,
                'notes' => $this->paymentNotes,
                'paid_by' => Auth::guard('partner')->id(),
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference_number
            ]);

            $this->updateParcelPaymentStatus();

            DB::commit();

            Log::info('Non-M-Pesa payment completed successfully');

            session()->flash('success', 'Payment of ' . number_format($this->paymentAmount, 2) . ' recorded successfully!');
            $this->closePaymentModal();
            $this->loadParcel();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Non-M-Pesa payment failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function generateReferenceNumber()
    {
        $prefix = 'PAY';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . $timestamp . $random;
    }

    public function checkMpesaStatus()
    {

        Log::info("Polling");
        if (!$this->checkoutRequestId) {
            Log::warning('No checkout request ID for status check');
            return;
        }

        $this->statusCheckCount++;

        Log::info('Checking M-Pesa payment status', [
            'checkout_request_id' => $this->checkoutRequestId,
            'attempt' => $this->statusCheckCount
        ]);

        try {
            $result = $this->mpesaService->checkStkStatus($this->checkoutRequestId);

            if (isset($result['result_code'])) {
                $resultCode = $result['result_code'];

                switch ($resultCode) {
                    case 0:
                        $this->handlePaymentSuccess($result);
                        break;

                    case 1032:
                        $this->handlePaymentCancelled($result);
                        break;

                    case 1037:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentTimeout($result);
                        }
                        break;

                    case 1:
                        $this->handlePaymentFailed($result, 'insufficient_funds');
                        break;

                    case 1019:
                        $this->handlePaymentFailed($result, 'wrong_pin');
                        break;

                    case 1036:
                    case 2001:
                    case 1031:
                    case 1026:
                        $this->handlePaymentFailed($result, 'failed');
                        break;

                    default:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentUnknown($result);
                        }
                        break;
                }
            }
        } catch (Exception $e) {
            Log::error('M-Pesa status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $this->checkoutRequestId
            ]);

            if ($this->statusCheckCount >= $this->maxStatusChecks) {
                $this->paymentStatus = 'check_failed';
                $this->paymentStatusType = 'warning';
                $this->paymentStatusIcon = 'bi-exclamation-triangle';
                $this->paymentStatusMessage = 'Unable to verify payment status. Please check transaction history or contact support.';
                $this->dispatch('stop-mpesa-polling');
            }
        }
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

        $this->loadParcel();
        $this->dispatch('stop-mpesa-polling');
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Payment completed successfully!'
        ]);
    }

    private function handlePaymentCancelled($result)
    {
        $this->paymentStatus = 'cancelled';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-x-circle';
        $this->paymentStatusMessage = $result['user_message'] ?? 'Transaction cancelled. You did not enter your M-Pesa PIN.';

        $this->updatePaymentRecord('failed', 'Transaction cancelled by user');
        $this->dispatch('stop-mpesa-polling');
    }

    private function handlePaymentTimeout($result)
    {
        $this->paymentStatus = 'timeout';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-clock-history';
        $this->paymentStatusMessage = $result['user_message'] ?? 'Payment timeout. You took too long to enter your PIN. Please try again.';

        $this->updatePaymentRecord('failed', 'Payment timeout');
        $this->dispatch('stop-mpesa-polling');
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
        $this->dispatch('stop-mpesa-polling');
    }

    private function handlePaymentUnknown($result)
    {
        $this->paymentStatus = 'unknown';
        $this->paymentStatusType = 'warning';
        $this->paymentStatusIcon = 'bi-question-circle';
        $this->paymentStatusMessage = 'Payment status unknown. Please check transaction history or contact support.';

        $this->dispatch('stop-mpesa-polling');
    }

    private function updatePaymentRecord($status, $notes)
    {
        if ($this->mpesaTransactionId) {
            $payment = Payment::where('mpesa_transaction_id', $this->mpesaTransactionId)->first();
            if ($payment) {
                $payment->update([
                    'status' => $status,
                    'notes' => 'M-Pesa: ' . $notes
                ]);
                Log::info('Payment record updated', [
                    'payment_id' => $payment->id,
                    'status' => $status
                ]);
            }
        }
    }

    protected function updateParcelPaymentStatus()
    {
        Log::info('Updating parcel payment status', ['parcel_id' => $this->parcelId]);

        $totalPaid = Payment::where('parcel_id', $this->parcelId)
            ->where('status', 'completed')
            ->sum('amount');

        Log::info('Payment totals calculated', [
            'total_paid' => $totalPaid,
            'total_amount' => $this->parcel->total_amount
        ]);

        if ($totalPaid >= $this->parcel->total_amount) {
            $this->parcel->payment_status = 'paid';
            Log::info('Parcel marked as fully paid');
        } elseif ($totalPaid > 0) {
            $this->parcel->payment_status = 'partially_paid';
            Log::info('Parcel marked as partially paid');
        } else {
            $this->parcel->payment_status = 'pending';
            Log::info('Parcel marked as pending');
        }

        $this->parcel->save();

        Log::info('Parcel payment status updated', [
            'parcel_id' => $this->parcelId,
            'new_status' => $this->parcel->payment_status
        ]);
    }

    public function retryPayment()
    {
        $this->resetPaymentModal();
        $this->showPaymentModal = true;
        $this->showMpesaStatus = false;
    }

    public function tryOtherMethod()
    {
        $this->paymentMethod = 'cash';
        $this->resetPaymentModal();
        $this->showPaymentModal = true;
        $this->showMpesaStatus = false;
    }

    public function getStatusBadge($status)
    {
        $badges = [
            'pending' => ['color' => 'secondary', 'icon' => 'bi-clock'],
            'assigned' => ['color' => 'info', 'icon' => 'bi-person-check'],
            'picked_up' => ['color' => 'primary', 'icon' => 'bi-box-seam'],
            'in_transit' => ['color' => 'warning', 'icon' => 'bi-truck'],
            'out_for_delivery' => ['color' => 'info', 'icon' => 'bi-bicycle'],
            'delivered' => ['color' => 'success', 'icon' => 'bi-check-circle'],
            'cancelled' => ['color' => 'danger', 'icon' => 'bi-x-circle'],
            'on_hold' => ['color' => 'dark', 'icon' => 'bi-pause-circle'],
            'returned' => ['color' => 'warning', 'icon' => 'bi-arrow-return-left'],
        ];

        return $badges[$status] ?? ['color' => 'secondary', 'icon' => 'bi-question-circle'];
    }

    public function getPaymentStatusBadge($status)
    {
        $badges = [
            'completed' => ['color' => 'success', 'icon' => 'bi-check-circle'],
            'pending' => ['color' => 'warning', 'icon' => 'bi-clock'],
            'partially_paid' => ['color' => 'info', 'icon' => 'bi-half'],
            'failed' => ['color' => 'danger', 'icon' => 'bi-exclamation-circle'],
            'refunded' => ['color' => 'secondary', 'icon' => 'bi-arrow-return-left'],
        ];

        return $badges[$status] ?? ['color' => 'secondary', 'icon' => 'bi-question-circle'];
    }

    public function getBookingTypeBadge($type)
    {
        return match ($type) {
            'instant' => ['color' => 'success', 'icon' => 'bi-lightning'],
            'scheduled' => ['color' => 'info', 'icon' => 'bi-calendar'],
            'bulk' => ['color' => 'primary', 'icon' => 'bi-stack'],
            default => ['color' => 'secondary', 'icon' => 'bi-question-circle'],
        };
    }
}
