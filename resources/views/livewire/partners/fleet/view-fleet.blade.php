<div>
    <div>
        <div class="fleet-view">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('partners.fleet.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Fleet
                </a>
            </div>

            <!-- Main Card -->
            <div class="card shadow-lg border-0">
                <!-- Header with Actions -->
                <div class="card-header bg-white border-0 py-4 px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="vehicle-icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 me-4">
                                <i class="fas fa-truck fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h2 class="card-title fw-bold mb-1">{{ $fleet->make }} {{ $fleet->model }}</h2>
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="badge bg-primary px-3 py-2 fs-6">
                                        <i class="fas fa-id-card me-2"></i>{{ $fleet->registration_number }}
                                    </span>
                                    <span class="badge badge-status-{{ $fleet->status }} px-3 py-2">
                                        <i class="fas {{ $fleet->status_icon ?? 'fa-circle' }} me-1"></i>
                                        {{ ucfirst($fleet->status) }}
                                    </span>
                                    @if($fleet->is_available)
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Available
                                    </span>
                                    @else
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i>Unavailable
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('partners.fleet.edit', $fleet->id) }}"
                                class="btn btn-primary btn-gradient">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            <button type="button" class="btn btn-outline-danger"
                                wire:click="confirmDelete">
                                <i class="fas fa-trash me-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body px-5 pt-4">
                    @include('components.alerts.response-alerts')

                    <!-- Quick Actions -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="fw-medium me-2">Quick Actions:</span>
                                            <div class="btn-group" role="group">
                                                <button type="button"
                                                    class="btn btn-outline-success {{ $fleet->status === 'active' ? 'active' : '' }}"
                                                    wire:click="updateStatus('active')">
                                                    <i class="fas fa-play me-1"></i> Set Active
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-warning {{ $fleet->status === 'maintenance' ? 'active' : '' }}"
                                                    wire:click="updateStatus('maintenance')">
                                                    <i class="fas fa-tools me-1"></i> Set Maintenance
                                                </button>
                                                <button type="button"
                                                    class="btn btn-outline-danger {{ $fleet->status === 'accident' ? 'active' : '' }}"
                                                    wire:click="updateStatus('accident')">
                                                    <i class="fas fa-car-crash me-1"></i> Report Accident
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                id="availabilitySwitch"
                                                {{ $fleet->is_available ? 'checked' : '' }}
                                                wire:change="toggleAvailability">
                                            <label class="form-check-label fw-medium" for="availabilitySwitch">
                                                Toggle Availability
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column - Vehicle Details -->
                        <div class="col-lg-8">
                            <!-- Vehicle Information -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-car me-2"></i>Vehicle Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Basic Info -->
                                        <div class="col-md-6">
                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Registration Number</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-primary bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-id-card text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">{{ $fleet->registration_number }}</h5>
                                                        <small class="text-muted">License Plate</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Make & Model</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-info bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-industry text-info"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">{{ $fleet->make }}</h5>
                                                        <p class="mb-0">{{ $fleet->model }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Vehicle Type</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-warning bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-truck-loading text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-light text-dark fs-6">
                                                            {{ ucfirst($fleet->fleet_type ?? $fleet->vehicle_type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Specifications -->
                                        <div class="col-md-6">
                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Year & Color</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-success bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-calendar-alt text-success"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">{{ $fleet->year ?? 'N/A' }}</h5>
                                                        <p class="mb-0">
                                                            <i class="fas fa-palette me-1"></i>
                                                            {{ $fleet->color ?? 'Not specified' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Fuel Type</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-danger bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-gas-pump text-danger"></i>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-light text-dark fs-6">
                                                            {{ ucfirst($fleet->fuel_type) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="info-group mb-4">
                                                <label class="text-muted small mb-1">Load Capacity</label>
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-circle bg-purple bg-opacity-10 p-2 me-3">
                                                        <i class="fas fa-weight-hanging text-purple"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-0">
                                                            {{ number_format($fleet->capacity ?? 0) }} kg
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Odometer Reading -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <label class="text-muted small mb-1">Current Odometer</label>
                                                            <h3 class="fw-bold text-primary mb-0">
                                                                {{ number_format($fleet->odometer_reading) }} KM
                                                            </h3>
                                                        </div>
                                                        <div class="text-end">
                                                            <small class="text-muted">Last Updated</small>
                                                            <div>{{ $fleet->updated_at }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Status -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-file-contract me-2"></i>Documents & Compliance
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Registration -->
                                        <div class="col-md-6 mb-3">
                                            <div class="document-card {{ $fleet->is_registration_valid ? 'border-success' : 'border-danger' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="document-icon {{ $fleet->is_registration_valid ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="fas fa-id-card"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-0 ms-3">Vehicle Registration</h6>
                                                        </div>
                                                        <p class="text-muted small mb-2">Certificate of Registration</p>
                                                        @if($fleet->registration_expiry)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                            <span class="{{ $fleet->is_registration_valid ? 'text-success' : 'text-danger' }}">
                                                                Expires: {{ $fleet->registration_expiry }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <span class="badge {{ $fleet->is_registration_valid ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $fleet->is_registration_valid ? 'VALID' : 'EXPIRED' }}
                                                    </span>
                                                </div>
                                                @if(!$fleet->is_registration_valid)
                                                <div class="mt-3">
                                                    <div class="alert alert-danger py-2 mb-0">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Registration expired {{ $fleet->registration_expiry->diffForHumans() }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Insurance -->
                                        <div class="col-md-6 mb-3">
                                            <div class="document-card {{ $fleet->is_insurance_valid ? 'border-success' : 'border-danger' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="document-icon {{ $fleet->is_insurance_valid ? 'bg-success' : 'bg-danger' }}">
                                                                <i class="fas fa-shield-alt"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-0 ms-3">Insurance</h6>
                                                        </div>
                                                        <p class="text-muted small mb-2">Comprehensive Insurance</p>
                                                        @if($fleet->insurance_expiry)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                            <span class="{{ $fleet->is_insurance_valid ? 'text-success' : 'text-danger' }}">
                                                                Expires: {{ $fleet->insurance_expiry }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <span class="badge {{ $fleet->is_insurance_valid ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $fleet->is_insurance_valid ? 'VALID' : 'EXPIRED' }}
                                                    </span>
                                                </div>
                                                @if(!$fleet->is_insurance_valid)
                                                <div class="mt-3">
                                                    <div class="alert alert-danger py-2 mb-0">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Insurance expired {{ $fleet->insurance_expiry->diffForHumans() }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Service Status -->
                                        <div class="col-md-6">
                                            <div class="document-card {{ !$fleet->needs_service ? 'border-success' : 'border-warning' }}">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <div class="d-flex align-items-center mb-2">
                                                            <div class="document-icon {{ !$fleet->needs_service ? 'bg-success' : 'bg-warning' }}">
                                                                <i class="fas fa-tools"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-0 ms-3">Service Status</h6>
                                                        </div>
                                                        @if($fleet->last_service_date)
                                                        <p class="text-muted small mb-2">
                                                            Last serviced: {{ $fleet->last_service_date }}
                                                        </p>
                                                        @endif
                                                        @if($fleet->next_service_date)
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-calendar-check text-muted me-2"></i>
                                                            <span class="{{ !$fleet->needs_service ? 'text-success' : 'text-warning' }}">
                                                                Next due: {{ $fleet->next_service_date }}
                                                            </span>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <span class="badge {{ !$fleet->needs_service ? 'bg-success' : 'bg-warning' }}">
                                                        {{ !$fleet->needs_service ? 'UP TO DATE' : 'NEEDS SERVICE' }}
                                                    </span>
                                                </div>
                                                @if($fleet->needs_service)
                                                <div class="mt-3">
                                                    <div class="alert alert-warning py-2 mb-0">
                                                        <i class="fas fa-tools me-2"></i>
                                                        Service required. Next service was due {{ $fleet->next_service_date->diffForHumans() }}
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Statistics & Driver Info -->
                        <div class="col-lg-4">
                            <!-- Driver Information -->
                            @if($driverInfo)
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-user me-2"></i>Current Driver
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="driver-profile text-center">
                                        <div class="driver-avatar mb-3">
                                            <div class="avatar-circle bg-primary bg-opacity-10">
                                                <i class="fas fa-user fa-2x text-primary"></i>
                                            </div>
                                        </div>
                                        <h5 class="fw-bold mb-1">{{ $driverInfo->first_name }} {{ $driverInfo->last_name }}</h5>
                                        <p class="text-muted mb-3">
                                            <i class="fas fa-id-badge me-1"></i>
                                            ID: {{ $driverInfo->driver_id ?? 'N/A' }}
                                        </p>

                                        <div class="driver-details">
                                            <div class="detail-item d-flex justify-content-between mb-2">
                                                <span class="text-muted">Contact:</span>
                                                <span class="fw-medium">{{ $driverInfo->phone ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item d-flex justify-content-between mb-2">
                                                <span class="text-muted">Email:</span>
                                                <span class="fw-medium">{{ $driverInfo->email ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item d-flex justify-content-between mb-2">
                                                <span class="text-muted">License:</span>
                                                <span class="badge bg-light text-dark">{{ $driverInfo->license_number ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item d-flex justify-content-between">
                                                <span class="text-muted">Status:</span>
                                                <span class="badge {{ $driverInfo->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($driverInfo->status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <a href="{{ route('partners.drivers.view', $driverInfo->id) }}"
                                                class="btn btn-outline-primary btn-sm w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>View Driver Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-user-slash me-2"></i>No Driver Assigned
                                    </h5>
                                </div>
                                <div class="card-body text-center py-4">
                                    <div class="empty-state mb-3">
                                        <i class="fas fa-user-times fa-3x text-muted opacity-25"></i>
                                    </div>
                                    <p class="text-muted mb-3">This vehicle doesn't have an assigned driver.</p>
                                    <a href="{{ route('partners.fleet.edit', $fleet->id) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>Assign Driver
                                    </a>
                                </div>
                            </div>
                            @endif

                            <!-- Vehicle Statistics -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-chart-line me-2"></i>Vehicle Statistics
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="stats-grid">
                                        <!-- Total Trips -->
                                        <div class="stat-item text-center p-3 border-bottom">
                                            <div class="stat-icon mb-2">
                                                <i class="fas fa-route fa-2x text-primary opacity-75"></i>
                                            </div>
                                            <h3 class="fw-bold mb-1">{{ $totalTrips }}</h3>
                                            <p class="text-muted mb-0">Total Trips</p>
                                        </div>

                                        <!-- Distance Traveled -->
                                        <div class="stat-item text-center p-3 border-bottom">
                                            <div class="stat-icon mb-2">
                                                <i class="fas fa-road fa-2x text-success opacity-75"></i>
                                            </div>
                                            <h3 class="fw-bold mb-1">{{ number_format($totalDistance) }}</h3>
                                            <p class="text-muted mb-0">Kilometers</p>
                                        </div>

                                        <!-- Average Load -->
                                        <div class="stat-item text-center p-3 border-bottom">
                                            <div class="stat-icon mb-2">
                                                <i class="fas fa-weight-hanging fa-2x text-warning opacity-75"></i>
                                            </div>
                                            <h3 class="fw-bold mb-1">{{ number_format($averageLoad) }}</h3>
                                            <p class="text-muted mb-0">Avg Load (kg)</p>
                                        </div>

                                        <!-- Fuel Consumption -->
                                        <div class="stat-item text-center p-3">
                                            <div class="stat-icon mb-2">
                                                <i class="fas fa-gas-pump fa-2x text-danger opacity-75"></i>
                                            </div>
                                            <h3 class="fw-bold mb-1">{{ $fuelConsumption }}</h3>
                                            <p class="text-muted mb-0">Liters Used</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Information -->
                            <div class="card border">
                                <div class="card-header bg-light py-3">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="fas fa-info-circle me-2"></i>Quick Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-list">
                                        <div class="info-item d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Vehicle Added:</span>
                                            <span class="fw-medium">{{ $fleet->created_at }}</span>
                                        </div>
                                        <div class="info-item d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Last Updated:</span>
                                            <span class="fw-medium">{{ $fleet->updated_at }}</span>
                                        </div>
                                        <div class="info-item d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">Vehicle Age:</span>
                                            <span class="fw-medium">
                                                {{ $fleet->year ? now()->year - $fleet->year . ' years' : 'N/A' }}
                                            </span>
                                        </div>
                                        <div class="info-item d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Service Interval:</span>
                                            <span class="fw-medium">3 months</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for History -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-3">
                                    <ul class="nav nav-tabs card-header-tabs" id="historyTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="maintenance-tab" data-bs-toggle="tab"
                                                data-bs-target="#maintenance" type="button" role="tab">
                                                <i class="fas fa-tools me-2"></i>Maintenance History
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="trips-tab" data-bs-toggle="tab"
                                                data-bs-target="#trips" type="button" role="tab">
                                                <i class="fas fa-route me-2"></i>Recent Trips
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="notes-tab" data-bs-toggle="tab"
                                                data-bs-target="#notes" type="button" role="tab">
                                                <i class="fas fa-sticky-note me-2"></i>Notes
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="historyTabsContent">
                                        <!-- Maintenance Tab -->
                                        <div class="tab-pane fade show active" id="maintenance" role="tabpanel">
                                            @if($maintenanceHistory->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Service Type</th>
                                                            <th>Description</th>
                                                            <th>Cost</th>
                                                            <th>Next Due</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($maintenanceHistory as $maintenance)
                                                        <tr>
                                                            <td>{{ $maintenance->maintenance_date }}</td>
                                                            <td>
                                                                <span class="badge bg-info">
                                                                    {{ $maintenance->service_type }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $maintenance->description ?? 'No description' }}</td>
                                                            <td>
                                                                @if($maintenance->cost)
                                                                <span class="fw-bold">${{ number_format($maintenance->cost, 2) }}</span>
                                                                @else
                                                                <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($maintenance->next_service_date)
                                                                {{ $maintenance->next_service_date }}
                                                                @else
                                                                <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-tools fa-3x text-muted mb-3 opacity-25"></i>
                                                <p class="text-muted">No maintenance history found</p>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Trips Tab -->
                                        <div class="tab-pane fade" id="trips" role="tabpanel">
                                            @if($recentTrips->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Trip ID</th>
                                                            <th>Date</th>
                                                            <th>Route</th>
                                                            <th>Distance</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($recentTrips as $trip)
                                                        <tr>
                                                            <td>TRP-{{ str_pad($trip->id, 6, '0', STR_PAD_LEFT) }}</td>
                                                            <td>{{ $trip->created_at }}</td>
                                                            <td>
                                                                {{ $trip->origin ?? 'N/A' }} → {{ $trip->destination ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ number_format($trip->distance_km ?? 0) }} km</td>
                                                            <td>
                                                                <span class="badge bg-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                                                    {{ ucfirst($trip->status) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-route fa-3x text-muted mb-3 opacity-25"></i>
                                                <p class="text-muted">No trip history found</p>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Notes Tab -->
                                        <div class="tab-pane fade" id="notes" role="tabpanel">
                                            @if($fleet->notes)
                                            <div class="notes-content">
                                                <div class="bg-light p-4 rounded">
                                                    <p class="mb-0">{{ $fleet->notes }}</p>
                                                </div>
                                            </div>
                                            @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-sticky-note fa-3x text-muted mb-3 opacity-25"></i>
                                                <p class="text-muted">No notes added for this vehicle</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        @if($showDeleteModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <div class="modal-icon bg-danger bg-gradient rounded-circle p-3">
                            <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                        </div>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body text-center pt-0">
                        <h5 class="fw-bold mb-3">Delete Vehicle</h5>
                        <p class="text-muted">
                            Are you sure you want to delete
                            <strong class="text-danger">{{ $fleet->registration_number }}</strong>
                            - {{ $fleet->make }} {{ $fleet->model }}?
                        </p>
                        <p class="text-danger small">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            This action cannot be undone. All trip history, maintenance records,
                            and other associated data will be permanently removed.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteFleet">
                            <i class="fas fa-trash me-2"></i>Delete Vehicle
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('styles')
    <style>
        .fleet-view {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
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

        .vehicle-icon-wrapper {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .badge-status-maintenance {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .badge-status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .badge-status-accident {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .document-card {
            padding: 20px;
            border-radius: 8px;
            border: 2px solid;
            background: white;
        }

        .document-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .driver-avatar .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0;
        }

        .stat-item {
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transform: translateY(-2px);
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link.active {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
            border-bottom: 3px solid #667eea;
        }

        .nav-tabs .nav-link:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-input:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }

        .modal-icon {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .btn-group .btn.active {
            background-color: rgba(102, 126, 234, 0.1);
            border-color: #667eea;
            color: #667eea;
            font-weight: 500;
        }

        .empty-state {
            opacity: 0.5;
        }

        .info-group {
            padding: 12px;
            border-radius: 8px;
            background: linear-gradient(to right, #f8f9fa, #fff);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tabs
            var tabEl = document.querySelector('button[data-bs-toggle="tab"]')
            if (tabEl) {
                var tab = new bootstrap.Tab(tabEl)
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Add animation to stats
            const statItems = document.querySelectorAll('.stat-item');
            statItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
    </script>
    @endpush
</div>