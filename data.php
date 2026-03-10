<?php

namespace App\Livewire\Partners\Fleet;

use App\Models\Driver;
use App\Models\Fleet;
use App\Models\Partner;
use App\Models\TransportPartner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $transportPartner = null;

    // Assign Driver Modal Properties
    public $showAssignDriverModal = false;
    public $selectedVehicleId = null;
    public $selectedVehicle = null;
    public $availableDrivers = [];
    public $driverSearch = '';
    public $driverStatusFilter = '';
    public $selectedDriverId = null;

    public function mount()
    {
        $this->transportPartner = Partner::where('owner_id', Auth::guard('partner')->user()->id)->first();
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

    /**
     * Open the assign driver modal for a specific vehicle
     */
    public function openAssignDriverModal($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $this->selectedVehicle = Fleet::with('currentDriver')->find($vehicleId);
        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->driverStatusFilter = '';
        $this->loadAvailableDrivers();
        $this->showAssignDriverModal = true;
    }

    /**
     * Load available drivers based on search and filter criteria
     */
    public function loadAvailableDrivers()
    {
        if (!$this->transportPartner) {
            $this->availableDrivers = [];
            return;
        }

        $query = Driver::where('partner_id', $this->transportPartner->id)
            ->where('status', 'active'); // Only active drivers can be assigned

        // Search filter
        if ($this->driverSearch) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->driverSearch . '%')
                    ->orWhere('last_name', 'like', '%' . $this->driverSearch . '%')
                    ->orWhere('email', 'like', '%' . $this->driverSearch . '%')
                    ->orWhere('phone', 'like', '%' . $this->driverSearch . '%');
            });
        }

        // Status filter (available/assigned/off_duty)
        if ($this->driverStatusFilter) {
            $query->where('status', $this->driverStatusFilter);
        }

        // Exclude drivers who are already assigned to other vehicles if needed
        // You can customize this logic based on your business rules
        // For now, we'll show all active drivers

        $this->availableDrivers = $query->orderBy('first_name')->get();
    }

    /**
     * Listen for changes in driver search and filter
     */
    public function updatedDriverSearch()
    {
        $this->loadAvailableDrivers();
    }

    /**
     * Listen for changes in driver status filter
     */
    public function updatedDriverStatusFilter()
    {
        $this->loadAvailableDrivers();
    }

    /**
     * Select a driver from the list
     */
    public function selectDriver($driverId)
    {
        $this->selectedDriverId = $driverId;
    }

    /**
     * Assign the selected driver to the vehicle
     */
    public function assignDriver()
    {
        if (!$this->selectedVehicleId || !$this->selectedDriverId) {
            session()->flash('error', 'Please select a driver to assign.');
            return;
        }

        try {
            DB::beginTransaction();

            $vehicle = Fleet::find($this->selectedVehicleId);
            $driver = Driver::find($this->selectedDriverId);

            if (!$vehicle || !$driver) {
                throw new \Exception('Vehicle or driver not found.');
            }

            // Check if driver is active
            if ($driver->status !== 'active') {
                throw new \Exception('Cannot assign an inactive driver.');
            }

            // If vehicle already has a driver, we need to handle the reassignment
            if ($vehicle->currentDriver) {
                // Option 1: Just update the vehicle's driver
                // Option 2: Also update the old driver's status/assigned vehicle
                // You can customize this based on your business logic

                // For now, we'll just update the vehicle
                $oldDriverId = $vehicle->current_driver_id;

                // Update vehicle with new driver
                $vehicle->current_driver_id = $driver->id;
                $vehicle->save();

                // Optionally update the old driver's status
                if ($oldDriverId) {
                    Driver::where('id', $oldDriverId)->update(['assigned_vehicle_id' => null]);
                }

                // Update new driver's assigned vehicle
                $driver->assigned_vehicle_id = $vehicle->id;
                $driver->save();

                $message = "Driver changed successfully from {$vehicle->currentDriver->first_name} {$vehicle->currentDriver->last_name} to {$driver->first_name} {$driver->last_name}.";
            } else {
                // No existing driver, simple assignment
                $vehicle->current_driver_id = $driver->id;
                $vehicle->save();

                // Update driver's assigned vehicle
                $driver->assigned_vehicle_id = $vehicle->id;
                $driver->save();

                $message = "Driver {$driver->first_name} {$driver->last_name} assigned successfully to vehicle {$vehicle->registration_number}.";
            }

            DB::commit();

            session()->flash('success', $message);
            $this->showAssignDriverModal = false;
            $this->selectedVehicleId = null;
            $this->selectedVehicle = null;
            $this->selectedDriverId = null;
            $this->driverSearch = '';
            $this->driverStatusFilter = '';
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to assign driver: ' . $e->getMessage());
        }
    }

    /**
     * Get available drivers count for stats
     */
    public function getAvailableDriversCountProperty()
    {
        if (!$this->transportPartner) {
            return 0;
        }
        return Driver::where('partner_id', $this->transportPartner->id)
            ->where('status', 'active')
            ->count();
    }

    /**
     * Get vehicles needing service count
     */
    public function getServiceNeededCountProperty()
    {
        if (!$this->transportPartner) {
            return 0;
        }
        return Fleet::where('partner_id', $this->transportPartner->id)
            ->where('status', 'maintenance')
            ->count();
    }

    /**
     * Get vehicles with expired documents count
     */
    public function getExpiredDocsCountProperty()
    {
        if (!$this->transportPartner) {
            return 0;
        }
        // You can customize this based on your document expiry logic
        // For now, returning 0 as placeholder
        return 0;
    }

    /**
     * Get available vehicles count
     */
    public function getAvailableCountProperty()
    {
        if (!$this->transportPartner) {
            return 0;
        }
        return Fleet::where('partner_id', $this->transportPartner->id)
            ->where('is_available', true)
            ->where('status', 'active')
            ->count();
    }

    public function render()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $query = Fleet::with(['currentDriver', 'transportPartner'])
            ->where('partner_id', $partner->id);

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
            $query->where('transport_partner_id', $this->transportPartnerFilter);
        }

        // Availability filter
        if ($this->availabilityFilter === 'available') {
            $query->where('is_available', true)->where('status', 'active');
        } elseif ($this->availabilityFilter === 'unavailable') {
            $query->where(function ($q) {
                $q->where('is_available', false)
                    ->orWhere('status', '!=', 'active');
            });
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $fleets = $query->paginate($this->perPage);

        // Add status icons for badges
        foreach ($fleets as $fleet) {
            $fleet->status_icon = match ($fleet->status) {
                'active' => 'fa-check-circle',
                'maintenance' => 'fa-tools',
                'inactive' => 'fa-clock',
                'accident' => 'fa-exclamation-triangle',
                default => 'fa-circle'
            };
        }

        return view('livewire.partners.fleet.fleets', [
            'fleets' => $fleets,
            'statuses' => ['active', 'maintenance', 'inactive', 'accident'],
            'types' => ['van', 'truck', 'pickup', 'motorcycle', 'car'],
            'availableCount' => $this->available_count,
            'serviceNeededCount' => $this->service_needed_count,
            'expiredDocsCount' => $this->expired_docs_count,
            'availableDriversCount' => $this->available_drivers_count,
        ]);
    }
}
