<?php

namespace App\Livewire\Partners\RolesAndPermissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RolesAndPermissions extends Component
{
    use WithPagination;

    // Search only
    public $search = '';
    
    // Pagination
    public $perPage = 10;
    
    // Modal flags
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showPermissionsModal = false;
    
    // Role data
    public $role = null;
    public $roleId = null;
    public $roleName_input = '';
    public $selectedPermissions = [];
    
    // Statistics
    public $totalRoles;
    public $totalPermissions;
    public $totalUsersWithRoles;
    public $mostAssignedRole;
    
    protected $rules = [
        'roleName_input' => 'required|min:3|max:255|unique:roles,name',
        'selectedPermissions' => 'array',
    ];
    
    protected $messages = [
        'roleName_input.required' => 'The role name is required.',
        'roleName_input.unique' => 'This role name already exists.',
        'roleName_input.min' => 'The role name must be at least 3 characters.',
    ];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->totalRoles = Role::count();
        $this->totalPermissions = Permission::count();
        $this->totalUsersWithRoles = User::whereHas('roles')->count();
        
        $mostAssigned = DB::table('model_has_roles')
            ->select('role_id', DB::raw('count(*) as total'))
            ->groupBy('role_id')
            ->orderByDesc('total')
            ->first();
            
        if ($mostAssigned && $mostAssigned->role_id) {
            $role = Role::find($mostAssigned->role_id);
            $this->mostAssignedRole = $role ? $role->name : 'None';
        } else {
            $this->mostAssignedRole = 'None';
        }
    }

    public function getPermissionsProperty()
    {
        return Permission::orderBy('name')->get();
    }

    public function getGroupedPermissionsProperty()
    {
        $permissions = Permission::orderBy('name')->get();
        
        return $permissions->groupBy(function($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        })->sortKeys();
    }

    public function render()
    {
        $roles = $this->loadRoles();
        
        return view('livewire.partners.roles-and-permissions.roles-and-permissions', [
            'roles' => $roles,
            'permissionsList' => $this->permissions,
            'groupedPermissions' => $this->groupedPermissions,
        ]);
    }

    public function loadRoles()
    {
        $query = Role::query()
            ->where('user_id', Auth::guard('partner')->user()->id)
            ->with('permissions')
            ->withCount('users');
            
        // Apply search filter only
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->paginate($this->perPage);
    }

    public function resetFilters()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['roleName_input', 'selectedPermissions']);
        $this->showCreateModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->role = Role::with('permissions')->find($id);
        
        if ($this->role) {
            $this->roleId = $this->role->id;
            $this->roleName_input = $this->role->name;
            $this->selectedPermissions = $this->role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->showEditModal = true;
        }
    }

    public function openPermissionsModal($id)
    {
        $this->resetValidation();
        $this->role = Role::with('permissions')->find($id);
        
        if ($this->role) {
            $this->roleId = $this->role->id;
            $this->selectedPermissions = $this->role->permissions->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->showPermissionsModal = true;
        }
    }

    public function confirmDelete($id)
    {
        $this->role = Role::find($id);
        
        if ($this->role) {
            $this->roleId = $this->role->id;
            $this->showDeleteModal = true;
        }
    }

    public function createRole()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $role = Role::create([
                'name' => $this->roleName_input,
                'guard_name' => 'web',
                'user_id' => Auth::guard('partner')->user()->id,
            ]);
            
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
            }
            
            DB::commit();
            
            $this->showCreateModal = false;
            $this->reset(['roleName_input', 'selectedPermissions']);
            $this->loadStatistics();
            $this->dispatch('alert', type: 'success', message: 'Role created successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', message: 'Error creating role: ' . $e->getMessage());
        }
    }

    public function updateRole()
    {
        $this->validate([
            'roleName_input' => 'required|min:3|max:255|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array',
        ]);
        
        try {
            DB::beginTransaction();
            
            $role = Role::find($this->roleId);
            $role->update([
                'name' => $this->roleName_input,
            ]);
            
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            DB::commit();
            
            $this->showEditModal = false;
            $this->reset(['roleName_input', 'selectedPermissions', 'roleId']);
            $this->loadStatistics();
            $this->dispatch('alert', type: 'success', message: 'Role updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', message: 'Error updating role: ' . $e->getMessage());
        }
    }

    public function updatePermissions()
    {
        try {
            $role = Role::find($this->roleId);
            
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            $this->showPermissionsModal = false;
            $this->reset(['selectedPermissions', 'roleId']);
            $this->loadStatistics();
            $this->dispatch('alert', type: 'success', message: 'Permissions updated successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Error updating permissions: ' . $e->getMessage());
        }
    }

    public function delete()
    {
        try {
            $role = Role::find($this->roleId);
            
            // Check if role has users
            if ($role->users()->count() > 0) {
                $this->dispatch('alert', type: 'error', message: 'Cannot delete role with assigned users.');
                $this->showDeleteModal = false;
                return;
            }
            
            // Prevent deletion of super-admin or admin roles
            if (in_array($role->name, ['super-admin', 'admin', 'partner-admin'])) {
                $this->dispatch('alert', type: 'error', message: 'Cannot delete system roles.');
                $this->showDeleteModal = false;
                return;
            }
            
            $role->delete();
            
            $this->showDeleteModal = false;
            $this->reset(['roleId']);
            $this->loadStatistics();
            $this->dispatch('alert', type: 'success', message: 'Role deleted successfully!');
            
        } catch (\Exception $e) {
            $this->dispatch('alert', type: 'error', message: 'Error deleting role: ' . $e->getMessage());
        }
    }
}