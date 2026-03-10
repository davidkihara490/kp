<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $subCategories = SubCategory::all();
        $itemAdjectives = ['Premium', 'Deluxe', 'Standard', 'Basic', 'Professional', 
                          'Advanced', 'Essential', 'Compact', 'Portable', 'Wireless',
                          'Smart', 'Digital', 'Analog', 'Mechanical', 'Automatic',
                          'Manual', 'Electric', 'Battery', 'Solar', 'USB'];
        
        $itemTypes = ['Device', 'Gadget', 'Tool', 'Kit', 'Set', 'Package', 'Bundle',
                     'System', 'Unit', 'Model', 'Version', 'Edition', 'Collection',
                     'Series', 'Line', 'Range', 'Type', 'Style', 'Design', 'Pattern'];

        $totalItems = 0;

        foreach ($subCategories as $subCategory) {
            $itemCount = rand(2, 10); // 50-100 items per subcategory
            
            for ($i = 1; $i <= $itemCount; $i++) {
                Item::create([
                    'sub_category_id' => $subCategory->id,
                    'name' => $subCategory->name . ' - ' . 
                             $itemAdjectives[array_rand($itemAdjectives)] . ' ' . 
                             $itemTypes[array_rand($itemTypes)] . ' ' . $i,
                    'status' => rand(0, 1) ? true : false,
                ]);
            }
            
            $totalItems += $itemCount;
        }

        $this->command->info("✅ {$totalItems} items seeded successfully!");
    }
}