<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-truck mr-2"></i>Fleet Management
            </h3>
            <!-- <a href="{{ route('admin.fleets.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus mr-2"></i>Add Fleet
            </a> -->
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search fleet..." wire:model.live.debounce.300ms="search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model.live="typeFilter">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model.live="transportPartnerFilter">
                        <option value="">All Partners</option>
                        @foreach($transportPartners as $partner)
                        <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" wire:model.live="availabilityFilter">
                        <option value="">Availability</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-outline-secondary btn-block" wire:click="resetFilters" title="Reset Filters">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('registration_number')" style="cursor: pointer;">
                                Registration
                                @if($sortField !== 'registration_number')
                                <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                <i class="fas fa-sort-up"></i>
                                @else
                                <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th>Vehicle Details</th>
                            <th>Partner</th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;">
                                Status
                                @if($sortField !== 'status')
                                <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                <i class="fas fa-sort-up"></i>
                                @else
                                <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th>Documents</th>
                            <th>Metrics</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fleets as $fleet)
                        <tr>
                            <td>
                                <strong>{{ $fleet->registration_number }}</strong><br>
                                <small class="text-muted">{{ $fleet->year ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <strong>{{ $fleet->make }} {{ $fleet->model }}</strong><br>
                                <small class="text-muted">
                                    <i class="fas fa-car mr-1"></i>{{ ucfirst($fleet->vehicle_type) }}
                                    @if($fleet->color)
                                    • <i class="fas fa-palette mr-1"></i>{{ $fleet->color }}
                                    @endif
                                    @if($fleet->capacity)
                                    • <i class="fas fa-weight-hanging mr-1"></i>{{ $fleet->capacity }} kg
                                    @endif
                                </small>
                            </td>
                            <td>
                                <a target="_blank" href="{{ route('admin.partners.view',  $fleet->partner->id) }}">{{ $fleet->partner->company_name }}</a>

                            </td>
                            <td>
                                <span class="badge badge-{{ $fleet->status_color }}">
                                    <i class="fas {{ $fleet->status_icon }} mr-1"></i>
                                    {{ ucfirst($fleet->status) }}
                                </span>
                                @if($fleet->is_available && $fleet->status === 'active')
                                <span class="badge badge-success mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>Available
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    @if($fleet->is_registration_valid)
                                    <span class="badge badge-success">
                                        <i class="fas fa-id-card mr-1"></i>Reg: Valid
                                    </span>
                                    @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Reg: Expired
                                    </span>
                                    @endif

                                    @if($fleet->is_insurance_valid)
                                    <span class="badge badge-success mt-1">
                                        <i class="fas fa-shield-alt mr-1"></i>Ins: Valid
                                    </span>
                                    @else
                                    <span class="badge badge-danger mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Ins: Expired
                                    </span>
                                    @endif

                                    @if($fleet->needs_service)
                                    <span class="badge badge-warning mt-1">
                                        <i class="fas fa-tools mr-1"></i>Needs Service
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <div class="text-center">
                                            <strong class="d-block">{{ number_format($fleet->odometer_reading) }}</strong>
                                            <small class="text-muted">KM</small>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 75%"></div>
                                        </div>
                                        <small class="text-muted">{{ $fleet->trips_count }} trips • {{ $fleet->drivers_count }} drivers</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.fleets.view', $fleet->id) }}"
                                        class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.fleets.edit', $fleet->id) }}"
                                        class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger"
                                        wire:click="confirmDelete({{ $fleet->id }})"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <div class="py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No fleet vehicles found</p>
                                    <a href="{{ route('admin.fleets.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i>Add First Vehicle
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Delete Modal -->
            @if($showDeleteModal && $fleetToDelete)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Fleet Vehicle</h5>
                            <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete <strong>{{ $fleetToDelete->full_name }}</strong>?</p>
                            <p class="text-danger"><small>This action cannot be undone.</small></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showDeleteModal', false)">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-danger" wire:click="delete">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">
                        Showing {{ $fleets->firstItem() }} to {{ $fleets->lastItem() }}
                        of {{ $fleets->total() }} vehicles
                    </p>
                </div>
                <div class="col-md-6">
                    {{ $fleets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }

    .progress {
        background-color: #e9ecef;
    }

    .badge {
        font-size: 0.85em;
    }
</style>
@endpush