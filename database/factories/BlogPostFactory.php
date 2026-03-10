<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogPostFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->sentence($this->faker->numberBetween(5, 10));
        $status = $this->faker->randomElement(['draft', 'published', 'scheduled']);
        $publishedAt = $status === 'published' 
            ? $this->faker->dateTimeBetween('-1 year', 'now')
            : null;
        $scheduledFor = $status === 'scheduled'
            ? $this->faker->dateTimeBetween('+1 day', '+1 month')
            : null;

        return [
            'author_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'category_id' => BlogCategory::inRandomOrder()->first()->id ?? BlogCategory::factory()->create()->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => $this->faker->paragraph(2),
            'content' => $this->faker->paragraphs($this->faker->numberBetween(5, 15), true),
            'featured_image' => $this->faker->imageUrl(1200, 630, 'business', true, 'blog'),
            'gallery_images' => $this->faker->boolean(30) 
                ? [$this->faker->imageUrl(800, 600), $this->faker->imageUrl(800, 600)]
                : [],
            'status' => $status,
            'visibility' => $this->faker->randomElement(['public', 'private', 'password_protected']),
            'password' => $this->faker->boolean(10) ? bcrypt('password123') : null,
            'is_featured' => $this->faker->boolean(20),
            'allow_comments' => $this->faker->boolean(80),
            'allow_sharing' => $this->faker->boolean(90),
            'published_at' => $publishedAt,
            'scheduled_for' => $scheduledFor,
            
            // SEO Fields
            'meta_title' => $this->faker->sentence($this->faker->numberBetween(6, 10)),
            'meta_description' => $this->faker->paragraph(1),
            'meta_keywords' => $this->faker->words($this->faker->numberBetween(3, 8)),
            'meta_image' => $this->faker->boolean(70) ? $this->faker->imageUrl(1200, 630) : null,
            'canonical_url' => $this->faker->boolean(20) ? $this->faker->url() : null,
            'focus_keyword' => $this->faker->boolean(60) ? $this->faker->word() : null,
            
            // Open Graph
            'og_title' => $this->faker->boolean(50) ? $this->faker->sentence() : null,
            'og_description' => $this->faker->boolean(50) ? $this->faker->paragraph() : null,
            'og_image' => $this->faker->boolean(40) ? $this->faker->imageUrl(1200, 630) : null,
            'og_type' => $this->faker->randomElement(['article', 'website', 'blog']),
            
            // Twitter Card
            'twitter_title' => $this->faker->boolean(40) ? $this->faker->sentence() : null,
            'twitter_description' => $this->faker->boolean(40) ? $this->faker->paragraph() : null,
            'twitter_image' => $this->faker->boolean(30) ? $this->faker->imageUrl(1200, 630) : null,
            'twitter_card_type' => $this->faker->randomElement(['summary', 'summary_large_image']),
            
            // Structured Data
            'article_type' => $this->faker->randomElement(['Article', 'BlogPosting', 'NewsArticle']),
            
            // Reading & Engagement
            'reading_time' => $this->faker->numberBetween(2, 15),
            'views_count' => $this->faker->numberBetween(0, 5000),
            'shares_count' => $this->faker->numberBetween(0, 500),
            'comments_count' => $this->faker->numberBetween(0, 200),
            'likes_count' => $this->faker->numberBetween(0, 1000),
            
            // Technical SEO
            'noindex' => $this->faker->boolean(5),
            'nofollow' => $this->faker->boolean(5),
            
            // URL & Sitemap
            'include_in_sitemap' => $this->faker->boolean(95),
            'sitemap_priority' => $this->faker->randomElement([0.1, 0.3, 0.5, 0.7, 0.9]),
            'sitemap_change_frequency' => $this->faker->randomElement(['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never']),
            
            // Performance & Analytics
            'seo_score' => $this->faker->numberBetween(20, 100),
            
            // Audit
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'last_modified_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'last_modified_by' => User::inRandomOrder()->first()->id ?? null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_for' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function withHighSeoScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'seo_score' => $this->faker->numberBetween(80, 100),
            'meta_title' => $this->faker->sentence(8),
            'meta_description' => $this->faker->paragraph(2),
            'focus_keyword' => $this->faker->word(),
        ]);
    }

    public function withImages(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured_image' => $this->faker->imageUrl(1200, 630, 'business', true, 'featured'),
            'gallery_images' => [
                $this->faker->imageUrl(800, 600, 'nature', true, 'gallery1'),
                $this->faker->imageUrl(800, 600, 'nature', true, 'gallery2'),
                $this->faker->imageUrl(800, 600, 'nature', true, 'gallery3'),
            ],
            'meta_image' => $this->faker->imageUrl(1200, 630),
            'og_image' => $this->faker->imageUrl(1200, 630),
            'twitter_image' => $this->faker->imageUrl(1200, 630),
        ]);
    }
}