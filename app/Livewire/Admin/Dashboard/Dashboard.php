<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Parcel;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalParcels = Parcel::count();
        $parcelsPending = Parcel::where('current_status', Parcel::STATUS_ACCEPTED)
            ->orWhere('current_status', Parcel::STATUS_CREATED)
            ->orWhere('current_status', Parcel::STATUS_ASSIGNED)
            ->orWhere('current_status', Parcel::STATUS_WAREHOUSE)
            ->count();
        $revenueGenerated = 245000;
        $parcelsInTransit = Parcel::where('current_status', Parcel::STATUS_IN_TRANSIT)->count();


        return view('livewire.admin.dashboard.dashboard', compact('totalParcels', 'parcelsPending', 'revenueGenerated', 'parcelsInTransit'));
    }
}
