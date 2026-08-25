<?php

namespace App\Livewire\Clients\Parcels;

use Livewire\Component;
use App\Models\Parcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Parcels extends Component
{
    public $parcels = [];
    public $selectedParcel = null;
    public $showParcelDetail = false;
    public $searchTerm = '';
    public $statusFilter = '';
    public $dateRange = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'overview';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateRange' => ['except' => 'all'],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->loadParcels();
    }

    public function loadParcels()
    {
        $query = Parcel::where('customer_id', Auth::guard('customer')->id())
            ->with(['senderTown', 'receiverTown', 'latestStatus', 'payments']);

        // Search filter
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('parcel_id', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('sender_name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('receiver_name', 'LIKE', '%' . $this->searchTerm . '%')
                    ->orWhere('parcel_type', 'LIKE', '%' . $this->searchTerm . '%');
            });
        }

        // Status filter
        if (!empty($this->statusFilter)) {
            $query->where('current_status', $this->statusFilter);
        }

        // Date range filter
        if ($this->dateRange !== 'all') {
            $date = now();
            switch ($this->dateRange) {
                case 'today':
                    $query->whereDate('created_at', $date->today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$date->startOfWeek(), $date->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', $date->year);
                    break;
            }
        }

        // Sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $this->parcels = $query->get();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['searchTerm', 'statusFilter', 'dateRange', 'sortBy', 'sortDirection'])) {
            $this->loadParcels();
        }
    }

    public function viewParcel($parcelId)
    {
        $this->selectedParcel = Parcel::with([
            'senderTown',
            'receiverTown',
            'statuses' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'payments',
            'latestStatus',
            'transportPartner'
        ])->findOrFail($parcelId);

        $this->activeTab = 'overview';
        $this->showParcelDetail = true;
    }

    public function closeParcelDetail()
    {
        $this->showParcelDetail = false;
        $this->selectedParcel = null;
        $this->activeTab = 'overview';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'created' => 'secondary',
            'booked' => 'info',
            'accepted' => 'primary',
            'assigned' => 'warning',
            'in_transit' => 'primary',
            'pending' => 'warning',
            'warehouse' => 'info',
            'arrived_at_destination' => 'success',
            'picked' => 'info',
            'delivered' => 'success',
            'failed' => 'danger',
            'returned' => 'warning',
            default => 'secondary',
        };
    }

    public function getStatusIcon($status)
    {
        return match ($status) {
            'created' => 'bi-clock',
            'booked' => 'bi-check-circle',
            'accepted' => 'bi-check-circle',
            'assigned' => 'bi-person-check',
            'in_transit' => 'bi-truck',
            'pending' => 'bi-clock-history',
            'warehouse' => 'bi-building',
            'arrived_at_destination' => 'bi-geo-alt',
            'picked' => 'bi-box-arrow-up',
            'delivered' => 'bi-check-circle-fill',
            'failed' => 'bi-x-circle',
            'returned' => 'bi-arrow-return-left',
            default => 'bi-question-circle',
        };
    }

    public function getStatusLabel($status)
    {
        return str_replace('_', ' ', ucfirst($status));
    }

    public function render()
    {
        return view('livewire.clients.parcels.parcels');
    }
}
