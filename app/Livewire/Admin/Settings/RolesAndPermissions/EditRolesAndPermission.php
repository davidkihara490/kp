<?php

namespace App\Livewire\Admin\Settings\RolesAndPermissions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class EditRolesAndPermission extends Component
{

    public $role;
    public $permissions = [];
    public $name;
    public $roleId;
    public $selectedPermissions = [];

    public function mount($id)
    {
        $this->role = Role::findOrFail($id);
        $this->roleId = $this->role->id;
        $this->name = $this->role->name;

        // Load all permissions
        $this->permissions = Permission::all();

        // Pre-select current role's permissions by name
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'required|array|min:1',
            'selectedPermissions.*' => 'exists:permissions,name',
        ];
    }

    protected $messages = [
        'name.required' => 'The role name is required.',
        'name.unique' => 'This role name is already taken.',
        'selectedPermissions.required' => 'You must select at least one permission.',
        'selectedPermissions.min' => 'You must select at least one permission.',

        'selectedPermissions.*.exists' => 'Invalid permission selection.',
    ];

    public function selectAllPermissions()
    {
        $this->selectedPermissions = $this->permissions->pluck('name')->toArray();
    }

    public function clearAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);

            // Sync permissions using names
            $role->syncPermissions(array_map('trim', $this->selectedPermissions));

            DB::commit();
            return redirect()
                ->route('admin.roles-and-permissions.index')
                ->with('success', 'Role updated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()
                ->route('admin.roles-and-permissions.edit', $this->role->id)
                ->with('error', 'Failed to update role. Please try again. ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.roles-and-permissions.edit-roles-and-permission');
    }
}
