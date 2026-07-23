<?php

namespace App\Livewire\Partners\Parcels;

use App\Livewire\Admin\Settings\Pricing\Pricings;
use App\Mail\NewParcel;
use App\Models\Parcel;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\User;
use App\Services\MpesaService;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

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
    public $showReceiptModal = false;
    public $selectedPayment = null;
    public $receiptData = [];
    public $pollingEnabled = false;
    public $loggedUser;
    public $loggedUserType;
    public $showErrorModal = false;
    public $errorMessage = null;
    public $showSuccessModal = false;
    public $successMessage = null;

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
    public function showReceipt($paymentId)
    {
        $this->selectedPayment = Payment::with('parcel')->findOrFail($paymentId);
        $this->prepareReceiptData();
        $this->showReceiptModal = true;
    }

    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
        $this->selectedPayment = null;
        $this->receiptData = [];
    }

    protected function prepareReceiptData()
    {
        $payment = $this->selectedPayment;
        $parcel = $payment->parcel;

        // Get all completed payments for this parcel
        $allPayments = Payment::where('parcel_id', $parcel->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPaid = $allPayments->sum('amount');
        $previousPaymentsTotal = $allPayments->where('created_at', '<', $payment->created_at)->sum('amount');

        $this->receiptData = [
            'receipt_number' => $this->generateReceiptNumber($payment),
            'payment' => $payment,
            'parcel' => $parcel,
            'total_paid' => $totalPaid,
            'previous_payments_total' => $previousPaymentsTotal,
            'payment_breakdown' => $allPayments,
            'company_details' => $this->getCompanyDetails(),
            'qr_code_url' => $this->generateQRCode($payment),
            // Add price breakdown data
            'price_breakdown' => [
                'base_price' => $parcel->base_price,
                'weight_charge' => $parcel->weight_charge,
                'distance_charge' => $parcel->distance_charge,
                'special_handling_charge' => $parcel->special_handling_charge,
                'insurance_charge' => $parcel->insurance_charge ?? 0,
                'tax_amount' => $parcel->tax_amount,
                'discount_amount' => $parcel->discount_amount,
                'total_amount' => $parcel->total_amount,
            ],
            'parcel_details' => [
                'parcel_type' => $parcel->parcel_type,
                'weight' => $parcel->weight,
                'weight_unit' => $parcel->weight_unit,
                'length' => $parcel->length,
                'width' => $parcel->width,
                'height' => $parcel->height,
                'dimension_unit' => $parcel->dimension_unit,
                'from_town' => $parcel->senderTown->name ?? 'N/A',
                'to_town' => $parcel->receiverTown->name ?? 'N/A',
                'sender_name' => $parcel->sender_name,
                'sender_phone' => $parcel->sender_phone,
                'receiver_name' => $parcel->receiver_name,
                'receiver_phone' => $parcel->receiver_phone,
            ]
        ];
    }

    protected function generateReceiptNumber($payment)
    {
        $prefix = 'RCP';
        $date = $payment->created_at->format('Ymd');
        $sequence = str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        return $prefix . $date . $sequence;
    }

    protected function getCompanyDetails()
    {
        // Fetch company details from settings or config
        return [
            'name' => config('app.name', 'Karibu Parcels'),
            'address' => 'Mashariki Breeze, Diani Beach Road, Office No.6 , Diani Beach Kwale County',
            'phone' => '+254 700 130 759',
            'email' => 'karibuparcels@gmail.com',
            'website' => 'www.karibuparcels.com',
            'pin' => '',
        ];
    }

    protected function generateQRCode($payment)
    {

        return;
        // Generate verification URL
        // $verificationUrl = route('receipt.verify', ['receipt' => $payment->reference_number]);

        // You can use a QR code library like SimpleSoftwareIO\QrCode
        // For now, return a placeholder or use an API
        // try {
        //     return 'https://quickchart.io/qr?text=' . urlencode($verificationUrl) . '&size=150';
        // } catch (\Exception $e) {
        //     return null;
        // }
    }

    public function sendReceiptWhatsApp()
    {
        $payment = $this->selectedPayment;
        $message = $this->generateReceiptMessage('whatsapp');
        $phone = $payment->parcel->sender_phone ?? $payment->phone;

        // Clean phone number for WhatsApp
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) == 10) {
            $phone = '254' . substr($phone, -9);
        } elseif (strlen($phone) == 9) {
            $phone = '254' . $phone;
        }

        $whatsappUrl = 'https://wa.me/' . $phone . '?text=' . urlencode($message);

        $this->dispatch('open-whatsapp', url: $whatsappUrl);
        session()->flash('success', 'Opening WhatsApp to send receipt...');
    }

    public function sendReceiptEmail()
    {
        $payment = $this->selectedPayment;
        $parcel = $payment->parcel;
        $email = $parcel->sender_email ?? $parcel->receiver_email;

        if (!$email) {
            session()->flash('error', 'No email address available for this parcel.');
            return;
        }

        try {
            Mail::send('emails.receipt', ['receiptData' => $this->receiptData], function ($message) use ($email, $parcel) {
                $message->to($email)
                    ->subject('Payment Receipt - Parcel #' . $parcel->parcel_id)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            session()->flash('success', 'Receipt sent via email successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to send receipt email', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send email. Please try again.');
        }
    }

    public function sendReceiptSMS(SMSService $smsService)
    {
        $payment = $this->selectedPayment;
        $parcel = $payment->parcel;
        $phone = $parcel->sender_phone ?? $parcel->receiver_phone;
        $message = $this->generateReceiptMessage('sms');

        try {
            $smsService->send($phone, $message);
            session()->flash('success', 'Receipt sent via SMS successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to send receipt SMS', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send SMS. Please try again.');
        }
    }

    public function printReceipt()
    {
        $this->dispatch('print-receipt');
    }

    protected function generateReceiptMessage($type = 'sms')
    {
        $payment = $this->selectedPayment;
        $parcel = $payment->parcel;

        $priceBreakdown = "Price Breakdown:\n" .
            "Base Price: KES " . number_format($parcel->base_price, 2) . "\n" .
            "Weight Charge: KES " . number_format($parcel->weight_charge, 2) . "\n" .
            "Distance Charge: KES " . number_format($parcel->distance_charge, 2) . "\n" .
            "Special Handling: KES " . number_format($parcel->special_handling_charge, 2) . "\n" .
            "Tax (16%): KES " . number_format($parcel->tax_amount, 2);

        if ($parcel->insurance_required) {
            $priceBreakdown .= "\nInsurance: KES " . number_format($parcel->insurance_charge, 2);
        }

        if ($parcel->discount_amount > 0) {
            $priceBreakdown .= "\nDiscount: -KES " . number_format($parcel->discount_amount, 2);
        }

        $priceBreakdown .= "\nTOTAL: KES " . number_format($parcel->total_amount, 2);

        if ($type === 'sms') {
            return "RECEIPT: Payment of KES " . number_format($payment->amount, 2) .
                " received for parcel #{$parcel->parcel_id}. " .
                "Receipt No: {$this->receiptData['receipt_number']}. " .
                "Total paid: KES " . number_format($this->receiptData['total_paid'], 2) . ". " .
                "Balance: KES " . number_format($parcel->total_amount - $this->receiptData['total_paid'], 2);
        } else {
            return "*COURIER SERVICE RECEIPT*\n\n" .
                "Receipt No: {$this->receiptData['receipt_number']}\n" .
                "Date: {$payment->created_at->format('Y-m-d H:i:s')}\n" .
                "Parcel #: {$parcel->parcel_id}\n\n" .
                "*Parcel Details*\n" .
                "Type: " . ucfirst($parcel->parcel_type) . "\n" .
                "Weight: {$parcel->weight} {$parcel->weight_unit}\n" .
                "From: {$parcel->senderTown->name}\n" .
                "To: {$parcel->receiverTown->name}\n\n" .
                "*Price Breakdown*\n" .
                $priceBreakdown . "\n\n" .
                "*Payment Details*\n" .
                "Amount Paid: KES " . number_format($payment->amount, 2) . "\n" .
                "Method: " . ucfirst($payment->payment_method) . "\n" .
                "Reference: {$payment->reference_number}\n\n" .
                "*Payment Summary*\n" .
                "Total Amount: KES " . number_format($parcel->total_amount, 2) . "\n" .
                "Total Paid: KES " . number_format($this->receiptData['total_paid'], 2) . "\n" .
                "Balance: KES " . number_format($parcel->total_amount - $this->receiptData['total_paid'], 2) . "\n\n" .
                "Thank you for choosing " . config('app.name') . "!";
        }
    }

    protected SMSService $smsService;

    public function boot(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    public function mount($id)
    {
        $this->loggedUser = Auth::guard('partner')->user();
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
                $this->parcel->delivery_pick_up_drop_off_point_id,
                Auth::guard('partner')->user()->id,
                current_user_type(),
                $notes,
                NULL,
                NULL,
            );

            $payout = $this->parcel->calculateParcelPayout((float)($this->parcel->base_price + $this->parcel->tax_amount), 'direct');

            ParcelPayout::create([
                'parcel_id' =>  $this->parcel->id,
                'partner_id' => Auth::guard('partner')->user()->parcelHandlingAssistant?->partner?->id ?? Auth::guard('partner')->user()->partner?->id,
                'type' => 'pickup-dropoff',
                'destination' => 'final',
                'destination_id' => $this->parcel->delivery_pick_up_drop_off_point_id,
                'origin_id' => $this->parcel->sender_pick_up_drop_off_point_id,
                'amount' => $payout['pick_up_drop_off_partner']['amount'],
                'status' => 'approved',
                'paid_out_on' => null,
                'cancelation_reason' => null
            ]);


            DB::commit();

            $this->closePickUpModal();
            session()->flash('success', 'Parcel pickup verified successfully');
            $this->dispatch('parcel-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
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
                $this->parcel->sender_pick_up_drop_off_point_id,
                Auth::guard('partner')->user()->id,
                current_user_type(),
                'Parcel picked by driver',
                $this->selectedDriver->id,
                null,
            );

            $this->parcel->current_status = Parcel::STATUS_IN_TRANSIT;
            $this->parcel->driver_id = $this->selectedDriver->id;
            $this->parcel->save();


            $this->parcel->parcelPayouts()
                ->where('origin_id', $this->parcel->sender_pick_up_drop_off_point_id)
                ->where('type', 'pickup-dropoff')->update([
                    'status' => 'approved'
                ]);
            DB::commit();

            $this->closeDriverVerificationModal();
        } else {
            $this->driverVerificationError = "Could not verify the code. Please check again!";
        }
    }

    public function receiveParcelFromDriver(SMSService  $smsService)
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

        $notes = null;
        $currentLocation = null;
        $status = null;
        $parcelCode = $this->parcel->generateDeliveryOtp();


        if ($this->parcel->delivery_flow == 'warehouse') {
            if ($this->parcel->current_location['pick-up-drop-off-point']->id == $this->parcel->warehouse_id) {
                $status = Parcel::STATUS_ARRIVED_AT_DESTINATION;
                $currentLocation = $this->parcel->delivery_pick_up_drop_off_point_id;
                $notes = 'Parcel arrived at destination: '
                    . ($this->parcel->deliveryStation?->name ?? 'pickup-point');
            }
            if ($this->parcel->current_location['pick-up-drop-off-point']->id == $this->parcel->sender_pick_up_drop_off_point_id) {
                $status = Parcel::STATUS_WAREHOUSE;
                $currentLocation = $this->parcel->warehouse_id;
                $notes = 'Parcel received at warehouse: '
                    . ($this->parcel->warehouse?->name ?? 'warehouse');
            }
        } else {
            $status = Parcel::STATUS_ARRIVED_AT_DESTINATION;
            $currentLocation = $this->parcel->delivery_pick_up_drop_off_point_id;
            $notes = 'Parcel arrived at destination: '
                . ($this->parcel->deliveryStation?->name ?? 'pickup-point');
        }

        $this->parcel->updateParcelStatus(
            $status,
            $currentLocation,
            Auth::guard('partner')->user()->id,
            current_user_type(),
            $notes,
            $this->selectedDriver->id,
            $parcelCode,
        );

        $this->parcel->current_status = $status;
        $this->parcel->driver_id = $this->selectedDriver->id;
        $this->parcel->save();


        $this->parcel->parcelPayouts()
            ->where('type', 'transport')->update([
                'status' => 'approved'
            ]);

        DB::commit();

        //Send SMS to receiver
        try {
            Log::info('Sending SMS to Parcel Sender Start');
            $smsService->sendRecipientSMSWhenParcelArrives(
                formatKenyaNumber($this->parcel->receiver_phone),
                $this->parcel->receiver_name,
                $this->parcel->parcel_id,
                $parcelCode,
                $this->parcel->receiverTown->name
            );
            Log::info('Sending SMS to Parcel Sender End');
        } catch (\Throwable $th) {
            Log::error('Failed to send SMS to receipient: ', [
                'error' => $th->getMessage(),
                'stack' => $th->getTraceAsString(),
            ]);
        }
    }

    protected function loadParcel()
    {
        Log::info('Loading parcel data', ['parcel_id' => $this->parcelId]);
        $this->parcel = Parcel::with([
            'customer',
            'sender',
            'receiver',
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
                'mpesa' => 'M-Pesa',
            ],
            // 'paymentMethods' => [
            //     'cash' => 'Cash',
            //     'mpesa' => 'M-Pesa',
            //     'card' => 'Card',
            //     'bank_transfer' => 'Bank Transfer',
            //     'wallet' => 'Wallet',
            // ],
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

    // protected function resetPaymentModal()
    // {
    //     $this->reset([
    //         'paymentMethod',
    //         'paymentNotes',
    //         'isProcessing',
    //         'checkoutRequestId',
    //         'paymentStatus',
    //         'paymentStatusMessage',
    //         'paymentStatusType',
    //         'showMpesaStatus',
    //         'paymentStatusIcon',
    //         'mpesaReceiptNumber',
    //         'mpesaTransactionId',
    //         'statusCheckCount',
    //     ]);
    // }

    public function updatedPaymentMethod($value)
    {
        Log::info('Payment method updated', ['method' => $value]);

        if ($value === 'mpesa') {
            $this->paymentPhone = $this->parcel->sender_phone;
            Log::info('M-Pesa selected, phone set', ['phone' => $this->paymentPhone]);
        }
    }

    public function processPayment(SMSService $smsService)
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

    // protected function processMpesaPayment()
    // {
    //     DB::beginTransaction();

    //     try {
    //         $accountReference = $this->parcel->parcel_id;
    //         $transactionDesc = 'Payment for parcel No:' . $this->parcel->parcel_id;

    //         $result = $this->mpesaService->stkPush(
    //             $this->paymentPhone,
    //             $this->paymentAmount,
    //             $accountReference,
    //             $transactionDesc,
    //             $this->parcelId,
    //             Auth::guard('partner')->id()
    //         );

    //         Log::info('M-Pesa STK Push result', $result);

    //         if ($result['success']) {
    //             $this->checkoutRequestId = $result['checkout_request_id'];
    //             $this->mpesaTransactionId = $result['transaction_id'];

    //             DB::commit();

    //             $this->paymentStatus = 'waiting_pin';
    //             $this->paymentStatusType = 'info';
    //             $this->paymentStatusIcon = 'bi-phone';
    //             $this->paymentStatusMessage = 'STK Push sent! Please check your phone and enter your M-Pesa PIN to complete payment.';
    //             $this->showMpesaStatus = true;
    //             $this->statusCheckCount = 0;

    //             $this->dispatch('start-mpesa-polling');

    //             Log::info('M-Pesa payment initiated successfully', [
    //                 'checkout_request_id' => $this->checkoutRequestId,
    //                 'transaction_id' => $result['transaction_id']
    //             ]);
    //         } else {
    //             DB::rollBack();

    //             Log::error('M-Pesa initiation failed', [
    //                 'message' => $result['message'],
    //                 'error_code' => $result['error_code'] ?? null
    //             ]);

    //             $this->paymentStatus = 'initiation_failed';
    //             $this->paymentStatusType = 'danger';
    //             $this->paymentStatusIcon = 'bi-exclamation-circle';
    //             $this->paymentStatusMessage = $result['message'];
    //             $this->showMpesaStatus = true;
    //         }
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function checkMpesaStatus()
    {
        if (!$this->pollingEnabled) {
            Log::info('Polling is disabled, skipping status check');
            return;
        }

        Log::info("Polling for payment status", ['checkout_request_id' => $this->checkoutRequestId]);

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

            if (isset($result['result_code'])) {
                $resultCode = $result['result_code'];

                switch ($resultCode) {
                    case 0:
                        $this->handlePaymentSuccess($result);
                        $this->stopPolling(); // Stop polling on success
                        break;

                    case 1032:
                        $this->handlePaymentCancelled($result);
                        $this->stopPolling(); // Stop polling on cancellation
                        break;

                    case 1037:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentTimeout($result);
                            $this->stopPolling(); // Stop polling on timeout
                        }
                        break;

                    case 1:
                        $this->handlePaymentFailed($result, 'insufficient_funds');
                        $this->stopPolling(); // Stop polling on failure
                        break;

                    case 1019:
                        $this->handlePaymentFailed($result, 'wrong_pin');
                        $this->stopPolling(); // Stop polling on failure
                        break;

                    case 1036:
                    case 2001:
                    case 1031:
                    case 1026:
                        $this->handlePaymentFailed($result, 'failed');
                        $this->stopPolling(); // Stop polling on failure
                        break;

                    default:
                        if ($this->statusCheckCount >= $this->maxStatusChecks) {
                            $this->handlePaymentUnknown($result);
                            $this->stopPolling(); // Stop polling on unknown status after max attempts
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
                $this->stopPolling(); // Stop polling on error after max attempts
            }
        }
    }

    // Modify handlePaymentSuccess to ensure polling stops
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
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Payment completed successfully!'
        ]);
    }

    // Modify processMpesaPayment to start polling properly
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

                // Start polling properly
                $this->startPolling();

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
                $this->stopPolling(); // Stop polling on initiation failure
            }
        } catch (Exception $e) {
            DB::rollBack();
            $this->stopPolling(); // Stop polling on exception
            throw $e;
        }
    }

    // Modify resetPaymentModal to stop polling
    protected function resetPaymentModal()
    {
        $this->stopPolling(); // Stop any ongoing polling
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

    // public function checkMpesaStatus()
    // {

    //     Log::info("Polling");
    //     if (!$this->checkoutRequestId) {
    //         Log::warning('No checkout request ID for status check');
    //         return;
    //     }

    //     $this->statusCheckCount++;

    //     Log::info('Checking M-Pesa payment status', [
    //         'checkout_request_id' => $this->checkoutRequestId,
    //         'attempt' => $this->statusCheckCount
    //     ]);

    //     try {
    //         $result = $this->mpesaService->checkStkStatus($this->checkoutRequestId);

    //         if (isset($result['result_code'])) {
    //             $resultCode = $result['result_code'];

    //             switch ($resultCode) {
    //                 case 0:
    //                     $this->handlePaymentSuccess($result);
    //                     break;

    //                 case 1032:
    //                     $this->handlePaymentCancelled($result);
    //                     break;

    //                 case 1037:
    //                     if ($this->statusCheckCount >= $this->maxStatusChecks) {
    //                         $this->handlePaymentTimeout($result);
    //                     }
    //                     break;

    //                 case 1:
    //                     $this->handlePaymentFailed($result, 'insufficient_funds');
    //                     break;

    //                 case 1019:
    //                     $this->handlePaymentFailed($result, 'wrong_pin');
    //                     break;

    //                 case 1036:
    //                 case 2001:
    //                 case 1031:
    //                 case 1026:
    //                     $this->handlePaymentFailed($result, 'failed');
    //                     break;

    //                 default:
    //                     if ($this->statusCheckCount >= $this->maxStatusChecks) {
    //                         $this->handlePaymentUnknown($result);
    //                     }
    //                     break;
    //             }
    //         }
    //     } catch (Exception $e) {
    //         Log::error('M-Pesa status check error', [
    //             'error' => $e->getMessage(),
    //             'checkout_request_id' => $this->checkoutRequestId
    //         ]);

    //         if ($this->statusCheckCount >= $this->maxStatusChecks) {
    //             $this->paymentStatus = 'check_failed';
    //             $this->paymentStatusType = 'warning';
    //             $this->paymentStatusIcon = 'bi-exclamation-triangle';
    //             $this->paymentStatusMessage = 'Unable to verify payment status. Please check transaction history or contact support.';
    //             $this->dispatch('stop-mpesa-polling');
    //         }
    //     }
    // }

    // private function handlePaymentSuccess($result)
    // {
    //     $this->paymentStatus = 'success';
    //     $this->paymentStatusType = 'success';
    //     $this->paymentStatusIcon = 'bi-check-circle-fill';
    //     $this->paymentStatusMessage = $result['user_message'] ?? 'Payment completed successfully!';

    //     if (isset($result['response']['CallbackMetadata']['Item'])) {
    //         foreach ($result['response']['CallbackMetadata']['Item'] as $item) {
    //             if ($item['Name'] === 'MpesaReceiptNumber') {
    //                 $this->mpesaReceiptNumber = $item['Value'];
    //                 break;
    //             }
    //         }
    //     }

    //     $this->loadParcel();
    //     $this->dispatch('stop-mpesa-polling');
    //     $this->dispatch('notify', [
    //         'type' => 'success',
    //         'message' => 'Payment completed successfully!'
    //     ]);
    // }

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

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = null;
    }


    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successMessage = null;
    }

    public function acceptParcel()
    {
        $this->loggedUser = Auth::guard('partner')->user();
        if ($this->parcel->payment_status != 'paid') {
            $this->showErrorModal = true;
            $this->errorMessage = "Parcel cannot be accepted because it is not fully paid. Please ensure that the payment is completed before accepting the parcel.";
            return;
        }


        $senderPartnerId = $this->parcel->sender_partner_id;

        $isPartner = $this->loggedUser->partner?->id === $senderPartnerId;
        $isAssistant = $this->loggedUser->parcelHandlingAssistant?->partner?->id === $senderPartnerId;

        if (! $isPartner && ! $isAssistant) {
            $this->showErrorModal = true;
            $this->errorMessage = "You are not authorized to accept this parcel. Only the sender partner can accept this parcel.";
            return;
        }

        // if ($this->loggedUser->partner->id != $this->parcel->sender_partner_id || $this->loggedUser->parcelHandlingAssistant?->partner->id != $this->parcel->sender_partner_id) {
        //     $this->showErrorModal = true;
        //     $this->errorMessage = "You are not authorized to accept this parcel. Only the sender partner can accept this parcel.";
        //     return;
        // }


        // if ($this->loggedUser->partner->id != $this->parcel->sender_partner_id || $this->loggedUser->parcelHandlingAssistant?->partner->id != $this->parcel->sender_partner_id) {
        //     $this->showErrorModal = true;
        //     $this->errorMessage = "You are not authorized to accept this parcel. Only the sender partner can accept this parcel.";
        //     return;
        // }

        try {
            DB::beginTransaction();

            // Update parcel status to booked
            $this->parcel->update([
                'current_status' => Parcel::STATUS_BOOKED,
                'pha_id' => $this->loggedUser->parcelHandlingAssistant?->id ?? null
            ]);

            // Create status history entry
            $this->parcel->updateParcelStatus(
                Parcel::STATUS_BOOKED,
                $this->parcel->sender_pick_up_drop_off_point_id,
                Auth::guard('partner')->user()->id,
                current_user_type(),
                'Parcel booked by partner',
                null,
                null,
            );

            DB::commit();

            // Show success message
            $this->showSuccessModal = true;
            $this->successMessage = "Parcel has been accepted successfully. Await for a driver to come and pick it up";

            $this->loadParcel();
            $this->dispatch('parcel-updated');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to accept parcel', [
                'parcel_id' => $this->parcelId,
                'error' => $e->getMessage()
            ]);

            $this->showErrorModal = true;
            $this->errorMessage = "Failed to accept parcel: " . $e->getMessage();
        }
    }
}
