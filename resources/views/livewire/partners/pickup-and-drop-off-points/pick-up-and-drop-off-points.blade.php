<div>
    <div>
        <!-- Card -->
        <div class="dashboard-section">
            <!-- Card Header -->
            <div class="section-header">
                <div>
                    <h3 class="section-title">Pick-up & Drop-off Points</h3>
                    <p class="section-subtitle">Manage all collection and delivery points</p>
                </div>
                <div class="header-actions">
                    <!-- Search Input -->
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                                placeholder="Search points...">
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
                            @if ($status === 'all')
                                <i class="bi bi-funnel me-2"></i>
                                Status
                            @elseif($status === 'active')
                                <i class="bi bi-check-circle me-2"></i>
                                Active
                            @elseif($status === 'inactive')
                                <i class="bi bi-x-circle me-2"></i>
                                Inactive
                            @else
                                <i class="bi bi-tools me-2"></i>
                                Maintenance
                            @endif
                            @if ($status !== 'all')
                                <span class="filter-badge">1</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ $status == 'all' ? 'active' : '' }}"
                                    wire:click="$set('status', 'all')">
                                    <i class="bi bi-funnel me-2"></i>
                                    All Status
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item {{ $status == 'active' ? 'active' : '' }}"
                                    wire:click="$set('status', 'active')">
                                    <i class="bi bi-check-circle me-2 text-success"></i>
                                    Active
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $status == 'inactive' ? 'active' : '' }}"
                                    wire:click="$set('status', 'inactive')">
                                    <i class="bi bi-x-circle me-2 text-danger"></i>
                                    Inactive
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $status == 'maintenance' ? 'active' : '' }}"
                                    wire:click="$set('status', 'maintenance')">
                                    <i class="bi bi-tools me-2 text-warning"></i>
                                    Maintenance
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Type Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            @if ($type === 'all')
                                <i class="bi bi-geo-alt me-2"></i>
                                Type
                            @elseif($type === 'pickup')
                                <i class="bi bi-box-arrow-in-up me-2"></i>
                                Pick-up
                            @elseif($type === 'dropoff')
                                <i class="bi bi-box-arrow-down me-2"></i>
                                Drop-off
                            @else
                                <i class="bi bi-arrows-exchange me-2"></i>
                                Both
                            @endif
                            @if ($type !== 'all')
                                <span class="filter-badge">1</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ $type == 'all' ? 'active' : '' }}"
                                    wire:click="$set('type', 'all')">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    All Types
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item {{ $type == 'pickup' ? 'active' : '' }}"
                                    wire:click="$set('type', 'pickup')">
                                    <i class="bi bi-box-arrow-in-up me-2 text-info"></i>
                                    Pick-up Only
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $type == 'dropoff' ? 'active' : '' }}"
                                    wire:click="$set('type', 'dropoff')">
                                    <i class="bi bi-box-arrow-down me-2 text-warning"></i>
                                    Drop-off Only
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $type == 'both' ? 'active' : '' }}"
                                    wire:click="$set('type', 'both')">
                                    <i class="bi bi-arrows-exchange me-2 text-primary"></i>
                                    Both
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Clear Filters Button (only show when filters are active) -->
                    @if ($activeFilters > 0)
                        <button class="btn btn-outline-danger" wire:click="clearFilters">
                            <i class="bi bi-x-circle me-2"></i>
                            Clear Filters
                        </button>
                    @endif

                    <!-- Add New Point Button -->

                    <a href="{{ route('partners.pd.create') }}" class="btn btn-primary"><i
                            class="bi bi-plus-circle me-2"></i>Add Point</a>

                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('name')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Name</span>
                                    @if ($sortField === 'name')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            
                            <th>Location</th>
                            <th>Contact</th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Status</span>
                                    @if ($sortField === 'status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Created</span>
                                    @if ($sortField === 'created_at')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($points as $point)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="point-icon">
                                            @if ($point->type === 'pickup')
                                                <i class="bi bi-box-arrow-in-up"></i>
                                            @elseif($point->type === 'dropoff')
                                                <i class="bi bi-box-arrow-down"></i>
                                            @else
                                                <i class="bi bi-arrows-exchange"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $point->name }}</div>
                                            <small class="text-muted">{{ $point->operating_hours ?? '24/7' }}</small>
                                        </div>
                                    </div>
                                </td>
                               
                                <td>
                                    <div class="location-info">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt text-primary me-1"></i>
                                            <span>{{ $point->address }}</span>
                                        </div>
                                        <small class="text-muted">{{ $point->city ?? '' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        @if ($point->contact_person)
                                            <div>{{ $point->contact_person }}</div>
                                        @endif
                                        @if ($point->contact_phone)
                                            <small class="text-muted">
                                                <i class="bi bi-telephone me-1"></i>
                                                {{ $point->contact_phone }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($point->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Active
                                        </span>
                                    @elseif($point->status === 'inactive')
                                        <span class="status-badge status-inactive">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Inactive
                                        </span>
                                    @else
                                        <span class="status-badge status-maintenance">
                                            <i class="bi bi-tools me-1"></i>
                                            Maintenance
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="date-info">
                                        <div>{{ $point->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $point->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">

                                        <a href="{{ route('partners.pd.edit', $point->id) }}"
                                            class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                        <a
                                            href="{{ route('partners.pd.view', $point->id) }}"class="btn btn-sm btn-outline-info"><i
                                                class="bi bi-eye"></i></a>
                                        <button class="btn btn-sm btn-outline-danger"
                                            wire:click="confirmDelete({{ $point->id }})" title="Delete">
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
                                            <i class="bi bi-geo-alt display-1 text-muted"></i>
                                            <h4 class="mt-3">No Points Found</h4>
                                            <p class="text-muted">No pick-up or drop-off points match your filters</p>
                                            @if ($activeFilters > 0)
                                                <button class="btn btn-primary mt-2" wire:click="clearFilters">
                                                    Clear Filters
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer (Only Pagination) -->
            <div class="table-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Results Info -->
                    <div class="results-info">
                        <span class="small text-muted">
                            Showing <strong>{{ $points->firstItem() ?? 0 }}-{{ $points->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $points->total() }}</strong> points
                        </span>
                    </div>

                    <!-- Pagination -->
                    <div>
                        {{ $points->links() }}
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
                            <h5 class="modal-title">Confirm Delete</h5>
                            <button type="button" class="btn-close"
                                wire:click="$set('showDeleteModal', false)"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center py-3">
                                <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                                <h4 class="mt-3">Are you sure?</h4>
                                <p class="text-muted">This action cannot be undone. This point will be permanently
                                    deleted.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showDeleteModal', false)">
                                Cancel
                            </button>
                            <button type="button" class="btn btn-danger" wire:click="delete">
                                <i class="bi bi-trash me-2"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <style>
            /* Header Actions Alignment */
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

            .search-container .form-control {
                border-radius: 0 6px 6px 0;
            }

            .search-container .input-group-text {
                background-color: #f8f9fa;
                border-right: none;
            }

            /* Dropdown Styles */
            .dropdown .btn {
                height: 38px;
                display: flex;
                align-items: center;
                white-space: nowrap;
            }

            .dropdown-menu .dropdown-item.active {
                background-color: var(--primary-light);
                color: var(--primary-dark);
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

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 6px;
            }

            .action-buttons .btn {
                padding: 5px 8px;
                font-size: 0.875rem;
                border-radius: 6px;
                transition: all 0.2s ease;
            }

            .action-buttons .btn:hover {
                transform: translateY(-1px);
            }

            /* Table Footer */
            .table-footer {
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid var(--border-color);
            }

            .results-info {
                font-size: 0.9rem;
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

                .section-header {
                    flex-direction: column;
                    align-items: stretch;
                }
            }

            /* Additional Styles */
            .point-icon {
                width: 36px;
                height: 36px;
                background-color: rgba(0, 143, 64, 0.1);
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--primary-color);
                margin-right: 10px;
            }

            .point-icon .bi-box-arrow-in-up {
                color: #17a2b8;
            }

            .point-icon .bi-box-arrow-down {
                color: #ffc107;
            }

            .point-icon .bi-arrows-exchange {
                color: var(--primary-color);
            }

            .location-info {
                line-height: 1.4;
            }

            .contact-info {
                line-height: 1.4;
            }

            .status-active {
                background-color: rgba(40, 167, 69, 0.1);
                color: #155724;
                border: 1px solid rgba(40, 167, 69, 0.2);
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 0.85rem;
                display: inline-flex;
                align-items: center;
            }

            .status-inactive {
                background-color: rgba(108, 117, 125, 0.1);
                color: #495057;
                border: 1px solid rgba(108, 117, 125, 0.2);
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 0.85rem;
                display: inline-flex;
                align-items: center;
            }

            .status-maintenance {
                background-color: rgba(255, 193, 7, 0.1);
                color: #856404;
                border: 1px solid rgba(255, 193, 7, 0.2);
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 0.85rem;
                display: inline-flex;
                align-items: center;
            }

            .empty-state {
                text-align: center;
                padding: 40px 20px;
            }

            .empty-state .display-1 {
                font-size: 4rem;
                color: #dee2e6;
            }

            /* Modal Animation */
            .modal {
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Close modal on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && @this.showDeleteModal) {
                        @this.set('showDeleteModal', false);
                    }
                });

                // Auto-focus search on page load
                const searchInput = document.querySelector('.search-container input');
                if (searchInput) {
                    searchInput.focus();
                }
            });

            // Livewire listeners
            Livewire.on('openCreateModal', () => {
                // Implement create modal logic
                console.log('Open create modal');
            });

            Livewire.on('openEditModal', (data) => {
                // Implement edit modal logic
                console.log('Open edit modal for id:', data.id);
            });

            Livewire.on('openViewModal', (data) => {
                // Implement view modal logic
                console.log('Open view modal for id:', data.id);
            });
        </script>
    </div>
</div>
