<div>
    <div>
    <div class="earnings-management">
        <!-- Header Section with Gradient -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-wallet2 me-2"></i>
                        Earnings & Payouts
                    </h2>
                    <p class="page-subtitle mb-0">Track your earnings, payouts, and financial performance</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-modern me-2" wire:click="downloadReport">
                        <i class="bi bi-download me-2"></i>
                        Export Report
                    </button>
                    <button class="btn btn-primary btn-modern" wire:click="requestPayout">
                        <i class="bi bi-cash-stack me-2"></i>
                        Request Payout
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="stats-grid mb-4">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="bi bi-calculator"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Overall Total</span>
                    <span class="stat-value">KES {{ number_format($overallTotal, 2) }}</span>
                </div>
            </div>

            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Pending Payouts</span>
                    <span class="stat-value">KES {{ number_format($pendingTotal, 2) }}</span>
                    <small>{{ $pendingCount }} pending requests</small>
                </div>
            </div>

            <div class="stat-card approved">
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Approved</span>
                    <span class="stat-value">KES {{ number_format($approvedTotal, 2) }}</span>
                </div>
            </div>

            <div class="stat-card completed">
                <div class="stat-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Completed Payouts</span>
                    <span class="stat-value">KES {{ number_format($completedTotal, 2) }}</span>
                    <small>{{ $completedCount }} completed</small>
                </div>
            </div>

            <div class="stat-card cancelled">
                <div class="stat-icon">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Cancelled Payouts</span>
                    <span class="stat-value">KES {{ number_format($cancelledTotal, 2) }}</span>
                    <small>{{ $cancelledCount }} cancelled</small>
                </div>
            </div>
        </div>

        <!-- Toggle Chart Button -->
        <div class="mb-3 text-end">
            <button class="btn btn-outline-primary" wire:click="toggleChart">
                <i class="bi bi-graph-up me-2"></i>
                {{ $showChart ? 'Hide' : 'Show' }} Earnings Chart
            </button>
        </div>

        <!-- Earnings Chart Section (Conditional) -->
        @if($showChart)
        <div class="chart-section mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>
                        Earnings Overview ({{ date('Y') }})
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" height="100"></canvas>
                </div>
            </div>
        </div>
        @endif

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

                <!-- Type Filter Dropdown -->
                <div class="filter-dropdown">
                    <button class="filter-btn" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-tag me-2"></i>
                        Type
                        @if ($typeFilter)
                        <span class="filter-badge">{{ $typeFilter }}</span>
                        @endif
                        <i class="bi bi-chevron-down ms-2"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">Filter by Type</div>
                        <a class="dropdown-item {{ !$typeFilter ? 'active' : '' }}" wire:click="$set('typeFilter', '')">
                            All Types
                        </a>
                        @foreach ($payoutTypes as $value => $label)
                        @if ($value !== '')
                        <a class="dropdown-item {{ $typeFilter == $value ? 'active' : '' }}"
                            wire:click="$set('typeFilter', '{{ $value }}')">
                            <i class="bi {{ $this->getTypeBadge($value)['icon'] }} me-2"></i>
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
                        @if ($dateFrom || $dateTo)
                        <span class="filter-badge">Active</span>
                        @endif
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
                        <button class="btn btn-sm btn-primary w-100" wire:click="$refresh">
                            Apply
                        </button>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if ($this->hasActiveFilters())
                <button class="filter-btn clear" wire:click="resetFilters">
                    <i class="bi bi-x-circle me-2"></i>
                    Clear Filters
                </button>
                @endif
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        @if ($showBulkActions)
        <div class="bulk-actions-bar mb-4">
            <div class="bulk-actions-content">
                <div class="selected-count">
                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                    <span class="fw-medium">{{ count($selectedPayouts) }} transactions selected</span>
                </div>
                <div class="bulk-buttons">
                    <button class="btn btn-sm btn-danger" wire:click="bulkDelete"
                        wire:confirm="Are you sure you want to delete selected transactions?">
                        <i class="bi bi-trash me-1"></i>
                        Delete
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" wire:click="$set('selectedPayouts', [])">
                        <i class="bi bi-x me-1"></i>
                        Clear
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Payouts Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table earnings-table">
                    <thead>
                        <tr>
                            <th width="50">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="selectAll" id="selectAll">
                                </div>
                            </th>
                            <th wire:click="sortBy('created_at')" class="sortable">
                                <div class="d-flex align-items-center">
                                    Date
                                    @if ($sortField === 'created_at')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Parcel Details</th>
                            <th>Type</th>
                            <th wire:click="sortBy('amount')" class="sortable">
                                <div class="d-flex align-items-center">
                                    Amount
                                    @if ($sortField === 'amount')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-2"></i>
                                    @endif
                                </div>
                            </th>
                            <th>Status</th>
                            <th>Destination</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                        <tr class="{{ in_array($payout->id, $selectedPayouts) ? 'selected-row' : '' }}">
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        value="{{ $payout->id }}" wire:model="selectedPayouts">
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-main">{{ $payout->created_at->format('M d, Y') }}</div>
                                    <div class="date-time">{{ $payout->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="parcel-info">
                                    <a href="javascript:void(0)" 
                                       class="parcel-number"
                                       wire:click="viewDetails({{ $payout->id }})">
                                        {{ $payout->parcel->parcel_id ?? 'N/A' }}
                                    </a>
                                    <div class="parcel-route">
                                        <i class="bi bi-arrow-left-right"></i>
                                        {{ $payout->parcel->senderTown->name ?? 'N/A' }} → 
                                        {{ $payout->parcel->receiverTown->name ?? 'N/A' }}
                                    </div>
                                    <div class="parcel-customer">
                                        <i class="bi bi-person"></i>
                                        {{ $payout->parcel->sender_name ?? 'N/A' }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                $typeBadge = $this->getTypeBadge($payout->type);
                                @endphp
                                <span class="type-badge" style="background: {{ $typeBadge['color'] }}20; color: {{ $typeBadge['color'] }}">
                                    <i class="bi {{ $typeBadge['icon'] }} me-1"></i>
                                    {{ $typeBadge['text'] }}
                                </span>
                            </td>

                            <td>
                                <div class="amount-cell {{ $payout->status === 'cancelled' ? 'cancelled' : '' }}">
                                    <span class="amount-value">KES {{ number_format($payout->amount, 2) }}</span>
                                    @if($payout->parcel?->payment_status === 'paid')
                                    <span class="payment-badge">
                                        <i class="bi bi-check-circle"></i> Paid
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @php
                                $statusBadge = $this->getStatusBadge($payout->status);
                                @endphp
                                <div class="status-wrapper">
                                    <span class="status-badge" style="background: {{ $statusBadge['color'] }}20; color: {{ $statusBadge['color'] }}">
                                        <i class="bi {{ $statusBadge['icon'] }} me-1"></i>
                                        {{ $statusBadge['text'] }}
                                    </span>
                                    @if($payout->paid_out_on)
                                    <div class="paid-date">
                                        <i class="bi bi-calendar-check"></i>
                                        Paid: {{ $payout->paid_out_on }}
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="destination-cell">
                                    @if($payout->parcelDestination)
                                    <div class="destination-name">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $payout->parcelDestination->name ?? 'N/A' }}
                                    </div>
                                    <div class="destination-town">
                                        {{ $payout->parcelDestination->town->name ?? 'N/A' }}
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button wire:click="viewDetails({{ $payout->id }})"
                                        class="action-btn view" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-wallet2"></i>
                                    </div>
                                    <h4>No Earnings Found</h4>
                                    <p>
                                        @if ($this->hasActiveFilters())
                                        No earnings match your current filters. Try adjusting your search criteria.
                                        @else
                                        No earnings have been recorded yet. Start shipping parcels to earn!
                                        @endif
                                    </p>
                                    @if ($this->hasActiveFilters())
                                    <button class="btn btn-primary" wire:click="resetFilters">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Clear Filters
                                    </button>
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
                Showing {{ $payouts->firstItem() ?? 0 }} to {{ $payouts->lastItem() ?? 0 }} of {{ $payouts->total() }} transactions
            </div>
            <div class="pagination-links">
                {{ $payouts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    @if($showModal && $modalPayout)
    <div class="modal fade show" id="detailsModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle me-2"></i>
                        Payout Details
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <!-- Parcel Information -->
                    <div class="detail-section mb-4">
                        <h6 class="section-title">
                            <i class="bi bi-box-seam me-2"></i>
                            Parcel Information
                        </h6>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Parcel Number:</span>
                                <span class="detail-value fw-bold text-primary">{{ $modalPayout->parcel->parcel_id ?? 'N/A' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Parcel Type:</span>
                                <span class="detail-value">{{ ucfirst($modalPayout->parcel->parcel_type ?? 'N/A') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Weight:</span>
                                <span class="detail-value">{{ $modalPayout->parcel->weight ?? 'N/A' }} {{ $modalPayout->parcel->weight_unit ?? 'kg' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Description:</span>
                                <span class="detail-value">{{ $modalPayout->parcel->content_description ?? 'No description' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sender & Receiver Information -->
                    <div class="detail-section mb-4">
                        <h6 class="section-title">
                            <i class="bi bi-people me-2"></i>
                            Sender & Receiver
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sub-section">
                                    <h6 class="sub-section-title">Sender</h6>
                                    <div class="detail-item">
                                        <span class="detail-label">Name:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->sender_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Phone:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->sender_phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->sender_email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sub-section">
                                    <h6 class="sub-section-title">Receiver</h6>
                                    <div class="detail-item">
                                        <span class="detail-label">Name:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->receiver_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Phone:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->receiver_phone ?? 'N/A' }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Email:</span>
                                        <span class="detail-value">{{ $modalPayout->parcel->receiver_email ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Route Information -->
                    <div class="detail-section mb-4">
                        <h6 class="section-title">
                            <i class="bi bi-map me-2"></i>
                            Route Information
                        </h6>
                        <div class="route-info">
                            <div class="route-point">
                                <i class="bi bi-geo-alt-fill text-success"></i>
                                <div>
                                    <strong>From:</strong> 
                                    {{ $modalPayout->parcel->senderTown->name ?? 'N/A' }}
                                    @if($modalPayout->origin)
                                    <br><small class="text-muted">{{ $modalPayout->origin->name ?? '' }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="route-arrow">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                            <div class="route-point">
                                <i class="bi bi-geo-alt-fill text-danger"></i>
                                <div>
                                    <strong>To:</strong> 
                                    {{ $modalPayout->parcel->receiverTown->name ?? 'N/A' }}
                                    @if($modalPayout->parcelDestination)
                                    <br><small class="text-muted">{{ $modalPayout->parcelDestination->name ?? '' }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payout Information -->
                    <div class="detail-section mb-4">
                        <h6 class="section-title">
                            <i class="bi bi-cash-stack me-2"></i>
                            Payout Information
                        </h6>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Payout Type:</span>
                                <span class="detail-value">
                                    @php
                                    $typeBadge = $this->getTypeBadge($modalPayout->type);
                                    @endphp
                                    <span class="type-badge" style="background: {{ $typeBadge['color'] }}20; color: {{ $typeBadge['color'] }}">
                                        <i class="bi {{ $typeBadge['icon'] }} me-1"></i>
                                        {{ $typeBadge['text'] }}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Amount:</span>
                                <span class="detail-value text-success fw-bold">KES {{ number_format($modalPayout->amount, 2) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    @php
                                    $statusBadge = $this->getStatusBadge($modalPayout->status);
                                    @endphp
                                    <span class="status-badge" style="background: {{ $statusBadge['color'] }}20; color: {{ $statusBadge['color'] }}">
                                        <i class="bi {{ $statusBadge['icon'] }} me-1"></i>
                                        {{ $statusBadge['text'] }}
                                    </span>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Created Date:</span>
                                <span class="detail-value">{{ $modalPayout->created_at->format('F d, Y h:i A') }}</span>
                            </div>
                            @if($modalPayout->paid_out_on)
                            <div class="detail-item">
                                <span class="detail-label">Paid Out On:</span>
                                <span class="detail-value">{{ $modalPayout->paid_out_on }}</span>
                            </div>
                            @endif
                            @if($modalPayout->cancelation_reason)
                            <div class="detail-item">
                                <span class="detail-label">Cancellation Reason:</span>
                                <span class="detail-value text-danger">{{ $modalPayout->cancelation_reason }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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

        .earnings-management {
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
        }

        .stat-card.total .stat-icon {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .stat-card.pending .stat-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .stat-card.approved .stat-icon {
            background: rgba(59, 130, 246, 0.1);
            color: var(--info);
        }

        .stat-card.completed .stat-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-card.cancelled .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
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
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.2;
        }

        .stat-content small {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        /* Chart Section */
        .chart-section .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .chart-section .card-header {
            background: white;
            border-bottom: 2px solid #f3f4f6;
            padding: 1.25rem 1.5rem;
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .chart-section .card-title {
            font-weight: 600;
            color: #1f2937;
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
            display: inline-flex;
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

        /* Table */
        .table-container {
            background: white;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .earnings-table {
            margin: 0;
        }

        .earnings-table thead th {
            background: #f9fafb;
            padding: 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            border-bottom: 2px solid #e5e7eb;
        }

        .earnings-table tbody tr {
            transition: all 0.3s ease;
        }

        .earnings-table tbody tr:hover {
            background: #f9fafb;
        }

        .earnings-table tbody tr.selected-row {
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
        .date-cell .date-main {
            font-size: 0.9rem;
            font-weight: 500;
            color: #1f2937;
        }

        .date-cell .date-time {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .parcel-info .parcel-number {
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .parcel-info .parcel-number:hover {
            text-decoration: underline;
        }

        .parcel-info .parcel-route {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .parcel-info .parcel-customer {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            width: fit-content;
        }

        .amount-cell .amount-value {
            font-weight: 600;
            color: #10b981;
            font-size: 1rem;
        }

        .amount-cell.cancelled .amount-value {
            color: #ef4444;
            text-decoration: line-through;
        }

        .payment-badge {
            font-size: 0.7rem;
            color: #10b981;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-left: 0.5rem;
        }

        .status-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            width: fit-content;
        }

        .paid-date {
            font-size: 0.75rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .destination-cell .destination-name {
            font-size: 0.85rem;
            font-weight: 500;
            color: #1f2937;
        }

        .destination-cell .destination-town {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
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

        /* Modal Styles */
        .modal {
            z-index: 1050;
        }
        
        .modal.show {
            display: block;
        }
        
        .modal-content {
            border: none;
            border-radius: 1.25rem;
            overflow: hidden;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .detail-section {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
        }
        
        .detail-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .sub-section {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 0.75rem;
        }
        
        .sub-section-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 0.75rem;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            font-size: 0.9rem;
            color: #1f2937;
            font-weight: 500;
        }
        
        .route-info {
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 1rem;
            background: #f9fafb;
            border-radius: 0.75rem;
        }
        
        .route-point {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        
        .route-point i {
            font-size: 1.5rem;
        }
        
        .route-arrow {
            color: var(--primary);
            font-size: 1.5rem;
            margin: 0 1rem;
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

        /* Responsive */
        @media (max-width: 768px) {
            .earnings-management {
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
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .route-info {
                flex-direction: column;
            }
            
            .route-arrow {
                transform: rotate(90deg);
                margin: 0.5rem 0;
            }
        }
    </style>
</div>

<!-- Chart.js for earnings chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        // Initialize earnings chart when shown
        Livewire.on('openModal', () => {
            // Modal is already handled by Livewire's conditional rendering
        });
        
        // Initialize chart when it becomes visible
        const initChart = () => {
            const ctx = document.getElementById('earningsChart');
            if (ctx && !ctx.chart) {
                ctx.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Earnings (KES)',
                            data: @json($monthlyEarnings ?? array_fill(0, 12, 0)),
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'KES ' + context.raw.toLocaleString();
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'KES ' + value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
            }
        };
        
        // Try to initialize chart
        setTimeout(initChart, 100);
        
        // Watch for chart visibility changes
        const observer = new MutationObserver(() => {
            const chartCanvas = document.getElementById('earningsChart');
            if (chartCanvas && chartCanvas.offsetParent && !chartCanvas.chart) {
                initChart();
            }
        });
        
        observer.observe(document.body, { childList: true, subtree: true });
    });
</script>
</div>