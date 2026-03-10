<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BlogPost::factory()->count(100)->create();

        // // Create a single blog post
        // $post = BlogPost::factory()->create();

        // // Create published post
        // $post = BlogPost::factory()->published()->create();

        // // Create featured post
        // $post = BlogPost::factory()->featured()->create();

        // // Create post with high SEO score
        // $post = BlogPost::factory()->withHighSeoScore()->create();

        // // Create post with images
        // $post = BlogPost::factory()->withImages()->create();

        // // Create draft post
        // $post = BlogPost::factory()->draft()->create();

        // // Create scheduled post
        // $post = BlogPost::factory()->scheduled()->create();

        // // Create multiple posts
        // $posts = BlogPost::factory()->count(10)->create();
    }
}
