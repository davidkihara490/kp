<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-user-plus mr-2"></i>Create New Driver
            </h3>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <form wire:submit.prevent="submit">
                <!-- Personal Information -->
                <div class="card card-primary mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-user-circle mr-2"></i>Personal Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        wire:model="first_name" placeholder="Enter first name">
                                    @error('first_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Middle Name</label>
                                    <input type="text" class="form-control @error('second_name') is-invalid @enderror"
                                        wire:model="second_name" placeholder="Enter middle name">
                                    @error('second_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        wire:model="last_name" placeholder="Enter last name">
                                    @error('last_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                        wire:model="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('gender')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror"
                                        wire:model="id_number" placeholder="National ID/Passport">
                                    @error('id_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Availability</label>
                                    <div class="custom-control custom-switch mt-3">
                                        <input type="checkbox" class="custom-control-input"
                                            id="is_available" wire:model="is_available">
                                        <label class="custom-control-label" for="is_available">
                                            {{ $is_available ? 'Available' : 'Unavailable' }}
                                        </label>
                                    </div>
                                    @error('is_available')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card card-warning mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-address-card mr-2"></i>Contact Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        wire:model="email" placeholder="Enter email">
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                        wire:model="phone_number" placeholder="2547XXXXXXXX">
                                    @error('phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Alternate Phone</label>
                                    <input type="tel" class="form-control @error('alternate_phone_number') is-invalid @enderror"
                                        wire:model="alternate_phone_number" placeholder="Alternate phone">
                                    @error('alternate_phone_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Driving License Information -->
                <div class="card card-success mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-id-card mr-2"></i>Driving License Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>License Number *</label>
                                    <input type="text" class="form-control @error('driving_license_number') is-invalid @enderror"
                                        wire:model="driving_license_number" placeholder="DL-XXXXXXX">
                                    @error('driving_license_number')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>License Class *</label>
                                    <select class="form-control @error('license_class') is-invalid @enderror"
                                        wire:model="license_class">
                                        <option value="A">Class A (Motorcycles)</option>
                                        <option value="B">Class B (Light Vehicles)</option>
                                        <option value="C">Class C (Heavy Vehicles)</option>
                                        <option value="D">Class D (Trailers)</option>
                                        <option value="E">Class E (Construction)</option>
                                        <option value="F">Class F (Special)</option>
                                    </select>
                                    @error('license_class')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Issue Date *</label>
                                    <input type="date" class="form-control @error('driving_license_issue_date') is-invalid @enderror"
                                        wire:model="driving_license_issue_date">
                                    @error('driving_license_issue_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Expiry Date *</label>
                                    <input type="date" class="form-control @error('driving_license_expiry_date') is-invalid @enderror"
                                        wire:model="driving_license_expiry_date" min="{{ now()->format('Y-m-d') }}">
                                    @error('driving_license_expiry_date')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-info" style="margin-top: 8px;">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    License must be valid (not expired) for driver to be available.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="card card-danger mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-ambulance mr-2"></i>Emergency Contact
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contact Name</label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                        wire:model="emergency_contact_name" placeholder="Full name">
                                    @error('emergency_contact_name')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                        wire:model="emergency_contact_phone" placeholder="Phone number">
                                    @error('emergency_contact_phone')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Relationship</label>
                                    <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                                        wire:model="emergency_contact_relationship" placeholder="e.g., Spouse, Parent">
                                    @error('emergency_contact_relationship')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- User Account -->
                <div class="card card-indigo mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-user-lock mr-2"></i>User Account
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('password') is-invalid @enderror"
                                            wire:model="password" placeholder="Password">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                wire:click="generatePassword">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Confirm Password *</label>
                                    <input type="text" class="form-control @error('password_confirmation') is-invalid @enderror"
                                        wire:model="password_confirmation" placeholder="Confirm password">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>User Role *</label>
                                    <select class="form-control"
                                        wire:model="role_id">
                                        @forelse($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @empty
                                        <option value="">No roles were found</option>
                                        @endforelse
                                    </select>
                                    @error('role_id')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            A user account will be created for this driver using the provided email and password.
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="card card-gray mb-4" style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <div class="card-header" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <h3 class="card-title">
                            <i class="fas fa-sticky-note mr-2"></i>Additional Notes
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                wire:model="notes" rows="3" placeholder="Any additional information about this driver"></textarea>
                            @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary" wire:click="submit" wire:loading.attr="disabled"
                wire:target="submit">
                <span wire:loading.remove wire:target="submit">
                    <i class="fas fa-plus mr-2"></i>Create Driver
                </span>
                <span wire:loading wire:target="submit">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Creating...
                </span>
            </button>
            <a href="{{ route('partners.drivers.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <div class="float-right">
                <span class="text-muted">
                    Fields marked with * are required
                </span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card {
        margin-bottom: 30px !important;
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        padding: 15px 20px;
        background-color: rgba(0, 0, 0, 0.02);
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 25px 20px;
    }

    .card-title {
        margin-bottom: 0;
        font-weight: 600;
    }

    /* Card color variations */
    .card-primary {
        border-left: 4px solid #007bff;
    }

    .card-warning {
        border-left: 4px solid #ffc107;
    }

    .card-success {
        border-left: 4px solid #28a745;
    }

    .card-danger {
        border-left: 4px solid #dc3545;
    }

    .card-teal {
        border-left: 4px solid #20c997;
    }

    .card-indigo {
        border-left: 4px solid #6610f2;
    }

    .card-gray {
        border-left: 4px solid #6c757d;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #495057;
    }

    .form-control {
        padding: 10px 15px;
        border-radius: 6px;
        border: 1px solid #ced4da;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Alert styling */
    .alert {
        border-radius: 8px;
        border: 1px solid transparent;
        padding: 15px;
        margin-bottom: 0;
    }

    /* Button styling */
    .btn {
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Input group styling */
    .input-group-append .btn {
        border-radius: 0 6px 6px 0;
    }

    /* Switch styling */
    .custom-switch {
        padding-left: 2.5rem;
        margin-top: 5px;
    }

    .custom-control-label::before {
        width: 2.5rem;
        height: 1.25rem;
        border-radius: 1rem;
    }

    .custom-control-label::after {
        width: 1rem;
        height: 1rem;
        border-radius: 1rem;
        top: 0.125rem;
        left: -2.25rem;
    }

    /* Row spacing */
    .row {
        margin-bottom: 10px;
    }

    .row.mt-3 {
        margin-top: 20px !important;
    }

    /* Footer spacing */
    .card-footer {
        padding: 20px;
        background-color: rgba(0, 0, 0, 0.02);
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Card shadow on focus */
    .form-control:focus~.card {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }
</style>
@endpush