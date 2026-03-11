<?php

namespace App\Livewire\Partners\Parcels;

use App\Livewire\Admin\Settings\Pricing\Pricings;
use App\Mail\NewParcel;
use App\Models\Parcel;
use App\Models\Driver;
use App\Models\TransportPartner;
use App\Models\PickUpDropOffPartner;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use App\Models\User;
use App\Models\Contact;
use App\Models\Item;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Pricing;
use App\Models\WeightRange;
use App\Services\SMSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithEvents;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateParcel extends Component
{
    // Step Management
    public $currentStep = 1;
    public $totalSteps = 3;

    // Parcel Data
    public $parcel_number;
    public $customer_id;
    public $sender_id;
    public $receiver_id;
    public $booking_type = 'instant';
    public $booking_source = 'admin';

    // Sender Information
    public $sender_name = '';
    public $sender_phone = '';
    public $sender_email = '';
    public $sender_address = '';
    public $sender_county_id = '';
    public $sender_subcounty_id = '';
    public $sender_town_id = '';
    public $sender_notes = '';
    public $sender_pick_up_drop_off_point_id;
    public $save_sender_as_contact = false;

    // Receiver Information
    public $receiver_name = '';
    public $receiver_phone = '';
    public $receiver_email = '';
    public $receiver_address = '';
    public $receiver_county_id = '';
    public $receiver_subcounty_id = '';
    public $receiver_town_id = '';
    public $receiver_notes = '';
    public $delivery_pick_up_drop_off_point_id;
    public $save_receiver_as_contact = false;

    // Parcel Details
    public $parcel_type = 'package';
    public $package_type = 'regular';
    public $weight = '';
    public $length = '';
    public $width = '';
    public $height = '';
    public $dimension_unit = 'cm';
    public $weight_unit = 'kg';
    public $declared_value = 0;
    public $insurance_amount = 0;
    public $insurance_required = false;
    public $content_description = '';
    public $special_instructions = '';

    // Pricing
    public $base_price = 0;
    public $weight_charge = 0;
    public $distance_charge = 0;
    public $special_handling_charge = 0;
    public $insurance_charge = 0;
    public $tax_amount = 0;
    public $discount_amount = 0;
    public $total_amount = 0;
    public $payment_method = 'mpesa';
    public $payment_status = 'pending';

    // Options
    public $customers = [];
    public $counties = [];
    public $subcounties = [];
    public $towns = [];
    public $pickupPartners = [];
    public $deliveryPartners = [];
    public $drivers = [];
    public $transportPartners = [];
    public $pickUpAndDropOffPoints = [];

    // Computed data
    public $calculatedPrice = 0;
    public $isCalculating = false;

    public $items = [];

    public $weightRanges = [];
    public $partner_id;
    protected SMSService $smsService;

    // Validation rules
    protected function rules()
    {
        $rules = [
            // Step 1 Rules
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            // 'sender_address' => 'required|string|max:500',
            'sender_town_id' => 'required|exists:towns,id',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            // 'receiver_address' => 'required|string|max:500',
            'receiver_town_id' => 'required|exists:towns,id',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            // Step 2 Rules
            'parcel_type' => 'required|exists:items,id',
            'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'weight' => 'required|numeric|min:0.1|max:1000',
            'content_description' => 'required|string|max:1000',

            // Step 3 Rules
            'payment_method' => 'required|in:cash,mpesa,card,bank_transfer,wallet',
            'payment_status' => 'required|in:pending,paid,partially_paid',
        ];

        // Optional fields with conditional validation
        if ($this->sender_email) {
            $rules['sender_email'] = 'email|max:255';
        }

        if ($this->receiver_email) {
            $rules['receiver_email'] = 'email|max:255';
        }

        if ($this->declared_value > 0) {
            $rules['declared_value'] = 'numeric|min:0|max:1000000';
        }

        if ($this->length || $this->width || $this->height) {
            $rules['length'] = 'nullable|numeric|min:1|max:500';
            $rules['width'] = 'nullable|numeric|min:1|max:500';
            $rules['height'] = 'nullable|numeric|min:1|max:500';
        }

        return $rules;
    }

    // Custom validation messages
    protected function messages()
    {
        return [
            'sender_name.required' => 'Sender name is required',
            'sender_phone.required' => 'Sender phone number is required',
            'sender_address.required' => 'Sender address is required',
            'sender_town_id.required' => 'Please select sender town',
            'sender_pick_up_drop_off_point_id.required' => 'Please select pickup point',
            'receiver_name.required' => 'Receiver name is required',
            'receiver_phone.required' => 'Receiver phone number is required',
            'receiver_address.required' => 'Receiver address is required',
            'receiver_town_id.required' => 'Please select receiver town',
            'delivery_pick_up_drop_off_point_id.required' => 'Please select delivery point',
            'weight.required' => 'Parcel weight is required',
            'weight.min' => 'Weight must be at least 0.1 kg',
            'weight.max' => 'Weight cannot exceed 1000 kg',
            'content_description.required' => 'Please describe the parcel contents',
            'payment_method.required' => 'Please select a payment method',
            'payment_status.required' => 'Please select payment status',
        ];
    }


    public function boot(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }



    public function mount()
    {
        $this->parcel_number = Parcel::generateParcelNumber();
        $this->loadOptions();


        // Set default values
        $this->weight_unit = 'kg';
        $this->payment_method = 'mpesa';
        $this->payment_status = 'pending';


        // Initial price calculation if default values exist
        if ($this->parcel_type && $this->weight) {
            $this->calculatePriceByTypeAndWeight();
        }

        $pha = Auth::guard('partner')->user()->parcelHandlingAssistant;

        $this->sender_pick_up_drop_off_point_id = $pha->assignment()?->pick_up_and_drop_off_point_id;

        $this->sender_town_id = $pha->assignment()?->pickUpAndDropOffPoint?->town?->id;
    }

    public function loadOptions()
    {
        try {
            $this->items = Item::limit(10)->get();

            $this->weightRanges = WeightRange::all();
            $this->customers = [];
            $this->counties = County::orderBy('name')->get();
            $this->towns = Town::where('status', true)->orderBy('name')->get();
            $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
                ->orderBy('name')
                ->get();

            // Load other options with error handling
            $this->pickupPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->deliveryPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->drivers =  [];
            $this->transportPartners = Partner::where('verification_status', true)->get() ?? [];
        } catch (\Exception $e) {
            dd('Error loading options: ' . $e->getMessage());
            Log::error('Error loading options: ' . $e->getMessage());
        }
    }

    public function updatedSenderCountyId($value)
    {
        try {
            if ($value) {
                $this->subcounties = SubCounty::where('county_id', $value)
                    ->orderBy('name')
                    ->get();
            } else {
                $this->subcounties = [];
                $this->sender_subcounty_id = '';
                $this->sender_town_id = '';
            }
            $this->calculatePrice();
        } catch (\Exception $e) {
            Log::error('Error updating sender county: ' . $e->getMessage());
        }
    }

    public function updatedSenderSubcountyId($value)
    {
        try {
            if ($value) {
                $this->towns = Town::where('sub_county_id', $value)
                    ->orderBy('name')
                    ->get();
            } else {
                $this->sender_town_id = '';
            }
            $this->calculatePrice();
        } catch (\Exception $e) {
            Log::error('Error updating sender subcounty: ' . $e->getMessage());
        }
    }

    public function updatedParcelType()
    {
        $this->calculatePriceByTypeAndWeight();
    }

    public function updatedWeight()
    {
        $this->calculatePriceByTypeAndWeight();
    }

    public function updateSenderTownId()
    {
        $this->calculatePrice();
    }
    public function updateReceiverTownId()
    {
        $this->calculatePrice();
    }
    public function calculatePriceByTypeAndWeight()
    {
        // Reset all price components
        $this->base_price = 0;
        $this->tax_amount = 0;
        $this->total_amount = 0;
        $this->calculatedPrice = 0;
        $this->insurance_charge = 0;

        $senderZone = $this->sender_town_id ? Town::find($this->sender_town_id)->subCounty->county->zone : null;
        $receiverZone = $this->receiver_town_id ? Town::find($this->receiver_town_id)->subCounty->county->zone : null;

        // Check if we have the required data for calculation
        if (!$this->parcel_type || !$this->weight || $this->weight <= 0) {
            return;
        }

        try {
            // Find pricing based on item (parcel type) and weight range
            $pricing = Pricing::where('item_id', $this->parcel_type)
                ->where('min_weight', '<=', $this->weight)
                ->where('max_weight', '>=', $this->weight)
                ->first();

            $pricing = $pricing->items->where('source_zone_id', $senderZone->id)->where('destination_zone_id', $receiverZone->id)->first();

            // If no exact weight match, find the closest weight range
            if (!$pricing) {
                $pricing = Pricing::where('item_id', $this->parcel_type)
                    ->where('min_weight', '<=', $this->weight)
                    ->orderBy('max_weight', 'asc')
                    ->first();
            }

            // If still no pricing found, get the default pricing for this item
            if (!$pricing) {
                $pricing = Pricing::where('item_id', $this->parcel_type)
                    ->orderBy('min_weight', 'asc')
                    ->first();
            }

            if ($pricing) {
                // Calculate base price
                // You can customize this formula based on your business logic
                $baseRate = $pricing->cost ?? $pricing->cost ?? 0;
                $minimumCharge = $pricing->cost ?? 0;

                // Calculate weight-based price
                $weightPrice = $this->weight * $baseRate;

                // Ensure minimum charge applies
                $this->base_price = round(max($minimumCharge, $weightPrice), 2);

                // Add any fixed cost from pricing
                if (isset($pricing->cost) && $pricing->cost > 0) {
                    $this->base_price = round($pricing->cost, 2);
                }

                // Calculate tax (16% VAT)
                $this->tax_amount = round($this->base_price * 0.16, 2);

                // Calculate total
                $this->total_amount = round($this->base_price + $this->tax_amount, 2);
                $this->calculatedPrice = $this->total_amount;

                // Log for debugging (remove in production)
                Log::info('Price calculated:', [
                    'parcel_type' => $this->parcel_type,
                    'weight' => $this->weight,
                    'base_price' => $this->base_price,
                    'tax' => $this->tax_amount,
                    'total' => $this->total_amount
                ]);
            } else {
                // Fallback calculation if no pricing found
                $this->calculateFallbackPrice();
            }
        } catch (\Exception $e) {
            Log::error('Price calculation error: ' . $e->getMessage());
            $this->calculateFallbackPrice();
        }
    }

    /**
     * Fallback price calculation method
     */
    protected function calculateFallbackPrice()
    {
        // Define base rates by parcel type (as fallback)
        $baseRates = [
            'document' => 150,
            'envelope' => 200,
            'package' => 300,
            'box' => 400,
            'pallet' => 800,
            'other' => 350,
        ];

        // Get base rate for parcel type, default to 300 if not found
        $baseRate = $baseRates[$this->parcel_type] ?? 300;

        // Calculate weight multiplier (example: additional charge per kg)
        $weightMultiplier = 0;
        if ($this->weight > 1) {
            $weightMultiplier = ($this->weight - 1) * 50; // KES 50 per additional kg
        }

        // Calculate base price
        $this->base_price = round($baseRate + $weightMultiplier, 2);

        // Calculate tax (16% VAT)
        $this->tax_amount = round($this->base_price * 0.16, 2);

        // Calculate total
        $this->total_amount = round($this->base_price + $this->tax_amount, 2);
        $this->calculatedPrice = $this->total_amount;
    }

    /**
     * Updated method to handle all price calculation triggers
     */
    public function updated($propertyName)
    {
        // Trigger price calculation when relevant fields change
        $priceRelatedFields = [
            'parcel_type',
            'weight',
            'sender_town_id',
            'receiver_town_id',
            'package_type',
            'declared_value'
        ];

        if (in_array($propertyName, $priceRelatedFields)) {
            $this->calculatePriceByTypeAndWeight();
        }
    }

    /**
     * Simplified price calculation for the view
     */
    public function getCalculatedPriceProperty()
    {
        $this->calculatePriceByTypeAndWeight();
        return $this->total_amount;
    }

    /**
     * Calculate insurance charge if needed
     */
    protected function calculateInsurance()
    {
        if ($this->insurance_required && $this->declared_value > 0) {
            $this->insurance_charge = round($this->declared_value * 0.015, 2); // 1.5% of declared value
        } else {
            $this->insurance_charge = 0;
        }
    }

    /**
     * Get all pricings for debugging (optional)
     */
    public function getPricings()
    {
        return Pricing::with('item')
            ->orderBy('item_id')
            ->orderBy('min_weight')
            ->get()
            ->groupBy('item_id');
    }




    public function updatedReceiverCountyId($value)
    {
        $this->calculatePrice();
    }

    // public function updatedWeight($value)
    // {
    //     $this->calculatePrice();
    // }

    public function updatedPackageType($value)
    {
        $this->calculatePrice();
    }

    public function updatedInsuranceRequired($value)
    {
        $this->calculatePrice();
    }

    public function updatedDeclaredValue($value)
    {
        $this->calculatePrice();
    }

    public function calculatePrice() {}

    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->validateStep($this->currentStep);
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateStep($step)
    {
        $rules = [];

        switch ($step) {
            case 1:
                $rules = [
                    'sender_name' => 'required|string|max:255',
                    'sender_phone' => 'required|string|max:20',
                    // 'sender_address' => 'required|string|max:500',
                    'sender_town_id' => 'required|exists:towns,id',
                    'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
                    'receiver_name' => 'required|string|max:255',
                    'receiver_phone' => 'required|string|max:20',
                    // 'receiver_address' => 'required|string|max:500',
                    'receiver_town_id' => 'required|exists:towns,id',
                    'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
                ];

                if ($this->sender_email) {
                    $rules['sender_email'] = 'email|max:255';
                }

                if ($this->receiver_email) {
                    $rules['receiver_email'] = 'email|max:255';
                }
                break;

            case 2:
                $rules = [
                    'parcel_type' => 'required|exists:items,id',
                    'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
                    'weight' => 'required|numeric|min:0.1|max:1000',
                    'content_description' => 'required|string|max:1000',
                ];

                if ($this->declared_value > 0) {
                    $rules['declared_value'] = 'numeric|min:0|max:1000000';
                }
                break;
        }

        try {
            $this->validate($rules, $this->messages());
        } catch (\Illuminate\Validation\ValidationException $e) {

            throw $e;
        }
    }

    public function saveParcel()
    {
        try {
            // Validate all steps
            $this->validate($this->rules(), $this->messages());

            DB::beginTransaction();

            $senderTown = Town::findOrFail($this->sender_town_id);
            $receiverTown = Town::findOrFail($this->receiver_town_id);

            $this->sender_county_id = $senderTown->subCounty->county->id;
            $this->sender_subcounty_id = $senderTown->subCounty->id;

            $this->receiver_county_id = $receiverTown->subCounty->county->id;
            $this->receiver_subcounty_id  = $receiverTown->subCounty->id;

            // Save sender as contact if requested
            // if ($this->save_sender_as_contact && $this->customer_id) {
            $senderContact = Contact::create([
                'name' => $this->sender_name,
                'phone' => $this->sender_phone,
                'email' => $this->sender_email,
                'address' => $this->sender_address,
                'county_id' => $this->sender_county_id,
                'sub_county_id' => $this->sender_subcounty_id,
                'town_id' => $this->sender_town_id,
            ]);
            $this->sender_id = $senderContact->id;
            // }

            // Save receiver as contact if requested
            // if ($this->save_receiver_as_contact && $this->customer_id) {
            $receiverContact = Contact::create([
                'name' => $this->receiver_name,
                'phone' => $this->receiver_phone,
                'email' => $this->receiver_email,
                'address' => $this->receiver_address,
                'county_id' => $this->receiver_county_id,
                'sub_county_id' => $this->receiver_subcounty_id,
                'town_id' => $this->receiver_town_id,
            ]);
            $this->receiver_id = $receiverContact->id;
            // }

            // Create parcel
            $parcelData = [
                'parcel_number' => $this->parcel_number,
                'customer_id' => $this->customer_id,
                'sender_id' => $this->sender_id,
                'receiver_id' => $this->receiver_id,
                'booking_type' => $this->booking_type,
                // 'booking_source' => $this->booking_source,

                // Sender information
                'sender_name' => $this->sender_name,
                'sender_phone' => $this->sender_phone,
                'sender_email' => $this->sender_email,
                'sender_address' => $this->sender_address,
                'sender_county_id' => $this->sender_county_id,
                'sender_subcounty_id' => $this->sender_subcounty_id,
                'sender_town_id' => $this->sender_town_id,
                'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
                'sender_notes' => $this->sender_notes,
                'pha_id' => Auth::guard('partner')->user()->parcelHandlingAssistant->id,
                'sender_partner_id' =>  Auth::guard('partner')->user()->parcelHandlingAssistant->partner->id,

                // Receiver information
                'receiver_name' => $this->receiver_name,
                'receiver_phone' => $this->receiver_phone,
                'receiver_email' => $this->receiver_email,
                'receiver_address' => $this->receiver_address,
                'receiver_county_id' => $this->receiver_county_id,
                'receiver_subcounty_id' => $this->receiver_subcounty_id,
                'receiver_town_id' => $this->receiver_town_id,
                'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
                'receiver_notes' => $this->receiver_notes,
                'delivery_partner_id' => PickUpAndDropOffPoint::where('id', $this->delivery_pick_up_drop_off_point_id)->value('partner_id'),

                // Parcel details
                'parcel_type' => $this->parcel_type,
                'package_type' => $this->package_type,
                'weight' => $this->weight,
                'length' => $this->length ?: null,
                'width' => $this->width ?: null,
                'height' => $this->height ?: null,
                'dimension_unit' => $this->dimension_unit,
                'weight_unit' => $this->weight_unit,
                'declared_value' => $this->declared_value,
                'insurance_amount' => $this->insurance_charge,
                'insurance_required' => $this->insurance_required,
                'content_description' => $this->content_description,
                'special_instructions' => $this->special_instructions,

                // Pricing
                'base_price' => $this->base_price,
                'weight_charge' => $this->weight_charge,
                'distance_charge' => $this->distance_charge,
                'special_handling_charge' => $this->special_handling_charge,
                'insurance_charge' => $this->insurance_charge,
                'tax_amount' => $this->tax_amount,
                'discount_amount' => $this->discount_amount,
                'total_amount' => $this->total_amount,
                'payment_method' => $this->payment_method,
                'payment_status' => $this->payment_status ?? 'pending',

                'current_status' => Parcel::STATUS_CREATED,
                // System fields
                'created_by' => Auth::guard('partner')->user()->id,
            ];

            $parcel = Parcel::create($parcelData);

            $parcelStatus = $parcel->updateParcelStatus(
                Parcel::STATUS_CREATED,
                Auth::guard('partner')->user()->id,
                'pha',
                'Parcel created',
                null,
                $parcel->generateDeliveryOtp(),

            );

            // Create initial tracking record
            if ($parcel) {
                $parcel->addTracking(Parcel::STATUS_PENDING, Auth::guard('partner')->user()->id);
            }

            DB::commit();

            try {
                Log::info('Created Parcel. Sending notification to admin and recipient');
                //Send Email to admin
                $admins = User::where('user_type', 'superadmin')->get();

                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NewParcel($parcel));
                }

                //Send SMS to recipient
                $phone = $this->receiver_phone;

                dispatch(function () use ($phone) {
                    app(SMSService::class)
                        ->notifyRecipientParcelDropOff($phone);
                });

                Log::info('Success sending notification to admin and recipient');
            } catch (\Exception $e) {

                Log::info('Created Parcel. Sending notification to admin and recipient');
                Log::info('Error send notification: ' . $e->getMessage());
                Log::info('Error sending notification to admin and recipient');
            }



            return redirect()->route('partners.parcels.view', $parcel->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->getMessage());

            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {

            dd($e->getMessage());
            DB::rollBack();
            Log::error('Parcel creation error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // $this->calculatePrice();

        if ($this->parcel_type && $this->weight > 0) {
            $this->calculatePriceByTypeAndWeight();
        }

        return view('livewire.partners.parcels.create-parcel', [
            'counties' => $this->counties,
            'subcounties' => $this->subcounties,
            'towns' => $this->towns,
            'customers' => $this->customers,
            'pickupPartners' => $this->pickupPartners,
            'deliveryPartners' => $this->deliveryPartners,
            'drivers' => $this->drivers,
            'transportPartners' => $this->transportPartners,
            'pickUpAndDropOffPoints' => $this->pickUpAndDropOffPoints,
            'parcelTypes' => $this->items,
            'packageTypes' => [
                'regular' => 'Regular',
                'fragile' => 'Fragile',
                'perishable' => 'Perishable',
                'valuable' => 'Valuable',
                'hazardous' => 'Hazardous',
                'oversized' => 'Oversized',
            ],
            'paymentMethods' => [
                'cash' => 'Cash',
                'mpesa' => 'M-Pesa',
                'card' => 'Card',
                'bank_transfer' => 'Bank Transfer',
                'wallet' => 'Wallet',
            ],
            'paymentStatuses' => [
                'pending' => 'Pending',
                'paid' => 'Paid',
                'partially_paid' => 'Partially Paid',
            ],
            'bookingTypes' => [
                'instant' => 'Instant Delivery',
                'scheduled' => 'Scheduled Delivery',
                'bulk' => 'Bulk Shipment',
            ],
        ]);
    }
}
