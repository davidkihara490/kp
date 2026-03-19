<?php

namespace App\Livewire\Admin\Blogs\Categories;


use App\Models\BlogCategory;
use Livewire\Component;

class ViewBlogCategory extends Component
{
    public $category;
    public $activeTab = 'overview';

    public function mount($id)
    {
        $this->category = BlogCategory::findOrFail($id);
        $this->category = $this->category->load(['parent', 'children', 'posts']);
    }

    public function render()
    {
        return view('livewire.admin.blogs.categories.view-blog-category', [
            'category' => $this->category,
            'relatedPosts' => $this->category->posts()->latest()->take(10)->get(),
            'subCategories' => $this->category->children()->orderBy('order')->get(),
        ]);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleStatus()
    {
        $this->category->update(['is_active' => !$this->category->is_active]);
        session()->flash('success', 'Category status updated successfully!');
    }
}
