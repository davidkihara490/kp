<?php

namespace App\Livewire\Admin\Blogs\Tags;

use App\Models\BlogTag;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateBlogTag extends Component
{
    public $name;
    public $slug;
    public $description;
    public $meta_title;
    public $meta_description;

    protected $rules = [
        'name' => 'required|string|max:255|unique:blog_tags,name',
        'slug' => 'required|string|max:255|unique:blog_tags,slug',
        'description' => 'nullable|string|max:500',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
    ];

    public function render()
    {
        return view('livewire.admin.blogs.tags.create-blog-tag');
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

    public function save()
    {
        $this->validate();

        $tag = BlogTag::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'post_count' => 0,
        ]);

        session()->flash('success', 'Tag created successfully!');
        return redirect()->route('blog-tags.index');
    }

    public function generateSlug()
    {
        if ($this->name) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function generateMetaTitle()
    {
        if ($this->name) {
            $this->meta_title = "Learn " . $this->name . " - Tips, Guides & Tutorials";
        }
    }

    public function generateMetaDescription()
    {
        if ($this->name) {
            $this->meta_description = "Discover the best articles, tutorials and resources about " . 
                                     strtolower($this->name) . ". Learn from experts and improve your skills.";
        }
    }
}