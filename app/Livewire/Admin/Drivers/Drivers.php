<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use App\Models\Partner;
use Livewire\Component;
use Livewire\WithPagination;
class Drivers extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $transportPartnerFilter = '';
    public $licenseStatusFilter = '';
    public $availabilityFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'transportPartnerFilter' => ['except' => ''],
        'licenseStatusFilter' => ['except' => ''],
        'availabilityFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public $showDeleteModal = false;
    public $driverToDelete;
    public $transportPartners = [];

    public function mount()
    {
        $this->transportPartners = Partner::orderBy('company_name')->get();
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

    public function confirmDelete($id)
    {
        $this->driverToDelete = Driver::find($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $name = $this->driverToDelete->full_name;
            $this->driverToDelete->delete();
            
            session()->flash('success', "Driver '{$name}' deleted successfully!");
            $this->showDeleteModal = false;
            $this->driverToDelete = null;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'transportPartnerFilter', 'licenseStatusFilter', 'availabilityFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Driver::query();
            // ->withCount(['trips', 'deliveries']);
            // ->withCount(['trips', 'deliveries']);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('driver_id', 'like', '%' . $this->search . '%')
                  ->orWhere('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                  ->orWhere('driving_license_number', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Transport Partner filter
        if ($this->transportPartnerFilter) {
            $query->where('partner_id', $this->transportPartnerFilter);
        }

        // License status filter
        if ($this->licenseStatusFilter === 'expired') {
            $query->where('driving_license_expiry_date', '<', now());
        } elseif ($this->licenseStatusFilter === 'valid') {
            $query->where('driving_license_expiry_date', '>=', now());
        }

        // Availability filter
        if ($this->availabilityFilter === 'available') {
            $query->where('is_available', true)->where('status', 'active');
        } elseif ($this->availabilityFilter === 'unavailable') {
            $query->where('is_available', false)->orWhere('status', '!=', 'active');
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $drivers = $query->paginate($this->perPage);

        return view('livewire.admin.drivers.drivers', [
            'drivers' => $drivers,
            'statuses' => ['active', 'inactive', 'suspended', 'on_leave', 'terminated'],
        ]);
    }
}