<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-truck mr-2"></i>Fleet Details: {{ $fleet->full_name }}
            </h3>
            <div class="card-tools">
                @php
                    $statusBadge = $this->getStatusBadge();
                @endphp
                <span class="badge badge-{{ $statusBadge['color'] }}">
                    <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                    {{ ucfirst($fleet->status) }}
                </span>
                @if($fleet->is_available && $fleet->status === 'active')
                    <span class="badge badge-success ml-2">
                        <i class="fas fa-check-circle mr-1"></i>Available
                    </span>
                @endif
            </div>
        </div>
        
        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-route"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Trips</span>
                            <span class="info-box-number">{{ $stats['total_trips'] }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Drivers</span>
                            <span class="info-box-number">{{ $stats['total_drivers'] }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-tachometer-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Odometer</span>
                            <span class="info-box-number">{{ number_format($fleet->odometer_reading) }} km</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Utilization</span>
                            <span class="info-box-number">{{ $stats['utilization_rate'] }}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $stats['utilization_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Fleet Information -->
                <div class="col-md-8">
                    <!-- Basic Information Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>Basic Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Registration Number:</strong></p>
                                    <p class="text-muted">{{ $fleet->registration_number }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Make & Model:</strong></p>
                                    <p class="text-muted">{{ $fleet->make }} {{ $fleet->model }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Vehicle Type:</strong></p>
                                    <p class="text-muted">{{ ucfirst($fleet->vehicle_type) }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Year:</strong></p>
                                    <p class="text-muted">{{ $fleet->year ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Color:</strong></p>
                                    <p class="text-muted">{{ $fleet->color ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Fuel Type:</strong></p>
                                    <p class="text-muted">{{ ucfirst($fleet->fuel_type) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Load Capacity:</strong></p>
                                    <p class="text-muted">
                                        @if($fleet->capacity)
                                            {{ number_format($fleet->capacity) }} kg
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ownership & Assignment Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tag mr-2"></i>Ownership & Assignment
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Transport Partner:</strong></p>
                                    <p class="text-muted">
                                        @if($fleet->transportPartner)
                                            <span class="badge badge-info">{{ $fleet->transportPartner->name }}</span>
                                        @else
                                            <span class="badge badge-secondary">Not Assigned</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Current Driver:</strong></p>
                                    @if($fleet->currentDriver)
                                        <p class="text-muted">
                                            <i class="fas fa-user mr-2"></i>
                                            {{ $fleet->currentDriver->full_name }} ({{ $fleet->currentDriver->driver_id }})
                                        </p>
                                    @else
                                        <p class="text-muted">
                                            <span class="badge badge-warning">
                                                <i class="fas fa-user-slash mr-1"></i>
                                                No Driver Assigned
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Document Status Card -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-contract mr-2"></i>Document Status
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="display-4 text-{{ $this->getDateColor($fleet->registration_expiry) }}">
                                            <i class="fas fa-id-card"></i>
                                        </div>
                                        <h5>Registration</h5>
                                        <p class="text-muted">{{ $this->formatDate($fleet->registration_expiry) }}</p>
                                        <span class="badge badge-{{ $this->getDateColor($fleet->registration_expiry) }}">
                                            {{ $this->isDateValid($fleet->registration_expiry) ? 'Valid' : 'Expired' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="display-4 text-{{ $this->getDateColor($fleet->insurance_expiry) }}">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <h5>Insurance</h5>
                                        <p class="text-muted">{{ $this->formatDate($fleet->insurance_expiry) }}</p>
                                        <span class="badge badge-{{ $this->getDateColor($fleet->insurance_expiry) }}">
                                            {{ $this->isDateValid($fleet->insurance_expiry) ? 'Valid' : 'Expired' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="display-4 text-{{ $this->getDateColor($fleet->last_service_date) }}">
                                            <i class="fas fa-wrench"></i>
                                        </div>
                                        <h5>Last Service</h5>
                                        <p class="text-muted">{{ $this->formatDate($fleet->last_service_date) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <div class="display-4 text-{{ $this->getDateColor($fleet->next_service_date) }}">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <h5>Next Service</h5>
                                        <p class="text-muted">{{ $this->formatDate($fleet->next_service_date) }}</p>
                                        @if($fleet->needs_service)
                                            <span class="badge badge-warning">Needs Service</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Actions & Additional Info -->
                <div class="col-md-4">
                    <!-- Quick Actions Card -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bolt mr-2"></i>Quick Actions
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.fleets.edit', $fleet->id) }}" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i>Edit Fleet
                                </a>
                                
                                <button type="button" class="btn btn-{{ $fleet->is_available ? 'danger' : 'success' }} btn-block"
                                        wire:click="toggleAvailability"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="toggleAvailability">
                                        <i class="fas fa-power-off mr-2"></i>
                                        {{ $fleet->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}
                                    </span>
                                    <span wire:loading wire:target="toggleAvailability">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                                
                                <button type="button" class="btn btn-{{ $fleet->status === 'active' ? 'secondary' : 'success' }} btn-block"
                                        wire:click="toggleStatus"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="toggleStatus">
                                        <i class="fas fa-exchange-alt mr-2"></i>
                                        {{ $fleet->status === 'active' ? 'Send to Maintenance' : 'Mark as Active' }}
                                    </span>
                                    <span wire:loading wire:target="toggleStatus">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                                
                                <button type="button" class="btn btn-danger btn-block"
                                        wire:click="confirmDelete"
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-trash mr-2"></i>Delete Fleet
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information Card -->
                    <div class="card" style="border-color: #{{ $statusBadge['color'] == 'success' ? '28a745' : ($statusBadge['color'] == 'warning' ? 'ffc107' : 'dc3545') }}">
                        <div class="card-header" style="background-color: #{{ $statusBadge['color'] == 'success' ? '28a745' : ($statusBadge['color'] == 'warning' ? 'ffc107' : 'dc3545') }}20">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>Status Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="display-4 text-{{ $statusBadge['color'] }}">
                                    <i class="fas {{ $statusBadge['icon'] }}"></i>
                                </div>
                                <h4 class="mt-2 text-{{ $statusBadge['color'] }}">
                                    {{ ucfirst($fleet->status) }}
                                </h4>
                                <p class="text-muted">
                                    @if($fleet->status === 'active')
                                        Vehicle is active and available for operations.
                                    @elseif($fleet->status === 'maintenance')
                                        Vehicle is undergoing maintenance.
                                    @elseif($fleet->status === 'inactive')
                                        Vehicle is inactive and not in use.
                                    @else
                                        Vehicle is involved in an accident.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Information Card -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>Audit Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Created:</strong></p>
                            <p class="text-muted">
                                <i class="far fa-calendar-plus mr-2"></i>
                                {{ $fleet->created_at->format('M d, Y') }}
                                <br>
                                <small>{{ $fleet->created_at->format('h:i A') }}</small>
                            </p>
                            
                            <p><strong>Last Updated:</strong></p>
                            <p class="text-muted">
                                <i class="far fa-calendar-check mr-2"></i>
                                {{ $fleet->updated_at->format('M d, Y') }}
                                <br>
                                <small>{{ $fleet->updated_at->format('h:i A') }}</small>
                            </p>
                            
                            @if($fleet->location_updated_at)
                                <p><strong>Last Location Update:</strong></p>
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{ $fleet->location_updated_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Card -->
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-sticky-note mr-2"></i>Notes
                            </h3>
                        </div>
                        <div class="card-body">
                            @if($fleet->notes)
                                <p class="text-muted">{{ $fleet->notes }}</p>
                            @else
                                <div class="alert alert-light">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No notes available for this fleet vehicle.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-history mr-2"></i>Recent Activity
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                Trip history and activity tracking will be implemented when Trips model is available.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('admin.fleets.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Fleet
                    </a>
                    <a href="{{ route('admin.fleets.create') }}" class="btn btn-success">
                        <i class="fas fa-plus mr-2"></i>Add New Vehicle
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">
                        Last updated: {{ $fleet->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        // Listen for delete confirmation
        Livewire.on('confirm-delete', (event) => {
            if (confirm(event.message)) {
                @this.delete();
            }
        });
    });
</script>
@endpush

@push('styles')
<style>
    .info-box {
        min-height: 90px;
        margin-bottom: 10px;
    }
    .info-box-icon {
        padding-top: 25px;
    }
    .card {
        margin-bottom: 20px;
    }
    .d-grid {
        display: grid;
    }
    .gap-2 {
        gap: 10px;
    }
    .display-4 {
        font-size: 3rem;
    }
</style>
@endpush