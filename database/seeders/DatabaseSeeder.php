<?php

namespace Database\Seeders;

use App\Livewire\Admin\Payments\Payments;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            CountySeeder::class,
            SubCountySeeder::class,
            // TownSeeder::class,

            UserSeeder::class,
            RolesAndPermissionSeeder::class,
            // PartnerSeeder::class,
            // ParcelHandlingAssistantSeeder::class,
            // DriverSeeder::class,
            // FleetSeeder::class,
            // PickUpAndDropOffPointSeeder::class,
            // FleetSeeder::class,

            BankSeeder::class,

            // ZoneSeeder::class,
            // CategorySeeder::class,
            // SubCategorySeeder::class,
            // ItemSeeder::class,
            WeightRangeSeeder::class,
            // PricingSeeder::class,
            PaymentStructureSeeder::class,
            // FAQSeeder::class,
            // BlogCategorySeeder::class,
            // BlogTagSeeder::class,
            // BlogPostSeeder::class,
            // TermsAndConditionSeeder::class,
            // PrivacyPolicySeeder::class,
            // ParcelSeeder::class,
            // PaymentSeeder::class,

            // DriverFleetAssignmentSeeder::class,
            // ParcelHandlingAssistantPickUpAndDropOffPointAssignmentSeeder::class,

        ]);
    }
}
