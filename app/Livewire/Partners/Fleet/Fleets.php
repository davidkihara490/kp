<?php

namespace App\Livewire\Partners\Fleet;

use App\Models\Driver;
use App\Models\DriverFleetAssignment;
use App\Models\Fleet;
use App\Models\Partner;
use App\Models\TransportPartner;
use Carbon\Carbon;
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
    public $drivers = [];
    public $transportPartner = null;
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
        $this->drivers = Driver::where('partner_id', $this->transportPartner->id)->where('status', 'active')->orderBy('first_name')->get();
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
        $partner = Auth::guard('partner')->user()->partner;


        $query = Fleet::where('partner_id', $partner->id);

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
            $query->where('is_available', false)->orWhere('status', '!=', 'active');
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $fleets = $query->paginate($this->perPage);

        return view('livewire.partners.fleet.fleets', [
            'fleets' => $fleets,
            'statuses' => ['active', 'maintenance', 'inactive', 'accident'],
            'types' => ['van', 'truck', 'pickup', 'motorcycle', 'car'],
        ]);
    }

    public function openAssignDriverModal($vehicleId)
    {
        $this->selectedVehicleId = $vehicleId;
        $this->selectedVehicle = Fleet::findOrFail($vehicleId);
        $this->selectedDriverId = null;
        $this->driverSearch = '';
        $this->driverStatusFilter = '';
        $this->drivers;
        $this->showAssignDriverModal = true;
    }

    public function selectDriver($driverId)
    {
        $this->selectedDriverId = $driverId;
    }

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


            DriverFleetAssignment::where('fleet_id', $this->selectedVehicleId)->update([
                'to' => Carbon::now(),
                'status' => 'inactive'
            ]);

            $newAssignment = DriverFleetAssignment::create([
                'fleet_id' => $this->selectedVehicleId,
                'driver_id' => $this->selectedDriverId,
                'from' => Carbon::now(),
                'to' => NULL,
                'status' => 'active',
                'assigned_by' => Auth::guard('partner')->user()->id
            ]);

            $message = "Driver {$driver->first_name} {$driver->last_name} assigned successfully to vehicle {$vehicle->registration_number}.";

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
}
