<?php

namespace Database\Factories;

use App\Models\Fleet;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fleet>
 */
class FleetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kenyanVehicleMakes = [
            'Toyota', 'Nissan', 'Mitsubishi', 'Isuzu', 'Hino', 'Scania', 'Mercedes-Benz',
            'Volvo', 'MAN', 'Ford', 'Mazda', 'Subaru', 'Volkswagen', 'Suzuki',
            'Probox', 'Fuso', 'Iveco', 'DAF', 'Renault'
        ];

        $truckModels = [
            'Fuso Fighter', 'Isuzu FRR', 'Hino 300', 'Scania R-series', 'Volvo FH',
            'Mercedes-Benz Actros', 'MAN TGX', 'DAF CF', 'Iveco Stralis', 'Fuso Canter'
        ];

        $vanModels = [
            'Toyota Hiace', 'Nissan Urvan', 'Mitsubishi L300', 'Ford Transit',
            'Volkswagen Crafter', 'Mercedes-Benz Sprinter', 'Isuzu N-series'
        ];

        $pickupModels = [
            'Toyota Hilux', 'Isuzu D-Max', 'Ford Ranger', 'Mitsubishi L200',
            'Nissan Navara', 'Mazda BT-50', 'Volkswagen Amarok'
        ];

        $motorcycleModels = [
            'Boxer BM150', 'TVS King', 'Bajaj Boxer', 'Honda CB150',
            'Yamaha YBR', 'Hero Splendor', 'Suzuki GS150'
        ];

        $vehicleTypes = ['motorcycle', 'pickup', 'van', 'truck', 'refrigerated_truck'];
        $selectedType = fake()->randomElement($vehicleTypes);
        
        switch ($selectedType) {
            case 'motorcycle':
                $make = fake()->randomElement(['Boxer', 'TVS', 'Bajaj', 'Honda', 'Yamaha', 'Hero', 'Suzuki']);
                $model = fake()->randomElement($motorcycleModels);
                $capacity = fake()->randomFloat(2, 50, 200); // 50-200 kg
                break;
            case 'pickup':
                $make = fake()->randomElement(['Toyota', 'Isuzu', 'Ford', 'Mitsubishi', 'Nissan', 'Mazda', 'Volkswagen']);
                $model = fake()->randomElement($pickupModels);
                $capacity = fake()->randomFloat(2, 500, 1500); // 500-1500 kg
                break;
            case 'van':
                $make = fake()->randomElement(['Toyota', 'Nissan', 'Mitsubishi', 'Ford', 'Volkswagen', 'Mercedes-Benz', 'Isuzu']);
                $model = fake()->randomElement($vanModels);
                $capacity = fake()->randomFloat(2, 800, 3000); // 800-3000 kg
                break;
            case 'refrigerated_truck':
                $make = fake()->randomElement(['Isuzu', 'Hino', 'Mitsubishi', 'Scania', 'Mercedes-Benz']);
                $model = fake()->randomElement(['Fuso Fighter', 'Isuzu FRR', 'Hino 300']) . ' (Refrigerated)';
                $capacity = fake()->randomFloat(2, 3000, 10000); // 3-10 tons
                break;
            case 'truck':
            default:
                $make = fake()->randomElement(['Isuzu', 'Hino', 'Mitsubishi', 'Scania', 'Mercedes-Benz', 'Volvo', 'MAN']);
                $model = fake()->randomElement($truckModels);
                $capacity = fake()->randomFloat(2, 3000, 30000); // 3-30 tons
                break;
        }

        $kenyanColors = [
            'White', 'Blue', 'Red', 'Silver', 'Black', 'Green', 'Yellow', 'Orange',
            'Grey', 'Brown', 'Purple', 'Gold', 'Bronze', 'Beige', 'Maroon'
        ];

        $statuses = ['active', 'maintenance', 'inactive', 'accident'];
        $fuelTypes = ['petrol', 'diesel', 'electric', 'hybrid'];
        
        // Generate Kenyan license plate
        $registrationNumber = $this->generateKenyanPlate();
        
        $registrationExpiry = fake()->optional(0.8)->dateTimeBetween('-1 year', '+2 years');
        $insuranceExpiry = fake()->optional(0.8)->dateTimeBetween('-1 month', '+1 year');
        $lastServiceDate = fake()->optional(0.7)->dateTimeBetween('-6 months', 'now');
        $nextServiceDate = $lastServiceDate ? 
            fake()->dateTimeBetween($lastServiceDate, '+6 months') : 
            fake()->optional(0.5)->dateTimeBetween('now', '+3 months');

        return [
            'partner_id' => Partner::factory(),
            'registration_number' => $registrationNumber,
            'make' => $make,
            'model' => $model,
            'vehicle_type' => $selectedType,
            'color' => fake()->randomElement($kenyanColors),
            'status' => fake()->randomElement($statuses),
            'is_available' => fake()->boolean(70),
            'registration_expiry' => $registrationExpiry,
            'insurance_expiry' => $insuranceExpiry,
            'last_service_date' => $lastServiceDate,
            'next_service_date' => $nextServiceDate,
            'year' => fake()->numberBetween(2015, 2024),
            'capacity' => $capacity,
            'fuel_type' => $selectedType === 'motorcycle' ? 'petrol' : fake()->randomElement(['diesel', 'petrol']),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
/**
 * Generate a Kenyan vehicle registration plate
 */
private function generateKenyanPlate(): string
{
    static $usedPlates = [];
    
    $patterns = [
        // Modern format: KAA 001A
        function() {
            $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            $first = 'K';
            $second = fake()->randomElement(str_split($letters));
            $third = fake()->randomElement(str_split($letters));
            $number = str_pad(fake()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
            $last = fake()->randomElement(str_split($letters));
            return $first . $second . $third . ' ' . $number . $last;
        },
        
        // Older format: KAA 001
        function() {
            $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
            $first = 'K';
            $second = fake()->randomElement(str_split($letters));
            $third = fake()->randomElement(str_split($letters));
            $number = str_pad(fake()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
            return $first . $second . $third . ' ' . $number;
        },
        
        // Motorcycle format: KMCA 001
        function() {
            $prefix = 'K' . fake()->randomElement(['M', 'C']) . fake()->randomElement(['A', 'B', 'C', 'D', 'E']);
            $number = fake()->numberBetween(1, 999);
            return $prefix . ' ' . str_pad($number, 3, '0', STR_PAD_LEFT);
        },
        
        // Trailer format: Z 0001
        function() {
            $prefix = 'Z';
            $number = fake()->numberBetween(1, 9999);
            return $prefix . ' ' . str_pad($number, 4, '0', STR_PAD_LEFT);
        }
    ];

    // Try to generate a unique plate
    $attempts = 0;
    $maxAttempts = 100;
    
    do {
        $plate = fake()->randomElement($patterns)();
        $attempts++;
        
        // Remove special plates (CD/D) as they're rare
        if ($plate === null) {
            continue;
        }
        
        // Check if plate is already used
        if (!in_array($plate, $usedPlates)) {
            $usedPlates[] = $plate;
            return $plate;
        }
        
    } while ($attempts < $maxAttempts);
    
    // Fallback with timestamp to ensure uniqueness
    $fallback = 'KXX ' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT) . 'X';
    $usedPlates[] = $fallback;
    return $fallback;
}

    /**
     * Indicate that the fleet is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the fleet is available.
     */
    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => true,
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the fleet is under maintenance.
     */
    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
            'is_available' => false,
        ]);
    }

    /**
     * Indicate that the fleet is a motorcycle.
     */
    public function motorcycle(): static
    {
        return $this->state(fn (array $attributes) => [
            'vehicle_type' => 'motorcycle',
            'make' => fake()->randomElement(['Boxer', 'TVS', 'Bajaj', 'Honda', 'Yamaha']),
            'model' => fake()->randomElement(['Boxer BM150', 'TVS King', 'Bajaj Boxer', 'Honda CB150', 'Yamaha YBR']),
            'capacity' => fake()->randomFloat(2, 50, 150),
            'fuel_type' => 'petrol',
        ]);
    }

    /**
     * Indicate that the fleet is a pickup.
     */
    public function pickup(): static
    {
        return $this->state(fn (array $attributes) => [
            'vehicle_type' => 'pickup',
            'make' => fake()->randomElement(['Toyota', 'Isuzu', 'Ford', 'Mitsubishi']),
            'model' => fake()->randomElement(['Toyota Hilux', 'Isuzu D-Max', 'Ford Ranger', 'Mitsubishi L200']),
            'capacity' => fake()->randomFloat(2, 800, 1200),
            'fuel_type' => 'diesel',
        ]);
    }

    /**
     * Indicate that the fleet is a van.
     */
    public function van(): static
    {
        return $this->state(fn (array $attributes) => [
            'vehicle_type' => 'van',
            'make' => fake()->randomElement(['Toyota', 'Nissan', 'Mitsubishi', 'Ford']),
            'model' => fake()->randomElement(['Toyota Hiace', 'Nissan Urvan', 'Mitsubishi L300', 'Ford Transit']),
            'capacity' => fake()->randomFloat(2, 1000, 2500),
            'fuel_type' => 'diesel',
        ]);
    }

    /**
     * Indicate that the fleet is a truck.
     */
    public function truck(): static
    {
        return $this->state(fn (array $attributes) => [
            'vehicle_type' => 'truck',
            'make' => fake()->randomElement(['Isuzu', 'Hino', 'Mitsubishi', 'Scania', 'Mercedes-Benz']),
            'model' => fake()->randomElement(['Isuzu FRR', 'Hino 300', 'Fuso Fighter', 'Scania R-series']),
            'capacity' => fake()->randomFloat(2, 5000, 30000),
            'fuel_type' => 'diesel',
        ]);
    }

    /**
     * Indicate that the fleet is a refrigerated truck.
     */
    public function refrigerated(): static
    {
        return $this->state(fn (array $attributes) => [
            'vehicle_type' => 'refrigerated_truck',
            'make' => fake()->randomElement(['Isuzu', 'Hino', 'Mitsubishi']),
            'model' => fake()->randomElement(['Isuzu FRR (Fridge)', 'Hino 300 (Reefer)', 'Fuso Fighter (Cold)']),
            'capacity' => fake()->randomFloat(2, 3000, 10000),
            'fuel_type' => 'diesel',
            'notes' => 'Refrigerated unit: ' . fake()->randomElement(['Thermo King', 'Carrier', 'Mitsubishi Heavy']) . ' ' . fake()->year(),
        ]);
    }

    /**
     * Indicate that the fleet has valid registration.
     */
    public function withValidRegistration(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_expiry' => fake()->dateTimeBetween('+1 month', '+2 years'),
        ]);
    }

    /**
     * Indicate that the fleet has expired registration.
     */
    public function withExpiredRegistration(): static
    {
        return $this->state(fn (array $attributes) => [
            'registration_expiry' => fake()->dateTimeBetween('-2 years', '-1 day'),
        ]);
    }

    /**
     * Indicate that the fleet has valid insurance.
     */
    public function withValidInsurance(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_expiry' => fake()->dateTimeBetween('+1 month', '+1 year'),
        ]);
    }

    /**
     * Indicate that the fleet has expired insurance.
     */
    public function withExpiredInsurance(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_expiry' => fake()->dateTimeBetween('-6 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the fleet needs service.
     */
    public function needsService(): static
    {
        return $this->state(fn (array $attributes) => [
            'next_service_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'last_service_date' => fake()->dateTimeBetween('-4 months', '-2 months'),
        ]);
    }

    /**
     * Indicate that the fleet is up to date with service.
     */
    public function serviced(): static
    {
        return $this->state(fn (array $attributes) => [
            'last_service_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'next_service_date' => fake()->dateTimeBetween('+1 month', '+3 months'),
        ]);
    }

    /**
     * Configure the model factory to create a fleet with a specific partner.
     */
    public function withPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'partner_id' => $partner->id,
        ]);
    }

    /**
     * Configure the model factory to create a fleet of a specific year.
     */
    public function year(int $year): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => $year,
        ]);
    }

    
}