<?php

namespace App\Livewire\Admin\PickUpAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use Livewire\Component;
use App\Models\Town;
use App\Models\Partner;
use Livewire\WithPagination;

class PickUpAndDropOffPoints extends Component
{
    use WithPagination;

    public string $search = '';
    public $selectedTown = '';
    public $selectedPartner = '';
    public $selectedStatus = '';
    public $minParcels = '';
    public $maxParcels = '';
    public $showFilters = false;

    protected $paginationTheme = 'bootstrap';

    public $deleteId;
    public $showDeleteModal = false;
    public $stationToDeleteName = '';

    public function confirm($id)
    {
        $pickUpAndDropOffPoint = PickUpAndDropOffPoint::findOrFail($id);
        $this->deleteId = $id;
        $this->stationToDeleteName = $pickUpAndDropOffPoint->name;
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
            session()->flash('error', 'Error deleting point: ' . $th->getMessage());
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedTown = '';
        $this->selectedPartner = '';
        $this->selectedStatus = '';
        $this->minParcels = '';
        $this->maxParcels = '';
        $this->resetPage();
    }

    public function applyFilters()
    {
        $this->resetPage();
        $this->showFilters = false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedTown()
    {
        $this->resetPage();
    }

    public function updatedSelectedPartner()
    {
        $this->resetPage();
    }

    public function updatedSelectedStatus()
    {
        $this->resetPage();
    }

    public function updatedMinParcels()
    {
        $this->resetPage();
    }

    public function updatedMaxParcels()
    {
        $this->resetPage();
    }

    public function render()
    {
        $pickUpAndDropOffPoints = PickUpAndDropOffPoint::query()
            ->with(['town', 'partner'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount([
                'senderParcels as sender_parcels_count',
                'deliveryParcels as delivery_parcels_count'
            ])
            ->when($this->selectedTown, function ($query) {
                $query->where('town_id', $this->selectedTown);
            })
            ->when($this->selectedPartner, function ($query) {
                $query->where('partner_id', $this->selectedPartner);
            })
            ->when($this->selectedStatus, function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when($this->minParcels, function ($query) {
                $query->havingRaw(
                    '(sender_parcels_count + delivery_parcels_count) >= ?',
                    [(int) $this->minParcels]
                );
            })
            ->when($this->maxParcels, function ($query) {
                $query->havingRaw(
                    '(sender_parcels_count + delivery_parcels_count) <= ?',
                    [(int) $this->maxParcels]
                );
            })
            ->orderBy('name')
            ->paginate(10);

        // Get filter options
        $towns = Town::orderBy('name')->get();
        $partners = Partner::orderBy('company_name')->get();
        $statuses = ['active', 'inactive', 'pending', 'suspended'];

        return view('livewire.admin.pick-up-and-drop-off-points.pick-up-and-drop-off-points', [
            'pickUpAndDropOffPoints' => $pickUpAndDropOffPoints,
            'towns' => $towns,
            'partners' => $partners,
            'statuses' => $statuses,
        ]);
    }
}
