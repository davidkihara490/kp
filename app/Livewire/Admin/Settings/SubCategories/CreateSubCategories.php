<?php

namespace App\Livewire\Admin\Settings\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Component;

class CreateSubCategories extends Component
{

    public ?string $name;
    public bool $status = true;
    public $categories = [];
    public ?int $category_id;

    public function mount(){
        $this->categories = Category::all();
    }
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean|required',
        ]);

        try {
            SubCategory::create([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.sub-categories.index')->with('success', 'Sub Category created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create sub-category: ' . $e->getMessage());
            return;
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.sub-categories.create-sub-categories');
    }
}
