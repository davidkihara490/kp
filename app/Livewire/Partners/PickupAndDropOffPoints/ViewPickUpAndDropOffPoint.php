<?php

namespace App\Livewire\Partners\PickupAndDropOffPoints;

use App\Models\Parcel;
use App\Models\PickUpAndDropOffPoint;
use Livewire\Component;
use Carbon\Carbon;

class ViewPickUpAndDropOffPoint extends Component
{
    // Related data
    public $town;
    public $station;
    public $parcels_today = 0;
    public $parcels_this_week = 0;
    public $totalParcels = 0;
    public $parcels_this_month = 0;
    public $last_activity;
    // Modal states
    public $showQrModal = false;
    public $showPrintModal = false;
    public $showDeactivateModal = false;
    public PickUpAndDropOffPoint $pickUpAndDropOffPoint;

    public function mount($id)
    {
        $this->pickUpAndDropOffPoint =  PickUpAndDropOffPoint::findOrFail($id);
        $query = Parcel::where('sender_pick_up_drop_off_point_id', $this->pickUpAndDropOffPoint)
            ->where('delivery_pick_up_drop_off_point_id', $this->pickUpAndDropOffPoint);

        $this->totalParcels = $query->count();

        // Today parcels
        $this->parcels_today = (clone $query)->whereDate('date', Carbon::today())->count();

        // Weekly parcels (current week)
        $this->parcels_this_week = (clone $query)
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();

        // Monthly parcels (current month)
        $this->parcels_this_month = (clone $query)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
    }
    public function toggleStatus()
    {
        $newStatus = $this->pickUpAndDropOffPoint->status === 'active' ? 'inactive' : 'active';

        $this->pickUpAndDropOffPoint->update(['status' => $newStatus]);
        $this->loadPoint();

        session()->flash('success', "Point {$newStatus} successfully!");
    }

    public function generateQrCode()
    {
        $this->showQrModal = true;
    }

    public function printLabel()
    {
        $this->showPrintModal = true;
    }

    public function confirmDeactivate()
    {
        $this->showDeactivateModal = true;
    }

    public function deactivatePoint()
    {
        $this->pickUpAndDropOffPoint->update(['status' => 'inactive']);
        $this->loadPoint();
        $this->showDeactivateModal = false;

        session()->flash('success', 'Point deactivated successfully!');
    }

    public function getStatusBadgeClass()
    {
        return match ($this->pickUpAndDropOffPoint->status) {
            'active' => 'status-active',
            'inactive' => 'status-inactive',
            'maintenance' => 'status-maintenance',
            default => 'status-inactive'
        };
    }

    public function getTypeBadgeClass()
    {
        return match ($this->pickUpAndDropOffPoint->type) {
            'pickup' => 'badge-info',
            'dropoff' => 'badge-warning',
            'both' => 'badge-primary',
            default => 'badge-secondary'
        };
    }

    public function getTypeIcon()
    {
        return match ($this->pickUpAndDropOffPoint->type) {
            'pickup' => 'bi-box-arrow-in-up',
            'dropoff' => 'bi-box-arrow-down',
            'both' => 'bi-arrows-exchange',
            default => 'bi-geo-alt'
        };
    }


    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.view-pick-up-and-drop-off-point');
    }
}
