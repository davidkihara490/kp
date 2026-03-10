<?php

namespace App\Livewire\Admin\Fleets;

use Livewire\Component;
use App\Models\Fleet;

class ViewFleet extends Component
{
    public Fleet $fleet;
    
    public $stats = [
        'total_trips' => 0,
        'total_drivers' => 0,
        'utilization_rate' => 0,
    ];

    public function mount($id)
    {
        $this->fleet = Fleet::findOrFail($id);
        
        $this->calculateStats();
    }

    private function calculateStats()
    {
        // $this->stats['total_trips'] = $this->fleet->trips_count ?? $this->fleet->trips()->count();
        // $this->stats['total_drivers'] = $this->fleet->drivers_count ?? $this->fleet->drivers()->count();
        
        // // Calculate utilization (simple example)
        // $this->stats['utilization_rate'] = $this->fleet->is_available ? 85 : 0;

        $this->stats['total_trips'] = 000000;
        $this->stats['total_drivers'] = 00000;
        
        // Calculate utilization (simple example)
        $this->stats['utilization_rate'] = $this->fleet->is_available ? 85 : 0;

    }

    public function getStatusBadge()
    {
        return [
            'color' => $this->fleet->status_color,
            'icon' => $this->fleet->status_icon,
        ];
    }

    public function formatDate($date)
    {
        return $date ? $date->format('M d, Y') : 'N/A';
    }

    public function isDateValid($date)
    {
        return $date && $date->isFuture();
    }

    public function getDateColor($date)
    {
        if (!$date) return 'secondary';
        
        if ($date->isPast()) {
            return 'danger';
        } elseif ($date->diffInDays(now()) <= 30) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    public function toggleAvailability()
    {
        try {
            $this->fleet->update([
                'is_available' => !$this->fleet->is_available,
                'status' => $this->fleet->is_available ? 'maintenance' : 'active'
            ]);
            
            session()->flash('success', 'Fleet availability updated successfully!');
            $this->fleet->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update availability: ' . $e->getMessage());
        }
    }

    public function toggleStatus()
    {
        try {
            $newStatus = $this->fleet->status === 'active' ? 'maintenance' : 'active';
            $newAvailability = $newStatus === 'active';
            
            $this->fleet->update([
                'status' => $newStatus,
                'is_available' => $newAvailability
            ]);
            
            session()->flash('success', 'Fleet status updated successfully!');
            $this->fleet->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $registration = $this->fleet->registration_number;
            $this->fleet->delete();
            
            session()->flash('success', "Fleet '{$registration}' deleted successfully!");
            return redirect()->route('admin.fleets.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete fleet: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        $this->dispatch('confirm-delete', [
            'title' => 'Delete Fleet',
            'message' => "Are you sure you want to delete fleet '{$this->fleet->registration_number}'? This action cannot be undone.",
            'id' => $this->fleet->id,
        ]);
    }
    public function render()
    {
        return view('livewire.admin.fleets.view-fleet');
    }
}
