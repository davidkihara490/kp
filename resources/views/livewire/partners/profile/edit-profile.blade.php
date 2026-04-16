<div>
    <div class="partner-profile">
        <!-- Header -->
        <div class="header-card mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-building fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-1">Edit Partner Profile</h2>
                        <p class="text-muted mb-0">Update your company information and settings</p>
                    </div>
                </div>
                <div class="partner-badge">
                    <span class="badge bg-{{ $partner_type === 'transport' ? 'info' : 'success' }} px-3 py-2 fs-6">
                        <i class="fas {{ $partner_type === 'transport' ? 'fa-truck' : 'fa-store' }} me-2"></i>
                        {{ $partner_type === 'transport' ? 'Transport Partner' : 'Pickup-Dropoff Partner' }}
                    </span>
                </div>
            </div>
        </div>

        <form wire:submit.prevent="updateProfile">
            @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            <!-- Basic Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Partner Type</label>
                            <div class="partner-type-selector">
                                <div class="btn-group w-100" role="group">
                                    <button class="btn btn-outline-info">
                                        @if($partner_type == 'pickup-dropoff')
                                        Pickup-Dropoff Partner
                                        @elseif($partner_type == 'transport')
                                        Transport Partner
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                wire:model="company_name"
                                placeholder="Enter your company name">
                            @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium">Registration Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror"
                                wire:model="registration_number"
                                placeholder="e.g., CPR/2021/12345">
                            @error('registration_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium">KRA PIN <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kra_pin') is-invalid @enderror"
                                wire:model="kra_pin"
                                placeholder="e.g., A123456789X">
                            @error('kra_pin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- Service Areas Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-map-marker-alt text-warning me-2"></i>
                        Service Areas
                    </h5>
                </div>
                <div class="card-body">
                    <label class="form-label fw-medium">Select Towns You Operate In</label>
                    <div class="row">
                        @foreach($availableTowns as $town)
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    value="{{ $town->id }}"
                                    id="town_{{ $town->id }}"
                                    wire:model="service_towns">
                                <label class="form-check-label" for="town_{{ $town->id }}">
                                    {{ $town->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('service_towns') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Document Uploads Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-file-contract text-danger me-2"></i>
                        Documents & Certificates
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Registration Certificate -->
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Registration Certificate</label>
                            <div class="document-upload">
                                <input type="file" class="form-control @error('registration_certificate') is-invalid @enderror"
                                    wire:model="registration_certificate"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                @error('registration_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if($current_registration_certificate)
                                <small class="text-success mt-1 d-block">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Current file: {{ basename($current_registration_certificate) }}
                                </small>
                                @endif
                            </div>
                        </div>

                        <!-- KRA PIN Certificate -->
                        <div class="col-md-6">
                            <label class="form-label fw-medium">KRA PIN Certificate</label>
                            <div class="document-upload">
                                <input type="file" class="form-control @error('pin_certificate') is-invalid @enderror"
                                    wire:model="pin_certificate"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                @error('pin_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if($current_pin_certificate)
                                <small class="text-success mt-1 d-block">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Current file: {{ basename($current_pin_certificate) }}
                                </small>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="sticky-footer bg-white border-top py-4 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-outline-primary"
                            wire:click="$refresh">
                            <i class="fas fa-redo me-2"></i>Reset Form
                        </button>
                        <button type="submit"
                            class="btn btn-primary btn-gradient"
                            wire:loading.attr="disabled"
                            wire:target="updateProfile">
                            <span wire:loading.remove wire:target="updateProfile">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </span>
                            <span wire:loading wire:target="updateProfile">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    .partner-profile {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .header-card .text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 10px 24px;
        font-weight: 500;
    }

    .btn-gradient:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4199 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        transition: all 0.3s ease;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
    }

    .partner-type-selector .btn-group .btn {
        padding: 12px 20px;
        font-weight: 500;
    }

    .partner-type-selector .btn-check:checked+.btn {
        background-color: rgba(102, 126, 234, 0.1);
        border-color: #667eea;
        color: #667eea;
    }

    .partner-type-selector .btn-check:checked+.btn-outline-info {
        background-color: rgba(23, 162, 184, 0.1) !important;
        border-color: #17a2b8 !important;
        color: #17a2b8 !important;
    }

    .partner-type-selector .btn-check:checked+.btn-outline-success {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-color: #28a745 !important;
        color: #28a745 !important;
    }

    .email-input-group .email-item {
        background: linear-gradient(to right, #f8f9fa, #fff);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .email-input-group .email-item:hover {
        background: linear-gradient(to right, #e9ecef, #f8f9fa);
    }

    .document-upload {
        position: relative;
    }

    .document-upload input[type="file"] {
        padding: 8px;
    }

    .form-check.form-switch .form-check-input {
        width: 3em;
        height: 1.5em;
        cursor: pointer;
    }

    .form-check.form-switch .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .form-check.form-switch .form-check-input:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 100;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .badge {
        font-size: 0.85em;
        font-weight: 500;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });

        // Auto-format KRA PIN to uppercase
        const kraPinInput = document.querySelector('[wire\\:model="kra_pin"]');
        if (kraPinInput) {
            kraPinInput.addEventListener('input', function(e) {
                this.value = this.value.toUpperCase();
            });
        }

        // Add tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush