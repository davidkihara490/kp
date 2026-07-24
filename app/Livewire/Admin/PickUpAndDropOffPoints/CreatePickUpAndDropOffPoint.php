<?php

namespace App\Livewire\Admin\PickUpAndDropOffPoints;

use Livewire\Component;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Partner;
use App\Models\Town;

class CreatePickUpAndDropOffPoint extends Component
{
    public int $partner_id;
    public string $name;
    public ?string $code = null;
    public $type = "warehouse";
    public int $town_id;
    public $building;
    public $room_number;
    public $address;
    public $latitude;
    public $longitude;
    public $status = 'active';
    public $contact_person;
    public $contact_email;
    public $contact_phone_number;
    public $operating_days = [];
    public $partners = [];
    public $towns = [];
    public $notes;
    public $capacity;

    protected $rules = [
        'partner_id' => 'required|exists:partners,id',
        'type' => 'required|string|in:warehouse,pickup-dropoff',
        'town_id' => 'required|exists:towns,id',
        'address' => 'required|string|max:500',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'status' => 'required|in:active,inactive',
        'contact_person' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'contact_phone_number' => 'nullable|string|max:20',
        'capacity' => 'integer',
    ];

    protected $messages = [
        'partner_id.required' => 'Please select a partner.',
        'partner_id.exists' => 'Selected partner does not exist.',
        'name.required' => 'The point name is required.',
        'town_id.required' => 'Please select a town.',
        'town_id.exists' => 'Selected town does not exist.',
        'address.required' => 'The address is required.',
        'status.required' => 'Please select a status.',
        'latitude.between' => 'Latitude must be between -90 and 90.',
        'longitude.between' => 'Longitude must be between -180 and 180.',
        'contact_email.email' => 'Please enter a valid email address.',
        'capacity.integer' => 'Use an integer for capacity.'
    ];

    public function mount()
    {
        $this->loadPartners();
        $this->loadTowns();
    }

    protected function loadPartners()
    {
        $this->partners = Partner::orderBy('company_name')->get();
    }

    protected function loadTowns()
    {
        $this->towns = Town::orderBy('name')->get();
    }

    public function generateCode()
    {
        $latest = PickUpAndDropOffPoint::orderBy('id', 'desc')->first();
        $nextNumber = $latest ? intval(substr($latest->code, -3)) + 1 : 1;
        $this->code = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function save()
    {

        $this->validate();

        try {
            PickUpAndDropOffPoint::create([
                'partner_id' => $this->partner_id,
                'name' => $this->name,
                'type' => $this->type,
                'code' => 'KP-W-' . $this->code,
                'town_id' => $this->town_id,
                'address' => $this->address,
                'latitude' => $this->latitude ?? null,
                'longitude' => $this->longitude ?? null,
                'status' => $this->status,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone_number' => $this->contact_phone_number,
                'capacity' => $this->capacity,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Pick-up & Drop-off Point created successfully!');
            return redirect()->route('admin.points.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating point: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pick-up-and-drop-off-points.create-pick-up-and-drop-off-point');
    }
}
