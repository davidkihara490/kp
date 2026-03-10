<?php

namespace App\Livewire\Partners\ParcelHandlingAssistants;

use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\User;
use App\Models\AssistantEmployment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditParcelHandlingAssistant extends Component
{
    public $assistant;
    public $assistant_id;

    // Form fields
    public $first_name = '';
    public $second_name = '';
    public $last_name = '';
    public $phone_number = '';
    public $email = '';
    public $role = 'assistant';
    public $id_number = '';
    public $status = 'active';
    public $originalStatus = 'active';
    
    // User account fields
    public $create_user_account = false;
    public $new_password = '';
    public $confirm_password = '';
    public $send_welcome_email = false;
    public $send_password_email = false;
    
    // Station assignment
    public $showAssignStationModal = false;
    public $selectedStation = '';
    public $stations = [];

    // protected $rules = [
    //     'first_name' => 'required|string|min:2|max:50',
    //     'second_name' => 'nullable|string|min:2|max:50',
    //     'last_name' => 'required|string|min:2|max:50',
    //     'phone_number' => [
    //         'required',
    //         'string',
    //         'min:10',
    //         'max:15',
    //         Rule::unique('parcel_handling_assistants', 'phone_number')->ignore(null, 'id'),
    //         Rule::unique('users', 'phone_number')->whereNull('deleted_at')->ignore(null, 'id'),
    //     ],
    //     'email' => [
    //         'required',
    //         'email',
    //         Rule::unique('parcel_handling_assistants', 'email')->ignore(null, 'id'),
    //         Rule::unique('users', 'email')->whereNull('deleted_at')->ignore(null, 'id'),
    //     ],
    //     'role' => 'required|in:assistant,supervisor,manager',
    //     'id_number' => [
    //         'required',
    //         'string',
    //         'min:5',
    //         'max:20',
    //         Rule::unique('parcel_handling_assistants', 'id_number')->ignore(null, 'id'),
    //     ],
    //     'status' => 'required|in:active,inactive,suspended,pending',
    //     'create_user_account' => 'boolean',
    //     'send_welcome_email' => 'boolean',
    //     'send_password_email' => 'boolean',
    //     'new_password' => 'nullable|min:8',
    //     'confirm_password' => 'nullable|same:new_password',
    //     'selectedStation' => 'nullable|exists:station_partners,id',
    // ];

    // protected $messages = [
    //     'first_name.required' => 'First name is required.',
    //     'last_name.required' => 'Last name is required.',
    //     'phone_number.required' => 'Phone number is required.',
    //     'phone_number.unique' => 'This phone number is already registered.',
    //     'email.required' => 'Email address is required.',
    //     'email.unique' => 'This email address is already registered.',
    //     'id_number.required' => 'ID number is required.',
    //     'id_number.unique' => 'This ID number is already registered.',
    //     'confirm_password.same' => 'Passwords do not match.',
    // ];

    public function mount($id)
    {
        $this->assistant_id = $id;
        $this->loadAssistant();
        $this->loadStations();
    }

    public function loadAssistant()
    {
        $this->assistant = ParcelHandlingAssistant::findOrFail($this->assistant_id);
        
        // Populate form fields
        $this->first_name = $this->assistant->first_name;
        $this->second_name = $this->assistant->second_name;
        $this->last_name = $this->assistant->last_name;
        $this->phone_number = $this->assistant->phone_number;
        $this->email = $this->assistant->email;
        $this->role = $this->assistant->role;
        $this->id_number = $this->assistant->id_number;
        $this->status = $this->assistant->status;
        $this->originalStatus = $this->assistant->status;
        
        // Update validation rules with actual ID
        // $this->rules['phone_number'] = [
        //     'required',
        //     'string',
        //     'min:10',
        //     'max:15',
        //     Rule::unique('parcel_handling_assistants', 'phone_number')->ignore($this->assistant->id),
        //     Rule::unique('users', 'phone_number')->whereNull('deleted_at')->ignore($this->assistant->user_id),
        // ];
        
        // $this->rules['email'] = [
        //     'required',
        //     'email',
        //     Rule::unique('parcel_handling_assistants', 'email')->ignore($this->assistant->id),
        //     Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->assistant->user_id),
        // ];
        
        // $this->rules['id_number'] = [
        //     'required',
        //     'string',
        //     'min:5',
        //     'max:20',
        //     Rule::unique('parcel_handling_assistants', 'id_number')->ignore($this->assistant->id),
        // ];
    }

    public function loadStations()
    {
        $this->stations = PickUpAndDropOffPoint::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function generateNewPassword()
    {
        $this->new_password = $this->generateRandomPassword();
        $this->confirm_password = $this->new_password;
    }

    public function showAssignStation()
    {
        $this->selectedStation = '';
        $this->showAssignStationModal = true;
    }

    public function assignStation()
    {
        $this->validate(['selectedStation' => 'required|exists:station_partners,id']);
        
        try {
            // Check if already employed at this station
            $existingEmployment = $this->assistant->employments()
                ->where('station_partner_id', $this->selectedStation)
                ->first();
            
            if ($existingEmployment) {
                // Update existing employment to active
                $existingEmployment->update(['status' => 'active']);
                $message = "Assistant already assigned to this station. Employment reactivated.";
            } else {
                // Create new employment
                $this->assistant->employments()->create([
                    'station_partner_id' => $this->selectedStation,
                    'status' => 'active',
                    'created_by' => Auth::id(),
                ]);
                $message = "Assistant assigned to station successfully.";
            }
            
            // Reload assistant data
            $this->loadAssistant();
            $this->showAssignStationModal = false;
            
            session()->flash('success', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to assign station: ' . $e->getMessage());
        }
    }

    public function deactivateEmployment($employmentId)
    {
        // try {
        //     $employment = AssistantEmployment::findOrFail($employmentId);
        //     $employment->update(['status' => 'inactive']);
        //     $this->loadAssistant();
        //     session()->flash('success', 'Employment deactivated.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to deactivate employment: ' . $e->getMessage());
        // }
    }

    public function activateEmployment($employmentId)
    {
        // try {
        //     $employment = AssistantEmployment::findOrFail($employmentId);
        //     $employment->update(['status' => 'active']);
        //     $this->loadAssistant();
        //     session()->flash('success', 'Employment activated.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to activate employment: ' . $e->getMessage());
        // }
    }

    public function removeEmployment($employmentId)
    {
        // try {
        //     AssistantEmployment::findOrFail($employmentId)->delete();
        //     $this->loadAssistant();
        //     session()->flash('success', 'Assistant removed from station.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to remove employment: ' . $e->getMessage());
        // }
    }

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function update()
    {
        $validated = $this->validate();
        
        try {
            DB::beginTransaction();
            
            // Update assistant
            $this->assistant->update([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'role' => $this->role,
                'id_number' => $this->id_number,
                'status' => $this->status,
            ]);
            
            // Handle user account
            if ($this->assistant->user) {
                // Update existing user
                $this->assistant->user->update([
                    'first_name' => $this->first_name,
                    'second_name' => $this->second_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                    'status' => $this->status,
                ]);
                
                // Update password if provided
                if ($this->new_password) {
                    $this->assistant->user->update([
                        'password' => Hash::make($this->new_password)
                    ]);
                    
                    // Send password reset email if requested
                    if ($this->send_password_email) {
                        // \Mail::to($this->assistant->user->email)->send(new PasswordResetEmail($this->assistant->user, $this->new_password));
                    }
                }
            } elseif ($this->create_user_account && $this->new_password) {
                // Create new user account
                $user = User::create([
                    'first_name' => $this->first_name,
                    'second_name' => $this->second_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                    'user_name' => strtolower($this->first_name . '.' . $this->last_name) . rand(100, 999),
                    'password' => Hash::make($this->new_password),
                    'status' => $this->status,
                    'login_attempts' => 0,
                ]);
                
                // Assign role to user
                // $user->assignRole('parcel-handling-assistant');
                
                // Link user to assistant
                $this->assistant->update(['user_id' => $user->id]);
                
                // Send welcome email if requested
                if ($this->send_welcome_email) {
                    // \Mail::to($user->email)->send(new WelcomeEmail($user, $this->new_password));
                }
            }
            
            DB::commit();
            
            // Reload assistant data
            $this->loadAssistant();
            
            session()->flash('success', 'Assistant updated successfully!');
            
            // Dispatch event to refresh parent component
            $this->dispatch('assistantUpdated', $this->assistant->id);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update assistant: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->loadAssistant();
        $this->new_password = '';
        $this->confirm_password = '';
        $this->send_welcome_email = false;
        $this->send_password_email = false;
        $this->create_user_account = false;
    }

    public function deleteAssistant()
    {
        try {
            $name = $this->assistant->full_name;
            $this->assistant->delete();
            session()->flash('success', "Assistant '{$name}' deleted successfully!");
            $this->dispatch('assistantDeleted', $this->assistant_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete assistant: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.parcel-handling-assistants.edit-parcel-handling-assistant');
    }
}