<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'status',
        'visibility',
        'password',
        'is_featured',
        'allow_comments',
        'allow_sharing',
        'published_at',
        'scheduled_for',
        
        // SEO Fields
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'canonical_url',
        'focus_keyword',
        
        // Open Graph
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        
        // Twitter Card
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_card_type',
        
        // Structured Data
        'schema_markup',
        'article_type',
        
        // Reading & Engagement
        'reading_time',
        'views_count',
        'shares_count',
        'comments_count',
        'likes_count',
        
        // Technical SEO
        'noindex',
        'nofollow',
        'custom_meta_tags',
        'hreflang',
        'alternate_urls',
        
        // Performance & Analytics
        'seo_score',
        'seo_issues',
        'internal_links',
        
        // URL & Sitemap
        'include_in_sitemap',
        'sitemap_priority',
        'sitemap_change_frequency',
        'last_seo_analysis',
        
        // Social Preview
        'social_preview_title',
        'social_preview_description',
        'social_preview_image',
        
        // Related Content
        'related_posts',
        'tags',
        
        // Advanced Features
        'enable_amp',
        'enable_rss',
        'custom_template',
        'custom_fields',
        
        // Audit
        'last_modified_at',
        'last_modified_by',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'meta_keywords' => 'array',
        'schema_markup' => 'array',
        'custom_meta_tags' => 'array',
        'alternate_urls' => 'array',
        'seo_issues' => 'array',
        'internal_links' => 'array',
        'related_posts' => 'array',
        'tags' => 'array',
        'custom_fields' => 'array',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'allow_sharing' => 'boolean',
        'noindex' => 'boolean',
        'nofollow' => 'boolean',
        'include_in_sitemap' => 'boolean',
        'enable_amp' => 'boolean',
        'enable_rss' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_for' => 'datetime',
        'last_modified_at' => 'datetime',
        'last_seo_analysis' => 'date',
        'seo_score' => 'decimal:2',
        'sitemap_priority' => 'decimal:1',
    ];

    protected $dates = [
        'published_at',
        'scheduled_for',
        'last_modified_at',
    ];

    protected $appends = [
        'url',
        'full_url',
        'reading_time_minutes',
        'is_published',
        'is_scheduled',
        'meta_image_url',
        'og_image_url',
        'twitter_image_url',
        'social_preview_image_url',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_blog_tag');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function lastModifiedBy()
    {
        return $this->belongsTo(User::class, 'last_modified_by');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return '/blog/' . $this->slug;
    }

    public function getFullUrlAttribute()
    {
        return url('/blog/' . $this->slug);
    }

    public function getReadingTimeMinutesAttribute()
    {
        if ($this->reading_time) {
            return $this->reading_time;
        }
        
        // Calculate reading time: 200 words per minute
        $wordCount = str_word_count(strip_tags($this->content));
        return ceil($wordCount / 200);
    }

    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getIsScheduledAttribute()
    {
        return $this->status === 'scheduled' && $this->scheduled_for > now();
    }

    public function getMetaImageUrlAttribute()
    {
        return $this->meta_image ? asset('storage/' . $this->meta_image) : null;
    }

    public function getOgImageUrlAttribute()
    {
        return $this->og_image ? asset('storage/' . $this->og_image) : $this->meta_image_url;
    }

    public function getTwitterImageUrlAttribute()
    {
        return $this->twitter_image ? asset('storage/' . $this->twitter_image) : $this->og_image_url;
    }

    public function getSocialPreviewImageUrlAttribute()
    {
        return $this->social_preview_image ? asset('storage/' . $this->social_preview_image) : $this->featured_image_url;
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : null;
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Generate excerpt from content if not provided
        return Str::limit(strip_tags($this->content), 160);
    }

    public function getMetaDescriptionAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // Generate meta description from excerpt if not provided
        return Str::limit($this->excerpt, 160);
    }

    // SEO Methods
    public function generateSlug()
    {
        $slug = Str::slug($this->title);
        $count = BlogPost::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function updateSeoScore()
    {
        $score = 0;
        $issues = [];
        
        // Check title
        if (Str::length($this->title) >= 50 && Str::length($this->title) <= 60) {
            $score += 20;
        } else {
            $issues[] = 'Title should be 50-60 characters';
        }
        
        // Check meta description
        if ($this->meta_description && Str::length($this->meta_description) >= 120 && Str::length($this->meta_description) <= 160) {
            $score += 20;
        } else {
            $issues[] = 'Meta description should be 120-160 characters';
        }
        
        // Check focus keyword
        if ($this->focus_keyword) {
            $score += 10;
            
            // Check if focus keyword appears in title
            if (Str::contains(Str::lower($this->title), Str::lower($this->focus_keyword))) {
                $score += 10;
            } else {
                $issues[] = 'Focus keyword not found in title';
            }
            
            // Check if focus keyword appears in content
            if (Str::contains(Str::lower($this->content), Str::lower($this->focus_keyword))) {
                $score += 10;
            } else {
                $issues[] = 'Focus keyword not found in content';
            }
        }
        
        // Check featured image
        if ($this->featured_image) {
            $score += 10;
        } else {
            $issues[] = 'No featured image';
        }
        
        // Check content length
        $wordCount = str_word_count(strip_tags($this->content));
        if ($wordCount >= 300) {
            $score += 20;
        } else {
            $issues[] = 'Content too short (minimum 300 words)';
        }
        
        // Check internal links
        if ($this->internal_links && count($this->internal_links) > 0) {
            $score += 10;
        } else {
            $issues[] = 'No internal links';
        }
        
        $this->update([
            'seo_score' => $score,
            'seo_issues' => $issues,
            'last_seo_analysis' => now(),
        ]);
    }

    public function generateSchemaMarkup()
    {
        $markup = [
            '@context' => 'https://schema.org',
            '@type' => $this->article_type,
            'headline' => $this->title,
            'description' => $this->excerpt,
            'image' => $this->featured_image_url,
            'datePublished' => $this->published_at?->toIso8601String(),
            'dateModified' => $this->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author->name ?? 'Admin',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('logo.png'),
                ],
            ],
        ];
        
        if ($this->category) {
            $markup['articleSection'] = $this->category->name;
        }
        
        if ($this->reading_time_minutes) {
            $markup['timeRequired'] = 'PT' . $this->reading_time_minutes . 'M';
        }
        
        $this->update(['schema_markup' => $markup]);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
                    ->orderBy('views_count', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_for', '>', now());
    }

    // Event Handlers
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = $post->generateSlug();
            }
            
            if ($post->status === 'published' && !$post->published_at) {
                $post->published_at = now();
            }
        });

        static::updating(function ($post) {
            $post->last_modified_at = now();
            $post->last_modified_by = Auth::id();
        });
    }
}