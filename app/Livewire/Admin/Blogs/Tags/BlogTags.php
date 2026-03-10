<?php

namespace App\Livewire\Admin\Blogs\Tags;

use App\Models\BlogTag;
use Livewire\Component;
use Livewire\WithPagination;

class BlogTags extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'post_count';
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $tagToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'post_count'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function render()
    {
        $query = BlogTag::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            });

        $tags = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.admin.blogs.tags.blog-tags', [
            'tags' => $tags,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($tagId)
    {
        $this->tagToDelete = BlogTag::find($tagId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->tagToDelete) {
            // Check if tag has posts
            if ($this->tagToDelete->posts()->count() > 0) {
                session()->flash('error', 'Cannot delete tag that has blog posts. Please detach posts first.');
                $this->showDeleteModal = false;
                $this->tagToDelete = null;
                return;
            }

            $this->tagToDelete->delete();
            session()->flash('success', 'Tag deleted successfully.');
            
            $this->showDeleteModal = false;
            $this->tagToDelete = null;
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->sortField = 'post_count';
        $this->sortDirection = 'desc';
    }

    public function getPopularityBadge($postCount)
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