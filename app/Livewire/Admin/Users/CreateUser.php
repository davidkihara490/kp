<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateUser extends Component
{
    public $first_name;
    public $second_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $role_id;
    public $roles;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'second_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|string|max:20|unique:users,phone_number',
        'role_id' => 'required|exists:roles,id',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ];

    protected $messages = [
        'first_name.required' => 'First name is required',
        'last_name.required' => 'Last name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'phone_number.required' => 'Phone number is required',
        'phone_number.unique' => 'This phone number is already registered',
        'role_id.required' => 'Please select a role',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 8 characters',
        'password.confirmed' => 'Password confirmation does not match',
    ];

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        
        try {
            // Create the user
            $user = User::create([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'user_type' => 'admin',
                'status' => 'active',
                'password' => Hash::make($this->password),
            ]);

            // Assign role
            $role = Role::findOrFail($this->role_id);
            $user->assignRole($role);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            
            session()->flas('error', 'Error creating user :', $e->getMessage());
        }
    }

    public function render()
    {     
        return view('livewire.admin.users.create-user');
    }
}