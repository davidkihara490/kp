<?php

namespace Database\Seeders;

use App\Models\DriverFleetAssignment;
use App\Models\Fleet;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverFleetAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all active fleets and drivers
        $fleets = Fleet::where('status', 'active')->get();
        $drivers = Driver::where('status', 'active')->get();
        $users = User::where('user_type', 'admin')->orWhere('user_type', 'transport')->get();

        if ($fleets->isEmpty() || $drivers->isEmpty()) {
            $this->command->info('No fleets or drivers found. Skipping assignments.');
            return;
        }

        // Create active assignments for some fleets
        $assignedFleets = [];

        foreach ($fleets->take(floor($fleets->count() / 2)) as $fleet) {
            // Find an available driver (not currently assigned)
            $availableDriver = $drivers->filter(function ($driver) use ($assignedFleets) {
                return !in_array($driver->id, array_column($assignedFleets, 'driver_id'));
            })->first();

            if ($availableDriver) {
                $assigner = $users->random();

                DriverFleetAssignment::factory()
                    ->active()
                    ->forFleet($fleet)
                    ->forDriver($availableDriver)
                    ->assignedBy($assigner)
                    ->create();

                $assignedFleets[] = [
                    'fleet_id' => $fleet->id,
                    'driver_id' => $availableDriver->id,
                ];

                $this->command->info("Assigned {$availableDriver->full_name} to {$fleet->full_name}");
            }
        }

        // Create some completed assignments
        for ($i = 0; $i < 10; $i++) {
            $fleet = $fleets->random();
            $driver = $drivers->random();
            $assigner = $users->random();

            DriverFleetAssignment::factory()
                ->completed()
                ->forFleet($fleet)
                ->forDriver($driver)
                ->assignedBy($assigner)
                ->create();
        }

        // Create some short-term assignments
        for ($i = 0; $i < 5; $i++) {
            $fleet = $fleets->random();
            $driver = $drivers->random();
            $assigner = $users->random();

            DriverFleetAssignment::factory()
                ->shortTerm()
                ->forFleet($fleet)
                ->forDriver($driver)
                ->assignedBy($assigner)
                ->create();
        }

        // Create specific assignment for test driver
        $testDriver = Driver::where('email', 'driver_test@karibuparcels.com')->first();
        $testFleet = Fleet::where('registration_number', 'KCE 123A')->first();
        $admin = User::where('email', 'admin@karibuparcels.com')->first();

        if ($testDriver && $testFleet && $admin) {
            DriverFleetAssignment::factory()
                ->testAssignment()
                ->create();

            $this->command->info('Created test assignment for driver_test@karibuparcels.com');
        }

        $this->command->info('Driver Fleet Assignments seeded successfully!');
    }

    // // Create a single assignment
    // DriverFleetAssignment::factory()->create();

    // // Create an active assignment
    // DriverFleetAssignment::factory()->active()->create();

    // // Assign a specific driver to a specific fleet
    // DriverFleetAssignment::factory()
    //     ->active()
    //     ->forFleet($fleet)
    //     ->forDriver($driver)
    //     ->assignedBy($admin)
    //     ->create();

    // // Create multiple assignments
    // DriverFleetAssignment::factory()
    //     ->count(5)
    //     ->completed()
    //     ->create();
}
