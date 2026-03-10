<?php

namespace App\Livewire\Admin\Settings\Categories;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{

    public ?string $name;
    public bool $status = true;
    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'status' => 'boolean|required',
        ]);

        try {
            Category::create([
                'name' => $this->name,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create category: ' . $e->getMessage());
            return;
        }
    }
    public function render()
    {
        return view('livewire.admin.settings.categories.create-category');
    }
}
