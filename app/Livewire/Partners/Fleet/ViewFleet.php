<?php

namespace App\Livewire\Partners\Fleet;

use App\Models\Fleet;
use App\Models\Driver;
use App\Models\Maintenance;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ViewFleet extends Component
{
    public $fleet;
    public $maintenanceHistory = [];
    public $recentTrips = [];
    public $driverInfo = null;
    public $showDeleteModal = false;

    // Statistics
    public $totalTrips = 0;
    public $totalDistance = 0;
    public $averageLoad = 0;
    public $fuelConsumption = 0;

    public function mount($id)
    {
        $this->fleet = Fleet::findOrFail($id);
        $this->loadFleetDetails();
        $this->loadStatistics();
    }

    public function loadFleetDetails()
    {
        // Load driver information
        if ($this->fleet->current_driver_id) {
            $this->driverInfo = Driver::find($this->fleet->current_driver_id);
        }

        // Load maintenance history (last 5)
        // $this->maintenanceHistory = Maintenance::where('fleet_id', $this->fleet->id)
        //     ->orderBy('maintenance_date', 'desc')
        //     ->take(5)
        //     ->get();

        // Load recent trips (last 5)
        // $this->recentTrips = Trip::where('fleet_id', $this->fleet->id)
        //     ->orderBy('created_at', 'desc')
        //     ->take(5)
        //     ->get();


        // Placeholder maintenance history
        $this->maintenanceHistory = collect([
            (object) [
                'id' => 1,
                'maintenance_date' => now()->subDays(15)->format('Y-m-d'),
                'service_type' => 'Routine Service',
                'description' => 'Oil change, filter replacement, tire rotation',
                'cost' => 12500,
                'next_service_date' => now()->addMonths(3)->format('Y-m-d'),
                'service_center' => 'AutoCare Center',
                'odometer_at_service' => 125000
            ],
            (object) [
                'id' => 2,
                'maintenance_date' => now()->subMonths(2)->format('Y-m-d'),
                'service_type' => 'Brake Service',
                'description' => 'Brake pad replacement and fluid change',
                'cost' => 28500,
                'next_service_date' => now()->addMonths(6)->format('Y-m-d'),
                'service_center' => 'Brake Masters',
                'odometer_at_service' => 118500
            ],
            (object) [
                'id' => 3,
                'maintenance_date' => now()->subMonths(4)->format('Y-m-d'),
                'service_type' => 'Engine Tune-up',
                'description' => 'Spark plug replacement, fuel system cleaning',
                'cost' => 32000,
                'next_service_date' => now()->addMonths(8)->format('Y-m-d'),
                'service_center' => 'Engine Experts',
                'odometer_at_service' => 112000
            ],
            (object) [
                'id' => 4,
                'maintenance_date' => now()->subMonths(6)->format('Y-m-d'),
                'service_type' => 'Tire Replacement',
                'description' => 'Full set of 4 new tires',
                'cost' => 45000,
                'next_service_date' => now()->addYears(2)->format('Y-m-d'),
                'service_center' => 'Tire World',
                'odometer_at_service' => 105000
            ],
            (object) [
                'id' => 5,
                'maintenance_date' => now()->subMonths(8)->format('Y-m-d'),
                'service_type' => 'Suspension Repair',
                'description' => 'Shock absorber replacement',
                'cost' => 38000,
                'next_service_date' => now()->addYears(3)->format('Y-m-d'),
                'service_center' => 'Suspension Pros',
                'odometer_at_service' => 98000
            ]
        ]);

        // Placeholder recent trips
        $this->recentTrips = collect([
            (object) [
                'id' => 101,
                'trip_id' => 'TRP-2024-001',
                'origin' => 'Nairobi Depot',
                'destination' => 'Mombasa Port',
                'distance_km' => 485,
                'status' => 'completed',
                'start_date' => now()->subDays(3)->format('Y-m-d'),
                'end_date' => now()->subDays(2)->format('Y-m-d'),
                'total_weight' => 2500,
                'revenue' => 75000,
                'created_at' => now()->subDays(3)
            ],
            (object) [
                'id' => 102,
                'trip_id' => 'TRP-2024-002',
                'origin' => 'Mombasa Port',
                'destination' => 'Kisumu',
                'distance_km' => 650,
                'status' => 'completed',
                'start_date' => now()->subDays(5)->format('Y-m-d'),
                'end_date' => now()->subDays(4)->format('Y-m-d'),
                'total_weight' => 3000,
                'revenue' => 92000,
                'created_at' => now()->subDays(3)

            ],
            (object) [
                'id' => 103,
                'trip_id' => 'TRP-2024-003',
                'origin' => 'Kisumu',
                'destination' => 'Eldoret',
                'distance_km' => 180,
                'status' => 'in_progress',
                'start_date' => now()->subDays(1)->format('Y-m-d'),
                'end_date' => null,
                'total_weight' => 1800,
                'revenue' => 45000,
                'created_at' => now()->subDays(3)

            ],
            (object) [
                'id' => 104,
                'trip_id' => 'TRP-2024-004',
                'origin' => 'Eldoret',
                'destination' => 'Nakuru',
                'distance_km' => 160,
                'status' => 'scheduled',
                'start_date' => now()->addDays(2)->format('Y-m-d'),
                'end_date' => null,
                'total_weight' => 2200,
                'revenue' => 38000,
                'created_at' => now()->subDays(3)

            ],
            (object) [
                'id' => 105,
                'trip_id' => 'TRP-2024-005',
                'origin' => 'Nakuru',
                'destination' => 'Nairobi Depot',
                'distance_km' => 160,
                'status' => 'cancelled',
                'start_date' => now()->subDays(7)->format('Y-m-d'),
                'end_date' => null,
                'total_weight' => 0,
                'revenue' => 0,
                'created_at' => now()->subDays(3)

            ]
        ]);
    }

    public function loadStatistics()
    {
        // Total trips
        // $this->totalTrips = Trip::where('fleet_id', $this->fleet->id)->count();
        $this->totalTrips = rand(10, 100); // Placeholder for demo purposes


        // Total distance traveled
        // $this->totalDistance = Trip::where('fleet_id', $this->fleet->id)
        //     ->sum('distance_km') ?? 0;
        $this->totalDistance = rand(100, 1000); // Placeholder for demo purposes

        // Calculate average load (example logic)
        // $completedTrips = Trip::where('fleet_id', $this->fleet->id)
        //     ->where('status', 'completed')
        //     ->get();
        $completedTrips = collect([
            (object) ['total_weight' => 500],
            (object) ['total_weight' => 700],
            (object) ['total_weight' => 600],
        ]); // Placeholder for demo purposes



        if ($completedTrips->count() > 0) {
            $this->averageLoad = $completedTrips->avg('total_weight') ?? 0;
        }

        // Calculate fuel consumption (example: 10km per liter)
        $this->fuelConsumption = $this->totalDistance > 0 ?
            round($this->totalDistance / 10, 2) : 0; // Assuming 10km/liter
    }

    //     public function loadStatistics()
    // {
    //     // Placeholder statistics
    //     $this->totalTrips = 47;
    //     $this->totalDistance = 24500; // Total kilometers
    //     $this->averageLoad = 2650; // Average load in kg
    //     $this->fuelConsumption = 2450; // Total liters consumed
    //     $this->fuelEfficiency = 10.0; // Kilometers per liter
    //     $this->totalRevenue = 3250000; // Total revenue in local currency
    //     $this->maintenanceCost = 156000; // Total maintenance cost
    // }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function deleteFleet()
    {
        try {
            DB::beginTransaction();

            // Delete related records if needed
            // Trip::where('fleet_id', $this->fleet->id)->delete();
            // Maintenance::where('fleet_id', $this->fleet->id)->delete();

            $this->fleet->delete();

            DB::commit();

            session()->flash('success', 'Fleet vehicle deleted successfully!');
            return redirect()->route('partners.fleet.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete fleet: ' . $e->getMessage());
        }
    }

    public function updateStatus($status)
    {
        try {
            $this->fleet->update(['status' => $status]);
            session()->flash('success', 'Status updated successfully!');
            $this->fleet->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function toggleAvailability()
    {
        try {
            $this->fleet->update(['is_available' => !$this->fleet->is_available]);
            session()->flash('success', 'Availability updated successfully!');
            $this->fleet->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update availability: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.fleet.view-fleet');
    }
}
