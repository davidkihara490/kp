<?php

namespace App\Livewire\Partners\PickupAndDropOffPoints;

use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePickUpAndDropOffPoint extends Component
{
    public $name;
    public $code;
    public $status = 'active';
    public $address;
    public $town_id;
    public $contact_person;
    public $contact_email;
    public $contact_phone_number;
    public $is_24_hours = false;
    public $opening_hours = '08:00';
    public $closing_hours = '17:00';
    public $operating_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    public $capacity;
    public $notes;

    // For loading related data
    public $towns = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:pick_up_and_drop_off_points,code',
        'status' => 'required|in:active,inactive,maintenance',
        'address' => 'required|string|max:500',
        'town_id' => 'required|exists:towns,id',
        'contact_person' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_phone_number' => 'required|string|max:20',
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
        'contact_phone_number.required' => 'Phone number is required.',
        'contact_person.required' => 'Contact person name is required.',
        'code.unique' => 'This point code already exists. Please use a different code.',
        'operating_days.required' => 'Please select at least one operating day.',
    ];

    public function mount()
    {
        $this->loadTowns();
        $this->generateCode();
    }

    public function loadTowns()
    {
        $this->towns = Town::orderBy('name')->get();
    }

    public function generateCode()
    {
        // Generate a unique code like KP-PT-001
        $latest = PickUpAndDropOffPoint::orderBy('id', 'desc')->first();
        $nextNumber = $latest ? intval(substr($latest->code, -3)) + 1 : 1;
        $this->code = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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

        $this->validate();

        try {
            DB::beginTransaction();

            $point = PickUpAndDropOffPoint::create([
                'partner_id' => Auth::guard('partner')->user()->partner->id,
                'name' => $this->name,
                'code' => 'KP-PT-' . $this->code,
                'status' => $this->status,
                'address' => $this->address,
                'town_id' => $this->town_id,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone_number' => $this->contact_phone_number,
                'is_24_hours' => $this->is_24_hours,
                'opening_hours' => $this->is_24_hours ? null : $this->opening_hours . ':00',
                'closing_hours' => $this->is_24_hours ? null : $this->closing_hours . ':00',
                'operating_days' => implode(',', $this->operating_days),
                'capacity' => $this->capacity,
                'notes' => $this->notes,
            ]);

            DB::commit();

            return redirect()->route('partners.pd.index')->with('success', 'Pick-up and Drop-off Point created successfully!');

            // Optionally redirect
            return redirect()->route('partners.pd.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create point: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.create-pick-up-and-drop-off-point');
    }
}
