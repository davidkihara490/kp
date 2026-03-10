<?php

namespace App\Livewire\Partners\Drivers;

use App\Jobs\SendWelcomeEmail;
use App\Models\Driver;
use App\Models\DriverEmployment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreateDriver extends Component
{
    public $first_name;
    public $second_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $alternate_phone_number;
    public $gender;
    public $id_number;
    public $driving_license_number;
    public $driving_license_issue_date;
    public $driving_license_expiry_date;
    public $license_class = 'B';
    public $emergency_contact_name;
    public $emergency_contact_phone;
    public $emergency_contact_relationship;
    public $bank_name;
    public $bank_account_number;
    public $bank_account_name;
    public $is_available = true;
    public $notes;
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'second_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:drivers,email|unique:users,email',
        'phone_number' => 'required|string|unique:drivers,phone_number',
        'alternate_phone_number' => 'nullable|string',

        'gender' => 'nullable|in:male,female,other',
        'id_number' => 'nullable|string|max:20|unique:drivers,id_number',

        'driving_license_number' => 'required|string|unique:drivers,driving_license_number',
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

        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'driving_license_expiry_date.after' => 'License expiry date must be after issue date.',
        'id_number.unique' => 'This ID number is already registered.',
        'phone_number.unique' => 'This phone number is already registered.',
        'email.unique' => 'This email address is already registered.',
        'driving_license_number.unique' => 'This driving license number is already registered.',
    ];
    public function mount()
    {
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

            // Create user account

            $user = User::create([
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
            $driver = Driver::create([
                'user_id' => $user->id,
                'partner_id' => Auth::guard('partner')->user()->partner->id,
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

            // TODO::Handle documents

            // TODO::send driver the welcome email
            // TODO::send driver the welcome SMS

            SendWelcomeEmail::dispatch($user, true, $this->password);

            DB::commit();

            return redirect()->route('partners.drivers.view', $driver->id)->with('success', 'Driver created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to create driver: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.drivers.create-driver');
    }
}
