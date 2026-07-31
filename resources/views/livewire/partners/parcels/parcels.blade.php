<div>
    <div>
        <div class="parcels-management">
            <!-- Header Section with Gradient -->
            <div class="page-header mb-3">
                <div class="header-content">
                    <div class="header-left">
                        <h2 class="page-title">
                            <i class="bi bi-box-seam me-2"></i>
                            Parcel Management
                        </h2>
                        <p class="page-subtitle mb-0">Manage and track all parcels in one place</p>
                    </div>

                    @if($partnerType == "pickup-dropoff" && $pointsCount > 0 )
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
            <div class="stats-grid mb-3">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Parcels</span>
                        @if(auth()->guard('partner')->user()->user_type == 'driver')
                        <span class="stat-value">{{ $statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->count() }}</span>
                        @elseif(auth()->guard('partner')->user()->user_type == 'pha')
                        <span class="stat-value">{{ $statParcels->count()}}</span>
                        @elseif(auth()->guard('partner')->user()->user_type == 'pickup-dropoff')
                        <span class="stat-value">{{ $statParcels->where('sender_partner_id', auth()->guard('partner')->user()->partner->id)->count() }}</span>
                        @elseif(auth()->guard('partner')->user()->user_type == 'transport')
                        <span class="stat-value">{{ $statParcels->count() }}</span>
                        @endif
                    </div>
                </div>

                @if(auth()->guard('partner')->user()->user_type == 'driver')
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value">{{ $statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'assigned')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'transport')
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value">{{ $statParcels->whereIn('current_status', ['accepted','assigned'])->count() + $statParcels->where('current_status', 'warehouse')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'pha')
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Pending</span>
                        <span class="stat-value">{{ $statParcels->whereIn('current_status', ['created','accepted','assigned'])->count() }}</span>
                    </div>
                </div>
                @else
                <span class="stat-value">{{ $totalParcels }}</span>
                @endif

                @if(auth()->guard('partner')->user()->user_type == 'driver')
                <div class="stat-card transit">
                    <div class="stat-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">In Transit</span>
                        <span class="stat-value">{{ $statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'in_transit')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'transport')
                <div class="stat-card transit">
                    <div class="stat-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">In Transit</span>
                        <span class="stat-value">{{ $statParcels->where('current_status', 'in_transit')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'pha')
                <div class="stat-card transit">
                    <div class="stat-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Received</span>
                        <span class="stat-value">{{ $statParcels->whereIn('current_status', ['warehouse','arrived_at_destination','picked','delivered'])->count() }}</span>
                    </div>
                </div>
                @else
                <span class="stat-value">{{ $totalParcels }}</span>
                @endif

                @if(auth()->guard('partner')->user()->user_type == 'driver')
                <div class="stat-card delivered">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Delivered</span>
                        <span class="stat-value">{{ $statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'arrived_at_destination')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'transport')
                <div class="stat-card delivered">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Delivered</span>
                        <span class="stat-value">{{ $statParcels->where('current_status', 'arrived_at_destination')->count() }}</span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'pha')
                <div class="stat-card delivered">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Returned</span>
                        <span class="stat-value">{{ $statParcels->whereIn('current_status', ['returned', 'failed'])->count() }}</span>
                    </div>
                </div>
                @else
                <span class="stat-value">{{ $totalParcels }}</span>
                @endif

                {{--Earnings_--}}
                @if(auth()->guard('partner')->user()->user_type == 'transport')
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Revenue</span>
                        <span class="stat-value">KES {{ number_format($statParcels
                            ->flatMap->parcelPayouts
                            ->where('status', 'approved')
                            ->sum('amount') ?? 0) }}
                        </span>
                    </div>
                </div>
                @elseif(auth()->guard('partner')->user()->user_type == 'pickup-dropoff')
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Revenue</span>
                        <span class="stat-value">KES {{ number_format($statParcels
                            ->flatMap->parcelPayouts
                            ->where('status', 'approved')
                            ->sum('amount') ?? 0) }}
                        </span>
                    </div>
                </div>
                @else
                <div class="stat-card revenue">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Total Revenue</span>
                        <span class="stat-value">N/A</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Search and Filters Bar -->
            <div class="filters-bar mb-3">
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
                            <i class="bi bi-funnel me-1"></i>
                            <span class="filter-label">Status</span>
                            @if ($statusFilter)
                            <span class="filter-badge">{{ $statusFilter }}</span>
                            @endif
                            <i class="bi bi-chevron-down ms-1"></i>
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
                            <i class="bi bi-credit-card me-1"></i>
                            <span class="filter-label">Payment</span>
                            @if ($paymentStatusFilter)
                            <span class="filter-badge">{{ $paymentStatusFilter }}</span>
                            @endif
                            <i class="bi bi-chevron-down ms-1"></i>
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
                            <i class="bi bi-calendar3 me-1"></i>
                            <span class="filter-label">Date</span>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                        <div class="dropdown-menu p-2" style="min-width: 250px;">
                            <div class="mb-2">
                                <label class="form-label small">From</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="dateFrom">
                            </div>
                            <div class="mb-2">
                                <label class="form-label small">To</label>
                                <input type="date" class="form-control form-control-sm" wire:model.live="dateTo">
                            </div>
                            <button class="btn btn-sm btn-primary w-100" wire:click="applyDateRange">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Advanced Filters Toggle -->
                    <button class="filter-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                        <i class="bi bi-sliders2 me-1"></i>
                        <span class="filter-label">Advanced</span>
                    </button>

                    <!-- Clear Filters -->
                    @if ($this->hasActiveFilters())
                    <button class="filter-btn clear" wire:click="resetFilters">
                        <i class="bi bi-x-circle me-1"></i>
                        <span class="filter-label">Clear</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div class="collapse mb-3" id="advancedFilters">
                <div class="advanced-filters-panel">
                    <div class="advanced-filters-grid">
                        <div class="filter-item">
                            <label class="form-label">Parcel Type</label>
                            <select class="form-select" wire:model.live="parcelTypeFilter">
                                @foreach ($parcelTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="form-label">Customer</label>
                            <select class="form-select" wire:model.live="customerFilter">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name ?? $customer->email }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="form-label">Driver</label>
                            <select class="form-select" wire:model.live="driverFilter">
                                <option value="">All Drivers</option>
                                @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}">{{ $driver->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
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
            <div class="bulk-actions-bar mb-3">
                <div class="bulk-actions-content">
                    <div class="selected-count">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        <span class="fw-medium">{{ count($selectedParcels) }} selected</span>
                    </div>
                    <div class="bulk-buttons">
                        <button class="btn btn-sm btn-success" wire:click="bulkMarkAsDelivered">
                            <i class="bi bi-check-circle me-1"></i>
                            <span class="btn-label">Deliver</span>
                        </button>
                        <button class="btn btn-sm btn-warning" wire:click="bulkCancel">
                            <i class="bi bi-x-circle me-1"></i>
                            <span class="btn-label">Cancel</span>
                        </button>
                        <button class="btn btn-sm btn-danger" wire:click="bulkDelete"
                            wire:confirm="Are you sure you want to delete selected parcels?">
                            <i class="bi bi-trash me-1"></i>
                            <span class="btn-label">Delete</span>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" wire:click="$set('selectedParcels', [])">
                            <i class="bi bi-x me-1"></i>
                            <span class="btn-label">Clear</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Parcels Table - Responsive Container -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="36" class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable parcel-col">
                                    <span>Parcel</span>
                                    @if ($sortField === 'parcel_number')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th class="details-col">Details</th>
                                <th class="location-col">From</th>
                                <th class="location-col">To</th>
                                <th wire:click="sortBy('current_status')" class="sortable status-col">
                                    <span>Status</span>
                                    @if ($sortField === 'current_status')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parcels as $parcel)
                            <tr class="{{ in_array($parcel->id, $selectedParcels) ? 'selected-row' : '' }}">
                                <td class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $parcel->id }}" wire:model="selectedParcels">
                                    </div>
                                </td>
                                <td class="parcel-col">
                                    <div class="parcel-info-cell">
                                        <a href="{{ route('partners.parcels.view', $parcel->id) }}"
                                            class="parcel-number">
                                            {{ $parcel->parcel_id }}
                                        </a>
                                        <div class="parcel-badges">
                                            <span class="badge bg-info">{{ $parcel->package_type}}</span>
                                            <span class="badge bg-warning">{{ $parcel->delivery_flow}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="details-col">
                                    <div class="details-cell">
                                        <div class="content-preview">
                                            {{ Str::limit($parcel->content_description ?? 'No description', 25) }}
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

                                @php
                                $fromLocation = null;
                                $toLocation = null;

                                if($parcel->delivery_flow == 'final_destination') {
                                $fromLocation = $parcel->current_location['pick-up-drop-off-point'] ?? null;
                                $toLocation = $parcel->deliveryStation;
                                } else {
                                if(in_array($parcel->current_status, [
                                'created',
                                'accepted',
                                'assigned',
                                'pending',
                                'picked',
                                'in_transit'
                                ])) {
                                $fromLocation = $parcel->senderPickUpDropOffPoint;
                                $toLocation = $parcel->warehouse;
                                }
                                elseif(in_array($parcel->current_status, [
                                'warehouse',
                                'arrived_at_destination'
                                ])) {
                                $fromLocation = $parcel->warehouse;
                                $toLocation = $parcel->deliveryStation;
                                }
                                elseif(in_array($parcel->current_status, [
                                'delivered',
                                'failed',
                                'returned'
                                ])) {
                                $fromLocation = $parcel->deliveryStation;
                                $toLocation = $parcel->receiverTown;
                                }
                                }
                                @endphp

                                <td class="location-col">
                                    @if($fromLocation)
                                    <strong>{{ $fromLocation?->town?->name ?? $fromLocation?->name }}</strong>
                                    @if(isset($fromLocation->name))
                                    <span class="badge bg-info d-block mt-1">{{ $fromLocation->name }}</span>
                                    @endif
                                    @if(isset($fromLocation->address))
                                    <span class="badge bg-warning d-block mt-1">{{ $fromLocation->address }}</span>
                                    @endif
                                    @endif
                                </td>

                                <td class="location-col">
                                    @if($toLocation)
                                    <strong>{{ $toLocation?->town?->name ?? $toLocation?->name }}</strong>
                                    @if(isset($toLocation->name))
                                    <span class="badge bg-info d-block mt-1">{{ $toLocation->name }}</span>
                                    @endif
                                    @if(isset($toLocation->address))
                                    <span class="badge bg-warning d-block mt-1">{{ $toLocation->address }}</span>
                                    @endif
                                    @endif
                                </td>

                                <td class="status-col">
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
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
            <div class="pagination-wrapper mt-3">
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
                                Assign Driver
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Search Drivers -->
                            <div class="search-wrapper mb-3">
                                <i class="bi bi-search search-icon"></i>
                                <input type="text"
                                    class="form-control search-input"
                                    wire:model.live.debounce.300ms="driverSearch"
                                    placeholder="Search drivers...">
                                @if ($driverSearch)
                                <button class="search-clear" wire:click="$set('driverSearch', '')" type="button">
                                    <i class="bi bi-x"></i>
                                </button>
                                @endif
                            </div>

                            <!-- Drivers List -->
                            <div class="drivers-list-container">
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
                                    </div>
                                    @endforelse
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

        <!-- Optimized CSS -->
        <style>
            /* Root Variables */
            :root {
                --primary: #4361ee;
                --primary-dark: #3730a3;
                --success: #10b981;
                --warning: #f59e0b;
                --danger: #ef4444;
                --info: #3b82f6;
                --border: #e5e7eb;
                --card-radius: 1rem;
                --transition-speed: 0.2s;
            }

            .parcels-management {
                padding: 1rem;
                background: #f3f4f6;
                min-height: 100vh;
            }

            /* Page Header */
            .page-header {
                background: linear-gradient(135deg, var(--primary), var(--primary-dark));
                padding: 1.5rem;
                border-radius: var(--card-radius);
                color: white;
                box-shadow: 0 4px 20px rgba(67, 97, 238, 0.25);
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .page-title {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0;
                display: flex;
                align-items: center;
            }

            .page-subtitle {
                font-size: 0.9rem;
                opacity: 0.9;
                margin: 0;
            }

            .btn-modern {
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.3);
                color: white;
                padding: 0.5rem 1.25rem;
                border-radius: var(--card-radius);
                font-weight: 600;
                backdrop-filter: blur(10px);
                transition: all var(--transition-speed) ease;
                white-space: nowrap;
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
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 0.75rem;
            }

            .stat-card {
                background: white;
                border-radius: var(--card-radius);
                padding: 0.75rem 1rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
                display: flex;
                align-items: center;
                gap: 0.75rem;
                transition: all var(--transition-speed) ease;
                position: relative;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            }

            .stat-icon {
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 0.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                flex-shrink: 0;
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
                min-width: 0;
            }

            .stat-label {
                font-size: 0.7rem;
                color: #6b7280;
                display: block;
                margin-bottom: 0.1rem;
            }

            .stat-value {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1f2937;
                line-height: 1.2;
            }

            /* Filters Bar */
            .filters-bar {
                display: flex;
                gap: 0.75rem;
                flex-wrap: wrap;
            }

            .search-wrapper {
                flex: 1;
                min-width: 200px;
                position: relative;
            }

            .search-input {
                padding-left: 2.25rem;
                padding-right: 2.25rem;
                height: 2.5rem;
                border: 2px solid var(--border);
                border-radius: var(--card-radius);
                font-size: 0.85rem;
                transition: all var(--transition-speed) ease;
                width: 100%;
            }

            .search-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            }

            .search-icon {
                position: absolute;
                left: 0.75rem;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                z-index: 10;
            }

            .search-clear {
                position: absolute;
                right: 0.75rem;
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
                gap: 0.4rem;
                flex-wrap: wrap;
                align-items: center;
            }

            .filter-btn {
                background: white;
                border: 2px solid var(--border);
                border-radius: 2rem;
                padding: 0.25rem 0.75rem;
                font-size: 0.8rem;
                font-weight: 500;
                color: #4b5563;
                display: flex;
                align-items: center;
                gap: 0.25rem;
                transition: all var(--transition-speed) ease;
                white-space: nowrap;
                height: 2.5rem;
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
                padding: 0.05rem 0.4rem;
                font-size: 0.6rem;
            }

            /* Advanced Filters */
            .advanced-filters-panel {
                background: white;
                border-radius: var(--card-radius);
                padding: 1rem;
                border: 2px solid var(--border);
            }

            .advanced-filters-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 0.75rem;
            }

            .filter-item .form-label {
                font-size: 0.75rem;
                margin-bottom: 0.2rem;
            }

            .filter-item .form-select {
                font-size: 0.85rem;
            }

            /* Bulk Actions */
            .bulk-actions-bar {
                background: white;
                border-radius: var(--card-radius);
                padding: 0.5rem 0.75rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
                border-left: 4px solid var(--primary);
            }

            .bulk-actions-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .selected-count {
                display: flex;
                align-items: center;
                font-size: 0.85rem;
            }

            .bulk-buttons {
                display: flex;
                gap: 0.3rem;
                flex-wrap: wrap;
            }

            .bulk-buttons .btn {
                font-size: 0.75rem;
                padding: 0.2rem 0.6rem;
            }

            /* Table */
            .table-container {
                background: white;
                border-radius: var(--card-radius);
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            }

            .table-responsive {
                border-radius: var(--card-radius);
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .parcels-table {
                margin: 0;
                width: 100%;
                min-width: 600px;
            }

            .parcels-table thead th {
                background: #f9fafb;
                padding: 0.5rem 0.6rem;
                font-size: 0.65rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                color: #6b7280;
                border-bottom: 2px solid var(--border);
                white-space: nowrap;
            }

            .parcels-table tbody td {
                padding: 0.5rem 0.6rem;
                vertical-align: middle;
                font-size: 0.8rem;
            }

            .parcels-table tbody tr {
                transition: background var(--transition-speed) ease;
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

            .sortable span {
                display: inline-flex;
                align-items: center;
                gap: 0.3rem;
            }

            /* Column Widths */
            .checkbox-col {
                width: 36px;
                min-width: 36px;
            }

            .parcel-col {
                min-width: 110px;
            }

            .details-col {
                min-width: 130px;
            }

            .location-col {
                min-width: 60px;
                max-width: 140px;
            }

            .status-col {
                min-width: 90px;
            }

            /* Cell Styles */
            .parcel-info-cell {
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
            }

            .parcel-number {
                font-weight: 600;
                color: var(--primary);
                text-decoration: none;
                font-size: 0.85rem;
                word-break: break-all;
            }

            .parcel-number:hover {
                text-decoration: underline;
            }

            .parcel-badges {
                display: flex;
                gap: 0.2rem;
                flex-wrap: wrap;
            }

            .parcel-badges .badge {
                font-size: 0.55rem;
                padding: 0.15rem 0.35rem;
            }

            .details-cell {
                display: flex;
                flex-direction: column;
                gap: 0.2rem;
            }

            .content-preview {
                font-size: 0.8rem;
                color: #1f2937;
                word-break: break-word;
            }

            .badges-group {
                display: flex;
                gap: 0.2rem;
                flex-wrap: wrap;
            }

            .badge-type,
            .badge-handling {
                font-size: 0.55rem;
                font-weight: 500;
                padding: 0.1rem 0.35rem;
                border-radius: 2rem;
                display: inline-flex;
                align-items: center;
                white-space: nowrap;
            }

            .weight-info {
                font-size: 0.7rem;
                color: #6b7280;
                display: flex;
                align-items: center;
                gap: 0.2rem;
            }

            .location-col strong {
                font-size: 0.75rem;
            }

            .location-col .badge {
                font-size: 0.55rem;
                padding: 0.1rem 0.3rem;
            }

            .status-wrapper {
                display: flex;
                flex-direction: column;
                gap: 0.15rem;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.7rem;
                font-weight: 500;
                padding: 0.15rem 0.5rem;
                border-radius: 2rem;
                width: fit-content;
                white-space: nowrap;
            }

            .estimated-date {
                font-size: 0.65rem;
                color: #9ca3af;
                display: flex;
                align-items: center;
                gap: 0.15rem;
            }

            /* Empty State */
            .empty-state {
                text-align: center;
                padding: 2rem 1rem;
            }

            .empty-icon {
                width: 4rem;
                height: 4rem;
                background: #f3f4f6;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 0.75rem;
                font-size: 1.5rem;
                color: #9ca3af;
            }

            .empty-state h4 {
                font-size: 1rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 0.25rem;
            }

            .empty-state p {
                color: #6b7280;
                margin-bottom: 0.75rem;
                font-size: 0.85rem;
            }

            /* Pagination */
            .pagination-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .pagination-info {
                color: #6b7280;
                font-size: 0.8rem;
            }

            .pagination-links .pagination {
                flex-wrap: wrap;
                gap: 0.2rem;
                margin: 0;
            }

            .pagination-links .pagination .page-link {
                font-size: 0.8rem;
                padding: 0.25rem 0.6rem;
            }

            /* Modal */
            .modal-lg {
                max-width: 700px;
            }

            .drivers-list-container {
                border: 1px solid var(--border);
                border-radius: 0.75rem;
                background: white;
            }

            .drivers-list {
                max-height: 300px;
                overflow-y: auto;
                padding: 0.5rem;
            }

            .driver-item {
                display: flex;
                align-items: center;
                padding: 0.6rem;
                border: 1px solid var(--border);
                border-radius: 0.6rem;
                margin-bottom: 0.4rem;
                cursor: pointer;
                transition: all var(--transition-speed) ease;
                gap: 0.6rem;
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
                width: 2.25rem;
                height: 2.25rem;
                background: #e2e8f0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.1rem;
                color: #64748b;
                flex-shrink: 0;
            }

            .driver-info {
                flex: 1;
                min-width: 0;
            }

            .driver-name {
                font-weight: 600;
                color: #0f172a;
                font-size: 0.85rem;
            }

            .driver-details {
                font-size: 0.75rem;
                color: #64748b;
            }

            .driver-select-radio {
                flex-shrink: 0;
            }

            .empty-drivers {
                text-align: center;
                padding: 1.5rem 1rem;
                color: #94a3b8;
            }

            .empty-drivers i {
                font-size: 2rem;
                display: block;
                margin-bottom: 0.5rem;
            }

            /* Responsive Breakpoints */

            /* Tablets */
            @media (max-width: 992px) {
                .parcels-management {
                    padding: 0.75rem;
                }

                .page-header {
                    padding: 1rem;
                }

                .page-title {
                    font-size: 1.25rem;
                }

                .header-content {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .header-actions {
                    width: 100%;
                }

                .header-actions .btn-modern {
                    width: 100%;
                    justify-content: center;
                }

                .stats-grid {
                    grid-template-columns: repeat(3, 1fr);
                    gap: 0.5rem;
                }

                .stat-value {
                    font-size: 1.1rem;
                }

                .stat-card {
                    padding: 0.6rem 0.75rem;
                }

                .stat-icon {
                    width: 2.25rem;
                    height: 2.25rem;
                    font-size: 0.9rem;
                }

                .filters-bar {
                    flex-direction: column;
                }

                .search-wrapper {
                    min-width: unset;
                    width: 100%;
                }

                .filter-group {
                    width: 100%;
                    overflow-x: auto;
                    flex-wrap: nowrap;
                    padding-bottom: 0.25rem;
                    -webkit-overflow-scrolling: touch;
                }

                .filter-group::-webkit-scrollbar {
                    height: 3px;
                }

                .filter-group::-webkit-scrollbar-thumb {
                    background: #d1d5db;
                    border-radius: 3px;
                }

                .filter-btn {
                    flex-shrink: 0;
                    padding: 0.2rem 0.6rem;
                    font-size: 0.75rem;
                }

                .filter-label {
                    display: none;
                }

                .filter-btn .bi-funnel,
                .filter-btn .bi-credit-card,
                .filter-btn .bi-calendar3,
                .filter-btn .bi-sliders2 {
                    margin-right: 0;
                }

                .advanced-filters-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .bulk-actions-content {
                    flex-direction: column;
                    align-items: stretch;
                }

                .selected-count {
                    justify-content: center;
                }

                .bulk-buttons {
                    justify-content: center;
                }

                .btn-label {
                    display: none;
                }

                .pagination-wrapper {
                    flex-direction: column;
                    text-align: center;
                }
            }

            /* Mobile */
            @media (max-width: 768px) {
                .parcels-management {
                    padding: 0.5rem;
                }

                .page-header {
                    padding: 0.75rem;
                    border-radius: 0.75rem;
                }

                .page-title {
                    font-size: 1.1rem;
                }

                .page-subtitle {
                    font-size: 0.75rem;
                }

                .btn-modern {
                    font-size: 0.8rem;
                    padding: 0.4rem 0.75rem;
                }

                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.4rem;
                }

                .stat-card {
                    padding: 0.5rem 0.6rem;
                }

                .stat-icon {
                    width: 1.75rem;
                    height: 1.75rem;
                    font-size: 0.75rem;
                }

                .stat-value {
                    font-size: 0.95rem;
                }

                .stat-label {
                    font-size: 0.6rem;
                }

                .search-input {
                    height: 2.25rem;
                    font-size: 0.8rem;
                    padding-left: 1.75rem;
                    padding-right: 1.75rem;
                }

                .filter-btn {
                    height: 2.25rem;
                    font-size: 0.7rem;
                    padding: 0.15rem 0.5rem;
                }

                .advanced-filters-grid {
                    grid-template-columns: 1fr;
                }

                .parcels-table thead th {
                    font-size: 0.55rem;
                    padding: 0.3rem 0.4rem;
                }

                .parcels-table tbody td {
                    padding: 0.3rem 0.4rem;
                    font-size: 0.7rem;
                }

                .parcel-number {
                    font-size: 0.75rem;
                }

                .content-preview {
                    font-size: 0.7rem;
                }

                .status-badge {
                    font-size: 0.6rem;
                    padding: 0.1rem 0.35rem;
                }

                .location-col strong {
                    font-size: 0.65rem;
                }

                .location-col .badge {
                    font-size: 0.5rem;
                }

                .checkbox-col {
                    width: 30px;
                    min-width: 30px;
                }

                .details-col {
                    min-width: 100px;
                }

                .location-col {
                    min-width: 50px;
                    max-width: 100px;
                }

                .status-col {
                    min-width: 75px;
                }

                .empty-state {
                    padding: 1.5rem 0.75rem;
                }

                .empty-icon {
                    width: 3.5rem;
                    height: 3.5rem;
                    font-size: 1.25rem;
                }

                .empty-state h4 {
                    font-size: 0.9rem;
                }

                .empty-state p {
                    font-size: 0.75rem;
                }

                .pagination-info {
                    font-size: 0.7rem;
                }

                .pagination-links .pagination .page-link {
                    font-size: 0.7rem;
                    padding: 0.2rem 0.5rem;
                }

                .bulk-buttons .btn {
                    font-size: 0.65rem;
                    padding: 0.15rem 0.4rem;
                }

                .selected-count {
                    font-size: 0.75rem;
                }

                /* Modal mobile */
                .modal-dialog {
                    margin: 0.5rem;
                }

                .modal-content {
                    border-radius: 0.75rem;
                }

                .driver-item {
                    padding: 0.4rem;
                    gap: 0.4rem;
                }

                .driver-avatar {
                    width: 1.75rem;
                    height: 1.75rem;
                    font-size: 0.9rem;
                }

                .driver-name {
                    font-size: 0.75rem;
                }

                .driver-details {
                    font-size: 0.65rem;
                }
            }

            /* Small phones */
            @media (max-width: 480px) {
                .stats-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .stat-value {
                    font-size: 0.85rem;
                }

                .stat-icon {
                    width: 1.5rem;
                    height: 1.5rem;
                    font-size: 0.65rem;
                }

                .parcels-table {
                    min-width: 480px;
                }

                .parcels-table thead th {
                    font-size: 0.5rem;
                    padding: 0.2rem 0.3rem;
                }

                .parcels-table tbody td {
                    padding: 0.2rem 0.3rem;
                    font-size: 0.65rem;
                }

                .parcel-number {
                    font-size: 0.65rem;
                }

                .parcel-badges .badge {
                    font-size: 0.45rem;
                    padding: 0.1rem 0.25rem;
                }

                .badge-type,
                .badge-handling {
                    font-size: 0.45rem;
                }

                .content-preview {
                    font-size: 0.6rem;
                }

                .weight-info {
                    font-size: 0.55rem;
                }

                .status-badge {
                    font-size: 0.5rem;
                    padding: 0.1rem 0.25rem;
                }

                .estimated-date {
                    font-size: 0.5rem;
                }

                .pagination-links .pagination .page-link {
                    font-size: 0.6rem;
                    padding: 0.15rem 0.4rem;
                }
            }

            /* Print */
            @media print {
                .parcels-management {
                    background: white;
                    padding: 0.25in;
                }

                .btn-modern,
                .filter-btn,
                .bulk-actions-bar {
                    display: none !important;
                }

                .stat-card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }

                .page-header {
                    background: #333 !important;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        let assignDriverModal = null;

        function initModal() {
            const modalElement = document.getElementById('assignDriverModal');
            if (modalElement && !assignDriverModal) {
                assignDriverModal = new bootstrap.Modal(modalElement);
                modalElement.addEventListener('hidden.bs.modal', () => {
                    Livewire.dispatch('modalClosed');
                });
            }
        }

        initModal();

        Livewire.on('openAssignDriverModal', () => {
            initModal();
            if (assignDriverModal) {
                assignDriverModal.show();
            }
        });

        Livewire.on('closeAssignDriverModal', () => {
            if (assignDriverModal) {
                assignDriverModal.hide();
            }
        });

        Livewire.hook('morphed', () => {
            initModal();
        });
    });
</script>