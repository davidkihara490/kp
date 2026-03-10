<?php

namespace App\Livewire\Admin\Blogs\Categories;

use App\Models\BlogCategory;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateBlogCategory extends Component
{
    use WithFileUploads;

    public $name;
    public $slug;
    public $description;
    public $parent_id;
    public $order;
    public $is_active = true;
    public $meta_title;
    public $meta_description;
    public $meta_keywords = [];
    public $featured_image;
    public $temp_featured_image;
    public $keyword = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:blog_categories,name',
        'slug' => 'required|string|max:255|unique:blog_categories,slug',
        'description' => 'nullable|string|max:500',
        'parent_id' => 'nullable|exists:blog_categories,id',
        'order' => 'required|integer|min:0',
        'is_active' => 'boolean',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'meta_keywords' => 'array',
        'meta_keywords.*' => 'string|max:50',
        'featured_image' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        // Set default order to next available number
        $lastOrder = BlogCategory::where('parent_id', null)->max('order');
        $this->order = $lastOrder ? $lastOrder + 1 : 1;
    }

    public function render()
    {
        $categories = BlogCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.blogs.categories.create-blog-category', [
            'categories' => $categories,
        ]);
    }

    public function updatedName($value)
    {
        if (!$this->slug) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug($value)
    {
        $this->slug = Str::slug($value);
    }

    public function addKeyword()
    {
        if ($this->keyword && !in_array($this->keyword, $this->meta_keywords)) {
            $this->meta_keywords[] = trim($this->keyword);
            $this->keyword = '';
        }
    }

    public function removeKeyword($index)
    {
        if (isset($this->meta_keywords[$index])) {
            unset($this->meta_keywords[$index]);
            $this->meta_keywords = array_values($this->meta_keywords);
        }
    }

    public function save()
    {
        $this->validate();

        // Handle featured image upload
        $featuredImagePath = null;
        if ($this->featured_image) {
            $featuredImagePath = $this->featured_image->store('blog-categories', 'public');
        }

        $category = BlogCategory::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'featured_image' => $featuredImagePath,
        ]);

        session()->flash('success', 'Category created successfully!');
        return redirect()->route('blog-categories.index');
    }

    public function removeFeaturedImage()
    {
        $this->featured_image = null;
        $this->temp_featured_image = null;
    }

    public function updatedFeaturedImage()
    {
        $this->validate([
            'featured_image' => 'image|max:2048',
        ]);

        $this->temp_featured_image = $this->featured_image->temporaryUrl();
    }
}
