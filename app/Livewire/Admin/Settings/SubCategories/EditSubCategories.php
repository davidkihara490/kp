<?php

namespace App\Livewire\Admin\Settings\SubCategories;

use App\Models\Category;
use App\Models\SubCategory;
use Livewire\Component;


class EditSubCategories extends Component
{
        public ?string $name;
    public bool $status = true;
    public $categories = [];
    public ?int $category_id;
    public SubCategory $subCategory;

    public function mount($id)
    {
        $this->categories = Category::all();
        $this->subCategory = SubCategory::findOrFail($id);
        $this->name = $this->subCategory->name;
        $this->status = $this->subCategory->status;
        $this->category_id = $this->subCategory->category_id;
    }
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $this->subCategory->id,
            'category_id' => 'required|exists:categories,id',
            'status' => 'boolean|required',
        ]);

        try {
            $this->subCategory->update([
                'category_id' => $this->category_id,
                'name' => $this->name,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.sub-categories.index')->with('success', 'Sub Category updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update sub-category: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.sub-categories.edit-sub-categories');
    }
}
