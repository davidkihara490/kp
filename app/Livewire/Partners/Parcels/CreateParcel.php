<?php

namespace App\Livewire\Partners\Parcels;

use App\Models\Contact;
use App\Models\County;
use App\Models\Item;
use App\Models\Parcel;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use App\Services\PricingCalculator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CreateParcel extends Component
{
    // ---------- Wizard state ----------
    public int $currentStep = 1;
    public int $totalSteps  = 5;   // Route → Items → Partner → PUDO+S/R → Review

    // ---------- Partner context ----------
    public $partner_id;
    public $parcel_number;

    // ---------- Step 1: Route ----------
    public $sender_town_id = '';
    public $receiver_county_id = '';
    public $receiver_town_id = '';

    // ---------- Step 2: Items ----------
    /** @var array<int, array<string, mixed>> */
    public array $parcelItems = [];

    // ---------- Step 3: Transport partner ----------
    public $transport_partner_id = null;
    /** @var array<int, array<string, mixed>> */
    public array $couriers = [];
    /** @var array<string, mixed>|null */
    public ?array $selectedBreakdown = null;

    // ---------- Step 4: PUDO + Sender/Receiver ----------
    public $sender_name = '';
    public $sender_phone = '';
    public $sender_email = '';
    public $sender_address = '';
    public $sender_pick_up_drop_off_point_id;

    public $receiver_name = '';
    public $receiver_phone = '';
    public $receiver_email = '';
    public $receiver_address = '';
    public $delivery_pick_up_drop_off_point_id;

    // ---------- Step 5: Payment ----------
    public $payment_method = 'mpesa';
    public $payment_status = 'pending';

    // ---------- Lookups ----------
    public $senderTowns = [];
    public $counties = [];
    public $countyTowns = [];
    public $senderPickUpAndDropOffPoints = [];
    public $receiverPickUpAndDropOffPoints = [];
    public $items = [];
    public $pickUpAndDropOffPoints = [];

    protected $pricing;

    public function boot(PricingCalculator $pricing): void
    {
        $this->pricing = $pricing;
    }

    // ---------- Validation ----------
    protected function rules(): array
    {
        return [
            'sender_town_id' => 'required|exists:towns,id',
            'receiver_county_id' => 'required|exists:counties,id',
            'receiver_town_id' => 'required|exists:towns,id',

            'parcelItems' => 'required|array|min:1',
            'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
            'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'parcelItems.*.content_description' => 'required|string|max:1000',
            'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',

            'transport_partner_id' => 'required|exists:partners,id',

            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_email' => 'nullable|email|max:255',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            'payment_method' => 'required|in:mpesa',
            'payment_status' => 'required|in:pending',
        ];
    }

    protected function messages(): array
    {
        return [
            'sender_town_id.required' => 'Please select the sender town.',
            'receiver_county_id.required' => 'Please select the receiver county.',
            'receiver_town_id.required' => 'Please select the receiver town.',
            'parcelItems.required' => 'Add at least one item.',
            'parcelItems.*.parcel_category_id.required' => 'Select a category for each item.',
            'parcelItems.*.package_type.required' => 'Select a package type for each item.',
            'parcelItems.*.content_description.required' => 'Describe the contents of each item.',
            'transport_partner_id.required' => 'Choose a transport partner before continuing.',
            'sender_name.required' => 'Sender name is required.',
            'sender_phone.required' => 'Sender phone is required.',
            'sender_pick_up_drop_off_point_id.required' => 'Please select a pickup station.',
            'receiver_name.required' => 'Receiver name is required.',
            'receiver_phone.required' => 'Receiver phone is required.',
            'delivery_pick_up_drop_off_point_id.required' => 'Please select a delivery station.',
        ];
    }

    // ---------- Lifecycle ----------
    public function mount(): void
    {
        $loggedInUser = Auth::guard('partner')->user();
        $partner = match ($loggedInUser->user_type) {
            'pickup-dropoff' => $loggedInUser->partner,
            'pha'            => $loggedInUser->parcelHandlingAssistant->partner,
            default          => null,
        };

        if (!$partner) {
            abort(403, 'You are not associated with a partner.');
        }

        $this->partner_id = $partner->id;
        $this->parcel_number = Parcel::generateParcelNumber();

        $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
            ->where('status', true)->with('town')->orderBy('name')->get();

        $this->senderTowns = $points->pluck('town')->filter()->unique('id')->values();

        $this->counties = County::whereHas('towns.pickUpAndDropOffPoint', function ($query) {
            $query->where('status', true);
        })->orderBy('name')->get();

        $this->items = Item::where('status', true)
            ->whereHas('paymentStructures')->orderBy('name')->get();

        $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)->orderBy('name')->get();

        if (empty($this->parcelItems)) {
            $this->addItemRow();
        }
    }

    // ---------- Step 1 hooks ----------
    public function updatedReceiverCountyId($value): void
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


        // $this->countyTowns = collect();
        // $this->receiver_town_id = null;
        // $this->receiverPickUpAndDropOffPoints = collect();
        // $this->delivery_pick_up_drop_off_point_id = null;

        // if ($value) {
        //     $this->countyTowns = Town::where('county_id', (int) $value)
        //         ->whereHas('pickUpAndDropOffPoint', fn ($q) => $q->where('status', true))
        //         ->orderBy('name')->get();
        // }
    }

    // ---------- Step 2: items ----------
    public function addItemRow(): void
    {
        $this->parcelItems[] = [
            'parcel_category_id'  => '',
            'package_type'        => 'regular',
            'declared_value'      => 0,
            'insurance_required'  => false,
            'content_description' => '',
            'special_notes'       => '',
            'payment_on_delivery' => false,
        ];
    }

    public function removeItemRow(int $index): void
    {
        if (!isset($this->parcelItems[$index])) return;
        if (count($this->parcelItems) <= 1) {
            $this->addError('parcelItems', 'At least one item is required.');
            return;
        }
        unset($this->parcelItems[$index]);
        $this->parcelItems = array_values($this->parcelItems);
    }

    // ---------- Step 3: partner ----------
    public function updatedTransportPartnerId($value): void
    {
        $this->recomputeSelectedBreakdown();
    }

    protected function itemsPayload(): array
    {
        return array_values(array_map(fn($row) => [
            'item_id'            => (int) ($row['parcel_category_id'] ?? 0),
            'declared_value'     => (float) ($row['declared_value'] ?? 0),
            'insurance_required' => (bool) ($row['insurance_required'] ?? false),
        ], array_filter($this->parcelItems, fn($r) => !empty($r['parcel_category_id']))));
    }

    protected function loadCouriers(): void
    {
        $this->couriers = [];
        $this->transport_partner_id = null;
        $this->selectedBreakdown = null;

        $fromTown = (int) $this->sender_town_id;
        $toTown   = (int) $this->receiver_town_id;
        $payload  = $this->itemsPayload();

        if (!$fromTown || !$toTown || empty($payload)) return;

        $this->couriers = $this->pricing
            ->partnersWithBreakdown($fromTown, $toTown, $payload)->toArray();
    }

    protected function recomputeSelectedBreakdown(): void
    {
        $this->selectedBreakdown = null;
        if (!$this->transport_partner_id) return;

        $fromTown = (int) $this->sender_town_id;
        $toTown   = (int) $this->receiver_town_id;
        if (!$fromTown || !$toTown) return;

        $payload = $this->itemsPayload();
        if (empty($payload)) return;

        $partner = Partner::find($this->transport_partner_id);
        if (!$partner) return;

        $fromZone = $this->pricing->zoneIdForTown($fromTown);
        $toZone   = $this->pricing->zoneIdForTown($toTown);
        $itemIds  = array_map(fn($i) => (int) $i['item_id'], $payload);

        $partner->load(['partnerRates' => function ($q) use ($fromZone, $toZone, $itemIds) {
            $q->where('source_zone_id', $fromZone)
                ->where('destination_zone_id', $toZone)
                ->whereIn('item_id', $itemIds);
        }]);

        $this->selectedBreakdown = $this->pricing->calculateBooking($partner, $fromTown, $toTown, $payload);
    }

    // ---------- Step 4 hooks ----------
    public function updatedSenderTownId($value): void
    {
        $this->senderPickUpAndDropOffPoints = collect();
        $this->sender_pick_up_drop_off_point_id = null;

        if ($value && $this->partner_id) {
            $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('partner_id', $this->partner_id)
                ->where('town_id', (int) $value)
                ->where('status', true)->orderBy('name')->get();
        }
    }

    public function updatedReceiverTownId($value): void
    {
        $this->receiverPickUpAndDropOffPoints = collect();
        $this->delivery_pick_up_drop_off_point_id = null;

        if ($value) {
            $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', (int) $value)
                ->where('status', true)->orderBy('name')->get();
        }
    }

    // ---------- Navigation ----------
    public function nextStep(): void
    {
        switch ($this->currentStep) {
            case 1:
                $this->validateStep1();
                $this->currentStep = 2;
                return;
            case 2:
                $this->validateStep2();
                $this->loadCouriers();
                $this->currentStep = 3;
                return;
            case 3:
                $this->validateStep3();
                $this->currentStep = 4;
                return;
            case 4:
                $this->validateStep4();
                $this->currentStep = 5;
                return;
        }
    }

    public function previousStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            if ($this->currentStep < 3) {
                $this->couriers = [];
                $this->transport_partner_id = null;
                $this->selectedBreakdown = null;
            }
        }
    }

    protected function validateStep1(): void
    {
        $this->validate([
            'sender_town_id' => 'required|exists:towns,id',
            'receiver_county_id' => 'required|exists:counties,id',
            'receiver_town_id' => 'required|exists:towns,id',
        ], $this->messages());

        if ((int) $this->sender_town_id === (int) $this->receiver_town_id) {
            $this->addError('receiver_town_id', 'Sender and receiver towns must be different.');
            throw new \Illuminate\Validation\ValidationException(validator([], []));
        }
    }

    protected function validateStep2(): void
    {
        $this->validate([
            'parcelItems' => 'required|array|min:1',
            'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
            'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'parcelItems.*.content_description' => 'required|string|max:1000',
            'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',
        ], $this->messages());

        foreach ($this->parcelItems as $i => $row) {
            if (!empty($row['payment_on_delivery'])) {
                if ((float) ($row['declared_value'] ?? 0) <= 0) {
                    $this->addError(
                        "parcelItems.$i.declared_value",
                        'Item #' . ($i + 1) . ': Declared value is required when payment on delivery is enabled.'
                    );
                }
                if (trim((string) ($row['special_notes'] ?? '')) === '') {
                    $this->addError(
                        "parcelItems.$i.special_notes",
                        'Item #' . ($i + 1) . ': Special notes (payment details) are required when payment on delivery is enabled.'
                    );
                }
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            throw new \Illuminate\Validation\ValidationException(validator([], []));
        }
    }

    protected function validateStep3(): void
    {
        $this->validate([
            'transport_partner_id' => 'required|exists:partners,id',
        ], $this->messages());

        if (empty($this->selectedBreakdown) || ($this->selectedBreakdown['totals']['total'] ?? 0) <= 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'transport_partner_id' => 'Could not price the selected transport partner.',
            ]);
        }
    }

    protected function validateStep4(): void
    {
        $this->validate([
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:20',
            'sender_email' => 'nullable|email|max:255',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'nullable|email|max:255',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
        ], $this->messages());
    }

    // ---------- Save ----------
    public function saveParcel(): void
    {
        $this->validate();

        $fromTown = (int) $this->sender_town_id;
        $toTown   = (int) $this->receiver_town_id;
        $payload  = $this->itemsPayload();

        if (empty($payload)) {
            $this->addError('parcelItems', 'Add at least one item.');
            return;
        }

        $partner = Partner::findOrFail($this->transport_partner_id);
        $fromZone = $this->pricing->zoneIdForTown($fromTown);
        $toZone   = $this->pricing->zoneIdForTown($toTown);
        $itemIds  = array_map(fn($i) => (int) $i['item_id'], $payload);

        $partner->load(['partnerRates' => function ($q) use ($fromZone, $toZone, $itemIds) {
            $q->where('source_zone_id', $fromZone)
                ->where('destination_zone_id', $toZone)
                ->whereIn('item_id', $itemIds);
        }]);

        $booking = $this->pricing->calculateBooking($partner, $fromTown, $toTown, $payload);

        if (($booking['totals']['total'] ?? 0) <= 0) {
            $this->addError('transport_partner_id', 'Could not price the selected transport partner.');
            return;
        }

        DB::beginTransaction();
        try {
            $partnerUser = Auth::guard('partner')->user();

            $senderTown   = Town::findOrFail($fromTown);
            $receiverTown = Town::findOrFail($toTown);

            $senderPoint    = PickUpAndDropOffPoint::with('partner')->findOrFail($this->sender_pick_up_drop_off_point_id);
            $receivingPoint = PickUpAndDropOffPoint::with('partner')->findOrFail($this->delivery_pick_up_drop_off_point_id);

            $senderContact = Contact::create([
                'name' => $this->sender_name,
                'phone' => $this->sender_phone,
                'email' => $this->sender_email,
                'address' => $this->sender_address,
                'county_id' => $senderTown->subCounty->county->id,
                'sub_county_id' => $senderTown->subCounty->id,
                'town_id' => $senderTown->id,
            ]);

            $receiverContact = Contact::create([
                'name' => $this->receiver_name,
                'phone' => $this->receiver_phone,
                'email' => $this->receiver_email,
                'address' => $this->receiver_address,
                'county_id' => $receiverTown->subCounty->county->id,
                'sub_county_id' => $receiverTown->subCounty->id,
                'town_id' => $receiverTown->id,
            ]);

            $partnerIdForPayout = $partnerUser->parcelHandlingAssistant?->partner?->id
                ?? $partnerUser->partner?->id;

            $createdParcels = [];

            foreach ($this->parcelItems as $index => $row) {
                $row = (array) $row;
                $catalogueItem = Item::findOrFail($row['parcel_category_id']);
                $priced = $booking['items'][$index] ?? null;

                if (!$priced) {
                    throw new \RuntimeException("Missing price for item #" . ($index + 1));
                }

                $resolvedWeight = $this->resolveWeight($catalogueItem);

                $parcel = Parcel::create([
                    'parcel_id'     => Parcel::generateParcelNumber(),
                    'customer_id'   => null,
                    'sender_id'     => $senderContact->id,
                    'receiver_id'   => $receiverContact->id,
                    'booking_type'  => 'instant',
                    'booking_source' => 'partner',
                    'transport_partner_id' => $this->transport_partner_id,

                    'sender_name'  => $this->sender_name,
                    'sender_phone' => $this->sender_phone,
                    'sender_email' => $this->sender_email,
                    'sender_address' => $this->sender_address,
                    'sender_county_id' => $senderTown->subCounty->county->id,
                    'sender_subcounty_id' => $senderTown->subCounty->id,
                    'sender_town_id' => $senderTown->id,
                    'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
                    'sender_partner_id' => $senderPoint->partner?->id ?? $partnerIdForPayout,

                    'receiver_name'  => $this->receiver_name,
                    'receiver_phone' => $this->receiver_phone,
                    'receiver_email' => $this->receiver_email,
                    'receiver_address' => $this->receiver_address,
                    'receiver_county_id' => $receiverTown->subCounty->county->id,
                    'receiver_subcounty_id' => $receiverTown->subCounty->id,
                    'receiver_town_id' => $receiverTown->id,
                    'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
                    'delivery_partner_id' => $receivingPoint->partner?->id,

                    'item_id' => $catalogueItem->id,
                    'item_name' => $catalogueItem->name,
                    'parcel_category_id' => $row['parcel_category_id'],
                    'parcel_type' => 'package',
                    'package_type' => $row['package_type'],
                    'declared_value' => $priced['declared_value'],
                    'insurance_amount' => $priced['insurance'],
                    'insurance_required' => !empty($row['insurance_required']),
                    'content_description' => $row['content_description'],
                    'special_notes' => $row['special_notes'] ?? null,
                    'special_instructions' => $row['special_notes'] ?? null,
                    'payment_on_delivery' => !empty($row['payment_on_delivery']),

                    'weight' => $resolvedWeight,
                    'weight_unit' => 'kg',
                    'dimension_unit' => 'cm',

                    'base_price' => $priced['courier_price'],
                    'insurance_charge' => $priced['insurance'],
                    'tax_amount' => $priced['tax_amount'],
                    'total_amount' => $priced['total'],

                    'payment_method' => $this->payment_method,
                    'payment_status' => $this->payment_status,
                    'current_status' => Parcel::STATUS_BOOKED,

                    'created_by' => $partnerUser->id,
                    'creator_id' => $partnerUser->id,
                    'creator_type' => current_user_type(),
                ]);

                $payout = $parcel->calculateParcelPayout(
                    (float) ($parcel->base_price + $parcel->tax_amount),
                    'direct'
                );

                ParcelPayout::create([
                    'parcel_id' => $parcel->id,
                    'partner_id' => $partnerIdForPayout,
                    'type' => 'pickup-dropoff',
                    'destination' => null,
                    'destination_id' => null,
                    'origin_id' => $this->sender_pick_up_drop_off_point_id,
                    'amount' => $payout['pick_up_drop_off_partner']['amount'],
                    'status' => 'pending',
                    'paid_out_on' => null,
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
                $createdParcels[] = $parcel;
            }

            DB::commit();

            session()->flash('success', count($createdParcels) . ' parcels created successfully.');
            $this->redirectRoute('partners.parcels.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Partner parcel creation failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->addError('general', 'Failed to create parcel: ' . $e->getMessage());
        }
    }

    protected function resolveWeight(Item $item): float
    {
        foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
            if (isset($item->{$col}) && is_numeric($item->{$col}) && $item->{$col} > 0) {
                return (float) $item->{$col};
            }
        }
        return 1.0;
    }

    public function render()
    {
        return view('livewire.partners.parcels.create-parcel', [
            'catalogueItems' => $this->items,
            'packageTypes' => [
                'regular' => 'Regular',
                'fragile' => 'Fragile',
                'perishable' => 'Perishable',
                'valuable' => 'Valuable',
                'hazardous' => 'Hazardous',
                'oversized' => 'Oversized',
            ],
            'paymentMethods' => ['mpesa' => 'M-Pesa'],
            'paymentStatuses' => ['pending' => 'Pending'],
        ]);
    }
}
// namespace App\Livewire\Partners\Parcels;

// use App\Models\Contact;
// use App\Models\County;
// use App\Models\Item;
// use App\Models\Parcel;
// use App\Models\ParcelPayout;
// use App\Models\Partner;
// use App\Models\PickUpAndDropOffPoint;
// use App\Models\Town;
// use App\Services\PricingCalculator;
// use App\Services\SMSService;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
// use Livewire\Component;

// class CreateParcel extends Component
// {
//     // ---------- Wizard state ----------
//     public int $currentStep = 1;
//     public int $totalSteps  = 4;      // Route → Items → Courier → Review

//     // ---------- Partner context ----------
//     public $partner_id;
//     public $parcel_number;

//     // ---------- Step 1: Route & Contacts ----------
//     public $sender_name = '';
//     public $sender_phone = '';
//     public $sender_email = '';
//     public $sender_address = '';

//     public $sender_town_id = '';
//     public $sender_pick_up_drop_off_point_id;

//     public $receiver_name = '';
//     public $receiver_phone = '';
//     public $receiver_email = '';
//     public $receiver_address = '';

//     public $receiver_county_id = '';
//     public $receiver_town_id = '';
//     public $delivery_pick_up_drop_off_point_id;

//     // ---------- Step 2: Items ----------
//     /** @var array<int, array<string, mixed>> */
//     public array $parcelItems = [];

//     // ---------- Step 3: Courier ----------
//     public $transport_partner_id = null;
//     /** @var array<int, array<string, mixed>> */
//     public array $couriers = [];
//     /** @var array<string, mixed>|null */
//     public ?array $selectedBreakdown = null;

//     // ---------- Step 4: Payment ----------
//     public $payment_method = 'mpesa';
//     public $payment_status = 'pending';

//     // ---------- Lookups ----------
//     public $senderTowns = [];
//     public $counties = [];
//     public $countyTowns = [];
//     public $senderPickUpAndDropOffPoints = [];
//     public $receiverPickUpAndDropOffPoints = [];
//     public $items = [];               // Item catalogue (status true + payment structure)
//     public $pickUpAndDropOffPoints = [];

//     /** @var PricingCalculator */
//     protected $pricing;

//     public function boot(PricingCalculator $pricing): void
//     {
//         $this->pricing = $pricing;
//     }

//     // ---------- Validation ----------
//     protected function rules(): array
//     {
//         return [
//             'sender_name'   => 'required|string|max:255',
//             'sender_phone'  => 'required|string|max:20',
//             'sender_email'  => 'nullable|email|max:255',
//             'sender_town_id' => 'required|exists:towns,id',
//             'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

//             'receiver_name'  => 'required|string|max:255',
//             'receiver_phone' => 'required|string|max:20',
//             'receiver_email' => 'nullable|email|max:255',
//             'receiver_county_id' => 'required|exists:counties,id',
//             'receiver_town_id'   => 'required|exists:towns,id',
//             'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

//             'parcelItems' => 'required|array|min:1',
//             'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
//             'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//             'parcelItems.*.content_description' => 'required|string|max:1000',
//             'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',

//             'transport_partner_id' => 'required|exists:partners,id',
//             'payment_method' => 'required|in:mpesa',
//             'payment_status' => 'required|in:pending',
//         ];
//     }

//     protected function messages(): array
//     {
//         return [
//             'sender_name.required'   => 'Sender name is required.',
//             'sender_phone.required'  => 'Sender phone is required.',
//             'sender_town_id.required' => 'Please select the sender town.',
//             'sender_pick_up_drop_off_point_id.required' => 'Please select a pickup point.',
//             'receiver_name.required'  => 'Receiver name is required.',
//             'receiver_phone.required' => 'Receiver phone is required.',
//             'receiver_county_id.required' => 'Please select the receiver county.',
//             'receiver_town_id.required'   => 'Please select the receiver town.',
//             'delivery_pick_up_drop_off_point_id.required' => 'Please select a delivery point.',
//             'parcelItems.required' => 'Add at least one item.',
//             'parcelItems.*.parcel_category_id.required' => 'Select a category for each item.',
//             'parcelItems.*.package_type.required' => 'Select a package type for each item.',
//             'parcelItems.*.content_description.required' => 'Describe the contents of each item.',
//             'transport_partner_id.required' => 'Choose a courier before continuing.',
//         ];
//     }

//     // ---------- Lifecycle ----------
//     public function mount(): void
//     {
//         $loggedInUser = Auth::guard('partner')->user();

//         $partner = match ($loggedInUser->user_type) {
//             'pickup-dropoff' => $loggedInUser->partner,
//             'pha'            => $loggedInUser->parcelHandlingAssistant->partner,
//             default          => null,
//         };

//         if (!$partner) {
//             abort(403, 'You are not associated with a partner.');
//         }

//         $this->partner_id   = $partner->id;
//         $this->parcel_number = Parcel::generateParcelNumber();

//         $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
//             ->where('status', true)
//             ->with('town')
//             ->orderBy('name')
//             ->get();

//         $this->senderTowns = $points->pluck('town')->filter()->unique('id')->values();

//         $this->counties = County::whereHas('towns.pickUpAndDropOffPoint', function ($query) {
//             $query->where('status', true);
//         })->orderBy('name')->get();

//         // Items: status true AND has a PaymentStructure
//         $this->items = Item::where('status', true)
//             ->whereHas('paymentStructures')
//             ->orderBy('name')
//             ->get();

//         $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
//             ->orderBy('name')
//             ->get();

//         if (empty($this->parcelItems)) {
//             $this->addItemRow();
//         }
//     }

//     // ---------- Step 1 hooks ----------
//     public function updatedSenderTownId($value): void
//     {
//         $this->senderPickUpAndDropOffPoints = collect();
//         $this->sender_pick_up_drop_off_point_id = null;

//         if ($value && $this->partner_id) {
//             $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('partner_id', $this->partner_id)
//                 ->where('town_id', (int) $value)
//                 ->where('status', true)
//                 ->orderBy('name')
//                 ->get();
//         }
//     }

//     public function updatedReceiverCountyId($value): void
//     {
//         try {
//             if ($value) {
//                 $county = County::where('id', (int)$value)->first();
//                 $this->countyTowns = $county->towns()
//                     ->whereHas('pickUpAndDropOffPoint', function ($query) {
//                         $query->where('status', true);
//                     })
//                     ->get();
//             } else {
//                 $this->countyTowns = Town::where('status', true)->orderBy('name')->get();
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating receiver county: ' . $e->getMessage());
//         }


//         // $this->countyTowns = collect();
//         // $this->receiver_town_id = null;
//         // $this->receiverPickUpAndDropOffPoints = collect();
//         // $this->delivery_pick_up_drop_off_point_id = null;

//         // if ($value) {
//         //     $this->countyTowns = Town::where('county_id', (int) $value)
//         //         ->whereHas('pickUpAndDropOffPoint', fn ($q) => $q->where('status', true))
//         //         ->orderBy('name')
//         //         ->get();
//         // }



//     }

//     public function updatedReceiverTownId($value): void
//     {
//         $this->receiverPickUpAndDropOffPoints = collect();
//         $this->delivery_pick_up_drop_off_point_id = null;

//         if ($value) {
//             $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', (int) $value)
//                 ->where('status', true)
//                 ->orderBy('name')
//                 ->get();
//         }
//     }

//     // ---------- Step 2: items ----------
//     public function addItemRow(): void
//     {
//         $this->parcelItems[] = [
//             'parcel_category_id'  => '',
//             'package_type'        => 'regular',
//             'declared_value'      => 0,
//             'insurance_required'  => false,
//             'content_description' => '',
//             'special_notes'       => '',
//             'payment_on_delivery' => false,
//         ];
//     }

//     public function removeItemRow(int $index): void
//     {
//         if (!isset($this->parcelItems[$index])) {
//             return;
//         }
//         if (count($this->parcelItems) <= 1) {
//             $this->addError('parcelItems', 'At least one item is required.');
//             return;
//         }
//         unset($this->parcelItems[$index]);
//         $this->parcelItems = array_values($this->parcelItems);
//     }

//     // ---------- Step 3: couriers ----------
//     public function updatedTransportPartnerId($value): void
//     {
//         $this->recomputeSelectedBreakdown();
//     }

//     protected function recomputeSelectedBreakdown(): void
//     {
//         $this->selectedBreakdown = null;
//         if (!$this->transport_partner_id) return;

//         $fromTown = (int) $this->sender_town_id;
//         $toTown   = (int) $this->receiver_town_id;
//         if (!$fromTown || !$toTown) return;

//         $payload = $this->itemsPayload();
//         if (empty($payload)) return;

//         $partner = Partner::find($this->transport_partner_id);
//         if (!$partner) return;

//         // Ensure partnerRates are loaded for the route + items
//         $fromZone = $this->pricing->zoneIdForTown($fromTown);
//         $toZone   = $this->pricing->zoneIdForTown($toTown);
//         $itemIds  = array_map(fn($i) => (int) $i['item_id'], $payload);

//         $partner->load(['partnerRates' => function ($q) use ($fromZone, $toZone, $itemIds) {
//             $q->where('source_zone_id', $fromZone)
//                 ->where('destination_zone_id', $toZone)
//                 ->whereIn('item_id', $itemIds);
//         }]);

//         $this->selectedBreakdown = $this->pricing->calculateBooking($partner, $fromTown, $toTown, $payload);
//     }

//     protected function itemsPayload(): array
//     {
//         return array_values(array_map(fn($row) => [
//             'item_id'            => (int) ($row['parcel_category_id'] ?? 0),
//             'declared_value'     => (float) ($row['declared_value'] ?? 0),
//             'insurance_required' => (bool) ($row['insurance_required'] ?? false),
//         ], array_filter($this->parcelItems, fn($r) => !empty($r['parcel_category_id']))));
//     }

//     protected function loadCouriers(): void
//     {
//         $this->couriers = [];
//         $this->transport_partner_id = null;
//         $this->selectedBreakdown = null;

//         $fromTown = (int) $this->sender_town_id;
//         $toTown   = (int) $this->receiver_town_id;
//         $payload  = $this->itemsPayload();

//         if (!$fromTown || !$toTown || empty($payload)) return;

//         $this->couriers = $this->pricing
//             ->partnersWithBreakdown($fromTown, $toTown, $payload)
//             ->toArray();
//     }

//     // ---------- Navigation ----------
//     public function nextStep(): void
//     {
//         if ($this->currentStep === 1) {
//             $this->validateStep1();
//             $this->currentStep = 2;
//             return;
//         }

//         if ($this->currentStep === 2) {
//             $this->validateStep2();
//             $this->loadCouriers();
//             $this->currentStep = 3;
//             return;
//         }

//         if ($this->currentStep === 3) {
//             $this->validateStep3();
//             $this->currentStep = 4;
//             return;
//         }
//     }

//     public function previousStep(): void
//     {
//         if ($this->currentStep > 1) {
//             $this->currentStep--;
//             if ($this->currentStep < 3) {
//                 $this->couriers = [];
//                 $this->transport_partner_id = null;
//                 $this->selectedBreakdown = null;
//             }
//         }
//     }

//     protected function validateStep1(): void
//     {
//         $this->validate([
//             'sender_name'   => 'required|string|max:255',
//             'sender_phone'  => 'required|string|max:20',
//             'sender_email'  => 'nullable|email|max:255',
//             'sender_town_id' => 'required|exists:towns,id',
//             'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//             'receiver_name'  => 'required|string|max:255',
//             'receiver_phone' => 'required|string|max:20',
//             'receiver_email' => 'nullable|email|max:255',
//             'receiver_county_id' => 'required|exists:counties,id',
//             'receiver_town_id'   => 'required|exists:towns,id',
//             'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//         ], $this->messages());
//     }

//     protected function validateStep2(): void
//     {
//         $this->validate([
//             'parcelItems' => 'required|array|min:1',
//             'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
//             'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//             'parcelItems.*.content_description' => 'required|string|max:1000',
//             'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',
//         ], $this->messages());

//         foreach ($this->parcelItems as $i => $row) {
//             if (!empty($row['payment_on_delivery'])) {
//                 if ((float) ($row['declared_value'] ?? 0) <= 0) {
//                     $this->addError(
//                         "parcelItems.$i.declared_value",
//                         'Item #' . ($i + 1) . ': Declared value is required when payment on delivery is enabled.'
//                     );
//                 }
//                 if (trim((string) ($row['special_notes'] ?? '')) === '') {
//                     $this->addError(
//                         "parcelItems.$i.special_notes",
//                         'Item #' . ($i + 1) . ': Special notes (payment details) are required when payment on delivery is enabled.'
//                     );
//                 }
//             }
//         }

//         if ($this->getErrorBag()->isNotEmpty()) {
//             throw new \Illuminate\Validation\ValidationException(
//                 validator([], [])
//             );
//         }
//     }

//     protected function validateStep3(): void
//     {
//         $this->validate([
//             'transport_partner_id' => 'required|exists:partners,id',
//         ], $this->messages());

//         if (empty($this->selectedBreakdown) || ($this->selectedBreakdown['totals']['total'] ?? 0) <= 0) {
//             throw \Illuminate\Validation\ValidationException::withMessages([
//                 'transport_partner_id' => 'Could not price the selected courier for this route.',
//             ]);
//         }
//     }

//     // ---------- Save ----------
//     public function saveParcel(): void
//     {
//         $this->validate();

//         $fromTown = (int) $this->sender_town_id;
//         $toTown   = (int) $this->receiver_town_id;
//         $payload  = $this->itemsPayload();

//         if (empty($payload)) {
//             $this->addError('parcelItems', 'Add at least one item.');
//             return;
//         }

//         // Authoritative re-pricing at save time
//         $partner = Partner::findOrFail($this->transport_partner_id);
//         $fromZone = $this->pricing->zoneIdForTown($fromTown);
//         $toZone   = $this->pricing->zoneIdForTown($toTown);
//         $itemIds  = array_map(fn($i) => (int) $i['item_id'], $payload);

//         $partner->load(['partnerRates' => function ($q) use ($fromZone, $toZone, $itemIds) {
//             $q->where('source_zone_id', $fromZone)
//                 ->where('destination_zone_id', $toZone)
//                 ->whereIn('item_id', $itemIds);
//         }]);

//         $booking = $this->pricing->calculateBooking($partner, $fromTown, $toTown, $payload);

//         if (($booking['totals']['total'] ?? 0) <= 0) {
//             $this->addError('transport_partner_id', 'Could not price the selected courier.');
//             return;
//         }

//         DB::beginTransaction();
//         try {
//             $partnerUser = Auth::guard('partner')->user();

//             $senderTown   = Town::findOrFail($fromTown);
//             $receiverTown = Town::findOrFail($toTown);

//             $senderPoint    = PickUpAndDropOffPoint::with('partner')->findOrFail($this->sender_pick_up_drop_off_point_id);
//             $receivingPoint = PickUpAndDropOffPoint::with('partner')->findOrFail($this->delivery_pick_up_drop_off_point_id);

//             // Contacts
//             $senderContact = Contact::create([
//                 'name'          => $this->sender_name,
//                 'phone'         => $this->sender_phone,
//                 'email'         => $this->sender_email,
//                 'address'       => $this->sender_address,
//                 'county_id'     => $senderTown->subCounty->county->id,
//                 'sub_county_id' => $senderTown->subCounty->id,
//                 'town_id'       => $senderTown->id,
//             ]);

//             $receiverContact = Contact::create([
//                 'name'          => $this->receiver_name,
//                 'phone'         => $this->receiver_phone,
//                 'email'         => $this->receiver_email,
//                 'address'       => $this->receiver_address,
//                 'county_id'     => $receiverTown->subCounty->county->id,
//                 'sub_county_id' => $receiverTown->subCounty->id,
//                 'town_id'       => $receiverTown->id,
//             ]);

//             $partnerIdForPayout = $partnerUser->parcelHandlingAssistant?->partner?->id
//                 ?? $partnerUser->partner?->id;

//             $createdParcels = [];

//             foreach ($this->parcelItems as $index => $row) {
//                 $row = (array) $row;
//                 $catalogueItem = Item::findOrFail($row['parcel_category_id']);
//                 $priced = $booking['items'][$index] ?? null;

//                 if (!$priced) {
//                     throw new \RuntimeException("Missing price for item #" . ($index + 1));
//                 }

//                 $resolvedWeight = $this->resolveWeight($catalogueItem);

//                 $parcel = Parcel::create([
//                     'parcel_id'     => Parcel::generateParcelNumber(),
//                     'customer_id'   => null,        // partner-side, no customer
//                     'sender_id'     => $senderContact->id,
//                     'receiver_id'   => $receiverContact->id,
//                     'booking_type'  => 'instant',
//                     'booking_source' => 'partner',

//                     'transport_partner_id' => $this->transport_partner_id,

//                     // Sender
//                     'sender_name'        => $this->sender_name,
//                     'sender_phone'       => $this->sender_phone,
//                     'sender_email'       => $this->sender_email,
//                     'sender_address'     => $this->sender_address,
//                     'sender_county_id'   => $senderTown->subCounty->county->id,
//                     'sender_subcounty_id' => $senderTown->subCounty->id,
//                     'sender_town_id'     => $senderTown->id,
//                     'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
//                     'sender_partner_id'  => $senderPoint->partner?->id ?? $partnerIdForPayout,

//                     // Receiver
//                     'receiver_name'        => $this->receiver_name,
//                     'receiver_phone'       => $this->receiver_phone,
//                     'receiver_email'       => $this->receiver_email,
//                     'receiver_address'     => $this->receiver_address,
//                     'receiver_county_id'   => $receiverTown->subCounty->county->id,
//                     'receiver_subcounty_id' => $receiverTown->subCounty->id,
//                     'receiver_town_id'     => $receiverTown->id,
//                     'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
//                     'delivery_partner_id'  => $receivingPoint->partner?->id,

//                     // Item
//                     'item_id'              => $catalogueItem->id,
//                     'item_name'            => $catalogueItem->name,
//                     'parcel_category_id'   => $row['parcel_category_id'],
//                     'parcel_type'          => 'package',           // hard-coded per spec
//                     'package_type'         => $row['package_type'],
//                     'declared_value'       => $priced['declared_value'],
//                     'insurance_amount'     => $priced['insurance'],
//                     'insurance_required'   => !empty($row['insurance_required']),
//                     'content_description'  => $row['content_description'],
//                     'special_notes'        => $row['special_notes'] ?? null,
//                     'special_instructions' => $row['special_notes'] ?? null,
//                     'payment_on_delivery'  => !empty($row['payment_on_delivery']),

//                     // Dimensions / weight
//                     'weight'         => $resolvedWeight,
//                     'weight_unit'    => 'kg',
//                     'dimension_unit' => 'cm',

//                     // Pricing (authoritative)
//                     'base_price'       => $priced['courier_price'],
//                     'insurance_charge' => $priced['insurance'],
//                     'tax_amount'       => $priced['tax_amount'],
//                     'total_amount'     => $priced['total'],

//                     'payment_method' => $this->payment_method,
//                     'payment_status' => $this->payment_status,
//                     'current_status' => Parcel::STATUS_BOOKED,

//                     'created_by'   => $partnerUser->id,
//                     'creator_id'   => $partnerUser->id,
//                     'creator_type' => current_user_type(),
//                 ]);

//                 // Payout
//                 $payout = $parcel->calculateParcelPayout(
//                     (float) ($parcel->base_price + $parcel->tax_amount),
//                     'direct'
//                 );

//                 ParcelPayout::create([
//                     'parcel_id'         => $parcel->id,
//                     'partner_id'        => $partnerIdForPayout,
//                     'type'              => 'pickup-dropoff',
//                     'destination'       => null,
//                     'destination_id'    => null,
//                     'origin_id'         => $this->sender_pick_up_drop_off_point_id,
//                     'amount'            => $payout['pick_up_drop_off_partner']['amount'],
//                     'status'            => 'pending',
//                     'paid_out_on'       => null,
//                     'cancelation_reason' => null,
//                 ]);

//                 $parcel->updateParcelStatus(
//                     Parcel::STATUS_CREATED,
//                     $this->sender_pick_up_drop_off_point_id,
//                     $partnerUser->id,
//                     current_user_type(),
//                     'Parcel created',
//                     null,
//                     null,
//                 );

//                 $parcel->addTracking(Parcel::STATUS_BOOKED, $partnerUser->id);

//                 $createdParcels[] = $parcel;
//             }

//             DB::commit();

//             // if (count($createdParcels) === 1) {
//             //     $this->redirectRoute('partners.parcels.view', ['parcel' => $createdParcels[0]->id]);
//             //     return;
//             // }

//             session()->flash('success', count($createdParcels) . ' parcels created successfully.');
//             $this->redirectRoute('partners.parcels.index');
//         } catch (\Illuminate\Validation\ValidationException $e) {
//             DB::rollBack();
//             throw $e;
//         } catch (\Throwable $e) {
//             DB::rollBack();
//             Log::error('Partner parcel creation failed: ' . $e->getMessage(), [
//                 'trace' => $e->getTraceAsString(),
//             ]);
//             $this->addError('general', 'Failed to create parcel: ' . $e->getMessage());
//         }
//     }

//     protected function resolveWeight(Item $item): float
//     {
//         foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
//             if (isset($item->{$col}) && is_numeric($item->{$col}) && $item->{$col} > 0) {
//                 return (float) $item->{$col};
//             }
//         }
//         return 1.0;
//     }

//     public function render()
//     {
//         return view('livewire.partners.parcels.create-parcel', [
//             'catalogueItems' => $this->items,
//             'packageTypes' => [
//                 'regular'    => 'Regular',
//                 'fragile'    => 'Fragile',
//                 'perishable' => 'Perishable',
//                 'valuable'   => 'Valuable',
//                 'hazardous'  => 'Hazardous',
//                 'oversized'  => 'Oversized',
//             ],
//             'paymentMethods' => ['mpesa' => 'M-Pesa'],
//             'paymentStatuses' => ['pending' => 'Pending'],
//         ]);
//     }
// }

// namespace App\Livewire\Partners\Parcels;

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
//     // Parcel-level rollups
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
//     // Pricing (parcel-level rollup)
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

//     /** @var \Illuminate\Support\Collection|array Item catalogue (Item model records) */
//     public $items = [];

//     public $weightRanges = [];
//     public $partner_id;

//     public $senderPickUpAndDropOffPoints = [];

//     public array $parcelItems = [];

//     protected SMSService $smsService;

//     protected function rules()
//     {
//         $rules = [
//             // Step 1
//             'sender_name' => 'required|string|max:255',
//             'sender_phone' => 'required|string|max:20',
//             'sender_town_id' => 'required|exists:towns,id',
//             'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',
//             'receiver_name' => 'required|string|max:255',
//             'receiver_phone' => 'required|string|max:20',
//             'receiver_county_id' => 'required|exists:counties,id',
//             'receiver_town_id' => 'required|exists:towns,id',
//             'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

//             // Step 2 (items)
//             'parcelItems' => 'required|array|min:1',
//             'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
//             'parcelItems.*.parcel_type' => 'required|string',
//             'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//             'parcelItems.*.content_description' => 'required|string|max:1000',
//             'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',

//             // Step 3
//             'payment_method' => 'required|in:cash,mpesa,card,bank_transfer,wallet',
//             'payment_status' => 'required|in:pending,paid,partially_paid',
//         ];

//         if ($this->sender_email) {
//             $rules['sender_email'] = 'email|max:255';
//         }
//         if ($this->receiver_email) {
//             $rules['receiver_email'] = 'email|max:255';
//         }

//         return $rules;
//     }

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
//             'parcelItems.required' => 'At least one parcel item is required',
//             'parcelItems.*.parcel_category_id.required' => 'Please select an item for each row',
//             'parcelItems.*.parcel_category_id.exists' => 'The selected item is invalid',
//             'parcelItems.*.parcel_type.required' => 'Parcel type is required for each item',
//             'parcelItems.*.package_type.required' => 'Package type is required for each item',
//             'parcelItems.*.content_description.required' => 'Please describe each item\'s contents',
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

//         if (!$partner) {
//             abort(403, 'You are not associated with a partner.');
//         }

//             $this->partner_id = $partner->id;

//         $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
//             ->where('status', true)
//             ->with('town')
//             ->orderBy('name')
//             ->get();

//         $this->senderTowns = $points->pluck('town')->filter()->unique('id')->values();

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

//         if (empty($this->parcelItems)) {
//             $this->addItemRow();
//         }

//         $this->recalculateParcelTotals();
//     }

//     public function loadOptions()
//     {
//         try {
//             // $this->items = Item::where('status', true)->orderBy('name')->get();

//             $this->items = Item::where('status', true)
//                 ->whereHas('pricing.items', function ($query) {
//                     $query->whereNotNull('cost')
//                         ->where('cost', '>', 0);
//                 })
//                 ->get();

//             $this->weightRanges = WeightRange::all();
//             $this->customers = [];
//             $this->towns = Town::where('status', true)->orderBy('name')->get();
//             $this->pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)
//                 ->orderBy('name')
//                 ->get();

//             $this->pickupPartners = Partner::where('verification_status', true)->get() ?? [];
//             $this->deliveryPartners = Partner::where('verification_status', true)->get() ?? [];
//             $this->drivers =  [];
//             $this->transportPartners = Partner::where('verification_status', true)->get() ?? [];
//         } catch (\Exception $e) {
//             Log::error('Error loading options: ' . $e->getMessage());
//         }
//     }

//     /* ===================== Item Repeater Methods ===================== */

//     public function addItemRow(): void
//     {
//         $this->parcelItems[] = [
//             'parcel_category_id'  => null,
//             'parcel_type'         => '',
//             'package_type'        => 'regular',
//             'declared_value'      => 0,
//             'insurance_required'  => false,
//             'content_description' => '',
//             'special_notes'       => '',
//             'payment_on_delivery' => false,
//             'base_price'          => 0,
//             'item_insurance_amount' => 0,
//             'tax_amount'          => 0,
//             'item_total'          => 0,
//         ];
//     }

//     public function removeItemRow(int $index): void
//     {
//         if (!isset($this->parcelItems[$index])) {
//             return;
//         }
//         if (count($this->parcelItems) <= 1) {
//             $this->addError('parcelItems', 'At least one item is required.');
//             return;
//         }
//         unset($this->parcelItems[$index]);
//         $this->parcelItems = array_values($this->parcelItems);
//         $this->recalculateParcelTotals();
//     }

//     /**
//      * Recompute per-item pricing then roll up into parcel-level totals.
//      */
//     public function recalculateParcelTotals(): void
//     {
//         $this->recalculateItems();

//         $grandBase       = 0;
//         $grandInsurance  = 0;
//         $grandTax        = 0;
//         $grandTotal      = 0;
//         $grandDeclared   = 0;
//         $grandWeight     = 0;

//         foreach ($this->parcelItems as $row) {
//             $grandBase      += (float) ($row['base_price'] ?? 0);
//             $grandInsurance += (float) ($row['item_insurance_amount'] ?? 0);
//             $grandTax       += (float) ($row['tax_amount'] ?? 0);
//             $grandTotal     += (float) ($row['item_total'] ?? 0);
//             $grandDeclared  += (float) ($row['declared_value'] ?? 0);
//             $grandWeight    += $this->resolveItemWeight($row);
//         }

//         $this->base_price         = round($grandBase, 2);
//         $this->insurance_charge   = round($grandInsurance, 2);
//         $this->insurance_amount   = $this->insurance_charge;
//         $this->tax_amount         = round($grandTax, 2);
//         $this->total_amount       = round($grandTotal, 2);
//         $this->declared_value     = $grandDeclared;
//         $this->calculatedPrice    = $this->total_amount;
//         $this->weight             = $grandWeight;

//         if (count($this->parcelItems) === 1) {
//             $first = $this->parcelItems[0];
//             $this->parcel_type         = $first['parcel_type'] ?: $this->parcel_type;
//             $this->package_type        = $first['package_type'] ?: $this->package_type;
//             $this->content_description = $first['content_description'] ?: $this->content_description;
//             $this->special_instructions = $first['special_notes'] ?: $this->special_instructions;
//             $this->insurance_required  = (bool) $first['insurance_required'];
//         }
//     }

//     /**
//      * Resolve the weight for an item row. Pulls from Item catalogue
//      * (weight column if present); falls back to 1 kg.
//      */
//     protected function resolveItemWeight(array $row): float
//     {
//         $id = $row['parcel_category_id'] ?? null;
//         if (!$id) {
//             return 0.0;
//         }

//         $catalogue = collect($this->items)->firstWhere('id', (int) $id);
//         if (!$catalogue) {
//             return 0.0;
//         }

//         foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
//             if (isset($catalogue->{$col}) && is_numeric($catalogue->{$col}) && $catalogue->{$col} > 0) {
//                 return (float) $catalogue->{$col};
//             }
//         }
//         return 1.0;
//     }

//     /**
//      * Price each item individually using zone pricing on resolved item weight.
//      */
//     protected function recalculateItems(): void
//     {
//         $senderTown   = $this->sender_town_id ? Town::with('subCounty.county')->find($this->sender_town_id) : null;
//         $receiverTown = $this->receiver_town_id ? Town::with('subCounty.county')->find($this->receiver_town_id) : null;

//         $senderZone   = $senderTown ? ZoneTown::where('town_id', $senderTown->id)->first() : null;
//         $receiverZone = $receiverTown ? ZoneTown::where('town_id', $receiverTown->id)->first() : null;

//         foreach ($this->parcelItems as &$row) {

//             $declared = (float) ($row['declared_value'] ?? 0);

//             // Skip pricing if no item selected yet
//             if (empty($row['parcel_category_id'])) {
//                 $row['base_price']            = 0;
//                 $row['item_insurance_amount'] = 0;
//                 $row['tax_amount']            = 0;
//                 $row['item_total']            = 0;
//                 continue;
//             }

//             $item = Item::find($row['parcel_category_id']);

//             // Skip if item not found
//             if (!$item) {
//                 $row['base_price']            = 0;
//                 $row['item_insurance_amount'] = 0;
//                 $row['tax_amount']            = 0;
//                 $row['item_total']            = 0;
//                 continue;
//             }

//             $pricing = Pricing::where('item_id', $item->id)->first();

//             // Skip if no pricing record or no zones resolved
//             if (!$pricing || !$senderZone || !$receiverZone) {
//                 $row['base_price']            = 0;
//                 $row['item_insurance_amount'] = 0;
//                 $row['tax_amount']            = 0;
//                 $row['item_total']            = 0;
//                 continue;
//             }

//             $pricingItem = PricingItem::where('pricing_id', $pricing->id)
//                 ->where('source_zone_id', $senderZone->zone_id)
//                 ->where('destination_zone_id', $receiverZone->zone_id)
//                 ->first();

//             // Skip if no matching pricing item for the route
//             if (!$pricingItem) {
//                 $row['base_price']            = 0;
//                 $row['item_insurance_amount'] = 0;
//                 $row['tax_amount']            = 0;
//                 $row['item_total']            = 0;
//                 continue;
//             }

//             $basePrice = (float) ($pricingItem->cost ?? 0);
//             $tax_amount = round($basePrice * 0.16, 2);
//             $total_amount = ceil($basePrice + $tax_amount);
//             $calculatedPrice = $total_amount;

//             $basePrice = round($basePrice, 2);

//             $insurance = !empty($row['insurance_required'])
//                 ? round($declared * 0.02, 2)
//                 : 0;

//             $tax = round(($basePrice + $insurance) * 0.16, 2);

//             $row['base_price']            = $basePrice;
//             $row['item_insurance_amount'] = $insurance;
//             $row['tax_amount']            = $tax_amount;
//             $row['item_total']            = round($basePrice + $insurance + $tax, 2);
//         }
//         unset($row);
//     }

//     /* ============ Livewire "updated" hooks ============ */

//     public function updatedSenderTownId($value)
//     {
//         try {
//             $this->senderPickUpAndDropOffPoints = collect();

//             if ($value && $this->partner_id) {
//                 $this->senderPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('partner_id', $this->partner_id)
//                     ->where('town_id', (int) $value)
//                     ->where('status', true)
//                     ->orderBy('name')
//                     ->get();
//             }

//             $this->recalculateParcelTotals();
//         } catch (\Exception $e) {
//             Log::error('Error updating sender details: ' . $e->getMessage());
//         }
//     }

//     public function updatedReceiverTownId($value)
//     {
//         try {
//             if ($value) {
//                 $town = Town::where('id', (int)$value)->first();
//                 $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('town_id', $town->id)
//                     ->where('status', true)->get();
//             } else {
//                 $this->receiverPickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', true)->get();
//             }
//             $this->recalculateParcelTotals();
//         } catch (\Exception $e) {
//             Log::error('Error updating receiver town: ' . $e->getMessage());
//         }
//     }

//     public function updatedReceiverCountyId($value)
//     {
//         try {
//             if ($value) {
//                 $county = County::where('id', (int)$value)->first();
//                 $this->countyTowns = $county->towns()
//                     ->whereHas('pickUpAndDropOffPoint', function ($query) {
//                         $query->where('status', true);
//                     })
//                     ->get();
//             } else {
//                 $this->countyTowns = Town::where('status', true)->orderBy('name')->get();
//             }
//         } catch (\Exception $e) {
//             Log::error('Error updating receiver county: ' . $e->getMessage());
//         }
//     }

//     public function updatedParcelItems($value = null, $key = null): void
//     {
//         $this->recalculateParcelTotals();
//     }

//     /* ===================== Step Navigation ===================== */

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
//                     'receiver_county_id' => 'required|exists:counties,id',
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
//                     'parcelItems' => 'required|array|min:1',
//                     'parcelItems.*.parcel_category_id' => 'required|exists:items,id',
//                     'parcelItems.*.parcel_type' => 'required|string',
//                     'parcelItems.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
//                     'parcelItems.*.content_description' => 'required|string|max:1000',
//                     'parcelItems.*.declared_value' => 'nullable|numeric|min:0|max:1000000',
//                 ];
//                 break;
//         }

//         $this->validate($rules, $this->messages());

//         if ($step === 2) {
//             foreach ($this->parcelItems as $i => $row) {
//                 if (!empty($row['payment_on_delivery'])) {
//                     $dv = (float) ($row['declared_value'] ?? 0);
//                     if ($dv <= 0) {
//                         $this->addError("parcelItems.$i.declared_value", 'Declared value is required when payment on delivery is selected.');
//                         throw new \Illuminate\Validation\ValidationException(
//                             validator([], []),
//                             null,
//                             null,
//                             null
//                         );
//                     }
//                     if (empty(trim($row['special_notes'] ?? ''))) {
//                         $this->addError("parcelItems.$i.special_notes", 'Special notes with payment details are required when payment on delivery is selected.');
//                         throw new \Illuminate\Validation\ValidationException(
//                             validator([], []),
//                             null,
//                             null,
//                             null
//                         );
//                     }
//                 }
//             }
//         }
//     }

//     /* ===================== Save ===================== */
//     public function saveParcel(SMSService $smsService)
//     {
//         try {
//             // Full validation (all steps)
//             $this->validate($this->rules(), $this->messages());
//             $this->recalculateParcelTotals();

//             // Extra POD validation pass
//             foreach ($this->parcelItems as $i => $row) {
//                 if (!empty($row['payment_on_delivery'])) {
//                     $dv = (float) ($row['declared_value'] ?? 0);
//                     if ($dv <= 0) {
//                         $this->addError(
//                             "parcelItems.$i.declared_value",
//                             'Item #' . ($i + 1) . ': Declared value is required (greater than 0) when payment on delivery is selected.'
//                         );
//                         throw new \Illuminate\Validation\ValidationException(validator([], []));
//                     }
//                     if (empty(trim((string) ($row['special_notes'] ?? '')))) {
//                         $this->addError(
//                             "parcelItems.$i.special_notes",
//                             'Item #' . ($i + 1) . ': Special notes with payment details are required when payment on delivery is selected.'
//                         );
//                         throw new \Illuminate\Validation\ValidationException(validator([], []));
//                     }
//                 }
//             }

//             DB::beginTransaction();

//             $senderTown   = Town::findOrFail($this->sender_town_id);
//             $receiverTown = Town::findOrFail($this->receiver_town_id);

//             $this->sender_county_id    = $senderTown->subCounty->county->id;
//             $this->sender_subcounty_id = $senderTown->subCounty->id;
//             $this->receiver_county_id  = $receiverTown->subCounty->county->id;
//             $this->receiver_subcounty_id = $receiverTown->subCounty->id;

//             $senderPoint = PickUpAndDropOffPoint::findOrFail($this->sender_pick_up_drop_off_point_id);
//             $receivingPoint = PickUpAndDropOffPoint::findOrFail($this->delivery_pick_up_drop_off_point_id);

//             // Sender contact
//             $senderContact = Contact::create([
//                 'name'          => $this->sender_name,
//                 'phone'         => $this->sender_phone,
//                 'email'         => $this->sender_email,
//                 'address'       => $this->sender_address,
//                 'county_id'     => $this->sender_county_id,
//                 'sub_county_id' => $this->sender_subcounty_id,
//                 'town_id'       => $this->sender_town_id,
//             ]);
//             $this->sender_id = $senderContact->id;

//             // Receiver contact
//             $receiverContact = Contact::create([
//                 'name'          => $this->receiver_name,
//                 'phone'         => $this->receiver_phone,
//                 'email'         => $this->receiver_email,
//                 'address'       => $this->receiver_address,
//                 'county_id'     => $this->receiver_county_id,
//                 'sub_county_id' => $this->receiver_subcounty_id,
//             ]);
//             $this->receiver_id = $receiverContact->id;

//             $partnerUser = Auth::guard('partner')->user();
//             $partnerId   = $partnerUser->parcelHandlingAssistant?->partner?->id
//                 ?? $partnerUser->partner?->id;

//             $createdParcels = [];

//             // One Parcel per item
//             foreach ($this->parcelItems as $index => $row) {
//                 $catalogueItem = Item::findOrFail($row['parcel_category_id']);

//                 $basePrice         = (float) ($row['base_price'] ?? $catalogueItem->price ?? 0);
//                 $declaredValue     = (float) ($row['declared_value'] ?? 0);
//                 $insuranceRequired = !empty($row['insurance_required']);
//                 $insuranceAmount   = (float) ($row['item_insurance_amount']
//                     ?? ($insuranceRequired ? round($declaredValue * 0.02, 2) : 0));
//                 $taxAmount         = (float) ($row['tax_amount']
//                     ?? round(($basePrice + $insuranceAmount) * 0.16, 2));
//                 $itemTotal         = (float) ($row['item_total']
//                     ?? round($basePrice + $insuranceAmount + $taxAmount, 2));
//                 $paymentOnDelivery = !empty($row['payment_on_delivery']);

//                 // Resolve weight from catalogue (no manual input from the form)
//                 $resolvedWeight = 0.0;
//                 foreach (['weight', 'default_weight', 'unit_weight'] as $col) {
//                     if (
//                         isset($catalogueItem->{$col})
//                         && is_numeric($catalogueItem->{$col})
//                         && $catalogueItem->{$col} > 0
//                     ) {
//                         $resolvedWeight = (float) $catalogueItem->{$col};
//                         break;
//                     }
//                 }
//                 if ($resolvedWeight <= 0) {
//                     $resolvedWeight = 1.0;
//                 }

//                 $parcelData = [
//                     'parcel_id'     => Parcel::generateParcelNumber(),
//                     'customer_id'   => $this->customer_id,
//                     'sender_id'     => $this->sender_id,
//                     'receiver_id'   => $this->receiver_id,
//                     'booking_type'  => $this->booking_type,
//                     'booking_source' => $this->booking_source,

//                     // Sender
//                     'sender_name'        => $this->sender_name,
//                     'sender_phone'       => $this->sender_phone,
//                     'sender_email'       => $this->sender_email,
//                     'sender_address'     => $this->sender_address,
//                     'sender_county_id'   => $this->sender_county_id,
//                     'sender_subcounty_id' => $this->sender_subcounty_id,
//                     'sender_town_id'     => $this->sender_town_id,
//                     'sender_pick_up_drop_off_point_id' => $this->sender_pick_up_drop_off_point_id,
//                     'sender_notes'       => $this->sender_notes,
//                     'sender_partner_id'  => $senderPoint->partner?->id ?? $partnerId,
//                     'pha_id'             => $partnerUser->parcelHandlingAssistant?->id ?? null,

//                     // Receiver
//                     'receiver_name'         => $this->receiver_name,
//                     'receiver_phone'        => $this->receiver_phone,
//                     'receiver_email'        => $this->receiver_email,
//                     'receiver_address'      => $this->receiver_address,
//                     'receiver_county_id'    => $this->receiver_county_id,
//                     'receiver_subcounty_id' => $this->receiver_subcounty_id,
//                     'receiver_town_id'      => $this->receiver_town_id,
//                     'receiver_notes'        => $this->receiver_notes,
//                     'delivery_pick_up_drop_off_point_id' => $this->delivery_pick_up_drop_off_point_id,
//                     'delivery_partner_id'   => $receivingPoint->partner?->id,

//                     // Item / parcel details
//                     'item_id'               => $catalogueItem->id,
//                     'item_name'             => $catalogueItem->name,
//                     'parcel_category_id'    => $row['parcel_category_id'],
//                     'parcel_type'           => $row['parcel_type'],
//                     'package_type'          => $row['package_type'],
//                     'declared_value'        => $declaredValue,
//                     'insurance_amount'      => $insuranceAmount,
//                     'insurance_required'    => $insuranceRequired,
//                     'content_description'   => $row['content_description'] ?? '',
//                     'special_instructions'  => $row['special_notes'] ?? null,
//                     'special_notes'         => $row['special_notes'] ?? null,
//                     'payment_on_delivery'   => $paymentOnDelivery,

//                     'weight'         => $resolvedWeight,
//                     'weight_unit'    => $this->weight_unit,
//                     'dimension_unit' => $this->dimension_unit,

//                     // Pricing
//                     'base_price'        => $basePrice,
//                     'insurance_charge'  => $insuranceAmount,
//                     'tax_amount'        => $taxAmount,
//                     'total_amount'      => $itemTotal,
//                     'payment_method'    => $this->payment_method,
//                     'payment_status'    => $this->payment_status ?? 'pending',

//                     // Status / audit
//                     'current_status' => Parcel::STATUS_BOOKED,
//                     'created_by'     => $partnerUser->id,
//                     'creator_id'     => $partnerUser->id,
//                     'creator_type'   => current_user_type(),

//                     'transporter_id'   => null,
//                     'transporter_type' => null,
//                 ];

//                 $parcel = Parcel::create($parcelData);
//                 $createdParcels[] = $parcel;

//                 // Payout per parcel
//                 $payout = $parcel->calculateParcelPayout(
//                     (float) ($parcel->base_price + $parcel->tax_amount),
//                     'direct'
//                 );

//                 ParcelPayout::create([
//                     'parcel_id'          => $parcel->id,
//                     'partner_id'         => $partnerId,
//                     'type'               => 'pickup-dropoff',
//                     'destination'        => null,
//                     'destination_id'     => null,
//                     'origin_id'          => $this->sender_pick_up_drop_off_point_id,
//                     'amount'             => $payout['pick_up_drop_off_partner']['amount'],
//                     'status'             => 'pending',
//                     'paid_out_on'        => null,
//                     'cancelation_reason' => null,
//                 ]);

//                 $parcel->updateParcelStatus(
//                     Parcel::STATUS_CREATED,
//                     $this->sender_pick_up_drop_off_point_id,
//                     $partnerUser->id,
//                     current_user_type(),
//                     'Parcel created',
//                     null,
//                     null,
//                 );

//                 $parcel->addTracking(Parcel::STATUS_BOOKED, $partnerUser->id);
//             }

//             DB::commit();

//             // If a single parcel was created, go straight to it.
//             if (count($createdParcels) === 1) {
//                 return redirect()->route('partners.parcels.view', $createdParcels[0]->id);
//             }

//             // Multiple items -> return to index with a success message
//             return redirect()
//                 ->route('partners.parcels.index')
//                 ->with('success', count($createdParcels) . ' parcels created successfully.');
//         } catch (\Illuminate\Validation\ValidationException $e) {
//             DB::rollBack();
//             throw $e;
//         } catch (\Exception $e) {
//             dd($e->getMessage());
//             DB::rollBack();
//             Log::error('Parcel creation error: ' . $e->getMessage(), [
//                 'trace' => $e->getTraceAsString(),
//             ]);
//             $this->addError('general', 'Failed to create parcel: ' . $e->getMessage());
//         }
//     }

//     public function render()
//     {
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
//             'catalogueItems' => $this->items,
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
