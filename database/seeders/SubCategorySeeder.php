<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $subCategoriesData = [
            'Electronics' => [
                'Smartphones', 'Laptops', 'Tablets', 'Headphones', 'Speakers',
                'Wearable Tech', 'Cameras', 'Gaming Consoles', 'TVs', 'Home Audio',
                'Computer Accessories', 'Networking Devices', 'Smart Home Devices',
                'Chargers & Cables', 'Power Banks', 'Memory Cards', 'Drones',
                'Action Cameras', 'Printers', 'Monitors'
            ],
            'Fashion' => [
                'Men\'s Clothing', 'Women\'s Clothing', 'Kids\' Clothing',
                'Men\'s Shoes', 'Women\'s Shoes', 'Kids\' Shoes', 'Bags & Luggage',
                'Watches', 'Sunglasses', 'Jewelry', 'Belts', 'Hats & Caps',
                'Sportswear', 'Traditional Wear', 'Winter Wear', 'Summer Wear',
                'Formal Wear', 'Casual Wear', 'Underwear', 'Swimwear'
            ],
            'Home & Kitchen' => [
                'Furniture', 'Bedding', 'Bath', 'Home Decor', 'Kitchen Appliances',
                'Cookware', 'Dinnerware', 'Cutlery', 'Storage & Organization',
                'Lighting', 'Cleaning Supplies', 'Laundry', 'Gardening',
                'Home Security', 'Tools & Hardware', 'Pest Control',
                'Home Fragrance', 'Wall Art', 'Curtains & Blinds', 'Rugs & Carpets'
            ],
        ];

        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 15; $i++) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $category->name . ' Subcategory ' . $i,
                    'status' => rand(0, 1) ? true : false,
                ]);
            }
        }

        $this->command->info('✅ ' . SubCategory::count() . ' subcategories seeded successfully!');
    }
}