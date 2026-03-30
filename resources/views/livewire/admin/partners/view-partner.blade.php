<div>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <x-alerts.response-alerts />
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('admin.partners.index') }}"
                                    class="btn btn-sm btn-outline-secondary mr-3">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-{{ $partnerTypeIcon }} text-{{ $partnerTypeColor }} mr-2"></i>
                                    {{ $partner->company_name ?? $partner->first_name . ' ' . $partner->last_name }}
                                </h3>
                            </div>
                            <div class="d-flex gap-2">
                                @if($partner->verification_status === 'pending')
                                <button class="btn btn-sm btn-success mr-2" wire:click="verifyPartner">
                                    <i class="fas fa-check mr-1"></i> Verify
                                </button>
                                @endif

                                <a href="{{ route('admin.partners.edit', ['id' => $partner->id]) }}"
                                    class="btn btn-sm btn-warning mr-2">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                @if($partner->verification_status === 'verified')
                                <button class="btn btn-sm btn-danger mr-2" wire:click="suspendPartner">
                                    <i class="fas fa-pause mr-1"></i> Suspend
                                </button>
                                @endif

                                <button class="btn btn-sm btn-danger mr-2" wire:click="confirmDelete">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex flex-wrap gap-3 mb-3">
                                    <span class="badge badge-{{ $partnerTypeColor }} p-2 mr-2">
                                        <i class="fas fa-{{ $partnerTypeIcon }} mr-1"></i>
                                        {{ ucfirst($partner->partner_type) }} Partner
                                    </span>

                                    <span class="badge badge-{{ $statusBadgeColor }} p-2 mr-2">
                                        <i class="fas fa-circle mr-1"></i>
                                        {{ ucfirst($partner->verification_status) }}
                                    </span>

                                    <span class="badge badge-{{ $verificationBadgeColor }} p-2 mr-2">
                                        <i class="fas fa-shield-alt mr-1"></i>
                                        {{ ucfirst($partner->verification_status) }}
                                    </span>

                                    @if($partner->registration_number)
                                    <span class="badge badge-info p-2 mr-2">
                                        <i class="fas fa-id-card mr-1"></i>
                                        Reg: {{ $partner->registration_number }}
                                    </span>
                                    @endif

                                    <span class="badge badge-secondary p-2 mr-2">
                                        <i class="fas fa-calendar-alt mr-1"></i>
                                        Joined: {{ $partner->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-envelope text-muted mr-2"></i>
                                            <strong>Email:</strong> {{ $partner->owner->email }}
                                        </p>
                                        <p class="mb-1">
                                            <i class="fas fa-phone text-muted mr-2"></i>
                                            <strong>Phone:</strong> {{ $partner->owner->phone_number }}
                                        </p>
                                        @if($partner->kra_pin)
                                        <p class="mb-1">
                                            <i class="fas fa-file-invoice-dollar text-muted mr-2"></i>
                                            <strong>KRA PIN:</strong> {{ $partner->kra_pin }}
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-chart-bar mr-2"></i>Quick Stats
                                        </h6>
                                        <div class="row text-center">
                                            <div class="col-6 mb-3">
                                                @if($partner->partner_type == 'transport')
                                                <div class="display-4 text-primary">{{ $partner->fleet_count ?? 0 }}</div>
                                                <small class="text-muted">Fleet Size</small>
                                                @elseif($partner->partner_type == 'pickup-dropoff')
                                                <div class="display-4 text-primary">{{ $partner->pickUpAndDropOffPoints->count() ?? 0 }}</div>
                                                <small class="text-muted">PickUp/DropOff Points</small>
                                                @endif
                                            </div>
                                            <div class="col-6 mb-3">
                                                @if($partner->partner_type == 'transport')
                                                <div class="display-4 text-success">{{ $partner->drivers->count() ?? 0 }}</div>
                                                <small class="text-muted">Drivers</small>
                                                @elseif($partner->partner_type == 'pickup-dropoff')
                                                <div class="display-4 text-success">{{ $partner->parcelHandlingAssistants->count() ?? 0 }}</div>
                                                <small class="text-muted">Assistants</small>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                                <div class="display-4 text-info">{{ $partner->towns->count() }}</div>
                                                <small class="text-muted">Service Towns</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header p-0">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach($tabs as $key => $label)
                            <li class="nav-item">
                                <a class="nav-link {{ $activeTab === $key ? 'active' : '' }}"
                                    href="#"
                                    wire:click.="changeTab('{{ $key }}')">
                                    @php
                                    $tabIcons = [
                                    'overview' => 'home',
                                    'details' => 'building',
                                    'fleet' => 'truck',
                                    'capacity' => 'boxes',
                                    'documents' => 'file-alt',
                                    'owners' => 'users',
                                    'drivers' => 'user-tie',
                                    'towns' => 'map-marker-alt'
                                    ];
                                    @endphp
                                    <i class="fas fa-{{ $tabIcons[$key] ?? 'info-circle' }} mr-2"></i>
                                    {{ $label }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="row">
            <div class="col-12">
                @if($activeTab === 'overview')
                <!-- Overview Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" wire:click="changeTab('overview')">
                            <i class="fas fa-home mr-2"></i>Overview
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>Basic Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Partner Type:</strong></td>
                                                    <td>
                                                        <span class="badge badge-{{ $partnerTypeColor }}">
                                                            {{ ucfirst($partner->partner_type) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        <span class="badge badge-{{ $statusBadgeColor }}">
                                                            {{ ucfirst($partner->verification_status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Verification:</strong></td>
                                                    <td>
                                                        <span class="badge badge-{{ $verificationBadgeColor }}">
                                                            {{ ucfirst($partner->verification_status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @if($partner->registration_number)
                                                <tr>
                                                    <td><strong>Registration No:</strong></td>
                                                    <td>{{ $partner->registration_number }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->kra_pin)
                                                <tr>
                                                    <td><strong>KRA PIN:</strong></td>
                                                    <td>{{ $partner->kra_pin }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>Created:</strong></td>
                                                    <td>{{ $partner->created_at->format('F d, Y') }}</td>
                                                </tr>
                                                @if($partner->approved_at)
                                                <tr>
                                                    <td><strong>Approved:</strong></td>
                                                    <td>{{ $partner->approved_at->format('F d, Y') }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-address-book mr-2"></i>Contact Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Email:</strong></td>
                                                    <td>{{ $partner->owner->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td>{{ $partner->owner->phone_number }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cogs mr-2"></i>Operations Summary
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center mb-3">
                                            <div class="col-4">
                                                @if($partner->partner_type == 'transport')
                                                <div class="display-4 text-primary">{{ $partner->fleet_count ?? 0 }}</div>
                                                <small class="text-muted">Fleet Size</small>
                                                @elseif($partner->partner_type == 'pickup-dropoff')
                                                <div class="display-4 text-primary">{{ $partner->pickUpAndDropOffPoints->count() ?? 0 }}</div>
                                                <small class="text-muted">PickUp/DropOff Points</small>
                                                @endif
                                            </div>
                                            <div class="col-4">
                                                @if($partner->partner_type == 'transport')
                                                <div class="display-4 text-success">{{ $partner->drivers->count() ?? 0 }}</div>
                                                <small class="text-muted">Drivers</small>
                                                @elseif($partner->partner_type == 'pickup-dropoff')
                                                <div class="display-4 text-success">{{ $partner->parcelHandlingAssistants->count() ?? 0 }}</div>
                                                <small class="text-muted">Assistants</small>
                                                @endif
                                            </div>
                                            <div class="col-4">
                                                <div class="display-4 text-info">{{ $partner->towns->count() }}</div>
                                                <small class="text-muted">Service Towns</small>
                                            </div>
                                        </div>
                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->maximum_daily_capacity)
                                                <tr>
                                                    <td width="60%"><strong>Max Daily Capacity:</strong></td>
                                                    <td>{{ number_format($partner->maximum_daily_capacity) }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->years_in_operation)
                                                <tr>
                                                    <td><strong>Years in Operation:</strong></td>
                                                    <td>{{ $partner->years_in_operation }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->previous_courier_experience)
                                                <tr>
                                                    <td><strong>Courier Experience:</strong></td>
                                                    <td>{{ $partner->previous_courier_experience }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-clipboard-check mr-2"></i>Compliance Status
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            @if($partner->points_compliant)
                                            <span class="badge badge-success p-2 mr-2">
                                                <i class="fas fa-check-circle mr-1"></i>Points Compliant
                                            </span>
                                            @endif

                                            @if($partner->fleets_compliant)
                                            <span class="badge badge-success p-2 mr-2">
                                                <i class="fas fa-check-circle mr-1"></i>Fleet Compliant
                                            </span>
                                            @endif

                                            @if($partner->drivers_compliant)
                                            <span class="badge badge-success p-2 mr-2">
                                                <i class="fas fa-check-circle mr-1"></i>Drivers Compliant
                                            </span>
                                            @endif
                                        </div>

                                        @if($partner->additional_notes)
                                        <div class="alert alert-info mb-0">
                                            <strong><i class="fas fa-sticky-note mr-2"></i>Notes:</strong>
                                            <p class="mb-0 mt-2">{{ $partner->additional_notes }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'details')
                <!-- Company Details Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building mr-2"></i>Company Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-contract mr-2"></i>Registration Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tbody>
                                                <tr>
                                                    <td width="40%"><strong>Company Name:</strong></td>
                                                    <td>{{ $partner->company_name ?? 'N/A' }}</td>
                                                </tr>
                                                @if($partner->registration_number)
                                                <tr>
                                                    <td><strong>Registration Number:</strong></td>
                                                    <td>{{ $partner->registration_number }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->kra_pin)
                                                <tr>
                                                    <td><strong>KRA PIN:</strong></td>
                                                    <td>{{ $partner->kra_pin }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-store mr-2"></i>Point Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <strong>Points Count:</strong> {{ $partner->points_count ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->points_have_phone ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Has Phone</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->points_have_computer ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Has Computer</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->points_have_internet ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Has Internet</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->officers_knowledgeable ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Knowledgeable Officers</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-industry mr-2"></i>Infrastructure
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->storage_facility_type)
                                                <tr>
                                                    <td width="40%"><strong>Storage Facility:</strong></td>
                                                    <td>{{ $partner->storage_facility_type }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->security_measures)
                                                <tr>
                                                    <td><strong>Security Measures:</strong></td>
                                                    <td>{{ $partner->security_measures }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->insurance_coverage)
                                                <tr>
                                                    <td><strong>Insurance Coverage:</strong></td>
                                                    <td>{{ $partner->insurance_coverage }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->insurance_coverage_amount)
                                                <tr>
                                                    <td><strong>Coverage Amount:</strong></td>
                                                    <td>KSh {{ number_format($partner->insurance_coverage_amount) }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->operating_hours)
                                                <tr>
                                                    <td><strong>Operating Hours:</strong></td>
                                                    <td>{{ $partner->operating_hours }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-history mr-2"></i>Experience
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->years_in_operation)
                                                <tr>
                                                    <td width="40%"><strong>Years in Operation:</strong></td>
                                                    <td>{{ $partner->years_in_operation }} years</td>
                                                </tr>
                                                @endif
                                                @if($partner->previous_courier_experience)
                                                <tr>
                                                    <td><strong>Previous Courier Experience:</strong></td>
                                                    <td>{{ $partner->previous_courier_experience }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'fleet')
                <!-- Fleet & Operations Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-truck mr-2"></i>Fleet & Operations
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-truck-loading mr-2"></i>Fleet Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <div class="display-4 text-primary text-center">{{ $partner->fleet_count ?? 0 }}</div>
                                                <div class="text-center text-muted">Total Fleet</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="display-4 text-success text-center">{{ $partner->driver_count ?? 0 }}</div>
                                                <div class="text-center text-muted">Drivers</div>
                                            </div>
                                        </div>

                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->fleet_ownership)
                                                <tr>
                                                    <td width="40%"><strong>Fleet Ownership:</strong></td>
                                                    <td>{{ ucfirst($partner->fleet_ownership) }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td><strong>Fleet Insured:</strong></td>
                                                    <td>
                                                        @if($partner->fleet_insured)
                                                        <span class="badge badge-success">Yes</span>
                                                        @else
                                                        <span class="badge badge-danger">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Fleet Compliant:</strong></td>
                                                    <td>
                                                        @if($partner->fleets_compliant)
                                                        <span class="badge badge-success">Yes</span>
                                                        @else
                                                        <span class="badge badge-warning">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Drivers Compliant:</strong></td>
                                                    <td>
                                                        @if($partner->drivers_compliant)
                                                        <span class="badge badge-success">Yes</span>
                                                        @else
                                                        <span class="badge badge-warning">No</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-truck-pickup mr-2"></i>Fleet Types
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_motorcycles ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Motorcycles</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_vans ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Vans</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_trucks ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Trucks</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_refrigerated ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Refrigerated</label>
                                                </div>
                                            </div>
                                        </div>
                                        @if($partner->other_fleet_types)
                                        <div class="mt-3">
                                            <strong>Other Fleet Types:</strong>
                                            <p class="mb-0">{{ $partner->other_fleet_types }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-desktop mr-2"></i>Operations Infrastructure
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_computer ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Has Computer</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_internet ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Has Internet</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->has_dedicated_allocator ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Dedicated Allocator</label>
                                                </div>
                                            </div>
                                        </div>

                                        @if($partner->has_dedicated_allocator)
                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->allocator_name)
                                                <tr>
                                                    <td width="40%"><strong>Allocator Name:</strong></td>
                                                    <td>{{ $partner->allocator_name }}</td>
                                                </tr>
                                                @endif
                                                @if($partner->allocator_phone)
                                                <tr>
                                                    <td><strong>Allocator Phone:</strong></td>
                                                    <td>{{ $partner->allocator_phone }}</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        @endif

                                        @if($partner->booking_emails && count($partner->booking_emails) > 0)
                                        <div class="mt-3">
                                            <strong>Booking Emails:</strong>
                                            <ul class="mb-0">
                                                @foreach($partner->booking_emails as $email)
                                                <li>{{ $email }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-shield-alt mr-2"></i>Safety & Systems
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($partner->safety_measures)
                                        <div class="mb-3">
                                            <strong>Safety Measures:</strong>
                                            <p class="mb-0">{{ $partner->safety_measures }}</p>
                                        </div>
                                        @endif

                                        @if($partner->tracking_system)
                                        <div class="mb-3">
                                            <strong>Tracking System:</strong>
                                            <p class="mb-0">{{ $partner->tracking_system }}</p>
                                        </div>
                                        @endif

                                        @if($partner->maximum_distance)
                                        <div class="mb-3">
                                            <strong>Maximum Distance:</strong>
                                            <p class="mb-0">{{ number_format($partner->maximum_distance) }} km</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'capacity')
                <!-- Capacity & Coverage Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-boxes mr-2"></i>Capacity & Coverage
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-weight-hanging mr-2"></i>Capacity Details
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <div class="display-3 text-primary text-center">{{ $partner->maximum_daily_capacity ?? 0 }}</div>
                                                <div class="text-center text-muted">Maximum Daily Capacity</div>
                                            </div>
                                        </div>

                                        <table class="table table-sm">
                                            <tbody>
                                                @if($partner->maximum_capacity_per_day)
                                                <tr>
                                                    <td width="40%"><strong>Capacity Per Day:</strong></td>
                                                    <td>{{ number_format($partner->maximum_capacity_per_day) }} units</td>
                                                </tr>
                                                @endif
                                                @if($partner->maximum_distance)
                                                <tr>
                                                    <td><strong>Maximum Distance:</strong></td>
                                                    <td>{{ number_format($partner->maximum_distance) }} km</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-map-marker-alt mr-2"></i>Service Coverage
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($partner->towns && $partner->towns->count() > 0)
                                        <div class="mb-3">
                                            <strong>Service Towns ({{ $partner->towns->count() }}):</strong>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @foreach($partner->towns as $partnerTown)
                                                <span class="badge badge-info">
                                                    {{ $partnerTown->town->name ?? 'Unknown' }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                        <div class="alert alert-warning mb-0">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            No service towns assigned yet.
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-hand-holding-box mr-2"></i>Handling Capabilities
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->can_handle_fragile ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Fragile Items</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->can_handle_perishable ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Perishable Items</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" {{ $partner->can_handle_valuables ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label">Valuable Items</label>
                                                </div>
                                            </div>
                                        </div>

                                        @if($partner->storage_facility_type)
                                        <div class="mt-3">
                                            <strong>Storage Facility Type:</strong>
                                            <p class="mb-0">{{ $partner->storage_facility_type }}</p>
                                        </div>
                                        @endif

                                        @if($partner->security_measures)
                                        <div class="mt-3">
                                            <strong>Security Measures:</strong>
                                            <p class="mb-0">{{ $partner->security_measures }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-alt mr-2"></i>Additional Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if($partner->additional_notes)
                                        <div class="mb-3">
                                            <strong>Notes:</strong>
                                            <p class="mb-0">{{ $partner->additional_notes }}</p>
                                        </div>
                                        @endif

                                        @if($partner->insurance_coverage)
                                        <div class="mb-3">
                                            <strong>Insurance Coverage:</strong>
                                            <p class="mb-0">{{ $partner->insurance_coverage }}</p>
                                        </div>
                                        @endif

                                        @if($partner->insurance_coverage_amount)
                                        <div class="mb-3">
                                            <strong>Insurance Amount:</strong>
                                            <p class="mb-0">KSh {{ number_format($partner->insurance_coverage_amount) }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'documents')
                <!-- Documents Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt mr-2"></i>Documents
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-file-contract mr-2"></i>Registration Documents
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Registration Certificate:</strong>
                                            @if($partner->registration_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $partner->registration_certificate_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $partner->registration_certificate_path) }}"
                                                    download
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                            @else
                                            <p class="text-danger mt-2">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Not uploaded
                                            </p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <strong>KRA PIN Certificate:</strong>
                                            @if($partner->pin_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $partner->pin_certificate_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $partner->pin_certificate_path) }}"
                                                    download
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                            @else
                                            <p class="text-danger mt-2">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Not uploaded
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-clipboard-check mr-2"></i>Compliance Documents
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>Compliance Certificate:</strong>
                                            @if($partner->compliance_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $partner->compliance_certificate_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $partner->compliance_certificate_path) }}"
                                                    download
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                            @else
                                            <p class="text-danger mt-2">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Not uploaded
                                            </p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <strong>Insurance Certificate:</strong>
                                            @if($partner->insurance_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $partner->insurance_certificate_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $partner->insurance_certificate_path) }}"
                                                    download
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                            @else
                                            <p class="text-danger mt-2">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Not uploaded
                                            </p>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <strong>Drivers Certificate:</strong>
                                            @if($partner->drivers_certificate_path)
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $partner->drivers_certificate_path) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye mr-1"></i> View Document
                                                </a>
                                                <a href="{{ asset('storage/' . $partner->drivers_certificate_path) }}"
                                                    download
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-download mr-1"></i> Download
                                                </a>
                                            </div>
                                            @else
                                            <p class="text-danger mt-2">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                Not uploaded
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($activeTab === 'points')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt mr-2"></i>Pick Up/ Drop Off Points ({{ $partner->pickUpAndDropOffPoints->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($partner->pickUpAndDropOffPoints->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Town</th>
                                        <th>Contact Phone</th>
                                        <th>Contact Email</th>
                                        <th>Status</th>

                                        <th>Parcels</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($partner->pickUpAndDropOffPoints as $point)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $point->name }}</td>
                                        <td>{{ $point->town->name ?? 'Unknown' }}</td>
                                        <td>{{ $point->contact_phone_number ?? 'Not provided' }}</td>
                                        <td>{{ $point->contact_email ?? 'Not provided' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $point->status === 'active' ? 'success' : ($point->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($point->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $point->parcels?->count() ?? 0}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            This partner does not have pick-up/drop-off points.
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($activeTab === 'pha')
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt mr-2"></i>Parcel Handling Assistants ({{ $partner->parcelHandlingAssistants->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($partner->parcelHandlingAssistants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Contact Phone</th>
                                        <th>Contact Email</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($partner->parcelHandlingAssistants as $assistant)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $assistant->full_name }}</td>
                                        <td>{{ $assistant->phone_number ?? 'Not provided' }}</td>
                                        <td>{{ $assistant->email ?? 'Not provided' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $assistant->status === 'active' ? 'success' : ($assistant->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($assistant->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            This partner does not have parcel handling assistants.
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                @if($activeTab === 'drivers')
                <!-- Drivers Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-tie mr-2"></i>Drivers ({{ $partner->drivers->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($partner->drivers && $partner->drivers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>ID Number</th>
                                        <th>License Number</th>
                                        <th>License Class</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Verified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($partner->drivers as $driver)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $driver->first_name }} {{ $driver->last_name }}</td>
                                        <td>{{ $driver->id_number }}</td>
                                        <td>{{ $driver->license_number }}</td>
                                        <td>
                                            @if($driver->license_class)
                                            <span class="badge badge-info">{{ $driver->license_class }}</span>
                                            @else
                                            <span class="badge badge-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $driver->phone_number }}</td>
                                        <td>
                                            <span class="badge badge-{{ $driver->status === 'active' ? 'success' : ($driver->status === 'suspended' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($driver->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $driver->verification_status === 'verified' ? 'success' : ($driver->verification_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($driver->verification_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.drivers.view', $driver->id) }}"
                                                class="btn btn-sm btn-info" title="View Driver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            No drivers found for this partner.
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($activeTab === 'towns')
                <!-- Service Towns Tab -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-map-marker-alt mr-2"></i>Service Towns ({{ $partner->towns->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($partner->towns && $partner->towns->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Town Name</th>
                                        <th>County</th>
                                        <th>Sub County</th>
                                        <th>Status</th>
                                        <th>Added On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($partner->towns as $partnerTown)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $partnerTown->town->name ?? 'Unknown' }}</td>
                                        <td>{{ $partnerTown->town->subCounty->county->name ?? 'Unknown' }}</td>
                                        <td>{{ $partnerTown->town->subCounty->name ?? 'Unknown' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $partnerTown->status === 'active' ? 'success' : ($partnerTown->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($partnerTown->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $partnerTown->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            No service towns assigned to this partner.
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
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
                    <p>Are you sure you want to delete this partner?</p>
                    <ul class="text-danger">
                        <li>This action cannot be undone</li>
                        <li>Associated user account will also be deleted</li>
                        <li>All related data will be removed</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showDeleteModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deletePartner">
                        <i class="fas fa-trash mr-1"></i> Delete Partner
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .nav-tabs .nav-link {
        border: 1px solid transparent;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        color: #495057;
        padding: 0.5rem 1rem;
    }

    .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .card-header.bg-light {
        background-color: #f8f9fa !important;
    }

    .table-sm td,
    .table-sm th {
        padding: 0.5rem;
    }

    .display-3 {
        font-size: 4.5rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .display-4 {
        font-size: 3.5rem;
        font-weight: 300;
        line-height: 1.2;
    }
</style>
@endpush