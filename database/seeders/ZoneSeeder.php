<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Zone;
use App\Models\County;
use App\Models\ZoneCounty;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Create Zones
        $zones = [
            'Coast',
            'North Eastern',
            'Rift Valley',
            'Central',
            'Western',
            'Nairobi & Surroundings',
            'Eastern',
        ];

        foreach ($zones as $zoneName) {
            Zone::firstOrCreate(['name' => $zoneName]);
        }

        // 2️⃣ Map Counties to Zones
        $zoneMappings = [
            'Coast' => ['Mombasa', 'Kwale', 'Kilifi', 'Tana River', 'Lamu', 'Taita Taveta'],
            'North Eastern' => ['Garissa', 'Wajir', 'Mandera'],
            'Rift Valley' => [
                'Marsabit', 'Turkana', 'West Pokot', 'Samburu', 'Trans Nzoia', 'Uasin Gishu',
                'Elgeyo Marakwet', 'Nandi', 'Baringo', 'Laikipia', 'Nakuru', 'Narok', 'Kajiado',
                'Kericho', 'Bomet'
            ],
            'Central' => ['Nyandarua', 'Nyeri', 'Kirinyaga', 'Murang\'a', 'Kiambu'],
            'Western' => ['Kakamega', 'Vihiga', 'Bungoma', 'Busia', 'Siaya', 'Kisumu', 'Homa Bay', 'Migori', 'Kisii', 'Nyamira'],
            'Nairobi & Surroundings' => ['Nairobi'],
            'Eastern' => ['Embu', 'Kitui', 'Machakos', 'Makueni'],
        ];

        foreach ($zoneMappings as $zoneName => $countyNames) {
            $zone = Zone::where('name', $zoneName)->first();

            foreach ($countyNames as $countyName) {
                $county = County::where('name', $countyName)->first();

                if ($zone && $county) {
                    ZoneCounty::firstOrCreate([
                        'zone_id' => $zone->id,
                        'county_id' => $county->id,
                    ]);
                }
            }
        }

        $this->command->info('Zones and ZoneCounty mapping seeded successfully!');
    }
}
