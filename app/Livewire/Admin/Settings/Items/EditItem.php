<?php

namespace App\Livewire\Admin\Settings\Items;

use App\Models\Category;
use App\Models\Item;
use App\Models\SubCategory;
use Livewire\Component;

class EditItem extends Component
{
    public ?string $name;
    public $categories = [];

    public $subCategories = [];
    public bool $status = true;
    public $category_id;
    public $sub_category_id;
    public ?Item $item;

    public function mount($id)
    {
        $this->categories = Category::where('status', true)->get();

        // $this->subCategories = SubCategory::all();
        $this->item = Item::findOrFail($id);

        $this->name = $this->item->name;
        $this->category_id = $this->item->category_id;
        $this->sub_category_id = $this->item->sub_category_id;
        $this->status = $this->item->status;
    }

    public function updatedCategoryId(int $id)
    {
        $this->subCategories = SubCategory::where('category_id', $id)->get();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:items,name,' . $this->item->id,
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'status' => 'boolean',
        ]);

        try {
            $this->item->update([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'sub_category_id' => $this->sub_category_id,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update item: ' . $e->getMessage());
            return;
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.items.edit-item');
    }
}
