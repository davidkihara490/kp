<?php

namespace Database\Seeders;

use App\Models\PickUpAndDropOffPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PickUpAndDropOffPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PickUpAndDropOffPoint::factory()->count(50)->create();
    }
}
