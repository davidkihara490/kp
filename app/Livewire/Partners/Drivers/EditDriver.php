<?php

namespace App\Livewire\Partners\Drivers;

use App\Models\Driver;
use App\Models\DriverEmployment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditDriver extends Component
{
    public $driverId;
    public $driver;
    // Personal Information
    public $first_name;
    public $second_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $alternate_phone_number;

    // Personal Details
    public $gender;
    public $id_number;

    // Driving License Information
    public $driving_license_number;
    public $driving_license_issue_date;
    public $driving_license_expiry_date;
    public $license_class = 'B';

    // Emergency Contact
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;

    // Banking Information
    public $bank_name;
    public $bank_account_number;
    public $bank_account_name;

    // Status
    public $is_available = true;

    // Notes
    public $notes;

    // For form
    public $password = '';
    public $password_confirmation = '';

protected function rules()
{
    $driverId = $this->driver->id ?? null;
    
    return [
        'first_name' => 'required|string|max:255',
        'second_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('drivers')->ignore($driverId),
            Rule::unique('users')->ignore($driverId ? User::where('email', $this->email)->value('id') : null),
        ],
        'phone_number' => [
            'required',
            'string',
            Rule::unique('drivers')->ignore($driverId)
        ],
        'alternate_phone_number' => 'nullable|string',

        'gender' => 'nullable|in:male,female,other',
        'id_number' => [
            'nullable',
            'string',
            'max:20',
            Rule::unique('drivers')->ignore($driverId)
        ],

        'driving_license_number' => [
            'required',
            'string',
            Rule::unique('drivers')->ignore($driverId)
        ],
        'driving_license_issue_date' => 'required|date',
        'driving_license_expiry_date' => 'required|date|after:driving_license_issue_date',
        'license_class' => 'required|in:A,B,C,D,E,F',

        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string',
        'emergency_contact_relationship' => 'nullable|string|max:100',

        'bank_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:50',
        'bank_account_name' => 'nullable|string|max:255',

        'is_available' => 'boolean',

        'notes' => 'nullable|string',

        'password' => $driverId ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
    ];
}

    public function mount($id)
    {

        $this->driver = Driver::findOrFail($id);

        $this->first_name = $this->driver->first_name;
        $this->second_name = $this->driver->second_name;
        $this->last_name = $this->driver->last_name;
        $this->email = $this->driver->email;
        $this->phone_number = $this->driver->phone_number;
        $this->alternate_phone_number = $this->driver->alternate_phone_number;

        $this->generatePassword();
        $this->setDefaultDates();
    }

    private function setDefaultDates()
    {
        $this->driving_license_issue_date = now()->subYears(2)->format('Y-m-d');
        $this->driving_license_expiry_date = now()->addYears(1)->format('Y-m-d');
    }

    public function generatePassword()
    {
        $this->password = 'Driver@' . rand(1000, 9999);
        $this->password_confirmation = $this->password;
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = User::findOrFail($this->driver->user_id);

            $user->update([
                'first_name'  => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'user_name' => explode('@', $this->email)[0],
                'user_type' => 'driver',
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'password' => Hash::make($this->password),
                'terms_and_conditions' => true,
                'privacy_policy' => true,
                'status' => 'active',
            ]);

            // Create driver
            $this->driver->update([
                'user_id' => $user->id,
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'alternate_phone_number' => $this->alternate_phone_number,
                'gender' => $this->gender,
                'id_number' => $this->id_number,
                'driving_license_number' => $this->driving_license_number,
                'driving_license_issue_date' => $this->driving_license_issue_date,
                'driving_license_expiry_date' => $this->driving_license_expiry_date,
                'license_class' => $this->license_class,
                'emergency_contact_name' => $this->emergency_contact_name,
                'emergency_contact_phone' => $this->emergency_contact_phone,
                'emergency_contact_relationship' => $this->emergency_contact_relationship,
                'bank_name' => $this->bank_name,
                'bank_account_number' => $this->bank_account_number,
                'bank_account_name' => $this->bank_account_name,
                'is_available' => $this->is_available,
                'notes' => $this->notes,
            ]);

            // $employment = DriverEmployment::create([
            //     'driver_id' => $driver->id,
            //     'partner_id' => Auth::guard('partner')->user()->currentPartner()->id,
            //     'from' => now(),
            //     'to' => null,
            //     'status' => 'active',
            // ]);

            DB::commit();

            session()->flash('success', 'Driver created successfully!');

            return redirect()->route('partners.drivers.view', $this->driver->id);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create driver: ' . $e->getMessage());
        }
    }

    private function getFullName()
    {
        $names = [$this->first_name];
        if ($this->second_name) {
            $names[] = $this->second_name;
        }
        $names[] = $this->last_name;
        return implode(' ', $names);
    }
    public function render()
    {
        return view('livewire.partners.drivers.edit-driver',[
            'licenseClasses' => [
                'A' => 'A - Motorcycle',
                'B' => 'B - Light Vehicle',
                'C' => 'C - Heavy Vehicle',
                'D' => 'D - Trailer',
                'E' => 'E - Articulated',
                'F' => 'F - Tractor',
            ],
            'genders' => [
                'male' => 'Male',
                'female' => 'Female',
                'other' => 'Other',
            ],
            'employmentTypes' => [
                'full_time' => 'Full Time',
                'contract' => 'Contract',
                'casual' => 'Casual',
                'part_time' => 'Part Time',
            ],
            'shiftTypes' => [
                'day' => 'Day Shift',
                'night' => 'Night Shift',
                'rotating' => 'Rotating Shift',
            ],
            'relationships' => [
                'Spouse' => 'Spouse',
                'Parent' => 'Parent',
                'Sibling' => 'Sibling',
                'Child' => 'Child',
                'Friend' => 'Friend',
                'Other' => 'Other',
            ],
            'statuses' => [
                'active' => 'Active',
                'inactive' => 'Inactive',
                'suspended' => 'Suspended',
                'on_leave' => 'On Leave',
                'terminated' => 'Terminated',
            ],
        ]);
    }

        public function validateIdNumber()
    {
        if ($this->id_number && $this->nationality === 'Kenyan') {
            $id = $this->id_number;
            if (strlen($id) !== 8 || !ctype_digit($id)) {
                $this->addError('id_number', 'Kenyan ID number must be 8 digits.');
                return false;
            }
        }
        return true;
    }

    public function validateLicenseNumber()
    {
        if ($this->driving_license_number) {
            $license = strtoupper($this->driving_license_number);
            if (!preg_match('/^[A-Z0-9]{6,20}$/', $license)) {
                $this->addError('driving_license_number', 'License number format is invalid. Use letters and numbers only (6-20 characters).');
                return false;
            }
        }
        return true;
    }

    public function getLicenseValidityColor()
    {
        if (!$this->driving_license_expiry_date) return 'secondary';

        $expiryDate = Carbon::parse($this->driving_license_expiry_date);

        if ($expiryDate->isPast()) {
            return 'danger';
        } elseif ($expiryDate->diffInDays(now()) <= 30) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    public function getLicenseValidityText()
    {
        if (!$this->driving_license_expiry_date) return 'Not Set';

        $expiryDate = Carbon::parse($this->driving_license_expiry_date);
        $daysUntilExpiry = now()->diffInDays($expiryDate, false);

        if ($daysUntilExpiry < 0) {
            return 'Expired ' . abs($daysUntilExpiry) . ' days ago';
        } elseif ($daysUntilExpiry <= 30) {
            return 'Expires in ' . $daysUntilExpiry . ' days';
        } else {
            return 'Valid for ' . $daysUntilExpiry . ' days';
        }
    }
}
