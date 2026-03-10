<?php

namespace App\Livewire\Admin\Settings\WeightRanges;

use App\Models\Item;
use App\Models\WeightRange;
use Livewire\Component;
use Livewire\WithPagination;

class WeightRanges extends Component
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
        $weightRange = WeightRange::findOrFail($this->deleteId);
        try {
            $weightRange->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Weight Range deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.weight-ranges.index')->with(['error', 'Error deleting Weight Range :' . $th->getMessage()]);
        }
    }


    public function render()
    {
        $weightRanges = WeightRange::query()
            ->when($this->search, function ($query) {
                $query->where('label', 'like', '%' . $this->search . '%');
            })
            ->orderBy('label', 'asc')
            ->paginate(10);

        return view('livewire.admin.settings.weight-ranges.weight-ranges', [
            'weightRanges' => $weightRanges
        ]);
    }
}
