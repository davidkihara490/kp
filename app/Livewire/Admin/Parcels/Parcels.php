<?php

namespace App\Livewire\Admin\Parcels;

use App\Models\Parcel;
use App\Models\Town;
use App\Models\Partner;
use App\Models\Driver;
use App\Models\PickUpAndDropOffPoint;
use Livewire\Component;
use Livewire\WithPagination;

class Parcels extends Component
{
    use WithPagination;

    // Search filters
    public $search = '';
    public $parcelId = '';
    public $senderName = '';
    public $receiverName = '';
    public $senderPhone = '';
    public $receiverPhone = '';

    // Dropdown filters
    public $status = '';
    public $paymentStatus = '';
    public $parcelType = '';
    public $packageType = '';
    public $senderTownId = '';
    public $receiverTownId = '';
    public $transportPartnerId = '';
    public $driverId = '';

    // Date range filters
    public $dateFrom = '';
    public $dateTo = '';

    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Statistics
    public $totalParcels = 0;
    public $pendingCount = 0;
    public $inTransitCount = 0;
    public $deliveredCount = 0;
    public $totalRevenue = 0;

    // Modal states
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $selectedParcel = null;

    // Bulk actions
    public $selectedParcels = [];
    public $selectAll = false;
    public $showBulkActionModal = false;
    public $bulkAction = '';
    public $perPage = 15;
    protected $queryString = [
        'search' => ['except' => ''],
        'parcelId' => ['except' => ''],
        'senderName' => ['except' => ''],
        'receiverName' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'senderTownId' => ['except' => ''],
        'receiverTownId' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->calculateStatistics();
    }

    public function updated($property)
    {
        // Reset pagination when filters change
        if (in_array($property, [
            'search',
            'parcelId',
            'senderName',
            'receiverName',
            'status',
            'paymentStatus',
            'senderTownId',
            'receiverTownId',
            'dateFrom',
            'dateTo'
        ])) {
            $this->resetPage();
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedParcels = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedParcels = [];
        }
    }

    public function calculateStatistics()
    {
        $this->totalParcels = Parcel::count();
        $this->pendingCount = Parcel::where('current_status', 'pending')->count();
        $this->inTransitCount = Parcel::whereIn('current_status', ['assigned', 'in_transit', 'warehouse'])->count();
        $this->deliveredCount = Parcel::where('current_status', 'delivered')->count();
        $this->totalRevenue = Parcel::where('payment_status', 'paid')->sum('total_amount');
    }

    protected function getFilteredQuery()
    {
        return Parcel::query()
            ->with(['senderTown', 'receiverTown', 'transportPartner', 'driver', 'payments'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('parcel_id', 'like', '%' . $this->search . '%')
                        ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                        ->orWhere('receiver_name', 'like', '%' . $this->search . '%')
                        ->orWhere('sender_phone', 'like', '%' . $this->search . '%')
                        ->orWhere('receiver_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->parcelId, function ($query) {
                $query->where('parcel_id', 'like', '%' . $this->parcelId . '%');
            })
            ->when($this->senderName, function ($query) {
                $query->where('sender_name', 'like', '%' . $this->senderName . '%');
            })
            ->when($this->receiverName, function ($query) {
                $query->where('receiver_name', 'like', '%' . $this->receiverName . '%');
            })
            ->when($this->senderPhone, function ($query) {
                $query->where('sender_phone', 'like', '%' . $this->senderPhone . '%');
            })
            ->when($this->receiverPhone, function ($query) {
                $query->where('receiver_phone', 'like', '%' . $this->receiverPhone . '%');
            })
            ->when($this->status, function ($query) {
                $query->where('current_status', $this->status);
            })
            ->when($this->paymentStatus, function ($query) {
                $query->where('payment_status', $this->paymentStatus);
            })
            ->when($this->parcelType, function ($query) {
                $query->where('parcel_type', $this->parcelType);
            })
            ->when($this->packageType, function ($query) {
                $query->where('package_type', $this->packageType);
            })
            ->when($this->senderTownId, function ($query) {
                $query->where('sender_town_id', $this->senderTownId);
            })
            ->when($this->receiverTownId, function ($query) {
                $query->where('receiver_town_id', $this->receiverTownId);
            })
            ->when($this->transportPartnerId, function ($query) {
                $query->where('transport_partner_id', $this->transportPartnerId);
            })
            ->when($this->driverId, function ($query) {
                $query->where('driver_id', $this->driverId);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection);
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

    public function viewParcel($id)
    {
        $this->selectedParcel = Parcel::with([
            'senderTown',
            'receiverTown',
            'transportPartner',
            'driver',
            'senderPartner',
            'senderPickUpDropOffPoint',
            'deliveryStation',
            'payments',
            'statuses' => function ($q) {
                $q->latest()->limit(10);
            }
        ])->find($id);

        $this->showViewModal = true;
    }

    public function confirmDelete($id)
    {
        $this->selectedParcel = Parcel::find($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->selectedParcel) {
            $parcelId = $this->selectedParcel->parcel_id;
            $this->selectedParcel->delete();
            $this->showDeleteModal = false;
            $this->selectedParcel = null;
            $this->calculateStatistics();
            session()->flash('success', "Parcel {$parcelId} deleted successfully.");
        }
    }

    public function openBulkActionModal($action)
    {
        if (count($this->selectedParcels) === 0) {
            session()->flash('error', 'Please select at least one parcel.');
            return;
        }

        $this->bulkAction = $action;
        $this->showBulkActionModal = true;
    }

    public function executeBulkAction()
    {
        $parcels = Parcel::whereIn('id', $this->selectedParcels)->get();
        $count = $parcels->count();

        switch ($this->bulkAction) {
            case 'assign':
                // This would open an assignment modal in a real app
                session()->flash('info', 'Bulk assignment feature coming soon.');
                break;

            case 'print':
                session()->flash('info', 'Bulk printing feature coming soon.');
                break;

            case 'delete':
                Parcel::whereIn('id', $this->selectedParcels)->delete();
                session()->flash('success', $count . ' parcels deleted successfully.');
                break;
        }

        $this->selectedParcels = [];
        $this->selectAll = false;
        $this->showBulkActionModal = false;
        $this->calculateStatistics();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'parcelId',
            'senderName',
            'receiverName',
            'senderPhone',
            'receiverPhone',
            'status',
            'paymentStatus',
            'parcelType',
            'packageType',
            'senderTownId',
            'receiverTownId',
            'transportPartnerId',
            'driverId',
            'dateFrom',
            'dateTo'
        ]);
        $this->resetPage();
    }

    public function export($format)
    {
        session()->flash('info', 'Export functionality coming soon.');
    }

    public function render()
    {
        $parcels = $this->getFilteredQuery()->paginate($this->perPage);

        $statuses = [
            '' => 'All Statuses',
            'pending' => 'Pending',
            'accepted' => 'Accepted',
            'assigned' => 'Assigned',
            'in_transit' => 'In Transit',
            'warehouse' => 'At Warehouse',
            'arrived_at_destination' => 'Arrived',
            'picked' => 'Picked Up',
            'delivered' => 'Delivered',
            'failed' => 'Failed',
            'returned' => 'Returned',
        ];

        $paymentStatuses = [
            '' => 'All Payments',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            'partially_paid' => 'Partially Paid',
        ];

        $parcelTypes = [
            '' => 'All Types',
            'document' => 'Document',
            'package' => 'Package',
            'envelope' => 'Envelope',
            'box' => 'Box',
            'pallet' => 'Pallet',
            'other' => 'Other',
        ];

        $packageTypes = [
            '' => 'All Package Types',
            'regular' => 'Regular',
            'fragile' => 'Fragile',
            'perishable' => 'Perishable',
            'valuable' => 'Valuable',
            'hazardous' => 'Hazardous',
            'oversized' => 'Oversized',
        ];

        $towns = Town::orderBy('name')->get()->pluck('name', 'id');
        $transportPartners = Partner::where('partner_type', 'transport')->orderBy('company_name')->get()->pluck('company_name', 'id');
        $drivers = Driver::where('status', 'active')->get()->pluck('full_name', 'id');

        return view('livewire.admin.parcels.parcels', [
            'parcels' => $parcels,
            'statuses' => $statuses,
            'paymentStatuses' => $paymentStatuses,
            'parcelTypes' => $parcelTypes,
            'packageTypes' => $packageTypes,
            'towns' => $towns,
            'transportPartners' => $transportPartners,
            'drivers' => $drivers,

        ]);
    }
}
