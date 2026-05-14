<?php

namespace App\Livewire\Admin\ParcelHandlingAssistants;

use App\Models\Partner;
use App\Models\User;
use App\Models\ParcelHandlingAssistant;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EditParcelHandlingAssistant extends Component
{
    public $pha_id;
    public $parcelHandlingAssistant;
    
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
    public $user_id = '';
    
    // Assignment Information
    public $partner_id = '';
    public $status = 'active';
    
    public $partners = [];
    public $showPasswordField = false;
    
    protected $rules = [
        'first_name' => 'required|string|max:255',
        'second_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'id_number' => 'required|string|max:50',
        'username' => 'required|string|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'partner_id' => 'required|exists:partners,id',
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
    
    public function mount($id)
    {
        $this->pha_id = $id;
        $this->loadPartners();
        $this->loadParcelHandlingAssistant();
    }
    
    public function loadPartners()
    {
        $this->partners = Partner::where('verification_status', 'verified')
            ->orderBy('company_name')
            ->get();
    }
    
    public function loadParcelHandlingAssistant()
    {
        $this->parcelHandlingAssistant = ParcelHandlingAssistant::with('user')->findOrFail($this->pha_id);
        
        // Load Personal Information
        $this->first_name = $this->parcelHandlingAssistant->first_name;
        $this->second_name = $this->parcelHandlingAssistant->second_name;
        $this->last_name = $this->parcelHandlingAssistant->last_name;
        $this->phone_number = $this->parcelHandlingAssistant->phone_number;
        $this->email = $this->parcelHandlingAssistant->email;
        $this->id_number = $this->parcelHandlingAssistant->id_number;
        
        // Load Account Information
        if ($this->parcelHandlingAssistant->user) {
            $this->username = $this->parcelHandlingAssistant->user->user_name;
            $this->user_id = $this->parcelHandlingAssistant->user->id;
        }
        
        // Load Assignment Information
        $this->partner_id = $this->parcelHandlingAssistant->partner_id;
        $this->status = $this->parcelHandlingAssistant->status;
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->getDynamicRules());
    }
    
    public function updatedEmail()
    {
        // Auto-generate username from email if username is empty and not manually changed
        if (empty($this->username) && !empty($this->email)) {
            $this->username = explode('@', $this->email)[0];
            // Ensure username is unique except for current user
            $originalUsername = $this->username;
            $counter = 1;
            while (User::where('user_name', $this->username)
                ->where('id', '!=', $this->user_id)
                ->exists()) {
                $this->username = $originalUsername . $counter;
                $counter++;
            }
        }
    }
    
    public function togglePasswordField()
    {
        $this->showPasswordField = !$this->showPasswordField;
        if (!$this->showPasswordField) {
            $this->password = '';
            $this->password_confirmation = '';
        }
    }
    
    protected function getDynamicRules()
    {
        $rules = $this->rules;
        
        // Add unique rules with ignore for current record
        $rules['phone_number'] = [
            'required',
            'string',
            'max:20',
            Rule::unique('parcel_handling_assistants', 'phone_number')->ignore($this->pha_id)
        ];
        
        $rules['email'] = [
            'required',
            'email',
            'max:255',
            Rule::unique('parcel_handling_assistants', 'email')->ignore($this->pha_id),
            Rule::unique('users', 'email')->ignore($this->user_id)
        ];
        
        $rules['id_number'] = [
            'required',
            'string',
            'max:50',
            Rule::unique('parcel_handling_assistants', 'id_number')->ignore($this->pha_id)
        ];
        
        $rules['username'] = [
            'required',
            'string',
            'max:255',
            Rule::unique('users', 'user_name')->ignore($this->user_id)
        ];
        
        return $rules;
    }
    
    public function update()
    {
        $this->validate($this->getDynamicRules());
        
        DB::beginTransaction();
        
        try {
            // 1. Update User Account
            if ($this->parcelHandlingAssistant->user) {
                $userData = [
                    'first_name' => $this->first_name,
                    'second_name' => $this->second_name,
                    'last_name' => $this->last_name,
                    'user_name' => $this->username,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                    'status' => $this->status,
                ];
                
                // Update password only if provided
                if (!empty($this->password)) {
                    $userData['password'] = Hash::make($this->password);
                }
                
                $this->parcelHandlingAssistant->user->update($userData);
            }
            
            // 2. Update Parcel Handling Assistant Record
            $this->parcelHandlingAssistant->update([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'id_number' => $this->id_number,
                'status' => $this->status,
                'partner_id' => $this->partner_id,
            ]);
            
            DB::commit();
            
            session()->flash('success', 'Parcel Handling Assistant updated successfully!');
            return redirect()->route('admin.pha.index');
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating Parcel Handling Assistant: ' . $e->getMessage());
            return null;
        }
    }
    
    public function cancel()
    {
        return redirect()->route('admin.pha.index');
    }
    
    public function render()
    {
        return view('livewire.admin.parcel-handling-assistants.edit-parcel-handling-assistant');
    }
}