<div>
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">
                        <i class="fas fa-shield-alt mr-2 text-primary"></i>
                        Roles & Permissions
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <!-- Action Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-success btn-sm" wire:click="openCreateModal">
                                <i class="fas fa-plus-circle mr-1"></i> 
                                Create New Role
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Dashboard -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalRoles) }}</h3>
                        <p>Total Roles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($totalPermissions) }}</h3>
                        <p>Total Permissions</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ number_format($totalUsersWithRoles) }}</h3>
                        <p>Users with Roles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $mostAssignedRole }}</h3>
                        <p>Most Popular Role</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Roles Management
                </h3>
                <div class="card-tools">
                    <!-- Search -->
                    <div class="input-group input-group-sm" style="width: 300px;">
                        <input type="text" 
                               class="form-control" 
                               wire:model.live.debounce.500ms="search" 
                               placeholder="Search roles by name...">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        @if($search)
                        <div class="input-group-append">
                            <button class="btn btn-default" type="button" wire:click="$set('search', '')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @include('components.alerts.response-alerts')

                <div class="table-responsive">
                    <table class="table table-hover table-dark table-striped table-bordered mb-0">
                        <thead class="thead-dark" style="background-color: #343a40;">
                            <tr>
                                <th class="text-center" width="60">#</th>
                                <th>Role Name</th>
                                <th class="text-center" width="80">Users</th>
                                <th class="text-center" width="100">Permissions</th>
                                <th>Permissions List</th>
                                <th class="text-center" width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $index => $role)
                            <tr wire:key="role-{{ $role->id }}" style="line-height: 1.2; height: 45px;">
                                <td class="text-center align-middle">
                                    {{ $roles->firstItem() + $index }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span class="font-weight-bold">{{ $role->display_name ?? $role->name }}</span>
                                            @if(in_array($role->name, ['super-admin', 'admin', 'partner-admin']))
                                            <span class="badge badge-danger ml-2">System</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-info badge-pill px-3 py-1">
                                        {{ $role->users_count }}
                                    </span>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-success badge-pill px-3 py-1">
                                        {{ $role->permissions_count }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex flex-wrap" style="max-width: 300px;">
                                        @forelse($role->permissions->take(4) as $permission)
                                        <span class="badge badge-primary mr-1 mb-1 px-2 py-1" style="font-size: 0.75rem;">
                                            {{ \Illuminate\Support\Str::limit($permission->name, 18) }}
                                        </span>
                                        @empty
                                        <span class="text-muted font-italic">No permissions assigned</span>
                                        @endforelse
                                        @if($role->permissions_count > 4)
                                        <span class="badge badge-secondary px-2 py-1" style="font-size: 0.75rem;">
                                            +{{ $role->permissions_count - 4 }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-info" 
                                                wire:click="openPermissionsModal({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Manage Permissions">
                                            <i class="fas fa-key"></i>
                                        </button>

                                        <button class="btn btn-primary" 
                                                wire:click="openEditModal({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Edit Role"
                                                {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) ? 'disabled' : '' }}>
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-danger" 
                                                wire:click="confirmDelete({{ $role->id }})"
                                                data-toggle="tooltip" 
                                                title="Delete Role"
                                                {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) || $role->users_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-shield-alt fa-4x text-muted mb-3"></i>
                                        <h5 class="text-light">No roles found</h5>
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
                                        <button class="btn btn-success" wire:click="openCreateModal">
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
            <div class="card-footer">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="text-muted small mb-3 mb-md-0">
                        <i class="fas fa-list mr-1"></i>
                        Showing 
                        <span class="font-weight-medium">{{ $roles->firstItem() ?? 0 }}</span>
                        to 
                        <span class="font-weight-medium">{{ $roles->lastItem() ?? 0 }}</span>
                        of 
                        <span class="font-weight-medium">{{ number_format($roles->total()) }}</span> 
                        entries
                    </div>

                    <div class="d-flex align-items-center">
                        <!-- Per Page Selector -->
                        <div class="mr-3">
                            <select wire:model.live="perPage" class="form-control form-control-sm" style="width: 70px;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-wrapper">
                            {{ $roles->links() }}
                        </div>
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
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">
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
                            <label for="roleName">
                                Role Name <span class="text-danger">*</span>
                                <i class="fas fa-question-circle text-muted ml-1" 
                                   data-toggle="tooltip" 
                                   title="Unique identifier for the role. Use lowercase letters, numbers, and hyphens only.">
                                </i>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text" 
                                       class="form-control @error('roleName_input') is-invalid @enderror"
                                       id="roleName" 
                                       wire:model="roleName_input" 
                                       placeholder="e.g., content-manager, editor, viewer">
                            </div>
                            @error('roleName_input')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                Use lowercase letters, numbers, and hyphens only. Example: content-manager
                            </small>
                        </div>

                        <!-- Permissions Selection -->
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-key mr-1 text-warning"></i>
                                    Permissions
                                    <span class="badge badge-primary ml-2">{{ count($selectedPermissions) }} selected</span>
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
                            
                            <div class="card card-outline card-primary">
                                <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                                    @forelse($groupedPermissions as $group => $permissionGroup)
                                    <div class="mb-3">
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
                            </div>
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
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">
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
                            <label for="editRoleName">Role Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('roleName_input') is-invalid @enderror"
                                   id="editRoleName" 
                                   wire:model="roleName_input"
                                   {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) ? 'readonly' : '' }}>
                            @error('roleName_input')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions Selection -->
                        <div class="form-group">
                            <label class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-key mr-1 text-warning"></i>
                                    Permissions
                                    <span class="badge badge-primary ml-2">{{ count($selectedPermissions) }} selected</span>
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
                            
                            <div class="card card-outline card-primary">
                                <div class="card-body p-2" style="max-height: 350px; overflow-y: auto;">
                                    @forelse($groupedPermissions as $group => $permissionGroup)
                                    <div class="mb-3">
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
                            </div>
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
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">
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
                                <span class="badge badge-primary badge-lg">{{ $role->users_count }}</span> user(s).
                                @if($role->users_count > 0)
                                <br><small>Changes will affect all users with this role.</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Selection -->
                    <div class="form-group">
                        <label class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 mb-0">
                                <i class="fas fa-key mr-1 text-warning"></i>
                                Assign Permissions
                                <span class="badge badge-primary ml-2">{{ count($selectedPermissions) }} selected</span>
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
                        
                        <div class="card card-outline card-info">
                            <div class="card-body p-2" style="max-height: 400px; overflow-y: auto;">
                                @forelse($groupedPermissions as $group => $permissionGroup)
                                <div class="mb-3">
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
                        </div>
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
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">
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
                            <span class="badge badge-primary badge-lg">{{ $role->users_count }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-key mr-2 text-muted"></i>Permissions</span>
                            <span class="badge badge-success badge-lg">{{ $role->permissions_count }}</span>
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

    <!-- Add this CSS to your layout or component -->
    <style>
        .small-box {
            border-radius: 0.25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            display: block;
            margin-bottom: 20px;
            position: relative;
        }
        
        .small-box > .inner {
            padding: 10px;
        }
        
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px;
            padding: 0;
            white-space: nowrap;
            color: #fff;
        }
        
        .small-box p {
            color: #fff;
            font-size: 1rem;
        }
        
        .small-box .icon {
            color: rgba(0,0,0,0.15);
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 0;
        }
        
        .small-box .icon > i {
            font-size: 70px;
        }
        
        /* Dark table styling */
        .table-dark {
            background-color: #1e1e2d;
            color: #fff;
        }
        
        .table-dark thead th {
            background-color: #2d2d3a;
            border-bottom: 2px solid #444;
            color: #fff;
            font-weight: 600;
            padding: 8px 5px;
        }
        
        .table-dark tbody tr {
            background-color: #2a2a36;
            border-bottom: 1px solid #3d3d4a;
        }
        
        .table-dark tbody tr:hover {
            background-color: #333340;
        }
        
        .table-dark tbody td {
            border-color: #3d3d4a;
            padding: 6px 5px;
        }
        
        .table-dark .badge-info {
            background-color: #17a2b8;
        }
        
        .table-dark .badge-success {
            background-color: #28a745;
        }
        
        .table-dark .badge-primary {
            background-color: #007bff;
        }
        
        .table-dark .badge-secondary {
            background-color: #6c757d;
        }
        
        .table-dark .btn-group .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        
        /* Reduced row height */
        .table tbody tr {
            height: 40px;
        }
        
        .table tbody td {
            vertical-align: middle;
            line-height: 1.2;
        }
        
        .badge-pill {
            border-radius: 10rem;
        }
        
        .modal {
            overflow-y: auto;
            padding-right: 0 !important;
        }
        
        .modal-dialog {
            pointer-events: all;
        }
        
        .custom-checkbox .custom-control-label {
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .custom-checkbox .custom-control-label small {
            font-size: 0.75rem;
        }
        
        /* Loading states */
        [wire\:loading] {
            cursor: wait;
            opacity: 0.7;
        }
        
        /* Pagination styling */
        .pagination-wrapper .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }
        
        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }
        
        .pagination-wrapper .page-link {
            color: #007bff;
            padding: 0.3rem 0.7rem;
        }
        
        @media (max-width: 768px) {
            .small-box h3 {
                font-size: 1.8rem;
            }
            
            .small-box .icon > i {
                font-size: 50px;
            }
            
            .table {
                font-size: 0.8rem;
            }
            
            .btn-group .btn {
                padding: 0.15rem 0.3rem;
            }
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