<?php

namespace App\Livewire\Admin\ParcelHandlingAssistants;

use App\Models\ParcelHandlingAssistant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewParcelHandlingAssistant extends Component
{
    public ParcelHandlingAssistant $assistant;
    public $activeTab = 'profile';
    public $showEmploymentModal = false;
    public $selectedStation = '';
    public $employmentStatus = 'active';

    public function mount($id)
    {
        $this->assistant = ParcelHandlingAssistant::findOrFail($id);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getStatusBadge($status)
    {
        return match($status) {
            'active' => ['color' => 'success', 'text' => 'Active', 'icon' => 'fa-check-circle'],
            'inactive' => ['color' => 'secondary', 'text' => 'Inactive', 'icon' => 'fa-times-circle'],
            'suspended' => ['color' => 'danger', 'text' => 'Suspended', 'icon' => 'fa-ban'],
            'pending' => ['color' => 'warning', 'text' => 'Pending', 'icon' => 'fa-clock'],
            default => ['color' => 'info', 'text' => ucfirst($status), 'icon' => 'fa-question-circle']
        };
    }

    public function toggleAssistantStatus()
    {
        $newStatus = $this->assistant->status === 'active' ? 'inactive' : 'active';
        
        $this->assistant->update(['status' => $newStatus]);
        
        // Update user status if exists
        if ($this->assistant->user) {
            $this->assistant->user->update(['status' => $newStatus]);
        }
        
        session()->flash('success', "Assistant status updated to " . ucfirst($newStatus));
        $this->assistant->refresh();
    }

    public function suspendAssistant()
    {
        $this->assistant->update(['status' => 'suspended']);
        
        if ($this->assistant->user) {
            $this->assistant->user->update(['status' => 'suspended']);
        }
        
        // Suspend all active employments
        $this->assistant->employments()->where('status', 'active')->update(['status' => 'suspended']);
        
        session()->flash('warning', "Assistant has been suspended.");
        $this->assistant->refresh();
    }

    public function activateAssistant()
    {
        $this->assistant->update(['status' => 'active']);
        
        if ($this->assistant->user) {
            $this->assistant->user->update(['status' => 'active']);
        }
        
        session()->flash('success', "Assistant has been activated.");
        $this->assistant->refresh();
    }

    public function showAssignStationModal()
    {
        $this->selectedStation = '';
        $this->employmentStatus = 'active';
        $this->showEmploymentModal = true;
    }

    public function assignStation()
    {
        $this->validate([
            'selectedStation' => 'required|exists:station_partners,id',
            'employmentStatus' => 'required|in:active,suspended,pending',
        ]);

        // Check if already employed at this station
        $existingEmployment = $this->assistant->employments()
            ->where('station_partner_id', $this->selectedStation)
            ->first();

        if ($existingEmployment) {
            // Update existing employment
            $existingEmployment->update([
                'status' => $this->employmentStatus,
                'updated_at' => now(),
            ]);
            $message = "Employment updated successfully.";
        } else {
            // Create new employment
            $this->assistant->employments()->create([
                'station_partner_id' => $this->selectedStation,
                'status' => $this->employmentStatus,
                'created_by' => Auth::id(),
            ]);
            $message = "Assistant assigned to station successfully.";
        }

        session()->flash('success', $message);
        $this->showEmploymentModal = false;
        $this->assistant->refresh();
    }

    public function updateEmploymentStatus($employmentId, $status)
    {
        $employment = $this->assistant->employments()->find($employmentId);
        
        if ($employment) {
            $employment->update(['status' => $status]);
            session()->flash('success', "Employment status updated to " . ucfirst($status));
            $this->assistant->refresh();
        }
    }

    public function removeEmployment($employmentId)
    {
        $employment = $this->assistant->employments()->find($employmentId);
        
        if ($employment) {
            $stationName = $employment->stationPartner->name ?? 'Unknown Station';
            $employment->delete();
            
            session()->flash('success', "Assistant removed from {$stationName}");
            $this->assistant->refresh();
        }
    }

    public function render()
    {
        return view('livewire.admin.parcel-handling-assistants.view-parcel-handling-assistant');
    }
}