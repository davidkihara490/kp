<?php

namespace Database\Seeders;

use App\Models\Fleet;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all transport partners
        $transportPartners = Partner::where('partner_type', 'transport')->get();

        foreach ($transportPartners as $partner) {
            // Create motorcycles
            Fleet::factory()
                ->count(rand(2, 5))
                ->motorcycle()
                ->available()
                ->withValidRegistration()
                ->withValidInsurance()
                ->serviced()
                ->withPartner($partner)
                ->create();

            // Create pickups
            Fleet::factory()
                ->count(rand(1, 3))
                ->pickup()
                ->available()
                ->withValidRegistration()
                ->withValidInsurance()
                ->serviced()
                ->withPartner($partner)
                ->create();

            // Create vans
            Fleet::factory()
                ->count(rand(1, 3))
                ->van()
                ->available()
                ->withValidRegistration()
                ->withValidInsurance()
                ->serviced()
                ->withPartner($partner)
                ->create();

            // Create trucks
            Fleet::factory()
                ->count(rand(1, 4))
                ->truck()
                ->available()
                ->withValidRegistration()
                ->withValidInsurance()
                ->serviced()
                ->withPartner($partner)
                ->create();

            // Maybe add some refrigerated trucks
            if (rand(0, 1)) {
                Fleet::factory()
                    ->count(rand(1, 2))
                    ->refrigerated()
                    ->available()
                    ->withValidRegistration()
                    ->withValidInsurance()
                    ->serviced()
                    ->withPartner($partner)
                    ->create();
            }

            // Add some vehicles in maintenance
            Fleet::factory()
                ->count(rand(1, 2))
                ->maintenance()
                ->withPartner($partner)
                ->create();

            // Add some vehicles with expired documents
            Fleet::factory()
                ->count(rand(0, 1))
                ->withExpiredRegistration()
                ->withExpiredInsurance()
                ->withPartner($partner)
                ->create();

            // Add some vehicles needing service
            Fleet::factory()
                ->count(rand(1, 2))
                ->needsService()
                ->withPartner($partner)
                ->create();
        }

        // Create specific fleet for the test transport partner
        $testPartner = Partner::whereHas('owner', function ($query) {
            $query->where('email', 'transportpartner_test@karibuparcels.com');
        })->first();

        if ($testPartner) {
            // Create a specific Hilux
            Fleet::factory()->create([
                'partner_id' => $testPartner->id,
                'registration_number' => 'KCE 123A',
                'make' => 'Toyota',
                'model' => 'Hilux Double Cabin',
                'vehicle_type' => 'pickup',
                'color' => 'White',
                'status' => 'active',
                'is_available' => true,
                'registration_expiry' => '2025-12-31',
                'insurance_expiry' => '2024-12-31',
                'last_service_date' => '2024-01-15',
                'next_service_date' => '2024-04-15',
                'year' => 2022,
                'capacity' => 1100,
                'fuel_type' => 'diesel',
                'notes' => 'Main vehicle for executive deliveries',
            ]);

            // Create a specific truck
            Fleet::factory()->create([
                'partner_id' => $testPartner->id,
                'registration_number' => 'KDA 456B',
                'make' => 'Isuzu',
                'model' => 'FRR 110',
                'vehicle_type' => 'truck',
                'color' => 'Blue',
                'status' => 'active',
                'is_available' => false,
                'registration_expiry' => '2024-06-30',
                'insurance_expiry' => '2024-06-30',
                'last_service_date' => '2024-02-01',
                'next_service_date' => '2024-05-01',
                'year' => 2021,
                'capacity' => 8500,
                'fuel_type' => 'diesel',
                'notes' => 'Currently on long-distance route to Mombasa',
            ]);

            // Create a motorcycle
            Fleet::factory()->create([
                'partner_id' => $testPartner->id,
                'registration_number' => 'KMCA 789C',
                'make' => 'Boxer',
                'model' => 'BM150',
                'vehicle_type' => 'motorcycle',
                'color' => 'Red',
                'status' => 'active',
                'is_available' => true,
                'registration_expiry' => '2024-08-31',
                'insurance_expiry' => '2024-08-31',
                'last_service_date' => '2024-02-20',
                'next_service_date' => '2024-05-20',
                'year' => 2023,
                'capacity' => 150,
                'fuel_type' => 'petrol',
                'notes' => 'Used for last-mile deliveries in CBD',
            ]);

            // Create a refrigerated truck
            Fleet::factory()->create([
                'partner_id' => $testPartner->id,
                'registration_number' => 'KDC 101R',
                'make' => 'Hino',
                'model' => '300 Series (Reefer)',
                'vehicle_type' => 'refrigerated_truck',
                'color' => 'White',
                'status' => 'active',
                'is_available' => true,
                'registration_expiry' => '2024-11-30',
                'insurance_expiry' => '2024-11-30',
                'last_service_date' => '2024-01-10',
                'next_service_date' => '2024-04-10',
                'year' => 2022,
                'capacity' => 4500,
                'fuel_type' => 'diesel',
                'notes' => 'Refrigerated unit for perishable goods. Thermo King unit.',
            ]);

            $this->command->info('Created specific fleet vehicles for test transport partner');
        }
    }
}



// // Create a single fleet
// $fleet = Fleet::factory()->create();

// // Create a specific type of fleet
// $motorcycle = Fleet::factory()->motorcycle()->available()->create();
// $truck = Fleet::factory()->truck()->withValidInsurance()->create();

// // Create fleet for a specific partner
// $fleet = Fleet::factory()->withPartner($partner)->count(5)->create();

// // Create a mix of fleet types
// $fleets = collect([
//     Fleet::factory()->motorcycle()->available()->make(),
//     Fleet::factory()->pickup()->available()->make(),
//     Fleet::factory()->van()->available()->make(),
//     Fleet::factory()->truck()->available()->make(),
// ]);

// // Create fleet with specific attributes
// $customFleet = Fleet::factory()->create([
//     'registration_number' => 'KCE 123A',
//     'make' => 'Toyota',
//     'model' => 'Hilux',
//     'color' => 'White',
//     'year' => 2023,
// ]);