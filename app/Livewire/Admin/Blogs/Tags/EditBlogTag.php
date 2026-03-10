<?php

namespace App\Livewire\Admin\Blogs\Tags;

use App\Models\BlogTag;
use Livewire\Component;
use Illuminate\Support\Str;

class EditBlogTag extends Component
{
    public $tag;
    public $name;
    public $slug;
    public $description;
    public $meta_title;
    public $meta_description;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:blog_tags,name,' . $this->tag->id,
            'slug' => 'required|string|max:255|unique:blog_tags,slug,' . $this->tag->id,
            'description' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    public function mount(BlogTag $tag)
    {
        $this->tag = $tag;
        $this->name = $tag->name;
        $this->slug = $tag->slug;
        $this->description = $tag->description;
        $this->meta_title = $tag->meta_title;
        $this->meta_description = $tag->meta_description;
    }

    public function render()
    {
        $popularity = $this->getPopularityBadge($this->tag->post_count);
        
        return view('livewire.admin.blogs.tags.edit-blog-tag', [
            'popularity' => $popularity,
        ]);
    }

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function update()
    {
        $this->validate();

        $this->tag->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        session()->flash('success', 'Tag updated successfully!');
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

