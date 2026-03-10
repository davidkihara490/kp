<?php

namespace App\Livewire\Admin\Blogs\BlogPosts;

use App\Models\BlogPost;
use Livewire\Component;

class ViewBlogPost extends Component
{
    public $post;
    public $activeTab = 'content';
    public $relatedPosts;
    public $popularPosts;

    public function mount(BlogPost $post)
    {
        $this->post = $post->load([
            'author', 
            'category', 
            'tags', 
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                      ->with(['replies', 'user'])
                      ->orderBy('created_at', 'desc');
            }
        ]);
        
        // Get related posts (same category, excluding current)
        $this->relatedPosts = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
            
        // Get popular posts
        $this->popularPosts = BlogPost::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
            
        // Increment view count
        $post->increment('views_count');
    }

    public function render()
    {
        $seoScore = $this->post->seo_score ?? 0;
        $seoColor = $seoScore >= 80 ? 'success' : ($seoScore >= 60 ? 'warning' : 'danger');
        
        return view('livewire.admin.blogs.blog-posts.view-blog-post', [
            'seoScore' => $seoScore,
            'seoColor' => $seoColor,
            'statusBadge' => $this->getStatusBadge(),
            'totalWords' => str_word_count(strip_tags($this->post->content)),
            'internalLinksCount' => count($this->post->internal_links ?? []),
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getStatusBadge()
    {
        if ($this->post->status === 'published' && $this->post->published_at && $this->post->published_at <= now()) {
            return ['color' => 'success', 'text' => 'Published', 'icon' => 'fa-check-circle'];
        } elseif ($this->post->status === 'scheduled' && $this->post->scheduled_for && $this->post->scheduled_for > now()) {
            return ['color' => 'warning', 'text' => 'Scheduled', 'icon' => 'fa-clock'];
        } elseif ($this->post->status === 'draft') {
            return ['color' => 'secondary', 'text' => 'Draft', 'icon' => 'fa-edit'];
        } else {
            return ['color' => 'info', 'text' => ucfirst($this->post->status), 'icon' => 'fa-file'];
        }
    }

    public function toggleFeatured()
    {
        $this->post->update(['is_featured' => !$this->post->is_featured]);
        session()->flash('success', 'Featured status updated successfully!');
    }

    public function toggleStatus()
    {
        if ($this->post->status === 'published') {
            $this->post->update([
                'status' => 'draft',
                'published_at' => null
            ]);
        } else {
            $this->post->update([
                'status' => 'published',
                'published_at' => now()
            ]);
        }
        session()->flash('success', 'Post status updated successfully!');
    }

    public function analyzeSeo()
    {
        $this->post->updateSeoScore();
        session()->flash('success', 'SEO analysis completed!');
    }

    public function generateSchema()
    {
        $this->post->generateSchemaMarkup();
        session()->flash('success', 'Schema markup generated!');
    }
}