<?php

namespace App\Livewire\Partners\RolesAndPermissions;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CreateRolesAndPermission extends Component
{
    // Form fields
    public $name = '';
    public $guard_name = 'web';
    public $description = '';
    public $selectedPermissions = [];
    public $is_system_role = false;
    public $color = 'primary';
    public $icon = 'fa-shield-alt';
    public $display_name = '';
    public $level = 0;
    
    // UI state
    public $showForm = false;
    public $editMode = false;
    public $roleId = null;
    
    // Permissions organized by modules
    public $permissionsByModule = [];
    public $allPermissions = [];
    
    // Validation rules
    protected function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9\-_]+$/',
                Rule::unique('roles')->ignore($this->roleId),
            ],
            'guard_name' => 'required|in:web,api',
            'description' => 'nullable|string|max:255',
            'selectedPermissions' => 'array',
            'selectedPermissions.*' => 'exists:permissions,id',
            'is_system_role' => 'boolean',
            'color' => 'required|in:primary,secondary,success,danger,warning,info,dark',
            'icon' => 'nullable|string|max:50',
            'display_name' => 'nullable|string|max:100',
            'level' => 'integer|min:0|max:100',
        ];
        
        return $rules;
    }
    
    protected $messages = [
        'name.required' => 'The role name is required.',
        'name.regex' => 'The role name may only contain lowercase letters, numbers, dashes, and underscores.',
        'name.unique' => 'This role name already exists.',
        'name.min' => 'The role name must be at least 3 characters.',
        'guard_name.required' => 'Please select a guard type.',
        'color.required' => 'Please select a color.',
    ];

    public function mount($role = null)
    {
        $this->loadPermissions();
        
        if ($role) {
            $this->editMode = true;
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->guard_name = $role->guard_name;
            $this->description = $role->description ?? '';
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
            $this->is_system_role = $role->is_system_role ?? false;
            $this->color = $role->color ?? 'primary';
            $this->icon = $role->icon ?? 'fa-shield-alt';
            $this->display_name = $role->display_name ?? '';
            $this->level = $role->level ?? 0;
        }
    }

    public function loadPermissions()
    {
        // Load all permissions
        $this->allPermissions = Permission::orderBy('name')->get();
        
        // Group permissions by module (first part before hyphen)
        $this->permissionsByModule = $this->allPermissions->groupBy(function($permission) {
            $parts = explode('-', $permission->name);
            return count($parts) > 1 ? ucfirst($parts[0]) : 'General';
        })->sortKeys();
    }

    public function updatedName($value)
    {
        // Auto-generate display name from role name
        if (!$this->display_name && !$this->editMode) {
            $this->display_name = ucwords(str_replace(['-', '_'], ' ', $value));
        }
    }

    public function selectAllPermissions()
    {
        $this->selectedPermissions = $this->allPermissions->pluck('id')->toArray();
    }

    public function deselectAllPermissions()
    {
        $this->selectedPermissions = [];
    }

    public function selectModulePermissions($module)
    {
        $modulePermissions = $this->permissionsByModule[$module] ?? collect();
        $modulePermissionIds = $modulePermissions->pluck('id')->toArray();
        
        // Merge with existing selections
        $this->selectedPermissions = array_unique(
            array_merge($this->selectedPermissions, $modulePermissionIds)
        );
    }

    public function deselectModulePermissions($module)
    {
        $modulePermissions = $this->permissionsByModule[$module] ?? collect();
        $modulePermissionIds = $modulePermissions->pluck('id')->toArray();
        
        // Remove module permissions from selections
        $this->selectedPermissions = array_diff(
            $this->selectedPermissions, 
            $modulePermissionIds
        );
    }

    public function getModuleSelectionState($module)
    {
        $modulePermissions = $this->permissionsByModule[$module] ?? collect();
        $modulePermissionIds = $modulePermissions->pluck('id')->toArray();
        
        $selectedInModule = array_intersect($this->selectedPermissions, $modulePermissionIds);
        
        if (empty($modulePermissionIds)) {
            return 'none';
        }
        
        if (count($selectedInModule) === count($modulePermissionIds)) {
            return 'all';
        } elseif (count($selectedInModule) > 0) {
            return 'partial';
        }
        
        return 'none';
    }

    public function save()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            $roleData = [
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'description' => $this->description,
                'is_system_role' => $this->is_system_role,
                'color' => $this->color,
                'icon' => $this->icon,
                'display_name' => $this->display_name ?: ucwords(str_replace(['-', '_'], ' ', $this->name)),
                'level' => $this->level,
            ];
            
            if ($this->editMode) {
                $role = Role::find($this->roleId);
                $role->update($roleData);
                $message = 'Role updated successfully!';
            } else {
                $role = Role::create($roleData);
                $message = 'Role created successfully!';
            }
            
            // Sync permissions
            if (!empty($this->selectedPermissions)) {
                $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            DB::commit();
            
            $this->dispatch('role-saved', [
                'type' => 'success',
                'message' => $message,
                'role' => $role
            ]);
            
            if (!$this->editMode) {
                $this->reset(['name', 'description', 'selectedPermissions', 'display_name']);
                $this->guard_name = 'web';
                $this->color = 'primary';
                $this->icon = 'fa-shield-alt';
                $this->level = 0;
            }
            
            $this->showForm = false;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('role-saved', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function cancel()
    {
        $this->resetValidation();
        $this->showForm = false;
        $this->dispatch('form-cancelled');
    }

    public function render()
    {
        return view('livewire.partners.roles-and-permissions.create-roles-and-permission');
    }
}