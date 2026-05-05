<div>
    <div>
        <div class="dashboard-section">
            <!-- Header Section -->
            <div class="section-header">
                <div>
                    <h3 class="section-title">
                        <i class="bi bi-box-seam me-2"></i>
                        Delivery Marketplace
                    </h3>
                    <p class="section-subtitle">Browse and accept available deliveries</p>
                </div>
                <div class="header-actions">
                    <!-- Search -->
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                                placeholder="Search by tracking #, sender, receiver...">
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
                            @if ($status)
                            <i class="bi bi-check-circle me-2 text-success"></i>
                            {{ ucfirst($status) }}
                            @else
                            <i class="bi bi-funnel me-2"></i>
                            Status
                            @endif
                            @if ($status)
                            <span class="filter-badge">1</span>
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ !$status ? 'active' : '' }}"
                                    wire:click="$set('status', '')">
                                    <i class="bi bi-funnel me-2"></i>
                                    All Status
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item {{ $status == 'pending' ? 'active' : '' }}"
                                    wire:click="$set('status', 'pending')">
                                    <i class="bi bi-clock me-2 text-warning"></i>
                                    Pending
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $status == 'accepted' ? 'active' : '' }}"
                                    wire:click="$set('status', 'accepted')">
                                    <i class="bi bi-check-circle me-2 text-info"></i>
                                    Accepted
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Parcel Type Filter -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            @if ($parcel_type)
                            <i class="bi bi-box me-2"></i>
                            {{ ucfirst($parcel_type) }}
                            @else
                            <i class="bi bi-box me-2"></i>
                            Parcel Type
                            @endif
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ !$parcel_type ? 'active' : '' }}"
                                    wire:click="$set('parcel_type', '')">
                                    <i class="bi bi-box me-2"></i>
                                    All Types
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @foreach($parcelTypes as $key => $type)
                            <li>
                                <a class="dropdown-item {{ $parcel_type == $key ? 'active' : '' }}"
                                    wire:click="$set('parcel_type', '{{ $key }}')">
                                    <i class="bi bi-box me-2"></i>
                                    {{ $type }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Sort By -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-sort-down me-2"></i>
                            Sort: {{ ucfirst(str_replace('_', ' ', $sort)) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ $sort == 'created_at' ? 'active' : '' }}"
                                    wire:click="$set('sort', 'created_at')">
                                    <i class="bi bi-calendar me-2"></i>
                                    Date Created
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sort == 'weight' ? 'active' : '' }}"
                                    wire:click="$set('sort', 'weight')">
                                    <i class="bi bi-weight-scale me-2"></i>
                                    Weight
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sort == 'declared_value' ? 'active' : '' }}"
                                    wire:click="$set('sort', 'declared_value')">
                                    <i class="bi bi-cash-stack me-2"></i>
                                    Value
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ $sort == 'estimated_cost' ? 'active' : '' }}"
                                    wire:click="$set('sort', 'estimated_cost')">
                                    <i class="bi bi-currency-dollar me-2"></i>
                                    Payout
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" wire:click="$set('direction', '{{ $direction == 'asc' ? 'desc' : 'asc' }}')">
                                    <i class="bi bi-arrow-{{ $direction == 'asc' ? 'up' : 'down' }} me-2"></i>
                                    {{ $direction == 'asc' ? 'Ascending' : 'Descending' }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Filter Toggle -->
                    <button class="btn btn-outline-secondary" wire:click="$toggle('showFilters')">
                        <i class="bi bi-filters me-2"></i>
                        Filters
                        @if($sender_county || $receiver_county || $weight_min || $weight_max || $size_filter || $priority_only || $cod_only || $high_value_only)
                        <span class="filter-badge">!</span>
                        @endif
                    </button>

                    <!-- Refresh Button -->
                    <button class="btn btn-primary" wire:click="refreshMarketplace">
                        <i class="bi bi-arrow-clockwise me-2"></i>
                        Refresh
                    </button>
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            @if($showFilters)
            <div class="advanced-filters mb-4">
                <div class="filter-panel">
                    <div class="row g-3">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">
                                <i class="bi bi-geo-alt me-1"></i> From County
                            </label>
                            <select class="form-select" wire:model.live="sender_county" style="font-size: 0.85rem;">
                                <option value="">All Counties</option>
                                @foreach($counties as $county)
                                <option value="{{ $county->id }}">{{ $county->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">
                                <i class="bi bi-geo-alt me-1"></i> To County
                            </label>
                            <select class="form-select" wire:model.live="receiver_county" style="font-size: 0.85rem;">
                                <option value="">All Counties</option>
                                @foreach($counties as $county)
                                <option value="{{ $county->id }}">{{ $county->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">
                                <i class="bi bi-weight-scale me-1"></i> Weight Range (kg)
                            </label>
                            <div class="d-flex gap-2">
                                <input type="number" class="form-control" wire:model.live="weight_min"
                                    placeholder="Min" style="font-size: 0.85rem;">
                                <input type="number" class="form-control" wire:model.live="weight_max"
                                    placeholder="Max" style="font-size: 0.85rem;">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6">
                            <label class="form-label fw-semibold" style="font-size: 0.85rem;">
                                <i class="bi bi-arrows-expand me-1"></i> Package Size
                            </label>
                            <select class="form-select" wire:model.live="size_filter" style="font-size: 0.85rem;">
                                <option value="">All Sizes</option>
                                <option value="small">Small (0-5kg)</option>
                                <option value="medium">Medium (5-15kg)</option>
                                <option value="large">Large (15kg+)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-3 flex-wrap">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="priorityOnly"
                                        wire:model.live="priority_only">
                                    <label class="form-check-label" for="priorityOnly">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        Priority Only
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="codOnly"
                                        wire:model.live="cod_only">
                                    <label class="form-check-label" for="codOnly">
                                        <i class="bi bi-cash-coin text-info me-1"></i>
                                        Cash on Delivery
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="highValueOnly"
                                        wire:model.live="high_value_only">
                                    <label class="form-check-label" for="highValueOnly">
                                        <i class="bi bi-gem text-danger me-1"></i>
                                        High Value (>KES 20,000)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-primary" wire:click="resetFilters">
                                    <i class="bi bi-arrow-repeat me-1"></i> Reset All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Stats Overview -->
            <div class="stats-overview mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon total">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $parcels->total() }}</div>
                                <div class="stat-label">Available Deliveries</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon active">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $statistics['active_bids'] ?? 0 }}</div>
                                <div class="stat-label">Active Bids</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon available">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $statistics['won_bids'] ?? 0 }}</div>
                                <div class="stat-label">Won Bids</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon warning">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">KES {{ number_format($statistics['total_earnings'] ?? 0) }}</div>
                                <div class="stat-label">Total Earnings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alert Banner -->
            @if($parcels->total() > 0)
            <div class="alert-banner alert alert-info mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <i class="bi bi-lightning-charge me-2"></i>
                        <strong>{{ $parcels->total() }} new deliveries available!</strong>
                        <span class="ms-2">Browse and accept deliveries that match your fleet.</span>
                    </div>
                    <button class="btn btn-sm btn-light mt-2 mt-sm-0" wire:click="refreshMarketplace">
                        <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                    </button>
                </div>
            </div>
            @endif

            <!-- Deliveries Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('parcel_id')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Tracking #</span>
                                    @if ($sortField === 'parcel_id')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Pick Up Point</th>
                            <th>Drop Off Point</th>
                            <th>Parcel Details</th>
                            <th wire:click="sortBy('weight')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Weight</span>
                                    @if ($sortField === 'weight')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('declared_value')" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <span>Value</span>
                                    @if ($sortField === 'declared_value')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parcels as $parcel)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $parcel->parcel_id }}</div>
                                <div class="mt-1">
                                    @if($parcel->is_priority)
                                    <span class="badge bg-warning badge-sm">
                                        <i class="bi bi-star-fill me-1"></i> Priority
                                    </span>
                                    @endif
                                    @if($parcel->is_cod)
                                    <span class="badge bg-info badge-sm ms-1">
                                        <i class="bi bi-cash-coin me-1"></i> COD
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <!-- <td>
                                <div class="route-info">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-geo-alt-fill text-success me-1 small"></i>
                                        <span class="small">{{ $parcel->senderTown->name ?? $parcel->senderCounty->name ?? 'N/A' }}</span>
                                    </div>
                                    <i class="bi bi-arrow-right-short"></i>

                                </div>
                            </td> -->
                            <td>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-geo-alt-fill text-success me-1 small"></i>
                                    <span class="small">{{ $parcel->senderTown->name ?? $parcel->senderCounty->name ?? 'N/A' }}</span>
                                </div>
                                <div class="fw-semibold">{{ $parcel->senderPickUpDropOffPoint?->name ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $parcel->senderPickUpDropOffPoint?->address ?? 'Address not specified' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center mt-1">
                                    <i class="bi bi-geo-alt-fill text-danger me-1 small"></i>
                                    <span class="small">{{ $parcel->receiverTown->name ?? $parcel->receiverCounty->name ?? 'N/A' }}</span>
                                </div>
                                <div class="fw-semibold">{{ $parcel->deliveryStation?->name ?? 'N/A' }}</div>
                                <div class="small text-muted">{{ $parcel->deliveryStation?->address ?? 'Address not specified' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ ucfirst($parcel->parcel_type ?? 'Package') }}</div>
                                <div class="small text-muted">{{ ucfirst($parcel->package_type ?? 'Regular') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $parcel->weight ?? '—' }} {{ $parcel->weight ? 'kg' : '' }}</div>
                                @if($parcel->dimensions)
                                <div class="small text-muted">{{ $parcel->dimensions }}</div>
                                @endif
                            </td>
                            <td>
                                <div>KES {{ number_format($parcel->declared_value ?? 0) }}</div>
                                @if($parcel->has_insurance)
                                <span class="badge bg-success badge-sm">Insured</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-outline-primary"
                                        wire:click="viewParcelDetails('{{ $parcel->id }}')"
                                        title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-success"
                                        wire:click="initiateDeliveryOption('{{ $parcel->id }}')"
                                        title="Accept Delivery">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-box-seam display-1 text-muted"></i>
                                        <h4 class="mt-3">No Deliveries Available</h4>
                                        <p class="text-muted">
                                            @if($search || $status || $parcel_type || $sender_county || $receiver_county || $weight_min || $weight_max || $size_filter || $priority_only || $cod_only || $high_value_only)
                                            No deliveries match your filters
                                            @else
                                            No deliveries are currently available. Check back later!
                                            @endif
                                        </p>
                                        @if($search || $status || $parcel_type || $sender_county || $receiver_county || $weight_min || $weight_max || $size_filter || $priority_only || $cod_only || $high_value_only)
                                        <button class="btn btn-primary mt-2" wire:click="resetFilters">
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

            <!-- Pagination -->
            <div class="table-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="results-info">
                        <span class="small text-muted">
                            Showing
                            <strong>{{ $parcels->firstItem() ?? 0 }}-{{ $parcels->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $parcels->total() }}</strong> deliveries
                        </span>
                    </div>
                    <div>
                        {{ $parcels->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Parcel Details Modal -->
        @if($showParcelDetailsModal && $selectedParcel)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-box-seam me-2"></i>
                            Delivery Details
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeParcelModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="details-section">
                                    <h6 class="section-label">Parcel Information</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Tracking Number:</th>
                                            <td><strong>{{ $selectedParcel->parcel_id ?? $selectedParcel->id }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Parcel Type:</th>
                                            <td>{{ ucfirst($selectedParcel->parcel_type ?? 'Package') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Package Type:</th>
                                            <td>{{ $selectedParcel->package_type ?? 'Standard' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight:</th>
                                            <td>{{ $selectedParcel->weight ?? 'N/A' }} {{ $selectedParcel->weight ? 'kg' : '' }}</td>
                                        </tr>
                                        @if($selectedParcel->dimensions)
                                        <tr>
                                            <th>Dimensions:</th>
                                            <td>{{ $selectedParcel->dimensions }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Declared Value:</th>
                                            <td>KES {{ number_format($selectedParcel->declared_value ?? 0) }}</td>
                                        </tr>
                                        @if($selectedParcel->is_priority)
                                        <tr>
                                            <th>Priority:</th>
                                            <td><span class="badge bg-warning">Yes</span></td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $selectedParcel->content_description }}</td>
                                        </tr>
                                    </table>

                                    @if($selectedParcel->special_instructions)
                                    <div class="alert alert-warning mt-2">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Special Instructions:</strong>
                                        <p class="mb-0 mt-1">{{ $selectedParcel->special_instructions }}</p>
                                    </div>
                                    @endif
                                </div>

                                <div class="details-section mt-3">
                                    <h6 class="section-label">Route Information</h6>
                                    <div class="route-visual">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center flex-grow-1">
                                                <i class="bi bi-geo-alt-fill text-success fs-4"></i>
                                                <div class="fw-semibold mt-1">{{ $selectedParcel->senderTown->name }}</div>
                                                <small class="text-muted">{{ $selectedParcel->senderPickUpDropOffPoint->address }}</small>
                                            </div>
                                            <div class="px-3">
                                                <i class="bi bi-arrow-right-short fs-3 text-muted"></i>
                                            </div>
                                            <div class="text-center flex-grow-1">
                                                <i class="bi bi-geo-alt-fill text-danger fs-4"></i>
                                                <div class="fw-semibold mt-1">{{ $selectedParcel->receiverTown->name }}</div>
                                                <small class="text-muted">{{ $selectedParcel->deliveryStation->address }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeParcelModal">
                            <i class="bi bi-x-circle me-2"></i> Close
                        </button>
                        <button type="button" class="btn btn-success" wire:click="initiateDeliveryOptionFromModal">
                            <i class="bi bi-truck me-2"></i> Select Delivery Option
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Delivery Option Selection Modal -->
        @if($showDeliveryOptionModal && $selectedParcel)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-geo-alt me-2"></i>
                            Select Delivery Destination
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDeliveryOptionModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Route Summary -->
                        <div class="route-summary bg-light p-3 rounded mb-4">
                            <div class="text-center mb-2">
                                <strong>Delivery Route</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-center">
                                    <i class="bi bi-geo-alt-fill text-success fs-4"></i>
                                    <div class="small fw-semibold mt-1">{{ $selectedParcel->senderTown->name ?? $selectedParcel->senderCounty->name ?? 'Pickup' }}</div>
                                    <small class="text-muted">{{ $selectedParcel->senderPickUpDropOffPoint->name }}</small>
                                    <small class="text-muted">{{ $selectedParcel->senderPickUpDropOffPoint->address }}</small>

                                </div>
                                <div>
                                    <i class="bi bi-arrow-right-short fs-2 text-muted"></i>
                                </div>
                                <div class="text-center">
                                    <i class="bi bi-geo-alt-fill text-danger fs-4"></i>
                                    <div class="small fw-semibold mt-1">{{ $selectedParcel->receiverTown->name ?? $selectedParcel->receiverCounty->name ?? 'Destination' }}</div>
                                    <small class="text-muted">{{ $selectedParcel->deliveryStation->name }}</small>
                                    <small class="text-muted">{{ $selectedParcel->deliveryStation->address }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            Please select where you will deliver this parcel. Payout will be calculated based on your selection.
                        </div>

                        <!-- Delivery Options -->
                        <div class="delivery-options mb-4">
                            <!-- Option 1: Deliver to Warehouse -->
                            <div class="delivery-option-card p-3 border rounded mb-3 {{ $delivery_option === 'warehouse' ? 'border-primary bg-primary bg-opacity-10' : '' }}"
                                wire:click="setDeliveryOption('warehouse')" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio"
                                            id="delivery_warehouse"
                                            value="warehouse"
                                            wire:model.live="delivery_option"
                                            style="cursor: pointer;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="fw-semibold fs-5 cursor-pointer" for="delivery_warehouse" style="cursor: pointer;">
                                            <i class="bi bi-building-warehouse me-2 text-primary"></i>
                                            Deliver to Warehouse Hub
                                        </label>
                                        <p class="text-muted small mb-0 mt-1">
                                            Drop off at our nearest warehouse. We'll handle the final delivery to the customer.
                                        </p>
                                        <div class="mt-2">
                                            <span class="badge bg-info">Partial Delivery</span>
                                            <span class="badge bg-secondary ms-1">Faster turnaround</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Estimated Payout</div>
                                        <div class="fw-bold text-success h5 mb-0">{{ $warehousePaymentStructure->transport_partner_percentage  }}%</div>
                                        <small class="text-muted">of total payout</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: Deliver to Final Destination -->
                            <div class="delivery-option-card p-3 border rounded {{ $delivery_option === 'final_destination' ? 'border-primary bg-primary bg-opacity-10' : '' }}"
                                wire:click="setDeliveryOption('final_destination')" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio"
                                            id="delivery_final"
                                            value="final_destination"
                                            wire:model.live="delivery_option"
                                            style="cursor: pointer;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <label class="fw-semibold fs-5 cursor-pointer" for="delivery_final" style="cursor: pointer;">
                                            <i class="bi bi-house-door me-2 text-success"></i>
                                            Deliver to Final Destination
                                        </label>
                                        <p class="text-muted small mb-0 mt-1">
                                            Complete delivery to the assigned drop off point. We'll handle the rest.
                                        </p>
                                        <div class="mt-2">
                                            <span class="badge bg-success">Full Delivery</span>
                                            <span class="badge bg-secondary ms-1">Higher payout</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="text-muted small">Estimated Payout</div>
                                        <div class="fw-bold text-success h5 mb-0">{{ $pickUpDropOffPaymentStructure->transport_partner_percentage  }}%</div>
                                        <small class="text-muted">of total payout</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warehouse Selection (shown only when warehouse option is selected) -->
                        @if($delivery_option === 'warehouse')
                        <div class="warehouse-selection-section border-top pt-3 mt-2">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-building-warehouse me-2"></i>
                                Select Warehouse <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" wire:model.live="selected_warehouse_id">
                                <option value="">Choose a warehouse...</option>
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">
                                    {{ $warehouse->name }}
                                    @if($warehouse->town)
                                    - {{ $warehouse->town->name }}
                                    @endif
                                    @if($warehouse->address)
                                    ({{ Str::limit($warehouse->address, 50) }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error('selected_warehouse_id')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror

                            @if($warehouses->isEmpty())
                            <div class="alert alert-warning mt-2">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No warehouses available. Please contact administrator.
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Payout Calculation Display -->
                        @if($delivery_option && ($delivery_option !== 'warehouse' || ($delivery_option === 'warehouse' && $selected_warehouse_id)))
                        <div class="payout-calculation mt-4 p-3 bg-success bg-opacity-10 rounded">
                            <div class="text-center">
                                <small class="text-muted">Your Calculated Payout</small>
                                <div class="display-6 fw-bold text-success mb-2">
                                    KES {{ $calculated_payout }}
                                </div>
                                <div class="small text-muted">
                                    <i class="bi bi-calculator me-1"></i>
                                    Based on:
                                    @if($delivery_option === 'warehouse')
                                    Warehouse delivery ({{ $warehousePaymentStructure->transport_partner_percentage  }}% of KES {{ $base_payout }})
                                    @else
                                    Final destination delivery ({{ $pickUpDropOffPaymentStructure->transport_partner_percentage  }}% of KES {{ $base_payout }})
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDeliveryOptionModal">
                            <i class="bi bi-x-circle me-2"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-primary"
                            wire:click="confirmDeliveryOption"
                            @if(!$delivery_option || ($delivery_option==='warehouse' && !$selected_warehouse_id)) disabled @endif>
                            <i class="bi bi-check-circle me-2"></i> Confirm & Calculate Payout
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payout Confirmation Modal -->
        @if($showPayoutConfirmationModal && $selectedParcel)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title text-white">
                            <i class="bi bi-currency-dollar me-2"></i>
                            Payout Confirmation
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePayoutConfirmationModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-check-circle-fill text-success display-1"></i>
                            <h4 class="mt-2">Delivery Option Confirmed!</h4>
                        </div>

                        <div class="route-summary bg-light p-3 rounded mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Tracking #</small>
                                    <div class="fw-semibold">{{ $selectedParcel->parcel_id }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Delivery Type</small>
                                    <div class="fw-semibold">
                                        @if($selectedParcel->delivery_option === 'warehouse')
                                        <i class="bi bi-building-warehouse me-1 text-primary"></i> Warehouse Delivery
                                        @else
                                        <i class="bi bi-house-door me-1 text-success"></i> Final Destination
                                        @endif
                                    </div>
                                </div>
                                @if($selectedParcel->delivery_option === 'warehouse' && $selectedParcel->assigned_warehouse_id)
                                <div class="col-12 mt-2">
                                    <small class="text-muted">Selected Warehouse</small>
                                    <div class="fw-semibold">{{ $selectedParcel->assignedWarehouse->name ?? 'N/A' }}</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="payout-summary text-center p-4 bg-success bg-opacity-10 rounded">
                            <small class="text-muted">Your Confirmed Payout</small>
                            <div class="display-4 fw-bold text-success mb-2">
                                KES {{ number_format($selectedParcel->partner_payout ?? $calculated_payout, 0) }}
                            </div>
                            <div class="small text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                This amount will be credited to your account upon successful delivery
                            </div>
                        </div>

                        @if($selectedParcel->delivery_option === 'warehouse')
                        <div class="warehouse-instructions mt-3 p-3 bg-warning bg-opacity-10 rounded">
                            <i class="bi bi-info-circle-fill text-warning me-2"></i>
                            <strong>Warehouse Delivery Instructions:</strong>
                            <ul class="mt-2 mb-0 small">
                                <li>Deliver the parcel to the selected warehouse hub</li>
                                <li>Present the tracking number for verification</li>
                                <li>Get a delivery confirmation receipt</li>
                                <li>We'll handle the final delivery to the customer</li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closePayoutConfirmationModal">
                            <i class="bi bi-x-circle me-2"></i> Close
                        </button>
                        <button type="button" class="btn btn-success" wire:click="proceedToDriverAssignment">
                            <i class="bi bi-truck me-2"></i> Proceed to Assign Driver
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Driver Assignment Modal -->
        @if($showDriverAssignmentModal && $selectedParcel)
        <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-truck me-2"></i>
                            Assign Driver
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDriverModal"></button>
                    </div>
                    <form wire:submit.prevent="assignDriver">
                        <div class="modal-body">
                            <div class="parcel-summary bg-light p-3 rounded mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Tracking #</small>
                                        <div class="fw-semibold">{{ $selectedParcel->parcel_id }}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Final Payout</small>
                                        <div class="fw-semibold text-success">KES {{ number_format($selectedParcel->partner_payout ?? $calculated_payout, 0) }}</div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted">Delivery Type</small>
                                        <div class="fw-semibold">
                                            @if($selectedParcel->delivery_option === 'warehouse')
                                            <i class="bi bi-building-warehouse me-1"></i> Warehouse Delivery
                                            @else
                                            <i class="bi bi-house-door me-1"></i> Door-to-Door
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <small class="text-muted">Route</small>
                                        <div>{{ $selectedParcel->senderTown->name ?? 'N/A' }} →
                                            @if($selectedParcel->delivery_option === 'warehouse' && $selectedParcel->assignedWarehouse)
                                            {{ $selectedParcel->assignedWarehouse->town->name ?? 'Warehouse' }}
                                            @else
                                            {{ $selectedParcel->receiverTown->name ?? 'N/A' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Driver <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="selectedDriver" required>
                                    <option value="">Choose a driver...</option>
                                    @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">
                                        {{ $driver->first_name }} {{ $driver->last_name }} -
                                        @if($driver->currentFleet && $driver->currentFleet->fleet)
                                        {{ $driver->currentFleet->fleet->registration_number }}
                                        ({{ $driver->currentFleet->fleet->vehicle_type }})
                                        @else
                                        No vehicle assigned
                                        @endif
                                        @if($driver->is_available)
                                        - Available
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('selectedDriver') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Assignment Notes (Optional)</label>
                                <textarea class="form-control" wire:model="assignment_notes" rows="2"
                                    placeholder="Any instructions for the driver..."></textarea>
                            </div>

                            @if(count($drivers) === 0)
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                No active drivers available. Please <a href="{{ route('partners.drivers.create') }}">add drivers</a> first.
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeDriverModal">
                                <i class="bi bi-x-circle me-2"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" @if(count($drivers)===0) disabled @endif>
                                <i class="bi bi-check-circle me-2"></i> Assign Driver
                            </button>
                        </div>
                    </form>
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
                width: 280px;
            }

            .search-container .input-group {
                height: 38px;
            }

            /* Filter Badge */
            .filter-badge {
                background-color: var(--primary-color, #008f40);
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
                border: 1px solid #e9ecef;
                display: flex;
                align-items: center;
                gap: 15px;
                height: 100%;
                transition: all 0.2s;
            }

            .stats-overview .stat-card:hover {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
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

            .stats-overview .stat-value {
                font-size: 2rem;
                font-weight: 700;
                color: #2c3e50;
                line-height: 1;
            }

            .stats-overview .stat-label {
                font-size: 0.9rem;
                color: #6c757d;
                margin-top: 5px;
            }

            /* Advanced Filters */
            .advanced-filters {
                margin-bottom: 20px;
            }

            .filter-panel {
                background: white;
                border-radius: 12px;
                padding: 20px;
                border: 1px solid #e9ecef;
            }

            /* Alert Banner */
            .alert-banner {
                background: linear-gradient(135deg, #008f40, #00b359);
                color: white;
                border: none;
                border-radius: 12px;
            }

            .alert-banner .btn-light {
                background: rgba(255, 255, 255, 0.9);
                border: none;
            }

            /* Table Styling */
            .table th {
                background-color: #f8f9fa;
                font-weight: 600;
                color: #2c3e50;
                border-bottom: 2px solid #e9ecef;
                white-space: nowrap;
            }

            .table td {
                vertical-align: middle;
                border-bottom: 1px solid #e9ecef;
            }

            .table tbody tr:hover {
                background-color: rgba(0, 143, 64, 0.03);
            }

            .badge-sm {
                font-size: 0.7rem;
                padding: 2px 6px;
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

            /* Delivery Option Cards */
            .delivery-option-card {
                transition: all 0.2s ease;
            }

            .delivery-option-card:hover {
                transform: translateX(5px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .cursor-pointer {
                cursor: pointer;
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
            .details-section {
                margin-bottom: 20px;
            }

            .details-section .section-label {
                font-size: 0.9rem;
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 2px solid #e9ecef;
            }

            .details-section .table {
                margin-bottom: 0;
            }

            .details-section .table th {
                background: none;
                font-weight: 600;
                width: 40%;
            }

            .route-visual {
                padding: 15px;
                background: #f8f9fa;
                border-radius: 12px;
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

                .modal-dialog {
                    margin: 10px;
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
                        @if($showParcelDetailsModal)
                        @this.call('closeParcelModal');
                        @endif
                        @if($showDeliveryOptionModal)
                        @this.call('closeDeliveryOptionModal');
                        @endif
                        @if($showPayoutConfirmationModal)
                        @this.call('closePayoutConfirmationModal');
                        @endif
                        @if($showDriverAssignmentModal)
                        @this.call('closeDriverModal');
                        @endif
                    }
                });

                // Focus search on page load
                const searchInput = document.querySelector('.search-container input');
                if (searchInput) {
                    searchInput.focus();
                }
            });
        </script>
    </div>
</div>