<?php

namespace App\Livewire\Partners\Dashboard;

use App\Models\Driver;
use App\Models\Parcel;
use App\Models\ParcelHandlingAssistant;
use App\Models\Partner;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public string $userType;
    public Partner $partner;
    public Driver $driver;
    public ParcelHandlingAssistant $parcelHandlingAssistant;
    public string $partnerType;
    public $loggedDriver;
    public $loggedUser;
    public $loggedUserType;

    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner
            ?? Auth::guard('partner')->user()->driver?->partner
            ?? Auth::guard('partner')->user()->parcelHandlingAssistant?->partner;
        $this->partnerType = $this->partner->partner_type;
        $this->loggedDriver = Auth::guard('partner')->user()->driver;
        $this->loggedUser = Auth::guard('partner')->user();
    }

    public function render()
    {
        $query = Parcel::query();

        if ($this->loggedUser->partner && $this->loggedUser->partner->partner_type ==  "transport") {
            $query = Parcel::where('transport_partner_id', $this->loggedUser->partner->id);
        } elseif ($this->loggedUser->partner && $this->loggedUser->partner->partner_type ==  "pickup-dropoff") {
            $query = Parcel::where('sender_partner_id', $this->loggedUser->partner->id)
                ->orWhere('delivery_partner_id', $this->loggedUser->partner->id);
        } elseif ($this->loggedUser->driver && $this->loggedUser->driver) {
            $query = Parcel::where('driver_id', $this->loggedUser->driver->id);
        } elseif ($this->loggedUser->parcelHandlingAssistant) {
            $query = Parcel::where('pha_id', $this->loggedUser->parcelHandlingAssistant->id)
                ->orWhere('delivery_partner_id', $this->loggedUser->parcelHandlingAssistant->partner->id);
            // ->orWhere('delivery_partner_id', $this->loggedUser->partner->id);
        }

        $parcels = $query->get();

        // Stats calculations
        $totalParcels = $query->count();
        $pendingParcels = Parcel::where('current_status', 'pending')->count();
        $inTransitParcels = Parcel::whereIn('current_status', ['in_transit', 'at_warehouse', 'out_for_delivery'])->count();
        $deliveredParcels = Parcel::where('current_status', 'delivered')->count();


        return view('livewire.partners.dashboard.dashboard', [
            'totalParcels'=> $totalParcels,
            'pendingParcels'=> $pendingParcels,
            'inTransitParcels'=> $inTransitParcels,
            // 'pendingParcels'=> $pendingParcels,
            ''
        ]);
    }
}
