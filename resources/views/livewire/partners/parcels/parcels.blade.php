<div>
    <div>
        <div class="parcels-management">
            <!-- Header Section with Gradient -->
            <div class="page-header mb-4">
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
            <div class="stats-grid mb-4">
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: {{ ($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'assigned')->count() / max($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: {{ ($statParcels->where('current_status', 'accepted')->count() + $statParcels->where('current_status', 'warehouse')->count() / max($statParcels->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: {{ ($statParcels->whereIn('current_status', ['created','accepted','assigned'])->count() / max($statParcels->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: {{ ($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'in_transit')->count() / max($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: {{ ($statParcels->where('current_status', 'in_transit')->count() / max($statParcels->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-progress">
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: {{ ($statParcels->whereIn('current_status', ['warehouse','arrived_at_destination','picked','delivered'])->count() / max($statParcels->count(), 1)) * 100 }}%"></div>
                        </div>
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
                    <div class="stat-trend positive">
                        <i class="bi bi-check"></i>
                        {{ round(($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->where('current_status', 'arrived_at_destination')->count()  ?? 0) / max($statParcels->where('driver_id', auth()->guard('partner')->user()->driver->id)->count(), 1) * 100) }}% success
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
                    <div class="stat-trend positive">
                        <i class="bi bi-check"></i>
                        {{ round(($statParcels->where('current_status', 'arrived_at_destination')->count()  ?? 0) / max($statParcels->count(), 1) * 100) }}% success
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
                    <div class="stat-trend positive">
                        <i class="bi bi-check"></i>
                        {{ round(($statParcels->whereIn('current_status', ['returned', 'failed'])->count()  ?? 0) / max($statParcels->count(), 1) * 100) }}% success
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
                    <div class="stat-trend">
                        <i class="bi bi-calendar"></i>
                        This month
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
                    <div class="stat-trend">
                        <i class="bi bi-calendar"></i>
                        This month
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
                            <span class="filter-label">Status</span>
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
                            <span class="filter-label">Payment</span>
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
                            <span class="filter-label">Date</span>
                            <i class="bi bi-chevron-down ms-2"></i>
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 280px;">
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
                        <span class="filter-label">Advanced</span>
                    </button>

                    <!-- Clear Filters -->
                    @if ($this->hasActiveFilters())
                    <button class="filter-btn clear" wire:click="resetFilters">
                        <i class="bi bi-x-circle me-2"></i>
                        <span class="filter-label">Clear</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div class="collapse mb-4" id="advancedFilters">
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
            <div class="bulk-actions-bar mb-4">
                <div class="bulk-actions-content">
                    <div class="selected-count">
                        <i class="bi bi-check-circle-fill text-primary me-2"></i>
                        <span class="fw-medium">{{ count($selectedParcels) }} parcels selected</span>
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

            <!-- Parcels Table -->
            @if(auth()->guard('partner')->user()->user_type == 'driver')
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="40" class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable parcel-col">
                                    <div class="d-flex align-items-center">
                                        Parcel
                                        @if ($sortField === 'parcel_number')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="details-col">Parcel Details</th>
                                <th class="location-col">From</th>
                                <th class="location-col">To</th>
                                <th wire:click="sortBy('current_status')" class="sortable status-col">
                                    <div class="d-flex align-items-center">
                                        Status
                                        @if ($sortField === 'current_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
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
            <div class="pagination-wrapper mt-4">
                <div class="pagination-info">
                    Showing {{ $parcels->firstItem() ?? 0 }} to {{ $parcels->lastItem() ?? 0 }} of {{ $parcels->total() }} parcels
                </div>
                <div class="pagination-links">
                    {{ $parcels->links('pagination::bootstrap-5') }}
                </div>
            </div>

            @elseif(auth()->guard('partner')->user()->user_type == 'transport')
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="40" class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable parcel-col">
                                    <div class="d-flex align-items-center">
                                        Parcel
                                        @if ($sortField === 'parcel_number')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="details-col">Parcel Details</th>
                                <th class="location-col">From</th>
                                <th class="location-col">To</th>
                                <th wire:click="sortBy('current_status')" class="sortable status-col">
                                    <div class="d-flex align-items-center">
                                        Status
                                        @if ($sortField === 'current_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="assignment-col">Assignment</th>
                                <th class="actions-col text-end">Actions</th>
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
                                    </div>
                                </td>

                                <td class="assignment-col">
                                    @php
                                    $assinged = $parcel->statuses->where('status', 'assigned')->first()
                                    @endphp
                                    <div class="assignment-cell">
                                        @if ($assinged)
                                        {{ $assinged->driver?->full_name }}
                                        <div class="vehicle-info">
                                            {{ $assinged->driver?->phone_number }}
                                        </div>
                                        @else
                                        <span class="unassigned">Not assigned</span>
                                        <button class="assign-btn" wire:click="showAssignDriver({{ $parcel->id }})">
                                            <i class="bi bi-person-plus me-1"></i>
                                            Assign Driver
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="actions-col text-end">
                                    <div class="action-buttons">
                                        <a href="{{ route('partners.parcels.view', $parcel->id) }}"
                                            class="action-btn view" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
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

            @elseif(auth()->guard('partner')->user()->user_type == 'pha')
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="40" class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable parcel-col">
                                    <div class="d-flex align-items-center">
                                        Parcel
                                        @if ($sortField === 'parcel_number')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="contact-col">Sender & Receiver</th>
                                <th class="location-col">From</th>
                                <th class="location-col">To</th>
                                <th wire:click="sortBy('payment_status')" class="sortable payment-col">
                                    <div class="d-flex align-items-center">
                                        Payment
                                        @if ($sortField === 'payment_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('current_status')" class="sortable status-col">
                                    <div class="d-flex align-items-center">
                                        Status
                                        @if ($sortField === 'current_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="actions-col text-end">Actions</th>
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
                                <td class="contact-col">
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

                                <td class="payment-col">
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

                                <td class="actions-col text-end">
                                    <div class="action-buttons">
                                        <a href="{{ route('partners.parcels.view', $parcel->id) }}"
                                            class="action-btn view" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if (!$parcel->payment_status =='paid')
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
                                <td colspan="8">
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

            @else
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th width="40" class="checkbox-col">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                    </div>
                                </th>
                                <th wire:click="sortBy('parcel_number')" class="sortable parcel-col">
                                    <div class="d-flex align-items-center">
                                        Parcel
                                        @if ($sortField === 'parcel_number')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="contact-col">Sender & Receiver</th>
                                <th class="details-col">Parcel Details</th>
                                <th class="assignment-col">Assignment</th>
                                <th wire:click="sortBy('current_status')" class="sortable status-col">
                                    <div class="d-flex align-items-center">
                                        Status
                                        @if ($sortField === 'current_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('payment_status')" class="sortable payment-col">
                                    <div class="d-flex align-items-center">
                                        Payment
                                        @if ($sortField === 'payment_status')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th wire:click="sortBy('created_at')" class="sortable date-col">
                                    <div class="d-flex align-items-center">
                                        Created
                                        @if ($sortField === 'created_at')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                        @endif
                                    </div>
                                </th>
                                <th class="actions-col text-end">Actions</th>
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
                                <td class="contact-col">
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
                                <td class="details-col">
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
                                <td class="assignment-col">
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
                                        @endif
                                    </div>
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
                                <td class="payment-col">
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
                                <td class="date-col">
                                    <div class="date-cell">
                                        <div class="date-main">{{ $parcel->created_at->format('M d, Y') }}</div>
                                        <div class="date-time">{{ $parcel->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td class="actions-col text-end">
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
            @endif

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

        <!-- Enhanced Responsive CSS -->
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
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 1rem;
            }

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
                white-space: nowrap;
            }

            .btn-modern:hover {
                background: white;
                color: var(--primary);
                border-color: white;
                transform: translateY(-2px);
            }

            /* Stats Grid - Responsive */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .stat-card {
                background: white;
                border-radius: 1.25rem;
                padding: 1.25rem;
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
                width: 3rem;
                height: 3rem;
                border-radius: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
                margin-right: 0.75rem;
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
                font-size: 0.75rem;
                color: #6b7280;
                display: block;
                margin-bottom: 0.25rem;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1f2937;
                line-height: 1.2;
            }

            .stat-trend {
                font-size: 0.7rem;
                padding: 0.15rem 0.4rem;
                border-radius: 2rem;
                background: #f3f4f6;
                color: #6b7280;
                position: absolute;
                top: 0.75rem;
                right: 0.75rem;
                white-space: nowrap;
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
                height: 3px;
                border-radius: 0;
                background: #e5e7eb;
            }

            /* Filters Bar - Responsive */
            .filters-bar {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .search-wrapper {
                flex: 1;
                min-width: 250px;
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
                height: 2.75rem;
                border: 2px solid #e5e7eb;
                border-radius: 1rem;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                width: 100%;
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
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
                font-weight: 500;
                color: #4b5563;
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
                white-space: nowrap;
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
                padding: 0.05rem 0.5rem;
                font-size: 0.7rem;
                margin-left: 0.25rem;
            }

            .filter-label {
                display: inline;
            }

            /* Advanced Filters - Responsive */
            .advanced-filters-panel {
                background: white;
                border-radius: 1.25rem;
                padding: 1.25rem;
                border: 2px solid #e5e7eb;
            }

            .advanced-filters-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1rem;
            }

            .filter-item {
                min-width: 0;
            }

            .filter-item .form-select {
                width: 100%;
            }

            /* Bulk Actions - Responsive */
            .bulk-actions-bar {
                background: white;
                border-radius: 1rem;
                padding: 0.75rem 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                border-left: 4px solid var(--primary);
            }

            .bulk-actions-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .selected-count {
                display: flex;
                align-items: center;
                font-size: 0.9rem;
            }

            .bulk-buttons {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .bulk-buttons .btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.75rem;
            }

            .btn-label {
                display: inline;
            }

            /* Table - Responsive */
            .table-container {
                background: white;
                border-radius: 1.5rem;
                overflow: hidden;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }

            .parcels-table {
                margin: 0;
                width: 100%;
            }

            .parcels-table thead th {
                background: #f9fafb;
                padding: 0.75rem 0.75rem;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #6b7280;
                border-bottom: 2px solid #e5e7eb;
                white-space: nowrap;
            }

            .parcels-table tbody td {
                padding: 0.75rem;
                vertical-align: middle;
                font-size: 0.85rem;
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

            /* Column Classes for Responsive */
            .checkbox-col {
                width: 40px;
            }

            .parcel-col {
                min-width: 120px;
            }

            .details-col {
                min-width: 150px;
            }

            .location-col {
                min-width: 40px;
            }

            .status-col {
                min-width: 100px;
            }

            .contact-col {
                min-width: 160px;
            }

            .payment-col {
                min-width: 100px;
            }

            .assignment-col {
                min-width: 120px;
            }

            .date-col {
                min-width: 90px;
            }

            .actions-col {
                min-width: 80px;
            }

            /* Cell Styles */
            .parcel-info-cell {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .parcel-number {
                font-weight: 600;
                color: var(--primary);
                text-decoration: none;
                font-size: 0.9rem;
                word-break: break-all;
            }

            .parcel-number:hover {
                text-decoration: underline;
            }

            .parcel-badges {
                display: flex;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .parcel-badges .badge {
                font-size: 0.6rem;
                padding: 0.2rem 0.4rem;
            }

            .parcel-amount {
                font-size: 0.85rem;
                color: #10b981;
                font-weight: 500;
            }

            .overdue-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.65rem;
                padding: 0.1rem 0.4rem;
                background: #fee2e2;
                color: var(--danger);
                border-radius: 2rem;
                width: fit-content;
            }

            .contact-cell {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
            }

            .contact-row {
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .contact-row i {
                color: #9ca3af;
                font-size: 0.8rem;
                width: 1rem;
                flex-shrink: 0;
            }

            .contact-name {
                font-size: 0.85rem;
                font-weight: 500;
                color: #1f2937;
                display: block;
            }

            .contact-phone {
                font-size: 0.75rem;
                color: #6b7280;
                text-decoration: none;
            }

            .contact-phone:hover {
                color: var(--primary);
            }

            .details-cell {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
            }

            .content-preview {
                font-size: 0.85rem;
                color: #1f2937;
                word-break: break-word;
            }

            .badges-group {
                display: flex;
                gap: 0.25rem;
                flex-wrap: wrap;
            }

            .badge-type,
            .badge-handling {
                font-size: 0.6rem;
                font-weight: 500;
                padding: 0.15rem 0.4rem;
                border-radius: 2rem;
                display: inline-flex;
                align-items: center;
                white-space: nowrap;
            }

            .weight-info {
                font-size: 0.75rem;
                color: #6b7280;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .assignment-cell {
                display: flex;
                flex-direction: column;
                gap: 0.35rem;
            }

            .vehicle-info {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.8rem;
                color: #4b5563;
                flex-wrap: wrap;
            }

            .unassigned {
                font-size: 0.8rem;
                color: #9ca3af;
                font-style: italic;
            }

            .assign-btn {
                background: none;
                border: 1px solid #e5e7eb;
                border-radius: 2rem;
                padding: 0.15rem 0.6rem;
                font-size: 0.7rem;
                color: var(--primary);
                cursor: pointer;
                transition: all 0.3s ease;
                width: fit-content;
                white-space: nowrap;
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
                gap: 0.2rem;
            }

            .status-badge,
            .payment-badge {
                display: inline-flex;
                align-items: center;
                font-size: 0.75rem;
                font-weight: 500;
                padding: 0.2rem 0.6rem;
                border-radius: 2rem;
                width: fit-content;
                white-space: nowrap;
            }

            .estimated-date,
            .payment-date {
                font-size: 0.7rem;
                color: #9ca3af;
                display: flex;
                align-items: center;
                gap: 0.2rem;
            }

            .date-cell {
                display: flex;
                flex-direction: column;
            }

            .date-main {
                font-size: 0.85rem;
                font-weight: 500;
                color: #1f2937;
            }

            .date-time {
                font-size: 0.7rem;
                color: #9ca3af;
            }

            /* Action Buttons */
            .action-buttons {
                display: flex;
                gap: 0.25rem;
                justify-content: flex-end;
                flex-wrap: wrap;
            }

            .action-btn {
                width: 1.8rem;
                height: 1.8rem;
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
                font-size: 0.8rem;
            }

            .action-btn:hover {
                background: #f3f4f6;
                transform: translateY(-1px);
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
                padding: 3rem 1.5rem;
            }

            .empty-icon {
                width: 5rem;
                height: 5rem;
                background: #f3f4f6;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 2rem;
                color: #9ca3af;
            }

            .empty-state h4 {
                font-size: 1.1rem;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 0.5rem;
            }

            .empty-state p {
                color: #6b7280;
                margin-bottom: 1rem;
                font-size: 0.9rem;
            }

            /* Pagination - Responsive */
            .pagination-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .pagination-info {
                color: #6b7280;
                font-size: 0.85rem;
            }

            .pagination-links {
                display: flex;
                flex-wrap: wrap;
            }

            .pagination-links .pagination {
                flex-wrap: wrap;
                gap: 0.25rem;
            }

            /* Modal styles */
            .modal-lg {
                max-width: 800px;
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
                padding: 0.75rem;
                border: 1px solid var(--border);
                border-radius: 0.75rem;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: all 0.2s ease;
                gap: 0.75rem;
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
                width: 2.5rem;
                height: 2.5rem;
                background: #e2e8f0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
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
                margin-bottom: 0.15rem;
                font-size: 0.9rem;
            }

            .driver-details {
                font-size: 0.8rem;
                color: #64748b;
            }

            .driver-details i {
                margin-right: 0.2rem;
            }

            .driver-select-radio {
                margin-left: 0.5rem;
                flex-shrink: 0;
            }

            .empty-drivers {
                text-align: center;
                padding: 2rem 1rem;
                color: #94a3b8;
            }

            .empty-drivers i {
                font-size: 2.5rem;
                margin-bottom: 0.75rem;
                display: block;
            }

            .empty-drivers p {
                margin-bottom: 0;
                font-size: 0.9rem;
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

            /* Responsive Breakpoints */

            /* Tablets and small laptops */
            @media (max-width: 1200px) {
                .stats-grid {
                    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                }

                .parcels-table thead th {
                    font-size: 0.7rem;
                    padding: 0.5rem 0.5rem;
                }

                .parcels-table tbody td {
                    padding: 0.5rem;
                    font-size: 0.8rem;
                }

                .location-col,
                .contact-col {
                    min-width: 40px;
                }
            }

            /* Tablets */
            @media (max-width: 992px) {
                .parcels-management {
                    padding: 1rem;
                }

                .page-header {
                    padding: 1.5rem;
                }

                .page-title {
                    font-size: 1.5rem;
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
                }

                .stat-value {
                    font-size: 1.25rem;
                }

                .stat-card {
                    padding: 1rem;
                }

                .stat-icon {
                    width: 2.5rem;
                    height: 2.5rem;
                    font-size: 1rem;
                }

                .filters-bar {
                    flex-direction: column;
                }

                .search-wrapper {
                    width: 100%;
                    min-width: unset;
                }

                .filter-group {
                    width: 100%;
                    justify-content: flex-start;
                    overflow-x: auto;
                    flex-wrap: nowrap;
                    padding-bottom: 0.5rem;
                    -webkit-overflow-scrolling: touch;
                }

                .filter-group::-webkit-scrollbar {
                    height: 4px;
                }

                .filter-group::-webkit-scrollbar-thumb {
                    background: #d1d5db;
                    border-radius: 4px;
                }

                .filter-btn {
                    flex-shrink: 0;
                    font-size: 0.8rem;
                    padding: 0.35rem 0.75rem;
                }

                .filter-label {
                    display: none;
                }

                .filter-btn .bi-funnel,
                .filter-btn .bi-credit-card,
                .filter-btn .bi-calendar3,
                .filter-btn .bi-sliders2 {
                    margin-right: 0.25rem;
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

                .bulk-buttons .btn {
                    font-size: 0.75rem;
                    padding: 0.2rem 0.6rem;
                }

                .btn-label {
                    display: none;
                }

                /* Hide less important columns on tablets */
                .date-col {
                    display: none;
                }

                .parcels-table thead th.date-col {
                    display: none;
                }

                .parcels-table tbody td.date-col {
                    display: none;
                }

                .pagination-wrapper {
                    flex-direction: column;
                    text-align: center;
                }
            }

            /* Mobile devices */
            @media (max-width: 768px) {
                .parcels-management {
                    padding: 0.75rem;
                }

                .page-header {
                    padding: 1rem;
                    border-radius: 1rem;
                }

                .page-title {
                    font-size: 1.25rem;
                }

                .page-title i {
                    font-size: 1.1rem;
                }

                .page-subtitle {
                    font-size: 0.85rem;
                }

                .btn-modern {
                    padding: 0.5rem 1rem;
                    font-size: 0.85rem;
                }

                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                    gap: 0.75rem;
                }

                .stat-card {
                    padding: 0.75rem;
                    border-radius: 1rem;
                }

                .stat-icon {
                    width: 2.25rem;
                    height: 2.25rem;
                    font-size: 0.9rem;
                    margin-right: 0.5rem;
                }

                .stat-value {
                    font-size: 1.1rem;
                }

                .stat-label {
                    font-size: 0.65rem;
                }

                .stat-trend {
                    font-size: 0.6rem;
                    padding: 0.1rem 0.3rem;
                    top: 0.5rem;
                    right: 0.5rem;
                }

                .advanced-filters-grid {
                    grid-template-columns: 1fr;
                }

                .filter-item .form-label {
                    font-size: 0.8rem;
                }

                .bulk-actions-bar {
                    padding: 0.5rem 0.75rem;
                }

                .bulk-buttons .btn {
                    font-size: 0.7rem;
                    padding: 0.15rem 0.5rem;
                }

                /* Table mobile optimizations */
                .table-responsive {
                    border-radius: 1rem;
                }

                .parcels-table thead th {
                    font-size: 0.6rem;
                    padding: 0.4rem 0.4rem;
                }

                .parcels-table tbody td {
                    padding: 0.4rem;
                    font-size: 0.75rem;
                }

                .parcel-number {
                    font-size: 0.8rem;
                }

                .content-preview {
                    font-size: 0.75rem;
                }

                .contact-name {
                    font-size: 0.75rem;
                }

                .contact-phone {
                    font-size: 0.65rem;
                }

                .status-badge,
                .payment-badge {
                    font-size: 0.65rem;
                    padding: 0.15rem 0.4rem;
                }

                .action-btn {
                    width: 1.5rem;
                    height: 1.5rem;
                    font-size: 0.7rem;
                }

                /* Hide more columns on mobile */
                .contact-col {
                    min-width: 120px;
                }

                .details-col {
                    min-width: 120px;
                }

                .assignment-col {
                    min-width: 100px;
                }

                .location-col {
                    min-width: 4cqh;
                }

                .payment-col {
                    min-width: 80px;
                }

                .status-col {
                    min-width: 80px;
                }

                .parcels-table .location-col .badge {
                    font-size: 0.5rem;
                    padding: 0.1rem 0.3rem;
                }

                .parcels-table .location-col strong {
                    font-size: 0.7rem;
                }

                .empty-state {
                    padding: 2rem 1rem;
                }

                .empty-icon {
                    width: 4rem;
                    height: 4rem;
                    font-size: 1.5rem;
                }

                .empty-state h4 {
                    font-size: 1rem;
                }

                .empty-state p {
                    font-size: 0.8rem;
                }

                .pagination-info {
                    font-size: 0.75rem;
                }

                .pagination-links .pagination .page-link {
                    font-size: 0.75rem;
                    padding: 0.3rem 0.6rem;
                }

                /* Modal mobile */
                .modal-dialog {
                    margin: 0.5rem;
                }

                .modal-content {
                    border-radius: 1rem;
                }

                .driver-item {
                    padding: 0.5rem;
                    gap: 0.5rem;
                }

                .driver-avatar {
                    width: 2rem;
                    height: 2rem;
                    font-size: 1rem;
                }

                .driver-name {
                    font-size: 0.8rem;
                }

                .driver-details {
                    font-size: 0.7rem;
                }
            }

            /* Small phones */
            @media (max-width: 480px) {
                .parcels-management {
                    padding: 0.5rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 0.5rem;
                }

                .stat-card {
                    padding: 0.6rem;
                }

                .stat-icon {
                    width: 2rem;
                    height: 2rem;
                    font-size: 0.8rem;
                    margin-right: 0.35rem;
                }

                .stat-value {
                    font-size: 0.95rem;
                }

                .stat-label {
                    font-size: 0.55rem;
                }

                .stat-trend {
                    font-size: 0.5rem;
                    padding: 0.05rem 0.25rem;
                    top: 0.3rem;
                    right: 0.3rem;
                }

                .page-title {
                    font-size: 1rem;
                }

                .page-subtitle {
                    font-size: 0.7rem;
                }

                .btn-modern {
                    font-size: 0.75rem;
                    padding: 0.4rem 0.75rem;
                }

                .filter-btn {
                    font-size: 0.7rem;
                    padding: 0.25rem 0.5rem;
                }

                .search-input {
                    height: 2.25rem;
                    font-size: 0.8rem;
                    padding-left: 2rem;
                    padding-right: 2rem;
                }

                .search-icon {
                    left: 0.75rem;
                    font-size: 0.8rem;
                }

                .parcels-table thead th {
                    font-size: 0.5rem;
                    padding: 0.3rem 0.3rem;
                }

                .parcels-table tbody td {
                    padding: 0.3rem;
                    font-size: 0.65rem;
                }

                .parcel-number {
                    font-size: 0.7rem;
                }

                .checkbox-col {
                    width: 30px;
                }

                .action-btn {
                    width: 1.3rem;
                    height: 1.3rem;
                    font-size: 0.6rem;
                }

                .status-badge,
                .payment-badge {
                    font-size: 0.55rem;
                    padding: 0.1rem 0.3rem;
                }

                .content-preview {
                    font-size: 0.65rem;
                }

                .contact-name {
                    font-size: 0.65rem;
                }

                .contact-phone {
                    font-size: 0.55rem;
                }

                .weight-info {
                    font-size: 0.6rem;
                }

                .badge-type,
                .badge-handling {
                    font-size: 0.5rem;
                    padding: 0.1rem 0.3rem;
                }

                .assign-btn {
                    font-size: 0.6rem;
                    padding: 0.1rem 0.4rem;
                }

                .pagination-info {
                    font-size: 0.65rem;
                }

                .pagination-links .pagination .page-link {
                    font-size: 0.65rem;
                    padding: 0.2rem 0.5rem;
                }

                .bulk-buttons .btn {
                    font-size: 0.6rem;
                    padding: 0.1rem 0.4rem;
                }

                .selected-count {
                    font-size: 0.75rem;
                }
            }

            /* Landscape phones */
            @media (max-height: 600px) and (orientation: landscape) {
                .stats-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

                .stat-card {
                    padding: 0.5rem;
                }

                .stat-icon {
                    width: 1.75rem;
                    height: 1.75rem;
                    font-size: 0.7rem;
                }

                .stat-value {
                    font-size: 0.9rem;
                }

                .stat-label {
                    font-size: 0.5rem;
                }

                .page-header {
                    padding: 0.75rem;
                }

                .page-title {
                    font-size: 1.1rem;
                }
            }

            /* Print styles */
            @media print {
                .parcels-management {
                    background: white;
                    padding: 0.5in;
                }

                .btn-modern,
                .filter-btn,
                .action-btn,
                .assign-btn,
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