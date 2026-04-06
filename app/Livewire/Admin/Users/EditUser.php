<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Validation\Rule;


class EditUser extends Component
{
    public $user;
    public $first_name;
    public $second_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $role_id;
    public $roles;

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user->id),
            ],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone_number')->ignore($this->user->id),
            ],
            'role_id' => 'required|exists:roles,id',
        ];
    }

    protected $messages = [
        'first_name.required' => 'First name is required',
        'last_name.required' => 'Last name is required',
        'email.required' => 'Email address is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'phone_number.required' => 'Phone number is required',
        'phone_number.unique' => 'This phone number is already registered',
        'role_id.required' => 'Please select a role',
    ];

    public function mount($id)
    {

        $this->roles = Role::all();

        $this->user = User::findOrFail($id);

        // Populate form with user data
        $this->first_name = $this->user->first_name;
        $this->second_name = $this->user->second_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->phone_number = $this->user->phone_number;

        // Get user's role
        $userRole = $this->user->roles->first();
        $this->role_id = $userRole ? $userRole->id : null;
    }

    public function updatedEmail($value)
    {
        // Ignore unique validation for the current user
        $this->resetValidation('email');
        if ($value !== User::find($this->user_id)->email) {
            $this->validateOnly('email');
        }
    }

    public function updatedPhoneNumber($value)
    {
        // Ignore unique validation for the current user
        $this->resetValidation('phone_number');
        if ($value !== User::find($this->user_id)->phone_number) {
            $this->validateOnly('phone_number');
        }
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $this->user->update([
                'first_name' => $this->first_name,
                'second_name' => $this->second_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
            ]);

            $role = Role::findOrFail($this->role_id);
            $this->user->syncRoles($role);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating user:' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user');
    }
}
