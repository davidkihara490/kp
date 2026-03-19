<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Partner;
use App\Models\Driver;
use App\Models\ParcelHandlingAssistant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::factory()->admin()->active()->create([
            'first_name' => 'Super',
            'second_name' => 'System',
            'last_name' => 'Administrator',
            'user_name' => 'superadmin',
            'email' => 'karibuparcels@gmail.com',
            'phone_number' => '+254700000001',
            'password' => Hash::make('admin123KP'),
            'user_type' => 'admin',
            'terms_and_conditions' => true,
            'privacy_policy' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Super Admin created: karibuparcels@gmail.com / admin123KP');

        // Create Transport Partner User
        $transportPartnerUser = User::factory()->transportPartner()->active()->create([
            'first_name' => 'James',
            'second_name' => 'Kipchoge',
            'last_name' => 'Kiprotich',
            'user_name' => 'james.kiprotich',
            'email' => 'transportpartner_test@karibuparcels.com',
            'phone_number' => '+254722111111',
            'password' => Hash::make('test1234KP'),
            'terms_and_conditions' => true,
            'privacy_policy' => true,
            'email_verified_at' => now(),
        ]);

        // Create Partner record for Transport Partner
        $transportPartner = Partner::factory()->transport()->verified()->create([
            'owner_id' => $transportPartnerUser->id,
            'company_name' => 'Express Transport Solutions Ltd',
            'registration_number' => 'C.123456/2020',
            'kra_pin' => 'P051234567A',
            'fleet_count' => 25,
            'driver_count' => 35,
            'has_motorcycles' => true,
            'has_vans' => true,
            'has_trucks' => true,
            'fleet_ownership' => 'both',
            'operating_hours' => 'Monday - Saturday: 6:00 AM - 8:00 PM',
            'maximum_daily_capacity' => 2500,
            'insurance_coverage' => 'Comprehensive',
            'insurance_coverage_amount' => 5000000,
            'can_handle_fragile' => true,
            'can_handle_perishable' => true,
            'can_handle_valuables' => true,
            'years_in_operation' => 12,
            'tracking_system' => 'GPS Tracking',
            'booking_emails' => ['bookings@expresstransport.co.ke', 'info@expresstransport.co.ke'],
        ]);

        $this->command->info('Transport Partner created: transportpartner_test@karibuparcels.com / test1234KP');

        // Create Pickup and Dropoff Partner User
        $pickupDropoffUser = User::factory()->pickupDropoffPartner()->active()->create([
            'first_name' => 'Mary',
            'second_name' => 'Wanjiku',
            'last_name' => 'Njoroge',
            'user_name' => 'mary.njoroge',
            'email' => 'pickup_dropoff_partner_test@karibuparcels.com',
            'phone_number' => '+254733222222',
            'password' => Hash::make('test1234KP'),
            'terms_and_conditions' => true,
            'privacy_policy' => true,
            'email_verified_at' => now(),
        ]);

        // Create Partner record for Pickup and Dropoff Partner
        $pickupDropoffPartner = Partner::factory()->pickupDropoff()->verified()->create([
            'owner_id' => $pickupDropoffUser->id,
            'company_name' => 'Nairobi Pickup & Dropoff Services',
            'registration_number' => 'PVT/987654/2021',
            'kra_pin' => 'P098765432B',
            'points_count' => 12,
            'points_have_phone' => true,
            'points_have_computer' => true,
            'points_have_internet' => true,
            'maximum_daily_capacity' => 850,
            'operating_hours' => 'Monday - Sunday: 7:00 AM - 9:00 PM',
            'storage_facility_type' => 'Warehouse',
            'security_measures' => '24/7 Security Guards, CCTV Surveillance',
            'has_computer' => true,
            'has_internet' => true,
            'booking_emails' => ['pickups@nairobipickup.co.ke'],
        ]);

        $this->command->info('Pickup & Dropoff Partner created: pickup_dropoff_partner_test@karibuparcels.com / test1234KP');

        // Create Parcel Handling Assistant User
        $phaUser = User::factory()->pha()->active()->create([
            'first_name' => 'David',
            'second_name' => 'Odhiambo',
            'last_name' => 'Omondi',
            'user_name' => 'david.omondi',
            'email' => 'pha_test@karibuparcels.com',
            'phone_number' => '+254744333333',
            'password' => Hash::make('test1234KP'),
            'terms_and_conditions' => true,
            'privacy_policy' => true,
            'email_verified_at' => now(),
        ]);

        // Create Parcel Handling Assistant record
        $pha = ParcelHandlingAssistant::factory()->active()->withRole('Supervisor')->create([
            'user_id' => $phaUser->id,
            'partner_id' => $pickupDropoffPartner->id,
            'first_name' => $phaUser->first_name,
            'second_name' => $phaUser->second_name,
            'last_name' => $phaUser->last_name,
            'email' => $phaUser->email,
            'phone_number' => $phaUser->phone_number,
            'id_number' => '28765432',
            'role' => 'Supervisor',
        ]);

        $this->command->info('Parcel Handling Assistant created: pha_test@karibuparcels.com / test1234KP');

        // Create Driver User
        $driverUser = User::factory()->driver()->active()->create([
            'first_name' => 'Peter',
            'second_name' => 'Kamau',
            'last_name' => 'Mwangi',
            'user_name' => 'peter.mwangi',
            'email' => 'driver_test@karibuparcels.com',
            'phone_number' => '+254755444444',
            'password' => Hash::make('test1234KP'),
            'terms_and_conditions' => true,
            'privacy_policy' => true,
            'email_verified_at' => now(),
        ]);

        // Create Driver record
        $driver = Driver::factory()->active()->available()->withValidLicense()->create([
            'user_id' => $driverUser->id,
            'partner_id' => $transportPartner->id,
            'first_name' => $driverUser->first_name,
            'second_name' => $driverUser->second_name,
            'last_name' => $driverUser->last_name,
            'email' => $driverUser->email,
            'phone_number' => $driverUser->phone_number,
            'id_number' => '31234567',
            'driving_license_number' => 'B12345678',
            'license_class' => 'B, C, CE',
            'driving_license_issue_date' => '2020-03-15',
            'driving_license_expiry_date' => '2025-03-14',
            'emergency_contact_name' => 'Jane Mwangi',
            'emergency_contact_phone' => '+254722555555',
            'emergency_contact_relationship' => 'Spouse',
            'bank_name' => 'Equity Bank',
            'bank_account_number' => '1234567890',
            'bank_account_name' => 'Peter Kamau Mwangi',
        ]);

        $this->command->info('Driver created: driver_test@karibuparcels.com / test1234KP');

        // Create additional Kenyan test data for development
        if (app()->environment('local', 'development')) {
            // Create additional Kenyan counties as service areas
            $kenyanTowns = [
                'Nairobi',
                'Mombasa',
                'Kisumu',
                'Nakuru',
                'Eldoret',
                'Thika',
                'Machakos',
                'Kiambu',
                'Kikuyu',
                'Ruiru',
                'Juja',
                'Limuru',
                'Kitengela',
                'Athi River',
                'Kitui',
                'Embu',
                'Meru',
                'Nyeri',
                'Muranga',
                'Kerugoya',
                'Karatina',
                'Naivasha',
                'Gilgil',
                'Nyandarua',
                'Ol Kalou',
                'Narok',
                'Bomet',
                'Kericho',
                'Kisii',
                'Migori',
                'Homa Bay',
                'Siaya',
                'Busia',
                'Bungoma',
                'Kakamega',
                'Vihiga',
                'Trans Nzoia',
                'Uasin Gishu',
                'Nandi',
                'Laikipia',
                'Samburu',
                'Isiolo',
                'Marsabit',
                'Garissa',
                'Wajir',
                'Mandera',
                'Lamu',
                'Tana River',
                'Kilifi',
                'Kwale',
                'Taita Taveta',
                'Makueni',
                'Kajiado'
            ];

            // Create additional transport partners with drivers
            User::factory()
                ->count(5)
                ->transportPartner()
                ->active()
                ->create()
                ->each(function ($user) use ($kenyanTowns) {
                    $partner = Partner::factory()
                        ->transport()
                        ->verified()
                        ->withOwner($user)
                        ->create([
                            'company_name' => fake()->randomElement([
                                'Highway Express',
                                'Coast Hauliers',
                                'Rift Valley Transport',
                                'Mountain Logistics',
                                'Savannah Couriers',
                                'Lake Region Movers'
                            ]) . ' ' . fake()->year(),
                            'operating_hours' => 'Monday - Friday: 8:00 AM - 6:00 PM, Saturday: 9:00 AM - 1:00 PM',
                        ]);

                    // Create 5-10 drivers for each transport partner
                    Driver::factory()
                        ->count(rand(5, 10))
                        ->active()
                        ->available()
                        ->withValidLicense()
                        ->withPartner($partner)
                        ->create()
                        ->each(function ($driver) use ($partner) {
                            $driverUser = User::factory()->driver()->active()->create([
                                'first_name' => $driver->first_name,
                                'second_name' => $driver->second_name,
                                'last_name' => $driver->last_name,
                                'email' => $driver->email,
                                'phone_number' => $driver->phone_number,
                            ]);
                            $driver->update(['user_id' => $driverUser->id]);
                        });
                });

            // Create additional pickup-dropoff partners with PHAs
            User::factory()
                ->count(10)
                ->pickupDropoffPartner()
                ->active()
                ->create()
                ->each(function ($user) use ($kenyanTowns) {
                    $partner = Partner::factory()
                        ->pickupDropoff()
                        ->verified()
                        ->withOwner($user)
                        ->create([
                            'company_name' => fake()->randomElement([
                                'City Pickup Services',
                                'Town Dropoff Express',
                                'Neighborhood Couriers',
                                'Estate Logistics',
                                'Local Parcel Hub',
                                'Community Deliveries'
                            ]) . ' ' . fake()->year(),
                            'points_count' => rand(3, 10),
                        ]);

                    // Create 3-6 PHAs for each pickup-dropoff partner
                    ParcelHandlingAssistant::factory()
                        ->count(rand(3, 6))
                        ->active()
                        ->withPartner($partner)
                        ->create()
                        ->each(function ($pha) use ($partner) {
                            $phaUser = User::factory()->pha()->active()->create([
                                'first_name' => $pha->first_name,
                                'second_name' => $pha->second_name,
                                'last_name' => $pha->last_name,
                                'email' => $pha->email,
                                'phone_number' => $pha->phone_number,
                            ]);
                            $pha->update(['user_id' => $phaUser->id]);
                        });
                });

            $this->command->info('Additional Kenyan test data created successfully!');
        }
    }
}
