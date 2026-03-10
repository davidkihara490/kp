<?php

namespace App\Livewire\Partners\PickupAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use App\Models\Station;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PickUpAndDropOffPoints extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $status = 'all';
    public string $type = 'all';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';
    
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'type' => ['except' => 'all'],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public $deleteId;
    public $showDeleteModal = false;

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        $point = PickUpAndDropOffPoint::findOrFail($this->deleteId);
        try {
            $point->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Pick-up/Drop-off point deleted successfully');
        } catch (\Throwable $th) {
            session()->flash('error', 'Error deleting point: ' . $th->getMessage());
        }
    }
    
    public function clearFilters()
    {
        $this->search = '';
        $this->status = 'all';
        $this->type = 'all';
        $this->sortField = 'name';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function updatingType()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $points = PickUpAndDropOffPoint::where('partner_id', $partner->id)
        // ->where('station_partner_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('address', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->type !== 'all', function ($query) {
                $query->where('type', $this->type);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
            
        return view('livewire.partners.pickup-and-drop-off-points.pick-up-and-drop-off-points', [
            'points' => $points,
            'activeFilters' => $this->getActiveFiltersCount()
        ]);
    }
    
    private function getActiveFiltersCount()
    {
        $count = 0;
        if (!empty($this->search)) $count++;
        if ($this->status !== 'all') $count++;
        if ($this->type !== 'all') $count++;
        return $count;
    }
}