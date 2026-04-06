<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-user-tag mr-2"></i>Edit Role: {{ $role->name }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.roles-and-permissions.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')


            <form wire:submit.prevent="submit">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input class="form-control" type="text" name="name"
                                label="Name"
                                wire:model="name"
                                placeholder="Enter role name" />
                        </div>

                        @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Permissions</label>
                            <div class="mb-2">
                                <button type="button"
                                    class="btn btn-sm btn-outline-primary mr-2"
                                    wire:click="selectAllPermissions">
                                    <i class="fas fa-check-double"></i> Select All
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    wire:click="clearAllPermissions">
                                    <i class="fas fa-times"></i> Clear All
                                </button>
                            </div>
                            <div class="card">
                                <div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="perm_{{ $permission->id }}"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->name }}"
                                                    @if(in_array($permission->name, $selectedPermissions)) checked @endif>
                                                <label class="custom-control-label"
                                                    for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Selected:</strong>
                                            <span class="badge badge-primary">
                                                {{ count($selectedPermissions) }} permissions
                                            </span>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <strong>Total:</strong>
                                            <span class="badge badge-secondary">
                                                {{ count($permissions) }} permissions
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error('selectedPermissions')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>