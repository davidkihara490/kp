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
                                    <input type="radio" class="btn-check" name="partner_type"
                                        id="transport_type" value="transport"
                                        wire:model="partner_type">
                                    <label class="btn btn-outline-info" for="transport_type">
                                        <i class="fas fa-truck me-2"></i>Transport Partner
                                    </label>

                                    <input type="radio" class="btn-check" name="partner_type"
                                        id="pickup_type" value="pickup_dropoff"
                                        wire:model="partner_type">
                                    <label class="btn btn-outline-success" for="pickup_type">
                                        <i class="fas fa-store me-2"></i>Pickup-Dropoff Partner
                                    </label>
                                </div>
                            </div>
                            @error('partner_type') <span class="text-danger small">{{ $message }}</span> @enderror
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

                        <div class="col-md-4">
                            <label class="form-label fw-medium">Verification Status</label>
                            <select class="form-select @error('verification_status') is-invalid @enderror"
                                wire:model="verification_status">
                                <option value="pending">Pending</option>
                                <option value="verified">Verified</option>
                                <option value="rejected">Rejected</option>
                                <option value="suspended">Suspended</option>
                            </select>
                            @error('verification_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

            <!-- Conditional Sections based on Partner Type -->
            @if($partner_type === 'transport')
            <!-- Transport Partner Specific Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-truck text-info me-2"></i>
                        Transport Partner Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Fleet Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Fleet Information</h6>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Total Fleet Count <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('fleet_count') is-invalid @enderror"
                                wire:model="fleet_count" min="0">
                            @error('fleet_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Fleet Ownership <span class="text-danger">*</span></label>
                            <select class="form-select @error('fleet_ownership') is-invalid @enderror"
                                wire:model="fleet_ownership">
                                <option value="">Select Ownership</option>
                                <option value="owned">Owned</option>
                                <option value="subcontracted">Subcontracted</option>
                                <option value="both">Both</option>
                            </select>
                            @error('fleet_ownership') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Driver Count <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('driver_count') is-invalid @enderror"
                                wire:model="driver_count" min="0">
                            @error('driver_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Fleet Types -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Fleet Types Available</h6>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_motorcycles" wire:model="has_motorcycles">
                                <label class="form-check-label" for="has_motorcycles">
                                    <i class="fas fa-motorcycle me-2"></i>Motorcycles
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_vans" wire:model="has_vans">
                                <label class="form-check-label" for="has_vans">
                                    <i class="fas fa-shuttle-van me-2"></i>Vans
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_trucks" wire:model="has_trucks">
                                <label class="form-check-label" for="has_trucks">
                                    <i class="fas fa-truck me-2"></i>Trucks
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_refrigerated" wire:model="has_refrigerated">
                                <label class="form-check-label" for="has_refrigerated">
                                    <i class="fas fa-snowflake me-2"></i>Refrigerated
                                </label>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label fw-medium">Other Fleet Types</label>
                            <input type="text" class="form-control"
                                wire:model="other_fleet_types"
                                placeholder="List other fleet types separated by commas">
                        </div>
                    </div>

                    <!-- Compliance & Insurance -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Compliance & Insurance</h6>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="fleet_insured" wire:model="fleet_insured">
                                <label class="form-check-label fw-medium" for="fleet_insured">
                                    Fleet Insured
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="fleets_compliant" wire:model="fleets_compliant">
                                <label class="form-check-label fw-medium" for="fleets_compliant">
                                    Fleets Compliant
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="drivers_compliant" wire:model="drivers_compliant">
                                <label class="form-check-label fw-medium" for="drivers_compliant">
                                    Drivers Compliant
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Capacity & Capabilities -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Capacity & Capabilities</h6>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium">Maximum Daily Capacity</label>
                            <div class="input-group">
                                <input type="number" class="form-control"
                                    wire:model="maximum_daily_capacity" min="0"
                                    placeholder="Maximum shipments per day">
                                <span class="input-group-text">shipments</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium">Maximum Distance</label>
                            <div class="input-group">
                                <input type="number" class="form-control"
                                    wire:model="maximum_distance" min="0"
                                    placeholder="Maximum distance per trip">
                                <span class="input-group-text">km</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-medium">Special Capabilities</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="can_handle_fragile" wire:model="can_handle_fragile">
                                        <label class="form-check-label small" for="can_handle_fragile">
                                            Fragile
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="can_handle_perishable" wire:model="can_handle_perishable">
                                        <label class="form-check-label small" for="can_handle_perishable">
                                            Perishable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            id="can_handle_valuables" wire:model="can_handle_valuables">
                                        <label class="form-check-label small" for="can_handle_valuables">
                                            Valuables
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($partner_type === 'pickup_dropoff')
            <!-- Pickup-Dropoff Partner Specific Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-store text-success me-2"></i>
                        Pickup-Dropoff Partner Details
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Points Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Pickup Points Information</h6>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Total Points <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('points_count') is-invalid @enderror"
                                wire:model="points_count" min="1">
                            @error('points_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Operating Hours</label>
                            <input type="text" class="form-control"
                                wire:model="operating_hours"
                                placeholder="e.g., 8:00 AM - 6:00 PM">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Storage Facility Type</label>
                            <select class="form-select" wire:model="storage_facility_type">
                                <option value="">Select Facility Type</option>
                                <option value="warehouse">Warehouse</option>
                                <option value="shop">Shop</option>
                                <option value="office">Office</option>
                                <option value="storage_unit">Storage Unit</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-medium">Maximum Daily Capacity</label>
                            <div class="input-group">
                                <input type="number" class="form-control"
                                    wire:model="maximum_capacity_per_day" min="0"
                                    placeholder="Maximum packages per day">
                                <span class="input-group-text">packages</span>
                            </div>
                        </div>
                    </div>

                    <!-- Points Facilities -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Points Facilities</h6>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="points_have_phone" wire:model="points_have_phone">
                                <label class="form-check-label" for="points_have_phone">
                                    <i class="fas fa-phone me-2"></i>Have Phone
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="points_have_computer" wire:model="points_have_computer">
                                <label class="form-check-label" for="points_have_computer">
                                    <i class="fas fa-computer me-2"></i>Have Computer
                                </label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="points_have_internet" wire:model="points_have_internet">
                                <label class="form-check-label" for="points_have_internet">
                                    <i class="fas fa-wifi me-2"></i>Have Internet
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="officers_knowledgeable" wire:model="officers_knowledgeable">
                                <label class="form-check-label" for="officers_knowledgeable">
                                    <i class="fas fa-user-graduate me-2"></i>Knowledgeable Officers
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="points_compliant" wire:model="points_compliant">
                                <label class="form-check-label" for="points_compliant">
                                    <i class="fas fa-shield-alt me-2"></i>Compliant Points
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Security & Insurance -->
                    <div class="row g-3">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Security & Insurance</h6>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Security Measures</label>
                            <textarea class="form-control" rows="3"
                                wire:model="security_measures"
                                placeholder="Describe your security measures..."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">Insurance Coverage</label>
                            <textarea class="form-control" rows="3"
                                wire:model="insurance_coverage"
                                placeholder="Describe your insurance coverage..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Operational Information Card (Common) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-cogs text-secondary me-2"></i>
                        Operational Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h6 class="fw-bold mb-3">Technology & Equipment</h6>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_computer" wire:model="has_computer">
                                <label class="form-check-label" for="has_computer">
                                    <i class="fas fa-computer me-2"></i>Has Computer
                                </label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_internet" wire:model="has_internet">
                                <label class="form-check-label" for="has_internet">
                                    <i class="fas fa-wifi me-2"></i>Has Internet
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    id="has_dedicated_allocator" wire:model="has_dedicated_allocator">
                                <label class="form-check-label" for="has_dedicated_allocator">
                                    <i class="fas fa-user-tie me-2"></i>Has Dedicated Allocator
                                </label>
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
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Fields marked with <span class="text-danger">*</span> are required
                    </small>
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