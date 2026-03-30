<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-users mr-2"></i>Drivers
            </h3>
            <!-- <a href="{{ route('admin.drivers.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus mr-2"></i>New Driver
            </a> -->
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search drivers..." wire:model.live.debounce.300ms="search">
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
                            <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
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
                    <select class="form-control" wire:model.live="licenseStatusFilter">
                        <option value="">License Status</option>
                        <option value="valid">Valid</option>
                        <option value="expired">Expired</option>
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
                            <th wire:click="sortBy('driver_id')" style="cursor: pointer;">
                                Driver ID 
                                @if($sortField !== 'driver_id')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th>Driver</th>
                            <th>Contact</th>
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
                            <th>License</th>
                            <th>Performance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            @php
                                $statusBadge = $driver->getStatusBadge();
                                $licenseColor = $driver->getLicenseValidityColor();
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $driver->driving_license_number }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $driver->full_name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-id-card mr-1"></i>{{ $driver->id_number ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <small><i class="fas fa-phone mr-1"></i>{{ $driver->phone_number }}</small><br>
                                    <small><i class="fas fa-envelope mr-1"></i>{{ $driver->email }}</small>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('admin.partners.view',  $driver->partner->id) }}">{{ $driver->partner->company_name }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusBadge['color'] }}">
                                        <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $driver->status)) }}
                                    </span>
                                    @if($driver->is_available && $driver->status === 'active')
                                        <span class="badge badge-success mt-1">
                                            <i class="fas fa-check-circle mr-1"></i>Available
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $licenseColor }}">
                                        <i class="fas fa-id-card mr-1"></i>
                                        {{ $driver->driving_license_number }}
                                    </span><br>
                                    <small class="text-muted">
                                        Exp: {{ $driver->driving_license_expiry_date->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <div class="text-center">
                                                <strong class="d-block">{{ $driver->rating ?? '0.0' }}</strong>
                                                <small class="text-muted">Rating</small>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $driver->total_delivery_rate }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $driver->total_deliveries }} deliveries</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.drivers.view', $driver->id) }}" 
                                           class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.drivers.edit', $driver->id) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger" 
                                                wire:click="confirmDelete({{ $driver->id }})" 
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No drivers found</p>
                                        <a href="{{ route('admin.drivers.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Add First Driver
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Delete Modal -->
            @if($showDeleteModal && $driverToDelete)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Driver</h5>
                                <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $driverToDelete->full_name }}</strong> ({{ $driverToDelete->driver_id }})?</p>
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
                        Showing {{ $drivers->firstItem() }} to {{ $drivers->lastItem() }} 
                        of {{ $drivers->total() }} drivers
                    </p>
                </div>
                <div class="col-md-6">
                    {{ $drivers->links() }}
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
</style>
@endpush