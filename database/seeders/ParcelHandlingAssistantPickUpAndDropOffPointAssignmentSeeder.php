<?php

namespace Database\Seeders;

use App\Models\ParcelHandlingAssistantPickUpAndDropOffPointAssignment;
use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParcelHandlingAssistantPickUpAndDropOffPointAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create active PHAs
        $phas = $this->getOrCreatePHAs();
        
        // Get or create active points
        $points = $this->getOrCreatePoints();
        
        // Get partners
        $partners = $this->getOrCreatePartners();
        
        // Get assigners (users)
        $assigners = $this->getOrCreateAssigners();

        if ($phas->isEmpty()) {
            $this->command->warn('No PHAs available. Creating sample PHAs...');
            $phas = $this->createSamplePHAs($partners);
        }

        if ($points->isEmpty()) {
            $this->command->warn('No points available. Creating sample points...');
            $points = $this->createSamplePoints($partners);
        }

        if ($assigners->isEmpty()) {
            $this->command->warn('No assigners available. Creating sample assigners...');
            $assigners = $this->createSampleAssigners();
        }

        $this->command->info('Creating PHA assignments...');

        // Create the warehouse assignment first
        $this->createWarehouseAssignment();

        // Assign PHAs to points (each PHA to one point)
        $assignedPHAs = [];
        $assignmentCount = 0;
        
        foreach ($phas as $pha) {
            // Skip if already assigned
            if (in_array($pha->id, $assignedPHAs)) {
                continue;
            }

            // Skip if no points available
            if ($points->isEmpty()) {
                break;
            }

            // Find points with fewer than 3 assignments
            $availablePoints = $points->filter(function ($point) {
                $currentAssignments = ParcelHandlingAssistantPickUpAndDropOffPointAssignment
                    ::where('pick_up_and_drop_off_point_id', $point->id)
                    ->where('status', 'active')
                    ->count();
                
                return $currentAssignments < 3;
            });

            if ($availablePoints->isEmpty()) {
                continue;
            }

            try {
                $point = $availablePoints->random();
            } catch (\InvalidArgumentException $e) {
                // No available points, skip this iteration
                continue;
            }

            $assigner = $assigners->random();
            $partner = $pha->partner ?? $point->partner ?? $partners->random();

            ParcelHandlingAssistantPickUpAndDropOffPointAssignment::factory()
                ->active()
                ->forPHA($pha)
                ->forPoint($point)
                ->forPartner($partner)
                ->assignedBy($assigner)
                ->create();

            $assignedPHAs[] = $pha->id;
            $assignmentCount++;

            $this->command->info("Assigned {$pha->first_name} {$pha->last_name} to {$point->name}");
        }

        $this->command->info("Created {$assignmentCount} active assignments");

        // Create some completed assignments if we have data
        if ($phas->isNotEmpty() && $points->isNotEmpty()) {
            $completedCount = min(15, $phas->count() * 2);
            for ($i = 0; $i < $completedCount; $i++) {
                try {
                    $pha = $phas->random();
                    $point = $points->random();
                    $assigner = $assigners->random();
                    $partner = $pha->partner ?? $point->partner ?? $partners->random();

                    ParcelHandlingAssistantPickUpAndDropOffPointAssignment::factory()
                        ->completed()
                        ->forPHA($pha)
                        ->forPoint($point)
                        ->forPartner($partner)
                        ->assignedBy($assigner)
                        ->create();
                } catch (\Exception $e) {
                    // Skip if random fails
                    continue;
                }
            }
            $this->command->info("Created completed assignments");
        }

        $this->command->info('PHA Assignments seeded successfully!');
    }

    /**
     * Get or create PHAs
     */
    private function getOrCreatePHAs()
    {
        $phas = ParcelHandlingAssistant::where('status', 'active')->get();
        
        if ($phas->isEmpty()) {
            $phas = ParcelHandlingAssistant::factory()->count(5)->active()->create();
        }
        
        return $phas;
    }

    /**
     * Get or create points
     */
    private function getOrCreatePoints()
    {
        $points = PickUpAndDropOffPoint::where('status', 'active')->get();
        
        if ($points->isEmpty()) {
            $points = PickUpAndDropOffPoint::factory()->count(5)->active()->create();
        }
        
        return $points;
    }

    /**
     * Get or create partners
     */
    private function getOrCreatePartners()
    {
        $partners = Partner::whereIn('partner_type', ['pickup-dropoff', 'transport'])->get();
        
        if ($partners->isEmpty()) {
            $partners = Partner::factory()->count(3)->pickupDropoff()->verified()->create();
        }
        
        return $partners;
    }

    /**
     * Get or create assigners (users)
     */
    private function getOrCreateAssigners()
    {
        $assigners = User::whereIn('user_type', ['admin', 'transport', 'pickup-dropoff'])->get();
        
        if ($assigners->isEmpty()) {
            $assigners = User::factory()->count(3)->admin()->active()->create();
        }
        
        return $assigners;
    }

    /**
     * Create sample PHAs
     */
    private function createSamplePHAs($partners)
    {
        $phas = collect();
        
        $samplePHAs = [
            ['first_name' => 'John', 'second_name' => 'Kamau', 'last_name' => 'Mwangi'],
            ['first_name' => 'Mary', 'second_name' => 'Wanjiku', 'last_name' => 'Njoroge'],
            ['first_name' => 'Peter', 'second_name' => 'Omondi', 'last_name' => 'Odhiambo'],
            ['first_name' => 'Grace', 'second_name' => 'Akinyi', 'last_name' => 'Achieng'],
            ['first_name' => 'David', 'second_name' => 'Kipchoge', 'last_name' => 'Kiprotich'],
        ];

        foreach ($samplePHAs as $index => $sample) {
            $partner = $partners->random();
            $email = strtolower($sample['first_name'] . '.' . $sample['last_name'] . rand(1, 999) . '@pha.co.ke');
            
            $pha = ParcelHandlingAssistant::factory()
                ->active()
                ->withRole($index === 0 ? 'Supervisor' : fake()->randomElement(['Loader', 'Sorter', 'Checker']))
                ->withPartner($partner)
                ->create([
                    'first_name' => $sample['first_name'],
                    'second_name' => $sample['second_name'],
                    'last_name' => $sample['last_name'],
                    'email' => $email,
                    'phone_number' => $this->generateKenyanPhone(),
                    'id_number' => (string) fake()->unique()->numberBetween(20000000, 39999999),
                ]);
            
            $phas->push($pha);
        }

        $this->command->info('Created sample PHAs');
        return $phas;
    }

    /**
     * Create sample points
     */
    private function createSamplePoints($partners)
    {
        $points = collect();
        
        $locations = [
            ['name' => 'Nairobi CBD', 'building' => 'I&M Bank Tower', 'town' => 'Nairobi'],
            ['name' => 'Westlands', 'building' => 'Delta Towers', 'town' => 'Nairobi'],
            ['name' => 'Mombasa Central', 'building' => 'Mombasa Trade Centre', 'town' => 'Mombasa'],
            ['name' => 'Kisumu City', 'building' => 'Kisumu Tower', 'town' => 'Kisumu'],
            ['name' => 'Industrial Area', 'building' => 'Export Processing Zone', 'town' => 'Nairobi'],
        ];

        foreach ($locations as $location) {
            $partner = $partners->random();
            $code = strtoupper(substr($location['name'], 0, 3)) . '-' . str_pad(fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);
            
            $point = PickUpAndDropOffPoint::factory()
                ->active()
                ->withPartner($partner)
                ->create([
                    'name' => $location['name'] . ' Pickup Point',
                    'code' => $code,
                    'building' => $location['building'],
                    'address' => $location['building'] . ', ' . $location['town'] . ', Kenya',
                    'contact_person' => fake()->name(),
                    'contact_email' => $location['town'] . '@karibuparcels.com',
                    'contact_phone_number' => $this->generateKenyanPhone(),
                    'operating_days' => 'Monday - Friday',
                    'opening_hours' => '08:00:00',
                    'closing_hours' => '17:00:00',
                ]);
            
            $points->push($point);
        }

        $this->command->info('Created sample points');
        return $points;
    }

    /**
     * Create sample assigners
     */
    private function createSampleAssigners()
    {
        $assigners = collect();
        
        $sampleUsers = [
            ['first_name' => 'Admin', 'last_name' => 'User', 'email' => 'admin.assigner@karibuparcels.com'],
            ['first_name' => 'Manager', 'last_name' => 'Transport', 'email' => 'manager.transport@karibuparcels.com'],
            ['first_name' => 'Supervisor', 'last_name' => 'Pickup', 'email' => 'supervisor.pickup@karibuparcels.com'],
        ];

        foreach ($sampleUsers as $sample) {
            $user = User::factory()
                ->admin()
                ->active()
                ->create([
                    'first_name' => $sample['first_name'],
                    'last_name' => $sample['last_name'],
                    'email' => $sample['email'],
                    'phone_number' => $this->generateKenyanPhone(),
                    'password' => bcrypt('password123'),
                    'user_type' => 'admin',
                    'email_verified_at' => now(),
                ]);
            
            $assigners->push($user);
        }

        $this->command->info('Created sample assigners');
        return $assigners;
    }

    /**
     * Generate a Kenyan phone number
     */
    private function generateKenyanPhone(): string
    {
        $prefixes = ['0700', '0722', '0733', '0740', '0757', '0768', '0777', '0780', '0792', '0110'];
        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $suffix;
    }

    /**
     * Create the specific warehouse assignment
     */
    private function createWarehouseAssignment(): void
    {
        // Try to find existing warehouse point and PHA
        $warehousePoint = PickUpAndDropOffPoint::where('code', 'WH-001')->first();
        $warehousePHA = ParcelHandlingAssistant::where('email', 'warehouse.pha@karibuparcels.com')->first();
        $admin = User::where('email', 'admin@karibuparcels.com')->first();

        // If not found, try to find alternatives
        if (!$warehousePoint) {
            $warehousePoint = PickUpAndDropOffPoint::where('name', 'like', '%Warehouse%')->first()
                ?? PickUpAndDropOffPoint::first();
        }

        if (!$warehousePHA) {
            $warehousePHA = ParcelHandlingAssistant::where('role', 'Supervisor')->first()
                ?? ParcelHandlingAssistant::first();
        }

        if (!$admin) {
            $admin = User::where('user_type', 'admin')->first()
                ?? User::first();
        }

        if ($warehousePoint && $warehousePHA && $admin) {
            // Check if assignment already exists
            $existing = ParcelHandlingAssistantPickUpAndDropOffPointAssignment
                ::where('parcel_handling_assistant_id', $warehousePHA->id)
                ->where('pick_up_and_drop_off_point_id', $warehousePoint->id)
                ->where('status', 'active')
                ->first();

            if (!$existing) {
                try {
                    ParcelHandlingAssistantPickUpAndDropOffPointAssignment::factory()
                        ->active()
                        ->forPHA($warehousePHA)
                        ->forPoint($warehousePoint)
                        ->assignedBy($admin)
                        ->create([
                            'notes' => 'Warehouse assignment - Central Sorting Facility',
                        ]);

                    $this->command->info('Created warehouse assignment');
                } catch (\Exception $e) {
                    $this->command->warn('Could not create warehouse assignment: ' . $e->getMessage());
                }
            }
        } else {
            $this->command->warn('Could not find warehouse point, PHA, or admin for warehouse assignment');
        }
    }
}