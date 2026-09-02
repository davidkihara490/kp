<?php

namespace App\Livewire\Admin\Settings\Items;

use App\Livewire\Admin\Settings\Categories\Categories;
use App\Models\Category;
use App\Models\Item;
use App\Models\SubCategory;
use Livewire\Component;

class CreateItem extends Component
{

    public ?string $name;
    public $categories = [];
    public $subCategories = [];
    public bool $status = true;
    public $category_id;
    public $sub_category_id;

    public function mount()
    {
        $this->categories = Category::where('status', true)->get();
    }

    public function updatedCategoryId(int $id)
    {
        $this->subCategories = SubCategory::where('category_id', $id)->get();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:items,name',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'status' => 'boolean',
        ]);

        try {
            Item::create([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'sub_category_id' => $this->sub_category_id,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create item: ' . $e->getMessage());
            return;
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.items.create-item');
    }
}
