<?php

namespace App\Livewire\Admin\Settings\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
class Categories extends Component
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
        $category = Category::findOrFail($this->deleteId);
        try {
            $category->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Category deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('categories.index')->with(['error', 'Error deleting category :' . $th->getMessage()]);
        }
    }
    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.admin.settings.categories.categories', [
            'categories' => $categories
        ]);
    }
}