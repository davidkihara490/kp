<?php

namespace Database\Seeders;

use App\Models\Parcel;
use App\Models\ParcelStatus;
use App\Models\ParcelPickUp;
use App\Models\User;
use App\Models\Driver;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use Illuminate\Database\Seeder;

class ParcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing towns from database
        $towns = Town::all();
        
        if ($towns->isEmpty()) {
            $this->command->error('No towns found in database. Please seed towns first.');
            return;
        }

        // Get existing points from database
        $points = PickUpAndDropOffPoint::where('status', 'active')->get();
        
        if ($points->isEmpty()) {
            $this->command->error('No pickup points found in database. Please seed points first.');
            return;
        }

        // Get existing partners
        $transportPartners = Partner::where('partner_type', 'transport')->get();
        $pickupPartners = Partner::where('partner_type', 'pickup-dropoff')->get();
        
        if ($transportPartners->isEmpty() || $pickupPartners->isEmpty()) {
            $this->command->error('No transport or pickup partners found in database.');
            return;
        }

        // Get existing drivers
        $drivers = Driver::where('status', 'active')->get();
        
        if ($drivers->isEmpty()) {
            $this->command->error('No active drivers found in database.');
            return;
        }

        // Get existing users (for customers)
        $customers = User::where('user_type', 'customer')->get();
        if ($customers->isEmpty()) {
            $customers = User::whereIn('user_type', ['driver', 'pha', 'transport', 'pickup-dropoff'])->get();
        }

        // Get warehouse point if exists
        $warehousePoint = $points->firstWhere('code', 'WH-001');
        if (!$warehousePoint) {
            $warehousePoint = $points->first();
        }

        // Get specific points by location if possible
        $nairobiPoints = $points->filter(function ($point) {
            return $point->town && str_contains(strtolower($point->town->name), 'nairobi');
        });
        
        $mombasaPoints = $points->filter(function ($point) {
            return $point->town && str_contains(strtolower($point->town->name), 'mombasa');
        });
        
        $kisumuPoints = $points->filter(function ($point) {
            return $point->town && str_contains(strtolower($point->town->name), 'kisumu');
        });

        // Get Nairobi and Mombasa towns
        $nairobiTown = $towns->firstWhere('name', 'Nairobi');
        $mombasaTown = $towns->firstWhere('name', 'Mombasa');
        $kisumuTown = $towns->firstWhere('name', 'Kisumu');
        $nakuruTown = $towns->firstWhere('name', 'Nakuru');
        $eldoretTown = $towns->firstWhere('name', 'Eldoret');

        $this->command->info('Creating parcels using existing towns and points...');

        // 1. Pending parcels
        $this->command->info('Creating pending parcels...');
        for ($i = 0; $i < 5; $i++) {
            $senderPoint = $nairobiPoints->isNotEmpty() ? $nairobiPoints->random() : $points->random();
            $receiverPoint = $mombasaPoints->isNotEmpty() ? $mombasaPoints->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->pending()
                ->create([
                    'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create initial status
            ParcelStatus::factory()
                ->initial()
                ->forParcel($parcel)
                ->create();
        }

        // 2. Accepted parcels
        $this->command->info('Creating accepted parcels...');
        for ($i = 0; $i < 4; $i++) {
            $senderPoint = $nairobiPoints->isNotEmpty() ? $nairobiPoints->random() : $points->random();
            $receiverPoint = $kisumuPoints->isNotEmpty() ? $kisumuPoints->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->accepted()
                ->create([
                    'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $kisumuTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create([
                'changed_by_type' => 'partner',
                'notes' => 'Parcel accepted for processing',
            ]);
        }

        // 3. Assigned parcels (to drivers)
        $this->command->info('Creating assigned parcels...');
        for ($i = 0; $i < 6; $i++) {
            $driver = $drivers->random();
            $senderPoint = $nairobiPoints->isNotEmpty() ? $nairobiPoints->random() : $points->random();
            $receiverPoint = $nakuruTown ? $points->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->assigned()
                ->create([
                    'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $nakuruTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'driver_id' => $driver->id,
                    'transport_partner_id' => $driver->partner_id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('assigned')
                ->byDriver($driver)
                ->forParcel($parcel)
                ->create([
                    'notes' => 'Assigned to driver ' . $driver->full_name,
                ]);
        }

        // 4. In transit parcels
        $this->command->info('Creating in-transit parcels...');
        for ($i = 0; $i < 8; $i++) {
            $driver = $drivers->random();
            $senderPoint = $mombasaPoints->isNotEmpty() ? $mombasaPoints->random() : $points->random();
            $receiverPoint = $nairobiPoints->isNotEmpty() ? $nairobiPoints->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->inTransit()
                ->create([
                    'sender_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'driver_id' => $driver->id,
                    'transport_partner_id' => $driver->partner_id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('assigned')->byDriver($driver)->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('in_transit')
                ->byDriver($driver)
                ->forParcel($parcel)
                ->create([
                    'notes' => 'Parcel picked up and in transit',
                ]);
        }

        // 5. At warehouse parcels
        $this->command->info('Creating warehouse parcels...');
        for ($i = 0; $i < 5; $i++) {
            $driver = $drivers->random();
            $senderPoint = $kisumuPoints->isNotEmpty() ? $kisumuPoints->random() : $points->random();
            $receiverPoint = $eldoretTown ? $points->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->atWarehouse()
                ->create([
                    'sender_town_id' => $kisumuTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $eldoretTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'driver_id' => $driver->id,
                    'transport_partner_id' => $driver->partner_id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('assigned')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('in_transit')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('warehouse')
                ->forParcel($parcel)
                ->create([
                    'notes' => 'Arrived at sorting warehouse',
                ]);
        }

        // 6. Delivered parcels (with pickup records)
        $this->command->info('Creating delivered parcels...');
        for ($i = 0; $i < 10; $i++) {
            $driver = $drivers->random();
            $senderPoint = $nairobiPoints->isNotEmpty() ? $nairobiPoints->random() : $points->random();
            $receiverPoint = $mombasaPoints->isNotEmpty() ? $mombasaPoints->random() : $points->random();
            
            $parcel = Parcel::factory()
                ->delivered()
                ->paid()
                ->create([
                    'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'driver_id' => $driver->id,
                    'transport_partner_id' => $driver->partner_id,
                    'customer_id' => $customers->isNotEmpty() ? $customers->random()->id : null,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('assigned')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('in_transit')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('arrived_at_destination')->forParcel($parcel)->create();
            
            // Delivery status with OTP
            $otp = rand(100000, 999999);
            ParcelStatus::factory()->delivery()
                ->forParcel($parcel)
                ->create([
                    'otp' => $otp,
                    'notes' => 'Parcel delivered successfully',
                ]);

            // Create pickup record (someone picked it up)
            ParcelPickUp::factory()
                ->byOwner()
                ->verified()
                ->forParcel($parcel)
                ->create([
                    'pickup_person_name' => $parcel->receiver_name,
                    'pickup_person_phone' => $parcel->receiver_phone,
                ]);
        }

        // 7. Failed delivery parcels
        $this->command->info('Creating failed delivery parcels...');
        for ($i = 0; $i < 3; $i++) {
            $driver = $drivers->random();
            $senderPoint = $points->random();
            $receiverPoint = $points->random();
            
            $parcel = Parcel::factory()
                ->create([
                    'current_status' => 'failed',
                    'sender_town_id' => $towns->random()->id,
                    'receiver_town_id' => $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $senderPoint->id,
                    'delivery_pick_up_drop_off_point_id' => $receiverPoint->id,
                    'sender_partner_id' => $senderPoint->partner_id ?? $pickupPartners->random()->id,
                    'delivery_partner_id' => $receiverPoint->partner_id ?? $pickupPartners->random()->id,
                    'driver_id' => $driver->id,
                    'transport_partner_id' => $driver->partner_id,
                ]);

            // Create status history
            ParcelStatus::factory()->initial()->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('assigned')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('in_transit')->forParcel($parcel)->create();
            ParcelStatus::factory()->withStatus('arrived_at_destination')->forParcel($parcel)->create();
            ParcelStatus::factory()->failed()->forParcel($parcel)->create();
        }

        // 8. Fragile/valuable parcels
        $this->command->info('Creating special handling parcels...');
        
        // Fragile parcel
        $fragileParcel = Parcel::factory()
            ->fragile()
            ->insured()
            ->create([
                'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                'receiver_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                'sender_pick_up_drop_off_point_id' => $points->random()->id,
                'delivery_pick_up_drop_off_point_id' => $points->random()->id,
                'content_description' => 'Glassware - 6 wine glasses',
                'special_instructions' => 'Fragile - Handle with extreme care',
            ]);
        
        ParcelStatus::factory()->initial()->forParcel($fragileParcel)->create();
        ParcelStatus::factory()->withStatus('accepted')->forParcel($fragileParcel)->create([
            'notes' => 'Fragile item - special handling required',
        ]);

        // Valuable parcel
        $valuableParcel = Parcel::factory()
            ->valuable()
            ->create([
                'sender_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                'receiver_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                'sender_pick_up_drop_off_point_id' => $points->random()->id,
                'delivery_pick_up_drop_off_point_id' => $points->random()->id,
                'content_description' => 'Laptop and electronics',
                'declared_value' => 85000,
            ]);
        
        ParcelStatus::factory()->initial()->forParcel($valuableParcel)->create();
        ParcelStatus::factory()->withStatus('accepted')->forParcel($valuableParcel)->create([
            'notes' => 'High value item - insurance verified',
        ]);

        // Perishable parcel
        $perishableParcel = Parcel::factory()
            ->perishable()
            ->create([
                'sender_town_id' => $nakuruTown?->id ?? $towns->random()->id,
                'receiver_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                'sender_pick_up_drop_off_point_id' => $points->random()->id,
                'delivery_pick_up_drop_off_point_id' => $points->random()->id,
                'content_description' => 'Fresh farm produce - vegetables',
                'special_instructions' => 'Perishable - deliver within 24 hours',
            ]);
        
        ParcelStatus::factory()->initial()->forParcel($perishableParcel)->create();
        ParcelStatus::factory()->withStatus('accepted')->forParcel($perishableParcel)->create([
            'notes' => 'Perishable goods - priority handling',
        ]);

        // 9. Create a test parcel for the test driver
        $testDriver = Driver::where('email', 'driver_test@karibuparcels.com')->first();
        if ($testDriver) {
            $testParcel = Parcel::factory()
                ->forDriver($testDriver)
                ->create([
                    'parcel_id' => 'KP' . date('Ymd') . 'TEST01',
                    'sender_town_id' => $nairobiTown?->id ?? $towns->random()->id,
                    'receiver_town_id' => $mombasaTown?->id ?? $towns->random()->id,
                    'sender_pick_up_drop_off_point_id' => $points->random()->id,
                    'delivery_pick_up_drop_off_point_id' => $points->random()->id,
                    'sender_name' => 'Test Sender',
                    'sender_phone' => '0712345678',
                    'receiver_name' => 'Test Receiver',
                    'receiver_phone' => '0723456789',
                    'content_description' => 'Test parcel for driver',
                ]);

            ParcelStatus::factory()->initial()->forParcel($testParcel)->create();
            ParcelStatus::factory()->withStatus('accepted')->forParcel($testParcel)->create();
            ParcelStatus::factory()->withStatus('assigned')
                ->byDriver($testDriver)
                ->forParcel($testParcel)
                ->create();
        }

        // 10. Create parcels for warehouse PHA
        $warehousePHA = \App\Models\ParcelHandlingAssistant::where('email', 'warehouse.pha@karibuparcels.com')->first();
        if ($warehousePHA && $warehousePoint) {
            for ($i = 0; $i < 3; $i++) {
                $parcel = Parcel::factory()
                    ->atWarehouse()
                    ->create([
                        'pha_id' => $warehousePHA->id,
                        'sender_pick_up_drop_off_point_id' => $warehousePoint->id,
                        'sender_partner_id' => $warehousePoint->partner_id,
                        'sender_town_id' => $warehousePoint->town_id ?? $towns->random()->id,
                        'receiver_town_id' => $towns->random()->id,
                    ]);

                ParcelStatus::factory()->initial()->forParcel($parcel)->create();
                ParcelStatus::factory()->withStatus('accepted')->forParcel($parcel)->create();
                ParcelStatus::factory()->withStatus('warehouse')
                    ->forParcel($parcel)
                    ->create([
                        'notes' => 'Arrived at central warehouse',
                    ]);
            }
        }

        $this->command->info('Parcels, statuses, and pickups seeded successfully using existing data!');
    }
}