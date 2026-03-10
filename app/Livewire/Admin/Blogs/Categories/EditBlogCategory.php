<?php


namespace App\Livewire\Admin\Blogs\Categories;

use App\Models\BlogCategory;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditBlogCategory extends Component
{
    use WithFileUploads;

    public $category;
    public $name;
    public $slug;
    public $description;
    public $parent_id;
    public $order;
    public $is_active;
    public $meta_title;
    public $meta_description;
    public $meta_keywords = [];
    public $featured_image;
    public $temp_featured_image;
    public $keyword = '';
    public $current_image;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $this->category->id,
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $this->category->id,
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:blog_categories,id|not_in:' . $this->category->id,
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'array',
            'meta_keywords.*' => 'string|max:50',
            'featured_image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(BlogCategory $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->order = $category->order;
        $this->is_active = $category->is_active;
        $this->meta_title = $category->meta_title;
        $this->meta_description = $category->meta_description;
        $this->meta_keywords = $category->meta_keywords ?? [];
        $this->current_image = $category->featured_image;
    }

    public function render()
    {
        $categories = BlogCategory::whereNull('parent_id')
            ->where('id', '!=', $this->category->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.blogs.categories.edit-blog-category', [
            'categories' => $categories,
        ]);
    }

    public function updatedName($value)
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

    public function update()
    {
        $this->validate();

        // Handle featured image upload
        if ($this->featured_image) {
            // Delete old image if exists
            if ($this->current_image) {
                Storage::disk('public')->delete($this->current_image);
            }
            
            $featuredImagePath = $this->featured_image->store('blog-categories', 'public');
            $this->current_image = $featuredImagePath;
        }

        $this->category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'featured_image' => $this->current_image,
        ]);

        session()->flash('success', 'Category updated successfully!');
        return redirect()->route('blog-categories.index');
    }

    public function removeFeaturedImage()
    {
        if ($this->current_image) {
            Storage::disk('public')->delete($this->current_image);
        }
        
        $this->featured_image = null;
        $this->temp_featured_image = null;
        $this->current_image = null;
        
        $this->category->update(['featured_image' => null]);
    }

    public function updatedFeaturedImage()
    {
        $this->validate([
            'featured_image' => 'image|max:2048',
        ]);
        
        $this->temp_featured_image = $this->featured_image->temporaryUrl();
    }
}