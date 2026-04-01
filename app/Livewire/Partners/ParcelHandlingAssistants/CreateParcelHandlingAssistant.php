<?php

namespace App\Livewire\Partners\ParcelHandlingAssistants;

use App\Jobs\SendWelcomeEmail;
use App\Models\ParcelHandlingAssistant;
use App\Models\User;
use App\Services\SMSService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateParcelHandlingAssistant extends Component
{
    public $first_name = '';
    public $second_name = '';
    public $last_name = '';
    public $phone_number = '';
    public $email = '';
    public $id_number = '';
    public $status = 'active';
    public $password = '';
    public $confirm_password = '';
    public $role_id;
    public $roles = [];
    public $role;
    protected SMSService $smsService;


    protected function rules()
    {
        return [
            'first_name' => 'required|string|min:2|max:50',
            'second_name' => 'nullable|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'phone_number' => [
                'required',
                'string',
                'min:10',
                'max:15',
                Rule::unique('parcel_handling_assistants', 'phone_number'),
                Rule::unique('users', 'phone_number'),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('parcel_handling_assistants', 'email'),
                Rule::unique('users', 'email'),
            ],
            'id_number' => [
                'required',
                'string',
                'min:5',
                'max:20',
                Rule::unique('parcel_handling_assistants', 'id_number'),
            ],
            'status' => 'required|in:active,inactive,suspended,pending',
            'password' => 'required|min:8|nullable',
            'confirm_password' => 'required|same:password|nullable',
        ];
    }

    protected $messages = [
        'first_name.required' => 'First name is required.',
        'last_name.required' => 'Last name is required.',
        'phone_number.required' => 'Phone number is required.',
        'phone_number.unique' => 'This phone number is already registered.',
        'email.required' => 'Email address is required.',
        'email.unique' => 'This email address is already registered.',
        'id_number.required' => 'ID number is required.',
        'id_number.unique' => 'This ID number is already registered.',
        'password.required_if' => 'Password is required when creating a user account.',
        'confirm_password.same' => 'Passwords do not match.',
    ];

    public function mount(SMSService $smsService)
    {
        $this->smsService = $smsService;

        // Generate a random password
        $this->password = $this->generateRandomPassword();
        $this->confirm_password = $this->password;
        $this->roles = Role::where('user_id', Auth::guard('partner')->user()->id)->get();
    }

    private function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function generateNewPassword()
    {
        $this->password = $this->generateRandomPassword();
        $this->confirm_password = $this->password;
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            DB::beginTransaction();

            $user = User::create([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'user_name' => strtolower($this->first_name . '.' . $this->last_name) . rand(100, 999),
                'password' => Hash::make($this->password),
                'status' => $this->status,
                'login_attempts' => 0,
                'user_type' => 'pha',
            ]);

            $assistant = ParcelHandlingAssistant::create([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'role' => $this->role->name,
                'id_number' => $this->id_number,
                'status' => $this->status,
                'user_id' => $user->id,
                'partner_id' => $partner = Auth::guard('partner')->user()->partner->id,
            ]);

            SendWelcomeEmail::dispatch($user, true, $this->password);

            $this->smsService->sendParcelHandlingAssistantWelcomeSMS(
                $this->phone_number,
                trim($this->first_name . ' ' . $this->second_name . ' ' . $this->last_name)
            );

            DB::commit();

            return redirect()->route('partners.pha.index')
                ->with('success', 'Assistant created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', 'Failed to create assistant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.parcel-handling-assistants.create-parcel-handling-assistant');
    }
}
