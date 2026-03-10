<?php

namespace App\Livewire\Admin\Settings\Categories;

use App\Models\Category;
use Livewire\Component;

class EditCategory extends Component
{

    public ?string $name;
    public bool $status;
    public Category $category;

    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
        $this->name = $this->category->name;
        $this->status = $this->category->status;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'boolean',
        ]);

        try {
            $this->category->update([
                'name' => $this->name,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update categories: ' . $e->getMessage());
            return;
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.categories.edit-category');
    }
}
