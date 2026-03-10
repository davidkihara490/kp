<?php

namespace Database\Factories;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partner>
 */
class PartnerFactory extends Factory
{
    public function definition(): array
    {
        $partnerTypes = ['transport', 'pickup-dropoff'];
        $fleetOwnerships = ['owned', 'subcontracted', 'both'];
        $onboardingSteps = ['pending', 'company_details', 'points_details', 'fleet_details', 'completed'];
        $verificationStatuses = ['pending', 'verified', 'rejected'];

        $kenyanCompanies = [
            'Express Connections',
            'Transporter Kenya',
            'Highway Logistics',
            'Coast Couriers',
            'Mountain Movers',
            'Savannah Shipping',
            'Rift Valley Transport',
            'Lake Region Express',
            'Capital City Couriers',
            'Metropolitan Movers',
            'Nairobi Express',
            'Mombasa Cargo',
            'Kisumu Logistics',
            'Eldoret Transporters',
            'Nakuru Hauliers',
            'Machakos Movers'
        ];

        $storageFacilities = ['Warehouse', 'Container Yard', 'Open Yard', 'Cold Storage', 'Godown', 'Storage Shed'];
        $securityMeasures = ['24/7 Security Guards', 'CCTV Surveillance', 'Electric Fence', 'Security Dogs', 'Armed Response', 'Biometric Access'];
        $trackingSystems = ['GPS Tracking', 'Fleet Management System', 'Manual Logbook', 'Mobile App', 'Web Portal'];

        return [
            'partner_type' => fake()->randomElement($partnerTypes),
            'owner_id' => User::factory(),
            'incharge_id' => null,

            // Company Details - Kenyan Context
            'company_name' => fake()->randomElement($kenyanCompanies) . ' ' . fake()->year(),
            'registration_number' => $this->generateKenyanBusinessReg(),
            'registration_certificate_path' => fake()->optional(0.5)->filePath(),
            'kra_pin' => $this->generateKenyanKraPin(),
            'pin_certificate_path' => fake()->optional(0.5)->filePath(),

            // Station / Points Details
            'points_count' => fake()->numberBetween(1, 20),
            'points_have_phone' => fake()->boolean(80),
            'points_have_computer' => fake()->boolean(70),
            'points_have_internet' => fake()->boolean(60),
            'officers_knowledgeable' => fake()->boolean(75),
            'points_compliant' => fake()->boolean(85),
            'compliance_certificate_path' => fake()->optional(0.6)->filePath(),

            // Operational Details - Kenyan Context
            'operating_hours' => fake()->optional(0.9)->randomElement([
                'Monday - Friday: 8:00 AM - 5:00 PM, Saturday: 9:00 AM - 1:00 PM',
                'Monday - Saturday: 7:00 AM - 7:00 PM',
                '24/7 - 7 Days a Week',
                'Monday - Friday: 8:00 AM - 8:00 PM, Saturday: 8:00 AM - 3:00 PM',
                'Monday - Sunday: 6:00 AM - 10:00 PM'
            ]),
            'maximum_capacity_per_day' => fake()->optional(0.7)->numberBetween(50, 5000),
            'storage_facility_type' => fake()->optional(0.6)->randomElement($storageFacilities),
            'security_measures' => fake()->optional(0.5)->randomElement($securityMeasures),
            'insurance_coverage' => fake()->optional(0.6)->randomElement(['Basic', 'Comprehensive', 'Premium', 'Third Party Only']),
            'additional_notes' => fake()->optional(0.3)->sentence(),

            // Fleet Details
            'fleet_count' => fake()->numberBetween(1, 100),
            'fleet_ownership' => fake()->randomElement($fleetOwnerships),
            'fleet_insured' => fake()->boolean(80),
            'insurance_certificate_path' => fake()->optional(0.7)->filePath(),
            'fleets_compliant' => fake()->boolean(85),
            'driver_count' => fake()->numberBetween(1, 150),
            'drivers_compliant' => fake()->boolean(80),
            'drivers_certificate_path' => fake()->optional(0.6)->filePath(),

            // Fleet Types - Kenyan Common Vehicles
            'has_motorcycles' => fake()->boolean(70),
            'has_vans' => fake()->boolean(80),
            'has_trucks' => fake()->boolean(60),
            'has_refrigerated' => fake()->boolean(30),
            'other_fleet_types' => fake()->optional(0.2)->randomElement([
                'Pickups',
                'Mini-buses',
                'Tuk Tuks',
                'Bicycles',
                'Trailers',
                'Long wheelbase'
            ]),

            // Office & Booking Details
            'has_computer' => fake()->boolean(90),
            'has_internet' => fake()->boolean(85),
            'booking_emails' => fake()->optional(0.8)->randomElement([
                ['bookings@' . strtolower(str_replace(' ', '', fake()->company())) . '.co.ke'],
                [
                    'info@' . strtolower(str_replace(' ', '', fake()->company())) . '.co.ke',
                    'bookings@' . strtolower(str_replace(' ', '', fake()->company())) . '.co.ke'
                ],
            ]),
            'has_dedicated_allocator' => fake()->boolean(70),
            'allocator_name' => fake()->optional(0.7)->name(),
            'allocator_phone' => fake()->optional(0.7)->phoneNumber(),

            // Capacity & Coverage - Kenyan Context
            'maximum_daily_capacity' => fake()->optional(0.8)->numberBetween(100, 10000),
            'maximum_distance' => fake()->optional(0.7)->numberBetween(50, 1000),
            'can_handle_fragile' => fake()->boolean(60),
            'can_handle_perishable' => fake()->boolean(70),
            'can_handle_valuables' => fake()->boolean(80),

            // Experience & Systems
            'years_in_operation' => fake()->numberBetween(1, 40),
            'previous_courier_experience' => fake()->optional(0.5)->randomElement([
                'Worked with G4S Courier for 5 years',
                'Former partner with Wells Fargo',
                'Experience with Aramex Kenya',
                'Previously contracted with DHL Kenya',
                'Worked with Kenyatta National Hospital deliveries'
            ]),
            'insurance_coverage_amount' => fake()->optional(0.6)->randomElement([100000, 250000, 500000, 1000000, 2000000, 5000000]),
            'safety_measures' => fake()->optional(0.6)->randomElement([
                'Fire extinguishers in all vehicles',
                'First aid kits available',
                'Drivers trained in defensive driving',
                'Regular vehicle maintenance',
                'Safety harnesses for all cargo'
            ]),
            'tracking_system' => fake()->optional(0.5)->randomElement($trackingSystems),

            // System Fields
            'onboarding_step' => fake()->randomElement($onboardingSteps),
            'verification_status' => fake()->randomElement($verificationStatuses),
        ];
    }

    private function generateKenyanBusinessReg(): string
    {
        $prefixes = ['C', 'CP', 'BN', 'PVT', 'LTD', 'PLC'];
        $prefix = fake()->randomElement($prefixes);
        $number = fake()->numberBetween(10000, 999999);
        return $prefix . '/' . $number . '/' . fake()->randomElement(['2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023']);
    }

    private function generateKenyanKraPin(): string
    {
        $letters = range('A', 'Z');
        $firstChar = fake()->randomElement($letters);
        $numbers = fake()->numberBetween(10000000, 99999999);
        $lastChar = fake()->randomElement($letters);
        return $firstChar . $numbers . $lastChar;
    }

    public function transport(): static
    {
        return $this->state(fn(array $attributes) => [
            'partner_type' => 'transport',
        ]);
    }

    public function pickupDropoff(): static
    {
        return $this->state(fn(array $attributes) => [
            'partner_type' => 'pickup-dropoff',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'verification_status' => 'verified',
            'onboarding_step' => 'completed',
        ]);
    }

    public function withOwner(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'owner_id' => $user->id,
        ]);
    }
}
