<?php

namespace App\Livewire\Admin\ParcelHandlingAssistants;

use App\Models\Partner;
use App\Models\User;
use App\Models\ParcelHandlingAssistant;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateParcelHandlingAssistant extends Component
{
    // Personal Information
    public $first_name = '';
    public $second_name = '';
    public $last_name = '';
    public $phone_number = '';
    public $email = '';
    public $id_number = '';
    
    // Account Information
    public $username = '';
    public $password = '';
    public $password_confirmation = '';
    
    // Assignment Information
    public $partner_id = '';
    public $role = 'assistant';
    public $status = 'active';
    
    public $partners = [];
    
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'second_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20|unique:parcel_handling_assistants,phone_number',
        'email' => 'required|email|max:255|unique:parcel_handling_assistants,email|unique:users,email',
        'id_number' => 'required|string|max:50|unique:parcel_handling_assistants,id_number',
        'username' => 'required|string|max:255|unique:users,user_name',
        'password' => 'required|string|min:8|confirmed',
        'partner_id' => 'required|exists:partners,id',
        'role' => 'required|string|in:assistant,supervisor,manager',
        'status' => 'required|in:active,inactive,pending',
    ];
    
    protected $messages = [
        'phone_number.unique' => 'This phone number is already registered.',
        'email.unique' => 'This email is already registered.',
        'id_number.unique' => 'This ID number is already registered.',
        'username.unique' => 'This username is already taken.',
        'password.confirmed' => 'Password confirmation does not match.',
        'partner_id.required' => 'Please select a partner/transport company.',
    ];
    
    public function mount()
    {
        $this->loadPartners();
    }
    
    public function loadPartners()
    {
        $this->partners = Partner::where('verification_status', 'verified')
            ->orderBy('company_name')
            ->get();
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function updatedEmail()
    {
        // Auto-generate username from email if username is empty
        if (empty($this->username) && !empty($this->email)) {
            $this->username = explode('@', $this->email)[0];
            // Ensure username is unique
            $originalUsername = $this->username;
            $counter = 1;
            while (User::where('user_name', $this->username)->exists()) {
                $this->username = $originalUsername . $counter;
                $counter++;
            }
        }
    }
    
    public function save()
    {
        $this->validate();
        
        DB::beginTransaction();
        
        try {
            // 1. Create User Account
            $user = User::create([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'user_name' => $this->username,
                'user_type' => 'pha', // Parcel Handling Assistant
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'password' => Hash::make($this->password),
                'status' => $this->status,
                'terms_and_conditions' => true,
                'privacy_policy' => true,
                'email_verified_at' => now(), // Auto verify for admin created accounts
            ]);
            
            // 2. Assign Role based on selected role
            // $roleName = $this->getRoleName();
            // $role = Role::findByName($roleName, 'web');
            // $user->assignRole($role);
            
            // 3. Create Parcel Handling Assistant Record
            $pha = ParcelHandlingAssistant::create([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'id_number' => $this->id_number,
                // 'role' => $this->role,
                'status' => $this->status,
                'partner_id' => $this->partner_id,
                'user_id' => $user->id,
            ]);
            
            DB::commit();
            
            session()->flash('success', 'Parcel Handling Assistant created successfully!');
            return redirect()->route('admin.pha.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating Parcel Handling Assistant: ' . $e->getMessage());
            return null;
        }
    }
    
    private function getRoleName()
    {
        return match($this->role) {
            'supervisor' => 'parcel-handling-supervisor',
            'manager' => 'parcel-handling-manager',
            default => 'parcel-handling-assistant',
        };
    }
    
    public function cancel()
    {
        return redirect()->route('admin.parcel-handling-assistants.index');
    }
    
    public function render()
    {
        return view('livewire.admin.parcel-handling-assistants.create-parcel-handling-assistant');
    }
}