<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = BlogCategory::factory()->count(15)->create();
        // $category = BlogCategory::factory()->create([
        //     'parent_id' => 1,
        //     'is_active' => true,
        // ]);
    }
}
