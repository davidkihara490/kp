<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-user-tag mr-2"></i>Create Role
            </h3>
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
                            <div class="card">
                                <div class="card-body p-3" style="max-height: 300px; overflow-y: auto;">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    id="perm_{{ $permission->id }}"
                                                    wire:model="selectedPermissions"
                                                    value="{{ $permission->id }}">
                                                <label class="custom-control-label"
                                                    for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
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
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>