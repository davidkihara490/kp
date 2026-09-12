<?php

namespace App\Livewire\Partners\Parcels;

use App\Mail\NewParcel;
use App\Models\Parcel;
use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use App\Models\User;
use App\Models\Contact;
use App\Models\Item;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\WeightRange;
use App\Models\ZoneCounty;
use App\Models\ZoneTown;
use App\Services\SMSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

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
    public $booking_source = 'partner';

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
    public $countyTowns  = [];
    public $receiver_notes = '';
    public $delivery_pick_up_drop_off_point_id;
    public $save_receiver_as_contact = false;
    public $receiverPickUpAndDropOffPoints = [];

    // Parcel-level rollups
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

    // Pricing (parcel-level rollup)
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

    /** @var \Illuminate\Support\Collection|array Item catalogue (Item model records) */
    public $items = [];

    public $weightRanges = [];
    public $partner_id;

    public $senderPickUpAndDropOffPoints = [];

    /**
     * Parcel line items (repeater). Each element:
     *  [
     *    'parcel_category_id'  => ?int,   // Item model id (dropdown selection)
     *    'parcel_type'         => string,
     *    'package_type'        => string,
     *    'declared_value'      => numeric,
     *    'insurance_required'  => bool,
     *    'content_description' => string,
     *    'special_notes'       => string,
     *    'payment_on_delivery' => bool,
     *    // calculated
     *    'base_price'                => numeric,
     *    'item_insurance_amount'     => numeric,
     *    'tax_amount'                => numeric,
     *    'item_total'                => numeric,
     *  ]
     */
    public array $parcelItems = [];

    protected SMSService $smsService;

    protected function rules()
    {
        $rules = [
            // Step 1
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_town_id' => 'required|exists:towns,id',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_county_id' => 'required|exists:counties,id',
            'receiver_town_id' => 'required|exists:towns,id',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            // Step 2 (items)
            'parcelItems' => 'required|array|min:1',
            'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
            'parcelItems.*.parcel_type' => 'required|string',
            'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'parcelItems.*.content_description' => 'required|string|max:1000',
            'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',

            // Step 3
            'payment_method' => 'required|in:cash,mpesa,card,bank_transfer,wallet',
            'payment_status' => 'required|in:pending,paid,partially_paid',
        ];

        if ($this->sender_email) {
            $rules['sender_email'] = 'email|max:255';
        }
        if ($this->receiver_email) {
            $rules['receiver_email'] = 'email|max:255';
        }

        return $rules;
    }

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
            'parcelItems.required' => 'At least one parcel item is required',
            'parcelItems.*.parcel_category_id.required' => 'Please select an item for each row',
            'parcelItems.*.parcel_category_id.exists' => 'The selected item is invalid',
            'parcelItems.*.parcel_type.required' => 'Parcel type is required for each item',
            'parcelItems.*.package_type.required' => 'Package type is required for each item',
            'parcelItems.*.content_description.required' => 'Please describe each item\'s contents',
            'payment_method.required' => 'Please select a payment method',
            'payment_status.required' => 'Please select payment status',
        ];
    }

    public function mount()
    {
        $loggedInUser = Auth::guard('partner')->user();
        $partner = match ($loggedInUser->user_type) {
            'pickup-dropoff' => $loggedInUser->partner,
            'pha' => $loggedInUser->parcelHandlingAssistant->partner,
            default => null,
        };

        $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
            ->where('status', true)
            ->with('town')
            ->orderBy('name')
            ->get();

        $this->senderTowns = $points->pluck('town')->unique('id')->values();

        $this->counties = County::whereHas('towns.pickUpAndDropOffPoint', function ($query) {
            $query->where('status', true);
        })
            ->orderBy('name')
            ->get();

        $this->parcel_number = Parcel::generateParcelNumber();
        $this->loadOptions();

        $this->weight_unit = 'kg';
        $this->payment_method = 'mpesa';
        $this->payment_status = 'pending';

        if (empty($this->parcelItems)) {
            $this->addItemRow();
        }

        $this->recalculateParcelTotals();
    }

    public function loadOptions()
    {
        try {
            $this->items = Item::where('status', true)->orderBy('name')->get();
            $this->weightRanges = WeightRange::all();
            $this->customers = [];
            $this->towns = Town::where('status', true)->orderBy('name')->get();
            $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
                ->orderBy('name')
                ->get();

            $this->pickupPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->deliveryPartners = Partner::where('verification_status', true)->get() ?? [];
            $this->drivers =  [];
            $this->transportPartners = Partner::where('verification_status', true)->get() ?? [];
        } catch (\Exception $e) {
            Log::error('Error loading options: ' . $e->getMessage());
        }
    }

    /* ===================== Item Repeater Methods ===================== */

    public function addItemRow(): void
    {
        $this->parcelItems[] = [
            'parcel_category_id'  => null,
            'parcel_type'         => '',
            'package_type'        => 'regular',
            'declared_value'      => 0,
            'insurance_required'  => false,
            'content_description' => '',
            'special_notes'       => '',
            'payment_on_delivery' => false,
            'base_price'          => 0,
            'item_insurance_amount' => 0,
            'tax_amount'          => 0,
            'item_total'          => 0,
        ];
    }

    public function removeItemRow(int $index): void
    {
        if (!isset($this->parcelItems[$index])) {
            return;
        }
        if (count($this->parcelItems) <= 1) {
            $this->addError('parcelItems', 'At least one item is required.');
            return;
        }
        unset($this->parcelItems[$index]);
        $this->parcelItems = array_values($this->parcelItems);
        $this->recalculateParcelTotals();
    }

    /**
     * Recompute per-item pricing then roll up into parcel-level totals.
     */
    public function recalculateParcelTotals(): void
    {
        $this->recalculateItems();

        $grandBase       = 0;
        $grandInsurance  = 0;
        $grandTax        = 0;
        $grandTotal      = 0;
        $grandDeclared   = 0;
        $grandWeight     = 0;

        foreach ($this->parcelItems as $row) {
            $grandBase      += (float) ($row['base_price'] ?? 0);
            $grandInsurance += (float) ($row['item_insurance_amount'] ?? 0);
            $grandTax       += (float) ($row['tax_amount'] ?? 0);
            $grandTotal     += (float) ($row['item_total'] ?? 0);
            $grandDeclared  += (float) ($row['declared_value'] ?? 0);
            $grandWeight    += $this->resolveItemWeight($row);
        }

        $this->base_price         = round($grandBase, 2);
        $this->insurance_charge   = round($grandInsurance, 2);
        $this->insurance_amount   = $this->insurance_charge;
        $this->tax_amount         = round($grandTax, 2);
        $this->total_amount       = round($grandTotal, 2);
        $this->declared_value     = $grandDeclared;
        $this->calculatedPrice    = $this->total_amount;
        $this->weight             = $grandWeight;

        if (count($this->parcelItems) === 1) {
            $first = $this->parcelItems[0];
            $this->parcel_type         = $first['parcel_type'] ?: $this->parcel_type;
            $this->package_type        = $first['package_type'] ?: $this->package_type;
            $this->content_description = $first['content_description'] ?: $this->content_description;
            $this->special_instructions = $first['special_notes'] ?: $this->special_instructions;
            $this->insurance_required  = (bool) $first['insurance_required'];
        }
    }

    /**
     * Resolve the weight for an item row. Pulls from Item catalogue
     * (weight column if present); falls back to 1 kg.
     */
    protected function resolveItemWeight(array $row): float
    {
        $id = $row['parcel_category_id'] ?? null;
        if (!$id) {
            return 0.0;
        }

        $catalogue = collect($this->items)->firstWhere('id', (int) $id);
        if (!$catalogue) {
            return 0.0;
        }

        foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
            if (isset($catalogue->{$col}) && is_numeric($catalogue->{$col}) && $catalogue->{$col} > 0) {
                return (float) $catalogue->{$col};
            }
        }
        return 1.0;
    }

    /**
     * Price each item individually using zone pricing on resolved item weight.
     */
    protected function recalculateItems(): void
    {
        $senderTown   = $this->sender_town_id ? Town::with('subCounty.county')->find($this->sender_town_id) : null;
        $receiverTown = $this->receiver_town_id ? Town::with('subCounty.county')->find($this->receiver_town_id) : null;

        $senderZone   = $senderTown ? ZoneTown::where('town_id', $senderTown->id)->first() : null;
        $receiverZone = $receiverTown ? ZoneTown::where('town_id', $receiverTown->id)->first() : null;

        $pricing = null;
        if ($senderZone && $receiverZone) {
            $pricing = PricingItem::where('source_zone_id', $senderZone->zone_id)
                ->where('destination_zone_id', $receiverZone->zone_id)
                ->first();
        }

        foreach ($this->parcelItems as &$row) {
            $weight   = $this->resolveItemWeight($row);
            $declared = (float) ($row['declared_value'] ?? 0);

            $basePrice = 0;
            $extraKgCost = 0;

            if ($pricing) {
                $basePrice   = (float) ($pricing->cost ?? 0);
                $extraKgCost = (float) ($pricing->extra ?? 0);

                if ($weight > 5) {
                    $extraWeight = $weight - 5;
                    $basePrice   = $basePrice + ($extraWeight * $extraKgCost);
                }
            } else {
                // Fallback price using item catalogue price if available, else parcel type map
                $catalogue = $row['parcel_category_id']
                    ? collect($this->items)->firstWhere('id', (int) $row['parcel_category_id'])
                    : null;

                if ($catalogue && isset($catalogue->price) && is_numeric($catalogue->price)) {
                    $basePrice = (float) $catalogue->price;
                } else {
                    $baseRates = [
                        'document' => 150,
                        'envelope' => 200,
                        'package'  => 300,
                        'box'      => 400,
                        'pallet'   => 800,
                        'other'    => 350,
                    ];
                    $basePrice = $baseRates[$row['parcel_type'] ?? 'package'] ?? 300;
                    if ($weight > 1) {
                        $basePrice += ($weight - 1) * 50;
                    }
                }
            }

            $pkg = $row['package_type'] ?? 'regular';
            switch ($pkg) {
                case 'fragile':
                case 'valuable':
                    $basePrice *= 1.2;
                    break;
                case 'perishable':
                    $basePrice *= 1.3;
                    break;
                case 'hazardous':
                    $basePrice *= 1.5;
                    break;
            }

            $basePrice = round($basePrice, 2);

            $insurance = !empty($row['insurance_required'])
                ? round($declared * 0.02, 2)
                : 0;

            $tax = round(($basePrice + $insurance) * 0.16, 2);

            $row['base_price']            = $basePrice;
            $row['item_insurance_amount'] = $insurance;
            $row['tax_amount']            = $tax;
            $row['item_total']            = round($basePrice + $insurance + $tax, 2);
        }
        unset($row);
    }

    /* ============ Livewire "updated" hooks ============ */

    public function updatedSenderTownId($value)
    {
        try {
            if ($value) {
                $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', (int)$value)
                    ->where('status', true)
                    ->orderBy('name')
                    ->get();
            }
            $this->recalculateParcelTotals();
        } catch (\Exception $e) {
            Log::error('Error updating sender details: ' . $e->getMessage());
        }
    }

    public function updatedReceiverTownId($value)
    {
        try {
            if ($value) {
                $town = Town::where('id', (int)$value)->first();
                $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', $town->id)
                    ->where('status', true)->get();
            } else {
                $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)->get();
            }
            $this->recalculateParcelTotals();
        } catch (\Exception $e) {
            Log::error('Error updating receiver town: ' . $e->getMessage());
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

    public function updatedParcelItems($value = null, $key = null): void
    {
        $this->recalculateParcelTotals();
    }

    /* ===================== Step Navigation ===================== */

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
                    'receiver_county_id' => 'required|exists:counties,id',
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
                    'parcelItems' => 'required|array|min:1',
                    'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
                    'parcelItems.*.parcel_type' => 'required|string',
                    'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
                    'parcelItems.*.content_description' => 'required|string|max:1000',
                    'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',
                ];
                break;
        }

        $this->validate($rules, $this->messages());

        if ($step === 2) {
            foreach ($this->parcelItems as $i => $row) {
                if (!empty($row['payment_on_delivery'])) {
                    $dv = (float) ($row['declared_value'] ?? 0);
                    if ($dv <= 0) {
                        $this->addError("parcelItems.$i.declared_value", 'Declared value is required when payment on delivery is selected.');
                        throw new \Illuminate\Validation\ValidationException(
                            validator([], []),
                            null,
                            null,
                            null
                        );
                    }
                    if (empty(trim($row['special_notes'] ?? ''))) {
                        $this->addError("parcelItems.$i.special_notes", 'Special notes with payment details are required when payment on delivery is selected.');
                        throw new \Illuminate\Validation\ValidationException(
                            validator([], []),
                            null,
                            null,
                            null
                        );
                    }
                }
            }
        }
    }

    /* ===================== Save ===================== */
    public function saveParcel(SMSService $smsService)
    {
        try {
            // Full validation (all steps)
            $this->validate($this->rules(), $this->messages());
            $this->recalculateParcelTotals();

            // Extra POD validation pass
            foreach ($this->parcelItems as $i => $row) {
                if (!empty($row['payment_on_delivery'])) {
                    $dv = (float) ($row['declared_value'] ?? 0);
                    if ($dv <= 0) {
                        $this->addError(
                            "parcelItems.$i.declared_value",
                            'Item #' . ($i + 1) . ': Declared value is required (greater than 0) when payment on delivery is selected.'
                        );
                        throw new \Illuminate\Validation\ValidationException(validator([], []));
                    }
                    if (empty(trim((string) ($row['special_notes'] ?? '')))) {
                        $this->addError(
                            "parcelItems.$i.special_notes",
                            'Item #' . ($i + 1) . ': Special notes with payment details are required when payment on delivery is selected.'
                        );
                        throw new \Illuminate\Validation\ValidationException(validator([], []));
                    }
                }
            }

            DB::beginTransaction();

            $senderTown   = Town::findOrFail($this->sender_town_id);
            $receiverTown = Town::findOrFail($this->receiver_town_id);

            $this->sender_county_id    = $senderTown->subCounty->county->id;
            $this->sender_subcounty_id = $senderTown->subCounty->id;
            $this->receiver_county_id  = $receiverTown->subCounty->county->id;
            $this->receiver_subcounty_id = $receiverTown->subCounty->id;

            $senderPoint = PickUpAndDropOffPoint::findOrFail($this->sender_pick_up_drop_off_point_id);
            $receivingPoint = PickUpAndDropOffPoint::findOrFail($this->delivery_pick_up_drop_off_point_id);

            // Sender contact
            $senderContact = Contact::create([
                'name'          => $this->sender_name,
                'phone'         => $this->sender_phone,
                'email'         => $this->sender_email,
                'address'       => $this->sender_address,
                'county_id'     => $this->sender_county_id,
                'sub_county_id' => $this->sender_subcounty_id,
                'town_id'       => $this->sender_town_id,
            ]);
            $this->sender_id = $senderContact->id;

            // Receiver contact
            $receiverContact = Contact::create([
                'name'          => $this->receiver_name,
                'phone'         => $this->receiver_phone,
                'email'         => $this->receiver_email,
                'address'       => $this->receiver_address,
                'county_id'     => $this->receiver_county_id,
                'sub_county_id' => $this->receiver_subcounty_id,
            ]);
            $this->receiver_id = $receiverContact->id;

            $partnerUser = Auth::guard('partner')->user();
            $partnerId   = $partnerUser->parcelHandlingAssistant?->partner?->id
                ?? $partnerUser->partner?->id;

            $createdParcels = [];

            // One Parcel per item
            foreach ($this->parcelItems as $index => $row) {
                $catalogueItem = Item::findOrFail($row['parcel_category_id']);

                $basePrice         = (float) ($row['base_price'] ?? $catalogueItem->price ?? 0);
                $declaredValue     = (float) ($row['declared_value'] ?? 0);
                $insuranceRequired = !empty($row['insurance_required']);
                $insuranceAmount   = (float) ($row['item_insurance_amount']
                    ?? ($insuranceRequired ? round($declaredValue * 0.02, 2) : 0));
                $taxAmount         = (float) ($row['tax_amount']
                    ?? round(($basePrice + $insuranceAmount) * 0.16, 2));
                $itemTotal         = (float) ($row['item_total']
                    ?? round($basePrice + $insuranceAmount + $taxAmount, 2));
                $paymentOnDelivery = !empty($row['payment_on_delivery']);

                // Resolve weight from catalogue (no manual input from the form)
                $resolvedWeight = 0.0;
                foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
                    if (
                        isset($catalogueItem->{$col})
                        && is_numeric($catalogueItem->{$col})
                        && $catalogueItem->{$col} > 0
                    ) {
                        $resolvedWeight = (float) $catalogueItem->{$col};
                        break;
                    }
                }
                if ($resolvedWeight <= 0) {
                    $resolvedWeight = 1.0;
                }

                $parcelData = [
                    'parcel_id'     => Parcel::generateParcelNumber(),
                    'customer_id'   => $this->customer_id,
                    'sender_id'     => $this->sender_id,
                    'receiver_id'   => $this->receiver_id,
                    'booking_type'  => $this->booking_type,
                    'booking_source' => $this->booking_source,

                    // Sender
                    'sender_name'        => $this->sender_name,
                    'sender_phone'       => $this->sender_phone,
                    'sender_email'       => $this->sender_email,
                    'sender_address'     => $this->sender_address,
                    'sender_county_id'   => $this->sender_county_id,
                    'sender_subcounty_id' => $this->sender_subcounty_id,
                    'sender_town_id'     => $this->sender_town_id,
                    'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
                    'sender_notes'       => $this->sender_notes,
                    'sender_partner_id'  => $senderPoint->partner?->id ?? $partnerId,
                    'pha_id'             => $partnerUser->parcelHandlingAssistant?->id ?? null,

                    // Receiver
                    'receiver_name'         => $this->receiver_name,
                    'receiver_phone'        => $this->receiver_phone,
                    'receiver_email'        => $this->receiver_email,
                    'receiver_address'      => $this->receiver_address,
                    'receiver_county_id'    => $this->receiver_county_id,
                    'receiver_subcounty_id' => $this->receiver_subcounty_id,
                    'receiver_town_id'      => $this->receiver_town_id,
                    'receiver_notes'        => $this->receiver_notes,
                    'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
                    'delivery_partner_id'   => $receivingPoint->partner?->id,

                    // Item / parcel details
                    'item_id'               => $catalogueItem->id,
                    'item_name'             => $catalogueItem->name,
                    'parcel_category_id'    => $row['parcel_category_id'],
                    'parcel_type'           => $row['parcel_type'],
                    'package_type'          => $row['package_type'],
                    'declared_value'        => $declaredValue,
                    'insurance_amount'      => $insuranceAmount,
                    'insurance_required'    => $insuranceRequired,
                    'content_description'   => $row['content_description'] ?? '',
                    'special_instructions'  => $row['special_notes'] ?? null,
                    'special_notes'         => $row['special_notes'] ?? null,
                    'payment_on_delivery'   => $paymentOnDelivery,

                    'weight'         => $resolvedWeight,
                    'weight_unit'    => $this->weight_unit,
                    'dimension_unit' => $this->dimension_unit,

                    // Pricing
                    'base_price'        => $basePrice,
                    'insurance_charge'  => $insuranceAmount,
                    'tax_amount'        => $taxAmount,
                    'total_amount'      => $itemTotal,
                    'payment_method'    => $this->payment_method,
                    'payment_status'    => $this->payment_status ?? 'pending',

                    // Status / audit
                    'current_status' => Parcel::STATUS_BOOKED,
                    'created_by'     => $partnerUser->id,
                    'creator_id'     => $partnerUser->id,
                    'creator_type'   => current_user_type(),

                    'transporter_id'   => null,
                    'transporter_type' => null,
                ];

                $parcel = Parcel::create($parcelData);
                $createdParcels[] = $parcel;

                // Payout per parcel
                $payout = $parcel->calculateParcelPayout(
                    (float) ($parcel->base_price + $parcel->tax_amount),
                    'direct'
                );

                ParcelPayout::create([
                    'parcel_id'          => $parcel->id,
                    'partner_id'         => $partnerId,
                    'type'               => 'pickup-dropoff',
                    'destination'        => null,
                    'destination_id'     => null,
                    'origin_id'          => $this->sender_pick_up_drop_off_point_id,
                    'amount'             => $payout['pick_up_drop_off_partner']['amount'],
                    'status'             => 'pending',
                    'paid_out_on'        => null,
                    'cancelation_reason' => null,
                ]);

                $parcel->updateParcelStatus(
                    Parcel::STATUS_CREATED,
                    $this->sender_pick_up_drop_off_point_id,
                    $partnerUser->id,
                    current_user_type(),
                    'Parcel created',
                    null,
                    null,
                );

                $parcel->addTracking(Parcel::STATUS_BOOKED, $partnerUser->id);
            }

            DB::commit();

            // If a single parcel was created, go straight to it.
            if (count($createdParcels) === 1) {
                return redirect()->route('partners.parcels.view', $createdParcels[0]->id);
            }

            // Multiple items -> return to index with a success message
            return redirect()
                ->route('partners.parcels.index')
                ->with('success', count($createdParcels) . ' parcels created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            Log::error('Parcel creation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            $this->addError('general', 'Failed to create parcel: ' . $e->getMessage());
        }
    }
    // public function saveParcel(SMSService $smsService)
    // {
    //     try {
    //         $this->validate($this->rules(), $this->messages());
    //         $this->recalculateParcelTotals();

    //         DB::beginTransaction();

    //         $senderTown = Town::findOrFail($this->sender_town_id);
    //         $receiverTown = Town::findOrFail($this->receiver_town_id);

    //         $this->sender_county_id = $senderTown->subCounty->county->id;
    //         $this->sender_subcounty_id = $senderTown->subCounty->id;

    //         $this->receiver_county_id = $receiverTown->subCounty->county->id;
    //         $this->receiver_subcounty_id  = $receiverTown->subCounty->id;

    //         $senderContact = Contact::create([
    //             'name' => $this->sender_name,
    //             'phone' => $this->sender_phone,
    //             'email' => $this->sender_email,
    //             'address' => $this->sender_address,
    //             'county_id' => $this->sender_county_id,
    //             'sub_county_id' => $this->sender_subcounty_id,
    //             'town_id' => $this->sender_town_id,
    //         ]);
    //         $this->sender_id = $senderContact->id;

    //         $receiverContact = Contact::create([
    //             'name' => $this->receiver_name,
    //             'phone' => $this->receiver_phone,
    //             'email' => $this->receiver_email,
    //             'address' => $this->receiver_address,
    //             'county_id' => $this->receiver_county_id,
    //             'sub_county_id' => $this->receiver_subcounty_id,
    //         ]);
    //         $this->receiver_id = $receiverContact->id;

    //         $primary = $this->parcelItems[0];

    //         $parcelData = [
    //             'parcel_id' => $this->parcel_number,
    //             'customer_id' => $this->customer_id,
    //             'sender_id' => $this->sender_id,
    //             'receiver_id' => $this->receiver_id,
    //             'booking_type' => $this->booking_type,

    //             'sender_name' => $this->sender_name,
    //             'sender_phone' => $this->sender_phone,
    //             'sender_email' => $this->sender_email,
    //             'sender_address' => $this->sender_address,
    //             'sender_county_id' => $this->sender_county_id,
    //             'sender_subcounty_id' => $this->sender_subcounty_id,
    //             'sender_town_id' => $this->sender_town_id,
    //             'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
    //             'sender_notes' => $this->sender_notes,
    //             'pha_id' => Auth::guard('partner')->user()->parcelHandlingAssistant?->id ?? NULL,
    //             'sender_partner_id' =>  Auth::guard('partner')->user()->parcelHandlingAssistant?->partner?->id ?? Auth::guard('partner')->user()->partner?->id,

    //             'receiver_name' => $this->receiver_name,
    //             'receiver_phone' => $this->receiver_phone,
    //             'receiver_email' => $this->receiver_email,
    //             'receiver_address' => $this->receiver_address,
    //             'receiver_county_id' => $this->receiver_county_id,
    //             'receiver_subcounty_id' => $this->receiver_subcounty_id,
    //             'receiver_town_id' => $this->receiver_town_id,
    //             'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
    //             'receiver_notes' => $this->receiver_notes,
    //             'delivery_partner_id' => PickUpAndDropOffPoint::where('id', $this->delivery_pick_up_drop_off_point_id)->value('partner_id'),

    //             'parcel_type' => $primary['parcel_type'] ?? null,
    //             'package_type' => $primary['package_type'] ?? 'regular',
    //             'weight' => $this->weight,
    //             'length' => $this->length ?: null,
    //             'width' => $this->width ?: null,
    //             'height' => $this->height ?: null,
    //             'dimension_unit' => $this->dimension_unit,
    //             'weight_unit' => $this->weight_unit,
    //             'declared_value' => $this->declared_value,
    //             'insurance_amount' => $this->insurance_charge,
    //             'insurance_required' => $this->insurance_required,
    //             'content_description' => $primary['content_description'] ?? '',
    //             'special_instructions' => $primary['special_notes'] ?? '',

    //             'base_price' => $this->base_price,
    //             'booking_source' => $this->booking_source,
    //             'weight_charge' => $this->weight_charge,
    //             'distance_charge' => $this->distance_charge,
    //             'special_handling_charge' => $this->special_handling_charge,
    //             'insurance_charge' => $this->insurance_charge,
    //             'tax_amount' => $this->tax_amount,
    //             'discount_amount' => $this->discount_amount,
    //             'total_amount' => $this->total_amount,
    //             'payment_method' => $this->payment_method,
    //             'payment_status' => $this->payment_status ?? 'pending',

    //             'current_status' => Parcel::STATUS_BOOKED,
    //             'created_by' => Auth::guard('partner')->user()->id,

    //             'transporter_id' => NULL,
    //             'transporter_type' => NULL,
    //             'creator_id' => Auth::guard('partner')->user()->id,
    //             'creator_type' => current_user_type(),
    //         ];

    //         $parcel = Parcel::create($parcelData);

    //         foreach ($this->parcelItems as $row) {
    //             $parcel->items()->create([
    //                 'parcel_category_id'  => $row['parcel_category_id'] ?? null,
    //                 'parcel_type'         => $row['parcel_type'],
    //                 'package_type'        => $row['package_type'],
    //                 'weight'              => $this->resolveItemWeight($row),
    //                 'weight_unit'         => $this->weight_unit,
    //                 'declared_value'      => $row['declared_value'] ?? 0,
    //                 'insurance_required'  => !empty($row['insurance_required']),
    //                 'insurance_amount'    => $row['item_insurance_amount'] ?? 0,
    //                 'base_price'          => $row['base_price'] ?? 0,
    //                 'tax_amount'          => $row['tax_amount'] ?? 0,
    //                 'item_total'          => $row['item_total'] ?? 0,
    //                 'content_description' => $row['content_description'] ?? '',
    //                 'special_notes'       => $row['special_notes'] ?? '',
    //                 'payment_on_delivery' => !empty($row['payment_on_delivery']),
    //             ]);
    //         }

    //         $payout = $parcel->calculateParcelPayout((float)($parcel->base_price + $parcel->tax_amount), 'direct');

    //         ParcelPayout::create([
    //             'parcel_id' => $parcel->id,
    //             'partner_id' => Auth::guard('partner')->user()->parcelHandlingAssistant?->partner?->id ?? Auth::guard('partner')->user()->partner?->id,
    //             'type' => 'pickup-dropoff',
    //             'destination' => null,
    //             'destination_id' => null,
    //             'origin_id' => $this->sender_pick_up_drop_off_point_id,
    //             'amount' => $payout['pick_up_drop_off_partner']['amount'],
    //             'status' => 'pending',
    //             'paid_out_on' => null,
    //             'cancelation_reason' => null
    //         ]);

    //         $parcel->updateParcelStatus(
    //             Parcel::STATUS_CREATED,
    //             $this->sender_pick_up_drop_off_point_id,
    //             Auth::guard('partner')->user()->id,
    //             current_user_type(),
    //             'Parcel created',
    //             null,
    //             null,
    //         );

    //         if ($parcel) {
    //             $parcel->addTracking(Parcel::STATUS_BOOKED, Auth::guard('partner')->user()->id);
    //         }

    //         DB::commit();

    //         return redirect()->route('partners.parcels.view', $parcel->id);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollBack();
    //         throw $e;
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Parcel creation error: ' . $e->getMessage());
    //         $this->addError('general', 'Failed to create parcel: ' . $e->getMessage());
    //     }
    // }

    public function render()
    {
        if (!empty($this->parcelItems)) {
            $this->recalculateParcelTotals();
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
            'catalogueItems' => $this->items,
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
                'mpesa' => 'M-Pesa',
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


// namespace App\Livewire\Partners\Parcels;

// use App\Livewire\Admin\Settings\Pricing\Pricings;
// use App\Mail\NewParcel;
// use App\Models\Parcel;
// use App\Models\County;
// use App\Models\SubCounty;
// use App\Models\Town;
// use App\Models\User;
// use App\Models\Contact;
// use App\Models\Item;
// use App\Models\ParcelPayout;
// use App\Models\Partner;
// use App\Models\PickUpAndDropOffPoint;
// use App\Models\Pricing;
// use App\Models\PricingItem;
// use App\Models\WeightRange;
// use App\Models\ZoneCounty;
// use App\Models\ZoneTown;
// use App\Services\SMSService;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
// use Livewire\Component;

// class CreateParcel extends Component
// {
//     // Step Management
//     public $currentStep = 1;
//     public $totalSteps = 3;

//     // Parcel Data
//     public $parcel_number;
//     public $customer_id;
//     public $sender_id;
//     public $receiver_id;
//     public $booking_type = 'instant';
//     public $booking_source = 'partner';

//     // Sender Information
//     public $sender_name = '';
//     public $sender_phone = '';
//     public $sender_email = '';
//     public $sender_address = '';
//     public $sender_county_id = '';
//     public $sender_subcounty_id = '';
//     public $sender_town_id = '';
//     public $sender_notes = '';
//     public $sender_pick_up_drop_off_point_id;
//     public $save_sender_as_contact = false;
//     public $senderTowns = [];

//     // Receiver Information
//     public $receiver_name = '';
//     public $receiver_phone = '';
//     public $receiver_email = '';
//     public $receiver_address = '';
//     public $receiver_county_id = '';
//     public $receiver_subcounty_id = '';
//     public $receiver_town_id = '';
//     public $countyTowns  = [];
//     public $receiver_notes = '';
//     public $delivery_pick_up_drop_off_point_id;
//     public $save_receiver_as_contact = false;
//     public $receiverPickUpAndDropOffPoints = [];

//     // Parcel Details
//     public $parcel_type;
//     public $package_type = 'regular';
//     public $weight = '';
//     public $length = '';
//     public $width = '';
//     public $height = '';
//     public $dimension_unit = 'cm';
//     public $weight_unit = 'kg';
//     public $declared_value = 0;
//     public $insurance_amount = 0;
//     public $insurance_required = false;
//     public $content_description = '';
//     public $special_instructions = '';

//     // Pricing
//     public $base_price = 0;
//     public $weight_charge = 0;
//     public $distance_charge = 0;
//     public $special_handling_charge = 0;
//     public $insurance_charge = 0;
//     public $tax_amount = 0;
//     public $discount_amount = 0;
//     public $total_amount = 0;
//     public $payment_method = 'mpesa';
//     public $payment_status = 'pending';

//     // Options
//     public $customers = [];
//     public $counties = [];
//     public $subcounties = [];
//     public $towns = [];
//     public $pickupPartners = [];
//     public $deliveryPartners = [];
//     public $drivers = [];
//     public $transportPartners = [];
//     public $pickUpAndDropOffPoints = [];

//     // Computed data
//     public $calculatedPrice = 0;
//     public $isCalculating = false;

//     public $items = [];

//     public $weightRanges = [];
//     public $partner_id;

//     public $senderPickUpAndDropOffPoints = [];
//     protected SMSService $smsService;

//     // Validation rules
//     protected function rules()
//     {
//         $rules = [
//             // Step 1 Rules
//             'sender_name' => 'required|string|max:255',
//             'sender_phone' => 'required|string|max:20',
//             'sender_town_id' => 'required|exists:towns,id',
//             'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//             'receiver_name' => 'required|string|max:255',
//             'receiver_phone' => 'required|string|max:20',
//             'receiver_county_id' => 'required|exists:counties,id',
//             'receiver_town_id' => 'required|exists:towns,id',
//             'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

//             // Step 2 Rules
//             'parcel_type' => 'required',
//             'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//             'weight' => 'required|numeric|min:0.1|max:1000',
//             'content_description' => 'required|string|max:1000',
//             'declared_value' => 'nullable|numeric|min:0|max:1000000',

//             // Step 3 Rules
//             'payment_method' => 'required|in:cash,mpesa,card,bank_transfer,wallet',
//             'payment_status' => 'required|in:pending,paid,partially_paid',
//         ];

//         // Optional fields with conditional validation
//         if ($this->sender_email) {
//             $rules['sender_email'] = 'email|max:255';
//         }

//         if ($this->receiver_email) {
//             $rules['receiver_email'] = 'email|max:255';
//         }

//         if ($this->length || $this->width || $this->height) {
//             $rules['length'] = 'nullable|numeric|min:1|max:500';
//             $rules['width'] = 'nullable|numeric|min:1|max:500';
//             $rules['height'] = 'nullable|numeric|min:1|max:500';
//         }

//         return $rules;
//     }

//     // Custom validation messages
//     protected function messages()
//     {
//         return [
//             'sender_name.required' => 'Sender name is required',
//             'sender_phone.required' => 'Sender phone number is required',
//             'sender_town_id.required' => 'Please select sender town',
//             'sender_pick_up_drop_off_point_id.required' => 'Please select pickup point',
//             'receiver_name.required' => 'Receiver name is required',
//             'receiver_phone.required' => 'Receiver phone number is required',
//             'receiver_town_id.required' => 'Please select receiver town',
//             'delivery_pick_up_drop_off_point_id.required' => 'Please select delivery point',
//             'weight.required' => 'Parcel weight is required',
//             'weight.min' => 'Weight must be at least 0.1 kg',
//             'weight.max' => 'Weight cannot exceed 1000 kg',
//             'content_description.required' => 'Please describe the parcel contents',
//             'payment_method.required' => 'Please select a payment method',
//             'payment_status.required' => 'Please select payment status',
//         ];
//     }

//     public function mount()
//     {
//         $loggedInUser = Auth::guard('partner')->user();
//         $partner = match ($loggedInUser->user_type) {
//             'pickup-dropoff' => $loggedInUser->partner,
//             'pha' => $loggedInUser->parcelHandlingAssistant->partner,
//             default => null,
//         };


//         $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
//             ->where('status', true)
//             ->with('town')
//             ->orderBy('name')
//             ->get();

//         $this->senderTowns = $points->pluck('town')->unique('id')->values();

//         // $this->counties = County::orderBy('name')->get();

//         $this->counties = County::whereHas('towns.pickUpAndDropOffPoint', function ($query) {
//             $query->where('status', true);
//         })
//             ->orderBy('name')
//             ->get();

//         $this->parcel_number = Parcel::generateParcelNumber();
//         $this->loadOptions();

//         $this->weight_unit = 'kg';
//         $this->payment_method = 'mpesa';
//         $this->payment_status = 'pending';

//         // Initial price calculation if default values exist
//         if ($this->parcel_type && $this->weight) {
//             // $this->calculatePriceByTypeAndWeight();
//             $this->calculatePriceByWeight();
//         }
//     }

//     public function loadOptions()
//     {
//         try {
//             $this->items = Item::where('status', true)->get();

//             $this->weightRanges = WeightRange::all();
//             $this->customers = [];
//             $this->towns = Town::where('status', true)->orderBy('name')->get();
//             $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
//                 ->orderBy('name')
//                 ->get();

//             // Load other options with error handling
//             $this->pickupPartners = Partner::where('verification_status', true)->get() ?? [];
//             $this->deliveryPartners = Partner::where('verification_status', true)->get() ?? [];
//             $this->drivers =  [];
//             $this->transportPartners = Partner::where('verification_status', true)->get() ?? [];
//         } catch (\Exception $e) {
//             dd('Error loading options: ' . $e->getMessage());
//             Log::error('Error loading options: ' . $e->getMessage());
//         }
//     }

//     public function updatedSenderTownId($value)
//     {
//         try {
//             if ($value) {
//                 $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', (int)$value)
//                     ->where('status', true)
//                     ->orderBy('name')
//                     ->get();
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender details: ' . $e->getMessage());
//         }
//     }

//     public function updatedSenderCountyId($value)
//     {
//         try {
//             if ($value) {

//                 $this->subcounties = SubCounty::where('county_id', $value)
//                     ->orderBy('name')
//                     ->get();
//             } else {
//                 $this->subcounties = [];
//                 $this->sender_subcounty_id = '';
//                 $this->sender_town_id = '';
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender county: ' . $e->getMessage());
//         }
//     }

//     public function updatedSenderSubcountyId($value)
//     {
//         try {
//             if ($value) {
//                 $this->towns = Town::where('sub_county_id', $value)
//                     ->orderBy('name')
//                     ->get();
//             } else {
//                 $this->sender_town_id = '';
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender subcounty: ' . $e->getMessage());
//         }
//     }

//     // public function updatedParcelType()
//     // {
//     //     $this->calculatePriceByTypeAndWeight();
//     // }

//     public function updatedWeight()
//     {
//         $this->calculatePriceByWeight();
//         // $this->calculatePriceByTypeAndWeight();
//     }

//     /**
//      * Calculate insurance charge based on declared value (2%)
//      */
//     protected function calculateInsurance()
//     {
//         if ($this->insurance_required && $this->declared_value > 0) {
//             $this->insurance_charge = round($this->declared_value * 0.02, 2); // 2% of declared value
//         } else {
//             $this->insurance_charge = 0;
//         }
//     }

//     /**
//      * Update insurance when declared value or insurance checkbox changes
//      */
//     public function updatedDeclaredValue()
//     {
//         $this->calculateInsurance();
//         $this->calculatePriceByWeight();
//         // $this->calculatePriceByTypeAndWeight();
//     }

//     /**
//      * Update insurance when checkbox is toggled
//      */
//     public function updatedInsuranceRequired()
//     {
//         $this->calculateInsurance();
//         $this->calculatePriceByWeight();
//         // $this->calculatePriceByTypeAndWeight();
//     }

//     public function calculatePriceByWeight()
//     {
//         $this->base_price = 0;
//         $this->tax_amount = 0;
//         $this->total_amount = 0;
//         $this->calculatedPrice = 0;

//         $this->calculateInsurance();

//         if (
//             !$this->weight ||
//             $this->weight <= 0 ||
//             !$this->sender_town_id ||
//             !$this->receiver_town_id
//         ) {
//             return;
//         }

//         try {
//             $senderTown = Town::with('subCounty.county')
//                 ->find($this->sender_town_id);

//             $receiverTown = Town::with('subCounty.county')
//                 ->find($this->receiver_town_id);

//             if (!$senderTown || !$receiverTown) {
//                 return;
//             }


//             $senderZone = ZoneTown::where('town_id', $senderTown->id)->first();
//             $receiverZone = ZoneTown::where('town_id', $receiverTown->id)->first();

//             if (!$senderZone || !$receiverZone) {
//                 return;
//             }

//             $pricing = PricingItem::where('source_zone_id', $senderZone->zone_id)
//                 ->where('destination_zone_id', $receiverZone->zone_id)->first();

//             if (!$pricing) {
//                 $this->calculateFallbackPrice();
//                 return;
//             }

//             $basePrice = (float) ($pricing->cost ?? 0);
//             $extraKgCost = (float) ($pricing->extra ?? 0);

//             if ($this->weight <= 5) {

//                 $this->base_price = round($basePrice, 2);
//             } else {

//                 $extraWeight = $this->weight - 5;

//                 $this->base_price = round(
//                     $basePrice + ($extraWeight * $extraKgCost),
//                     2
//                 );
//             }

//             $this->tax_amount = round(
//                 $this->base_price * 0.16,
//                 2
//             );

//             $this->total_amount = round(
//                 $this->base_price +
//                     $this->insurance_charge +
//                     $this->tax_amount,
//                 2
//             );

//             $this->calculatedPrice = $this->total_amount;

//             Log::info('Parcel price calculated', [
//                 'parcel_type' => $this->parcel_type,
//                 'weight' => $this->weight,
//                 'base_price' => $this->base_price,
//                 'extra_kg_cost' => $extraKgCost,
//                 'insurance_charge' => $this->insurance_charge,
//                 'tax_amount' => $this->tax_amount,
//                 'total_amount' => $this->total_amount,
//                 'sender_zone' => $senderZone->zone_id,
//                 'receiver_zone' => $receiverZone->zone_id,
//             ]);
//         } catch (\Throwable $e) {

//             Log::error('Price calculation failed', [
//                 'message' => $e->getMessage(),
//                 'trace' => $e->getTraceAsString(),
//             ]);

//             $this->calculateFallbackPrice();
//         }
//     }

//     public function calculatePriceByTypeAndWeight()
//     {
//         // Reset all price components
//         $this->base_price = 0;
//         $this->tax_amount = 0;
//         $this->total_amount = 0;
//         $this->calculatedPrice = 0;

//         // Calculate insurance first
//         $this->calculateInsurance();

//         if ($this->sender_town_id && $this->receiver_town_id) {
//             $senderCounty = $this->sender_town_id ? Town::find($this->sender_town_id)->subCounty->county : null;
//             $receiverCounty = $this->receiver_town_id ? Town::find($this->receiver_town_id)->subCounty->county : null;

//             $senderZone = ZoneCounty::where('county_id', $senderCounty->id)->first();
//             $receiverZone = ZoneCounty::where('county_id', $receiverCounty->id)->first();
//         }


//         // Check if we have the required data for calculation
//         if (!$this->parcel_type || !$this->weight || $this->weight <= 0) {
//             return;
//         }

//         try {
//             // TODO::check the correct pricing
//             $pricing = Pricing::where('item_id', $this->parcel_type)
//                 ->where('min_weight', '<=', $this->weight)
//                 ->where('max_weight', '>=', $this->weight)
//                 ->first();

//             $pricing = $pricing->items->where('source_zone_id', $senderZone->zone_id)->where('destination_zone_id', $receiverZone->zone_id)->first();

//             // If no exact weight match, find the closest weight range
//             if (!$pricing) {
//                 $pricing = Pricing::where('item_id', $this->parcel_type)
//                     ->where('min_weight', '<=', $this->weight)
//                     ->orderBy('max_weight', 'asc')
//                     ->first();
//             }

//             // If still no pricing found, get the default pricing for this item
//             if (!$pricing) {
//                 $pricing = Pricing::where('item_id', $this->parcel_type)
//                     ->orderBy('min_weight', 'asc')
//                     ->first();
//             }

//             if ($pricing) {
//                 $baseRate = $pricing->cost ?? $pricing->cost ?? 0;
//                 $minimumCharge = $pricing->cost ?? 0;

//                 $weightPrice = $this->weight * $baseRate;
//                 $this->base_price = round($minimumCharge, 2);

//                 // Add any fixed cost from pricing
//                 if (isset($pricing->cost) && $pricing->cost > 0) {
//                     $this->base_price = round($pricing->cost, 2);
//                 }

//                 // Calculate tax (16% VAT) - tax applies to base price
//                 $taxableAmount = $this->base_price;
//                 $this->tax_amount = round($taxableAmount * 0.16, 2);

//                 // Calculate total (base price + insurance + tax)
//                 $this->total_amount = round($this->base_price + $this->insurance_charge + $this->tax_amount, 2);
//                 $this->calculatedPrice = $this->total_amount;

//                 // Log for debugging (remove in production)
//                 Log::info('Price calculated:', [
//                     'parcel_type' => $this->parcel_type,
//                     'weight' => $this->weight,
//                     'base_price' => $this->base_price,
//                     'insurance_charge' => $this->insurance_charge,
//                     'tax' => $this->tax_amount,
//                     'total' => $this->total_amount
//                 ]);
//             } else {
//                 // Fallback calculation if no pricing found
//                 $this->calculateFallbackPrice();
//             }
//         } catch (\Exception $e) {
//             Log::error('Price calculation error: ' . $e->getMessage());
//             $this->calculateFallbackPrice();
//         }
//     }

//     /**
//      * Fallback price calculation method
//      */
//     protected function calculateFallbackPrice()
//     {
//         // Define base rates by parcel type (as fallback)
//         $baseRates = [
//             'document' => 150,
//             'envelope' => 200,
//             'package' => 300,
//             'box' => 400,
//             'pallet' => 800,
//             'other' => 350,
//         ];

//         // Get base rate for parcel type, default to 300 if not found
//         $baseRate = $baseRates[$this->parcel_type] ?? 300;

//         // Calculate weight multiplier (example: additional charge per kg)
//         $weightMultiplier = 0;
//         if ($this->weight > 1) {
//             $weightMultiplier = ($this->weight - 1) * 50; // KES 50 per additional kg
//         }

//         // Calculate base price
//         $this->base_price = round($baseRate + $weightMultiplier, 2);

//         // Calculate tax (16% VAT) - tax applies to base price + insurance
//         $taxableAmount = $this->base_price;
//         $this->tax_amount = round($taxableAmount * 0.16, 2);

//         // Calculate total (base price + insurance + tax)
//         $this->total_amount = round($this->base_price + $this->insurance_charge + $this->tax_amount, 2);
//         $this->calculatedPrice = $this->total_amount;
//     }

//     /**
//      * Updated method to handle all price calculation triggers
//      */
//     public function updated($propertyName)
//     {
//         // Trigger price calculation when relevant fields change
//         $priceRelatedFields = [
//             'parcel_type',
//             'weight',
//             'sender_town_id',
//             'receiver_town_id',
//             'package_type',
//             'declared_value',
//             'insurance_required'
//         ];

//         if (in_array($propertyName, $priceRelatedFields)) {
//             // $this->calculatePriceByTypeAndWeight();
//             $this->calculatePriceByWeight();
//         }
//     }

//     /**
//      * Simplified price calculation for the view
//      */
//     public function getCalculatedPriceProperty()
//     {
//         // $this->calculatePriceByTypeAndWeight();
//         $this->calculatePriceByWeight();

//         return $this->total_amount;
//     }

//     public function getPricings()
//     {
//         return Pricing::with('item')
//             ->orderBy('item_id')
//             ->orderBy('min_weight')
//             ->get()
//             ->groupBy('item_id');
//     }

//     public function senderPickUpDropOffPointId($value)
//     {
//         try {
//             if ($value) {
//                 $point = PickUpAndDropOffPoint::where('id', (int)$value)->first();
//                 $this->sender_town_id = $point->town_id;
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender details: ' . $e->getMessage());
//         }
//     }

//     public function updatedReceiverCountyId($value)
//     {
//         try {
//             if ($value) {
//                 $county = County::where('id', (int)$value)->first();
//                 // $this->countyTowns = $county->towns()->where('status', true)->get();
//                 $this->countyTowns = $county->towns()
//                     ->whereHas('pickUpAndDropOffPoint', function ($query) {
//                         $query->where('status', true);
//                     })
//                     ->get();
//             } else {
//                 $this->countyTowns = Town::where('status', true)->orderBy('name')->get();
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender county: ' . $e->getMessage());
//         }
//     }

//     public function updatedReceiverTownId($value)
//     {
//         try {
//             if ($value) {
//                 $town = Town::where('id', (int)$value)->first();
//                 $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', $town->id)->where('status', true)->get();
//             } else {
//                 $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)->get();
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating sender county: ' . $e->getMessage());
//         }
//     }

//     public function nextStep()
//     {
//         if ($this->currentStep < $this->totalSteps) {
//             $this->validateStep($this->currentStep);
//             $this->currentStep++;
//         }
//     }

//     public function previousStep()
//     {
//         if ($this->currentStep > 1) {
//             $this->currentStep--;
//         }
//     }

//     protected function validateStep($step)
//     {
//         $rules = [];

//         switch ($step) {
//             case 1:
//                 $rules = [
//                     'sender_name' => 'required|string|max:255',
//                     'sender_phone' => 'required|string|max:20',
//                     'sender_town_id' => 'required|exists:towns,id',
//                     'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//                     'receiver_name' => 'required|string|max:255',
//                     'receiver_phone' => 'required|string|max:20',
//                     'receiver_town_id' => 'required|exists:towns,id',
//                     'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//                 ];

//                 if ($this->sender_email) {
//                     $rules['sender_email'] = 'email|max:255';
//                 }

//                 if ($this->receiver_email) {
//                     $rules['receiver_email'] = 'email|max:255';
//                 }
//                 break;

//             case 2:
//                 $rules = [
//                     'parcel_type' => 'required',
//                     'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//                     'weight' => 'required|numeric|min:0.1|max:1000',
//                     'content_description' => 'required|string|max:1000',
//                     'declared_value' => 'nullable|numeric|min:0|max:1000000',
//                 ];
//                 break;
//         }

//         try {
//             $this->validate($rules, $this->messages());
//         } catch (\Illuminate\Validation\ValidationException $e) {

//             throw $e;
//         }
//     }

//     public function saveParcel(SMSService $smsService)
//     {
//         try {
//             // Validate all steps
//             $this->validate($this->rules(), $this->messages());

//             DB::beginTransaction();

//             $senderTown = Town::findOrFail($this->sender_town_id);
//             $receiverTown = Town::findOrFail($this->receiver_town_id);

//             $this->sender_county_id = $senderTown->subCounty->county->id;
//             $this->sender_subcounty_id = $senderTown->subCounty->id;

//             $this->receiver_county_id = $receiverTown->subCounty->county->id;
//             $this->receiver_subcounty_id  = $receiverTown->subCounty->id;

//             // Save sender as contact if requested
//             $senderContact = Contact::create([
//                 'name' => $this->sender_name,
//                 'phone' => $this->sender_phone,
//                 'email' => $this->sender_email,
//                 'address' => $this->sender_address,
//                 'county_id' => $this->sender_county_id,
//                 'sub_county_id' => $this->sender_subcounty_id,
//                 'town_id' => $this->sender_town_id,
//             ]);
//             $this->sender_id = $senderContact->id;

//             // Save receiver as contact if requested
//             $receiverContact = Contact::create([
//                 'name' => $this->receiver_name,
//                 'phone' => $this->receiver_phone,
//                 'email' => $this->receiver_email,
//                 'address' => $this->receiver_address,
//                 'county_id' => $this->receiver_county_id,
//                 'sub_county_id' => $this->receiver_subcounty_id,
//             ]);
//             $this->receiver_id = $receiverContact->id;

//             // Create parcel
//             $parcelData = [
//                 'parcel_id' => $this->parcel_number,
//                 'customer_id' => $this->customer_id,
//                 'sender_id' => $this->sender_id,
//                 'receiver_id' => $this->receiver_id,
//                 'booking_type' => $this->booking_type,

//                 // Sender information
//                 'sender_name' => $this->sender_name,
//                 'sender_phone' => $this->sender_phone,
//                 'sender_email' => $this->sender_email,
//                 'sender_address' => $this->sender_address,
//                 'sender_county_id' => $this->sender_county_id,
//                 'sender_subcounty_id' => $this->sender_subcounty_id,
//                 'sender_town_id' => $this->sender_town_id,
//                 'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
//                 'sender_notes' => $this->sender_notes,
//                 'pha_id' => Auth::guard('partner')->user()->parcelHandlingAssistant?->id ?? NULL,
//                 'sender_partner_id' =>  Auth::guard('partner')->user()->parcelHandlingAssistant?->partner?->id ?? Auth::guard('partner')->user()->partner?->id,

//                 // Receiver information
//                 'receiver_name' => $this->receiver_name,
//                 'receiver_phone' => $this->receiver_phone,
//                 'receiver_email' => $this->receiver_email,
//                 'receiver_address' => $this->receiver_address,
//                 'receiver_county_id' => $this->receiver_county_id,
//                 'receiver_subcounty_id' => $this->receiver_subcounty_id,
//                 'receiver_town_id' => $this->receiver_town_id,
//                 'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
//                 'receiver_notes' => $this->receiver_notes,
//                 'delivery_partner_id' => PickUpAndDropOffPoint::where('id', $this->delivery_pick_up_drop_off_point_id)->value('partner_id'),

//                 // Parcel details
//                 'parcel_type' => $this->parcel_type,
//                 'package_type' => $this->package_type,
//                 'weight' => $this->weight,
//                 'length' => $this->length ?: null,
//                 'width' => $this->width ?: null,
//                 'height' => $this->height ?: null,
//                 'dimension_unit' => $this->dimension_unit,
//                 'weight_unit' => $this->weight_unit,
//                 'declared_value' => $this->declared_value,
//                 'insurance_amount' => $this->insurance_charge,
//                 'insurance_required' => $this->insurance_required,
//                 'content_description' => $this->content_description,
//                 'special_instructions' => $this->special_instructions,

//                 // Pricing
//                 'base_price' => $this->base_price,
//                 'booking_source' => $this->booking_source,
//                 'weight_charge' => $this->weight_charge,
//                 'distance_charge' => $this->distance_charge,
//                 'special_handling_charge' => $this->special_handling_charge,
//                 'insurance_charge' => $this->insurance_charge,
//                 'tax_amount' => $this->tax_amount,
//                 'discount_amount' => $this->discount_amount,
//                 'total_amount' => $this->total_amount,
//                 'payment_method' => $this->payment_method,
//                 'payment_status' => $this->payment_status ?? 'pending',

//                 'current_status' => Parcel::STATUS_BOOKED,
//                 // System fields
//                 'created_by' => Auth::guard('partner')->user()->id,

//                 'transporter_id' => NULL,
//                 'transporter_type' => NULL,
//                 'creator_id' => Auth::guard('partner')->user()->id,
//                 'creator_type' => current_user_type(),
//             ];

//             $parcel = Parcel::create($parcelData);
//             $payout = $parcel->calculateParcelPayout((float)($parcel->base_price + $parcel->tax_amount), 'direct');

//             ParcelPayout::create([
//                 'parcel_id' => $parcel->id,
//                 'partner_id' => Auth::guard('partner')->user()->parcelHandlingAssistant?->partner?->id ?? Auth::guard('partner')->user()->partner?->id,
//                 'type' => 'pickup-dropoff',
//                 'destination' => null,
//                 'destination_id' => null,
//                 'origin_id' => $this->sender_pick_up_drop_off_point_id,
//                 'amount' => $payout['pick_up_drop_off_partner']['amount'],
//                 'status' => 'pending',
//                 'paid_out_on' => null,
//                 'cancelation_reason' => null
//             ]);

//             $parcel->updateParcelStatus(
//                 Parcel::STATUS_CREATED,
//                 $this->sender_pick_up_drop_off_point_id,
//                 Auth::guard('partner')->user()->id,
//                 current_user_type(),
//                 'Parcel created',
//                 null,
//                 null,
//             );

//             // Create initial tracking record
//             if ($parcel) {
//                 $parcel->addTracking(Parcel::STATUS_BOOKED, Auth::guard('partner')->user()->id);
//             }

//             DB::commit();

//             return redirect()->route('partners.parcels.view', $parcel->id);
//         } catch (\Illuminate\Validation\ValidationException $e) {
//             dd($e->getMessage());
//             DB::rollBack();
//             throw $e;
//         } catch (\Exception $e) {
//             dd($e->getMessage());
//             DB::rollBack();
//             Log::error('Parcel creation error: ' . $e->getMessage());
//         }
//     }

//     public function render()
//     {
//         if ($this->parcel_type && $this->weight > 0) {
//             // $this->calculatePriceByTypeAndWeight();
//             $this->calculatePriceByWeight();
//         }

//         return view('livewire.partners.parcels.create-parcel', [
//             'counties' => $this->counties,
//             'subcounties' => $this->subcounties,
//             'towns' => $this->towns,
//             'customers' => $this->customers,
//             'pickupPartners' => $this->pickupPartners,
//             'deliveryPartners' => $this->deliveryPartners,
//             'drivers' => $this->drivers,
//             'transportPartners' => $this->transportPartners,
//             'pickUpAndDropOffPoints' => $this->pickUpAndDropOffPoints,
//             // 'parcelTypes' => $this->items,
//             'parcelTypes' => [
//                 'document' => 'Document',
//                 'package' => 'Package',
//                 'envelope' => 'Envelope',
//                 'box' => 'Box',
//                 'pallet' => 'Pallet',
//                 'other' => 'Other',
//             ],
//             'packageTypes' => [
//                 'regular' => 'Regular',
//                 'fragile' => 'Fragile',
//                 'perishable' => 'Perishable',
//                 'valuable' => 'Valuable',
//                 'hazardous' => 'Hazardous',
//                 'oversized' => 'Oversized',
//             ],
//             'paymentMethods' => [
//                 'mpesa' => 'M-Pesa',
//             ],
//             'paymentStatuses' => [
//                 'pending' => 'Pending',
//                 'paid' => 'Paid',
//                 'partially_paid' => 'Partially Paid',
//             ],
//             'bookingTypes' => [
//                 'instant' => 'Instant Delivery',
//                 'scheduled' => 'Scheduled Delivery',
//                 'bulk' => 'Bulk Shipment',
//             ],
//         ]);
//     }
// }
