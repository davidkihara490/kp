<?php

namespace App\Livewire\Admin\Settings\Items;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Items extends Component
{

    use WithPagination;
    public string $search = '';
    protected $paginationTheme = 'bootstrap';

    public $deleteId;
    public $showDeleteModal = false;

    public function confirm($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    public function delete()
    {
        $item = Item::findOrFail($this->deleteId);
        try {
            $item->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Item deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->company_route('sub-categories.index')->with(['error', 'Error deleting item :' . $th->getMessage()]);
        }
    }


    public function render()
    {
        $items = Item::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate(10);


        return view('livewire.admin.settings.items.items', [
            'items' => $items
        ]);
    }
}
