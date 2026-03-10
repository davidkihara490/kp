<?php
// app/Livewire/Partners/Parcels/ViewParcel.php

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
    public $showMpesaStatus = false;
    // Filters
    public $paymentStatusFilter = '';
    public $dateRange = '';
    protected $mpesaService;
    public $selectedDriver = null;
    public $driverCode = '';
    public $driver_code = '';
    public $driverVerificationError = '';
    public $showDriverVerificationModal = false;
    public $showPickUpModal = false;
    public $latestStatus;
    public $pickup_person_type = 'owner'; // Default to owner
    public $picker_name = '';
    public $picker_phone = '';
    public $picker_id_number = '';
    public $picker_relationship = '';
    public $picker_reason = '';
    public $confirm_terms = false;
    public $pickup_code = '';
    public $pickupVerificationError = '';

    public function boot(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
        Log::info('ViewParcel component booted', ['parcel_id' => $this->parcelId]);
    }

    public function mount($id)
    {
        $this->parcelId = $id;
        Log::info('ViewParcel mounted', ['parcel_id' => $id]);
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

    // Add validation rules
protected function rulesForPickup()
{
    $rules = [
        'pickup_code' => 'required|string|size:6',
        'pickup_person_type' => 'required|in:owner,other',
        'confirm_terms' => 'required|accepted',
    ];
    
    // Add conditional validation for other person
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

// Add the verifyPickup method
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



        // Verify the pickup code
        $verified = $this->verifyPickupCode($this->pickup_code);
        
        if (!$verified) {
            $this->pickupVerificationError = 'Invalid verification code';
            return;
        }
        
        // Prepare pickup person data
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
        
        // Update parcel with pickup information
        $this->parcel->update([
            'current_status' => Parcel::STATUS_PICKED,
        ]);
        
        // Save pickup details to a separate table if you have one
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
        
        // Close modal and show success message
        $this->closePickUpModal();
        session()->flash('success', 'Parcel pickup verified successfully');
        
        // Refresh the page data
        $this->dispatch('parcel-updated');
        
    } catch (\Exception $e) {

    dd($e->getMessage());
        session()->flash('error', 'Failed to verify pickup: ' . $e->getMessage());
    }
}

// Add the verifyPickupCode method
private function verifyPickupCode($code)
{
    // Implement your verification logic here
    // This could check against a code sent to the recipient's phone
    // or against a predefined code in the database
    
    // Example implementation:
    // return $this->parcel->pickup_code === $code;
    
    // For now, return true for testing
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

            //TODO::Send Email and text to driver when assigned the parcel
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

        //TODO::Send Email and text to recipient to pick up the parcel after arriving at destination
        $this->parcel->current_status = Parcel::STATUS_ARRIVED_AT_DESTINATION;
        $this->parcel->driver_id = $this->selectedDriver->id;
        $this->parcel->save();
        DB::commit();
    }


    public function pickUpByRecipient() {}

    /**
     * Load parcel with relationships
     */
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

    /**
     * Get payments for the parcel
     */
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

    /**
     * Change active tab
     */
    public function changeTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        Log::info('Tab changed', ['new_tab' => $tab]);
    }

    /**
     * Open payment modal
     */
    public function openPaymentModal()
    {
        Log::info('Opening payment modal', ['parcel_id' => $this->parcelId]);

        $this->resetPaymentModal();

        // Calculate remaining balance
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

    /**
     * Close payment modal
     */
    public function closePaymentModal()
    {
        Log::info('Closing payment modal');
        $this->showPaymentModal = false;
        $this->resetPaymentModal();
    }

    /**
     * Reset payment modal properties
     */
    protected function resetPaymentModal()
    {
        $this->reset([
            'paymentMethod',
            'paymentNotes',
            'isProcessing',
            'checkoutRequestId',
            'paymentStatus',
            'paymentStatusMessage',
            'showMpesaStatus'
        ]);
    }

    /**
     * Update payment method handler
     */
    public function updatedPaymentMethod($value)
    {
        Log::info('Payment method updated', ['method' => $value]);

        if ($value === 'mpesa') {
            $this->paymentPhone = $this->parcel->sender_phone;
            Log::info('M-Pesa selected, phone set', ['phone' => $this->paymentPhone]);
        }
    }

    /**
     * Process payment
     */
    public function processPayment()
    {
        Log::info('=== Starting Payment Processing ===', [
            'parcel_id' => $this->parcelId,
            'amount' => $this->paymentAmount,
            'method' => $this->paymentMethod
        ]);

        // Calculate remaining balance
        $totalPaid = $this->parcel->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $remainingBalance = $this->parcel->total_amount - $totalPaid;

        Log::info('Payment calculation', [
            'total_amount' => $this->parcel->total_amount,
            'total_paid' => $totalPaid,
            'remaining' => $remainingBalance,
            'requested' => $this->paymentAmount
        ]);

        // Validate input
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

            $this->addError('payment', 'Failed to process payment: ' . $e->getMessage());
            $this->paymentStatusMessage = 'Failed to process payment: ' . $e->getMessage();
        } finally {
            $this->isProcessing = false;
            Log::info('=== Payment Processing Completed ===');
        }
    }

    /**
     * Process M-Pesa payment
     */
    protected function processMpesaPayment()
    {
        Log::info('Processing M-Pesa payment', [
            'phone' => $this->paymentPhone,
            'amount' => $this->paymentAmount
        ]);

        DB::beginTransaction();

        try {
            $accountReference = 'KARIBU PARCELS' . $this->parcel->parcel_id;
            $transactionDesc = 'Payment for parcel ' . $this->parcel->parcel_id;

            Log::info('Initiating M-Pesa STK Push', [
                'account_ref' => $accountReference,
                'description' => $transactionDesc
            ]);

            // Initiate M-Pesa payment with parcel and user IDs
            $result = $this->mpesaService->stkPush(
                $this->paymentPhone,
                $this->paymentAmount,
                $accountReference,
                $transactionDesc,
                $this->parcelId,           // Pass parcel ID
                Auth::guard('partner')->id() // Pass user ID
            );

            Log::info('M-Pesa STK Push result', $result);

            if ($result['success']) {
                // Store checkout request ID for status checking
                $this->checkoutRequestId = $result['checkout_request_id'];

                DB::commit();

                // Show status checking UI
                $this->showMpesaStatus = true;
                $this->paymentStatus = 'pending';
                $this->paymentStatusMessage = 'M-Pesa prompt sent. Please check your phone and enter your PIN to complete payment.';

                Log::info('M-Pesa payment initiated successfully', [
                    'checkout_request_id' => $this->checkoutRequestId,
                    'transaction_id' => $result['transaction_id']
                ]);

                // Dispatch event to start polling
                $this->dispatch('mpesa-payment-initiated', checkoutRequestId: $this->checkoutRequestId);
            } else {
                DB::rollBack();

                Log::error('M-Pesa initiation failed', [
                    'message' => $result['message'],
                    'error_code' => $result['error_code'] ?? null
                ]);

                throw new Exception($result['message']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Process other payment methods (cash, card, etc.)
     */
    protected function processOtherPayment()
    {
        Log::info('Processing non-M-Pesa payment', [
            'method' => $this->paymentMethod,
            'amount' => $this->paymentAmount
        ]);

        DB::beginTransaction();

        try {
            // Create payment record
            $payment = Payment::create([
                'reference_number' => $this->generateReferenceNumber(),
                'parcel_id' => $this->parcelId,
                'amount' => $this->paymentAmount,
                'payment_method' => $this->paymentMethod,
                'payment_date' => now(),
                'status' => 'completed', // Instant completion for non-M-Pesa
                'phone' => $this->paymentMethod === 'mpesa' ? $this->paymentPhone : null,
                'notes' => $this->paymentNotes,
                'paid_by' => Auth::guard('partner')->id(),
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->id,
                'reference' => $payment->reference_number
            ]);

            // Update parcel payment status
            $this->updateParcelPaymentStatus();

            DB::commit();

            Log::info('Non-M-Pesa payment completed successfully');

            session()->flash('success', 'Payment of ' . number_format($this->paymentAmount, 2) . ' recorded successfully!');
            $this->closePaymentModal();
            $this->loadParcel(); // Refresh parcel data

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Non-M-Pesa payment failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generate unique reference number for payments
     */
    protected function generateReferenceNumber()
    {
        $prefix = 'PAY';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(uniqid(), -4));

        $reference = $prefix . $timestamp . $random;

        Log::debug('Generated reference number', ['reference' => $reference]);

        return $reference;
    }

    /**
     * Check M-Pesa payment status (called via polling)
     */
    public function checkMpesaStatus()
    {
        if (!$this->checkoutRequestId) {
            Log::warning('No checkout request ID for status check');
            return;
        }

        Log::info('Checking M-Pesa payment status', [
            'checkout_request_id' => $this->checkoutRequestId
        ]);

        try {
            $result = $this->mpesaService->checkStkStatus($this->checkoutRequestId);

            if ($result['success']) {
                $response = $result['response'];
                $resultCode = $result['result_code'] ?? null;

                Log::info('M-Pesa status check response', [
                    'result_code' => $resultCode,
                    'result_desc' => $result['result_desc']
                ]);

                // Find the transaction
                $transaction = MpesaTransaction::where('checkout_request_id', $this->checkoutRequestId)->first();

                if (!$transaction) {
                    Log::error('Transaction not found for status check', [
                        'checkout_request_id' => $this->checkoutRequestId
                    ]);
                    return;
                }

                // Handle based on result code
                if ($resultCode === 0) {
                    // Payment completed successfully
                    $this->paymentStatus = 'completed';
                    $this->paymentStatusMessage = 'Payment completed successfully!';

                    Log::info('M-Pesa payment completed', [
                        'transaction_id' => $transaction->id,
                        'receipt' => $response['MpesaReceiptNumber'] ?? null
                    ]);

                    // Find and update the payment record
                    $payment = Payment::where('mpesa_transaction_id', $transaction->id)->first();

                    if ($payment) {
                        $payment->update([
                            'status' => 'completed',
                            'reference_number' => $response['MpesaReceiptNumber'] ?? $payment->reference_number,
                            'payment_date' => now()
                        ]);

                        Log::info('Payment record updated', ['payment_id' => $payment->id]);
                    }

                    // Update parcel payment status
                    $this->updateParcelPaymentStatus();
                    $this->loadParcel();

                    // Stop polling
                    $this->dispatch('mpesa-payment-completed');
                } elseif ($resultCode === 1037) {
                    // Still pending
                    $this->paymentStatusMessage = 'Waiting for you to enter M-Pesa PIN...';
                    Log::info('M-Pesa payment still pending');
                } elseif (in_array($resultCode, [1032, 2001])) {
                    // Transaction cancelled by user
                    $this->paymentStatus = 'failed';
                    $this->paymentStatusMessage = 'Transaction was cancelled. Please try again.';

                    Log::info('M-Pesa payment cancelled by user');

                    // Update payment record
                    $payment = Payment::where('mpesa_transaction_id', $transaction->id)->first();
                    if ($payment) {
                        $payment->update(['status' => 'failed']);
                        Log::info('Payment marked as failed', ['payment_id' => $payment->id]);
                    }

                    $this->dispatch('mpesa-payment-failed');
                } elseif (in_array($resultCode, [1, 1036])) {
                    // Payment failed (insufficient funds, etc.)
                    $this->paymentStatus = 'failed';
                    $this->paymentStatusMessage = $result['result_desc'] ?? 'Payment failed. Please try again.';

                    Log::info('M-Pesa payment failed', ['reason' => $result['result_desc']]);

                    // Update payment record
                    $payment = Payment::where('mpesa_transaction_id', $transaction->id)->first();
                    if ($payment) {
                        $payment->update(['status' => 'failed']);
                        Log::info('Payment marked as failed', ['payment_id' => $payment->id]);
                    }

                    $this->dispatch('mpesa-payment-failed');
                }
            }
        } catch (Exception $e) {
            Log::error('M-Pesa status check error', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $this->checkoutRequestId
            ]);
        }
    }

    /**
     * Update parcel payment status based on total payments
     */
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

    /**
     * Format phone number for M-Pesa
     */
    protected function formatMpesaPhone($phone)
    {
        Log::debug('Formatting phone for M-Pesa', ['original' => $phone]);

        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert to format 254XXXXXXXXX
        if (substr($phone, 0, 1) == '0') {
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) == '254') {
            // Already in correct format
        } else {
            $phone = '254' . $phone;
        }

        Log::debug('Phone formatted for M-Pesa', ['formatted' => $phone]);

        return $phone;
    }

    /**
     * Update parcel status
     */
    public function updateStatus($status)
    {
        Log::info('Updating parcel status', [
            'parcel_id' => $this->parcelId,
            'new_status' => $status
        ]);

        try {
            $this->parcel->addTracking(
                $status,
                Auth::guard('partner')->user()->id,
                'Status updated to ' . ucfirst(str_replace('_', ' ', $status)),
            );

            $this->loadParcel();

            Log::info('Parcel status updated successfully', [
                'parcel_id' => $this->parcelId,
                'status' => $status
            ]);

            session()->flash('success', 'Parcel status updated to ' . ucfirst(str_replace('_', ' ', $status)));
        } catch (Exception $e) {
            Log::error('Failed to update parcel status', [
                'error' => $e->getMessage(),
                'parcel_id' => $this->parcelId
            ]);

            session()->flash('error', 'Failed to update parcel status');
        }
    }

    /**
     * Get status badge configuration
     */
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

    /**
     * Get payment status badge configuration
     */
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

    /**
     * Generate receipt
     */
    public function generateReceipt()
    {
        Log::info('Receipt generation initiated', ['parcel_id' => $this->parcelId]);
        session()->flash('info', 'Receipt generation initiated. Download will start shortly.');
    }

    /**
     * Print label
     */
    public function printLabel()
    {
        Log::info('Label printing initiated', ['parcel_id' => $this->parcelId]);
        session()->flash('info', 'Label generation initiated.');
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
