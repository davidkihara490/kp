<div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-user-circle mr-2"></i>Driver Details: {{ $driver->full_name }}
            </h3>
            <div class="card-tools">
                @php
                    $statusBadge = $this->getStatusBadge();
                @endphp
                <span class="badge badge-{{ $statusBadge['color'] }}">
                    <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                    {{ ucfirst($driver->status) }}
                </span>
                @if($driver->is_available && $driver->status === 'active')
                    <span class="badge badge-success ml-2">
                        <i class="fas fa-check-circle mr-1"></i>Available
                    </span>
                @endif
                
                @if($driver->isLicenseExpiringSoon())
                    <span class="badge badge-warning ml-2" title="License expiring soon">
                        <i class="fas fa-exclamation-triangle mr-1"></i>License Expiring
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
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Deliveries</span>
                            <span class="info-box-number">{{ $stats['total_deliveries'] }}</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Success Rate</span>
                            <span class="info-box-number">{{ number_format($stats['success_rate'], 1) }}%</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $stats['success_rate'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-star"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rating</span>
                            <span class="info-box-number">{{ number_format($stats['average_rating'], 1) }} / 5</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($stats['average_rating'] / 5) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Personal Information -->
                <div class="col-md-8">
                    <!-- Personal Information Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user mr-2"></i>Personal Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong>Full Name:</strong></p>
                                    <p class="text-muted">{{ $driver->full_name }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>ID Number:</strong></p>
                                    <p class="text-muted">{{ $driver->id_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong>Gender:</strong></p>
                                    <p class="text-muted">{{ ucfirst($driver->gender) ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Email:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ $driver->email ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phone Number:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ $driver->phone_number ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Alternate Phone:</strong></p>
                                    <p class="text-muted">
                                        <i class="fas fa-phone-alt mr-2"></i>
                                        {{ $driver->alternate_phone_number ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Age:</strong></p>
                                    <p class="text-muted">{{ $this->getAge() }} years</p>
                                </div>
                            </div>
                            
                            @if($driver->notes)
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Notes:</strong></p>
                                    <p class="text-muted">{{ $driver->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- License Information Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-id-card mr-2"></i>Driver's License Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>License Number:</strong></p>
                                    <p class="text-muted">{{ $driver->driving_license_number ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>License Class:</strong></p>
                                    <p class="text-muted">{{ $driver->license_class ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Issue Date:</strong></p>
                                    <p class="text-muted">{{ $driver->driving_license_issue_date ? $driver->driving_license_issue_date->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Expiry Date:</strong></p>
                                    <p class="text-muted">
                                        {{ $driver->driving_license_expiry_date ? $driver->driving_license_expiry_date->format('M d, Y') : 'N/A' }}
                                        @if($driver->driving_license_expiry_date)
                                            <span class="badge badge-{{ $this->getLicenseValidityColor() }} ml-2">
                                                {{ $driver->isLicenseValid() ? 'Valid' : 'Expired' }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            @if($driver->isLicenseExpiringSoon())
                                <div class="alert alert-warning mt-3">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <strong>Warning:</strong> Driver's license will expire on 
                                    {{ $driver->driving_license_expiry_date->format('M d, Y') }}. 
                                    Please remind driver to renew.
                                </div>
                            @endif
                            
                            @if($driver->driving_license_expiry_date && $driver->driving_license_expiry_date->isPast())
                                <div class="alert alert-danger mt-3">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    <strong>Alert:</strong> Driver's license has expired on 
                                    {{ $driver->driving_license_expiry_date->format('M d, Y') }}. 
                                    Driver cannot operate vehicles until license is renewed.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Employment & Assignment Card -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-briefcase mr-2"></i>Employment & Assignment
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Partner:</strong></p>
                                    <p class="text-muted">
                                        @if($driver->partner)
                                            <span class="badge badge-info">{{ $driver->partner->company_name }}</span>
                                        @else
                                            <span class="badge badge-secondary">Not Assigned</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Employment Date:</strong></p>
                                    <p class="text-muted">
                                        {{ $driver->employment_date ? $driver->employment_date->format('M d, Y') : 'N/A' }}
                                        @if($driver->employment_date)
                                            <br><small>({{ $this->getEmploymentDuration() }})</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Current Vehicle:</strong></p>
                                    @if($driver->currentFleet())
                                        <p class="text-muted">
                                            <i class="fas fa-truck mr-2"></i>
                                            {{ $driver->currentFleet()->full_name }}
                                            <br>
                                            <small class="text-muted">Since {{ $driver->currentFleet()->pivot->from->format('M d, Y') }}</small>
                                        </p>
                                    @else
                                        <p class="text-muted">
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                No Vehicle Assigned
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Driver ID:</strong></p>
                                    <p class="text-muted">
                                        <span class="badge badge-dark">DRV-{{ str_pad($driver->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Card -->
                    @if($driver->emergency_contact_name || $driver->emergency_contact_phone)
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-phone-alt mr-2"></i>Emergency Contact
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong></p>
                                    <p class="text-muted">{{ $driver->emergency_contact_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phone:</strong></p>
                                    <p class="text-muted">{{ $driver->emergency_contact_phone }}</p>
                                </div>
                            </div>
                            
                            @if($driver->emergency_contact_relationship)
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Relationship:</strong></p>
                                    <p class="text-muted">{{ $driver->emergency_contact_relationship }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
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
                                <a href="{{ route('admin.drivers.edit', $driver->id) }}" 
                                   class="btn btn-warning btn-block">
                                    <i class="fas fa-edit mr-2"></i>Edit Driver
                                </a>
                                
                                <button type="button" class="btn btn-{{ $driver->is_available ? 'danger' : 'success' }} btn-block"
                                        wire:click="toggleAvailability"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="toggleAvailability">
                                        <i class="fas fa-power-off mr-2"></i>
                                        {{ $driver->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}
                                    </span>
                                    <span wire:loading wire:target="toggleAvailability">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                                
                                <button type="button" class="btn btn-{{ $driver->status === 'active' ? 'secondary' : 'success' }} btn-block"
                                        wire:click="toggleStatus"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="toggleStatus">
                                        <i class="fas fa-exchange-alt mr-2"></i>
                                        {{ $driver->status === 'active' ? 'Deactivate Driver' : 'Activate Driver' }}
                                    </span>
                                    <span wire:loading wire:target="toggleStatus">
                                        <span class="spinner-border spinner-border-sm" role="status"></span>
                                        Updating...
                                    </span>
                                </button>
                                
                                @if(!$driver->currentFleet())
                                <a href="#" 
                                   class="btn btn-info btn-block">
                                    <i class="fas fa-truck mr-2"></i>Assign Vehicle
                                </a>
                                @endif
                                
                                <button type="button" class="btn btn-danger btn-block"
                                        wire:click="confirmDelete"
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-trash mr-2"></i>Delete Driver
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
                                    {{ ucfirst($driver->status) }}
                                </h4>
                                <p class="text-muted">
                                    @if($driver->status === 'active')
                                        Driver is active and available for assignments.
                                    @elseif($driver->status === 'inactive')
                                        Driver is currently inactive.
                                    @elseif($driver->status === 'suspended')
                                        Driver has been suspended.
                                    @elseif($driver->status === 'terminated')
                                        Driver has been terminated.
                                    @else
                                        Driver status is pending review.
                                    @endif
                                </p>
                                
                                @if($driver->is_available && $driver->status === 'active')
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Available for dispatch
                                    </span>
                                @elseif(!$driver->is_available && $driver->status === 'active')
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock mr-1"></i>
                                        Currently unavailable
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bank Information Card -->
                    @if($driver->bank_name || $driver->bank_account_number)
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-university mr-2"></i>Bank Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Bank Name:</strong></p>
                            <p class="text-muted">{{ $driver->bank_name ?? 'N/A' }}</p>
                            
                            <p><strong>Account Number:</strong></p>
                            <p class="text-muted">
                                @if($driver->bank_account_number)
                                    ****{{ substr($driver->bank_account_number, -4) }}
                                @else
                                    N/A
                                @endif
                            </p>
                            
                            <p><strong>Account Name:</strong></p>
                            <p class="text-muted">{{ $driver->bank_account_name ?? $driver->full_name }}</p>
                        </div>
                    </div>
                    @endif

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
                                {{ $driver->created_at->format('M d, Y') }}
                                <br>
                                <small>{{ $driver->created_at->format('h:i A') }}</small>
                            </p>
                            
                            <p><strong>Last Updated:</strong></p>
                            <p class="text-muted">
                                <i class="far fa-calendar-check mr-2"></i>
                                {{ $driver->updated_at->format('M d, Y') }}
                                <br>
                                <small>{{ $driver->updated_at->format('h:i A') }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Trips -->
            @if($driver->trips && $driver->trips->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-route mr-2"></i>Recent Trips
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.drivers.trips', $driver->id) }}" class="btn btn-sm btn-info">
                                    View All <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Distance</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($driver->trips as $trip)
                                    <tr>
                                        <td>{{ $trip->created_at->format('M d, Y') }}</td>
                                        <td>{{ $trip->origin ?? 'N/A' }}</td>
                                        <td>{{ $trip->destination ?? 'N/A' }}</td>
                                        <td>{{ $trip->distance ?? 'N/A' }} km</td>
                                        <td>
                                            <span class="badge badge-{{ $trip->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($trip->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Deliveries -->
            @if($driver->deliveries && $driver->deliveries->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-box mr-2"></i>Recent Deliveries
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.drivers.deliveries', $driver->id) }}" class="btn btn-sm btn-info">
                                    View All <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Parcel #</th>
                                        <th>Customer</th>
                                        <th>Destination</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($driver->deliveries as $delivery)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.parcels.show', $delivery->parcel_id) }}">
                                                {{ $delivery->parcel->parcel_id ?? 'N/A' }}
                                            </a>
                                        </td>
                                        <td>{{ $delivery->parcel->receiver_name ?? 'N/A' }}</td>
                                        <td>{{ $delivery->parcel->receiver_town->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $delivery->status == 'delivered' ? 'success' : 'warning' }}">
                                                {{ ucfirst($delivery->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $delivery->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Drivers
                    </a>
                    <a href="{{ route('admin.drivers.create') }}" class="btn btn-success">
                        <i class="fas fa-plus mr-2"></i>Add New Driver
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <span class="text-muted">
                        Last updated: {{ $driver->updated_at->diffForHumans() }}
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
    .badge {
        padding: 0.5em 0.75em;
    }
    .table td {
        vertical-align: middle;
    }
</style>
@endpush