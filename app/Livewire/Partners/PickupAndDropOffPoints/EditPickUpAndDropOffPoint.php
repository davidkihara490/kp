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
    public $contact_phone_number;
    public $is_24_hours = false;
    public $operating_hours = [];
    public $capacity;
    public $notes;

    // For loading related data
    public $towns = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,maintenance',
            'address' => 'required|string|max:500',
            'town_id' => 'required|exists:towns,id',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone_number' => 'required|string|max:20',
            'is_24_hours' => 'boolean',
            'capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ];

        // Add validation rules for operating hours when not 24/7
        if (!$this->is_24_hours) {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                $rules["operating_hours.{$day}.opening"] = [
                    'nullable',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) use ($day) {
                        if (!$this->operating_hours[$day]['closed'] && empty($value)) {
                            $fail("Opening time for {$day} is required when the day is not closed.");
                        }
                    },
                ];
                
                $rules["operating_hours.{$day}.closing"] = [
                    'nullable',
                    'date_format:H:i',
                    function ($attribute, $value, $fail) use ($day) {
                        if (!$this->operating_hours[$day]['closed'] && empty($value)) {
                            $fail("Closing time for {$day} is required when the day is not closed.");
                        }
                        if (!$this->operating_hours[$day]['closed'] && 
                            !empty($this->operating_hours[$day]['opening']) && 
                            !empty($value) && 
                            $this->operating_hours[$day]['opening'] >= $value) {
                            $fail("Closing time for {$day} must be after opening time.");
                        }
                    },
                ];
                
                $rules["operating_hours.{$day}.closed"] = 'boolean';
            }
        }

        return $rules;
    }

    protected $messages = [
        'town_id.required' => 'Please select a town.',
        'town_id.exists' => 'The selected town does not exist.',
        'contact_email.email' => 'Please enter a valid email address.',
        'contact_phone_number.required' => 'Phone number is required.',
        'contact_person.required' => 'Contact person name is required.',
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
        $this->contact_phone_number = $this->point->contact_phone_number;
        $this->is_24_hours = $this->point->is_24_hours;
        $this->capacity = $this->point->capacity;
        $this->notes = $this->point->notes;

        // Load operating hours
        if ($this->point->operating_times) {
            $this->operating_hours = $this->point->operating_times;
        } else {
            // Initialize with default values if operating_times doesn't exist
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                $this->operating_hours[$day] = [
                    'opening' => $this->point->opening_hours ? substr($this->point->opening_hours, 0, 5) : '09:00',
                    'closing' => $this->point->closing_hours ? substr($this->point->closing_hours, 0, 5) : '17:00',
                    'closed' => false,
                ];
            }
        }
    }

    public function loadTowns()
    {
        $this->towns = Town::orderBy('name')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function toggleDayClosed($day)
    {
        if (isset($this->operating_hours[$day])) {
            $this->operating_hours[$day]['closed'] = !$this->operating_hours[$day]['closed'];
            
            // Clear times if day is closed
            if ($this->operating_hours[$day]['closed']) {
                $this->operating_hours[$day]['opening'] = null;
                $this->operating_hours[$day]['closing'] = null;
            }
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Prepare operating days array
            $operatingDaysArray = [];
            $dayMapping = [
                'Monday' => 'Mon',
                'Tuesday' => 'Tue',
                'Wednesday' => 'Wed',
                'Thursday' => 'Thu',
                'Friday' => 'Fri',
                'Saturday' => 'Sat',
                'Sunday' => 'Sun',
            ];

            if ($this->is_24_hours) {
                // If 24/7, all days are operating
                $operatingDaysArray = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                
                // Set all days as open with null times
                foreach ($this->operating_hours as $day => $schedule) {
                    $this->operating_hours[$day] = [
                        'opening' => null,
                        'closing' => null,
                        'closed' => false,
                    ];
                }
            } else {
                // Build operating days from non-closed days
                foreach ($this->operating_hours as $day => $schedule) {
                    if (!$schedule['closed']) {
                        $operatingDaysArray[] = $dayMapping[$day];
                    }
                    
                    // Format times with seconds
                    if (!$schedule['closed'] && !empty($schedule['opening'])) {
                        $this->operating_hours[$day]['opening'] = $schedule['opening'] . ':00';
                    }
                    if (!$schedule['closed'] && !empty($schedule['closing'])) {
                        $this->operating_hours[$day]['closing'] = $schedule['closing'] . ':00';
                    }
                }
                
                // Sort days in correct order
                $dayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $operatingDaysArray = array_values(array_intersect($dayOrder, $operatingDaysArray));
            }

            // Get default opening/closing for legacy fields (use Monday's times)
            $defaultOpening = null;
            $defaultClosing = null;
            if (!$this->is_24_hours && isset($this->operating_hours['Monday']) && !$this->operating_hours['Monday']['closed']) {
                $defaultOpening = $this->operating_hours['Monday']['opening'];
                $defaultClosing = $this->operating_hours['Monday']['closing'];
            }

            $this->point->update([
                'name' => $this->name,
                'status' => $this->status,
                'address' => $this->address,
                'town_id' => $this->town_id,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone_number' => $this->contact_phone_number,
                'operating_days' => json_encode($this->operating_hours),
                'capacity' => $this->capacity,
                'notes' => $this->notes,
            ]);

            DB::commit();

            return redirect()->route('partners.pd.index')->with('success', 'Point updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update point: ' . $e->getMessage());
            return null;
        }
    }

    public function resetForm()
    {
        $this->loadPoint();
    }

    public function deletePoint()
    {
        try {
            DB::beginTransaction();
            $this->point->delete();
            DB::commit();
            
            session()->flash('success', 'Point deleted successfully!');
            return redirect()->route('partners.pd.index')->with('success', 'Point deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete point: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.edit-pick-up-and-drop-off-point');
    }
}