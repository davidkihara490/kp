<div>
    <div class="fleet-management">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-2 me-3">
                            <i class="fas fa-truck text-white fa-lg"></i>
                        </div>
                        <div>
                            <h3 class="card-title fw-bold mb-0">Fleet Management</h3>
                            <p class="text-muted mb-0 small">Manage your vehicle fleet efficiently</p>
                        </div>
                    </div>
                    <a href="{{ route('partners.fleet.create') }}" class="btn btn-primary btn-gradient">
                        <i class="fas fa-plus me-2"></i>Add New Vehicle
                    </a>
                </div>
            </div>

            <div class="card-body px-4 pt-4">
                @include('components.alerts.response-alerts')

                <!-- Enhanced Filters Section -->
                <div class="card filter-card mb-4 border">
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0"
                                        placeholder="Search by registration, make, model..."
                                        wire:model.live.debounce.300ms="search">
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <select class="form-select form-select-sm" wire:model.live="statusFilter">
                                    <option value="">All Status</option>
                                    @foreach($statuses as $status)
                                    <option value="{{ $status }}" class="text-capitalize">
                                        {{ ucfirst($status) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <select class="form-select form-select-sm" wire:model.live="typeFilter">
                                    <option value="">All Types</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type }}" class="text-capitalize">
                                        {{ ucfirst($type) }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <select class="form-select form-select-sm" wire:model.live="availabilityFilter">
                                    <option value="">Availability</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>

                            <div class="col-lg-1 col-md-6">
                                <button class="btn btn-outline-primary btn-sm w-100"
                                    wire:click="resetFilters"
                                    title="Reset all filters">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stat-card bg-primary bg-opacity-10 border-start border-primary border-3 p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Vehicles</h6>
                                    <h3 class="mb-0 fw-bold">{{ $fleets->total() }}</h3>
                                </div>
                                <i class="fas fa-truck fa-2x text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-success bg-opacity-10 border-start border-success border-3 p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Available</h6>
                                    <h3 class="mb-0 fw-bold">{{ $availableCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-warning bg-opacity-10 border-start border-warning border-3 p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Need Service</h6>
                                    <h3 class="mb-0 fw-bold">{{ $serviceNeededCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-tools fa-2x text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card bg-danger bg-opacity-10 border-start border-danger border-3 p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Expired Docs</h6>
                                    <h3 class="mb-0 fw-bold">{{ $expiredDocsCount ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Table -->
                <div class="table-responsive rounded border">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th wire:click="sortBy('registration_number')"
                                    class="sortable-header"
                                    style="min-width: 140px; cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <span>Registration</span>
                                        @if($sortField === 'registration_number')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                        @else
                                        <i class="fas fa-sort text-muted ms-1"></i>
                                        @endif
                                    </div>
                                </th>
                                <th style="min-width: 180px;">Vehicle Details</th>
                                <th style="min-width: 150px;"> Driver</th>
                                <th wire:click="sortBy('status')" class="sortable-header" style="min-width: 120px; cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <span>Status</span>
                                        @if($sortField === 'status')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                        @else
                                        <i class="fas fa-sort text-muted ms-1"></i>
                                        @endif
                                    </div>
                                </th>
                                <th style="min-width: 100px;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fleets as $fleet)
                            <tr class="align-middle">
                                <!-- Registration Column -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="vehicle-avatar bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-truck text-primary"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-primary">{{ $fleet->registration_number }}</strong>
                                            <small class="text-muted">{{ $fleet->year ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <!-- Vehicle Details -->
                                <td>
                                    <strong class="d-block">{{ $fleet->make }} {{ $fleet->model }}</strong>
                                    <div class="mt-2">
                                        <span class="badge bg-light text-dark border me-2">
                                            <i class="fas fa-car me-1"></i>{{ ucfirst($fleet->vehicle_type) }}
                                        </span>
                                        @if($fleet->color)
                                        <span class="badge bg-light text-dark border me-2">
                                            <i class="fas fa-palette me-1"></i>{{ $fleet->color }}
                                        </span>
                                        @endif
                                        @if($fleet->capacity)
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-weight-hanging me-1"></i>{{ $fleet->capacity }}kg
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Partner & Driver -->
                                <td>
                                    @if($fleet->current_driver)
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="driver-avatar bg-success bg-opacity-10 rounded-circle p-1 me-2">
                                            <i class="fas fa-user text-success fa-sm"></i>
                                        </div>
                                        <small>{{ $fleet->current_driver->first_name }} {{ $fleet->current_driver->last_name }}</small>
                                    </div>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge badge-status-{{ $fleet->status }}">
                                            <i class="fas {{ $fleet->status_icon }} me-1"></i>
                                            {{ ucfirst($fleet->status) }}
                                        </span>
                                        @if($fleet->is_available && $fleet->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                            <i class="fas fa-check-circle me-1"></i>Available
                                        </span>
                                        @elseif(!$fleet->is_available && $fleet->status === 'active')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                            <i class="fas fa-times-circle me-1"></i>Unavailable
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="action-buttons d-flex gap-1 justify-content-end" role="group">
                                        @if(!$fleet->currentDriver)
                                        <button class="btn btn-sm btn-outline-success"
                                            wire:click="openAssignDriverModal({{ $fleet->id }})"
                                            title="Assign Driver">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                        @else
                                        <button class="btn btn-sm btn-outline-warning"
                                            wire:click="openAssignDriverModal({{ $fleet->id }})"
                                            title="Change Driver">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        @endif
                                        <a href="{{ route('partners.fleet.view', $fleet->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('partners.fleet.edit', $fleet->id) }}"
                                            class="btn btn-sm btn-outline-info" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger"
                                            wire:click="confirmDelete({{ $fleet->id }})"
                                            title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-truck fa-4x text-muted mb-4 opacity-25"></i>
                                            <h4 class="text-muted mb-3">No Vehicles Found</h4>
                                            <p class="text-muted mb-4">Add your first vehicle to start managing your fleet</p>
                                            <a href="{{ route('partners.fleet.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>Add First Vehicle
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Footer with Pagination -->
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted small">
                            Showing <strong>{{ $fleets->firstItem() ?: 0 }}</strong> to
                            <strong>{{ $fleets->lastItem() ?: 0 }}</strong> of
                            <strong>{{ $fleets->total() }}</strong> vehicles
                        </p>
                    </div>
                    <div>
                        {{ $fleets->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Assign Driver Modal -->
        @if($showAssignDriverModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1"
            wire:click.self="$set('showAssignDriverModal', false)">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title">
                            <i class="fas fa-user-plus me-2"></i>
                            @if($selectedVehicle && $selectedVehicle->currentDriver)
                            Change Driver for {{ $selectedVehicle->registration_number }}
                            @else
                            Assign Driver to {{ $selectedVehicle ? $selectedVehicle->registration_number : '' }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('showAssignDriverModal', false)"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Search and Filter -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control"
                                        placeholder="Search drivers by name, email, phone..."
                                        wire:model.live.debounce.300ms="driverSearch">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" wire:model.live="driverStatusFilter">
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="off_duty">Off Duty</option>
                                </select>
                            </div>
                        </div>

                        <!-- Selected Driver Preview -->
                        @if($selectedDriverId)
                        <div class="alert alert-success mb-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Driver Selected:</strong>
                                    @php
                                    $selectedDriver = collect($drivers)->firstWhere('id', $selectedDriverId);
                                    @endphp
                                    @if($selectedDriver)
                                    {{ $selectedDriver->first_name }} {{ $selectedDriver->last_name }}
                                    ({{ $selectedDriver->email }})
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Drivers List -->
                        <div class="drivers-list" style="max-height: 400px; overflow-y: auto;">
                            @if($drivers && count($drivers) > 0)
                            @foreach($drivers as $driver)
                            <div class="driver-item card mb-2 {{ $selectedDriverId == $driver->id ? 'border-success border-2' : '' }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="driver-avatar bg-{{ $driver->status == 'available' ? 'success' : 'warning' }} bg-opacity-10 rounded-circle p-3 me-3">
                                                <i class="fas fa-user text-{{ $driver->status == 'available' ? 'success' : 'warning' }} fa-lg"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold">{{ $driver->first_name }} {{ $driver->last_name }}</h6>
                                                <div class="d-flex flex-wrap gap-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i>{{ $driver->email }}
                                                    </small>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i>{{ $driver->phone }}
                                                    </small>
                                                </div>
                                                <div class="mt-2">
                                                    <span class="badge bg-{{ $driver->status == 'available' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $driver->status == 'available' ? 'success' : 'warning' }} border border-{{ $driver->status == 'available' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($driver->status) }}
                                                    </span>
                                                    @if($driver->license_type)
                                                    <span class="badge bg-light text-dark border ms-2">
                                                        <i class="fas fa-id-card me-1"></i>{{ $driver->license_type }}
                                                    </span>
                                                    @endif
                                                    @if($driver->experience_years)
                                                    <span class="badge bg-light text-dark border ms-2">
                                                        <i class="fas fa-clock me-1"></i>{{ $driver->experience_years }} years exp.
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-{{ $selectedDriverId == $driver->id ? 'success' : 'outline-primary' }} btn-sm"
                                                wire:click="selectDriver({{ $driver->id }})">
                                                @if($selectedDriverId == $driver->id)
                                                <i class="fas fa-check me-1"></i>Selected
                                                @else
                                                Select
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-users fa-4x text-muted mb-3 opacity-25"></i>
                                    <h5 class="text-muted">No Drivers Found</h5>
                                    <p class="text-muted small">No available drivers match your search criteria</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Current Assignment Info -->
                        @if($selectedVehicle && $selectedVehicle->currentDriver)
                        <div class="alert alert-info mt-4 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Current Driver:</strong>
                                    {{ $selectedVehicle->currentDriver->first_name }} {{ $selectedVehicle->currentDriver->last_name }}
                                    <br>
                                    <small>Changing the driver will reassign this vehicle to the new driver</small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="$set('showAssignDriverModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-success"
                            wire:click="assignDriver"
                            @if(!$selectedDriverId) disabled @endif>
                            <i class="fas fa-check-circle me-2"></i>
                            @if($selectedVehicle && $selectedVehicle->currentDriver)
                            Change Driver
                            @else
                            Assign Driver
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Delete Modal -->
        @if($showDeleteModal && $fleetToDelete)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1"
            wire:click.self="$set('showDeleteModal', false)">
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
                            <strong class="text-danger">{{ $fleetToDelete->registration_number }}</strong>
                            - {{ $fleetToDelete->make }} {{ $fleetToDelete->model }}?
                        </p>
                        <p class="text-danger small">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            This action cannot be undone and all associated data will be permanently removed.
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary"
                            wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="delete">
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
        .fleet-management {
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
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4199 100%);
            color: white;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .sortable-header {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .sortable-header:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .badge-status-active {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .badge-status-inactive {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.2);
        }

        .badge-status-maintenance {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .vehicle-avatar,
        .partner-avatar,
        .driver-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-card {
            background: linear-gradient(to right, #f8f9fa, #fff);
        }

        .empty-state {
            max-width: 400px;
            margin: 0 auto;
        }

        .modal-icon {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.02);
        }

        .badge {
            padding: 0.4em 0.8em;
            font-weight: 500;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        .drivers-list::-webkit-scrollbar {
            width: 8px;
        }

        .drivers-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .drivers-list::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .drivers-list::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .driver-item {
            transition: all 0.3s ease;
        }

        .driver-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .driver-item.border-success {
            box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.25);
        }

        .action-buttons .btn {
            transition: all 0.2s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
        }

        /* Modal close on background click */
        [wire\:click\.self] {
            cursor: pointer;
        }
    </style>
    @endpush
</div>