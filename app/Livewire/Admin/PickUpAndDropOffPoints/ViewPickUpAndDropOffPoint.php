<?php

namespace App\Livewire\Admin\PickUpAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use Livewire\Component;

class ViewPickUpAndDropOffPoint extends Component
{
    public PickUpAndDropOffPoint $pickUpAndDropOffPoint;
    
    // Statistics
    public $parcelsCount = 0;
    public $todayParcels = 0;
    public $weeklyParcels = 0;
    public $monthlyParcels = 0;

    public function mount($id)
    {
        $this->pickUpAndDropOffPoint = PickUpAndDropOffPoint::findOrFail($id);
    }


    // Helper methods for the component
    public function getStatusColor()
    {
        return match($this->pickUpAndDropOffPoint->status) {
            'active' => 'success',
            'inactive' => 'danger',
            'maintenance' => 'warning',
            'planned' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusIcon()
    {
        return match($this->pickUpAndDropOffPoint->status) {
            'active' => 'fa-check-circle',
            'inactive' => 'fa-times-circle',
            'maintenance' => 'fa-tools',
            'planned' => 'fa-calendar',
            default => 'fa-question-circle',
        };
    }

    public function formatTime($time)
    {
        if (!$time) return 'N/A';
        
        try {
            return \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('h:i A');
        } catch (\Exception $e) {
            return $time;
        }
    }

    public function isCurrentlyOpen()
    {
        try {
            $opening = \Carbon\Carbon::createFromFormat('H:i:s', $this->pickUpAndDropOffPoint->opening_hours ?? '00:00:00');
            $closing = \Carbon\Carbon::createFromFormat('H:i:s', $this->pickUpAndDropOffPoint->closing_hours ?? '00:00:00');
            return now()->between($opening, $closing);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getOperatingHoursProgress()
    {
        try {
            $opening = \Carbon\Carbon::createFromFormat('H:i:s', $this->pickUpAndDropOffPoint->opening_hours ?? '00:00:00');
            $closing = \Carbon\Carbon::createFromFormat('H:i:s', $this->pickUpAndDropOffPoint->closing_hours ?? '00:00:00');
            $totalHours = $opening->diffInHours($closing);
            
            if ($totalHours == 0) return 0;
            
            $currentHour = now()->hour;
            return (($currentHour - $opening->hour) / $totalHours) * 100;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function delete()
    {
        try {
            $pickUpAndDropOffPointName = $this->pickUpAndDropOffPoint->name;
            $this->pickUpAndDropOffPoint->delete();
            
            session()->flash('success', "Station  deleted successfully!");
            return redirect()->route('admin.points.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete station: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        $this->dispatch('confirm-delete', [
            'title' => 'Delete Station',
            'message' => "Are you sure you want to delete station '{$this->pickUpAndDropOffPoint->name}'? This action cannot be undone.",
            'id' => $this->pickUpAndDropOffPoint->id,
        ]);
    }

    public function toggleStatus()
    {
        try {
            $this->pickUpAndDropOffPoint->update([
                'status' => $this->pickUpAndDropOffPoint->status === 'active' ? 'inactive' : 'active'
            ]);
            
            session()->flash('success', 'Station status updated successfully!');
            $this->pickUpAndDropOffPoint->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pick-up-and-drop-off-points.view-pick-up-and-drop-off-point');
    }
}
