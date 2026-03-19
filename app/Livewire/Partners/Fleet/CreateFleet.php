<?php

namespace App\Livewire\Partners\Fleet;

use App\Models\Fleet;
use App\Models\TransportPartner;
use App\Models\Driver;
use App\Models\FleetOwner;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Validation\Rule;

class CreateFleet extends Component
{
    public $registration_number;
    public $make;
    public $model;
    public $vehicle_type = 'van';
    public $color;
    public $transport_partner_id;
    public $current_driver_id;
    public $status = 'active';
    public $is_available = true;
    public $odometer_reading = 0;
    public $registration_expiry;
    public $insurance_expiry;
    public $last_service_date;
    public $next_service_date;
    public $year;
    public $capacity;
    public $fuel_type = 'diesel';
    public $notes;
    // For form
    public $transportPartners = [];
    public $drivers = [];

    public $loggedInUser;
    // Use the rules() method instead of $rules property
    public function rules()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        return [
            'registration_number' => 'required|string|unique:fleets,registration_number',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'vehicle_type' => 'required|in:van,truck,pickup,motorcycle,car',
            'color' => 'nullable|string|max:50',

            'transport_partner_id' => 'nullable|exists:transport_partners,id',
            'current_driver_id' => 'nullable|exists:drivers,id',

            'status' => 'required|in:active,maintenance,inactive,accident',
            'is_available' => 'boolean',

            'odometer_reading' => 'required|numeric|min:0',

            'registration_expiry' => 'nullable|date',
            'insurance_expiry' => 'nullable|date',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',

            'year' => 'nullable|integer|min:1900|max:' . $nextYear,
            'capacity' => 'nullable|numeric|min:0',
            'fuel_type' => 'required|in:petrol,diesel,electric,hybrid',

            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'registration_number.unique' => 'This registration number is already registered.',
        'year.max' => 'Year cannot be in the future.',
    ];

    public function mount()
    {
        $this->loggedInUser = Auth::guard('partner')->user();
        $this->loadFormData();

        // Set default dates
        $this->registration_expiry = now()->addYear()->format('Y-m-d');
        $this->insurance_expiry = now()->addYear()->format('Y-m-d');
        $this->last_service_date = now()->format('Y-m-d');
        $this->next_service_date = now()->addMonths(3)->format('Y-m-d');
        $this->year = date('Y') - 1;
    }

    public function loadFormData()
    {
        $this->transportPartners = [];

        $this->drivers = Auth::guard('partner')->user()->drivers ?? [];

        // $this->drivers = Driver::where('status', 'active')
        //     ->orderBy('first_name')
        //     ->get()
        //     ->map(function ($driver) {
        //         return [
        //             'id' => $driver->id,
        //             'name' => "{$driver->first_name} {$driver->last_name} ({$driver->driver_id})"
        //         ];
        //     });
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $fleet = Fleet::create([
                'partner_id' => Auth::guard('partner')->user()->partner->id,
                'registration_number' => $this->registration_number,
                'make' => $this->make,
                'model' => $this->model,
                'vehicle_type' => $this->vehicle_type,
                'color' => $this->color,

                'transport_partner_id' => $this->transport_partner_id,
                'current_driver_id' => $this->current_driver_id,

                'status' => $this->status,
                'is_available' => $this->is_available,

                'odometer_reading' => $this->odometer_reading,

                'registration_expiry' => $this->registration_expiry,
                'insurance_expiry' => $this->insurance_expiry,
                'last_service_date' => $this->last_service_date,
                'next_service_date' => $this->next_service_date,

                'year' => $this->year,
                'capacity' => $this->capacity,
                'fuel_type' => $this->fuel_type,

                'notes' => $this->notes,
            ]);

            // if ($this->loggedInUser->i_am_driver) {
            // }


            DB::commit();

            return redirect()->route('partners.fleet.index')->with('success', 'Fleet created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create fleet: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.fleet.create-fleet');
    }
}
