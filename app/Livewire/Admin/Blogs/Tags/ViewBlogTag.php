<?php

namespace App\Livewire\Admin\Blogs\Tags;

use App\Models\BlogTag;
use Livewire\Component;

class ViewBlogTag extends Component
{
    public $tag;
    public $activeTab = 'overview';

    public function mount(BlogTag $tag)
    {
        $this->tag = $tag->load(['posts' => function ($query) {
            $query->latest()->take(10);
        }]);
    }

    public function render()
    {
        $relatedPosts = $this->tag->posts()->latest()->paginate(10);
        
        return view('livewire.admin.blogs.tags.view-blog-tag', [
            'tag' => $this->tag,
            'relatedPosts' => $relatedPosts,
            'popularity' => $this->getPopularityBadge($this->tag->post_count),
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function getPopularityBadge($postCount): array
    {
        if ($postCount >= 50) {
            return ['color' => 'danger', 'text' => 'Very Popular', 'icon' => 'fa-fire'];
        } elseif ($postCount >= 20) {
            return ['color' => 'warning', 'text' => 'Popular', 'icon' => 'fa-star'];
        } elseif ($postCount >= 10) {
            return ['color' => 'info', 'text' => 'Trending', 'icon' => 'fa-chart-line'];
        } elseif ($postCount >= 5) {
            return ['color' => 'primary', 'text' => 'Active', 'icon' => 'fa-bolt'];
        } else {
            return ['color' => 'secondary', 'text' => 'New', 'icon' => 'fa-seedling'];
        }
    }
}