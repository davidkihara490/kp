<?php

namespace App\Livewire\Admin\Parcels;

use App\Models\Parcel;
use App\Models\ParcelStatus;
use App\Models\ParcelPickUp;
use App\Models\User;
use App\Models\Driver;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\TransportPartner;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewParcel extends Component
{
    public Parcel $parcel;
    public $activeTab = 'details';
    public $showUpdateStatusModal = false;
    public $showPickupModal = false;
    public $showOtpModal = false;
    public $showTransportPartnerModal = false;
    public $newStatus = '';
    public $statusNotes = '';
    public $otp = '';
    public $selectedTransportPartnerId = null;

    // Pickup form
    public $pickupPersonType = 'owner';
    public $pickupPersonName = '';
    public $pickupPersonPhone = '';
    public $pickupPersonIdNumber = '';
    public $pickupPersonRelationship = '';
    public $pickupReason = '';
    public $pickupNotes = '';

    protected $rules = [
        'newStatus' => 'required|string',
        'statusNotes' => 'nullable|string|max:500',
    ];

    protected $pickupRules = [
        'pickupPersonType' => 'required|in:owner,other',
        'pickupPersonName' => 'required|string|max:255',
        'pickupPersonPhone' => 'required|string|max:20',
        'pickupPersonIdNumber' => 'required_if:pickupPersonType,other|nullable|string|max:20',
        'pickupPersonRelationship' => 'required_if:pickupPersonType,other|nullable|string|max:100',
        'pickupReason' => 'nullable|string|max:500',
        'pickupNotes' => 'nullable|string|max:500',
    ];

    public function mount($id)
    {
        $this->parcel = Parcel::with([
            'customer',
            'senderTown',
            'receiverTown',
            'senderPartner',
            'transportPartner',
            'driver',
            'parcelHandlingAssistant',
            'senderPickUpDropOffPoint',
            'deliveryStation',
            'payments',
            'statuses' => function ($query) {
                $query->with(['changer', 'driver'])->latest();
            },
            'parcelPickUp' => function ($query) {
                $query->with('verifier');
            }
        ])->findOrFail($id);
    }

    public function getStatusBadge($status)
    {
        $badges = [
            'pending' => ['color' => 'secondary', 'icon' => 'fa-clock'],
            'accepted' => ['color' => 'info', 'icon' => 'fa-check-circle'],
            'assigned' => ['color' => 'warning', 'icon' => 'fa-user-check'],
            'in_transit' => ['color' => 'primary', 'icon' => 'fa-truck'],
            'warehouse' => ['color' => 'info', 'icon' => 'fa-warehouse'],
            'arrived_at_destination' => ['color' => 'success', 'icon' => 'fa-map-marker-alt'],
            'picked' => ['color' => 'success', 'icon' => 'fa-box-open'],
            'delivered' => ['color' => 'success', 'icon' => 'fa-check-circle'],
            'failed' => ['color' => 'danger', 'icon' => 'fa-times-circle'],
            'returned' => ['color' => 'warning', 'icon' => 'fa-undo-alt'],
        ];

        return $badges[$status] ?? ['color' => 'secondary', 'icon' => 'fa-question-circle'];
    }

    public function getPaymentStatusColor($status)
    {
        return match ($status) {
            'paid' => 'success',
            'pending' => 'warning',
            'failed' => 'danger',
            'refunded' => 'info',
            default => 'secondary',
        };
    }

    public function generateOtp()
    {
        try {
            $otp = $this->parcel->generateDeliveryOtp();
            $this->otp = $otp;
            $this->showOtpModal = true;

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'OTP generated successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to generate OTP: ' . $e->getMessage()
            ]);
        }
    }

    public function updateStatus()
    {
        $this->validate();

        try {
            $changedByType = Auth::user()->user_type ?? 'admin';

            $this->parcel->updateParcelStatus(
                $this->newStatus,
                Auth::id(),
                $changedByType,
                $this->statusNotes,
                $this->parcel->driver_id,
                null
            );

            $this->reset(['newStatus', 'statusNotes']);
            $this->showUpdateStatusModal = false;

            $this->parcel->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Parcel status updated successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to update status: ' . $e->getMessage()
            ]);
        }
    }

    public function recordPickup()
    {
        $this->validate($this->pickupRules);

        try {
            ParcelPickUp::create([
                'parcel_id' => $this->parcel->id,
                'pickup_person_type' => $this->pickupPersonType,
                'pickup_person_name' => $this->pickupPersonName,
                'pickup_person_phone' => $this->pickupPersonPhone,
                'pickup_person_id_number' => $this->pickupPersonIdNumber,
                'pickup_person_relationship' => $this->pickupPersonRelationship,
                'pickup_reason' => $this->pickupReason,
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'notes' => $this->pickupNotes,
            ]);

            // Update parcel status to picked
            $this->parcel->updateParcelStatus(
                'picked',
                Auth::id(),
                Auth::user()->user_type ?? 'admin',
                'Parcel picked up by ' . $this->pickupPersonName,
                $this->parcel->driver_id,
                null
            );

            $this->reset([
                'pickupPersonType',
                'pickupPersonName',
                'pickupPersonPhone',
                'pickupPersonIdNumber',
                'pickupPersonRelationship',
                'pickupReason',
                'pickupNotes'
            ]);
            $this->showPickupModal = false;

            $this->parcel->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Pickup recorded successfully!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Failed to record pickup: ' . $e->getMessage()
            ]);
        }
    }

    public function assignToDriver()
    {
        return redirect()->route('admin.parcels.assign', $this->parcel->id);
    }

    public function assignTransportPartner()
    {
        $this->showTransportPartnerModal = true;
    }

    public function updateSelectedTransportPartnerId($value)
    {
        $this->selectedTransportPartnerId = $value;
    }

    public function saveTransportPartnerAssignment()
    {
        if (!$this->selectedTransportPartnerId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select a transport partner'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $transportPartner = Partner::findOrFail($this->selectedTransportPartnerId);

            $this->parcel->updateParcelStatus(
                Parcel::STATUS_ACCEPTED,
                $this->selectedTransportPartnerId,
                'transport',
                'Paercel accepted by admin and assigned to transport partner: ' . $transportPartner->company_name,

                // 'Parcel accepted with ' . ($this->delivery_option === 'warehouse' ? 'warehouse' : 'final destination') . ' delivery. Payout: KES ' . $this->calculated_payout,
                null,
                $this->parcel->generateDeliveryOtp(),
            );

            // Add a status update for transport partner assignment
            $this->parcel->updateParcelStatus(
                $this->parcel->current_status,
                Auth::id(),
                Auth::user()->user_type ?? 'admin',
                'Assigned to transport partner: ' . $transportPartner->company_name,
                $this->parcel->driver_id,
                null
            );

            $this->parcel->current_status = Parcel::STATUS_ACCEPTED;
            $this->parcel->save();

            $this->parcel->parcelPayout()->where('type', 'pickup-dropoff')->whereNotNull('origin_id')->update([
                'status' => 'approved'
            ]);

            $this->showTransportPartnerModal = false;
            $this->selectedTransportPartnerId = null;

            $this->parcel->refresh();

            DB::commit();

            session()->flash('success', 'Transport partner assigned successfully!');
        } catch (\Exception $e) {

            DB::rollback();
            session()->flash('error', 'Failed to assign transport partner: ' . $e->getMessage());
        }
    }

    public function printLabel()
    {
        $this->dispatch('print-label', [
            'parcel_id' => $this->parcel->id
        ]);
    }

    public function render()
    {
        $statusOptions = [
            'pending' => 'Pending',
            'accepted' => 'Accept Parcel',
            'assigned' => 'Assign to Driver',
            'in_transit' => 'Mark In Transit',
            'warehouse' => 'Arrived at Warehouse',
            'arrived_at_destination' => 'Arrived at Destination',
            'picked' => 'Picked Up',
            'delivered' => 'Delivered',
            'failed' => 'Failed Delivery',
            'returned' => 'Return to Sender',
        ];

        // Get available transport partners with their towns
        $transportPartners = Partner::where('partner_type', 'transport')->with(['towns'])->get();

        return view('livewire.admin.parcels.view-parcel', [
            'statusOptions' => $statusOptions,
            'transportPartners' => $transportPartners,
        ]);
    }
}
