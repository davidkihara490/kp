<div>
    <div>
        <div class="dashboard-section">
            <!-- Header Section -->
            <div class="section-header">
                <div>
                    <h3 class="section-title">
                        <i class="bi bi-people me-2"></i>
                        Parcel Handling Assistants
                    </h3>
                    <p class="section-subtitle">Manage all parcel handling assistants</p>
                </div>
                <div class="header-actions">
                    <!-- Search -->
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                                placeholder="Search assistants...">
                            @if ($search)
                            <button class="btn btn-outline-secondary" wire:click="$set('search', '')">
                                <i class="bi bi-x"></i>
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            @if ($statusFilter == 'active')
                            <i class="bi bi-check-circle me-2 text-success"></i>
                            Active
                            @elseif($statusFilter == 'inactive')
                            <i class="bi bi-x-circle me-2 text-secondary"></i>
                            Inactive
                            @elseif($statusFilter == 'suspended')
                            <i class="bi bi-ban me-2 text-danger"></i>
                            Suspended
                            @elseif($statusFilter == 'pending')
                            <i class="bi bi-clock me-2 text-warning"></i>
                            Pending
                            @else
                            <i class="bi bi-funnel me-2"></i>
                            Status
                            @endif
                            @if ($statusFilter)
                            <span class="filter-badge">1</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ !$statusFilter ? 'active' : '' }}"
                                    wire:click="$set('statusFilter', '')">
                                    <i class="bi bi-funnel me-2"></i>
                                    All Status
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item {{ $statusFilter == 'active' ? 'active' : '' }}"
                                    wire:click="$set('statusFilter', 'active')">
                                    <i class="bi bi-check-circle me-2 text-success"></i>
                                    Active
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $statusFilter == 'inactive' ? 'active' : '' }}"
                                    wire:click="$set('statusFilter', 'inactive')">
                                    <i class="bi bi-x-circle me-2 text-secondary"></i>
                                    Inactive
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $statusFilter == 'suspended' ? 'active' : '' }}"
                                    wire:click="$set('statusFilter', 'suspended')">
                                    <i class="bi bi-ban me-2 text-danger"></i>
                                    Suspended
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $statusFilter == 'pending' ? 'active' : '' }}"
                                    wire:click="$set('statusFilter', 'pending')">
                                    <i class="bi bi-clock me-2 text-warning"></i>
                                    Pending
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Station Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            @if ($stationFilter)
                            <i class="bi bi-shop me-2"></i>
                            {{ $stations->firstWhere('id', $stationFilter)?->name ?? 'Station' }}
                            @else
                            <i class="bi bi-geo-alt me-2"></i>
                            Station
                            @endif
                            @if ($stationFilter)
                            <span class="filter-badge">1</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="max-height: 300px; overflow-y: auto;">
                            <li>
                                <a class="dropdown-item {{ !$stationFilter ? 'active' : '' }}"
                                    wire:click="$set('stationFilter', '')">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    All Stations
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @foreach ($stations as $station)
                            <li>
                                <a class="dropdown-item {{ $stationFilter == $station->id ? 'active' : '' }}"
                                    wire:click="$set('stationFilter', '{{ $station->id }}')">
                                    <i class="bi bi-shop me-2"></i>
                                    {{ $station->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Clear Filters -->
                    @if ($search || $statusFilter || $stationFilter)
                    <button class="btn btn-outline-danger" wire:click="resetFilters">
                        <i class="bi bi-x-circle me-2"></i>
                        Clear
                    </button>
                    @endif

                    <!-- Add New Assistant -->

                    <a href="{{ route('partners.pha.create') }}" class="btn btn-primary"> <i
                            class="bi bi-person-plus me-2"></i>
                        Add Assistant</a>

                </div>
            </div>

            <!-- Stats Overview -->
            <div class="stats-overview mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon total">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $totalAssistants }}</div>
                                <div class="stat-label">Total Assistants</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon active">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $activeAssistants }}</div>
                                <div class="stat-label">Active</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon pending">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $pendingAssistants }}</div>
                                <div class="stat-label">Pending</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon suspended">
                                <i class="bi bi-ban"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $suspendedAssistants }}</div>
                                <div class="stat-label">Suspended</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions (when assistants are selected) -->
            @if ($showBulkActions)
            <div class="bulk-actions-bar mb-3 p-3 bg-light border rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-check-circle-fill text-primary"></i>
                        <span class="fw-medium">{{ count($selectedAssistants) }} assistants selected</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-success" wire:click="bulkActivate">
                            <i class="bi bi-check-circle me-1"></i>
                            Activate
                        </button>
                        <button class="btn btn-sm btn-outline-danger" wire:click="bulkSuspend">
                            <i class="bi bi-ban me-1"></i>
                            Suspend
                        </button>
                        <button class="btn btn-sm btn-outline-dark" wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete selected assistants?">
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>
                        <button class="btn btn-sm btn-outline-secondary"
                            wire:click="$set('selectedAssistants', [])">
                            <i class="bi bi-x me-1"></i>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Name</span>
                                    @if ($sortField === 'first_name')
                                    <i
                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Contact</th>
                            <th wire:click="sortBy('id_number')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>ID Number</span>
                                    @if ($sortField === 'id_number')
                                    <i
                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Role</th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Status</span>
                                    @if ($sortField === 'status')
                                    <i
                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Joined</span>
                                    @if ($sortField === 'created_at')
                                    <i
                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assistants as $assistant)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar">
                                        @if ($assistant->user && $assistant->user->avatar)
                                        <img src="{{ $assistant->user->avatar }}"
                                            alt="{{ $assistant->full_name }}" class="rounded-circle">
                                        @else
                                        <div class="avatar-placeholder">
                                            {{ substr($assistant->first_name, 0, 1) }}{{ substr($assistant->last_name, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $assistant->full_name }}</div>
                                        <small class="text-muted">ID: {{ $assistant->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <div class="mb-1">
                                        <i class="bi bi-envelope me-1 text-muted"></i>
                                        <a href="mailto:{{ $assistant->email }}" class="text-decoration-none">
                                            {{ $assistant->email }}
                                        </a>
                                    </div>
                                    <div>
                                        <i class="bi bi-telephone me-1 text-muted"></i>
                                        <a href="tel:{{ $assistant->phone_number }}"
                                            class="text-decoration-none">
                                            {{ $assistant->phone_number }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="id-info">
                                    <span class="badge bg-light text-dark">{{ $assistant->id_number }}</span>
                                    <small class="d-block text-muted">User:
                                        {{ $assistant->user->user_name }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="station-assignments">
                                    <span class="badge bg-primary mb-1">
                                        <i class="bi bi-shop me-1"></i>
                                        {{ $assistant->user->getRoleNames()->first()  ?? 'No role assigned'}}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @php
                                $statusBadge = $this->getStatusBadge($assistant->status);
                                @endphp
                                <span class="status-badge status-{{ $statusBadge['color'] }}">
                                    <i class="bi {{ str_replace('fa', 'bi', $statusBadge['icon']) }} me-1"></i>
                                    {{ $statusBadge['text'] }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <div>{{ $assistant->created_at->format('M d, Y') }}</div>
                                    <small
                                        class="text-muted">{{ $assistant->created_at->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">

                                    <a href="{{ route('partners.pha.edit', $assistant->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="{{ route('partners.pha.view', $assistant->id) }}"
                                        class="btn btn-sm btn-outline-warning"><i class="bi bi-eye"></i></a>


                                    <!-- Status Actions -->
                                    @if ($assistant->status === 'active')
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="suspendAssistant({{ $assistant->id }})" title="Suspend">
                                        <i class="bi bi-ban"></i>
                                    </button>
                                    @elseif($assistant->status === 'suspended')
                                    <button class="btn btn-sm btn-outline-success"
                                        wire:click="activateAssistant({{ $assistant->id }})"
                                        title="Activate">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    @elseif($assistant->status === 'inactive')
                                    <button class="btn btn-sm btn-outline-success"
                                        wire:click="toggleStatus({{ $assistant->id }})" title="Activate">
                                        <i class="bi bi-toggle-on"></i>
                                    </button>
                                    @endif

                                    <!-- Delete Action -->
                                    <button class="btn btn-sm btn-outline-dark"
                                        wire:click="confirmDelete({{ $assistant->id }})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-people display-1 text-muted"></i>
                                        <h4 class="mt-3">No Assistants Found</h4>
                                        <p class="text-muted">
                                            @if ($search || $statusFilter || $stationFilter)
                                            No assistants match your filters
                                            @else
                                            No parcel handling assistants have been added yet
                                            @endif
                                        </p>
                                        @if ($search || $statusFilter || $stationFilter)
                                        <button class="btn btn-primary mt-2" wire:click="resetFilters">
                                            Clear Filters
                                        </button>
                                        @else
                                        <a href="{{ route('partners.pha.create') }}" class="btn btn-primary">
                                            <i class="bi bi-person-plus me-2"></i>
                                            Add First Assistant</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="results-info">
                        <span class="small text-muted">
                            Showing
                            <strong>{{ $assistants->firstItem() ?? 0 }}-{{ $assistants->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $assistants->total() }}</strong> assistants
                        </span>
                    </div>
                    <div>
                        {{ $assistants->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Assignment Modal -->
        @if ($showEmploymentModal && $selectedAssistantForEmployment)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-shop me-2"></i>
                            Assign Station
                        </h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showEmploymentModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="assistant-info p-3 bg-light rounded mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar">
                                        @if ($selectedAssistantForEmployment->user && $selectedAssistantForEmployment->user->avatar)
                                        <img src="{{ $selectedAssistantForEmployment->user->avatar }}"
                                            alt="{{ $selectedAssistantForEmployment->full_name }}"
                                            class="rounded-circle">
                                        @else
                                        <div class="avatar-placeholder">
                                            {{ substr($selectedAssistantForEmployment->first_name, 0, 1) }}{{ substr($selectedAssistantForEmployment->last_name, 0, 1) }}
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $selectedAssistantForEmployment->full_name }}</h6>
                                        <small
                                            class="text-muted">{{ $selectedAssistantForEmployment->email }}</small>
                                    </div>
                                </div>
                            </div>

                            <label for="selectedStation" class="form-label">Select Station</label>
                            <select class="form-select @error('selectedStation') is-invalid @enderror"
                                id="selectedStation" wire:model="selectedStation">
                                <option value="">Choose a station...</option>
                                @foreach ($stations as $station)
                                <option value="{{ $station->id }}">{{ $station->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedStation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showEmploymentModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="assignStation"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="assignStation">
                                <i class="bi bi-check-circle me-2"></i>
                                Assign
                            </span>
                            <span wire:loading wire:target="assignStation">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Assigning...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if ($showDeleteModal && $assistantToDelete)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Confirm Delete
                        </h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-3">
                            <i class="bi bi-trash text-danger display-4"></i>
                            <h4 class="mt-3">Delete Assistant?</h4>
                            <p class="text-muted">
                                Are you sure you want to delete
                                <strong>{{ $assistantToDelete->full_name }}</strong>?
                            </p>
                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                This action cannot be undone. All related data will be permanently deleted.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="delete"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="delete">
                                <i class="bi bi-trash me-2"></i>
                                Delete Assistant
                            </span>
                            <span wire:loading wire:target="delete">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Deleting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <style>
            /* Header Actions */
            .header-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .search-container {
                width: 250px;
            }

            .search-container .input-group {
                height: 38px;
            }

            /* Filter Badge */
            .filter-badge {
                background-color: var(--primary-color);
                color: white;
                font-size: 0.7rem;
                padding: 2px 6px;
                border-radius: 10px;
                margin-left: 5px;
            }

            /* Stats Overview */
            .stats-overview .stat-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                border: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                gap: 15px;
                height: 100%;
            }

            .stats-overview .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                color: white;
            }

            .stats-overview .stat-icon.total {
                background: linear-gradient(135deg, #6c757d, #495057);
            }

            .stats-overview .stat-icon.active {
                background: linear-gradient(135deg, #28a745, #20c997);
            }

            .stats-overview .stat-icon.pending {
                background: linear-gradient(135deg, #ffc107, #fd7e14);
            }

            .stats-overview .stat-icon.suspended {
                background: linear-gradient(135deg, #dc3545, #c82333);
            }

            .stats-overview .stat-content {
                flex: 1;
            }

            .stats-overview .stat-value {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-dark);
                line-height: 1;
            }

            .stats-overview .stat-label {
                font-size: 0.9rem;
                color: var(--text-light);
                margin-top: 5px;
            }

            /* Bulk Actions */
            .bulk-actions-bar {
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Table Styling */
            .table th {
                background-color: #f8f9fa;
                font-weight: 600;
                color: var(--text-dark);
                border-bottom: 2px solid var(--border-color);
                white-space: nowrap;
            }

            .table td {
                vertical-align: middle;
                border-bottom: 1px solid var(--border-color);
            }

            .table tbody tr:hover {
                background-color: rgba(0, 143, 64, 0.03);
            }

            /* Avatar */
            .avatar {
                width: 40px;
                height: 40px;
                flex-shrink: 0;
            }

            .avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .avatar-placeholder {
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 600;
                font-size: 0.9rem;
            }

            /* Contact Info */
            .contact-info a {
                color: var(--text-dark);
                text-decoration: none;
            }

            .contact-info a:hover {
                color: var(--primary-color);
            }

            /* Station Assignments */
            .station-assignments .badge {
                font-size: 0.75rem;
                font-weight: 500;
                padding: 4px 8px;
            }

            /* Status Badges */
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 500;
                white-space: nowrap;
            }

            .status-success {
                background-color: rgba(40, 167, 69, 0.1);
                color: #155724;
                border: 1px solid rgba(40, 167, 69, 0.2);
            }

            .status-secondary {
                background-color: rgba(108, 117, 125, 0.1);
                color: #495057;
                border: 1px solid rgba(108, 117, 125, 0.2);
            }

            .status-danger {
                background-color: rgba(220, 53, 69, 0.1);
                color: #721c24;
                border: 1px solid rgba(220, 53, 69, 0.2);
            }

            .status-warning {
                background-color: rgba(255, 193, 7, 0.1);
                color: #856404;
                border: 1px solid rgba(255, 193, 7, 0.2);
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 5px;
                flex-wrap: nowrap;
            }

            .action-buttons .btn {
                padding: 5px 8px;
                font-size: 0.875rem;
                border-radius: 6px;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 40px 20px;
            }

            .empty-state .display-1 {
                font-size: 4rem;
                color: #dee2e6;
            }

            /* Modal Styling */
            .modal .assistant-info .avatar {
                width: 50px;
                height: 50px;
            }

            .modal .assistant-info .avatar-placeholder {
                font-size: 1.1rem;
            }

            /* Responsive Design */
            @media (max-width: 1200px) {
                .header-actions {
                    width: 100%;
                    margin-top: 15px;
                    justify-content: flex-start;
                }

                .search-container {
                    flex: 1;
                    min-width: 200px;
                }
            }

            @media (max-width: 768px) {
                .header-actions {
                    flex-direction: column;
                    align-items: stretch;
                    gap: 10px;
                }

                .search-container {
                    width: 100%;
                }

                .stats-overview .row {
                    gap: 15px;
                }

                .stats-overview .col-md-3 {
                    flex: 0 0 calc(50% - 7.5px);
                }

                .table-responsive {
                    font-size: 0.9rem;
                }

                .action-buttons {
                    flex-direction: column;
                    gap: 3px;
                }

                .action-buttons .btn {
                    padding: 4px 6px;
                }
            }

            @media (max-width: 576px) {
                .stats-overview .col-md-3 {
                    flex: 0 0 100%;
                }

                .bulk-actions-bar .d-flex {
                    flex-direction: column;
                    gap: 10px;
                    align-items: stretch;
                }

                .bulk-actions-bar .btn {
                    width: 100%;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tooltips for action buttons
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Close modals on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        @if($showDeleteModal)
                        @this.set('showDeleteModal', false);
                        @endif
                        @if($showEmploymentModal)
                        @this.set('showEmploymentModal', false);
                        @endif
                    }
                });

                // Focus search on page load
                const searchInput = document.querySelector('.search-container input');
                if (searchInput) {
                    searchInput.focus();
                }
            });

            // Livewire listeners
            Livewire.on('createAssistant', () => {
                console.log('Create assistant clicked');
                // Navigate to create page or open modal
            });

            Livewire.on('viewAssistant', (assistantId) => {
                console.log('View assistant:', assistantId);
                // Navigate to view page
            });

            Livewire.on('editAssistant', (assistantId) => {
                console.log('Edit assistant:', assistantId);
                // Navigate to edit page
            });

            // Success message handling
            Livewire.on('assistantUpdated', (message) => {
                // Show toast notification
                console.log('Assistant updated:', message);
            });
        </script>
    </div>
</div>