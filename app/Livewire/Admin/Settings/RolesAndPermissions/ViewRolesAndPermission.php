<?php

namespace App\Livewire\Admin\Settings\RolesAndPermissions;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class ViewRolesAndPermission extends Component
{
    public $role;
    public function mount($id)
    {
        $this->role = Role::findOrFail($id);
    }
    public function render()
    {
        return view('livewire.admin.settings.roles-and-permissions.view-roles-and-permission');
    }
}
