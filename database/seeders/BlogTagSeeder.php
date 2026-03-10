<?php

namespace Database\Seeders;

use App\Models\BlogTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogTag::factory()->count(200)->create();

        // $tag = BlogTag::factory()->create();

        // // Create tag with description
        // $tag = BlogTag::factory()->withDescription()->create();

        // // Create tag with metadata
        // $tag = BlogTag::factory()->withMetaData()->create();

        // // Create popular tag
        // $tag = BlogTag::factory()->popular()->create();

        // // Create trending tag
        // $tag = BlogTag::factory()->trending()->create();

        // // Create multiple tags
        // $tags = BlogTag::factory()->count(10)->create();

        // // Create tags with specific attributes
        // $tag = BlogTag::factory()->create([
        //     'name' => 'Laravel',
        //     'slug' => 'laravel',
        //     'description' => 'PHP framework for web artisans',
        // ]);
    }
}
