<?php

namespace App\Livewire\Admin\Partners;

use App\Models\Partner;
use Livewire\Component;

class ViewPartner extends Component
{
    public Partner $partner;
    public $activeTab = 'overview';
    public $showDeleteModal = false;
    public $tabs = [
        'overview' => 'Overview',
        'details' => 'Company Details',
        'fleet' => 'Fleet & Operations',
        'capacity' => 'Capacity & Coverage',
        'documents' => 'Documents',
        'owners' => 'Owners',
        'drivers' => 'Drivers',
        'towns' => 'Service Towns',
    ];

    public function mount($id)
    {
        $this->partner = Partner::findOrFail($id);
    }

    public function verifyPartner(){
        $this->partner->update([
            'verification_status' => 'active'
        ]);
        session()->flash('success', 'Partner verified successfully');
    }

    public function render()
    {
        return view('livewire.admin.partners.view-partner', [
            'verificationBadgeColor' => $this->getVerificationBadgeColor(),
            'statusBadgeColor' => $this->getStatusBadgeColor(),
            'partnerTypeIcon' => $this->getPartnerTypeIcon(),
            'partnerTypeColor' => $this->getPartnerTypeColor(),
        ]);
    }

    private function getVerificationBadgeColor()
    {
        return match ($this->partner->verification_status) {
            'active' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    private function getStatusBadgeColor()
    {
        return match ($this->partner->sverification_tatus) {
            'active' => 'success',
            'pending' => 'warning',
            'suspended' => 'danger',
            'inactive' => 'secondary',
            default => 'info'
        };
    }

    private function getPartnerTypeIcon()
    {
        return match ($this->partner->partner_type) {
            'transport' => 'truck',
            'station' => 'store',
            'pha' => 'box',
            'driver' => 'user-tie',
            default => 'user'
        };
    }

    private function getPartnerTypeColor()
    {
        return match ($this->partner->partner_type) {
            'transport' => 'primary',
            'station' => 'success',
            'pha' => 'info',
            'driver' => 'warning',
            default => 'secondary'
        };
    }
}
