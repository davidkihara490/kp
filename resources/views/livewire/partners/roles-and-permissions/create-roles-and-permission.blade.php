<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Roles & Permissions
                </h3>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm" wire:click="openCreateModal">
                        <i class="fas fa-plus mr-1"></i> Create New Role
                    </button>
                    
                    <button class="btn btn-outline-info btn-sm" wire:click="resetFilters">
                        <i class="fas fa-undo mr-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-shield-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Roles</span>
                            <span class="info-box-number">{{ number_format($totalRoles) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-key"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Permissions</span>
                            <span class="info-box-number">{{ number_format($totalPermissions) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Users with Roles</span>
                            <span class="info-box-number">{{ number_format($totalUsersWithRoles) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Most Assigned Role</span>
                            <span class="info-box-number">{{ $mostAssignedRole }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-filter mr-2"></i>
                        Filters
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Search</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Search by role name or guard...">
                        </div>
                        
                        <div class="col-md-4">
                            <label>Role Name</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="roleName"
                                placeholder="Filter by role name">
                        </div>
                        
                        <div class="col-md-4">
                            <label>Permission</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="permissionFilter"
                                placeholder="Filter by permission">
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <label>Date From</label>
                                    <input type="date" class="form-control" wire:model.live="dateFrom">
                                </div>
                                <div class="col-6">
                                    <label>Date To</label>
                                    <input type="date" class="form-control" wire:model.live="dateTo">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            @if(count($selectedRoles) > 0)
            <div class="alert alert-info mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ count($selectedRoles) }} role(s) selected
                    </span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            wire:click="openBulkActionModal('delete')">
                            <i class="fas fa-trash mr-1"></i> Delete Selected
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40">
                                <input type="checkbox" wire:model.live="selectAll">
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('name')">
                                    Name
                                    @if($sortField === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('guard_name')">
                                    Guard
                                    @if($sortField === 'guard_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('users_count')">
                                    Users
                                    @if($sortField === 'users_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('permissions_count')">
                                    Permissions
                                    @if($sortField === 'permissions_count')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Permissions List</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                        <tr>
                            <td>
                                <input type="checkbox"
                                    wire:model.live="selectedRoles"
                                    value="{{ $role->id }}">
                            </td>
                            <td>
                                <span class="font-weight-bold">{{ $role->name }}</span>
                                @if(in_array($role->name, ['super-admin', 'admin', 'partner-admin']))
                                <br>
                                <span class="badge badge-danger mt-1">System Role</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $role->guard_name }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $role->users_count }}</span>
                            </td>
                            <td>
                                <span class="badge badge-success">{{ $role->permissions_count }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap" style="max-width: 250px;">
                                    @forelse($role->permissions->take(3) as $permission)
                                    <span class="badge badge-primary mr-1 mb-1">
                                        {{ $permission->name }}
                                    </span>
                                    @empty
                                    <span class="text-muted">No permissions</span>
                                    @endforelse
                                    @if($role->permissions_count > 3)
                                    <span class="badge badge-secondary">+{{ $role->permissions_count - 3 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info"
                                        wire:click="openPermissionsModal({{ $role->id }})"
                                        title="Manage Permissions">
                                        <i class="fas fa-key"></i>
                                    </button>

                                    <button class="btn btn-sm btn-primary"
                                        wire:click="openEditModal({{ $role->id }})"
                                        title="Edit"
                                        {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) ? 'disabled' : '' }}>
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger"
                                        wire:click="confirmDelete({{ $role->id }})"
                                        title="Delete"
                                        {{ in_array($role->name, ['super-admin', 'admin', 'partner-admin']) || $role->users_count > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-shield-alt fa-4x text-muted mb-3"></i>
                                <h5>No roles found</h5>
                                <p class="text-muted">
                                    Try adjusting your search filters or create a new role.
                                </p>
                                <button class="btn btn-success" wire:click="openCreateModal">
                                    <i class="fas fa-plus mr-1"></i> Create New Role
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                <div class="text-muted mb-3 mb-md-0">
                    @if($roles->total() > 0)
                    Showing <span class="font-weight-medium">{{ $roles->firstItem() }}</span>
                    to <span class="font-weight-medium">{{ $roles->lastItem() }}</span>
                    of <span class="font-weight-medium">{{ number_format($roles->total()) }}</span> entries
                    @else
                    No entries found
                    @endif
                </div>

                <div class="pagination-wrapper">
                    {{ $roles->links() }}
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="d-flex justify-content-end mt-2">
                <div class="form-inline">
                    <label for="perPage" class="mr-2 small text-muted">Show:</label>
                    <select wire:model.live="perPage" id="perPage" class="form-control form-control-sm" style="width: 70px;">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Role Modal -->
    @if($showCreateModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Create New Role
                    </h5>
                    <button type="button" class="close" wire:click="$set('showCreateModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="createRole">
                        <div class="form-group">
                            <label for="roleName">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('roleName_input') is-invalid @enderror"
                                id="roleName" wire:model="roleName_input" placeholder="e.g., admin, manager, editor">
                            @error('roleName_input')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="guardName">Guard Name</label>
                            <select class="form-control @error('guardName') is-invalid @enderror"
                                id="guardName" wire:model="guardName">
                                <option value="web">Web</option>
                                <option value="api">API</option>
                            </select>
                            @error('guardName')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="selectAllPermissions"
                                            wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})"
                                            {{ count($selectedPermissions) === $permissionsList->count() ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="selectAllPermissions">
                                            Select All Permissions
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($groupedPermissions as $group => $permissionGroup)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-primary text-uppercase small font-weight-bold">{{ $group }}</h6>
                                            @foreach($permissionGroup as $permission)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="perm_{{ $permission->id }}"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}">
                                                <label class="custom-control-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showCreateModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-success" wire:click="createRole">
                        <i class="fas fa-save mr-1"></i> Create Role
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Role Modal -->
    @if($showEditModal && $role)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Role: {{ $role->name }}
                    </h5>
                    <button type="button" class="close" wire:click="$set('showEditModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateRole">
                        <div class="form-group">
                            <label for="editRoleName">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('roleName_input') is-invalid @enderror"
                                id="editRoleName" wire:model="roleName_input">
                            @error('roleName_input')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="editGuardName">Guard Name</label>
                            <select class="form-control @error('guardName') is-invalid @enderror"
                                id="editGuardName" wire:model="guardName">
                                <option value="web">Web</option>
                                <option value="api">API</option>
                            </select>
                            @error('guardName')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="editSelectAllPermissions"
                                            wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})"
                                            {{ count($selectedPermissions) === $permissionsList->count() ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="editSelectAllPermissions">
                                            Select All Permissions
                                        </label>
                                    </div>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($groupedPermissions as $group => $permissionGroup)
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-primary text-uppercase small font-weight-bold">{{ $group }}</h6>
                                            @foreach($permissionGroup as $permission)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="edit_perm_{{ $permission->id }}"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}">
                                                <label class="custom-control-label" for="edit_perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showEditModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="updateRole">
                        <i class="fas fa-save mr-1"></i> Update Role
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Manage Permissions Modal -->
    @if($showPermissionsModal && $role)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key mr-2"></i>
                        Manage Permissions - {{ $role->name }}
                    </h5>
                    <button type="button" class="close" wire:click="$set('showPermissionsModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Currently assigned to <strong>{{ $role->users_count }}</strong> user(s)
                    </div>

                    <div class="form-group">
                        <label>Permissions</label>
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="permSelectAll"
                                        wire:click="$set('selectedPermissions', {{ json_encode($permissionsList->pluck('id')->toArray()) }})"
                                        {{ count($selectedPermissions) === $permissionsList->count() ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="permSelectAll">
                                        Select All Permissions
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                                <div class="row">
                                    @foreach($groupedPermissions as $group => $permissionGroup)
                                    <div class="col-md-6 mb-3">
                                        <h6 class="text-primary text-uppercase small font-weight-bold">{{ $group }}</h6>
                                        @foreach($permissionGroup as $permission)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="perm_manage_{{ $permission->id }}"
                                                wire:model="selectedPermissions"
                                                value="{{ $permission->id }}">
                                            <label class="custom-control-label" for="perm_manage_{{ $permission->id }}">
                                                {{ $permission->name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showPermissionsModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="updatePermissions">
                        <i class="fas fa-save mr-1"></i> Save Permissions
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal && $role)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this role?</p>
                    <ul class="text-danger">
                        <li>Role: <strong>{{ $role->name }}</strong></li>
                        <li>Guard: {{ $role->guard_name }}</li>
                        <li>Users assigned: {{ $role->users_count }}</li>
                        <li>Permissions: {{ $role->permissions_count }}</li>
                        <li class="font-weight-bold">This action cannot be undone!</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showDeleteModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fas fa-trash mr-1"></i> Delete Role
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Action Modal -->
    @if($showBulkActionModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize">
                        <i class="fas fa-cogs mr-2"></i>
                        {{ ucfirst($bulkAction) }} Roles
                    </h5>
                    <button type="button" class="close" wire:click="$set('showBulkActionModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to {{ $bulkAction }} <strong>{{ count($selectedRoles) }}</strong> role(s).</p>
                    <p class="text-warning">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        System roles and roles with users will be skipped.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showBulkActionModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="executeBulkAction">
                        <i class="fas fa-check mr-1"></i> Confirm {{ ucfirst($bulkAction) }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: .25rem;
            background: #fff;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
        }

        .info-box-icon {
            border-radius: .25rem;
            -ms-flex-align: center;
            align-items: center;
            display: -ms-flexbox;
            display: flex;
            font-size: 1.875rem;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
            width: 70px;
        }

        .info-box-content {
            -ms-flex: 1;
            flex: 1;
            padding: 5px 10px;
        }

        .info-box-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 700;
            font-size: .875rem;
            text-transform: uppercase;
        }

        .info-box-number {
            display: block;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: white;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        table th a {
            text-decoration: none;
            color: inherit;
        }

        table th a:hover {
            color: #007bff;
        }

        .btn-group {
            white-space: nowrap;
        }

        .modal {
            overflow-y: auto;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item .page-link {
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 4px;
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Enhanced Pagination Styles */
        .pagination-wrapper {
            display: flex;
            align-items: center;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 2px;
        }

        .pagination-wrapper .page-item .page-link {
            padding: 0.5rem 0.75rem;
            margin: 0;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #4a5568;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            min-width: 38px;
            text-align: center;
        }

        .pagination-wrapper .page-item .page-link:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
            color: #2d3748;
            z-index: 2;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-color: #0056b3;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #a0aec0;
            background-color: #f7fafc;
            border-color: #e2e8f0;
            pointer-events: none;
            opacity: 0.6;
        }

        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        /* Loading state for pagination */
        .pagination-wrapper .page-link[wire\:loading] {
            opacity: 0.6;
            cursor: wait;
        }

        /* Per page selector */
        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-sm:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .pagination-wrapper .pagination {
                justify-content: center;
            }

            .pagination-wrapper .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
                min-width: 32px;
            }

            .pagination-wrapper .page-item:first-child .page-link,
            .pagination-wrapper .page-item:last-child .page-link {
                padding: 0.4rem 0.8rem;
            }

            .text-muted {
                font-size: 0.875rem;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .pagination-wrapper {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .pagination-wrapper .pagination {
                flex-wrap: nowrap;
                justify-content: flex-start;
            }

            .pagination-wrapper .page-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.7rem;
                min-width: 28px;
            }
        }

        .custom-checkbox {
            margin-bottom: 0.25rem;
        }

        .custom-checkbox .custom-control-label {
            font-size: 0.9rem;
        }
    </style>
</div>