<?php

namespace App\Livewire\Admin\Blogs\BlogPosts;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateBlogPost extends Component
{
    use WithFileUploads;

    // Basic Information
    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $category_id;
    public $status = 'draft';
    public $visibility = 'public';
    public $password;
    public $is_featured = false;
    public $allow_comments = true;
    public $allow_sharing = true;
    public $published_at;
    public $scheduled_for;
    public $reading_time;

    // Images
    public $featured_image;
    public $temp_featured_image;
    public $gallery_images = [];
    public $temp_gallery_images = [];

    // Tags
    public $selectedTags = [];
    public $availableTags = [];
    public $newTag = '';

    // SEO
    public $meta_title;
    public $meta_description;
    public $meta_keywords = [];
    public $meta_image;
    public $temp_meta_image;
    public $canonical_url;
    public $focus_keyword;

    // Open Graph
    public $og_title;
    public $og_description;
    public $og_image;
    public $temp_og_image;
    public $og_type = 'article';

    // Twitter Card
    public $twitter_title;
    public $twitter_description;
    public $twitter_image;
    public $temp_twitter_image;
    public $twitter_card_type = 'summary_large_image';

    // Technical
    public $noindex = false;
    public $nofollow = false;
    public $include_in_sitemap = true;
    public $sitemap_priority = 0.5;
    public $sitemap_change_frequency = 'weekly';

    // Article Type
    public $article_type = 'Article';

    protected $rules = [
        // Basic
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:blog_posts,slug',
        'excerpt' => 'nullable|string',
        'content' => 'required|string',
        'category_id' => 'required|exists:blog_categories,id',
        'status' => 'required|in:draft,published,scheduled',
        'visibility' => 'required|in:public,private,password_protected',
        'password' => 'required_if:visibility,password_protected|nullable|string',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'allow_sharing' => 'boolean',
        'published_at' => 'required_if:status,published|nullable|date',
        'scheduled_for' => 'required_if:status,scheduled|nullable|date|after:now',
        'reading_time' => 'nullable|integer|min:1',

        // Images
        'featured_image' => 'nullable|image|max:5120',
        'gallery_images.*' => 'nullable|image|max:5120',

        // SEO
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_image' => 'nullable|image|max:5120',
        'canonical_url' => 'nullable|url|max:255',
        'focus_keyword' => 'nullable|string|max:100',

        // Open Graph
        'og_title' => 'nullable|string|max:255',
        'og_description' => 'nullable|string',
        'og_image' => 'nullable|image|max:5120',
        'og_type' => 'nullable|string|max:50',

        // Twitter
        'twitter_title' => 'nullable|string|max:255',
        'twitter_description' => 'nullable|string',
        'twitter_image' => 'nullable|image|max:5120',
        'twitter_card_type' => 'nullable|string|max:50',
    ];

    public function mount()
    {
        $this->availableTags = BlogTag::orderBy('name')->get();
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();
        // $authors = User::role(['admin', 'author', 'editor'])->get();

        $authors = collect(['admin', 'author', 'editor'])
            ->map(function ($role, $index) {
                return [
                    'id' => $index + 1,
                    'name' => $role,
                ];
            })
            ->values()
            ->toArray();


        return view('livewire.admin.blogs.blog-posts.create-blog-post', [
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    public function updatedTitle($value)
    {
        if (!$this->slug) {
            $this->slug = Str::slug($value);
        }

        if (!$this->meta_title) {
            $this->meta_title = $value;
        }

        if (!$this->og_title) {
            $this->og_title = $value;
        }

        if (!$this->twitter_title) {
            $this->twitter_title = $value;
        }
    }

    public function updatedSlug($value)
    {
        $this->slug = Str::slug($value);
    }

    public function updatedExcerpt($value)
    {
        if (!$this->meta_description) {
            $this->meta_description = Str::limit($value, 160);
        }

        if (!$this->og_description) {
            $this->og_description = Str::limit($value, 300);
        }

        if (!$this->twitter_description) {
            $this->twitter_description = Str::limit($value, 200);
        }
    }

    public function generateSlug()
    {
        if ($this->title) {
            $slug = Str::slug($this->title);
            $count = BlogPost::where('slug', 'LIKE', "{$slug}%")->count();
            $this->slug = $count ? "{$slug}-{$count}" : $slug;
        }
    }

    public function updatedFeaturedImage()
    {
        $this->validate(['featured_image' => 'image|max:5120']);
        $this->temp_featured_image = $this->featured_image->temporaryUrl();

        // Auto-set other images if not set
        if (!$this->meta_image) {
            $this->meta_image = $this->featured_image;
            $this->temp_meta_image = $this->temp_featured_image;
        }

        if (!$this->og_image) {
            $this->og_image = $this->featured_image;
            $this->temp_og_image = $this->temp_featured_image;
        }

        if (!$this->twitter_image) {
            $this->twitter_image = $this->featured_image;
            $this->temp_twitter_image = $this->temp_featured_image;
        }
    }

    public function removeFeaturedImage()
    {
        $this->featured_image = null;
        $this->temp_featured_image = null;
    }

    public function addGalleryImage()
    {
        $this->gallery_images[] = null;
    }

    public function updatedGalleryImages($value, $key)
    {
        if (isset($this->gallery_images[$key]) && $this->gallery_images[$key]) {
            $this->validate(["gallery_images.{$key}" => 'image|max:5120']);
            $this->temp_gallery_images[$key] = $this->gallery_images[$key]->temporaryUrl();
        }
    }

    public function removeGalleryImage($index)
    {
        unset($this->gallery_images[$index]);
        unset($this->temp_gallery_images[$index]);
        $this->gallery_images = array_values($this->gallery_images);
        $this->temp_gallery_images = array_values($this->temp_gallery_images);
    }

    public function addTag($tagId)
    {
        if (!in_array($tagId, $this->selectedTags)) {
            $this->selectedTags[] = $tagId;
        }
    }

    public function removeTag($tagId)
    {
        $this->selectedTags = array_filter($this->selectedTags, fn($id) => $id != $tagId);
    }

    public function createNewTag()
    {
        if ($this->newTag) {
            $tag = BlogTag::create([
                'name' => $this->newTag,
                'slug' => Str::slug($this->newTag),
            ]);

            $this->availableTags->push($tag);
            $this->selectedTags[] = $tag->id;
            $this->newTag = '';
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();


            // Handle image uploads
            $featuredImagePath = null;
            if ($this->featured_image) {
                $featuredImagePath = $this->featured_image->store('blog/posts/featured', 'public');
            }

            $metaImagePath = null;
            if ($this->meta_image && $this->meta_image !== $this->featured_image) {
                $metaImagePath = $this->meta_image->store('blog/posts/meta', 'public');
            }

            $ogImagePath = null;
            if ($this->og_image && $this->og_image !== $this->featured_image) {
                $ogImagePath = $this->og_image->store('blog/posts/og', 'public');
            }

            $twitterImagePath = null;
            if ($this->twitter_image && $this->twitter_image !== $this->featured_image) {
                $twitterImagePath = $this->twitter_image->store('blog/posts/twitter', 'public');
            }

            // Handle gallery images
            $galleryPaths = [];
            foreach ($this->gallery_images as $image) {
                if ($image) {
                    $galleryPaths[] = $image->store('blog/posts/gallery', 'public');
                }
            }

            // Calculate reading time if not provided
            if (!$this->reading_time) {
                $wordCount = str_word_count(strip_tags($this->content));
                $this->reading_time = ceil($wordCount / 200);
            }

            // Create post
            $post = BlogPost::create([
                'author_id' => Auth::guard('admin')->user()->id,
                'category_id' => $this->category_id,
                'title' => $this->title,
                'slug' => $this->slug,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'featured_image' => $featuredImagePath,
                'gallery_images' => $galleryPaths,
                'status' => $this->status,
                'visibility' => $this->visibility,
                'password' => $this->visibility === 'password_protected' ? bcrypt($this->password) : null,
                'is_featured' => $this->is_featured,
                'allow_comments' => $this->allow_comments,
                'allow_sharing' => $this->allow_sharing,
                'published_at' => $this->status === 'published' ? $this->published_at : null,
                'scheduled_for' => $this->status === 'scheduled' ? $this->scheduled_for : null,
                'reading_time' => $this->reading_time,

                // SEO
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'meta_keywords' => $this->meta_keywords,
                'meta_image' => $metaImagePath ?? $featuredImagePath,
                'canonical_url' => $this->canonical_url,
                'focus_keyword' => $this->focus_keyword,

                // Open Graph
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_image' => $ogImagePath ?? $featuredImagePath,
                'og_type' => $this->og_type,

                // Twitter
                'twitter_title' => $this->twitter_title,
                'twitter_description' => $this->twitter_description,
                'twitter_image' => $twitterImagePath ?? $featuredImagePath,
                'twitter_card_type' => $this->twitter_card_type,

                // Technical SEO
                'noindex' => $this->noindex,
                'nofollow' => $this->nofollow,
                'include_in_sitemap' => $this->include_in_sitemap,
                'sitemap_priority' => $this->sitemap_priority,
                'sitemap_change_frequency' => $this->sitemap_change_frequency,

                // Article Type
                'article_type' => $this->article_type,
            ]);

            // Attach tags
            if (!empty($this->selectedTags)) {
                $post->tags()->attach($this->selectedTags);

                // Update tag post counts
                foreach ($this->selectedTags as $tagId) {
                    $tag = BlogTag::find($tagId);
                    if ($tag) {
                        $tag->increment('post_count');
                    }
                }
            }

            // Update category post count
            $category = BlogCategory::find($this->category_id);
            if ($category) {
                $category->increment('post_count');
            }

            // Generate SEO data
            $post->updateSeoScore();
            $post->generateSchemaMarkup();

            DB::commit();

            return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error creating blog post' . $th->getMessage());
        }
    }

    public function calculateReadingTime()
    {
        if ($this->content) {
            $wordCount = str_word_count(strip_tags($this->content));
            $this->reading_time = ceil($wordCount / 200);
        }
    }

    public function generateMetaDescription()
    {
        if ($this->excerpt) {
            $this->meta_description = Str::limit($this->excerpt, 160);
            $this->og_description = Str::limit($this->excerpt, 300);
            $this->twitter_description = Str::limit($this->excerpt, 200);
        }
    }
}
