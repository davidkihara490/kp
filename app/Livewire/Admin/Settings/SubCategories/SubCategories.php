<?php

namespace App\Livewire\Admin\Settings\SubCategories;

use App\Models\SubCategory;
use Livewire\Component;
use Livewire\WithPagination;

class SubCategories extends Component
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
        $subCategory = SubCategory::findOrFail($this->deleteId);
        try {
            $subCategory->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Sub Category deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->company_route('sub-categories.index')->with(['error', 'Error deleting sub-category :' . $th->getMessage()]);
        }
    }

    public function render()
    {
        $subCategories = SubCategory::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.settings.sub-categories.sub-categories', [
            'subCategories' => $subCategories
        ]);
    }
}
