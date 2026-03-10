<?php

namespace App\Livewire\Partners\PickupAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditPickUpAndDropOffPoint extends Component
{
    public $point;
    public $point_id;

    // Form fields
    public $name;
    public $code;
    public $status = 'active';
    public $address;
    public $town_id;
    public $contact_person;
    public $contact_email;
    public $contact_phone;
    public $is_24_hours = false;
    public $opening_hours = '08:00';
    public $closing_hours = '17:00';
    public $operating_days = [];
    public $capacity;
    public $notes;

    // For loading related data
    public $towns = [];
    protected $rules = [
        'name' => 'required|string|max:255',
        
        'status' => 'required|in:active,inactive,maintenance',
        'address' => 'required|string|max:500',
        'town_id' => 'required|exists:towns,id',
        
        'contact_person' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone' => 'required|string|max:20',
        'is_24_hours' => 'boolean',
        'opening_hours' => 'required_if:is_24_hours,false|date_format:H:i',
        'closing_hours' => 'required_if:is_24_hours,false|date_format:H:i|after:opening_hours',
        'operating_days' => 'required|array|min:1',
        'operating_days.*' => 'in:Mon,Tue,Wed,Thu,Fri,Sat,Sun',
        'capacity' => 'nullable|integer|min:1',
        'notes' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'town_id.required' => 'Please select a town.',
        'town_id.exists' => 'The selected town does not exist.',
        'closing_hours.after' => 'Closing time must be after opening time.',
        'contact_email.email' => 'Please enter a valid email address.',
        'contact_phone.required' => 'Phone number is required.',
        'contact_person.required' => 'Contact person name is required.',
        'operating_days.required' => 'Please select at least one operating day.',
    ];

    public function mount($id)
    {
        $this->point_id = $id;
        $this->loadPoint();
        $this->loadTowns();
    }

    public function loadPoint()
    {
        $this->point = PickUpAndDropOffPoint::findOrFail($this->point_id);
        
        // Populate form fields
        $this->name = $this->point->name;
        $this->code = str_replace('KP-PT-', '', $this->point->code);
        $this->status = $this->point->status;
        $this->address = $this->point->address;
        $this->town_id = $this->point->town_id;
       
        $this->contact_person = $this->point->contact_person;
        $this->contact_email = $this->point->contact_email;
        $this->contact_phone = $this->point->contact_phone;
        $this->is_24_hours = $this->point->is_24_hours;
        $this->opening_hours = $this->point->opening_hours ? substr($this->point->opening_hours, 0, 5) : '08:00';
        $this->closing_hours = $this->point->closing_hours ? substr($this->point->closing_hours, 0, 5) : '17:00';
        $this->operating_days = $this->point->operating_days ? explode(',', $this->point->operating_days) : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $this->capacity = $this->point->capacity;
        $this->notes = $this->point->notes;

        // Update validation rule with actual ID
        $this->rules['code'] = 'required|string|max:50|unique:pick_up_and_drop_off_points,code,' . $this->point->id . ',id';
    }

    public function loadTowns()
    {
        $this->towns = Town::orderBy('name')->get();
    }

    public function toggleDay($day)
    {
        if (in_array($day, $this->operating_days)) {
            $this->operating_days = array_diff($this->operating_days, [$day]);
        } else {
            $this->operating_days[] = $day;
            $this->operating_days = array_unique($this->operating_days);
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Auto-validate closing hours when opening hours changes
        if ($propertyName === 'opening_hours' && $this->closing_hours) {
            $this->validateOnly('closing_hours');
        }
    }

    public function submit()
    {

    // dd($this->all());
        $this->validate();

        try {
            DB::beginTransaction();

            $this->point->update([
                'name' => $this->name,
                'status' => $this->status,
                'address' => $this->address,
                'town_id' => $this->town_id,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone' => $this->contact_phone,
                'is_24_hours' => $this->is_24_hours,
                'opening_hours' => $this->is_24_hours ? null : $this->opening_hours . ':00',
                'closing_hours' => $this->is_24_hours ? null : $this->closing_hours . ':00',
                'operating_days' => implode(',', $this->operating_days),
                'capacity' => $this->capacity,
                'notes' => $this->notes,
            ]);

            DB::commit();

            return redirect()->route('partners.pd.index')->with('success', 'Point updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update point: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->loadPoint();
    }

    public function deletePoint()
    {
        try {
            $this->point->delete();
            session()->flash('success', 'Point deleted successfully!');
            $this->dispatch('pointDeleted', $this->point_id);
            // return redirect()->route('points.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete point: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.edit-pick-up-and-drop-off-point');
    }
}