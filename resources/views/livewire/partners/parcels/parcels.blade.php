<div>
    <div>
        <div class="parcels-management">
            <!-- Header Section with Gradient -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">
                            <i class="bi bi-box-seam me-2"></i>
                            Parcel Management
                        </h2>
                        <p class="page-subtitle mb-0">Manage and track all parcels in one place</p>
                    </div>

                    @if($partnerType == "pickup-dropoff" || $loggedUser->i_am_responsible && $loggedUser->i_am_assistant )
                    <div class="header-actions">
                        <a href="{{ route('partners.parcels.create') }}" class="btn btn-primary btn-modern">
                            <i class="bi bi-plus-circle me-2"></i>
                            New Parcel
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="stats-grid mb-4">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Parcels</span>
                        <span class="stat-value">{{ $totalParcels }}</span>
                    </div>
                    <div class="stat-trend positive">
                        <i class="bi bi-arrow-up"></i>
                        +12%
                    </div>
                </div>

                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value">{{ $pendingParcels }}</span>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: {{ ($pendingParcels / max($totalParcels, 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card transit">
                    <div class="stat-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">In Transit</span>
                        <span class="stat-value">{{ $inTransitParcels }}</span>
                    </div>
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: {{ ($inTransitParcels / max($totalParcels, 1)) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card delivered">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Delivered</span>
                        <span class="stat-value">{{ $deliveredParcels ?? 0 }}</span>
                    </div>
                    <div class="stat-trend positive">
                        <i class="bi bi-check"></i>
                        {{ round(($deliveredParcels ?? 0) / max($totalParcels, 1) * 100) }}% success
                    </div>
                </div>

                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Revenue</span>
                        <span class="stat-value">KES {{ number_format($totalRevenue ?? 0) }}</span>
                    </div>
                    <div class="stat-trend">
                        <i class="bi bi-calendar"></i>
                        This month
                    </div>
                </div>
            </div>

            <!-- Search and Filters Bar -->
            <div class="filters-bar mb-4">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text"
                        class="form-control search-input"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by parcel number, sender, receiver...">
                    @if ($search)
                    <button class="search-clear" wire:click="$set('search', '')">
                        <i class="bi bi-x"></i>
                    </button>
                    @endif
                </div>

                <div class="filter-group">
                    <!-- Status Filter Dropdown -->
                    <div class="filter-dropdown">
                        <button class="filter-btn" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-funnel me-2"></i>
                            Status
                            @if ($statusFilter)
                            <span class="filter-badge">{{ $statusFilter }}</span>
                            @endif
                            <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">Filter by Status</div>
                            <a class="dropdown-item {{ !$statusFilter ? 'active' : '' }}" wire:click="$set('statusFilter', '')">
                                All Status
                            </a>
                            @foreach ($statuses as $value => $label)
                            @if ($value !== '')
                            <a class="dropdown-item {{ $statusFilter == $value ? 'active' : '' }}"
                                wire:click="$set('statusFilter', '{{ $value }}')">
                                <i class="bi {{ $this->getStatusBadge($value)['icon'] }} me-2"></i>
                                {{ $label }}
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Filter Dropdown -->
                    <div class="filter-dropdown">
                        <button class="filter-btn" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-credit-card me-2"></i>
                            Payment
                            @if ($paymentStatusFilter)
                            <span class="filter-badge">{{ $paymentStatusFilter }}</span>
                            @endif
                            <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="dropdown-header">Filter by Payment Status</div>
                            <a class="dropdown-item {{ !$paymentStatusFilter ? 'active' : '' }}" wire:click="$set('paymentStatusFilter', '')">
                                All Payments
                            </a>
                            @foreach ($paymentStatuses as $value => $label)
                            @if ($value !== '')
                            <a class="dropdown-item {{ $paymentStatusFilter == $value ? 'active' : '' }}"
                                wire:click="$set('paymentStatusFilter', '{{ $value }}')">
                                <i class="bi {{ $this->getPaymentStatusBadge($value)['icon'] }} me-2"></i>
                                {{ $label }}
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Date Range Picker -->
                    <div class="filter-dropdown">
                        <button class="filter-btn" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-calendar3 me-2"></i>
                            Date Range
                            <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 300px;">
                            <div class="mb-3">
                                <label class="form-label small">From</label>
                                <input type="date" class="form-control" wire:model.live="dateFrom">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">To</label>
                                <input type="date" class="form-control" wire:model.live="dateTo">
                            </div>
                            <button class="btn btn-sm btn-primary w-100" wire:click="applyDateRange">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Filters Toggle -->
                    <button class="filter-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                        <i class="bi bi-sliders2 me-2"></i>
                        Advanced
                    </button>

                    <!-- Clear Filters -->
                    @if ($this->hasActiveFilters())
                    <button class="filter-btn clear" wire:click="resetFilters">
                        <i class="bi bi-x-circle me-2"></i>
                        Clear Filters
                    </button>
                    @endif
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div class="collapse mb-4" id="advancedFilters">
                <div class="advanced-filters-panel">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Parcel Type</label>
                            <select class="form-select" wire:model.live="parcelTypeFilter">
                                @foreach ($parcelTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Customer</label>
                            <select class="form-select" wire:model.live="customerFilter">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name ?? $customer->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Driver</label>
                            <select class="form-select" wire:model.live="driverFilter">
                                <option value="">All Drivers</option>
                                @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Transport Partner</label>
                            <select class="form-select" wire:model.live="transportPartnerFilter">
                                <option value="">All Partners</option>
                                @foreach ($transportPartners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            @if ($showBulkActions)
            <div class="bulk-actions-bar mb-4">
                <div class="bulk-actions-content">
                    <div class="selected-count">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        <span class="fw-medium">{{ count($selectedParcels) }} parcels selected</span>
                    </div>
                    <div class="bulk-buttons">
                        <button class="btn btn-sm btn-success" wire:click="bulkMarkAsDelivered">
                            <i class="bi bi-check-circle me-1"></i>
                            Mark Delivered
                        </button>
                        <button class="btn btn-sm btn-warning" wire:click="bulkCancel">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel
                        </button>
                        <button class="btn btn-sm btn-danger" wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete selected parcels?">
                            <i class="bi bi-trash me-1"></i>
                            Delete
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" wire:click="$set('selectedParcels', [])">
                            <i class="bi bi-x me-1"></i>
                            Clear
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Parcels Table -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable">
                                    <div class="d-flex align-items-center">
                                        Parcel
                                        @if ($sortField === 'parcel_number')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th>Sender & Receiver</th>
                                <th>Parcel Details</th>
                                <th>Assignment</th>
                                <th wire:click="sortBy('current_status')" class="sortable">
                                    <div class="d-flex align-items-center">
                                        Status
                                        @if ($sortField === 'current_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('payment_status')" class="sortable">
                                    <div class="d-flex align-items-center">
                                        Payment
                                        @if ($sortField === 'payment_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('created_at')" class="sortable">
                                    <div class="d-flex align-items-center">
                                        Created
                                        @if ($sortField === 'created_at')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parcels as $parcel)
                            <tr class="{{ in_array($parcel->id, $selectedParcels) ? 'selected-row' : '' }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $parcel->id }}" wire:model="selectedParcels">
                                    </div>
                                </td>
                                <td>
                                    <div class="parcel-info-cell">
                                        <a href="{{ route('partners.parcels.view', $parcel->id) }}"
                                            class="parcel-number">
                                            {{ $parcel->parcel_id }}
                                        </a>
                                        <div class="parcel-amount">
                                            KES {{ number_format($parcel->total_amount, 2) }}
                                        </div>
                                        @if ($parcel->is_overdue)
                                        <span class="overdue-badge">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Overdue
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-cell">
                                        <div class="contact-row sender">
                                            <i class="bi bi-person-up"></i>
                                            <div>
                                                <span class="contact-name">{{ Str::limit($parcel->sender_name, 20) }}</span>
                                                <a href="tel:{{ $parcel->sender_phone }}" class="contact-phone">
                                                    {{ $parcel->sender_phone }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="contact-row receiver">
                                            <i class="bi bi-person-down"></i>
                                            <div>
                                                <span class="contact-name">{{ Str::limit($parcel->receiver_name, 20) }}</span>
                                                <a href="tel:{{ $parcel->receiver_phone }}" class="contact-phone">
                                                    {{ $parcel->receiver_phone }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="details-cell">
                                        <div class="content-preview">
                                            {{ Str::limit($parcel->content_description ?? 'No description', 30) }}
                                        </div>
                                        <div class="badges-group">
                                            @php
                                            $typeBadge = $this->getParcelTypeBadge($parcel->parcel_type);
                                            @endphp
                                            <span class="badge badge-type" style="background: {{ $typeBadge['color'] }}20; color: {{ $typeBadge['color'] }}">
                                                <i class="bi {{ $typeBadge['icon'] }} me-1"></i>
                                                {{ $typeBadge['text'] }}
                                            </span>
                                            @if($parcel->requiresSpecialHandling())
                                            @php
                                            $handlingBadge = $this->getPackageTypeBadge($parcel->package_type);
                                            @endphp
                                            <span class="badge badge-handling" style="background: {{ $handlingBadge['color'] }}20; color: {{ $handlingBadge['color'] }}">
                                                <i class="bi {{ $handlingBadge['icon'] }} me-1"></i>
                                                {{ $handlingBadge['text'] }}
                                            </span>
                                            @endif
                                        </div>
                                        <div class="weight-info">
                                            <i class="bi bi-weight"></i>
                                            {{ $parcel->weight }} {{ $parcel->weight_unit }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="assignment-cell">
                                        @if ($parcel->transportPartner)
                                        {{ $parcel->transportPartner->company_name }}
                                        <div class="vehicle-info">
                                            <i class="bi bi-truck"></i>
                                            @if ($parcel->currentStatus()->driver_id)
                                            {{ $parcel->currentStatus()->driver->full_name }}
                                            @else
                                            <button class="assign-btn" wire:click="showAssignDriver({{ $parcel->id }})">
                                                <i class="bi bi-person-plus me-1"></i>
                                                Assign Driver
                                            </button>
                                            @endif
                                        </div>
                                        @else
                                        <span class="unassigned">Not assigned</span>
                                        <button class="assign-btn" wire:click="showAssignDriver({{ $parcel->id }})">
                                            <i class="bi bi-person-plus me-1"></i>
                                            Assign Partner
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                    $statusBadge = $this->getStatusBadge($parcel->current_status);
                                    @endphp
                                    <div class="status-wrapper">
                                        <span class="status-badge" style="background: {{ $statusBadge['color'] }}20; color: {{ $statusBadge['color'] }}">
                                            <i class="bi {{ $statusBadge['icon'] }} me-1"></i>
                                            {{ $statusBadge['text'] }}
                                        </span>
                                        @if ($parcel->estimated_delivery_date)
                                        <div class="estimated-date">
                                            <i class="bi bi-calendar"></i>
                                            Est: {{ $parcel->estimated_delivery_date->format('M d') }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                    $paymentBadge = $this->getPaymentStatusBadge($parcel->payment_status);
                                    @endphp
                                    <div class="payment-wrapper">
                                        <span class="payment-badge" style="background: {{ $paymentBadge['color'] }}20; color: {{ $paymentBadge['color'] }}">
                                            <i class="bi {{ $paymentBadge['icon'] }} me-1"></i>
                                            {{ $paymentBadge['text'] }}
                                        </span>
                                        @if ($parcel->paid_at)
                                        <div class="payment-date">
                                            <i class="bi bi-clock"></i>
                                            {{ $parcel->paid_at->format('M d') }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="date-cell">
                                        <div class="date-main">{{ $parcel->created_at->format('M d, Y') }}</div>
                                        <div class="date-time">{{ $parcel->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('partners.parcels.view', $parcel->id) }}"
                                            class="action-btn view" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($parcel->current_status === 'picked_up')
                                        <button class="action-btn success"
                                            wire:click="markAsDelivered({{ $parcel->id }})"
                                            title="Mark as Delivered">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        @elseif($parcel->current_status === 'pending')
                                        <button class="action-btn primary"
                                            wire:click="markAsPickedUp({{ $parcel->id }})"
                                            title="Mark as Picked Up">
                                            <i class="bi bi-box-arrow-in-down"></i>
                                        </button>
                                        @endif

                                        @if (in_array($parcel->current_status, ['pending', 'confirmed', 'processing']))
                                        <button class="action-btn warning"
                                            wire:click="showUpdateStatus({{ $parcel->id }})"
                                            title="Change Status">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                        @endif

                                        @if ($parcel->canBeCancelled())
                                        <button class="action-btn danger"
                                            wire:click="confirmDelete({{ $parcel->id }})"
                                            title="Delete Parcel">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>
                                        <h4>No Parcels Found</h4>
                                        <p>
                                            @if ($this->hasActiveFilters())
                                            No parcels match your current filters. Try adjusting your search criteria.
                                            @else
                                            No parcels have been added yet. Start by creating your first parcel.
                                            @endif
                                        </p>
                                        @if ($this->hasActiveFilters())
                                        <button class="btn btn-primary" wire:click="resetFilters">
                                            <i class="bi bi-x-circle me-2"></i>
                                            Clear Filters
                                        </button>
                                        @else
                                        <a href="{{ route('partners.parcels.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>
                                            Create First Parcel
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper mt-4">
                <div class="pagination-info">
                    Showing {{ $parcels->firstItem() ?? 0 }} to {{ $parcels->lastItem() ?? 0 }} of {{ $parcels->total() }} parcels
                </div>
                <div class="pagination-links">
                    {{ $parcels->links('pagination::bootstrap-5') }}
                </div>
            </div>

            <!-- Assign Driver Modal -->
            <div class="modal fade" id="assignDriverModal" tabindex="-1" aria-labelledby="assignDriverModalLabel" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignDriverModalLabel">
                                <i class="bi bi-person-plus me-2"></i>
                                Assign Driver to Parcel
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if($selectedParcelForDriver)
                            <!-- Parcel Information Card -->
                            <div class="parcel-info mb-4">
                                <div class="parcel-detail-card">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <span class="detail-label">Parcel Number:</span>
                                                <span class="detail-value fw-bold text-primary">{{ $selectedParcelForDriver->parcel_number }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">From:</span>
                                                <span class="detail-value">{{ $selectedParcelForDriver->sender_address }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <span class="detail-label">To:</span>
                                                <span class="detail-value">{{ $selectedParcelForDriver->receiver_address }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <span class="detail-label">Amount:</span>
                                                <span class="detail-value text-success">KES {{ number_format($selectedParcelForDriver->total_amount, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Search Drivers -->
                            <div class="search-wrapper mb-3">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text"
                                    class="form-control search-input"
                                    wire:model.live.debounce.300ms="driverSearch"
                                    placeholder="Search drivers by name, phone...">
                                @if ($driverSearch)
                                <button class="search-clear" wire:click="$set('driverSearch', '')" type="button">
                                    <i class="bi bi-x"></i>
                                </button>
                                @endif
                            </div>

                            <!-- Drivers List -->
                            <div class="drivers-list-container mb-3">
                                <h6 class="mb-3">Available Drivers</h6>
                                <div class="drivers-list">
                                    @forelse($availableDrivers as $driver)
                                    <div class="driver-item {{ $selectedDriverId == $driver->id ? 'selected' : '' }}"
                                        wire:click="selectDriver({{ $driver->id }})"
                                        wire:key="driver-{{ $driver->id }}">
                                        <div class="driver-avatar">
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                        <div class="driver-info">
                                            <div class="driver-name">{{ $driver->full_name }}</div>
                                            <div class="driver-details">
                                                <span><i class="bi bi-telephone"></i> {{ $driver->phone }}</span>
                                            </div>
                                            <div class="driver-status mt-1">
                                                <span class="badge {{ $driver->is_available ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $driver->is_available ? 'Available' : 'Busy' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="driver-select-radio">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="driverRadio" 
                                                       value="{{ $driver->id }}"
                                                       {{ $selectedDriverId == $driver->id ? 'checked' : '' }}
                                                       wire:click="selectDriver({{ $driver->id }})">
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="empty-drivers">
                                        <i class="bi bi-person-x"></i>
                                        <p>No available drivers found</p>
                                        @if($driverSearch)
                                        <p class="small text-muted">Try adjusting your search criteria</p>
                                        @endif
                                    </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Assignment Options -->
                            <div class="assignment-options mt-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="bi bi-gear me-2"></i>
                                            Assignment Options
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   wire:model="notifyDriver" 
                                                   id="notifyDriver">
                                            <label class="form-check-label" for="notifyDriver">
                                                <i class="bi bi-bell me-1"></i>
                                                Send notification to driver
                                            </label>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-medium">
                                                <i class="bi bi-calendar me-1"></i>
                                                Estimated Delivery Date
                                            </label>
                                            <input type="date" class="form-control" 
                                                   wire:model="estimatedDeliveryDate"
                                                   min="{{ now()->format('Y-m-d') }}">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-medium">
                                                <i class="bi bi-chat-text me-1"></i>
                                                Assignment Notes
                                            </label>
                                            <textarea class="form-control" rows="2"
                                                wire:model="assignmentNotes"
                                                placeholder="Add any special instructions for the driver..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bi bi-x me-2"></i>
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" 
                                    wire:click="assignDriver" 
                                    wire:loading.attr="disabled"
                                    {{ !$selectedDriverId ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="assignDriver">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Assign Driver
                                </span>
                                <span wire:loading wire:target="assignDriver">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Assigning...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            :root {
                --primary: #4361ee;
                --primary-dark: #3730a3;
                --success: #10b981;
                --warning: #f59e0b;
                --danger: #ef4444;
                --info: #3b82f6;
                --dark: #1f2937;
                --light: #f9fafb;
                --border: #e5e7eb;
            }

            .parcels-management {
                padding: 1.5rem;
                background: #f3f4f6;
                min-height: 100vh;
            }

            /* Page Header */
            .page-header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                padding: 2rem;
                border-radius: 1.5rem;
                color: white;
                box-shadow: 0 10px 30px rgba(67, 97, 238, 0.2);
            }

            .page-title {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
                display: flex;
                align-items: center;
            }

            .page-subtitle {
                font-size: 1rem;
                opacity: 0.9;
            }

            .btn-modern {
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.3);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 1rem;
                font-weight: 600;
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .btn-modern:hover {
                background: white;
                color: var(--primary);
                border-color: white;
                transform: translateY(-2px);
            }

            /* Stats Grid */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 1.5rem;
            }

            .stat-card {
                background: white;
                border-radius: 1.25rem;
                padding: 1.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .stat-icon {
                width: 3.5rem;
                height: 3.5rem;
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-right: 1rem;
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary);
            }

            .stat-card.total .stat-icon {
                background: rgba(67, 97, 238, 0.1);
                color: var(--primary);
            }

            .stat-card.pending .stat-icon {
                background: rgba(245, 158, 11, 0.1);
                color: var(--warning);
            }

            .stat-card.transit .stat-icon {
                background: rgba(59, 130, 246, 0.1);
                color: var(--info);
            }

            .stat-card.delivered .stat-icon {
                background: rgba(16, 185, 129, 0.1);
                color: var(--success);
            }

            .stat-card.revenue .stat-icon {
                background: rgba(139, 92, 246, 0.1);
                color: #8b5cf6;
            }

            .stat-content {
                flex: 1;
            }

            .stat-label {
                font-size: 0.875rem;
                color: #6b7280;
                display: block;
                margin-bottom: 0.25rem;
            }

            .stat-value {
                font-size: 1.8rem;
                font-weight: 700;
                color: #1f2937;
                line-height: 1.2;
            }

            .stat-trend {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
                border-radius: 2rem;
                background: #f3f4f6;
                color: #6b7280;
                position: absolute;
                top: 1rem;
                right: 1rem;
            }

            .stat-trend.positive {
                background: rgba(16, 185, 129, 0.1);
                color: var(--success);
            }

            .stat-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
            }

            .stat-progress .progress {
                height: 4px;
                border-radius: 0;
                background: #e5e7eb;
            }

            /* Filters Bar */
            .filters-bar {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .search-wrapper {
                flex: 1;
                min-width: 300px;
                position: relative;
            }

            .search-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                z-index: 10;
            }

            .search-input {
                padding-left: 2.75rem;
                padding-right: 2.75rem;
                height: 3rem;
                border: 2px solid #e5e7eb;
                border-radius: 1rem;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .search-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            }

            .search-clear {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #9ca3af;
                cursor: pointer;
                padding: 0.25rem;
                z-index: 10;
            }

            .filter-group {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .filter-btn {
                background: white;
                border: 2px solid #e5e7eb;
                border-radius: 2rem;
                padding: 0.5rem 1.25rem;
                font-size: 0.9rem;
                font-weight: 500;
                color: #4b5563;
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .filter-btn:hover {
                border-color: var(--primary);
                color: var(--primary);
            }

            .filter-btn.clear {
                background: #fee2e2;
                border-color: #fecaca;
                color: var(--danger);
            }

            .filter-badge {
                background: var(--primary);
                color: white;
                border-radius: 1rem;
                padding: 0.1rem 0.5rem;
                font-size: 0.75rem;
                margin-left: 0.5rem;
            }

            /* Advanced Filters Panel */
            .advanced-filters-panel {
                background: white;
                border-radius: 1.25rem;
                padding: 1.5rem;
                border: 2px solid #e5e7eb;
            }

            /* Bulk Actions */
            .bulk-actions-bar {
                background: white;
                border-radius: 1rem;
                padding: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                border-left: 4px solid var(--primary);
            }

            .bulk-actions-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .selected-count {
                display: flex;
                align-items: center;
                font-size: 0.95rem;
            }

            .bulk-buttons {
                display: flex;
                gap: 0.5rem;
            }

            /* Table */
            .table-container {
                background: white;
                border-radius: 1.5rem;
                overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .parcels-table {
                margin: 0;
            }

            .parcels-table thead th {
                background: #f9fafb;
                padding: 1rem 1rem;
                font-size: 0.85rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #6b7280;
                border-bottom: 2px solid #e5e7eb;
            }

            .parcels-table tbody tr {
                transition: all 0.3s ease;
            }

            .parcels-table tbody tr:hover {
                background: #f9fafb;
            }

            .parcels-table tbody tr.selected-row {
                background: rgba(67, 97, 238, 0.05);
            }

            .sortable {
                cursor: pointer;
                user-select: none;
            }

            .sortable:hover {
                color: var(--primary);
            }

            /* Cell Styles */
            .parcel-info-cell {
                display: flex;
                flex-direction: column;
            }

            .parcel-number {
                font-weight: 600;
                color: var(--primary);
                text-decoration: none;
                font-size: 0.95rem;
            }

            .parcel-number:hover {
                text-decoration: underline;
            }

            .parcel-amount {
                font-size: 0.85rem;
                color: #10b981;
                font-weight: 500;
                margin-top: 0.25rem;
            }

            .overdue-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.7rem;
                padding: 0.2rem 0.5rem;
                background: #fee2e2;
                color: var(--danger);
                border-radius: 2rem;
                margin-top: 0.25rem;
            }

            .contact-cell {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .contact-row {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .contact-row i {
                color: #9ca3af;
                font-size: 0.9rem;
                width: 1.2rem;
            }

            .contact-name {
                font-size: 0.9rem;
                font-weight: 500;
                color: #1f2937;
                display: block;
            }

            .contact-phone {
                font-size: 0.8rem;
                color: #6b7280;
                text-decoration: none;
            }

            .contact-phone:hover {
                color: var(--primary);
            }

            .details-cell {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .content-preview {
                font-size: 0.9rem;
                color: #1f2937;
            }

            .badges-group {
                display: flex;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .badge-type,
            .badge-handling {
                font-size: 0.7rem;
                font-weight: 500;
                padding: 0.25rem 0.5rem;
                border-radius: 2rem;
                display: inline-flex;
                align-items: center;
            }

            .weight-info {
                font-size: 0.8rem;
                color: #6b7280;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .assignment-cell {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .driver-info,
            .vehicle-info {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.85rem;
                color: #4b5563;
            }

            .unassigned {
                font-size: 0.85rem;
                color: #9ca3af;
                font-style: italic;
            }

            .assign-btn {
                background: none;
                border: 1px solid #e5e7eb;
                border-radius: 2rem;
                padding: 0.25rem 0.75rem;
                font-size: 0.75rem;
                color: var(--primary);
                cursor: pointer;
                transition: all 0.3s ease;
                width: fit-content;
            }

            .assign-btn:hover {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            .status-wrapper,
            .payment-wrapper {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .status-badge,
            .payment-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.8rem;
                font-weight: 500;
                padding: 0.35rem 0.75rem;
                border-radius: 2rem;
                width: fit-content;
            }

            .estimated-date,
            .payment-date {
                font-size: 0.75rem;
                color: #9ca3af;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .date-cell {
                display: flex;
                flex-direction: column;
            }

            .date-main {
                font-size: 0.9rem;
                font-weight: 500;
                color: #1f2937;
            }

            .date-time {
                font-size: 0.75rem;
                color: #9ca3af;
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 0.25rem;
                justify-content: flex-end;
            }

            .action-btn {
                width: 2rem;
                height: 2rem;
                border-radius: 0.5rem;
                border: none;
                background: white;
                color: #6b7280;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                border: 1px solid #e5e7eb;
            }

            .action-btn:hover {
                background: #f3f4f6;
                transform: translateY(-2px);
            }

            .action-btn.view:hover {
                background: var(--info);
                color: white;
                border-color: var(--info);
            }

            .action-btn.success:hover {
                background: var(--success);
                color: white;
                border-color: var(--success);
            }

            .action-btn.primary:hover {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            .action-btn.warning:hover {
                background: var(--warning);
                color: white;
                border-color: var(--warning);
            }

            .action-btn.danger:hover {
                background: var(--danger);
                color: white;
                border-color: var(--danger);
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 4rem 2rem;
            }

            .empty-icon {
                width: 6rem;
                height: 6rem;
                background: #f3f4f6;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
                font-size: 2.5rem;
                color: #9ca3af;
            }

            .empty-state h4 {
                font-size: 1.25rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 0.5rem;
            }

            .empty-state p {
                color: #6b7280;
                margin-bottom: 1.5rem;
            }

            /* Pagination */
            .pagination-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .pagination-info {
                color: #6b7280;
                font-size: 0.9rem;
            }

            /* Modal specific styles */
            .modal-lg {
                max-width: 800px;
            }

            .parcel-detail-card {
                background: #f8fafc;
                border-radius: 0.75rem;
                padding: 1.25rem;
                border: 1px solid #e2e8f0;
            }

            .detail-item {
                display: flex;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
            }

            .detail-item:last-child {
                margin-bottom: 0;
            }

            .detail-label {
                width: 120px;
                color: #64748b;
                font-weight: 500;
            }

            .detail-value {
                flex: 1;
                color: #0f172a;
                font-weight: 500;
            }

            .drivers-list-container {
                border: 1px solid var(--border);
                border-radius: 0.75rem;
                background: white;
            }

            .drivers-list {
                max-height: 350px;
                overflow-y: auto;
                padding: 0.5rem;
            }

            .driver-item {
                display: flex;
                align-items: center;
                padding: 1rem;
                border: 1px solid var(--border);
                border-radius: 0.75rem;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .driver-item:last-child {
                margin-bottom: 0;
            }

            .driver-item:hover {
                background: #f8fafc;
                border-color: var(--primary);
            }

            .driver-item.selected {
                background: #eef2ff;
                border-color: var(--primary);
                box-shadow: 0 2px 8px rgba(67, 97, 238, 0.1);
            }

            .driver-avatar {
                width: 3rem;
                height: 3rem;
                background: #e2e8f0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
                font-size: 1.5rem;
                color: #64748b;
                overflow: hidden;
            }

            .driver-avatar img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .driver-info {
                flex: 1;
            }

            .driver-name {
                font-weight: 600;
                color: #0f172a;
                margin-bottom: 0.25rem;
            }

            .driver-details {
                font-size: 0.85rem;
                color: #64748b;
                margin-bottom: 0.25rem;
            }

            .driver-details i {
                margin-right: 0.25rem;
            }

            .driver-select-radio {
                margin-left: 1rem;
            }

            .empty-drivers {
                text-align: center;
                padding: 3rem 1rem;
                color: #94a3b8;
            }

            .empty-drivers i {
                font-size: 3rem;
                margin-bottom: 1rem;
                display: block;
            }

            .empty-drivers p {
                margin-bottom: 0;
            }

            /* Loading States */
            .btn:disabled {
                opacity: 0.6;
                cursor: not-allowed;
            }

            .spinner-border-sm {
                width: 1rem;
                height: 1rem;
                border-width: 0.15em;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .parcels-management {
                    padding: 1rem;
                }

                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

                .filters-bar {
                    flex-direction: column;
                }

                .search-wrapper {
                    width: 100%;
                }

                .filter-group {
                    width: 100%;
                    justify-content: space-between;
                }

                .pagination-wrapper {
                    flex-direction: column;
                    text-align: center;
                }

                .action-buttons {
                    flex-wrap: wrap;
                }
            }
        </style>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        let assignDriverModal = null;

        // Initialize modal when component is loaded
        function initModal() {
            const modalElement = document.getElementById('assignDriverModal');
            if (modalElement && !assignDriverModal) {
                assignDriverModal = new bootstrap.Modal(modalElement);
                
                // Handle modal hidden event
                modalElement.addEventListener('hidden.bs.modal', () => {
                    Livewire.dispatch('modalClosed');
                });
            }
        }

        // Try to initialize immediately
        initModal();

        // Listen for open modal event
        Livewire.on('openAssignDriverModal', () => {
            initModal(); // Re-initialize if needed
            if (assignDriverModal) {
                assignDriverModal.show();
            } else {
                console.error('Modal element not found');
            }
        });

        // Listen for close modal event
        Livewire.on('closeAssignDriverModal', () => {
            if (assignDriverModal) {
                assignDriverModal.hide();
            }
        });

        // Re-initialize on livewire update
        Livewire.hook('morphed', () => {
            initModal();
        });
    });
</script><script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS not loaded!');
    }
});
</script>

