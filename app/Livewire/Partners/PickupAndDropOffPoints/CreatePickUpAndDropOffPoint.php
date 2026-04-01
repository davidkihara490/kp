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
    public $capacity;
    public $notes;

    // Operating hours for each day of the week
    public $operating_hours = [
        'Monday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Tuesday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Wednesday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Thursday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Friday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Saturday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
        'Sunday' => ['opening' => '08:00', 'closing' => '17:00', 'closed' => false],
    ];

    // For loading related data
    public $towns = [];

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:pick_up_and_drop_off_points,code',
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
                // Only validate if the day is not marked as closed
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
                        if (
                            !$this->operating_hours[$day]['closed'] &&
                            !empty($this->operating_hours[$day]['opening']) &&
                            !empty($value) &&
                            $this->operating_hours[$day]['opening'] >= $value
                        ) {
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
        'code.unique' => 'This point code already exists. Please use a different code.',
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

    public function updated($propertyName)
    {
        // Validate only the changed property
        $this->validateOnly($propertyName);

        // When 24/7 mode is toggled, validate the operating hours structure
        if ($propertyName === 'is_24_hours') {
            $this->validateOperatingHours();
        }
    }

    /**
     * Validate that all operating hours are properly set when not in 24/7 mode
     */
    protected function validateOperatingHours()
    {
        if (!$this->is_24_hours) {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                if (!$this->operating_hours[$day]['closed']) {
                    if (empty($this->operating_hours[$day]['opening'])) {
                        $this->operating_hours[$day]['opening'] = '09:00';
                    }
                    if (empty($this->operating_hours[$day]['closing'])) {
                        $this->operating_hours[$day]['closing'] = '17:00';
                    }
                }
            }
        }
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Prepare operating hours data for storage
            $operatingDaysArray = [];
            $operatingTimesArray = [];

            if ($this->is_24_hours) {
                $operatingDaysArray = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $operatingTimesArray = [
                    'Monday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Tuesday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Wednesday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Thursday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Friday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Saturday' => ['opening' => null, 'closing' => null, 'closed' => false],
                    'Sunday' => ['opening' => null, 'closing' => null, 'closed' => false],
                ];
            } else {
                $dayMapping = [
                    'Monday' => 'Mon',
                    'Tuesday' => 'Tue',
                    'Wednesday' => 'Wed',
                    'Thursday' => 'Thu',
                    'Friday' => 'Fri',
                    'Saturday' => 'Sat',
                    'Sunday' => 'Sun',
                ];

                foreach ($this->operating_hours as $day => $schedule) {
                    if (!$schedule['closed']) {
                        $operatingDaysArray[] = $dayMapping[$day];
                    }

                    // Store times with seconds (add ':00')
                    $operatingTimesArray[$day] = [
                        'opening' => !$schedule['closed'] && !empty($schedule['opening']) ? $schedule['opening'] . ':00' : null,
                        'closing' => !$schedule['closed'] && !empty($schedule['closing']) ? $schedule['closing'] . ':00' : null,
                        'closed' => $schedule['closed'],
                    ];
                }

                // Ensure operating days are sorted in the correct order
                $dayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $operatingDaysArray = array_values(array_intersect($dayOrder, $operatingDaysArray));
            }

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
                'operating_days' => json_encode($operatingTimesArray),
                'capacity' => $this->capacity,
                'notes' => $this->notes,
            ]);

            DB::commit();

            return redirect()->route('partners.pd.index')->with('success', 'Pick-up and Drop-off Point created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create point: ' . $e->getMessage());
            return null;
        }
    }

    public function render()
    {
        return view('livewire.partners.pickup-and-drop-off-points.create-pick-up-and-drop-off-point');
    }
}
