<?php

namespace App\Livewire\Partners\Drivers;

use App\Models\Driver;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Drivers extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $partnerFilter = '';
    public $licenseStatusFilter = '';
    public $availabilityFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $showDeleteModal = false;
    public $driverToDeleteId = null;
    public $driverToDeleteName = '';

    // Stats properties
    public $totalDrivers = 0;
    public $activeDrivers = 0;
    public $availableDrivers = 0;
    public $expiringLicenses = 0;

    public $hasFilters = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'partnerFilter' => ['except' => ''],
        'licenseStatusFilter' => ['except' => ''],
        'availabilityFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {

        $partner = Auth::guard('partner')->user()->partner;

        $this->totalDrivers = $partner->drivers()->count();
        $this->activeDrivers = $partner->drivers()->where('status' , 'active')->count();
        $this->availableDrivers = $partner->drivers()->where('is_available', true)->count();
        $this->expiringLicenses = $partner->drivers()->where('driving_license_expiry_date', '<', now()->addDays(30))
            ->where('driving_license_expiry_date', '>', now())
            ->count();
    }

    public function getHasFiltersProperty()
    {
        return $this->search || $this->statusFilter || $this->partnerFilter ||
            $this->licenseStatusFilter || $this->availabilityFilter;
    }

    public function getSelectedPartnerNameProperty()
    {
        if ($this->partnerFilter) {
            $partner = Partner::find($this->partnerFilter);
            return $partner ? $partner->company_name : '';
        }
        return '';
    }

    public function getStatusesProperty()
    {
        return ['active', 'inactive', 'suspended', 'on_leave', 'terminated'];
    }

    public function getPartnersProperty()
    {
        return Partner::where('partner_type', 'transport')
            ->where('verification_status', 'verified')
            ->orderBy('company_name')
            ->get();
    }

    public function getDriversProperty()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $query = Driver::where('partner_id', $partner->id);
           

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                    ->orWhere('driving_license_number', 'like', '%' . $this->search . '%')
                    ->orWhere('id_number', 'like', '%' . $this->search . '%');
            });
        }

        // Partner filter
        if ($this->partnerFilter) {
            $query->whereHas('currentEmployment', function ($q) {
                $q->where('partner_id', $this->partnerFilter);
            });
        }

        // License status filter
        if ($this->licenseStatusFilter) {
            $now = now();
            if ($this->licenseStatusFilter === 'valid') {
                $query->where('driving_license_expiry_date', '>', $now);
            } elseif ($this->licenseStatusFilter === 'expiring') {
                $query->where('driving_license_expiry_date', '>', $now)
                    ->where('driving_license_expiry_date', '<', $now->addDays(30));
            } elseif ($this->licenseStatusFilter === 'expired') {
                $query->where('driving_license_expiry_date', '<', $now);
            }
        }

        // Availability filter
        if ($this->availabilityFilter) {
            $query->where('is_available', $this->availabilityFilter === 'available');
        }

        // Sorting
        if ($this->sortField === 'current_partner_id') {
            $query->leftJoin('driver_employments', function ($join) {
                $join->on('drivers.id', '=', 'driver_employments.driver_id')
                    ->whereNull('driver_employments.to')
                    ->where('driver_employments.status', 'active');
            })->orderBy('driver_employments.partner_id', $this->sortDirection)
                ->select('drivers.*');
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate(20);
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

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'partnerFilter', 'licenseStatusFilter', 'availabilityFilter']);
        $this->resetPage();
    }

    public function confirmDelete($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $this->driverToDeleteId = $driverId;
        $this->driverToDeleteName = $driver->full_name;
        $this->showDeleteModal = true;

        $this->dispatch('showDeleteModal');
    }

    public function deleteDriver()
    {
        DB::beginTransaction();

        try {
            $driver = Driver::findOrFail($this->driverToDeleteId);

            // Delete user account if exists
            if ($driver->user) {
                $driver->user->delete();
            }

            // Delete employments
            $driver->employments()->delete();

            // Delete driver
            $driver->delete();

            DB::commit();

            session()->flash('success', 'Driver deleted successfully.');
            $this->loadStats();

            $this->dispatch('hideDeleteModal');
            $this->dispatch('showToast', 'Driver deleted successfully.');

            $this->reset(['showDeleteModal', 'driverToDeleteId', 'driverToDeleteName']);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    public function cancelDelete()
    {
        $this->reset(['showDeleteModal', 'driverToDeleteId', 'driverToDeleteName']);
    }

    public function toggleAvailability($driverId)
    {
        $driver = Driver::findOrFail($driverId);
        $driver->update([
            'is_available' => !$driver->is_available
        ]);

        $status = $driver->is_available ? 'available' : 'unavailable';
        session()->flash('success', "Driver marked as {$status}.");
        $this->loadStats();

        $this->dispatch('showToast', "Driver marked as {$status}.");
    }

    public function exportDrivers()
    {
        // Export logic here
        session()->flash('success', 'Drivers exported successfully.');
        $this->dispatch('showToast', 'Drivers exported successfully.');
    }

    public function render()
    {
        return view('livewire.partners.drivers.drivers', [
            'drivers' => $this->drivers,
            'partners' => $this->partners,
            'statuses' => $this->statuses,
        ]);
    }
}
