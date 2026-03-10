<?php

namespace App\Livewire\Partners\PickupAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use App\Models\Station;
use Livewire\Component;

class ViewPickUpAndDropOffPoint extends Component
{
    public $point;
    public $point_id;
    // Related data
    public $town;
    public $station;
    public $parcels_today = 0;
    public $parcels_this_week = 0;
    public $last_activity;
    // Modal states
    public $showQrModal = false;
    public $showPrintModal = false;
    public $showDeactivateModal = false;

    public function mount($id)
    {
        $this->point_id = $id;
        $this->loadPoint();
        $this->loadStats();
    }

    public function loadPoint()
    {
        $this->point = PickUpAndDropOffPoint::with(['town'])
            ->findOrFail($this->point_id);
            
        $this->town = $this->point->town;
    }

    public function loadStats()
    {
        // Load point statistics
        // You can implement actual statistics based on your business logic
        $this->parcels_today = rand(5, 50);
        $this->parcels_this_week = rand(30, 200);
        $this->last_activity = now()->subHours(rand(1, 12));
    }

    public function toggleStatus()
    {
        $newStatus = $this->point->status === 'active' ? 'inactive' : 'active';
        
        $this->point->update(['status' => $newStatus]);
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
        $this->point->update(['status' => 'inactive']);
        $this->loadPoint();
        $this->showDeactivateModal = false;
        
        session()->flash('success', 'Point deactivated successfully!');
    }

    public function getStatusBadgeClass()
    {
        return match($this->point->status) {
            'active' => 'status-active',
            'inactive' => 'status-inactive',
            'maintenance' => 'status-maintenance',
            default => 'status-inactive'
        };
    }

    public function getTypeBadgeClass()
    {
        return match($this->point->type) {
            'pickup' => 'badge-info',
            'dropoff' => 'badge-warning',
            'both' => 'badge-primary',
            default => 'badge-secondary'
        };
    }

    public function getTypeIcon()
    {
        return match($this->point->type) {
            'pickup' => 'bi-box-arrow-in-up',
            'dropoff' => 'bi-box-arrow-down',
            'both' => 'bi-arrows-exchange',
            default => 'bi-geo-alt'
        };
    }

    public function getOperatingDays()
    {
        return $this->point->operating_days 
            ? explode(',', $this->point->operating_days)
            : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    }

    public function getOperatingHours()
    {
        if ($this->point->is_24_hours) {
            return '24/7';
        }
        
        if ($this->point->opening_hours && $this->point->closing_hours) {
            $opening = substr($this->point->opening_hours, 0, 5);
            $closing = substr($this->point->closing_hours, 0, 5);
            return "{$opening} - {$closing}";
        }
        
        return 'Not specified';
    }

    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.view-pick-up-and-drop-off-point');
    }
}