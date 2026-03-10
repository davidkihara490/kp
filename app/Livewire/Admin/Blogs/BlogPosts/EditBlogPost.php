<?php

namespace App\Livewire\Admin\Blogs\BlogPosts;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditBlogPost extends Component
{
    use WithFileUploads;

    public $post;
    
    // Basic Information
    public $title;
    public $slug;
    public $excerpt;
    public $content;
    public $category_id;
    public $status;
    public $visibility;
    public $password;
    public $is_featured;
    public $allow_comments;
    public $allow_sharing;
    public $published_at;
    public $scheduled_for;
    public $reading_time;
    
    // Images
    public $featured_image;
    public $temp_featured_image;
    public $current_featured_image;
    public $gallery_images = [];
    public $temp_gallery_images = [];
    public $existing_gallery_images = [];
    
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
    public $current_meta_image;
    public $canonical_url;
    public $focus_keyword;
    
    // Open Graph
    public $og_title;
    public $og_description;
    public $og_image;
    public $temp_og_image;
    public $current_og_image;
    public $og_type;
    
    // Twitter Card
    public $twitter_title;
    public $twitter_description;
    public $twitter_image;
    public $temp_twitter_image;
    public $current_twitter_image;
    public $twitter_card_type;
    
    // Technical
    public $noindex;
    public $nofollow;
    public $include_in_sitemap;
    public $sitemap_priority;
    public $sitemap_change_frequency;
    
    // Article Type
    public $article_type;

    protected function rules()
    {
        return [
            // Basic
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,' . $this->post->id,
            'excerpt' => 'nullable|string|max:500',
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
            'meta_description' => 'nullable|string|max:500',
            'meta_image' => 'nullable|image|max:5120',
            'canonical_url' => 'nullable|url|max:255',
            'focus_keyword' => 'nullable|string|max:100',
            
            // Open Graph
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|image|max:5120',
            'og_type' => 'nullable|string|max:50',
            
            // Twitter
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string|max:500',
            'twitter_image' => 'nullable|image|max:5120',
            'twitter_card_type' => 'nullable|string|max:50',
        ];
    }

    public function mount(BlogPost $post)
    {
        $this->post = $post->load(['category', 'tags']);
        
        // Basic Information
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->category_id = $post->category_id;
        $this->status = $post->status;
        $this->visibility = $post->visibility;
        $this->password = '';
        $this->is_featured = $post->is_featured;
        $this->allow_comments = $post->allow_comments;
        $this->allow_sharing = $post->allow_sharing;
        $this->published_at = $post->published_at?->format('Y-m-d\TH:i');
        $this->scheduled_for = $post->scheduled_for?->format('Y-m-d\TH:i');
        $this->reading_time = $post->reading_time;
        
        // Images
        $this->current_featured_image = $post->featured_image;
        $this->existing_gallery_images = $post->gallery_images ?? [];
        
        // Tags
        $this->selectedTags = $post->tags?->pluck('id')->toArray() ?? [];
        $this->availableTags = BlogTag::orderBy('name')->get();
        
        // SEO
        $this->meta_title = $post->meta_title;
        $this->meta_description = $post->meta_description;
        $this->meta_keywords = $post->meta_keywords ?? [];
        $this->current_meta_image = $post->meta_image;
        $this->canonical_url = $post->canonical_url;
        $this->focus_keyword = $post->focus_keyword;
        
        // Open Graph
        $this->og_title = $post->og_title;
        $this->og_description = $post->og_description;
        $this->current_og_image = $post->og_image;
        $this->og_type = $post->og_type;
        
        // Twitter
        $this->twitter_title = $post->twitter_title;
        $this->twitter_description = $post->twitter_description;
        $this->current_twitter_image = $post->twitter_image;
        $this->twitter_card_type = $post->twitter_card_type;
        
        // Technical
        $this->noindex = $post->noindex;
        $this->nofollow = $post->nofollow;
        $this->include_in_sitemap = $post->include_in_sitemap;
        $this->sitemap_priority = $post->sitemap_priority;
        $this->sitemap_change_frequency = $post->sitemap_change_frequency;
        
        // Article Type
        $this->article_type = $post->article_type;
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

        
        return view('livewire.admin.blogs.blog-posts.edit-blog-post', [
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }

    public function updatedTitle($value)
    {
        $this->slug = Str::slug($value);
    }

    public function generateSlug()
    {
        if ($this->title) {
            $slug = Str::slug($this->title);
            $count = BlogPost::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $this->post->id)->count();
            $this->slug = $count ? "{$slug}-{$count}" : $slug;
        }
    }

    public function updatedFeaturedImage()
    {
        $this->validate(['featured_image' => 'image|max:5120']);
        $this->temp_featured_image = $this->featured_image->temporaryUrl();
    }

    public function removeFeaturedImage()
    {
        if ($this->current_featured_image) {
            Storage::disk('public')->delete($this->current_featured_image);
        }
        $this->featured_image = null;
        $this->temp_featured_image = null;
        $this->current_featured_image = null;
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

    public function removeExistingGalleryImage($index)
    {
        if (isset($this->existing_gallery_images[$index])) {
            Storage::disk('public')->delete($this->existing_gallery_images[$index]);
            unset($this->existing_gallery_images[$index]);
            $this->existing_gallery_images = array_values($this->existing_gallery_images);
        }
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

    public function update()
    {
        $this->validate();

        // Handle image uploads
        $featuredImagePath = $this->current_featured_image;
        if ($this->featured_image) {
            // Delete old image if exists
            if ($this->current_featured_image) {
                Storage::disk('public')->delete($this->current_featured_image);
            }
            $featuredImagePath = $this->featured_image->store('blog/posts/featured', 'public');
        }

        $metaImagePath = $this->current_meta_image;
        if ($this->meta_image) {
            if ($this->current_meta_image) {
                Storage::disk('public')->delete($this->current_meta_image);
            }
            $metaImagePath = $this->meta_image->store('blog/posts/meta', 'public');
        }

        $ogImagePath = $this->current_og_image;
        if ($this->og_image) {
            if ($this->current_og_image) {
                Storage::disk('public')->delete($this->current_og_image);
            }
            $ogImagePath = $this->og_image->store('blog/posts/og', 'public');
        }

        $twitterImagePath = $this->current_twitter_image;
        if ($this->twitter_image) {
            if ($this->current_twitter_image) {
                Storage::disk('public')->delete($this->current_twitter_image);
            }
            $twitterImagePath = $this->twitter_image->store('blog/posts/twitter', 'public');
        }

        // Handle gallery images
        $galleryPaths = $this->existing_gallery_images;
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

        // Old category for post count update
        $oldCategoryId = $this->post->category_id;

        // Update post
        $this->post->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'featured_image' => $featuredImagePath,
            'gallery_images' => $galleryPaths,
            'status' => $this->status,
            'visibility' => $this->visibility,
            'password' => $this->visibility === 'password_protected' ? bcrypt($this->password) : $this->post->password,
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
            'meta_image' => $metaImagePath,
            'canonical_url' => $this->canonical_url,
            'focus_keyword' => $this->focus_keyword,
            
            // Open Graph
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $ogImagePath,
            'og_type' => $this->og_type,
            
            // Twitter
            'twitter_title' => $this->twitter_title,
            'twitter_description' => $this->twitter_description,
            'twitter_image' => $twitterImagePath,
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

        // Update tags
        $oldTags = $this->post->tags->pluck('id')->toArray();
        
        // Detach old tags
        $this->post->tags()->detach();
        
        // Attach new tags
        if (!empty($this->selectedTags)) {
            $this->post->tags()->attach($this->selectedTags);
            
            // Update tag post counts
            foreach ($this->selectedTags as $tagId) {
                $tag = BlogTag::find($tagId);
                if ($tag && !in_array($tagId, $oldTags)) {
                    $tag->increment('post_count');
                }
            }
            
            // Decrement counts for removed tags
            foreach ($oldTags as $tagId) {
                if (!in_array($tagId, $this->selectedTags)) {
                    $tag = BlogTag::find($tagId);
                    if ($tag && $tag->post_count > 0) {
                        $tag->decrement('post_count');
                    }
                }
            }
        } else {
            // Decrement all old tags if no tags selected
            foreach ($oldTags as $tagId) {
                $tag = BlogTag::find($tagId);
                if ($tag && $tag->post_count > 0) {
                    $tag->decrement('post_count');
                }
            }
        }

        // Update category post counts if category changed
        if ($oldCategoryId != $this->category_id) {
            $oldCategory = BlogCategory::find($oldCategoryId);
            if ($oldCategory && $oldCategory->post_count > 0) {
                $oldCategory->decrement('post_count');
            }
            
            $newCategory = BlogCategory::find($this->category_id);
            if ($newCategory) {
                $newCategory->increment('post_count');
            }
        }

        // Update SEO data
        $this->post->updateSeoScore();
        $this->post->generateSchemaMarkup();

        session()->flash('success', 'Blog post updated successfully!');
        return redirect()->route('blog-posts.index');
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

    public function getStatusBadge()
    {
        if ($this->status === 'published') {
            return ['color' => 'success', 'text' => 'Published', 'icon' => 'fa-check-circle'];
        } elseif ($this->status === 'scheduled') {
            return ['color' => 'warning', 'text' => 'Scheduled', 'icon' => 'fa-clock'];
        } else {
            return ['color' => 'secondary', 'text' => 'Draft', 'icon' => 'fa-edit'];
        }
    }
}