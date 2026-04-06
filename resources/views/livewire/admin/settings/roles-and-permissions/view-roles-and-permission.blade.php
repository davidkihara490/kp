<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-user-tag mr-2"></i>View Role: {{ $role->name }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.roles-and-permissions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Roles
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Role Info -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <h5><i class="fas fa-info-circle mr-2"></i> Role Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4 text-muted">Role Name:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-primary badge-lg">{{ $role->name }}</span>
                                    </dd>

                                    <dt class="col-sm-4 text-muted">Guard:</dt>
                                    <dd class="col-sm-8">
                                        <span class="badge badge-secondary">{{ $role->guard_name ?? 'web' }}</span>
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="row">
                                    <dt class="col-sm-4 text-muted">Created:</dt>
                                    <dd class="col-sm-8">{{ $role->created_at?->format('d M, Y H:i') ?? 'N/A' }}</dd>

                                    <dt class="col-sm-4 text-muted">Updated:</dt>
                                    <dd class="col-sm-8">{{ $role->updated_at?->format('d M, Y H:i') ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-key mr-1"></i>
                                Permissions
                                <span class="badge badge-info ml-2">{{ $role->permissions->count() }} Total</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($role->permissions->count() > 0)
                                <div class="row">
                                    @foreach($role->permissions->groupBy(function($permission) {
                                        return explode('.', $permission->name)[0] ?? 'general';
                                    }) as $group => $groupedPermissions)
                                        <div class="col-md-6 mb-4">
                                            <div class="card card-secondary">
                                                <div class="card-header">
                                                    <h5 class="card-title text-capitalize">
                                                        <i class="fas fa-folder-open mr-1"></i>
                                                        {{ $group }}
                                                    </h5>
                                                </div>
                                                <div class="card-body p-2">
                                                    @foreach($groupedPermissions as $permission)
                                                        <span class="badge badge-primary m-1 p-2">
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                            {{ $permission->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No permissions assigned to this role.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info (if needed) -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="alert alert-light">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> Users with this role will have access to all the permissions listed above.
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.roles-and-permissions.edit', $role->id) }}" class="btn btn-info">
                    <i class="fas fa-edit mr-1"></i> Edit Role
                </a>
                <!-- <button type="button" 
                        class="btn btn-danger" 
                        wire:click="confirmDelete({{ $role->id }})"
                        @if($role->name === 'Super Admin' || $role->name === 'Admin') disabled @endif>
                    <i class="fas fa-trash mr-1"></i> Delete Role
                </button> -->
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal ?? false)
    @endif
</div>