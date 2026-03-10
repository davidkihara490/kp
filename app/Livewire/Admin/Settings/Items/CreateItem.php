<?php

namespace App\Livewire\Admin\Settings\Items;

use App\Models\Item;
use App\Models\SubCategory;
use Livewire\Component;

class CreateItem extends Component
{

    public ?string $name;
    public $subCategories = [];
    public bool $status = true;
    public $sub_category_id;

    public function mount()
    {
        $this->subCategories = SubCategory::all();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:items,name',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'status' => 'boolean',
        ]);

        try {
            Item::create([
                'name' => $this->name,
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
