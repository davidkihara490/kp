<div>
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-shield-alt mr-2 text-primary"></i>
                        Roles & Permissions
                    </h1>
                </div>
                <div class="col-sm-6 text-right">
                    <button class="btn btn-success" wire:click="openCreateModal">
                        <i class="fas fa-plus-circle mr-2"></i> 
                        Create New Role
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- Filters Card -->
        <div class="card card-outline card-primary mb-4">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group" style="max-width: 300px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" 
                                   class="form-control border-left-0 pl-0" 
                                   wire:model.live.debounce.500ms="search" 
                                   placeholder="Search roles by name...">
                            @if($search)
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" wire:click="$set('search', '')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 text-md-right mt-3 mt-md-0">
                        <div class="d-inline-flex align-items-center">
                            <span class="text-muted mr-2">Show</span>
                            <select wire:model.live="perPage" class="form-control form-control-sm" style="width: auto;">
                                <option value="10">10 entries</option>
                                <option value="25">25 entries</option>
                                <option value="50">50 entries</option>
                                <option value="100">100 entries</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table Card -->
        <div class="card">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2 text-primary"></i>
                    Roles Management
                    <span class="badge badge-primary ml-2">{{ $roles->total() }}</span>
                </h3>
            </div>

            <div class="card-body p-0">
                @include('components.alerts.response-alerts')

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="60">#</th>
                                <th>Role Name</th>
                                <th class="text-center" width="100">Users</th>
                                <th class="text-center" width="100">Permissions</th>
                                <th>Assigned Permissions</th>
                                <th class="text-center" width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                            <tr wire:key="role-{{ $role->id }}" class="align-middle">
                                <td class="text-center text-muted">
                                    {{ $roles->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="role-icon mr-3">
                                            @if(in_array($role->name, ['super-admin', 'admin']))
                                                <i class="fas fa-crown text-warning"></i>
                                            @elseif($role->name == 'partner-admin')
                                                <i class="fas fa-building text-info"></i>
                                            @else
                                                <i class="fas fa-user-tag text-primary"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="d-block">{{ $role->display_name ?? $role->name }}</strong>
                                            <small class="text-muted">{{ $role->name }}</small>
                                            @if(in_array($role->name, ['super-admin', 'admin', 'partner-admin']))
                                                <span class="badge badge-danger ml-2">System Role</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $role->users_count }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        <i class="fas fa-key mr-1"></i>
                                        {{ $role->permissions_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap" style="max-width: 400px;">
                                        @forelse($role->permissions->take(5) as $permission)
                                        <span class="badge badge-light border mr-1 mb-1 px-2 py-1">
                                            <i class="fas fa-check-circle text-success mr-1" style="font-size: 0.7rem;"></i>
                                            {{ \Illuminate\Support\Str::limit($permission->name, 20) }}
                                        </span>
                                        @empty
                                        <span class="text-muted">
                                            <i class="fas fa-ban mr-1"></i>No permissions assigned
                                        </span>
                                        @endforelse
                                        @if($role->permissions_count > 5)
                                        <span class="badge badge-secondary px-2 py-1">
                                            +{{ $role->permissions_count - 5 }} more
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-info" 
                                                wire:click="openPermissionsModal({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Manage Permissions">
                                            <i class="fas fa-key"></i>
                                            <span class="ml-1">Permissions</span>
                                        </button>

                                        <button class="btn btn-sm btn-outline-primary" 
                                                wire:click="openEditModal({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Edit Role"
                                                {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) ? 'disabled' : '' }}>
                                            <i class="fas fa-edit"></i>
                                            <span class="ml-1">Edit</span>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="confirmDelete({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Delete Role"
                                                {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) || $role->users_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash-alt"></i>
                                            <span class="ml-1">Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-shield-alt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-dark">No roles found</h5>
                                        <p class="text-muted">
                                            @if($search)
                                                No roles matching "<strong>{{ $search }}</strong>". 
                                                <a href="#" wire:click.prevent="$set('search', '')" class="text-primary">
                                                    Clear search
                                                </a>
                                            @else
                                                Get started by creating your first role.
                                            @endif
                                        </p>
                                        <button class="btn btn-success mt-2" wire:click="openCreateModal">
                                            <i class="fas fa-plus-circle mr-1"></i> 
                                            Create New Role
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Footer with Pagination -->
            @if($roles->hasPages() || $roles->total() > 0)
            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted small mb-3 mb-md-0">
                        <i class="fas fa-list mr-1"></i>
                        Showing 
                        <span class="font-weight-medium">{{ $roles->firstItem() ?? 0 }}</span>
                        to 
                        <span class="font-weight-medium">{{ $roles->lastItem() ?? 0 }}</span>
                        of 
                        <span class="font-weight-medium">{{ number_format($roles->total()) }}</span> 
                        roles
                    </div>

                    <div class="pagination-wrapper">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Create Role Modal -->
    @if($showCreateModal)
    <div class="modal fade show d-block" id="createRoleModal" tabindex="-1" role="dialog" 
         style="background-color: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Create New Role
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showCreateModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="createRole">
                        <!-- Role Name -->
                        <div class="form-group">
                            <label class="font-weight-bold">
                                Role Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-tag text-primary"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       class="form-control @error('roleName_input') is-invalid @enderror"
                                       wire:model="roleName_input" 
                                       placeholder="e.g., content-manager, editor, viewer">
                            </div>
                            @error('roleName_input')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Use lowercase letters, numbers, and hyphens only.
                            </small>
                        </div>

                        <!-- Permissions Selection -->
                        <div class="form-group">
                            <label class="font-weight-bold d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-key text-warning mr-1"></i>
                                    Permissions
                                </span>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" 
                                            wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})">
                                        <i class="fas fa-check-double mr-1"></i> All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" 
                                            wire:click="$set('selectedPermissions', [])">
                                        <i class="fas fa-times mr-1"></i> None
                                    </button>
                                </div>
                            </label>
                            
                            <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                                @forelse($groupedPermissions as $group => $permissionGroup)
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-folder-open mr-2"></i>
                                        {{ $group }}
                                        <span class="badge badge-info ml-2">{{ $permissionGroup->count() }}</span>
                                    </h6>
                                    <div class="row">
                                        @foreach($permissionGroup as $permission)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input"
                                                       id="perm_{{ $permission->id }}"
                                                       wire:model="selectedPermissions"
                                                       value="{{ $permission->id }}">
                                                <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    @if($permission->description)
                                                    <small class="text-muted d-block">{{ $permission->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted text-center py-3">No permissions available</p>
                                @endforelse
                            </div>
                            <small class="form-text text-muted mt-2">
                                Selected: <span class="badge badge-primary">{{ count($selectedPermissions) }}</span> permissions
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showCreateModal', false)">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-success" wire:click="createRole" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="createRole">
                            <i class="fas fa-save mr-1"></i> Create Role
                        </span>
                        <span wire:loading wire:target="createRole">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Role Modal -->
    @if($showEditModal && $role)
    <div class="modal fade show d-block" id="editRoleModal" tabindex="-1" role="dialog" 
         style="background-color: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Role: {{ $role->display_name ?? $role->name }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showEditModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateRole">
                        <!-- Role Name -->
                        <div class="form-group">
                            <label class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-tag text-primary"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       class="form-control @error('roleName_input') is-invalid @enderror"
                                       wire:model="roleName_input"
                                       {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) ? 'readonly' : '' }}>
                            </div>
                            @error('roleName_input')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Selection -->
                        <div class="form-group">
                            <label class="font-weight-bold d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-key text-warning mr-1"></i>
                                    Permissions
                                </span>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary" 
                                            wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})">
                                        <i class="fas fa-check-double mr-1"></i> All
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" 
                                            wire:click="$set('selectedPermissions', [])">
                                        <i class="fas fa-times mr-1"></i> None
                                    </button>
                                </div>
                            </label>
                            
                            <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                                @forelse($groupedPermissions as $group => $permissionGroup)
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2">
                                        <i class="fas fa-folder-open mr-2"></i>
                                        {{ $group }}
                                        <span class="badge badge-info ml-2">{{ $permissionGroup->count() }}</span>
                                    </h6>
                                    <div class="row">
                                        @foreach($permissionGroup as $permission)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input"
                                                       id="edit_perm_{{ $permission->id }}"
                                                       wire:model="selectedPermissions"
                                                       value="{{ $permission->id }}">
                                                <label class="custom-control-label" for="edit_perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    @if($permission->description)
                                                    <small class="text-muted d-block">{{ $permission->description }}</small>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @empty
                                <p class="text-muted text-center py-3">No permissions available</p>
                                @endforelse
                            </div>
                            <small class="form-text text-muted mt-2">
                                Selected: <span class="badge badge-primary">{{ count($selectedPermissions) }}</span> permissions
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showEditModal', false)">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="updateRole" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="updateRole">
                            <i class="fas fa-save mr-1"></i> Update Role
                        </span>
                        <span wire:loading wire:target="updateRole">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Updating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Manage Permissions Modal -->
    @if($showPermissionsModal && $role)
    <div class="modal fade show d-block" id="permissionsModal" tabindex="-1" role="dialog" 
         style="background-color: rgba(0,0,0,0.5); overflow-y: auto;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-key mr-2"></i>
                        Manage Permissions: {{ $role->display_name ?? $role->name }}
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showPermissionsModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Role Info -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x mr-3"></i>
                            <div>
                                <strong>{{ $role->name }}</strong> is currently assigned to 
                                <span class="badge badge-primary">{{ $role->users_count }}</span> user(s).
                                @if($role->users_count > 0)
                                <br><small>Changes will affect all users with this role.</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Selection -->
                    <div class="form-group">
                        <label class="font-weight-bold d-flex justify-content-between align-items-center mb-3">
                            <span>
                                <i class="fas fa-key text-warning mr-1"></i>
                                Assign Permissions
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" 
                                        wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})">
                                    <i class="fas fa-check-double mr-1"></i> Select All
                                </button>
                                <button type="button" class="btn btn-outline-secondary" 
                                        wire:click="$set('selectedPermissions', [])">
                                    <i class="fas fa-times mr-1"></i> Clear All
                                </button>
                            </div>
                        </label>
                        
                        <div class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                            @forelse($groupedPermissions as $group => $permissionGroup)
                            <div class="mb-4">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-folder-open mr-2"></i>
                                    {{ $group }}
                                    <span class="badge badge-info ml-2">{{ $permissionGroup->count() }}</span>
                                </h6>
                                <div class="row">
                                    @foreach($permissionGroup as $permission)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" 
                                                   class="custom-control-input"
                                                   id="perm_manage_{{ $permission->id }}"
                                                   wire:model="selectedPermissions"
                                                   value="{{ $permission->id }}">
                                            <label class="custom-control-label" for="perm_manage_{{ $permission->id }}">
                                                {{ $permission->name }}
                                                @if($permission->description)
                                                <small class="text-muted d-block">{{ $permission->description }}</small>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center py-3">No permissions available</p>
                            @endforelse
                        </div>
                        <small class="form-text text-muted mt-2">
                            Selected: <span class="badge badge-primary">{{ count($selectedPermissions) }}</span> permissions
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showPermissionsModal', false)">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-info" wire:click="updatePermissions" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="updatePermissions">
                            <i class="fas fa-save mr-1"></i> Save Permissions
                        </span>
                        <span wire:loading wire:target="updatePermissions">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $role)
    <div class="modal fade show d-block" id="deleteModal" tabindex="-1" role="dialog" 
         style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="close text-white" wire:click="$set('showDeleteModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-4x text-danger mb-3"></i>
                        <h5>Are you sure?</h5>
                        <p class="text-muted">
                            You are about to delete the role <strong>"{{ $role->display_name ?? $role->name }}"</strong>.
                            This action cannot be undone.
                        </p>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-tag mr-2 text-muted"></i>Role Name</span>
                            <span class="font-weight-bold">{{ $role->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users mr-2 text-muted"></i>Assigned Users</span>
                            <span class="badge badge-primary">{{ $role->users_count }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-key mr-2 text-muted"></i>Permissions</span>
                            <span class="badge badge-success">{{ $role->permissions_count }}</span>
                        </li>
                    </ul>
                    
                    @if($role->users_count > 0)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Cannot delete role with assigned users. Please reassign or remove users first.
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete" 
                            {{ $role->users_count > 0 ? 'disabled' : '' }}
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="delete">
                            <i class="fas fa-trash mr-1"></i> Delete Role
                        </span>
                        <span wire:loading wire:target="delete">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Deleting...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom CSS -->
    <style>
        /* Table styling - Light and clean */
        .table {
            background-color: #ffffff;
            color: #212529;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            padding: 12px 8px;
            color: #495057;
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table tbody td {
            padding: 12px 8px;
            vertical-align: middle;
            color: #212529;
        }
        
        /* Badge styling */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
        }
        
        .badge-primary {
            background-color: #007bff;
            color: #ffffff;
        }
        
        .badge-success {
            background-color: #28a745;
            color: #ffffff;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: #ffffff;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: #ffffff;
        }
        
        .badge-secondary {
            background-color: #6c757d;
            color: #ffffff;
        }
        
        .badge-light {
            background-color: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        
        /* Button groups */
        .btn-group .btn {
            margin: 0 2px;
            border-radius: 4px;
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
            transition: all 0.2s;
        }
        
        .btn-group .btn i {
            margin-right: 4px;
        }
        
        .btn-outline-info {
            color: #17a2b8;
            border-color: #17a2b8;
        }
        
        .btn-outline-info:hover {
            background-color: #17a2b8;
            color: #ffffff;
        }
        
        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }
        
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        
        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: #ffffff;
        }
        
        /* Role icons */
        .role-icon {
            width: 32px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        /* Card styling */
        .card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            border: 1px solid #e9ecef;
        }
        
        .card-header {
            padding: 1rem 1.25rem;
            background-color: #ffffff;
        }
        
        .card-header .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #212529;
        }
        
        /* Modal styling */
        .modal-content {
            border-radius: 12px;
            overflow: hidden;
            border: none;
        }
        
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        
        /* Custom checkbox */
        .custom-control-label {
            cursor: pointer;
            font-size: 0.85rem;
            color: #212529;
        }
        
        .custom-control-label small {
            font-size: 0.7rem;
            color: #6c757d;
        }
        
        /* Pagination styling */
        .pagination-wrapper .pagination {
            margin-bottom: 0;
        }
        
        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #ffffff;
        }
        
        .pagination-wrapper .page-link {
            color: #007bff;
            padding: 0.5rem 0.75rem;
            border: 1px solid #dee2e6;
        }
        
        .pagination-wrapper .page-link:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }
        
        /* Empty state */
        .empty-state {
            padding: 3rem 1rem;
        }
        
        /* Alert styling */
        .alert-info {
            background-color: #d1ecf1;
            border-color: #bee5eb;
            color: #0c5460;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeeba;
            color: #856404;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .btn-group .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }
            
            .btn-group .btn i {
                margin-right: 2px;
            }
            
            .table {
                font-size: 0.8rem;
            }
            
            .badge {
                font-size: 0.65rem;
                padding: 0.25rem 0.5rem;
            }
            
            .table thead th {
                font-size: 0.7rem;
                padding: 8px 4px;
            }
            
            .table tbody td {
                padding: 8px 4px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .table tbody tr {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Form controls */
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* Text colors */
        .text-muted {
            color: #6c757d !important;
        }
        
        .text-primary {
            color: #007bff !important;
        }
        
        .text-success {
            color: #28a745 !important;
        }
        
        .text-warning {
            color: #ffc107 !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-info {
            color: #17a2b8 !important;
        }
    </style>

    <!-- Initialize tooltips -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Initialize Bootstrap tooltips
            if (typeof $ !== 'undefined' && $.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip();
                
                // Reinitialize tooltips after Livewire updates
                Livewire.hook('element.updated', (el, component) => {
                    setTimeout(() => {
                        $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
                    }, 100);
                });
            }
        });
    </script>
</div>