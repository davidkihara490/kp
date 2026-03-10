<?php

namespace Database\Factories;

use App\Models\PickUpAndDropOffPoint;
use App\Models\Partner;
use App\Models\Town;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PickUpAndDropOffPoint>
 */
class PickUpAndDropOffPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kenyanBuildings = [
            'I&M Bank Tower', 'Times Tower', 'NSSF Building', 'Cooperative Bank House',
            'Kenya Re Towers', 'UAP Tower', 'Britam Tower', 'Prudential Building',
            'Ambassadeur House', 'Hilton Hotel', 'Stanbank House', 'Barclays Plaza',
            'Absa Towers', 'KCB Plaza', 'Equity Centre', 'Delta Towers',
            'Mombasa Trade Centre', 'Kisumu Tower', 'Eldoret Plaza', 'Nakuru Mall'
        ];

        $kenyanTowns = [
            'Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Eldoret', 'Thika', 'Machakos',
            'Kiambu', 'Kikuyu', 'Ruiru', 'Juja', 'Limuru', 'Kitengela', 'Athi River',
            'Kitui', 'Embu', 'Meru', 'Nyeri', 'Muranga', 'Kerugoya', 'Karatina',
            'Naivasha', 'Gilgil', 'Nyandarua', 'Ol Kalou', 'Narok', 'Bomet', 'Kericho',
            'Kisii', 'Migori', 'Homa Bay', 'Siaya', 'Busia', 'Bungoma', 'Kakamega',
            'Vihiga', 'Trans Nzoia', 'Uasin Gishu', 'Nandi', 'Laikipia', 'Samburu',
            'Isiolo', 'Marsabit', 'Garissa', 'Wajir', 'Mandera', 'Lamu', 'Tana River',
            'Kilifi', 'Kwale', 'Taita Taveta', 'Makueni', 'Kajiado'
        ];

        $kenyanFirstNames = [
            'John', 'James', 'Peter', 'David', 'Daniel', 'Joseph', 'Samuel', 'Patrick',
            'Mary', 'Jane', 'Grace', 'Mercy', 'Faith', 'Lucy', 'Agnes', 'Margaret',
            'Kenneth', 'Brian', 'Kevin', 'Collins', 'Dennis', 'Evans', 'Felix', 'George'
        ];

        $kenyanLastNames = [
            'Ochieng', 'Okoth', 'Omondi', 'Otieno', 'Kipchumba', 'Kipkemoi', 'Kiprotich',
            'Maina', 'Kimani', 'Kariuki', 'Njenga', 'Wainaina', 'Macharia', 'Ndegwa',
            'Kamau', 'Odhiambo', 'Mwangi', 'Njoroge', 'Waweru', 'Mutua', 'Kilonzo'
        ];

        $statuses = ['active', 'inactive', 'pending', 'suspended'];
        $operatingDays = [
            'Monday - Friday',
            'Monday - Saturday',
            'Monday - Sunday',
            'Monday - Friday, Saturday Half Day',
            '24/7',
            'Weekdays Only',
            'Weekends Only'
        ];

        $town = fake()->randomElement($kenyanTowns);
        $building = fake()->randomElement($kenyanBuildings);
        $roomNumber = fake()->optional(0.7)->numberBetween(1, 100) . fake()->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H']);
        
        // Generate a unique code for the point
        $code = $this->generatePointCode($town);

        return [
            'partner_id' => optional(Partner::inRandomOrder()->first())->id,
            'name' => $this->generatePointName($town, $building),
            'code' => $code,
            'town_id' => optional(Town::inRandomOrder()->first())->id,
            'building' => $building,
            'room_number' => $roomNumber,
            'address' => $building . ', ' . ($roomNumber ? 'Room ' . $roomNumber . ', ' : '') . $town . ', Kenya',
            'status' => fake()->randomElement($statuses),
            'contact_person' => fake()->randomElement($kenyanFirstNames) . ' ' . fake()->randomElement($kenyanLastNames),
            'contact_email' => 'info@' . strtolower(str_replace(' ', '', $this->generatePointName($town, $building))) . '.co.ke',
            'contact_phone_number' => $this->generateKenyanPhone(),
            'opening_hours' => fake()->optional(0.9)->time('H:i:s', '09:00'),
            'closing_hours' => fake()->optional(0.9)->time('H:i:s', '17:00'),
            'operating_days' => fake()->randomElement($operatingDays),
        ];
    }

    /**
     * Generate a Kenyan phone number
     */
    private function generateKenyanPhone(): string
    {
        $prefixes = ['0700', '0701', '0710', '0711', '0712', '0713', '0714', '0715', '0716', '0717', '0718', '0719',
                    '0720', '0721', '0722', '0723', '0724', '0725', '0726', '0727', '0728', '0729',
                    '0730', '0731', '0732', '0733', '0734', '0735', '0736', '0737', '0738', '0739',
                    '0740', '0741', '0742', '0743', '0744', '0745', '0746', '0747', '0748', '0749',
                    '0750', '0751', '0752', '0753', '0754', '0755', '0756', '0757', '0758', '0759',
                    '0760', '0761', '0762', '0763', '0764', '0765', '0766', '0767', '0768', '0769',
                    '0770', '0771', '0772', '0773', '0774', '0775', '0776', '0777', '0778', '0779',
                    '0780', '0781', '0782', '0783', '0784', '0785', '0786', '0787', '0788', '0789',
                    '0790', '0791', '0792', '0793', '0794', '0795', '0796', '0797', '0798', '0799',
                    '0110', '0111', '0112', '0113', '0114', '0115'];
        
        $prefix = fake()->randomElement($prefixes);
        $suffix = str_pad(fake()->numberBetween(0, 999999), 6, '0', STR_PAD_LEFT);
        
        return $prefix . $suffix;
    }

    /**
     * Generate a point name based on town and building
     */
    private function generatePointName(string $town, string $building): string
    {
        $nameTypes = [
            $town . ' Pickup Point',
            $town . ' Dropoff Center',
            $town . ' Parcel Hub',
            $town . ' Collection Point',
            $town . ' Delivery Station',
            $town . ' Express Center',
            $town . ' Parcel Stop',
            $town . ' Cargo Point',
            $building . ' Collection Center',
            $building . ' Parcel Desk',
            'Karibu ' . $town . ' Point',
            'Karibu Express - ' . $town,
        ];

        return fake()->randomElement($nameTypes);
    }

    /**
     * Generate a unique point code
     */
    private function generatePointCode(string $town): string
    {
        static $usedCodes = [];
        
        $townPrefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $town), 0, 3));
        $number = str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
        $code = $townPrefix . '-' . $number;
        
        // Ensure uniqueness
        $attempts = 0;
        while (in_array($code, $usedCodes) && $attempts < 100) {
            $number = str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);
            $code = $townPrefix . '-' . $number;
            $attempts++;
        }
        
        $usedCodes[] = $code;
        return $code;
    }

    /**
     * Indicate that the point is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the point is in a specific town.
     */
    public function inTown(string $townName): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $townName . ' Pickup Point',
            'code' => strtoupper(substr($townName, 0, 3)) . '-' . fake()->numberBetween(100, 999),
            'address' => $townName . ', Kenya',
        ]);
    }

    /**
     * Indicate that the point belongs to a specific partner.
     */
    public function withPartner(Partner $partner): static
    {
        return $this->state(fn (array $attributes) => [
            'partner_id' => $partner->id,
        ]);
    }

    /**
     * Indicate that the point is a warehouse (special point).
     */
    public function asWarehouse(): static
    {
        $warehouseNames = [
            'Central Warehouse', 'Main Distribution Center', 'Karibu Central Hub',
            'Primary Sorting Facility', 'Main Warehouse', 'Central Sorting Center',
            'Karibu Main Depot', 'Central Fulfillment Center'
        ];

        $warehouseCode = 'WH-' . str_pad(fake()->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);

        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement($warehouseNames),
            'code' => $warehouseCode,
            'building' => fake()->randomElement(['Industrial Area', 'Export Processing Zone', 'Enterprise Road', 'Lunga Lunga Road']),
            'room_number' => null,
            'operating_days' => 'Monday - Sunday',
            'opening_hours' => '00:00:00',
            'closing_hours' => '23:59:59',
        ]);
    }

    /**
     * Indicate that the point is open 24/7.
     */
    public function twentyFourSeven(): static
    {
        return $this->state(fn (array $attributes) => [
            'opening_hours' => '00:00:00',
            'closing_hours' => '23:59:59',
            'operating_days' => '24/7',
        ]);
    }

    /**
     * Indicate that the point has specific operating hours.
     */
    public function withHours(string $open, string $close, string $days = 'Monday - Friday'): static
    {
        return $this->state(fn (array $attributes) => [
            'opening_hours' => $open,
            'closing_hours' => $close,
            'operating_days' => $days,
        ]);
    }
}