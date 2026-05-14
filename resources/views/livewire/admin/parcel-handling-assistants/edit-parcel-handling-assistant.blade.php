<div>
   <div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">
                            <i class="fas fa-user-edit"></i> Edit Parcel Handling Assistant
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.pha.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <form wire:submit.prevent="update">
                        <div class="card-body">
                            @include('components.alerts.response-alerts')
                            
                            <div class="row">
                                <!-- Personal Information Section -->
                                <div class="col-md-6">
                                    <div class="card card-outline card-primary">
                                        <div class="card-header">
                                            <h5 class="card-title">Personal Information</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="first_name">First Name <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       id="first_name" 
                                                       wire:model.live="first_name" 
                                                       class="form-control @error('first_name') is-invalid @enderror"
                                                       placeholder="Enter first name">
                                                @error('first_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="second_name">Second Name</label>
                                                <input type="text" 
                                                       id="second_name" 
                                                       wire:model.live="second_name" 
                                                       class="form-control @error('second_name') is-invalid @enderror"
                                                       placeholder="Enter second name (optional)">
                                                @error('second_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       id="last_name" 
                                                       wire:model.live="last_name" 
                                                       class="form-control @error('last_name') is-invalid @enderror"
                                                       placeholder="Enter last name">
                                                @error('last_name')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="id_number">ID Number <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       id="id_number" 
                                                       wire:model.live="id_number" 
                                                       class="form-control @error('id_number') is-invalid @enderror"
                                                       placeholder="Enter national ID number">
                                                @error('id_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <div class="col-md-6">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h5 class="card-title">Contact Information</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="email">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" 
                                                       id="email" 
                                                       wire:model.live="email" 
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       placeholder="Enter email address">
                                                @error('email')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                                <input type="tel" 
                                                       id="phone_number" 
                                                       wire:model.live="phone_number" 
                                                       class="form-control @error('phone_number') is-invalid @enderror"
                                                       placeholder="Enter phone number">
                                                @error('phone_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Account Information Section -->
                                    <div class="card card-outline card-warning mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title">Account Information</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="username">Username <span class="text-danger">*</span></label>
                                                <input type="text" 
                                                       id="username" 
                                                       wire:model.live="username" 
                                                       class="form-control @error('username') is-invalid @enderror"
                                                       placeholder="Enter username">
                                                <small class="form-text text-muted">Username must be unique</small>
                                                @error('username')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <label>Password</label>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-info" 
                                                            wire:click="togglePasswordField">
                                                        <i class="fas fa-key"></i> 
                                                        {{ $showPasswordField ? 'Cancel' : 'Change Password' }}
                                                    </button>
                                                </div>
                                                
                                                @if($showPasswordField)
                                                    <div class="mt-2">
                                                        <input type="password" 
                                                               id="password" 
                                                               wire:model.live="password" 
                                                               class="form-control @error('password') is-invalid @enderror mt-2"
                                                               placeholder="Enter new password (min. 8 characters)">
                                                        @error('password')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror

                                                        <input type="password" 
                                                               id="password_confirmation" 
                                                               wire:model.live="password_confirmation" 
                                                               class="form-control @error('password_confirmation') is-invalid @enderror mt-2"
                                                               placeholder="Confirm new password">
                                                        @error('password_confirmation')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                        <small class="form-text text-muted">Leave password fields empty to keep current password</small>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info mt-2 mb-0">
                                                        <small><i class="fas fa-info-circle"></i> Password is hidden. Click "Change Password" to update.</small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assignment Information Section -->
                                <div class="col-12">
                                    <div class="card card-outline card-success">
                                        <div class="card-header">
                                            <h5 class="card-title">Assignment Information</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="partner_id">Partner/Transport Company <span class="text-danger">*</span></label>
                                                        <select id="partner_id" 
                                                                wire:model.live="partner_id" 
                                                                class="form-control @error('partner_id') is-invalid @enderror">
                                                            <option value="">Select Partner</option>
                                                            @foreach($partners as $partner)
                                                                <option value="{{ $partner->id }}" 
                                                                        {{ $partner_id == $partner->id ? 'selected' : '' }}>
                                                                    {{ $partner->company_name }} - {{ $partner->registration_number ?? '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('partner_id')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status <span class="text-danger">*</span></label>
                                                        <select id="status" 
                                                                wire:model.live="status" 
                                                                class="form-control @error('status') is-invalid @enderror">
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                            <option value="pending">Pending</option>
                                                        </select>
                                                        @error('status')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                        <small class="form-text text-muted">
                                                            <span class="badge badge-success">Active</span> - Can access system<br>
                                                            <span class="badge badge-warning">Inactive</span> - Temporarily disabled<br>
                                                            <span class="badge badge-info">Pending</span> - Awaiting approval
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Info Card -->
                                <div class="col-12">
                                    <div class="card card-outline card-secondary">
                                        <div class="card-header">
                                            <h5 class="card-title">System Information</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted">Created At:</small><br>
                                                    <strong>{{ $parcelHandlingAssistant->created_at ? $parcelHandlingAssistant->created_at->format('d/m/Y H:i') : 'N/A' }}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Last Updated:</small><br>
                                                    <strong>{{ $parcelHandlingAssistant->updated_at ? $parcelHandlingAssistant->updated_at->format('d/m/Y H:i') : 'N/A' }}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">User Account ID:</small><br>
                                                    <strong>#{{ $user_id ?? 'N/A' }}</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted">Assistant ID:</small><br>
                                                    <strong>#{{ $pha_id }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save"></i> Update Assistant
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin"></i> Updating...
                                        </span>
                                    </button>
                                    <button type="button" class="btn btn-secondary" wire:click="cancel">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="if(confirm('Are you sure you want to reset all changes?')) window.location.reload()">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Auto-generate username when email is entered (only if username hasn't been manually edited)
        let usernameManuallyEdited = false;
        
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            usernameInput.addEventListener('input', () => {
                usernameManuallyEdited = true;
            });
        }
        
        Livewire.hook('element.updated', (el, component) => {
            if (el.id === 'email' && !usernameManuallyEdited) {
                let email = el.value;
                if (email && email.includes('@')) {
                    let username = email.split('@')[0];
                    // Check if username exists
                    Livewire.dispatch('check-username', { username: username, component: component });
                    component.set('username', username);
                }
            }
        });
    });
</script>
@endpush
</div>
