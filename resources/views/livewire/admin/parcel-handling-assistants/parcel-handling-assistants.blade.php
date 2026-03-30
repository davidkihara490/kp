<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold d-inline">
                    <i class="fas fa-users-cog mr-2"></i>Parcel Handling Assistants
                </h3>
                <!-- <a href="{{ route('admin.pha.create') }}" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-user-plus mr-2"></i>Add Assistant
                </a> -->
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Assistants</span>
                                <span class="info-box-number">{{ $totalAssistants }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active</span>
                                <span class="info-box-number">{{ $activeAssistants }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-user-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Inactive</span>
                                <span class="info-box-number">{{ $inactiveAssistants }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-user-slash"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Suspended</span>
                                <span class="info-box-number">{{ $suspendedAssistants }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by name, email, phone..."
                                wire:model.live.debounce.300ms="search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" wire:model.live="statusFilter">
                            @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" wire:model.live="stationFilter">
                            <option value="">All Stations</option>
                            @foreach ($pickUpAndDropOffPoints as $station)
                            <option value="{{ $station->id }}">{{ $station->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary btn-block" wire:click="resetFilters"
                            title="Reset Filters">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                @if ($showBulkActions)
                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-tasks mr-2"></i>
                            <strong>{{ count($selectedAssistants) }}</strong> assistants selected
                        </div>
                        <div>
                            <button class="btn btn-sm btn-success mr-2" wire:click="bulkActivate">
                                <i class="fas fa-user-check mr-1"></i>Activate
                            </button>
                            <button class="btn btn-sm btn-danger mr-2" wire:click="bulkSuspend">
                                <i class="fas fa-user-slash mr-1"></i>Suspend
                            </button>
                            <button class="btn btn-sm btn-danger" wire:click="bulkDelete">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 30px;">
                                    <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                                </th>
                                <th wire:click="sortBy('first_name')" style="cursor: pointer; width: 20%;">
                                    Name
                                    @if ($sortField !== 'first_name')
                                    <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                    @else
                                    <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th style="width: 15%;">Contact Info</th>
                                <th style="width: 10%;">ID Number</th>
                                <th style="width: 15%;">Partner</th>

                                <th wire:click="sortBy('status')" style="cursor: pointer; width: 10%;">
                                    Status
                                    @if ($sortField !== 'status')
                                    <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                    @else
                                    <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('created_at')" style="cursor: pointer; width: 10%;">
                                    Joined
                                    @if ($sortField !== 'created_at')
                                    <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                    @else
                                    <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assistants as $assistant)
                            @php
                            $statusBadge = $this->getStatusBadge($assistant->status);
                            $activeEmployments = $assistant->where('status', 'active');
                            @endphp
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model.live="selectedAssistants"
                                        value="{{ $assistant->id }}" class="form-check-input">
                                </td>
                                <td>
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-2"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $assistant->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">User ID:
                                                {{ $assistant->user_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div>
                                            <i class="fas fa-envelope mr-1 text-primary"></i>
                                            {{ $assistant->email }}
                                        </div>
                                        <div class="mt-1">
                                            <i class="fas fa-phone mr-1 text-success"></i>
                                            {{ $assistant->phone_number }}
                                        </div>
                                        @if ($assistant->user)
                                        <div class="mt-1">
                                            <i class="fas fa-user-circle mr-1 text-info"></i>
                                            Last login:
                                            {{ $assistant->last_login_at ? $assistant->last_login_at : 'Never' }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $assistant->id_number }}</code>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('admin.partners.view', $assistant->partner->id) }}">{{ $assistant->partner->company_name }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $statusBadge['color'] }}">
                                        <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                                        {{ $statusBadge['text'] }}
                                    </span>
                                </td>
                                <td>
                                    @if ($activeEmployments->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($activeEmployments as $employment)
                                        <span class="badge badge-light"
                                            title="{{ $employment->stationPartner->name ?? 'Unknown Station' }}">
                                            <i class="fas fa-building mr-1"></i>
                                            {{ Str::limit($employment->stationPartner->name ?? 'N/A', 15) }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Not Assigned
                                    </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        {{ $activeEmployments->count() }} active assignment(s)
                                    </small>
                                </td>
                                <td>
                                    <div class="small">
                                        {{ $assistant->created_at }}
                                        <br>
                                        <span class="text-muted">by
                                            {{ $assistant->creator->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">

                                        <a href="{{ route('admin.pha.view', $assistant->id) }}"
                                            class="btn btn-warning" title="Edit">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pha.edit', $assistant->id) }}"
                                            class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-secondary"
                                            wire:click="showAssignStation({{ $assistant->id }})"
                                            title="Assign Station">
                                            <i class="fas fa-building"></i>
                                        </button>
                                        @if ($assistant->status === 'active')
                                        <button class="btn btn-danger"
                                            wire:click="suspendAssistant({{ $assistant->id }})"
                                            title="Suspend">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        @elseif($assistant->status === 'suspended')
                                        <button class="btn btn-success"
                                            wire:click="activateAssistant({{ $assistant->id }})"
                                            title="Activate">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @elseif($assistant->status === 'inactive')
                                        <button class="btn btn-success"
                                            wire:click="toggleStatus({{ $assistant->id }})" title="Activate">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                        @endif
                                        <button class="btn btn-danger"
                                            wire:click="confirmDelete({{ $assistant->id }})" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No assistants found</p>
                                        <a href="{{ route('admin.pha.create') }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-user-plus mr-2"></i>Add First Assistant
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Delete Modal -->
                @if ($showDeleteModal && $assistantToDelete)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Assistant</h5>
                                <button type="button" class="close"
                                    wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete assistant
                                    <strong>"{{ $assistantToDelete->full_name }}"</strong>?
                                </p>
                                <p class="text-danger">
                                    <strong>Warning:</strong> This will also delete the associated user account and
                                    all employment records.
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showDeleteModal', false)">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-danger" wire:click="delete">
                                    Delete Assistant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assign Station Modal -->
                @if ($showEmploymentModal && $selectedAssistantForEmployment)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Assign Station to Assistant</h5>
                                <button type="button" class="close"
                                    wire:click="$set('showEmploymentModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Assigning station to:
                                    <strong>{{ $selectedAssistantForEmployment->full_name }}</strong>
                                </p>

                                <div class="form-group">
                                    <label for="stationSelect">Select Station</label>
                                    <select id="stationSelect" class="form-control" wire:model="selectedStation">
                                        <option value="">-- Select a Station --</option>
                                        @foreach ($pickUpAndDropOffPoints as $station)
                                        <option value="{{ $station->id }}">
                                            {{ $station->name }}
                                            @if ($station->location)
                                            - {{ $station->location }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('selectedStation')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if ($selectedAssistantForEmployment->employments->count() > 0)
                                <div class="alert alert-info mt-3">
                                    <h6>Current Assignments:</h6>
                                    <ul class="mb-0">
                                        @foreach ($selectedAssistantForEmployment->employments->where('status', 'active') as $employment)
                                        <li>
                                            {{ $employment->stationPartner->name ?? 'Unknown' }}
                                            ({{ ucfirst($employment->status) }})
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showEmploymentModal', false)">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-primary" wire:click="assignStation"
                                    @if (!$selectedStation) disabled @endif>
                                    <i class="fas fa-building mr-1"></i>
                                    Assign Station
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
                            Showing {{ $assistants->firstItem() }} to {{ $assistants->lastItem() }}
                            of {{ $assistants->total() }} assistants
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $assistants->links() }}
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
        <style>
            .table th {
                white-space: nowrap;
            }

            .info-box {
                background: #fff;
                border-radius: 0.25rem;
                padding: 15px;
                box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
                margin-bottom: 0;
            }

            .info-box-icon {
                border-radius: 0.25rem;
                float: left;
                height: 70px;
                width: 70px;
                text-align: center;
                font-size: 30px;
                line-height: 70px;
                background: rgba(0, 0, 0, 0.2);
            }

            .info-box-content {
                padding: 5px 10px;
                margin-left: 80px;
            }

            .info-box-text {
                text-transform: uppercase;
                font-weight: 600;
                font-size: 14px;
                color: #6c757d;
            }

            .info-box-number {
                font-weight: 700;
                font-size: 22px;
                color: #343a40;
            }

            .badge-light {
                background-color: #f8f9fa;
                color: #495057;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }

            .rounded-circle {
                border-radius: 50% !important;
            }
        </style>
        @endpush
    </div>
</div>