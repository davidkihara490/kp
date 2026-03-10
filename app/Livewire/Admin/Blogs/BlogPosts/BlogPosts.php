<?php

namespace App\Livewire\Admin\Blogs\BlogPosts;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class BlogPosts extends Component
{
    use WithPagination;
        protected $paginationTheme = 'bootstrap';


    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';
    public $authorFilter = '';
    public $tagFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $postToDelete = null;
    public $showBulkActions = false;
    public $selectedPosts = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'authorFilter' => ['except' => ''],
        'tagFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function render()
    {
        $query = BlogPost::query()
            ->with(['author', 'category', 'tags'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                      ->orWhereHas('author', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'published') {
                    $query->where('status', 'published')->where('published_at', '<=', now());
                } elseif ($this->statusFilter === 'draft') {
                    $query->where('status', 'draft');
                } elseif ($this->statusFilter === 'scheduled') {
                    $query->where('status', 'scheduled')->where('scheduled_for', '>', now());
                } elseif ($this->statusFilter === 'trashed') {
                    $query->onlyTrashed();
                }
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category_id', $this->categoryFilter);
            })
            ->when($this->authorFilter, function ($query) {
                $query->where('author_id', $this->authorFilter);
            })
            ->when($this->tagFilter, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('blog_tags.id', $this->tagFilter);
                });
            });

        // Handle trashed posts separately for sorting
        if ($this->statusFilter === 'trashed') {
            $posts = $query->orderBy('deleted_at', 'desc')->paginate(15);
        } else {
            $posts = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);
        }

        return view('livewire.admin.blogs.blog-posts.blog-posts', [
            'posts' => $posts,
            'categories' => BlogCategory::where('is_active', true)->orderBy('name')->get(),
            // 'authors' => User::role(['admin', 'author', 'editor'])->get(),
            'authors' => ['admin', 'author', 'editor'],
            'tags' => BlogTag::orderBy('name')->get(),
            'statuses' => [
                '' => 'All Status',
                'published' => 'Published',
                'draft' => 'Draft',
                'scheduled' => 'Scheduled',
                'trashed' => 'Trashed'
            ],
            'totalPosts' => BlogPost::count(),
            'publishedPosts' => BlogPost::where('status', 'published')->where('published_at', '<=', now())->count(),
            'draftPosts' => BlogPost::where('status', 'draft')->count(),
            'scheduledPosts' => BlogPost::where('status', 'scheduled')->where('scheduled_for', '>', now())->count(),
            'trashedPosts' => BlogPost::onlyTrashed()->count(),
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

    public function confirmDelete($postId)
    {
        $this->postToDelete = BlogPost::withTrashed()->find($postId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->postToDelete) {
            if ($this->postToDelete->trashed()) {
                // Permanent delete
                $this->postToDelete->forceDelete();
                session()->flash('success', 'Post permanently deleted.');
            } else {
                // Soft delete
                $this->postToDelete->delete();
                session()->flash('success', 'Post moved to trash.');
            }
            
            $this->showDeleteModal = false;
            $this->postToDelete = null;
        }
    }

    public function restore($postId)
    {
        $post = BlogPost::onlyTrashed()->find($postId);
        if ($post) {
            $post->restore();
            session()->flash('success', 'Post restored successfully.');
        }
    }

    public function toggleFeatured($postId)
    {
        $post = BlogPost::find($postId);
        if ($post) {
            $post->update(['is_featured' => !$post->is_featured]);
            session()->flash('success', 'Post featured status updated.');
        }
    }

    public function toggleStatus($postId)
    {
        $post = BlogPost::find($postId);
        if ($post) {
            $newStatus = $post->status === 'published' ? 'draft' : 'published';
            $post->update([
                'status' => $newStatus,
                'published_at' => $newStatus === 'published' ? now() : null
            ]);
            session()->flash('success', 'Post status updated.');
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->categoryFilter = '';
        $this->authorFilter = '';
        $this->tagFilter = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->selectedPosts = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = BlogPost::query();
            
            if ($this->statusFilter === 'trashed') {
                $query->onlyTrashed();
            } elseif ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }
            
            $this->selectedPosts = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedPosts = [];
        }
        $this->showBulkActions = count($this->selectedPosts) > 0;
    }

    public function updatedSelectedPosts()
    {
        $this->selectAll = count($this->selectedPosts) === BlogPost::count();
        $this->showBulkActions = count($this->selectedPosts) > 0;
    }

    public function bulkDelete()
    {
        if (count($this->selectedPosts) > 0) {
            if ($this->statusFilter === 'trashed') {
                BlogPost::onlyTrashed()->whereIn('id', $this->selectedPosts)->forceDelete();
                session()->flash('success', count($this->selectedPosts) . ' posts permanently deleted.');
            } else {
                BlogPost::whereIn('id', $this->selectedPosts)->delete();
                session()->flash('success', count($this->selectedPosts) . ' posts moved to trash.');
            }
            
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkRestore()
    {
        if (count($this->selectedPosts) > 0) {
            BlogPost::onlyTrashed()->whereIn('id', $this->selectedPosts)->restore();
            session()->flash('success', count($this->selectedPosts) . ' posts restored.');
            
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkPublish()
    {
        if (count($this->selectedPosts) > 0) {
            BlogPost::whereIn('id', $this->selectedPosts)->update([
                'status' => 'published',
                'published_at' => now()
            ]);
            session()->flash('success', count($this->selectedPosts) . ' posts published.');
            
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkDraft()
    {
        if (count($this->selectedPosts) > 0) {
            BlogPost::whereIn('id', $this->selectedPosts)->update([
                'status' => 'draft',
                'published_at' => null
            ]);
            session()->flash('success', count($this->selectedPosts) . ' posts moved to draft.');
            
            $this->selectedPosts = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function getStatusBadge($status, $publishedAt = null, $scheduledFor = null)
    {
        if ($status === 'published' && $publishedAt && $publishedAt <= now()) {
            return ['color' => 'success', 'text' => 'Published', 'icon' => 'fa-check-circle'];
        } elseif ($status === 'scheduled' && $scheduledFor && $scheduledFor > now()) {
            return ['color' => 'warning', 'text' => 'Scheduled', 'icon' => 'fa-clock'];
        } elseif ($status === 'draft') {
            return ['color' => 'secondary', 'text' => 'Draft', 'icon' => 'fa-edit'];
        } else {
            return ['color' => 'info', 'text' => ucfirst($status), 'icon' => 'fa-file'];
        }
    }
}