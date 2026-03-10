<?php

namespace Database\Seeders;

use App\Models\ParcelHandlingAssistant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParcelHandlingAssistantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ParcelHandlingAssistant::factory()->count(100)->create();
    }
}
