<?php

namespace App\Livewire\Admin\PickUpAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use Livewire\Component;
use App\Models\Station;
use Livewire\WithPagination;

class PickUpAndDropOffPoints extends Component
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
        $pickUpAndDropOffPoint = PickUpAndDropOffPoint::findOrFail($this->deleteId);
        try {
            $pickUpAndDropOffPoint->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Point deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.points.index')->with(['error', 'Error deleting point :' . $th->getMessage()]);
        }
    }
    public function render()
    {
        $pickUpAndDropOffPoints = PickUpAndDropOffPoint::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.admin.pick-up-and-drop-off-points.pick-up-and-drop-off-points', [
            'pickUpAndDropOffPoints' => $pickUpAndDropOffPoints
        ]);
    }
}
