<?php

namespace App\Livewire\Admin\Parcels;

use App\Models\Parcel;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use App\Models\Contact;
use App\Models\Item;
use App\Models\ParcelHandlingAssistant;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\ParcelPayout;
use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\WeightRange;
use App\Models\ZoneTown;
use App\Services\SMSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class EditParcel extends Component
{
    // Step Management
    public $currentStep = 1;
    public $totalSteps = 3;

    // Parcel ID
    public $parcelId;
    public $parcel;

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
    public $senderTowns = [];

    // Receiver Information
    public $receiver_name = '';
    public $receiver_phone = '';
    public $receiver_email = '';
    public $receiver_address = '';
    public $receiver_county_id = '';
    public $receiver_subcounty_id = '';
    public $receiver_town_id = '';
    public $countyTowns = [];
    public $receiver_notes = '';
    public $delivery_pick_up_drop_off_point_id;
    public $save_receiver_as_contact = false;
    public $receiverPickUpAndDropOffPoints = [];

    // Parcel Details
    public $parcel_type;
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
    public $pha_id;

    public $senderPickUpAndDropOffPoints = [];

    // Validation rules
    protected function rules()
    {
        $rules = [
            // Step 1 Rules
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_town_id' => 'required|exists:towns,id',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_town_id' => 'required|exists:towns,id',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            // Step 2 Rules
            'parcel_type' => 'required',
            'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'weight' => 'required|numeric|min:0.1|max:1000',
            'content_description' => 'required|string|max:1000',
            'declared_value' => 'nullable|numeric|min:0|max:1000000',

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
            'sender_town_id.required' => 'Please select sender town',
            'sender_pick_up_drop_off_point_id.required' => 'Please select pickup point',
            'receiver_name.required' => 'Receiver name is required',
            'receiver_phone.required' => 'Receiver phone number is required',
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

    public function mount($id)
    {
        $this->parcelId = $id;
        $this->loadParcelData();
        $this->loadOptions();

        $this->parcel_number = $this->parcel->parcel_id;
        $this->weight_unit = 'kg';

        // Load sender towns based on sender town ID
        if ($this->sender_town_id) {
            $this->updatedSenderTownId($this->sender_town_id);
        }

        // Load receiver towns based on receiver town ID
        if ($this->receiver_town_id) {
            $this->updatedReceiverTownId($this->receiver_town_id);
        }

        // Load county towns if receiver county exists
        if ($this->receiver_county_id) {
            $this->updatedReceiverCountyId($this->receiver_county_id);
        }

        // Initial price calculation if default values exist
        if ($this->parcel_type && $this->weight) {
            $this->calculatePriceByWeight();
        }
    }

    public function loadParcelData()
    {
        $this->parcel = Parcel::with([
            'senderTown.subCounty.county',
            'receiverTown.subCounty.county',
            'senderPickUpDropOffPoint',
            'deliveryStation'
        ])->findOrFail($this->parcelId);

        // Basic Info
        $this->parcel_number = $this->parcel->parcel_id;
        $this->customer_id = $this->parcel->customer_id;
        $this->booking_type = $this->parcel->booking_type;
        $this->booking_source = $this->parcel->booking_source;

        // Sender Information
        $this->sender_name = $this->parcel->sender_name;
        $this->sender_phone = $this->parcel->sender_phone;
        $this->sender_email = $this->parcel->sender_email;
        $this->sender_address = $this->parcel->sender_address;
        $this->sender_town_id = $this->parcel->sender_town_id;
        $this->sender_notes = $this->parcel->sender_notes;
        $this->sender_pick_up_drop_off_point_id = $this->parcel->sender_pick_up_drop_off_point_id;
        $this->pha_id = $this->parcel->pha_id;

        // Set sender county and subcounty from town
        if ($this->parcel->senderTown) {
            $this->sender_county_id = $this->parcel->senderTown->subCounty?->county_id;
            $this->sender_subcounty_id = $this->parcel->senderTown->subCounty?->id;
        }

        // Receiver Information
        $this->receiver_name = $this->parcel->receiver_name;
        $this->receiver_phone = $this->parcel->receiver_phone;
        $this->receiver_email = $this->parcel->receiver_email;
        $this->receiver_address = $this->parcel->receiver_address;
        $this->receiver_town_id = $this->parcel->receiver_town_id;
        $this->receiver_notes = $this->parcel->receiver_notes;
        $this->delivery_pick_up_drop_off_point_id = $this->parcel->delivery_pick_up_drop_off_point_id;

        // Set receiver county and subcounty from town
        if ($this->parcel->receiverTown) {
            $this->receiver_county_id = $this->parcel->receiverTown->subCounty?->county_id;
            $this->receiver_subcounty_id = $this->parcel->receiverTown->subCounty?->id;
        }

        // Parcel Details
        $this->parcel_type = $this->parcel->parcel_type;
        $this->package_type = $this->parcel->package_type;
        $this->weight = $this->parcel->weight;
        $this->length = $this->parcel->length;
        $this->width = $this->parcel->width;
        $this->height = $this->parcel->height;
        $this->dimension_unit = $this->parcel->dimension_unit;
        $this->weight_unit = $this->parcel->weight_unit;
        $this->declared_value = $this->parcel->declared_value;
        $this->insurance_amount = $this->parcel->insurance_amount;
        $this->insurance_required = (bool) $this->parcel->insurance_required;
        $this->content_description = $this->parcel->content_description;
        $this->special_instructions = $this->parcel->special_instructions;

        // Pricing
        $this->base_price = $this->parcel->base_price;
        $this->weight_charge = $this->parcel->weight_charge;
        $this->distance_charge = $this->parcel->distance_charge;
        $this->special_handling_charge = $this->parcel->special_handling_charge;
        $this->insurance_charge = $this->parcel->insurance_charge;
        $this->tax_amount = $this->parcel->tax_amount;
        $this->discount_amount = $this->parcel->discount_amount;
        $this->total_amount = $this->parcel->total_amount;
        $this->payment_method = $this->parcel->payment_method;
        $this->payment_status = $this->parcel->payment_status;
    }

    public function loadOptions()
    {
        try {
            // Load counties with towns that have pick up points
            $this->counties = County::whereHas('towns.pickUpAndDropOffPoint', function ($query) {
                $query->where('status', true);
            })
                ->orderBy('name')
                ->get();

            // Load towns for dropdowns
            $this->towns = Town::where('status', true)->whereHas('pickUpAndDropOffPoint', function ($query) {
                $query->where('status', 'active');
            })->orderBy('name')->get();

            // Load pick up and drop off points
            $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
                ->orderBy('name')
                ->get();

            // Load partners
            $this->pickupPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->deliveryPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->transportPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->drivers = [];

            // Load items/parcel types
            $this->items = Item::where('status', true)->get();

            // Load weight ranges
            $this->weightRanges = WeightRange::all();
        } catch (\Exception $e) {
            Log::error('Error loading options: ' . $e->getMessage());
        }
    }

    public function updatedSenderTownId($value)
    {
        try {
            if ($value) {
                $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', (int)$value)
                    ->where('status', true)
                    ->orderBy('name')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Error updating sender details: ' . $e->getMessage());
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
        } catch (\Exception $e) {
            Log::error('Error updating sender subcounty: ' . $e->getMessage());
        }
    }

    public function updatedReceiverCountyId($value)
    {
        try {
            if ($value) {
                $county = County::where('id', (int)$value)->first();
                $this->countyTowns = $county->towns()
                    ->whereHas('pickUpAndDropOffPoint', function ($query) {
                        $query->where('status', true);
                    })
                    ->get();
            } else {
                $this->countyTowns = Town::where('status', true)->orderBy('name')->get();
            }
        } catch (\Exception $e) {
            Log::error('Error updating receiver county: ' . $e->getMessage());
        }
    }

    public function updatedReceiverTownId($value)
    {
        try {
            if ($value) {
                $town = Town::where('id', (int)$value)->first();
                $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', $town->id)
                    ->where('status', true)
                    ->get();
            } else {
                $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)->get();
            }
        } catch (\Exception $e) {
            Log::error('Error updating receiver town: ' . $e->getMessage());
        }
    }

    public function updatedWeight()
    {
        $this->calculatePriceByWeight();
    }

    public function updatedDeclaredValue()
    {
        $this->calculateInsurance();
        $this->calculatePriceByWeight();
    }

    public function updatedInsuranceRequired()
    {
        $this->calculateInsurance();
        $this->calculatePriceByWeight();
    }

    /**
     * Calculate insurance charge based on declared value (2%)
     */
    protected function calculateInsurance()
    {
        if ($this->insurance_required && $this->declared_value > 0) {
            $this->insurance_charge = round($this->declared_value * 0.02, 2);
        } else {
            $this->insurance_charge = 0;
        }
    }

    public function calculatePriceByWeight()
    {
        $this->base_price = 0;
        $this->tax_amount = 0;
        $this->total_amount = 0;
        $this->calculatedPrice = 0;

        $this->calculateInsurance();

        if (
            !$this->weight ||
            $this->weight <= 0 ||
            !$this->sender_town_id ||
            !$this->receiver_town_id
        ) {
            return;
        }

        try {
            $senderTown = Town::with('subCounty.county')
                ->find($this->sender_town_id);

            $receiverTown = Town::with('subCounty.county')
                ->find($this->receiver_town_id);

            if (!$senderTown || !$receiverTown) {
                return;
            }

            $senderZone = ZoneTown::where('town_id', $senderTown->id)->first();
            $receiverZone = ZoneTown::where('town_id', $receiverTown->id)->first();

            if (!$senderZone || !$receiverZone) {
                return;
            }

            $pricing = PricingItem::where('source_zone_id', $senderZone->zone_id)
                ->where('destination_zone_id', $receiverZone->zone_id)->first();

            if (!$pricing) {
                $this->calculateFallbackPrice();
                return;
            }

            $basePrice = (float) ($pricing->cost ?? 0);
            $extraKgCost = (float) ($pricing->extra ?? 0);

            if ($this->weight <= 5) {
                $this->base_price = round($basePrice, 2);
            } else {
                $extraWeight = $this->weight - 5;
                $this->base_price = round(
                    $basePrice + ($extraWeight * $extraKgCost),
                    2
                );
            }

            $this->tax_amount = round(
                $this->base_price * 0.16,
                2
            );

            $this->total_amount = round(
                $this->base_price +
                    $this->insurance_charge +
                    $this->tax_amount,
                2
            );

            $this->calculatedPrice = $this->total_amount;

            Log::info('Parcel price calculated for edit', [
                'parcel_id' => $this->parcelId,
                'parcel_type' => $this->parcel_type,
                'weight' => $this->weight,
                'base_price' => $this->base_price,
                'insurance_charge' => $this->insurance_charge,
                'tax_amount' => $this->tax_amount,
                'total_amount' => $this->total_amount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Price calculation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->calculateFallbackPrice();
        }
    }

    /**
     * Fallback price calculation method
     */
    protected function calculateFallbackPrice()
    {
        $baseRates = [
            'document' => 150,
            'envelope' => 200,
            'package' => 300,
            'box' => 400,
            'pallet' => 800,
            'other' => 350,
        ];

        $baseRate = $baseRates[$this->parcel_type] ?? 300;

        $weightMultiplier = 0;
        if ($this->weight > 1) {
            $weightMultiplier = ($this->weight - 1) * 50;
        }

        $this->base_price = round($baseRate + $weightMultiplier, 2);
        $this->tax_amount = round($this->base_price * 0.16, 2);
        $this->total_amount = round($this->base_price + $this->insurance_charge + $this->tax_amount, 2);
        $this->calculatedPrice = $this->total_amount;
    }

    public function updated($propertyName)
    {
        $priceRelatedFields = [
            'parcel_type',
            'weight',
            'sender_town_id',
            'receiver_town_id',
            'package_type',
            'declared_value',
            'insurance_required'
        ];

        if (in_array($propertyName, $priceRelatedFields)) {
            $this->calculatePriceByWeight();
        }
    }

    public function getCalculatedPriceProperty()
    {
        $this->calculatePriceByWeight();
        return $this->total_amount;
    }

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
                    'sender_town_id' => 'required|exists:towns,id',
                    'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
                    'receiver_name' => 'required|string|max:255',
                    'receiver_phone' => 'required|string|max:20',
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
                    'parcel_type' => 'required',
                    'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
                    'weight' => 'required|numeric|min:0.1|max:1000',
                    'content_description' => 'required|string|max:1000',
                    'declared_value' => 'nullable|numeric|min:0|max:1000000',
                ];
                break;
        }

        try {
            $this->validate($rules, $this->messages());
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }
    }

    public function updateParcel()
    {
        try {
            // Validate all steps
            $this->validate($this->rules(), $this->messages());

            DB::beginTransaction();

            $senderPartner = Partner::findOrFail($this->sender_pick_up_drop_off_point_id);
            $receiverPartner = Partner::findOrFail($this->delivery_pick_up_drop_off_point_id);

            $parcelHandlingAssistant = ParcelHandlingAssistant::where($this->pha_id)->first();
            if ($parcelHandlingAssistant) {
                if ($parcelHandlingAssistant->partner->id != $senderPartner->id) {
                    $this->pha_id = null;
                }
            }
            $senderTown = Town::findOrFail($this->sender_town_id);
            $receiverTown = Town::findOrFail($this->receiver_town_id);

            $this->sender_county_id = $senderTown->subCounty->county->id;
            $this->sender_subcounty_id = $senderTown->subCounty->id;

            $this->receiver_county_id = $receiverTown->subCounty->county->id;
            $this->receiver_subcounty_id = $receiverTown->subCounty->id;

            // Update parcel
            $parcelData = [
                'parcel_id' => $this->parcel_number,
                'customer_id' => $this->customer_id,
                'booking_type' => $this->booking_type,
                'booking_source' => $this->booking_source,

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
                'sender_partner_id' =>  $senderPartner->id,
                'pha_id' =>  $this->pha_id,

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
                'delivery_partner_id' => $receiverPartner->id,

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
            ];

            $this->parcel->update($parcelData);

            // Update payout if needed
            $payout = $this->parcel->calculateParcelPayout(
                (float)($this->parcel->base_price + $this->parcel->tax_amount),
                'direct'
            );

            // Update or create parcel payout
            $existingPayout = ParcelPayout::where('parcel_id', $this->parcel->id)
                ->where('type', 'pickup-dropoff')
                ->first();

            if ($existingPayout) {
                $existingPayout->update([
                    'origin_id' => $this->sender_pick_up_drop_off_point_id,
                    'amount' => $payout['pick_up_drop_off_partner']['amount'],
                ]);
            } else {
                ParcelPayout::create([
                    'parcel_id' => $this->parcel->id,
                    'partner_id' => Auth::guard('admin')->user()->id,
                    'type' => 'pickup-dropoff',
                    'destination' => null,
                    'destination_id' => null,
                    'origin_id' => $this->sender_pick_up_drop_off_point_id,
                    'amount' => $payout['pick_up_drop_off_partner']['amount'],
                    'status' => 'pending',
                    'paid_out_on' => null,
                    'cancelation_reason' => null
                ]);
            }

            DB::commit();

            session()->flash('success', 'Parcel updated successfully!');
            return redirect()->route('admin.parcels.view', $this->parcel->id);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Parcel update error: ' . $e->getMessage());
            session()->flash('error', 'Failed to update parcel: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if ($this->parcel_type && $this->weight > 0) {
            $this->calculatePriceByWeight();
        }

        return view('livewire.admin.parcels.edit-parcel', [
            'counties' => $this->counties,
            'subcounties' => $this->subcounties,
            'towns' => $this->towns,
            'customers' => $this->customers,
            'pickupPartners' => $this->pickupPartners,
            'deliveryPartners' => $this->deliveryPartners,
            'drivers' => $this->drivers,
            'transportPartners' => $this->transportPartners,
            'pickUpAndDropOffPoints' => $this->pickUpAndDropOffPoints,
            'parcelTypes' => [
                'document' => 'Document',
                'package' => 'Package',
                'envelope' => 'Envelope',
                'box' => 'Box',
                'pallet' => 'Pallet',
                'other' => 'Other',
            ],
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
                'failed' => 'Failed',
                'refunded' => 'Refunded',
            ],
            'bookingTypes' => [
                'instant' => 'Instant Delivery',
                'scheduled' => 'Scheduled Delivery',
                'bulk' => 'Bulk Shipment',
            ],
        ]);
    }
}
