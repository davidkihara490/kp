<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Kitchen',
            'Beauty & Personal Care',
            'Sports & Outdoors',
            'Books & Media',
            'Toys & Games',
            'Health & Wellness',
            'Automotive',
            'Grocery',
            'Baby & Kids',
            'Pet Supplies',
            'Office Supplies',
            'Jewelry & Watches',
            'Home Improvement',
            'Arts & Crafts',
            'Musical Instruments',
            'Industrial & Scientific',
            'Travel & Luggage',
            'Collectibles'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'status' => rand(0, 1) ? true : false,
            ]);
        }

        $this->command->info('✅ 20 categories seeded successfully!');
    }
}
