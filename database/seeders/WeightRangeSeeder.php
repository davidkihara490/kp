<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeightRange;

class WeightRangeSeeder extends Seeder
{
    public function run(): void
    {
        $ranges = [
            ['min_weight' => 0, 'max_weight' => 1, 'label' => '0 - 1 kg'],
            ['min_weight' => 1, 'max_weight' => 5, 'label' => '1 - 5 kg'],
            ['min_weight' => 5, 'max_weight' => 15, 'label' => '5 - 15 kg'],
            ['min_weight' => 15, 'max_weight' => 40, 'label' => '15 - 40 kg'],
            ['min_weight' => 40, 'max_weight' => 75, 'label' => '40 - 75 kg'],
            ['min_weight' => 75, 'max_weight' => 100, 'label' => '75 - 100 kg'],
        ];

        foreach ($ranges as $range) {
            WeightRange::firstOrCreate($range);
        }

        $this->command->info('Weight ranges seeded successfully.');
    }
}
