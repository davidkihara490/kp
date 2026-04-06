<?php

namespace App\Livewire\Admin\Settings\RolesAndPermissions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CreateRolesAndPermission extends Component
{
        public $permissions;
    public $name;
    public $selectedPermissions = [];

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'required|array|min:1',
        'selectedPermissions.*' => 'exists:permissions,id',
    ];

    protected $messages = [
        'name.required' => 'Role name is required.',
        'name.unique' => 'This role name already exists.',
        'selectedPermissions.required' => 'Choose at least one permission.',
        'selectedPermissions.*.exists' => 'Invalid permission selected.',
    ];

    public function submit()
    {
        // $this->validate();

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $this->name,
            ]);

            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();

            $role->syncPermissions($permissions);
            DB::commit();
            session()->flash('success', 'Role created successfully!');
            return redirect()->route('admin.roles-and-permissions.index')->with('success', 'Role created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Failed to create role. Try again.' . $th->getMessage());
            return back();
        }
    }
    public function render()
    {
        return view('livewire.admin.settings.roles-and-permissions.create-roles-and-permission');
    }
}
