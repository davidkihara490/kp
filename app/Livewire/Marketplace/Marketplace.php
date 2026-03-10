<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Parcel;
use App\Models\Bid;
use App\Models\Driver;
use App\Models\County;
use App\Models\Partner;
use App\Models\ParcelTracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public $showDriverAssignmentModal = false;

    // Bid form properties
    public $bid_amount = '';
    public $estimated_delivery_time = '';
    public $bid_vehicle_type = '';
    public $bid_notes = '';

    // Driver assignment properties
    public $selectedDriver = '';
    public $assignment_notes = '';

    // Statistics
    public $statistics = [];

    public $transportPartner = null;

    public $drivers = [];

    public function mount()
    {
        $this->transportPartner = Partner::where('owner_id', Auth::guard('partner')->user()->id)->first();
        $this->drivers = Driver::where('partner_id', $this->transportPartner->id)->where('status', 'active')->orderBy('first_name')->get();



        if ($this->transportPartner) {
            $this->loadStatistics();
        }
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
        $query = Parcel::where('payment_status', 'paid')
            // ->with([
            //         'senderCounty',
            //         'receiverCounty',
            //         'pickupPartner',
            //         'deliveryPartner'
            //     ])
            ->where('current_status', 'created');

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

        DB::beginTransaction();

        $parcelStatus = $parcel->updateParcelStatus(
            Parcel::STATUS_ACCEPTED,
            Auth::guard('partner')->user()->id,
            'transport',
            'Parcel accepted',
            null,
            $parcel->generateDeliveryOtp(),

        );

        $parcel->current_status = Parcel::STATUS_ACCEPTED;
        $parcel->save();


        DB::commit();

        // Close details modal if open and show driver assignment modal
        $this->selectedParcel = $parcel;
        $this->showParcelDetailsModal = false;
        $this->showDriverAssignmentModal = true;
        $this->resetDriverForm();
    }

    public function closeDriverModal()
    {
        $this->showDriverAssignmentModal = false;
        $this->selectedDriver = '';
        $this->assignment_notes = '';
    }

    protected function resetDriverForm()
    {
        $this->selectedDriver = '';
        $this->assignment_notes = '';
    }

    protected function resetBidForm()
    {
        $this->bid_amount = '';
        $this->estimated_delivery_time = '';
        $this->bid_vehicle_type = '';
        $this->bid_notes = '';
    }

    public function assignDriver()
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

            DB::beginTransaction();
            $this->selectedParcel->updateParcelStatus(
                Parcel::STATUS_ASSIGNED,
                Auth::guard('partner')->user()->id,
                'transport',
                'Parcel assigned to driver',
                $driver->id,
                $this->selectedParcel->generateDeliveryOtp(),
            );

            //TODO::Send Email and text to driver when assigned the parcel
            $this->selectedParcel->current_status = Parcel::STATUS_ASSIGNED;
            $this->selectedParcel->driver_id = $driver->id;
            $this->selectedParcel->save();

            DB::commit();


            // Refresh statistics
            $this->loadStatistics();

            $this->dispatch('show-notification', [
                'type' => 'success',
                'message' => 'Driver assigned successfully!'
            ]);

            $this->closeDriverModal();
        } catch (\Exception $e) {
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


    protected function mapVehicleType($fleetType)
    {
        $mapping = [
            'motorcycle' => 'motorcycle',
            'pickup' => 'pickup_truck',
            'van' => 'van',
            'truck' => 'truck',
            'refrigerated' => 'refrigerated_truck',
            'heavy' => 'heavy_truck',
        ];

        return $mapping[$fleetType] ?? 'pickup_truck';
    }

    public function getServiceTypeClass($parcel)
    {
        return 'standard';
    }

    public function getServiceBadgeClass($parcel)
    {
        return 'badge-standard';
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
