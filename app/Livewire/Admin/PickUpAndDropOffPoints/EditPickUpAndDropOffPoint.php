<?php

namespace App\Livewire\Admin\PickUpAndDropOffPoints;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Partner;
use App\Models\Town;

class EditPickUpAndDropOffPoint extends Component
{
    use WithFileUploads;

    public $point_id;
    public $partner_id;
    public $name;
    public $code;
    public $type;
    public $town_id;
    public $building;
    public $room_number;
    public $address;
    public $latitude;
    public $longitude;
    public $status;
    public $contact_person;
    public $contact_email;
    public $contact_phone_number;
    public $operating_days = [];

    public $partners = [];
    public $towns = [];

    public $original_code;

    protected $rules = [
        'partner_id' => 'required|exists:partners,id',
        'name' => 'required|string|max:255',
        'type' => 'required|string|in:warehouse,pickup-dropoff',
        'town_id' => 'required|exists:towns,id',
        'address' => 'required|string|max:500',
        'latitude' => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        'status' => 'required|in:active,inactive,pending,suspended',
        'contact_person' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
        'contact_phone_number' => 'nullable|string|max:20',
    ];

    protected $messages = [
        'partner_id.required' => 'Please select a partner.',
        'partner_id.exists' => 'Selected partner does not exist.',
        'name.required' => 'The point name is required.',
        'type.required' => 'The type is required.',
        'town_id.required' => 'Please select a town.',
        'town_id.exists' => 'Selected town does not exist.',
        'address.required' => 'The address is required.',
        'status.required' => 'Please select a status.',
        'latitude.between' => 'Latitude must be between -90 and 90.',
        'longitude.between' => 'Longitude must be between -180 and 180.',
        'contact_email.email' => 'Please enter a valid email address.',
    ];

    public function mount($id)
    {
        $this->point_id = $id;
        $this->loadPartners();
        $this->loadTowns();
        $this->loadPointData();
    }

    protected function loadPartners()
    {
        $this->partners = Partner::orderBy('company_name')->get();
    }

    protected function loadTowns()
    {
        $this->towns = Town::orderBy('name')->get();
    }

    protected function loadPointData()
    {
        $point = PickUpAndDropOffPoint::findOrFail($this->point_id);

        $this->partner_id = $point->partner_id;
        $this->name = $point->name;
        $this->type = $point->type;
        $this->town_id = $point->town_id;
        $this->address = $point->address;
        $this->latitude = $point->latitude;
        $this->longitude = $point->longitude;
        $this->status = $point->status;
        $this->contact_person = $point->contact_person;
        $this->contact_email = $point->contact_email;
        $this->contact_phone_number = $point->contact_phone_number;
    }



    public function update()
    {

        // If code hasn't changed, remove unique validation rule
        $rules = $this->rules;
        if ($this->code === $this->original_code) {
            unset($rules['code']);
        }

        $this->validate($rules);

        try {
            $point = PickUpAndDropOffPoint::findOrFail($this->point_id);

            $point->update([
                'partner_id' => $this->partner_id,
                'name' => $this->name,
                'town_id' => $this->town_id,
                'type' => $this->type,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => $this->status,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone_number' => $this->contact_phone_number,
            ]);

            session()->flash('success', 'Pick-up & Drop-off Point updated successfully!');
            return redirect()->route('admin.points.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating point: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.pick-up-and-drop-off-points.edit-pick-up-and-drop-off-point');
    }
}
