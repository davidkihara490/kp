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
        'points' => 'PickUp/DropOff Points',
        'pha' => 'Parcel Handling Assistants',
        'owners' => 'Owners',
        'drivers' => 'Drivers',
        'towns' => 'Service Towns',

    ];

    public function mount($id)
    {
        $this->partner = Partner::with([
            'owner',
            // 'drivers',
            'towns.town',
        ])->findOrFail($id);
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
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
            'verified' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    private function getStatusBadgeColor()
    {
        return match ($this->partner->verification_status) {
            'verified' => 'success',
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

    public function verifyPartner()
    {
        $this->partner->update([
            'verification_status' => 'verified',
        ]);

        session()->flash('success', 'Partner verified successfully');
    }
}
