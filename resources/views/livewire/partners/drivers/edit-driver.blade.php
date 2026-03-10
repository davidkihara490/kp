<div>
    <div class="dashboard-section">
    <!-- Header Section -->
    <div class="section-header">
        <div>
            <div class="d-flex align-items-center gap-3">
                <div class="driver-avatar">
                    @if($driver->profile_photo)
                        <img src="{{ asset('storage/' . $driver->profile_photo) }}" 
                             alt="{{ $driver->full_name }}"
                             class="profile-photo rounded-circle border border-3 border-primary"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                            <span class="text-white fw-bold fs-4">
                                {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="section-title mb-1">
                        Edit Driver: {{ $driver->full_name }}
                    </h3>
                    <p class="section-subtitle mb-2">
                        <i class="bi bi-person-badge me-1"></i>{{ $driver->employee_number ?? 'No ID' }} • 
                        <i class="bi bi-card-checklist me-1 ms-2"></i>{{ $driver->driving_license_number }}
                    </p>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="status-badge status-{{ $driver->getStatusBadge()['color'] }}">
                            <i class="bi {{ str_replace('fa', 'bi', $driver->getStatusBadge()['icon']) }} me-1"></i>
                            {{ ucfirst($driver->status) }}
                        </span>
                        <span class="badge bg-{{ $driver->is_available ? 'success' : 'secondary' }}">
                            <i class="bi bi-{{ $driver->is_available ? 'check' : 'x' }}-circle me-1"></i>
                            {{ $driver->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                        @php
                            $licenseColor = $this->getLicenseValidityColor();
                        @endphp
                        <span class="badge bg-{{ $licenseColor }}">
                            <i class="bi bi-{{ $licenseColor === 'success' ? 'check' : ($licenseColor === 'warning' ? 'clock' : 'exclamation-triangle') }}-circle me-1"></i>
                            {{ $this->getLicenseValidityText() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('partners.drivers.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left me-2"></i>
                Back to Drivers
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mb-4">
        <div class="card border-start border-3 border-primary shadow-sm">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Quick Actions:</small>
                    </div>
                    <div class="d-flex gap-2">
                        @if($driver->is_available)
                            <button type="button" class="btn btn-sm btn-outline-warning" wire:click="markAsUnavailable">
                                <i class="bi bi-person-dash me-1"></i>
                                Mark Unavailable
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-outline-success" wire:click="markAsAvailable">
                                <i class="bi bi-person-check me-1"></i>
                                Mark Available
                            </button>
                        @endif
                        @if($driver->status !== 'active')
                            <button type="button" class="btn btn-sm btn-outline-success" wire:click="updateStatus('active')">
                                <i class="bi bi-check-circle me-1"></i>
                                Activate
                            </button>
                        @endif
                        @if($driver->status !== 'suspended')
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="updateStatus('suspended')">
                                <i class="bi bi-ban me-1"></i>
                                Suspend
                            </button>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="resetForm"
                                wire:confirm="Are you sure you want to reset the form? All changes will be lost.">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Personal Information Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-person-vcard text-primary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Personal Information</h5>
                            <small class="text-muted">Driver's personal details</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Profile Photo -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if($driver->profile_photo)
                                <img src="{{ asset('storage/' . $driver->profile_photo) }}" 
                                     alt="{{ $driver->full_name }}"
                                     class="profile-photo-preview rounded-circle border border-4 border-primary shadow-sm"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger btn-icon rounded-circle position-absolute top-0 end-0"
                                        wire:click="removeProfilePhoto"
                                        onclick="return confirm('Remove profile photo?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @else
                                <div class="profile-photo-placeholder rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                     style="width: 120px; height: 120px; background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                                    <span class="text-white fw-bold fs-2">
                                        {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <input type="file" class="form-control form-control-sm w-auto mx-auto" 
                                   wire:model="profile_photo" 
                                   accept="image/*"
                                   style="max-width: 200px;">
                            <small class="text-muted">JPG, PNG, max 2MB</small>
                        </div>
                    </div>

                    <!-- Name Fields -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="first_name" class="form-label fw-medium">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" wire:model="first_name" placeholder="John">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="second_name" class="form-label fw-medium">Middle Name</label>
                            <input type="text" class="form-control @error('second_name') is-invalid @enderror" 
                                   id="second_name" wire:model="second_name" placeholder="James">
                            @error('second_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label fw-medium">Last Name *</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" wire:model="last_name" placeholder="Doe">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">Email Address *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" wire:model="email" placeholder="john.doe@example.com">
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone_number" class="form-label fw-medium">Phone Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" wire:model="phone_number" placeholder="0712345678">
                            </div>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Date of Birth & Gender -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="gender" class="form-label fw-medium">Gender *</label>
                            <select class="form-select @error('gender') is-invalid @enderror" 
                                    id="gender" wire:model="gender">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- ID Number & Nationality -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="id_number" class="form-label fw-medium">ID/Passport Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                       id="id_number" wire:model="id_number" placeholder="12345678">
                            </div>
                            @error('id_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="nationality" class="form-label fw-medium">Nationality *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                <input type="text" class="form-control @error('nationality') is-invalid @enderror" 
                                       id="nationality" wire:model="nationality" placeholder="Kenyan">
                            </div>
                            @error('nationality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- License Information Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-card-checklist text-warning fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">License Information</h5>
                            <small class="text-muted">Driver's license details</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- License Number & Class -->
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="driving_license_number" class="form-label fw-medium">License Number *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                <input type="text" class="form-control @error('driving_license_number') is-invalid @enderror" 
                                       id="driving_license_number" wire:model="driving_license_number" 
                                       placeholder="DL1234567" style="text-transform: uppercase">
                            </div>
                            @error('driving_license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="license_class" class="form-label fw-medium">Class *</label>
                            <select class="form-select @error('license_class') is-invalid @enderror" 
                                    id="license_class" wire:model="license_class">
                                <option value="">Select Class</option>
                                @foreach($licenseClasses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('license_class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- License Dates -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label for="driving_license_issue_date" class="form-label fw-medium">Issue Date *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-plus"></i></span>
                                <input type="date" class="form-control @error('driving_license_issue_date') is-invalid @enderror" 
                                       id="driving_license_issue_date" wire:model="driving_license_issue_date">
                            </div>
                            @error('driving_license_issue_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="driving_license_expiry_date" class="form-label fw-medium">Expiry Date *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar-check"></i></span>
                                <input type="date" class="form-control @error('driving_license_expiry_date') is-invalid @enderror" 
                                       id="driving_license_expiry_date" wire:model="driving_license_expiry_date">
                            </div>
                            @error('driving_license_expiry_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-1">
                                <i class="bi bi-{{ $licenseColor === 'success' ? 'check' : ($licenseColor === 'warning' ? 'clock' : 'exclamation-triangle') }}-circle me-1"></i>
                                {{ $this->getLicenseValidityText() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <!-- Address Information Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-geo-alt text-info fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Address Information</h5>
                            <small class="text-muted">Driver's residential address</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label fw-medium">Address *</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-house-door"></i></span>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" wire:model="address" rows="3" 
                                      placeholder="123 Main Street, Apartment 4B"></textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City & Postal Code -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label fw-medium">City/Town *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" wire:model="city" placeholder="Nairobi">
                            </div>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="postal_code" class="form-label fw-medium">Postal Code</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-mailbox"></i></span>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                       id="postal_code" wire:model="postal_code" placeholder="00100">
                            </div>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-danger bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-telephone-plus text-danger fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Emergency Contact</h5>
                            <small class="text-muted">Emergency contact details</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="emergency_contact_name" class="form-label fw-medium">Contact Name *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" wire:model="emergency_contact_name" placeholder="Jane Smith">
                            </div>
                            @error('emergency_contact_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="emergency_contact_phone" class="form-label fw-medium">Contact Phone *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" 
                                       id="emergency_contact_phone" wire:model="emergency_contact_phone" placeholder="0712345678">
                            </div>
                            @error('emergency_contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="emergency_contact_relationship" class="form-label fw-medium">Relationship *</label>
                        <select class="form-select @error('emergency_contact_relationship') is-invalid @enderror" 
                                id="emergency_contact_relationship" wire:model="emergency_contact_relationship">
                            <option value="">Select Relationship</option>
                            @foreach($relationships as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('emergency_contact_relationship')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Notes Card -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-secondary bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-gear text-secondary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-semibold">Status & Notes</h5>
                            <small class="text-muted">Driver status and additional notes</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Status & Availability -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-medium">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" wire:model="status">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Availability</label>
                            <div class="d-flex align-items-center h-100">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_available" wire:model="is_available" 
                                           {{ $is_available ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium" for="is_available">
                                        {{ $is_available ? 'Available' : 'Unavailable' }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-text">Mark as available for assignments</div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mt-3">
                        <label for="notes" class="form-label fw-medium">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" wire:model="notes" rows="4" 
                                  placeholder="Any additional information about the driver..."></textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-clock-history me-1"></i>
                            Last updated: {{ $driver->updated_at->format('M d, Y h:i A') }}
                        </small>
                    </div>
                    <div class="d-flex gap-3">
                        <a href="{{ route('partners.drivers.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4" 
                                wire:click="submit" 
                                wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="update">
                                <i class="bi bi-check-circle me-2"></i>
                                Update Driver
                            </span>
                            <span wire:loading wire:target="update">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if (session()->has('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong class="me-auto">Success</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .dashboard-section {
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .section-subtitle {
        color: #64748b;
        margin: 5px 0 0 0;
        font-size: 0.95rem;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .driver-avatar .profile-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 3px solid #4f46e5;
    }

    .driver-avatar .avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.5rem;
        border: 3px solid #e9ecef;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-success {
        background-color: rgba(34, 197, 94, 0.1);
        color: #166534;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .status-secondary {
        background-color: rgba(100, 116, 139, 0.1);
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.2);
    }

    .status-danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .status-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    /* Card Styles */
    .card {
        border: none;
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
    }

    .icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Form Styles */
    .form-label {
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .input-group-text {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    /* Profile Photo */
    .profile-photo-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 4px solid #4f46e5;
    }

    .profile-photo-placeholder {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 4px solid #e9ecef;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Form Switch */
    .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
    }

    /* Toast */
    .toast-container {
        z-index: 1060;
    }

    .toast {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
    }

    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            align-items: stretch;
        }

        .header-actions {
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .quick-actions .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        .profile-photo-preview,
        .profile-photo-placeholder {
            width: 100px;
            height: 100px;
        }
    }
</style>

<script>
    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                const bsToast = new bootstrap.Toast(toast);
                bsToast.hide();
            }, 5000);
        });

        // Focus on first input field
        document.getElementById('first_name')?.focus();

        // License number auto-uppercase
        document.getElementById('driving_license_number')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
        });
    });
</script>
</div>