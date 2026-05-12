<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Parcel;
use App\Models\Driver;
use App\Models\County;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\PaymentStructure;
use App\Models\PickUpAndDropOffPoint;
use App\Services\SMSService;
use Illuminate\Broadcasting\Broadcasters\NullBroadcaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Marketplace extends Component
{
    use WithPagination;

    // Filter properties with URL persistence
    #[Url(as: 'search', history: true)]
    public $search = '';

    #[Url(as: 'status', history: true)]
    public $status = '';

    #[Url(as: 'parcel_type', history: true)]
    public $parcel_type = '';

    #[Url(as: 'sender_county', history: true)]
    public $sender_county = '';

    #[Url(as: 'receiver_county', history: true)]
    public $receiver_county = '';

    #[Url(as: 'weight_min', history: true)]
    public $weight_min = '';

    #[Url(as: 'weight_max', history: true)]
    public $weight_max = '';

    #[Url(as: 'service_type', history: true)]
    public $service_type = '';

    #[Url(as: 'size_filter', history: true)]
    public $size_filter = '';

    #[Url(as: 'priority_only', history: true)]
    public $priority_only = false;

    #[Url(as: 'cod_only', history: true)]
    public $cod_only = false;

    #[Url(as: 'high_value_only', history: true)]
    public $high_value_only = false;

    #[Url(as: 'sort', history: true)]
    public $sort = 'created_at';

    #[Url(as: 'direction', history: true)]
    public $direction = 'desc';

    public $perPage = 12;
    public $selectedParcel = null;
    public $showFilters = false;

    // Modal control properties
    public $showParcelDetailsModal = false;
    public $showDeliveryOptionModal = false;
    public $showDriverAssignmentModal = false;

    // Delivery option properties
    public $delivery_option = ''; // 'warehouse' or 'final_destination'
    public $selected_warehouse_id = '';
    public $warehouses = [];
    public $calculated_payout = 0;
    public $base_payout = 0;
    public $warehouse_payout = 0;
    public $final_destination_payout = 0;

    // Driver assignment properties
    public $selectedDriver = '';
    public $assignment_notes = '';

    // Statistics
    public $statistics = [];

    public $transportPartner = null;

    public $drivers = [];

    public $sortField;

    public $showPayoutConfirmationModal = false;

    public $warehousePaymentStructure;
    public $pickUpDropOffPaymentStructure;
    protected SMSService $smsService;


    // Add these methods
    public function initiateDeliveryOption($parcelId)
    {
        $this->selectedParcel = Parcel::findOrFail($parcelId);
        $this->base_payout = $this->selectedParcel->base_price ?? 0;
        $this->delivery_option = '';
        $this->selected_warehouse_id = '';
        $this->calculated_payout = 0;
        $this->loadWarehouses();
        $this->showDeliveryOptionModal = true;
    }

    public function initiateDeliveryOptionFromModal()
    {
        $this->delivery_option = '';
        $this->selected_warehouse_id = '';
        $this->calculated_payout = 0;
        $this->loadWarehouses();
        $this->showParcelDetailsModal = false;
        $this->showDeliveryOptionModal = true;
    }

    public function setDeliveryOption($option)
    {
        $this->delivery_option = $option;
        $this->calculatePayout();
    }


    // Payout calculation constants
    const WAREHOUSE_PERCENTAGE = 0.7; // 70% of original payout for warehouse delivery
    const FINAL_DESTINATION_PERCENTAGE = 1.0; // 100% for final destination

    public function calculatePayout()
    {
        $cost = $this->selectedParcel->base_price;

        if ($this->delivery_option === 'warehouse') {
            $this->calculated_payout = ($cost * $this->warehousePaymentStructure->transport_partner_percentage) / 100;
        } elseif ($this->delivery_option === 'final_destination') {
            $this->calculated_payout = ($cost * $this->pickUpDropOffPaymentStructure->transport_partner_percentage) / 100;
        } else {
            $this->calculated_payout = 0;
        }
    }

    public function updatedSelectedWarehouseId()
    {
        if ($this->delivery_option === 'warehouse') {
            $this->calculatePayout();
        }
    }
    public function closePayoutConfirmationModal()
    {
        $this->showPayoutConfirmationModal = false;
    }

    public function proceedToDriverAssignment()
    {
        $this->showPayoutConfirmationModal = false;
        $this->showDriverAssignmentModal = true;
    }

    protected function loadWarehouses()
    {
        $this->warehouses = PickUpAndDropOffPoint::where('type', 'warehouse')
            ->with('town')
            ->orderBy('name')
            ->get();
    }

    public function mount()
    {
        $this->transportPartner = Partner::where('owner_id', Auth::guard('partner')->user()->id)->first();
        $this->drivers = Driver::where('partner_id', $this->transportPartner?->id)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        if ($this->transportPartner) {
            $this->loadStatistics();
        }

        $this->warehousePaymentStructure = PaymentStructure::where('delivery_type', 'warehouse_split')->first();
        $this->pickUpDropOffPaymentStructure = PaymentStructure::where('delivery_type', 'direct')->first();
    }

    public function render()
    {
        return view('livewire.marketplace.marketplace', [
            'parcels' => $this->getAvailableParcels(),
            'counties' => County::orderBy('name')->get(),
            'parcelTypes' => $this->getParcelTypes(),
            'transportPartner' => $this->transportPartner,
            'statistics' => $this->statistics,
        ]);
    }

    protected function getAvailableParcels()
    {
        $query = Parcel::where('current_status', 'pending');

        // Apply filters
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('parcel_number', 'like', '%' . $this->search . '%')
                    ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                    ->orWhere('receiver_name', 'like', '%' . $this->search . '%')
                    ->orWhere('content_description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->status)) {
            $query->where('current_status', $this->status);
        }

        if (!empty($this->parcel_type)) {
            $query->where('parcel_type', $this->parcel_type);
        }

        if (!empty($this->sender_county)) {
            $query->where('sender_county_id', $this->sender_county);
        }

        if (!empty($this->receiver_county)) {
            $query->where('receiver_county_id', $this->receiver_county);
        }

        if (!empty($this->weight_min)) {
            $query->where('weight', '>=', $this->weight_min);
        }

        if (!empty($this->weight_max)) {
            $query->where('weight', '<=', $this->weight_max);
        }

        // Size filter based on weight
        if (!empty($this->size_filter)) {
            switch ($this->size_filter) {
                case 'small':
                    $query->where('weight', '<=', 5);
                    break;
                case 'medium':
                    $query->whereBetween('weight', [5.01, 15]);
                    break;
                case 'large':
                    $query->where('weight', '>', 15);
                    break;
            }
        }

        // Priority filter
        if ($this->priority_only) {
            $query->where('is_priority', true);
        }

        // COD filter
        if ($this->cod_only) {
            $query->where('is_cod', true);
        }

        // High value filter
        if ($this->high_value_only) {
            $query->where('declared_value', '>', 20000);
        }

        // Apply sorting
        $query->orderBy($this->sort, $this->direction);

        return $query->paginate($this->perPage);
    }

    protected function loadStatistics()
    {
        $this->statistics = [
            'total_available' => 0,
            'active_bids' => 0,
            'won_bids' => 0,
            'total_earnings' => 4500,
        ];
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'status',
            'parcel_type',
            'sender_county',
            'receiver_county',
            'weight_min',
            'weight_max',
            'service_type',
            'size_filter',
            'priority_only',
            'cod_only',
            'high_value_only'
        ]);
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedParcelType()
    {
        $this->resetPage();
    }

    // Modal methods
    public function viewParcelDetails($parcelId)
    {
        $this->selectedParcel = Parcel::findOrFail($parcelId);
        $this->showParcelDetailsModal = true;
        $this->showDeliveryOptionModal = false;
        $this->showDriverAssignmentModal = false;
    }

    public function closeParcelModal()
    {
        $this->showParcelDetailsModal = false;
        $this->selectedParcel = null;
    }

    public function acceptParcel($parcelId)
    {
        $parcel = Parcel::findOrFail($parcelId);
        $this->selectedParcel = $parcel;

        // Calculate payouts
        $this->base_payout = $parcel->base_price ?? 0;

        // $this->warehouse_payout = $this->base_payout * self::WAREHOUSE_PERCENTAGE;
        // $this->final_destination_payout = $this->base_payout * self::FINAL_DESTINATION_PERCENTAGE;

        // Load warehouses (points with type 'warehouse')
        $this->loadWarehouses();

        // Reset delivery option
        $this->delivery_option = '';
        $this->selected_warehouse_id = '';
        $this->calculated_payout = 0;

        // Close details modal and show delivery option modal
        $this->showParcelDetailsModal = false;
        $this->showDeliveryOptionModal = true;
    }

    // public function updatedDeliveryOption()
    // {
    //     if ($this->delivery_option === 'warehouse') {
    //         $this->calculated_payout = $this->warehouse_payout;
    //     } elseif ($this->delivery_option === 'final_destination') {
    //         $this->calculated_payout = $this->final_destination_payout;
    //     } else {
    //         $this->calculated_payout = 0;
    //     }
    // }

    public function confirmDeliveryOption()
    {
        $this->validate([
            'delivery_option' => 'required|in:warehouse,final_destination',
            'selected_warehouse_id' => 'required_if:delivery_option,warehouse|exists:pick_up_and_drop_off_points,id'
        ], [
            'delivery_option.required' => 'Please select a delivery option.',
            'selected_warehouse_id.required_if' => 'Please select a warehouse for delivery.',
            'selected_warehouse_id.exists' => 'Selected warehouse does not exist.'
        ]);

        try {
            DB::beginTransaction();

            // Update parcel with delivery option and payout
            // $this->selectedParcel->delivery_option = $this->delivery_option;
            // $this->selectedParcel->partner_payout = $this->calculated_payout;

            // if ($this->delivery_option === 'warehouse') {
            //     $this->selectedParcel->delivery_warehouse_id = $this->selected_warehouse_id;
            // }

            // $this->selectedParcel->save();

            // Update parcel status to accepted
            $this->selectedParcel->updateParcelStatus(
                Parcel::STATUS_ACCEPTED,
                Auth::guard('partner')->user()->id,
                'transport',
                'Parcel accepted with ' . ($this->delivery_option === 'warehouse' ? 'warehouse' : 'final destination') . ' delivery. Payout: KES ' . $this->calculated_payout,
                null,
                $this->selectedParcel->generateDeliveryOtp(),
            );

            $this->selectedParcel->current_status = Parcel::STATUS_ACCEPTED;
            $this->selectedParcel->save();

            $this->selectedParcel->parcelPayout()->where('type', 'pickup-dropoff')->whereNotNull('origin_id')->update([
                'status' => 'approved'
            ]);

            $destination = null;
            $destination_id = null;
            $amount = 0;

            $this->warehousePaymentStructure = PaymentStructure::where('delivery_type', 'warehouse_split')->first();
            $this->pickUpDropOffPaymentStructure = PaymentStructure::where('delivery_type', 'direct')->first();

            if ($this->delivery_option === 'warehouse') {
                $destination = 'warehouse';
                $destination_id = $this->selected_warehouse_id;
                $amount = ($this->selectedParcel->base_price * $this->warehousePaymentStructure->transport_partner_percentage) / 100;
            } elseif ($this->delivery_option === 'final_destination') {
                $destination = 'final';
                $destination_id = $this->selectedParcel->delivery_pick_up_drop_off_point_id;
                $amount = ($this->selectedParcel->base_price * $this->pickUpDropOffPaymentStructure->transport_partner_percentage) / 100;
            }

            $parcelPayout = ParcelPayout::create([
                'parcel_id' =>  $this->selectedParcel->id,
                'partner_id' => Auth::guard('partner')->user()->id,
                'type' => 'transport',
                'destination' => $destination,
                'destination_id' => $destination_id,
                'origin_id' => $this->selectedParcel->sender_pick_up_drop_off_point_id,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            DB::commit();

            // Close delivery option modal and show driver assignment modal
            $this->showDeliveryOptionModal = false;
            $this->showDriverAssignmentModal = true;
            $this->resetDriverForm();

            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Delivery option confirmed! Payout: KES ' . number_format($this->calculated_payout)
            ]);
        } catch (\Exception $e) {

            dd($e->getMessage());
            DB::rollBack();
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Failed to confirm delivery option: ' . $e->getMessage()
            ]);
        }
    }

    public function closeDeliveryOptionModal()
    {
        $this->showDeliveryOptionModal = false;
        $this->selectedParcel = null;
        $this->delivery_option = '';
        $this->selected_warehouse_id = '';
        $this->calculated_payout = 0;
    }

    public function closeDriverModal()
    {
        $this->showDriverAssignmentModal = false;
        $this->selectedDriver = '';
        $this->assignment_notes = '';
        $this->selectedParcel = null;
    }

    protected function resetDriverForm()
    {
        $this->selectedDriver = '';
        $this->assignment_notes = '';
    }

    public function assignDriver(SMSService $smsService)
    {
        $this->validate([
            'selectedParcel.id' => 'required|exists:parcels,id',
            'selectedDriver' => 'required|exists:drivers,id',
            'assignment_notes' => 'nullable|string|max:500'
        ]);

        if (!$this->transportPartner) {
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'You need to be registered as a transport partner.'
            ]);
            return;
        }

        try {
            $driver = Driver::find($this->selectedDriver);

            $parcelCode = $this->selectedParcel->generateDeliveryOtp();
            DB::beginTransaction();
            $this->selectedParcel->updateParcelStatus(
                Parcel::STATUS_ASSIGNED,
                Auth::guard('partner')->user()->id,
                'transport',
                'Parcel assigned to driver: ' . $driver->first_name . ' ' . $driver->last_name,
                $driver->id,
                $parcelCode
            );

            $this->selectedParcel->current_status = Parcel::STATUS_ASSIGNED;
            $this->selectedParcel->driver_id = $driver->id;
            $this->selectedParcel->save();

            DB::commit();


            //Send Driver SMS notification
            try {
                Log::info('START::Sending SMS to driver after Asignment');
                $smsService->sendDriverAssignmentSMS(
                    $driver->phone_number,
                    $driver->full_name,
                    $this->parcel->senderTown->name,
                    $this->parcel->receiverTown->name,
                    $parcelCode
                );
                Log::info('START::Sending SMS to driver after Asignment');
            } catch (\Throwable $th) {
                Log::error('Failed to send SMS to driver: ', [
                    'error' => $th->getMessage(),
                    'stack' => $th->getTraceAsString(),
                ]);
            }

            // Refresh statistics
            $this->loadStatistics();

            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Driver assigned successfully!'
            ]);

            $this->closeDriverModal();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-notification', [
                'type' => 'error',
                'message' => 'Failed to assign driver: ' . $e->getMessage()
            ]);
        }
    }

    #[On('refreshMarketplace')]
    public function refreshMarketplace()
    {
        $this->resetPage();
        $this->loadStatistics();

        $this->dispatch('show-notification', [
            'type' => 'info',
            'message' => 'Marketplace refreshed successfully!'
        ]);
    }

    protected function getParcelTypes()
    {
        return [
            'document' => 'Document',
            'package' => 'Package',
            'envelope' => 'Envelope',
            'box' => 'Box',
            'pallet' => 'Pallet',
            'other' => 'Other',
        ];
    }

    public function getFormattedPayout($parcel)
    {
        return 'KES ' . number_format($parcel->estimated_cost ?? 0, 0);
    }

    public function getDistance($parcel)
    {
        return rand(50, 500) . ' km';
    }

    public function getPickupTime($parcel)
    {
        if ($parcel->pickup_date) {
            return $parcel->pickup_date->format('M d, g:i A');
        }
        return 'Today, ' . now()->format('g:i A');
    }

    public function getDeliveryDeadline($parcel)
    {
        if ($parcel->delivery_window) {
            return $parcel->delivery_window;
        }
        return 'Tomorrow, ' . now()->addDay()->format('g:i A');
    }
}
