<?php

namespace App\Livewire\Admin\Drivers;

use App\Models\Driver;
use Livewire\Component;

class ViewDriver extends Component
{
    public Driver $driver;
    
    public $stats = [
        'total_trips' => 0,
        'total_deliveries' => 0,
        'success_rate' => 0,
        'average_rating' => 0,
    ];

    public function mount($id)
    {
        $this->driver = Driver::findOrFail($id);
        
        $this->calculateStats();
    }

    private function calculateStats()
    {
        // $this->stats['total_trips'] = $this->driver->trips_count ?? $this->driver->trips()->count();
        // $this->stats['total_deliveries'] = $this->driver->total_deliveries;
        
        // $totalDeliveries = $this->driver->successful_deliveries + $this->driver->failed_deliveries + $this->driver->cancelled_deliveries;
        // $this->stats['success_rate'] = $totalDeliveries > 0 
        //     ? ($this->driver->successful_deliveries / $totalDeliveries) * 100 
        //     : 0;
            
        // $this->stats['average_rating'] = $this->driver->rating ?? 0;



         $this->stats['total_trips'] = 0000000000000;
        $this->stats['total_deliveries'] = 0000000000000;
        
        $totalDeliveries = 0000000000000000;
        $this->stats['success_rate'] = 000000000000000000000;
            
        $this->stats['average_rating'] = 00000000000000000000;


    }

    public function getStatusBadge()
    {
        return $this->driver->getStatusBadge();
    }

    public function getLicenseValidityColor()
    {
        return $this->driver->getLicenseValidityColor();
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

    public function getAge()
    {
        return $this->driver->date_of_birth ? $this->driver->date_of_birth->age : 'N/A';
    }

    public function getEmploymentDuration()
    {
        return $this->driver->employment_date ? $this->driver->employment_date->diffForHumans() : 'N/A';
    }

    public function toggleAvailability()
    {
        try {
            $this->driver->update([
                'is_available' => !$this->driver->is_available
            ]);
            
            session()->flash('success', 'Driver availability updated successfully!');
            $this->driver->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update availability: ' . $e->getMessage());
        }
    }

    public function toggleStatus()
    {
        try {
            $newStatus = $this->driver->status === 'active' ? 'inactive' : 'active';
            
            $this->driver->update([
                'status' => $newStatus,
                'is_available' => $newStatus === 'active'
            ]);
            
            session()->flash('success', 'Driver status updated successfully!');
            $this->driver->refresh();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $name = $this->driver->full_name;
            $this->driver->delete();
            
            session()->flash('success', "Driver '{$name}' deleted successfully!");
            return redirect()->route('admin.drivers.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    public function confirmDelete()
    {
        $this->dispatch('confirm-delete', [
            'title' => 'Delete Driver',
            'message' => "Are you sure you want to delete driver '{$this->driver->full_name}'? This action cannot be undone.",
            'id' => $this->driver->id,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.drivers.view-driver');
    }
}