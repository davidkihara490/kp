<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            // Basic Information
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->onDelete('set null');

            // Post Content
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Post Settings
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'password_protected'])->default('public');
            $table->string('password')->nullable()->comment('For password protected posts');
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_sharing')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_for')->nullable();

            // SEO Fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('meta_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('focus_keyword')->nullable();

            // Open Graph (OG) Fields
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type')->default('article');

            // Twitter Card Fields
            $table->string('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image')->nullable();
            $table->enum('twitter_card_type', ['summary', 'summary_large_image', 'app', 'player'])->default('summary');

            // Structured Data (Schema.org)
            $table->json('schema_markup')->nullable();
            $table->string('article_type')->default('Article')->comment('BlogPosting, NewsArticle, etc.');

            // Reading & Engagement
            $table->integer('reading_time')->nullable()->comment('Estimated reading time in minutes');
            $table->integer('views_count')->default(0);
            $table->integer('shares_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('likes_count')->default(0);

            // Technical SEO
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);
            $table->json('custom_meta_tags')->nullable();
            $table->string('hreflang')->nullable()->comment('For multilingual sites');
            $table->string('alternate_urls')->nullable()->comment('JSON of alternate language URLs');

            // Performance & Analytics
            $table->decimal('seo_score', 5, 2)->nullable()->comment('SEO optimization score 0-100');
            $table->json('seo_issues')->nullable()->comment('JSON array of SEO issues');
            $table->text('internal_links')->nullable()->comment('JSON of internal links in content');

            // URL & Sitemap
            $table->boolean('include_in_sitemap')->default(true);
            $table->decimal('sitemap_priority', 2, 1)->default(0.8)->comment('0.0 to 1.0');
            $table->string('sitemap_change_frequency')->default('weekly')->comment('always, hourly, daily, weekly, monthly, yearly, never');
            $table->date('last_seo_analysis')->nullable();

            // Social Preview
            $table->string('social_preview_title')->nullable();
            $table->text('social_preview_description')->nullable();
            $table->string('social_preview_image')->nullable();

            // Related Content
            $table->json('related_posts')->nullable()->comment('JSON array of related post IDs');
            $table->json('tags')->nullable()->comment('JSON array of tags');

            // Advanced Features
            $table->boolean('enable_amp')->default(true);
            $table->boolean('enable_rss')->default(true);
            $table->string('custom_template')->nullable();
            $table->json('custom_fields')->nullable()->comment('JSON for additional custom fields');

            // Audit & Timestamps
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('last_modified_at')->nullable();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->onDelete('set null');

            // Indexes for Performance
            $table->index(['status', 'published_at']);
            $table->index(['category_id', 'status']);
            $table->index(['author_id', 'status']);
            $table->index('slug');
            $table->index('is_featured');
            $table->index('views_count');
            $table->index('created_at');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
