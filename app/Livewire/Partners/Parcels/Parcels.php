<?php

namespace App\Livewire\Partners\Parcels;

use App\Models\Parcel;
use App\Models\Driver;
use App\Models\County;
use App\Models\Partner;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Parcels extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Url]
    public $search = '';
    #[Url]
    public $statusFilter = '';
    #[Url]
    public $customerFilter = '';
    #[Url]
    public $driverFilter = '';
    #[Url]
    public $transportPartnerFilter = '';
    #[Url]
    public $pickupPartnerFilter = '';
    #[Url]
    public $deliveryPartnerFilter = '';
    #[Url]
    public $countyFilter = '';
    #[Url]
    public $subcountyFilter = '';
    #[Url]
    public $townFilter = '';
    #[Url]
    public $parcelTypeFilter = '';
    #[Url]
    public $paymentStatusFilter = '';
    #[Url]
    public $sortField = 'created_at';
    #[Url]
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $parcelToDelete = null;
    public $showBulkActions = false;
    public $selectedParcels = [];
    public $selectAll = false;
    public $showAssignDriverModal = false;
    public $selectedParcelForAssignment = null;
    public $selectedDriver = '';
    public $showStatusUpdateModal = false;
    public $selectedParcelForStatusUpdate = null;
    public $newStatus = '';
    public $partnerType;
    public $partner;
    public $loggedDriver;
    public $loggedUser;
    public $loggedUserType;
    public $selectedParcelForDriver;
    public $selectedDriverId;
    public $driverSearch = '';
    public $availableDrivers = [];
    public $notifyDriver = true;
    public $estimatedDeliveryDate;
    public $assignmentNotes = '';

    protected $listeners = [
        'refreshParcels' => '$refresh',
        'driverAssigned' => '$refresh',
        'modalClosed' => 'resetModalState'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'customerFilter' => ['except' => ''],
        'driverFilter' => ['except' => ''],
        'transportPartnerFilter' => ['except' => ''],
        'pickupPartnerFilter' => ['except' => ''],
        'deliveryPartnerFilter' => ['except' => ''],
        'countyFilter' => ['except' => ''],
        'subcountyFilter' => ['except' => ''],
        'townFilter' => ['except' => ''],
        'parcelTypeFilter' => ['except' => ''],
        'paymentStatusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner ?? Auth::guard('partner')->user()->driver?->partner ?? Auth::guard('partner')->user()->parcelHandlingAssistant?->partner;
        $this->partnerType = $this->partner->partner_type;
        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->loggedDriver = Auth::guard('partner')->user()->driver;
        $this->loggedUser = Auth::guard('partner')->user();
    }

    public function showAssignDriver($parcelId)
    {
        $this->selectedParcelForDriver = Parcel::findOrFail($parcelId);
        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->assignmentNotes = '';
        $this->loadAvailableDrivers();
        $this->showAssignDriverModal = true;

        // Dispatch event to open modal
        $this->dispatch('openAssignDriverModal');
    }

    public function loadAvailableDrivers()
    {
        if (!$this->partner) {
            $this->availableDrivers = collect();
            return;
        }

        $query = Driver::query()
            ->where('partner_id', $this->partner->id)
            ->where('status', 'active')
            ->where('is_available', true);

        // Apply search filter
        if (!empty($this->driverSearch)) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->driverSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->driverSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->driverSearch . '%');
            });
        }

        $this->availableDrivers = $query->orderBy('first_name')->get();
    }

    public function updatedDriverSearch()
    {
        $this->loadAvailableDrivers();
    }

    public function selectDriver($driverId)
    {
        $this->selectedDriverId = $driverId;
    }

    public function assignDriver()
    {
        $this->validate([
            'selectedDriverId' => 'required|exists:drivers,id',
        ]);

        if (!$this->selectedParcelForDriver) {
            return;
        }

        try {
            $driver = Driver::find($this->selectedDriverId);

            DB::beginTransaction();
            $this->selectedParcelForDriver->updateParcelStatus(
                Parcel::STATUS_ASSIGNED,
                Auth::guard('partner')->user()->id,
                'transport',
                'Parcel assigned to driver',
                $driver->id,
                $this->selectedParcelForDriver->generateDeliveryOtp(),
            );

            //TODO::Send Email and text to driver when assigned the parcel
            $this->selectedParcelForDriver->current_status = Parcel::STATUS_ASSIGNED;
            $this->selectedPaselectedParcelForDriverrcel->driver_id = $driver->id;
            $this->selectedParcelForDriver->save();

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

        session()->flash('success', "Parcel assigned to driver successfully.");

        $this->dispatch('closeAssignDriverModal');
        $this->resetModalState();
        $this->dispatch('refreshParcels');
    }

    private function sendDriverNotification($driver, $parcel)
    {
        // Implement your notification logic here
        if ($driver->phone) {
            // Send SMS
            $message = "New delivery assignment: Parcel #{$parcel->parcel_number} from {$parcel->sender_address} to {$parcel->receiver_address}";
            // Your SMS sending logic here
        }
    }

    public function resetModalState()
    {
        $this->showAssignDriverModal = false;
        $this->selectedParcelForDriver = null;
        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->availableDrivers = [];
        $this->notifyDriver = true;
        $this->estimatedDeliveryDate = now()->addDay()->format('Y-m-d');
        $this->assignmentNotes = '';
    }

    public function render()
    {
        // dd($this->loggedUser->parcelHandlingAssistant);

        $query = Parcel::query();

        if ($this->loggedUser->partner && $this->loggedUser->partner->partner_type ==  "transport") {

            // dd(11111);
            $query = Parcel::where('transport_partner_id', $this->loggedUser->partner->id);
        } elseif ($this->loggedUser->partner && $this->loggedUser->partner->partner_type ==  "pickup-dropoff") {

            // dd(2222222);
            $query = Parcel::where('sender_partner_id', $this->loggedUser->partner->id)
                ->orWhere('delivery_partner_id', $this->loggedUser->partner->id);
        } elseif ($this->loggedUser->driver && $this->loggedUser->driver) {

            // dd(333333);
            $query = Parcel::where('driver_id', $this->loggedUser->driver->id);
        } elseif ($this->loggedUser->parcelHandlingAssistant) {
            // dd(4444444);
            $query = Parcel::where('pha_id', $this->loggedUser->parcelHandlingAssistant->id)
                ->orWhere('delivery_partner_id', $this->loggedUser->parcelHandlingAssistant->partner->id);
            // ->orWhere('delivery_partner_id', $this->loggedUser->partner->id);
        }

        // Filter by partner type
        // if ($this->partnerType == 'transport') {
        //     $query = Parcel::where('transport_partner_id', $this->partner->id);
        // } elseif ($this->partnerType == 'transport' && $this->loggedDriver) {
        //     $query = Parcel::where('driver_id', $this->loggedDriver->id);
        // } else {
        //     $query = Parcel::where('transport_partner_id', $this->partner->id);
        // }



        // Apply filters
        $query = $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('parcel_number', 'like', '%' . $this->search . '%')
                    ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                    ->orWhere('sender_phone', 'like', '%' . $this->search . '%')
                    ->orWhere('receiver_name', 'like', '%' . $this->search . '%')
                    ->orWhere('receiver_phone', 'like', '%' . $this->search . '%')
                    ->orWhere('content_description', 'like', '%' . $this->search . '%');
            });
        })
            ->when($this->statusFilter, function ($query) {
                $query->where('current_status', $this->statusFilter);
            })
            ->when($this->countyFilter, function ($query) {
                $query->where(function ($q) {
                    $q->where('sender_county_id', $this->countyFilter)
                        ->orWhere('receiver_county_id', $this->countyFilter);
                });
            })
            ->when($this->parcelTypeFilter, function ($query) {
                $query->where('parcel_type', $this->parcelTypeFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            });

        $parcels = $query->orderBy($this->sortField, $this->sortDirection)->paginate(10);

        // Stats calculations
        $totalParcels = $query->count();
        $pendingParcels = Parcel::where('current_status', 'pending')->count();
        $inTransitParcels = Parcel::whereIn('current_status', ['in_transit', 'at_warehouse', 'out_for_delivery'])->count();
        $deliveredParcels = Parcel::where('current_status', 'delivered')->count();

        return view('livewire.partners.parcels.parcels', [
            'parcels' => $parcels,
            'customers' => [],
            'drivers' => Driver::orderBy('first_name')->get(),
            'transportPartners' => Partner::orderBy('company_name')->get(),
            'pickupPartners' => Partner::orderBy('company_name')->get(),
            'deliveryPartners' => Partner::orderBy('company_name')->get(),
            'counties' => County::orderBy('name')->get(),
            'parcelTypes' => [
                '' => 'All Types',
                'document' => 'Document',
                'package' => 'Package',
                'envelope' => 'Envelope',
                'box' => 'Box',
                'pallet' => 'Pallet',
                'other' => 'Other',
            ],
            'statuses' => [
                '' => 'All Status',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'assigned' => 'Assigned',
                'picked_up' => 'Picked Up',
                'in_transit' => 'In Transit',
                'at_warehouse' => 'At Warehouse',
                'out_for_delivery' => 'Out for Delivery',
                'delivered' => 'Delivered',
                'failed' => 'Failed',
                'returned' => 'Returned',
                'cancelled' => 'Cancelled',
            ],
            'paymentStatuses' => [
                '' => 'All Payment Status',
                'pending' => 'Pending',
                'paid' => 'Paid',
                'failed' => 'Failed',
                'refunded' => 'Refunded',
                'partially_paid' => 'Partially Paid',
            ],
            'totalParcels' => $totalParcels,
            'pendingParcels' => $pendingParcels,
            'inTransitParcels' => $inTransitParcels,
            'deliveredParcels' => $deliveredParcels,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($parcelId)
    {
        $this->parcelToDelete = Parcel::find($parcelId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->parcelToDelete) {
            $parcelNumber = $this->parcelToDelete->parcel_number;
            $this->parcelToDelete->delete();

            session()->flash('success', "Parcel #{$parcelNumber} deleted successfully.");
            $this->showDeleteModal = false;
            $this->parcelToDelete = null;
        }
    }

    public function markAsPickedUp($parcelId)
    {
        $parcel = Parcel::find($parcelId);
        if ($parcel) {
            $parcel->markAsPickedUp(Auth::id(), 'Marked as picked up from admin panel');
            session()->flash('success', "Parcel #{$parcel->parcel_number} marked as picked up.");
        }
    }

    public function markAsDelivered($parcelId)
    {
        $parcel = Parcel::find($parcelId);
        if ($parcel) {
            $parcel->markAsDelivered(Auth::id());
            session()->flash('success', "Parcel #{$parcel->parcel_number} marked as delivered.");
        }
    }

    public function showUpdateStatus($parcelId)
    {
        $this->selectedParcelForStatusUpdate = Parcel::find($parcelId);
        $this->newStatus = $this->selectedParcelForStatusUpdate->current_status;
        $this->showStatusUpdateModal = true;
    }

    public function updateStatus()
    {
        $this->validate([
            'newStatus' => 'required|in:pending,confirmed,processing,assigned,picked_up,in_transit,at_warehouse,out_for_delivery,delivered,failed,returned,cancelled',
        ]);

        if ($this->selectedParcelForStatusUpdate) {
            $oldStatus = $this->selectedParcelForStatusUpdate->current_status;

            $this->selectedParcelForStatusUpdate->updateStatus(
                $this->newStatus,
                'Status updated from admin panel',
                Auth::id()
            );

            session()->flash('success', "Parcel status updated from {$oldStatus} to {$this->newStatus}");
            $this->showStatusUpdateModal = false;
            $this->selectedParcelForStatusUpdate = null;
            $this->newStatus = '';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->customerFilter = '';
        $this->driverFilter = '';
        $this->transportPartnerFilter = '';
        $this->pickupPartnerFilter = '';
        $this->deliveryPartnerFilter = '';
        $this->countyFilter = '';
        $this->subcountyFilter = '';
        $this->townFilter = '';
        $this->parcelTypeFilter = '';
        $this->paymentStatusFilter = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->selectedParcels = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = Parcel::query();

            if ($this->statusFilter) {
                $query->where('current_status', $this->statusFilter);
            }

            $this->selectedParcels = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedParcels = [];
        }
        $this->showBulkActions = count($this->selectedParcels) > 0;
    }

    public function updatedSelectedParcels()
    {
        $this->selectAll = false;
        $this->showBulkActions = count($this->selectedParcels) > 0;
    }

    public function bulkDelete()
    {
        if (count($this->selectedParcels) > 0) {
            $parcels = Parcel::whereIn('id', $this->selectedParcels)->get();

            foreach ($parcels as $parcel) {
                $parcel->delete();
            }

            session()->flash('success', count($this->selectedParcels) . ' parcels deleted.');

            $this->selectedParcels = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkMarkAsDelivered()
    {
        if (count($this->selectedParcels) > 0) {
            DB::transaction(function () {
                $parcels = Parcel::whereIn('id', $this->selectedParcels)->get();

                foreach ($parcels as $parcel) {
                    $parcel->markAsDelivered(Auth::id());
                }
            });

            session()->flash('success', count($this->selectedParcels) . ' parcels marked as delivered.');

            $this->selectedParcels = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkCancel()
    {
        if (count($this->selectedParcels) > 0) {
            DB::transaction(function () {
                $parcels = Parcel::whereIn('id', $this->selectedParcels)->get();

                foreach ($parcels as $parcel) {
                    if ($parcel->canBeCancelled()) {
                        $parcel->cancel('Bulk cancellation from admin panel', Auth::id());
                    }
                }
            });

            session()->flash('warning', count($this->selectedParcels) . ' parcels cancelled.');

            $this->selectedParcels = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function hasActiveFilters()
    {
        return $this->search ||
            $this->statusFilter ||
            $this->customerFilter ||
            $this->driverFilter ||
            $this->transportPartnerFilter ||
            $this->pickupPartnerFilter ||
            $this->deliveryPartnerFilter ||
            $this->countyFilter ||
            $this->subcountyFilter ||
            $this->townFilter ||
            $this->parcelTypeFilter ||
            $this->paymentStatusFilter;
    }

    public function getStatusBadge($status)
    {
        $badges = [
            'pending' => ['color' => '#6b7280', 'text' => 'Pending', 'icon' => 'bi-clock'],
            'confirmed' => ['color' => '#3b82f6', 'text' => 'Confirmed', 'icon' => 'bi-check-circle'],
            'processing' => ['color' => '#8b5cf6', 'text' => 'Processing', 'icon' => 'bi-gear'],
            'assigned' => ['color' => '#f59e0b', 'text' => 'Assigned', 'icon' => 'bi-person-check'],
            'picked_up' => ['color' => '#3b82f6', 'text' => 'Picked Up', 'icon' => 'bi-box-arrow-in-down'],
            'in_transit' => ['color' => '#8b5cf6', 'text' => 'In Transit', 'icon' => 'bi-truck'],
            'at_warehouse' => ['color' => '#3b82f6', 'text' => 'At Warehouse', 'icon' => 'bi-building'],
            'out_for_delivery' => ['color' => '#f59e0b', 'text' => 'Out for Delivery', 'icon' => 'bi-box-arrow-up'],
            'delivered' => ['color' => '#10b981', 'text' => 'Delivered', 'icon' => 'bi-check-circle-fill'],
            'failed' => ['color' => '#ef4444', 'text' => 'Failed', 'icon' => 'bi-x-circle'],
            'returned' => ['color' => '#f59e0b', 'text' => 'Returned', 'icon' => 'bi-arrow-return-left'],
            'cancelled' => ['color' => '#1f2937', 'text' => 'Cancelled', 'icon' => 'bi-x-octagon'],
        ];

        return $badges[$status] ?? ['color' => '#6b7280', 'text' => ucfirst($status), 'icon' => 'bi-question-circle'];
    }

    public function getPaymentStatusBadge($paymentStatus)
    {
        $badges = [
            'pending' => ['color' => '#f59e0b', 'text' => 'Pending', 'icon' => 'bi-clock'],
            'paid' => ['color' => '#10b981', 'text' => 'Paid', 'icon' => 'bi-check-circle'],
            'failed' => ['color' => '#ef4444', 'text' => 'Failed', 'icon' => 'bi-x-circle'],
            'refunded' => ['color' => '#3b82f6', 'text' => 'Refunded', 'icon' => 'bi-arrow-counterclockwise'],
            'partially_paid' => ['color' => '#8b5cf6', 'text' => 'Partially Paid', 'icon' => 'bi-percent'],
        ];

        return $badges[$paymentStatus] ?? ['color' => '#6b7280', 'text' => ucfirst($paymentStatus), 'icon' => 'bi-question-circle'];
    }

    public function getParcelTypeBadge($parcelType)
    {
        $badges = [
            'document' => ['color' => '#3b82f6', 'text' => 'Document', 'icon' => 'bi-file-text'],
            'package' => ['color' => '#8b5cf6', 'text' => 'Package', 'icon' => 'bi-box'],
            'envelope' => ['color' => '#6b7280', 'text' => 'Envelope', 'icon' => 'bi-envelope'],
            'box' => ['color' => '#f59e0b', 'text' => 'Box', 'icon' => 'bi-box-seam'],
            'pallet' => ['color' => '#1f2937', 'text' => 'Pallet', 'icon' => 'bi-palette'],
            'other' => ['color' => '#6b7280', 'text' => 'Other', 'icon' => 'bi-question-circle'],
        ];

        return $badges[$parcelType] ?? ['color' => '#6b7280', 'text' => ucfirst($parcelType), 'icon' => 'bi-question-circle'];
    }

    public function getPackageTypeBadge($packageType)
    {
        $badges = [
            'regular' => ['color' => '#6b7280', 'text' => 'Regular', 'icon' => 'bi-box'],
            'fragile' => ['color' => '#f59e0b', 'text' => 'Fragile', 'icon' => 'bi-exclamation-triangle'],
            'perishable' => ['color' => '#3b82f6', 'text' => 'Perishable', 'icon' => 'bi-snow'],
            'valuable' => ['color' => '#10b981', 'text' => 'Valuable', 'icon' => 'bi-gem'],
            'hazardous' => ['color' => '#ef4444', 'text' => 'Hazardous', 'icon' => 'bi-exclamation-octagon'],
            'oversized' => ['color' => '#1f2937', 'text' => 'Oversized', 'icon' => 'bi-aspect-ratio'],
        ];

        return $badges[$packageType] ?? ['color' => '#6b7280', 'text' => ucfirst($packageType), 'icon' => 'bi-box'];
    }
}
