<div>
    <div class="dashboard-section">
        <!-- Header Section -->
        <div class="section-header">
            <div>
                <h3 class="section-title">
                    <i class="bi bi-person-badge me-2"></i>
                    Driver Management
                </h3>
                <p class="section-subtitle">Manage all drivers and their assignments</p>
            </div>
            <div class="header-actions">
                <!-- Search -->
                <div class="search-container">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                            placeholder="Search drivers...">
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
                        @elseif($statusFilter == 'on_leave')
                        <i class="bi bi-umbrella me-2 text-warning"></i>
                        On Leave
                        @elseif($statusFilter == 'terminated')
                        <i class="bi bi-person-x me-2 text-dark"></i>
                        Terminated
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
                            <a class="dropdown-item {{ $statusFilter == 'on_leave' ? 'active' : '' }}"
                                wire:click="$set('statusFilter', 'on_leave')">
                                <i class="bi bi-umbrella me-2 text-warning"></i>
                                On Leave
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $statusFilter == 'terminated' ? 'active' : '' }}"
                                wire:click="$set('statusFilter', 'terminated')">
                                <i class="bi bi-person-x me-2 text-dark"></i>
                                Terminated
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Availability Filter -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        @if ($availabilityFilter === 'available')
                        <i class="bi bi-check-circle me-2 text-success"></i>
                        Available
                        @elseif($availabilityFilter === 'unavailable')
                        <i class="bi bi-x-circle me-2 text-secondary"></i>
                        Unavailable
                        @else
                        <i class="bi bi-gear me-2"></i>
                        Availability
                        @endif
                        @if ($availabilityFilter)
                        <span class="filter-badge">1</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ !$availabilityFilter ? 'active' : '' }}"
                                wire:click="$set('availabilityFilter', '')">
                                <i class="bi bi-gear me-2"></i>
                                All Availability
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item {{ $availabilityFilter === 'available' ? 'active' : '' }}"
                                wire:click="$set('availabilityFilter', 'available')">
                                <i class="bi bi-check-circle me-2 text-success"></i>
                                Available
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $availabilityFilter === 'unavailable' ? 'active' : '' }}"
                                wire:click="$set('availabilityFilter', 'unavailable')">
                                <i class="bi bi-x-circle me-2 text-secondary"></i>
                                Unavailable
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- License Status Filter -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        @if ($licenseStatusFilter == 'valid')
                        <i class="bi bi-check-circle me-2 text-success"></i>
                        Valid License
                        @elseif($licenseStatusFilter == 'expiring')
                        <i class="bi bi-clock me-2 text-warning"></i>
                        Expiring Soon
                        @elseif($licenseStatusFilter == 'expired')
                        <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                        Expired
                        @else
                        <i class="bi bi-card-checklist me-2"></i>
                        License
                        @endif
                        @if ($licenseStatusFilter)
                        <span class="filter-badge">1</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ !$licenseStatusFilter ? 'active' : '' }}"
                                wire:click="$set('licenseStatusFilter', '')">
                                <i class="bi bi-card-checklist me-2"></i>
                                All License Status
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item {{ $licenseStatusFilter == 'valid' ? 'active' : '' }}"
                                wire:click="$set('licenseStatusFilter', 'valid')">
                                <i class="bi bi-check-circle me-2 text-success"></i>
                                Valid
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $licenseStatusFilter == 'expiring' ? 'active' : '' }}"
                                wire:click="$set('licenseStatusFilter', 'expiring')">
                                <i class="bi bi-clock me-2 text-warning"></i>
                                Expiring Soon
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ $licenseStatusFilter == 'expired' ? 'active' : '' }}"
                                wire:click="$set('licenseStatusFilter', 'expired')">
                                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>
                                Expired
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Add New Driver -->
                <a href="{{ route('partners.drivers.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i>
                    Add Driver
                </a>
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
                            <div class="stat-value">{{ $totalDrivers }}</div>
                            <div class="stat-label">Total Drivers</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon active">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $activeDrivers }}</div>
                            <div class="stat-label">Active</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon available">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $availableDrivers }}</div>
                            <div class="stat-label">Available</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $expiringLicenses }}</div>
                            <div class="stat-label">License Expiring</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <span>Driver</span>
                                @if ($sortField === 'first_name')
                                <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </div>
                        </th>
                        <th>Contact & ID</th>
                        <th wire:click="sortBy('driving_license_number')" style="cursor: pointer;">
                            <div class="d-flex align-items-center">
                                <span>License</span>
                                @if ($sortField === 'driving_license_number')
                                <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </div>
                        </th>
                        <th>Availability</th>
                        <th>Deliveries</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar">
                                    @if ($driver->user && $driver->user->avatar)
                                    <img src="{{ $driver->user->avatar }}"
                                        alt="{{ $driver->full_name }}" class="rounded-circle">
                                    @else
                                    <div class="avatar-placeholder bg-primary">
                                        {{ substr($driver->first_name, 0, 1) }}{{ substr($driver->last_name, 0, 1) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <div class="fw-semibold">{{ $driver->full_name }}</div>
                                    <small class="text-muted">
                                        ID: {{ $driver->id }}
                                        @if($driver->age)
                                        • {{ $driver->age }} yrs
                                        @endif
                                    </small>
                                    <div class="mt-1">
                                        @php
                                        $badgeColor = match($driver->status) {
                                        'active' => 'success',
                                        'inactive' => 'secondary',
                                        'suspended' => 'danger',
                                        'on_leave' => 'warning',
                                        'terminated' => 'dark',
                                        default => 'secondary'
                                        };
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }} badge-sm">
                                            {{ ucfirst($driver->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div class="mb-1">
                                    <i class="bi bi-envelope me-1 text-muted"></i>
                                    <a href="mailto:{{ $driver->email }}" class="text-decoration-none small">
                                        {{ $driver->email }}
                                    </a>
                                </div>
                                <div class="mb-1">
                                    <i class="bi bi-telephone me-1 text-muted"></i>
                                    <a href="tel:{{ $driver->phone_number }}" class="text-decoration-none small">
                                        {{ $driver->phone_number }}
                                    </a>
                                </div>
                                <div>
                                    <i class="bi bi-person-badge me-1 text-muted"></i>
                                    <span class="small">{{ $driver->id_number }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="license-info">
                                <div class="fw-medium small">{{ $driver->driving_license_number }}</div>
                                <div class="small">
                                    <span class="badge bg-light text-dark">{{ $driver->license_class }}</span>
                                </div>
                                @php
                                $now = now();
                                $expiry = $driver->driving_license_expiry_date;
                                $licenseColor = 'success';
                                $licenseText = 'Valid';
                                $licenseIcon = 'bi-check-circle';

                                if ($expiry) {
                                if ($expiry->isPast()) {
                                $licenseColor = 'danger';
                                $licenseText = 'Expired';
                                $licenseIcon = 'bi-exclamation-triangle';
                                } elseif ($expiry->diffInDays($now) <= 30) {
                                    $licenseColor='warning' ;
                                    $licenseText='Expiring Soon' ;
                                    $licenseIcon='bi-clock' ;
                                    }
                                    }
                                    @endphp
                                    <span class="badge bg-{{ $licenseColor }} mt-1">
                                    <i class="bi {{ $licenseIcon }} me-1"></i>
                                    {{ $licenseText }}
                                    </span>
                                    @if($expiry)
                                    <div class="small text-muted mt-1">
                                        Exp: {{ $expiry->format('M d, Y') }}
                                    </div>
                                    @endif
                            </div>
                        </td>

                        <td>
                            <div class="availability-info">
                                @if($driver->is_available)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Available
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Unavailable
                                </span>
                                @endif
                                <div class="mt-1">
                                    <button class="btn btn-sm btn-outline-{{ $driver->is_available ? 'secondary' : 'success' }} btn-xs"
                                        wire:click="toggleAvailability({{ $driver->id }})"
                                        title="{{ $driver->is_available ? 'Mark Unavailable' : 'Mark Available' }}">
                                        <i class="bi bi-{{ $driver->is_available ? 'x' : 'check' }}"></i>
                                    </button>
                                </div>
                            </div>
                        </td>

                        <td>
                            0
                        </td>


                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('partners.drivers.edit', $driver->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('partners.drivers.view', $driver->id) }}"
                                    class="btn btn-sm btn-outline-info" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <!-- Status Actions -->
                                @if ($driver->status === 'active')
                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="confirmStatusUpdate({{ $driver->id }}, 'suspended')"
                                    title="Suspend">
                                    <i class="bi bi-ban"></i>
                                </button>
                                @elseif(in_array($driver->status, ['inactive', 'suspended', 'on_leave']))
                                <button class="btn btn-sm btn-outline-success"
                                    wire:click="confirmStatusUpdate({{ $driver->id }}, 'active')"
                                    title="Activate">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                                @endif

                                <!-- Delete Action -->
                                <button class="btn btn-sm btn-outline-dark"
                                    wire:click="confirmDelete({{ $driver->id }})"
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
                                    <i class="bi bi-person-badge display-1 text-muted"></i>
                                    <h4 class="mt-3">No Drivers Found</h4>
                                    <p class="text-muted">
                                        @if ($hasFilters)
                                        No drivers match your filters
                                        @else
                                        No drivers have been added yet
                                        @endif
                                    </p>
                                    @if ($hasFilters)
                                    <button class="btn btn-primary mt-2" wire:click="resetFilters">
                                        Clear Filters
                                    </button>
                                    @else
                                    <a href="{{ route('partners.drivers.create') }}" class="btn btn-primary">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Add First Driver
                                    </a>
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
                        <strong>{{ $drivers->firstItem() ?? 0 }}-{{ $drivers->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $drivers->total() }}</strong> drivers
                    </span>
                </div>
                <div>
                    {{ $drivers->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close"
                        wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-3">
                        <i class="bi bi-trash text-danger display-4"></i>
                        <h4 class="mt-3">Delete Driver?</h4>
                        <p class="text-muted">
                            Are you sure you want to delete
                            <strong>{{ $driverToDeleteName }}</strong>?
                        </p>
                        <div class="alert alert-warning mt-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This action cannot be undone. All related employments and data will be removed.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="cancelDelete">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="deleteDriver"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="deleteDriver">
                            <i class="bi bi-trash me-2"></i>
                            Delete Driver
                        </span>
                        <span wire:loading wire:target="deleteDriver">
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

        .stats-overview .stat-icon.available {
            background: linear-gradient(135deg, #17a2b8, #0dcaf0);
        }

        .stats-overview .stat-icon.warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
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
            border-radius: 50%;
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

        .badge-sm {
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        /* Contact Info */
        .contact-info a {
            color: var(--text-dark);
            text-decoration: none;
        }

        .contact-info a:hover {
            color: var(--primary-color);
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
        .modal .driver-info .avatar {
            width: 50px;
            height: 50px;
        }

        .modal .driver-info .avatar-placeholder {
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
                    @this.call('cancelDelete');
                    @endif
                }
            });

            // Focus search on page load
            const searchInput = document.querySelector('.search-container input');
            if (searchInput) {
                searchInput.focus();
            }
        });

        // Listen for Livewire events
        document.addEventListener('livewire:init', () => {
            Livewire.on('showDeleteModal', () => {
                // Handle modal show if needed
            });

            Livewire.on('hideDeleteModal', () => {
                // Handle modal hide if needed
            });

            Livewire.on('showToast', (message) => {
                // Show toast notification
                showToast(message);
            });
        });

        function showToast(message, type = 'success') {
            // Implement toast notification
            alert(message); // Temporary - replace with actual toast implementation
        }
    </script>
</div>