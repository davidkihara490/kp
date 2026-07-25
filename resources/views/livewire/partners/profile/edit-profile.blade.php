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
            <div id="profile-response-area" class="mb-3">
                {{-- Validation summary --}}
                @if ($errors->any())
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>

                        <div class="flex-grow-1">
                            <strong>Profile update failed.</strong>

                            <p class="mb-2">
                                Please correct the following information:
                            </p>

                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                </div>
                @elseif (session()->has('validation_error'))
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('validation_error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif

                {{-- Successful response --}}
                @if (session()->has('success'))
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => show = false, 6000)"
                    x-transition>
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Success:</strong>
                    {{ session('success') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                        @click="show = false"></button>
                </div>
                @endif

                {{-- General update failure --}}
                @if (session()->has('error'))
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    <strong>Unable to complete request:</strong>
                    {{ session('error') }}

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
                @endif
            </div>

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
                        <div class="col-md-12">
                            <label class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('company_name') is-invalid @enderror"
                                wire:model.blur="company_name"
                                wire:loading.attr="disabled"
                                wire:target="updateProfile"
                                placeholder="Enter your company name">

                            @error('company_name')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Registration Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('registration_number') is-invalid @enderror"
                                wire:model.blur="registration_number"
                                wire:loading.attr="disabled"
                                wire:target="updateProfile"
                                placeholder="e.g., CPR/2021/12345">

                            @error('registration_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">KRA PIN <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control text-uppercase @error('kra_pin') is-invalid @enderror"
                                wire:model.blur="kra_pin"
                                wire:loading.attr="disabled"
                                wire:target="updateProfile"
                                placeholder="e.g., A123456789X">

                            @error('kra_pin')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Phone Number <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                wire:model.blur="phone_number"
                                wire:loading.attr="disabled"
                                wire:target="updateProfile"
                                placeholder="e.g., CPR/2021/12345">

                            @error('phone_number')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control text-uppercase @error('email') is-invalid @enderror"
                                wire:model.blur="email"
                                wire:loading.attr="disabled"
                                wire:target="updateProfile"
                                placeholder="e.g., A123456789X">

                            @error('email')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-circle me-1"></i>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                    </div>
                </div>
            </div>

            <!-- Service Areas Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-map-marker-alt text-warning me-2"></i>
                                Service Areas
                            </h5>
                            <p class="text-muted small mt-2 mb-0">Select the towns where you operate</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text"
                                    class="form-control"
                                    id="townSearch"
                                    placeholder="Search towns..."
                                    wire:model.live.debounce.300ms="searchTerm">
                                @if($searchTerm)
                                <button class="btn btn-outline-secondary" type="button" wire:click="$set('searchTerm', '')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="selectAllTowns">
                                <i class="fas fa-check-double me-1"></i> Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="deselectAllTowns">
                                <i class="fas fa-times me-1"></i> Deselect All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Selection Summary -->
                    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ count($service_towns) }}</strong> town(s) selected
                                @if($searchTerm)
                                <span class="ms-2">
                                    <i class="fas fa-filter me-1"></i>
                                    Showing results for: "<strong>{{ $searchTerm }}</strong>"
                                </span>
                                @endif
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>

                    <!-- All towns displayed in a grid -->
                    @if(count($availableTowns) > 0)
                    <div class="row">
                        @foreach($availableTowns as $town)
                        <div class="col-md-4 col-lg-3 mb-3 town-item">
                            <div class="form-check">
                                <input class="form-check-input town-checkbox" type="checkbox"
                                    value="{{ $town->id }}"
                                    id="town_{{ $town->id }}"
                                    wire:model="service_towns">
                                <label class="form-check-label" for="town_{{ $town->id }}">
                                    <strong>{{ $town->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-pin me-1"></i>
                                        {{ $town->subCounty->name ?? 'N/A' }},
                                        {{ $town->subCounty->county->name ?? 'N/A' }}
                                    </small>
                                    @if(in_array($town->id, $service_towns))
                                    <i class="fas fa-check-circle text-success ms-1"></i>
                                    @endif
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No towns found matching "<strong>{{ $searchTerm }}</strong>"</p>
                        <button type="button" class="btn btn-sm btn-primary" wire:click="$set('searchTerm', '')">
                            Clear Search
                        </button>
                    </div>
                    @endif

                    @error('service_towns')
                    <div class="alert alert-danger py-2 mt-3 mb-0">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror

                    @error('service_towns.*')
                    <div class="alert alert-danger py-2 mt-3 mb-0">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Document Uploads Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-file-contract text-danger me-2"></i>
                        Documents & Certificates
                    </h5>
                    <p class="text-muted small mt-2 mb-0">Upload PDF, JPG, or PNG files (Max 5MB each)</p>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Registration Certificate -->
                        <div class="col-md-6">
                            <div class="document-card">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-building me-1"></i>
                                    Registration Certificate
                                    <span class="text-muted small">(Optional)</span>
                                </label>
                                <div class="document-upload-wrapper">
                                    <input type="file" class="form-control @error('registration_certificate') is-invalid @enderror"
                                        wire:model="registration_certificate"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('registration_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    @if($current_registration_certificate)
                                    <div class="current-document mt-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <small>Current: {{ basename($current_registration_certificate) }}</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                wire:click="removeDocument('registration')"
                                                wire:confirm="Are you sure you want to remove this document?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @if($registration_certificate)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            New file selected: {{ $registration_certificate->getClientOriginalName() }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- KRA PIN Certificate -->
                        <div class="col-md-6">
                            <div class="document-card">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-file-invoice me-1"></i>
                                    KRA PIN Certificate
                                    <span class="text-muted small">(Optional)</span>
                                </label>
                                <div class="document-upload-wrapper">
                                    <input type="file" class="form-control @error('pin_certificate') is-invalid @enderror"
                                        wire:model="pin_certificate"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('pin_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    @if($current_pin_certificate)
                                    <div class="current-document mt-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <small>Current: {{ basename($current_pin_certificate) }}</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                wire:click="removeDocument('pin')"
                                                wire:confirm="Are you sure you want to remove this document?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @if($pin_certificate)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            New file selected: {{ $pin_certificate->getClientOriginalName() }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Compliance Certificate -->
                        <div class="col-md-6">
                            <div class="document-card">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Compliance Certificate
                                    <span class="text-muted small">(Optional)</span>
                                </label>
                                <div class="document-upload-wrapper">
                                    <input type="file" class="form-control @error('compliance_certificate') is-invalid @enderror"
                                        wire:model="compliance_certificate"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('compliance_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    @if($current_compliance_certificate)
                                    <div class="current-document mt-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <small>Current: {{ basename($current_compliance_certificate) }}</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                wire:click="removeDocument('compliance')"
                                                wire:confirm="Are you sure you want to remove this document?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @if($compliance_certificate)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            New file selected: {{ $compliance_certificate->getClientOriginalName() }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Insurance Certificate -->
                        <div class="col-md-6">
                            <div class="document-card">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-umbrella me-1"></i>
                                    Insurance Certificate
                                    <span class="text-muted small">(Optional)</span>
                                </label>
                                <div class="document-upload-wrapper">
                                    <input type="file" class="form-control @error('insurance_certificate') is-invalid @enderror"
                                        wire:model="insurance_certificate"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('insurance_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    @if($current_insurance_certificate)
                                    <div class="current-document mt-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <small>Current: {{ basename($current_insurance_certificate) }}</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                wire:click="removeDocument('insurance')"
                                                wire:confirm="Are you sure you want to remove this document?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @if($insurance_certificate)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            New file selected: {{ $insurance_certificate->getClientOriginalName() }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Drivers Certificate -->
                        <div class="col-md-6">
                            <div class="document-card">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-id-card me-1"></i>
                                    Drivers Certificate
                                    <span class="text-muted small">(Optional)</span>
                                </label>
                                <div class="document-upload-wrapper">
                                    <input type="file" class="form-control @error('drivers_certificate') is-invalid @enderror"
                                        wire:model="drivers_certificate"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    @error('drivers_certificate') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                    @if($current_drivers_certificate)
                                    <div class="current-document mt-2 p-2 bg-light rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                <small>Current: {{ basename($current_drivers_certificate) }}</small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                wire:click="removeDocument('drivers')"
                                                wire:confirm="Are you sure you want to remove this document?">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endif

                                    @if($drivers_certificate)
                                    <div class="mt-2">
                                        <small class="text-info">
                                            <i class="fas fa-spinner fa-spin me-1"></i>
                                            New file selected: {{ $drivers_certificate->getClientOriginalName() }}
                                        </small>
                                    </div>
                                    @endif
                                </div>
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
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            wire:click="resetProfileForm"
                            wire:loading.attr="disabled"
                            wire:target="resetProfileForm">
                            <span wire:loading.remove wire:target="resetProfileForm">
                                <i class="fas fa-redo me-2"></i>
                                Reset
                            </span>

                            <span wire:loading wire:target="resetProfileForm">
                                <span
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"></span>
                                Resetting...
                            </span>
                        </button>
                        <button
                            type="submit"
                            class="btn btn-primary btn-gradient"
                            wire:loading.attr="disabled"
                            wire:target="
        updateProfile,
        registration_certificate,
        pin_certificate,
        compliance_certificate,
        insurance_certificate,
        drivers_certificate
    ">
                            <span wire:loading.remove wire:target="updateProfile">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </span>

                            <span wire:loading wire:target="updateProfile">
                                <span
                                    class="spinner-border spinner-border-sm me-2"
                                    role="status"
                                    aria-hidden="true"></span>
                                Validating and updating...
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
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 10px 24px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-gradient:hover:not(:disabled) {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4199 100%);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }

    .btn-gradient:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .town-item {
        animation: fadeInUp 0.3s ease-out;
        animation-fill-mode: both;
    }

    .town-item:nth-child(n) {
        animation-delay: calc(0.02s * var(--item-index, 0));
    }

    .form-check {
        padding: 10px 12px;
        transition: all 0.2s ease;
        border-radius: 8px;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .form-check:hover {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        transform: translateX(3px);
    }

    .form-check-input {
        margin-top: 0.3rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }

    .document-card {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .document-card:hover {
        background: #f1f3f5;
        transform: translateY(-2px);
    }

    .current-document {
        background: #e9ecef !important;
        border-left: 3px solid #28a745;
        border-radius: 4px;
    }

    .form-control:focus,
    .form-select:focus,
    .input-group:focus-within {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }

    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 100;
        background: white;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        border-radius: 8px 8px 0 0;
    }

    .alert {
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading states */
    [wire\:loading] {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .header-card {
            padding: 20px;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
        }

        .icon-wrapper i {
            font-size: 1.5rem !important;
        }

        .sticky-footer {
            padding: 15px !important;
        }

        .form-check-label {
            font-size: 0.8rem;
        }

        .btn-sm {
            font-size: 0.7rem;
        }

        .input-group {
            width: 100% !important;
            margin-bottom: 10px;
        }

        .form-check {
            padding: 8px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        const scrollToResponse = () => {
            const responseArea = document.getElementById(
                'profile-response-area'
            );

            if (responseArea) {
                responseArea.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }
        };

        const scrollToFirstInvalidField = () => {
            setTimeout(() => {
                const invalidField = document.querySelector(
                    '.is-invalid'
                );

                if (invalidField) {
                    invalidField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                    });

                    invalidField.focus();
                    return;
                }

                scrollToResponse();
            }, 150);
        };

        Livewire.on('profile-updated', () => {
            scrollToResponse();
        });

        Livewire.on('profile-validation-failed', () => {
            scrollToFirstInvalidField();
        });

        Livewire.on('profile-update-failed', () => {
            scrollToResponse();
        });
    });

    document.addEventListener('input', (event) => {
        if (!event.target.matches('[wire\\:model\\.blur="kra_pin"]')) {
            return;
        }

        const cursorPosition = event.target.selectionStart;

        event.target.value = event.target.value.toUpperCase();

        event.target.setSelectionRange(
            cursorPosition,
            cursorPosition
        );
    });

    document.addEventListener('livewire:navigated', () => {
        document.querySelectorAll('.town-item').forEach(
            (item, index) => {
                item.style.setProperty('--item-index', index);
            }
        );
    });
</script>
@endpush