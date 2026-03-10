<?php

namespace App\Livewire\Admin\Fleets;

use App\Models\Fleet;
use App\Models\Partner;
use App\Models\TransportPartner;
use Livewire\Component;
use Livewire\WithPagination;

class Fleets extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $transportPartnerFilter = '';
    public $availabilityFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'transportPartnerFilter' => ['except' => ''],
        'availabilityFilter' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public $showDeleteModal = false;
    public $fleetToDelete;
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
        $this->fleetToDelete = Fleet::find($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        try {
            $registration = $this->fleetToDelete->registration_number;
            $this->fleetToDelete->delete();
            
            session()->flash('success', "Fleet '{$registration}' deleted successfully!");
            $this->showDeleteModal = false;
            $this->fleetToDelete = null;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete fleet: ' . $e->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'typeFilter', 'transportPartnerFilter', 'availabilityFilter']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Fleet::query();

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('registration_number', 'like', '%' . $this->search . '%')
                  ->orWhere('make', 'like', '%' . $this->search . '%')
                  ->orWhere('model', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Type filter
        if ($this->typeFilter) {
            $query->where('vehicle_type', $this->typeFilter);
        }

        // Transport Partner filter
        if ($this->transportPartnerFilter) {
            $query->where('partner_id', $this->transportPartnerFilter);
        }

        // Availability filter
        if ($this->availabilityFilter === 'available') {
            $query->where('is_available', true)->where('status', 'active');
        } elseif ($this->availabilityFilter === 'unavailable') {
            $query->where('is_available', false)->orWhere('status', '!=', 'active');
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $fleets = $query->paginate($this->perPage);

        return view('livewire.admin.fleets.fleets', [
            'fleets' => $fleets,
            'statuses' => ['active', 'maintenance', 'inactive', 'accident'],
            'types' => ['van', 'truck', 'pickup', 'motorcycle', 'car'],
        ]);
    }
}