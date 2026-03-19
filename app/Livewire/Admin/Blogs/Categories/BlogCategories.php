<?php

namespace App\Livewire\Admin\Blogs\Categories;

use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithPagination;

class BlogCategories extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $statusFilter = '';
    public $parentFilter = '';
    public $sortField = 'order';
    public $sortDirection = 'asc';
    public $showDeleteModal = false;
    public $categoryToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'parentFilter' => ['except' => ''],
        'sortField' => ['except' => 'order'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function render()
    {
        $query = BlogCategory::query()
            ->with(['parent', 'children', 'posts'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->when($this->parentFilter, function ($query) {
                if ($this->parentFilter === 'none') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $this->parentFilter);
                }
            });

        $categories = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.blogs.categories.blog-categories', [
            'categories' => $categories,
            'parents' => BlogCategory::whereNull('parent_id')->orderBy('name')->get(),
            'statuses' => ['all' => 'All', 'active' => 'Active', 'inactive' => 'Inactive'],
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

    public function confirmDelete($categoryId)
    {
        $this->categoryToDelete = BlogCategory::find($categoryId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->categoryToDelete) {
            // Check if category has children
            if ($this->categoryToDelete->children()->count() > 0) {
                session()->flash('error', 'Cannot delete category with sub-categories. Please move or delete sub-categories first.');
                $this->showDeleteModal = false;
                $this->categoryToDelete = null;
                return;
            }

            // Check if category has posts
            if ($this->categoryToDelete->posts()->count() > 0) {
                session()->flash('error', 'Cannot delete category that has blog posts. Please reassign posts first.');
                $this->showDeleteModal = false;
                $this->categoryToDelete = null;
                return;
            }

            $this->categoryToDelete->delete();
            session()->flash('success', 'Category deleted successfully.');

            $this->showDeleteModal = false;
            $this->categoryToDelete = null;
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->parentFilter = '';
        $this->sortField = 'order';
        $this->sortDirection = 'asc';
    }

    public function toggleStatus($categoryId)
    {
        $category = BlogCategory::find($categoryId);
        if ($category) {
            $category->update(['is_active' => !$category->is_active]);
            session()->flash('success', 'Category status updated successfully.');
        }
    }

    public function moveUp($categoryId)
    {
        $category = BlogCategory::find($categoryId);
        if ($category) {
            $previous = BlogCategory::where('parent_id', $category->parent_id)
                ->where('order', '<', $category->order)
                ->orderBy('order', 'desc')
                ->first();

            if ($previous) {
                $temp = $category->order;
                $category->update(['order' => $previous->order]);
                $previous->update(['order' => $temp]);
            }
        }
    }

    public function moveDown($categoryId)
    {
        $category = BlogCategory::find($categoryId);
        if ($category) {
            $next = BlogCategory::where('parent_id', $category->parent_id)
                ->where('order', '>', $category->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($next) {
                $temp = $category->order;
                $category->update(['order' => $next->order]);
                $next->update(['order' => $temp]);
            }
        }
    }
}
