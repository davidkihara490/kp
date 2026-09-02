<div>
    <div class="customer-dashboard">
        <div class="container py-4">
            <!-- Dashboard Header -->
            <div class="dashboard-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-1">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            My Parcels
                        </h2>
                        <p class="text-muted">Track and manage all your parcels in one place</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a target="_blank" href="{{ route('home') }}#bookingTabs" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-plus-circle me-2"></i>
                            Book New Parcel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-primary-light">
                            <i class="bi bi-box-seam text-primary"></i>
                        </div>
                        <div class="stat-info">
                            <h5 class="stat-number">{{ $parcels->count() }}</h5>
                            <span class="stat-label">Total Parcels</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning-light">
                            <i class="bi bi-clock-history text-warning"></i>
                        </div>
                        <div class="stat-info">
                            <h5 class="stat-number">{{ $parcels->whereIn('current_status', ['created', 'booked', 'accepted', 'assigned', 'pending'])->count() }}</h5>
                            <span class="stat-label">In Progress</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-success-light">
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>
                        <div class="stat-info">
                            <h5 class="stat-number">{{ $parcels->where('current_status', 'delivered')->count() }}</h5>
                            <span class="stat-label">Delivered</span>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon bg-danger-light">
                            <i class="bi bi-exclamation-triangle text-danger"></i>
                        </div>
                        <div class="stat-info">
                            <h5 class="stat-number">{{ $parcels->whereIn('current_status', ['failed', 'returned'])->count() }}</h5>
                            <span class="stat-label">Issues</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-section mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text"
                                class="form-control form-control-lg"
                                wire:model.live.debounce.300ms="searchTerm"
                                placeholder="Search by ID, sender, receiver...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-lg" wire:model.live="statusFilter">
                            <option value="">All Statuses</option>
                            <option value="created">Created</option>
                            <option value="booked">Booked</option>
                            <option value="accepted">Accepted</option>
                            <option value="assigned">Assigned</option>
                            <option value="in_transit">In Transit</option>
                            <option value="pending">Pending</option>
                            <option value="warehouse">At Warehouse</option>
                            <option value="arrived_at_destination">Arrived at Destination</option>
                            <option value="picked">Picked for Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="failed">Failed</option>
                            <option value="returned">Returned</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-lg" wire:model.live="dateRange">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" wire:click="loadParcels">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Parcels List -->
            @if($parcels->count() > 0)
            <div class="parcels-list">
                <div class="table-responsive">
                    <table class="table parcels-table">
                        <thead>
                            <tr>
                                <th>Parcel ID</th>
                                <th>Route</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parcels as $parcel)
                            <tr>
                                <td>
                                    <span class="parcel-id-badge">{{ $parcel->parcel_id }}</span>
                                </td>
                                <td>
                                    <div class="route-info">
                                        <span class="town-name">{{ $parcel->senderTown?->name ?? 'N/A' }}</span>
                                        <i class="bi bi-arrow-right text-primary mx-1"></i>
                                        <span class="town-name">{{ $parcel->receiverTown?->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <span class="contact-name">{{ $parcel->sender_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <span class="contact-name">{{ $parcel->receiver_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="parcel-type-badge">
                                        <i class="bi bi-box"></i>
                                        {{ ucfirst($parcel->parcel_type) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="parcel-amount">KSh {{ number_format($parcel->total_amount ?? 0, 2) }}</span>
                                    <br>
                                    @php
                                    $paymentBadge = $this->getPaymentStatusBadge($parcel->payment_status ?? 'pending');
                                    @endphp
                                    <span class="status-badge status-{{ $paymentBadge['color'] }}" style="font-size: 0.65rem;">
                                        <i class="{{ $paymentBadge['icon'] }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $parcel->payment_status ?? 'Pending')) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status-badge status-{{ $this->getStatusColor($parcel->current_status) }}">
                                        <i class="{{ $this->getStatusIcon($parcel->current_status) }} me-1"></i>
                                        {{ $this->getStatusLabel($parcel->current_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="parcel-date">{{ $parcel->created_at->format('M d, Y') }}</span>
                                </td>
                                <td>
                                    <button class="btn-view" wire:click="viewParcel({{ $parcel->id }})">
                                        <i class="bi bi-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon">
                    <i class="bi bi-box-seam"></i>
                </div>
                <h4 class="fw-bold mt-3">No Parcels Found</h4>
                <p class="text-muted mb-4">
                    @if($searchTerm || $statusFilter || $dateRange !== 'all')
                    No parcels match your current filters.
                    @else
                    You haven't sent any parcels yet. Start sending your first parcel today!
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    @if($searchTerm || $statusFilter || $dateRange !== 'all')
                    <button class="btn btn-outline-secondary" wire:click="$set('searchTerm', ''); $set('statusFilter', ''); $set('dateRange', 'all')">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Clear Filters
                    </button>
                    @endif
                    <a target="_blank" href="{{ route('home') }}#bookingTabs" class="btn btn-primary btn-lg rounded-pill px-5">
                        <i class="bi bi-plus-circle me-2"></i>
                        Book Your First Parcel
                    </a>
                </div>
            </div>
            @endif
        </div>

        <!-- Parcel Detail Modal -->
        @if($showParcelDetail && $selectedParcel)
        <div class="modal-overlay" wire:click.self="closeParcelDetail">
            <div class="modal-content modal-lg animate-fade-up">
                <div class="modal-header">
                    <div class="modal-header-info">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-box-seam me-2 text-primary"></i>
                            Parcel Details
                        </h5>
                        <span class="parcel-id-display">{{ $selectedParcel->parcel_id }}</span>
                    </div>
                    <!-- Payment Response Alert -->
                    @if($paymentResponse)
                    <div class="alert alert-{{ $paymentResponseType === 'success' ? 'success' : ($paymentResponseType === 'warning' ? 'warning' : ($paymentResponseType === 'info' ? 'info' : 'danger')) }} alert-dismissible fade show mb-3" role="alert">
                        <i class="bi {{ $paymentResponseType === 'success' ? 'bi-check-circle-fill' : ($paymentResponseType === 'warning' ? 'bi-exclamation-triangle-fill' : ($paymentResponseType === 'info' ? 'bi-info-circle-fill' : 'bi-x-circle-fill')) }} me-2"></i>
                        {{ $paymentResponseMessage }}
                        <button type="button" class="btn-close" wire:click="$set('paymentResponse', null)"></button>
                    </div>
                    @endif
                    <div class="modal-header-actions">
                        <span class="status-badge status-{{ $this->getStatusColor($selectedParcel->current_status) }}">
                            <i class="{{ $this->getStatusIcon($selectedParcel->current_status) }} me-1"></i>
                            {{ $this->getStatusLabel($selectedParcel->current_status) }}
                        </span>
                        <button type="button" class="btn-close" wire:click="closeParcelDetail"></button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="modal-tabs">
                    <button class="tab-btn {{ $activeTab === 'overview' ? 'active' : '' }}"
                        wire:click="setActiveTab('overview')">
                        <i class="bi bi-info-circle me-2"></i>Overview
                    </button>
                    <button class="tab-btn {{ $activeTab === 'payment' ? 'active' : '' }}"
                        wire:click="setActiveTab('payment')">
                        <i class="bi bi-currency-dollar me-2"></i>Payment
                    </button>
                    <button class="tab-btn {{ $activeTab === 'tracking' ? 'active' : '' }}"
                        wire:click="setActiveTab('tracking')">
                        <i class="bi bi-map me-2"></i>Tracking
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Tab Content: Overview -->
                    @if($activeTab === 'overview')
                    <div class="tab-content active">
                        <!-- Route Information -->
                        <div class="route-summary mb-4">
                            <div class="route-points">
                                <div class="route-point-detail">
                                    <div class="point-icon bg-success">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="point-info">
                                        <span class="point-label">From</span>
                                        <span class="point-value">{{ $selectedParcel->senderTown?->name ?? 'N/A' }}</span>
                                        <span class="point-person">{{ $selectedParcel->sender_name }}</span>
                                    </div>
                                </div>
                                <div class="route-line">
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                                <div class="route-point-detail">
                                    <div class="point-icon bg-danger">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="point-info">
                                        <span class="point-label">To</span>
                                        <span class="point-value">{{ $selectedParcel->receiverTown?->name ?? 'N/A' }}</span>
                                        <span class="point-person">{{ $selectedParcel->receiver_name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parcel Details Grid -->
                        <div class="detail-grid">
                            <div class="detail-section">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-person me-2"></i>Sender Information
                                </h6>
                                <div class="detail-row">
                                    <span class="label">Name</span>
                                    <span class="value">{{ $selectedParcel->sender_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Phone</span>
                                    <span class="value">{{ $selectedParcel->sender_phone ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Email</span>
                                    <span class="value">{{ $selectedParcel->sender_email ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Town</span>
                                    <span class="value">{{ $selectedParcel->senderTown?->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="detail-section">
                                <h6 class="fw-bold text-danger">
                                    <i class="bi bi-person me-2"></i>Receiver Information
                                </h6>
                                <div class="detail-row">
                                    <span class="label">Name</span>
                                    <span class="value">{{ $selectedParcel->receiver_name }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Phone</span>
                                    <span class="value">{{ $selectedParcel->receiver_phone ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Email</span>
                                    <span class="value">{{ $selectedParcel->receiver_email ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Town</span>
                                    <span class="value">{{ $selectedParcel->receiverTown?->name ?? 'N/A' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Address</span>
                                    <span class="value">{{ $selectedParcel->receiver_address ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="detail-section">
                                <h6 class="fw-bold text-success">
                                    <i class="bi bi-box me-2"></i>Parcel Details
                                </h6>
                                <div class="detail-row">
                                    <span class="label">Type</span>
                                    <span class="value">{{ ucfirst($selectedParcel->parcel_type) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Package Type</span>
                                    <span class="value">{{ ucfirst($selectedParcel->package_type) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Weight</span>
                                    <span class="value">{{ $selectedParcel->weight }} {{ $selectedParcel->weight_unit ?? 'kg' }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Dimensions</span>
                                    <span class="value">{{ $selectedParcel->parcel_dimensions ?? 'N/A' }}</span>
                                </div>
                                @if($selectedParcel->content_description)
                                <div class="detail-row">
                                    <span class="label">Description</span>
                                    <span class="value">{{ $selectedParcel->content_description }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->special_instructions)
                                <div class="detail-row">
                                    <span class="label">Special Instructions</span>
                                    <span class="value">{{ $selectedParcel->special_instructions }}</span>
                                </div>
                                @endif
                            </div>

                            <div class="detail-section">
                                <h6 class="fw-bold text-info">
                                    <i class="bi bi-info-circle me-2"></i>Additional Info
                                </h6>
                                <div class="detail-row">
                                    <span class="label">Booking Type</span>
                                    <span class="value">{{ ucfirst($selectedParcel->booking_type ?? 'N/A') }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Booking Source</span>
                                    <span class="value">{{ ucfirst($selectedParcel->booking_source ?? 'N/A') }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="label">Created</span>
                                    <span class="value">{{ $selectedParcel->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($selectedParcel->updated_at)
                                <div class="detail-row">
                                    <span class="label">Last Updated</span>
                                    <span class="value">{{ $selectedParcel->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tab Content: Payment -->
                    @if($activeTab === 'payment')
                    <div class="tab-content active">
                        <div class="payment-section">
                            <!-- Payment Status Header -->
                            <div class="payment-status-header mb-4">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div>
                                        <h6 class="fw-bold mb-1">Payment Status</h6>
                                        @php
                                        $paymentBadge = $this->getPaymentStatusBadge($selectedParcel->payment_status ?? 'pending');
                                        @endphp
                                        <span class="status-badge status-{{ $paymentBadge['color'] }}">
                                            <i class="{{ $paymentBadge['icon'] }} me-1"></i>
                                            {{ ucfirst(str_replace('_', ' ', $selectedParcel->payment_status ?? 'Pending')) }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <div class="payment-amount-large">
                                            <span class="text-muted">Total Amount</span>
                                            <h4 class="fw-bold text-success">KSh {{ number_format($selectedParcel->total_amount ?? 0, 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Button -->
                            @if($selectedParcel->payment_status !== 'paid')
                            <div class="text-center mb-4">
                                <button class="btn btn-primary btn-lg px-5" wire:click="openPaymentModal">
                                    <i class="bi bi-credit-card me-2"></i>
                                    Make Payment
                                </button>
                            </div>
                            @endif

                            <!-- Payment Breakdown -->
                            <div class="payment-breakdown mb-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-receipt me-2"></i>Payment Breakdown</h6>
                                <div class="breakdown-item">
                                    <span>Base Price</span>
                                    <span>KSh {{ number_format($selectedParcel->base_price ?? 0, 2) }}</span>
                                </div>
                                @if($selectedParcel->weight_charge)
                                <div class="breakdown-item">
                                    <span>Weight Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->weight_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->distance_charge)
                                <div class="breakdown-item">
                                    <span>Distance Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->distance_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->insurance_charge)
                                <div class="breakdown-item">
                                    <span>Insurance Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->insurance_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->special_handling_charge)
                                <div class="breakdown-item">
                                    <span>Special Handling</span>
                                    <span>KSh {{ number_format($selectedParcel->special_handling_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->tax_amount)
                                <div class="breakdown-item">
                                    <span>Tax</span>
                                    <span>KSh {{ number_format($selectedParcel->tax_amount, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel->discount_amount)
                                <div class="breakdown-item text-success">
                                    <span>Discount</span>
                                    <span>-KSh {{ number_format($selectedParcel->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                <div class="breakdown-total">
                                    <span class="fw-bold">Total</span>
                                    <span class="fw-bold text-primary">KSh {{ number_format($selectedParcel->total_amount ?? 0, 2) }}</span>
                                </div>
                            </div>

                            <!-- Payment History -->
                            @if($selectedParcel->payments && $selectedParcel->payments->count() > 0)
                            <div class="payment-history mt-4">
                                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-2"></i>Payment History</h6>
                                @foreach($selectedParcel->payments as $payment)
                                <div class="payment-history-item">
                                    <div class="payment-history-icon">
                                        <i class="bi bi-credit-card"></i>
                                    </div>
                                    <div class="payment-history-info">
                                        <span class="payment-history-amount">KSh {{ number_format($payment->amount ?? 0, 2) }}</span>
                                        <span class="payment-history-method">{{ ucfirst($payment->payment_method ?? 'N/A') }}</span>
                                        <span class="payment-history-date">{{ $payment->created_at->format('M d, Y h:i A') }}</span>
                                        @if($payment->mpesa_receipt_number)
                                        <span class="payment-history-method">Receipt: {{ $payment->mpesa_receipt_number }}</span>
                                        @endif
                                    </div>
                                    <div class="payment-history-status">
                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status ?? 'pending') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Tab Content: Tracking -->
                    @if($activeTab === 'tracking')
                    <div class="tab-content active">
                        <div class="tracking-timeline">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-map me-2"></i>Tracking History
                            </h6>
                            @php
                            $statuses = $selectedParcel->statuses->reverse();
                            @endphp
                            @if($statuses->count() > 0)
                            <div class="timeline">
                                @foreach($statuses as $index => $status)
                                <div class="timeline-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="timeline-marker">
                                        <div class="marker-dot {{ $loop->first ? 'active' : '' }}">
                                            <i class="{{ $this->getStatusIcon($status->status) }}"></i>
                                        </div>
                                        @if(!$loop->last)
                                        <div class="marker-line"></div>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-header">
                                            <span class="timeline-status">{{ $this->getStatusLabel($status->status) }}</span>
                                            <span class="timeline-date">{{ $status->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        @if($status->notes)
                                        <p class="timeline-description">{{ $status->notes }}</p>
                                        @endif
                                        @if($status->driver)
                                        <div class="timeline-driver">
                                            <i class="bi bi-person-badge"></i>
                                            Driver: {{ $status->driver->name ?? 'N/A' }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-clock-history" style="font-size: 3rem; color: var(--text-light);"></i>
                                <p class="text-muted mt-2">No tracking history available yet.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="closeParcelDetail">Close</button>
                    @if($selectedParcel->payment_status === 'paid')
                    <a href="{{ route('print-customer-receipt', $selectedParcel->id) }}" target="_blank" class="btn btn-primary">
                        <i class="bi bi-printer me-2"></i>Print Sticker
                    </a>
                    @endif
                </div>
            </div>
            <!-- Payment Overlay -->
            @if($showPaymentOverlay)
            <div class="payment-overlay active">
                <div class="spinner-container">
                    <div id="paymentStatusIcon">
                        @if($paymentOverlayStatus === 'loading')
                        <div class="spinner-border" role="status"></div>
                        @elseif($paymentOverlayStatus === 'success')
                        <i class="bi bi-check-circle-fill status-icon success"></i>
                        @elseif($paymentOverlayStatus === 'failed')
                        <i class="bi bi-x-circle-fill status-icon failed"></i>
                        @elseif($paymentOverlayStatus === 'waiting')
                        <i class="bi bi-phone status-icon waiting"></i>
                        @endif
                    </div>
                    <h5 class="mt-3">{{ $paymentOverlayTitle }}</h5>
                    <p class="text-muted">{{ $paymentOverlayMessage }}</p>
                    @if($paymentOverlayStatus === 'failed')
                    <div class="mt-3">
                        <button class="btn btn-primary" wire:click="retryPayment">
                            <i class="bi bi-arrow-repeat me-2"></i>Try Again
                        </button>
                        <button class="btn btn-outline-secondary ms-2" wire:click="closePaymentOverlay">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                    @endif
                    @if($paymentOverlayStatus === 'success')
                    <div class="mt-3">
                        <button class="btn btn-success" wire:click="closePaymentOverlay">
                            <i class="bi bi-check-circle me-2"></i>Done
                        </button>
                    </div>
                    @endif
                    @if($paymentOverlayStatus === 'waiting')
                    <div class="mt-3">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            <span>Waiting for PIN...</span>
                        </div>
                        <button class="btn btn-outline-secondary mt-2" wire:click="closePaymentOverlay">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Payment Modal -->
            @if($showPaymentModal)
            <div class="modal-overlay" wire:click.self="closePaymentModal">
                <div class="modal-content modal-lg animate-fade-up" style="max-width: 600px;">
                    <div class="modal-header">
                        <div class="modal-header-info">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-credit-card me-2 text-primary"></i>
                                Make Payment
                            </h5>
                            <span class="parcel-id-display">{{ $selectedParcel?->parcel_id ?? 'N/A' }}</span>
                        </div>
                        <div class="modal-header-actions">
                            <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                        </div>
                    </div>

                    <div class="modal-body">
                        <!-- Payment Response Alert -->
                        @if($paymentResponse)
                        <div class="alert alert-{{ $paymentResponseType === 'success' ? 'success' : ($paymentResponseType === 'warning' ? 'warning' : ($paymentResponseType === 'info' ? 'info' : 'danger')) }} alert-dismissible fade show mb-3" role="alert">
                            <i class="bi {{ $paymentResponseType === 'success' ? 'bi-check-circle-fill' : ($paymentResponseType === 'warning' ? 'bi-exclamation-triangle-fill' : ($paymentResponseType === 'info' ? 'bi-info-circle-fill' : 'bi-x-circle-fill')) }} me-2"></i>
                            {{ $paymentResponseMessage }}
                            <button type="button" class="btn-close" wire:click="$set('paymentResponse', null)"></button>
                        </div>
                        @endif

                        <!-- M-Pesa Status -->
                        @if($showMpesaStatus)
                        <div class="alert alert-{{ $paymentStatusType }} mb-3">
                            <i class="bi {{ $paymentStatusIcon }} me-2"></i>
                            {{ $paymentStatusMessage }}
                        </div>
                        @endif

                        <!-- Payment Details -->
                        <div class="payment-summary mb-4">
                            <div class="payment-total-card">
                                <div class="payment-total-label">Total Amount</div>
                                <div class="payment-total-value">KSh {{ number_format($selectedParcel?->total_amount ?? 0, 2) }}</div>
                                <div class="payment-status-label">
                                    @php
                                    $paymentBadge = $this->getPaymentStatusBadge($selectedParcel?->payment_status ?? 'pending');
                                    @endphp
                                    Status:
                                    <span class="badge bg-{{ $paymentBadge['color'] }}">
                                        {{ ucfirst(str_replace('_', ' ', $selectedParcel?->payment_status ?? 'Pending')) }}
                                    </span>
                                </div>
                            </div>
                            <div class="payment-breakdown">
                                <h6 class="fw-bold mb-2">Payment Breakdown</h6>
                                <div class="breakdown-item">
                                    <span>Base Price</span>
                                    <span>KSh {{ number_format($selectedParcel?->base_price ?? 0, 2) }}</span>
                                </div>
                                @if($selectedParcel?->weight_charge)
                                <div class="breakdown-item">
                                    <span>Weight Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->weight_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel?->distance_charge)
                                <div class="breakdown-item">
                                    <span>Distance Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->distance_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel?->insurance_charge)
                                <div class="breakdown-item">
                                    <span>Insurance Charge</span>
                                    <span>KSh {{ number_format($selectedParcel->insurance_charge, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel?->tax_amount)
                                <div class="breakdown-item">
                                    <span>Tax</span>
                                    <span>KSh {{ number_format($selectedParcel->tax_amount, 2) }}</span>
                                </div>
                                @endif
                                @if($selectedParcel?->discount_amount)
                                <div class="breakdown-item text-success">
                                    <span>Discount</span>
                                    <span>-KSh {{ number_format($selectedParcel->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                <div class="breakdown-total">
                                    <span class="fw-bold">Total</span>
                                    <span class="fw-bold text-primary">KSh {{ number_format($selectedParcel?->total_amount ?? 0, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        @if($selectedParcel?->payment_status !== 'paid')
                        <form wire:submit.prevent="processPayment">
                            <div class="mb-3">
                                <label class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="paymentAmount" min="1" step="1" required readonly>
                                <small class="text-muted">Enter the amount you want to pay</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" wire:model="paymentMethod">
                                    <option value="mpesa">M-PESA</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" wire:model="paymentPhone" placeholder="0712345678" required>
                                <small class="text-muted">Enter the M-PESA registered phone number</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes (optional)</label>
                                <input type="text" class="form-control" wire:model="paymentNotes" placeholder="Any notes about this payment">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg" wire:loading.attr="disabled">
                                    <span wire:loading.remove>Pay KSh {{ number_format($paymentAmount ?? 0, 2) }}</span>
                                    <span wire:loading><span class="spinner-border spinner-border-sm me-2"></span>Processing...</span>
                                </button>
                            </div>
                        </form>

                        <!-- Manual Payment Instructions -->
                        <div class="manual-payment-card mt-4">
                            <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Manual Payment</h6>
                            <p class="text-muted small">Follow these steps to pay manually via M-PESA Paybill</p>
                            <div class="manual-steps">
                                <div class="manual-step">
                                    <span class="step-num">1</span>
                                    <span>Go to M-PESA <strong>Lipa Na M-PESA</strong> &amp; select <strong>Paybill</strong></span>
                                </div>
                                <div class="manual-step">
                                    <span class="step-num">2</span>
                                    <span>Enter Business number <strong class="text-primary">4563911</strong></span>
                                </div>
                                <div class="manual-step">
                                    <span class="step-num">3</span>
                                    <span>Enter Account number <strong class="text-primary">{{ $selectedParcel?->parcel_id ?? 'N/A' }}</strong></span>
                                </div>
                                <div class="manual-step">
                                    <span class="step-num">4</span>
                                    <span>Enter Amount <strong class="text-primary">KSh {{ number_format($selectedParcel?->total_amount ?? 0, 2) }}</strong></span>
                                </div>
                                <div class="manual-step">
                                    <span class="step-num">5</span>
                                    <span>Complete payment on your phone</span>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary w-100 mt-3" wire:click="checkPaymentStatus">
                                <i class="bi bi-arrow-repeat me-2"></i>
                                Check Payment Status
                            </button>
                        </div>
                        @else
                        <div class="payment-completed">
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                <h5 class="fw-bold mt-3">Payment Completed</h5>
                                <p class="text-muted">This parcel has been paid for successfully.</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closePaymentModal">Close</button>
                        @if($selectedParcel?->payment_status === 'paid')
                        <a href="{{ route('print-customer-receipt', $selectedParcel->id) }}" target="_blank" class="btn btn-primary">
                            <i class="bi bi-printer me-2"></i>Print Sticker
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
        @endif

        <!-- Payment Overlay -->


        <!-- Styles -->
        <style>
            /* ===== DASHBOARD ===== */
            .customer-dashboard {
                background: var(--light-bg);
                min-height: 100vh;
                padding: 40px 0 60px;
                margin-top: 30px;
            }

            /* ===== STAT CARDS ===== */
            .stat-card {
                background: white;
                border-radius: 16px;
                padding: 18px 20px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
                transition: var(--transition);
                height: 100%;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                flex-shrink: 0;
            }

            .bg-primary-light {
                background: var(--primary-light);
            }

            .bg-warning-light {
                background: #fff8e1;
            }

            .bg-success-light {
                background: #e8f5e9;
            }

            .bg-danger-light {
                background: #ffebee;
            }

            .stat-info {
                flex: 1;
            }

            .stat-number {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0;
                line-height: 1.2;
            }

            .stat-label {
                font-size: 0.8rem;
                color: var(--text-light);
                font-weight: 500;
            }

            /* ===== FILTERS ===== */
            .filters-section {
                background: white;
                border-radius: 16px;
                padding: 20px;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
            }

            .search-box {
                position: relative;
            }

            .search-box i {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-light);
                font-size: 1.1rem;
            }

            .search-box .form-control {
                padding-left: 45px;
                border-radius: 12px;
                border: 2px solid var(--border-color);
            }

            .search-box .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            }

            .form-select-lg {
                border-radius: 12px;
                border: 2px solid var(--border-color);
                padding: 12px 16px;
                font-size: 0.95rem;
            }

            .form-select-lg:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            }

            /* ===== PARCELS TABLE ===== */
            .parcels-table {
                background: white;
                border-radius: 16px;
                overflow: hidden;
                border-collapse: separate;
                border-spacing: 0;
                box-shadow: var(--shadow-sm);
                border: 1px solid var(--border-color);
                width: 100%;
                margin-bottom: 0;
            }

            .parcels-table thead {
                background: var(--light-bg);
            }

            .parcels-table thead th {
                padding: 14px 16px;
                font-weight: 600;
                font-size: 0.8rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--text-light);
                border-bottom: 2px solid var(--border-color);
                white-space: nowrap;
            }

            .parcels-table tbody tr {
                transition: var(--transition);
                cursor: pointer;
            }

            .parcels-table tbody tr:hover {
                background: var(--primary-light);
            }

            .parcels-table tbody td {
                padding: 14px 16px;
                vertical-align: middle;
                border-bottom: 1px solid var(--border-color);
                font-size: 0.9rem;
            }

            .parcels-table tbody tr:last-child td {
                border-bottom: none;
            }

            .parcel-id-badge {
                font-weight: 600;
                color: var(--primary-color);
                font-size: 0.85rem;
                background: var(--primary-light);
                padding: 4px 10px;
                border-radius: 6px;
            }

            .route-info {
                display: flex;
                align-items: center;
                gap: 4px;
                flex-wrap: wrap;
            }

            .route-info .town-name {
                font-weight: 500;
                font-size: 0.85rem;
            }

            .route-info i {
                font-size: 0.8rem;
            }

            .contact-info .contact-name {
                font-weight: 500;
                font-size: 0.85rem;
            }

            .parcel-type-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                background: #f0f0f0;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 0.8rem;
                font-weight: 500;
                color: var(--text-dark);
            }

            .parcel-amount {
                font-weight: 600;
                color: var(--text-dark);
                font-size: 0.9rem;
            }

            .parcel-date {
                font-size: 0.8rem;
                color: var(--text-light);
                white-space: nowrap;
            }

            /* ===== STATUS BADGES ===== */
            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 4px 12px;
                border-radius: 30px;
                font-size: 0.75rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .status-secondary {
                background: #e9ecef;
                color: #495057;
            }

            .status-info {
                background: #d1ecf1;
                color: #0c5460;
            }

            .status-primary {
                background: var(--primary-light);
                color: var(--primary-color);
            }

            .status-warning {
                background: #fff3cd;
                color: #856404;
            }

            .status-success {
                background: #d4edda;
                color: #155724;
            }

            .status-danger {
                background: #f8d7da;
                color: #721c24;
            }

            .btn-view {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 12px;
                border-radius: 6px;
                background: var(--primary-color);
                color: white;
                border: none;
                font-size: 0.8rem;
                font-weight: 500;
                transition: var(--transition);
                cursor: pointer;
            }

            .btn-view:hover {
                background: var(--primary-dark);
                transform: translateY(-1px);
            }

            /* ===== EMPTY STATE ===== */
            .empty-state-icon {
                font-size: 6rem;
                color: var(--border-color);
            }

            .empty-state-icon i {
                display: inline-block;
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-15px);
                }
            }

            /* ===== MODAL ===== */
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
                overflow-y: auto;
            }

            .modal-content {
                background: white;
                border-radius: 24px;
                max-width: 1000px;
                width: 100%;
                max-height: 92vh;
                overflow: hidden;
                animation: slideUp 0.3s ease-out;
                display: flex;
                flex-direction: column;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(50px) scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            /* ===== MODAL HEADER ===== */
            .modal-header {
                padding: 20px 28px;
                border-bottom: 1px solid var(--border-color);
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 12px;
                flex-shrink: 0;
            }

            .modal-header-info {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-wrap: wrap;
            }

            .modal-header-info .modal-title {
                margin: 0;
                font-size: 1.1rem;
            }

            .parcel-id-display {
                font-size: 0.8rem;
                color: var(--text-light);
                background: var(--light-bg);
                padding: 2px 12px;
                border-radius: 30px;
                font-weight: 500;
            }

            .modal-header-actions {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            /* ===== MODAL TABS ===== */
            .modal-tabs {
                display: flex;
                gap: 4px;
                padding: 0 28px;
                border-bottom: 1px solid var(--border-color);
                background: var(--light-bg);
                flex-shrink: 0;
            }

            .tab-btn {
                padding: 12px 20px;
                border: none;
                background: transparent;
                font-weight: 500;
                font-size: 0.9rem;
                color: var(--text-light);
                transition: var(--transition);
                cursor: pointer;
                border-bottom: 3px solid transparent;
                position: relative;
            }

            .tab-btn:hover {
                color: var(--text-dark);
                background: rgba(0, 0, 0, 0.02);
            }

            .tab-btn.active {
                color: var(--primary-color);
                border-bottom-color: var(--primary-color);
                background: transparent;
            }

            .tab-btn i {
                font-size: 1rem;
            }

            /* ===== MODAL BODY ===== */
            .modal-body {
                padding: 28px;
                overflow-y: auto;
                flex: 1;
            }

            .tab-content {
                display: none;
                animation: fadeIn 0.3s ease-out;
            }

            .tab-content.active {
                display: block;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* ===== ROUTE SUMMARY ===== */
            .route-summary {
                background: var(--light-bg);
                border-radius: 16px;
                padding: 20px;
            }

            .route-points {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 20px;
                flex-wrap: wrap;
            }

            .route-point-detail {
                display: flex;
                align-items: center;
                gap: 14px;
                flex: 1;
                min-width: 180px;
            }

            .route-point-detail .point-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                color: white;
                flex-shrink: 0;
            }

            .route-point-detail .point-icon.bg-success {
                background: var(--primary-color);
            }

            .route-point-detail .point-icon.bg-danger {
                background: #dc3545;
            }

            .route-point-detail .point-info {
                display: flex;
                flex-direction: column;
            }

            .route-point-detail .point-label {
                font-size: 0.7rem;
                color: var(--text-light);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .route-point-detail .point-value {
                font-weight: 600;
                font-size: 1rem;
                color: var(--text-dark);
            }

            .route-point-detail .point-person {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .route-line {
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: var(--primary-color);
                padding: 0 8px;
            }

            /* ===== DETAIL GRID ===== */
            .detail-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 20px;
            }

            .detail-section {
                background: var(--light-bg);
                border-radius: 12px;
                padding: 16px 18px;
            }

            .detail-section h6 {
                font-size: 0.85rem;
                margin-bottom: 12px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 6px 0;
                border-bottom: 1px solid var(--border-color);
                font-size: 0.9rem;
            }

            .detail-row:last-child {
                border-bottom: none;
            }

            .detail-row .label {
                color: var(--text-light);
                font-weight: 500;
            }

            .detail-row .value {
                color: var(--text-dark);
                font-weight: 500;
                text-align: right;
            }

            /* ===== PAYMENT SECTION ===== */
            .payment-section {
                padding: 4px 0;
            }

            .payment-status-header {
                background: var(--light-bg);
                border-radius: 16px;
                padding: 16px 20px;
                border: 1px solid var(--border-color);
            }

            .payment-amount-large h4 {
                margin: 0;
            }

            .payment-breakdown {
                background: var(--light-bg);
                border-radius: 16px;
                padding: 20px;
                border: 1px solid var(--border-color);
            }

            .breakdown-item {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid var(--border-color);
                font-size: 0.9rem;
                color: var(--text-dark);
            }

            .breakdown-item:last-child {
                border-bottom: none;
            }

            .breakdown-total {
                display: flex;
                justify-content: space-between;
                padding: 12px 0 0;
                border-top: 2px solid var(--border-color);
                margin-top: 8px;
                font-size: 1.1rem;
            }

            .payment-method-card {
                background: white;
                border: 2px solid var(--border-color);
                border-radius: 16px;
                padding: 20px;
                height: 100%;
            }

            .manual-payment-card {
                background: white;
                border: 2px solid var(--border-color);
                border-radius: 16px;
                padding: 20px;
                height: 100%;
            }

            .manual-steps {
                margin-top: 12px;
            }

            .manual-step {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 6px 0;
                font-size: 0.9rem;
                color: var(--text-dark);
                border-bottom: 1px solid #f1f5f9;
            }

            .manual-step:last-child {
                border-bottom: none;
            }

            .manual-step .step-num {
                background: var(--primary-light);
                color: var(--primary-color);
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
                font-weight: 700;
                flex-shrink: 0;
            }

            .payment-history-item {
                display: flex;
                align-items: center;
                gap: 16px;
                padding: 12px 0;
                border-bottom: 1px solid var(--border-color);
            }

            .payment-history-item:last-child {
                border-bottom: none;
            }

            .payment-history-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: var(--primary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                color: var(--primary-color);
                flex-shrink: 0;
            }

            .payment-history-info {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .payment-history-amount {
                font-weight: 600;
                color: var(--text-dark);
            }

            .payment-history-method {
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .payment-history-date {
                font-size: 0.7rem;
                color: var(--text-light);
            }

            .payment-completed {
                background: var(--primary-light);
                border-radius: 16px;
                border: 2px solid var(--primary-color);
            }

            /* ===== TRACKING TIMELINE ===== */
            .tracking-timeline {
                padding: 4px 0;
            }

            .timeline {
                display: flex;
                flex-direction: column;
                gap: 0;
            }

            .timeline-item {
                display: flex;
                gap: 16px;
                padding: 4px 0;
                position: relative;
            }

            .timeline-marker {
                display: flex;
                flex-direction: column;
                align-items: center;
                flex-shrink: 0;
                padding-top: 4px;
            }

            .marker-dot {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                background: var(--border-color);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--text-light);
                font-size: 0.8rem;
                transition: var(--transition);
                border: 2px solid var(--border-color);
            }

            .marker-dot.active {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            .marker-line {
                width: 2px;
                flex: 1;
                background: var(--border-color);
                margin: 4px 0;
                min-height: 20px;
            }

            .timeline-content {
                flex: 1;
                padding-bottom: 12px;
            }

            .timeline-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
            }

            .timeline-status {
                font-weight: 600;
                font-size: 0.95rem;
                color: var(--text-dark);
            }

            .timeline-date {
                font-size: 0.75rem;
                color: var(--text-light);
            }

            .timeline-description {
                font-size: 0.85rem;
                color: var(--text-light);
                margin: 4px 0 0;
            }

            .timeline-driver {
                font-size: 0.8rem;
                color: var(--text-light);
                margin: 4px 0 0;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .timeline-driver i {
                color: var(--primary-color);
            }

            /* ===== MODAL FOOTER ===== */
            .modal-footer {
                padding: 16px 28px;
                border-top: 1px solid var(--border-color);
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                background: var(--light-bg);
                border-radius: 0 0 24px 24px;
                flex-shrink: 0;
            }

            /* ===== PAYMENT OVERLAY ===== */
            .payment-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(8px);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 99999;
                padding: 1.5rem;
            }

            .payment-overlay.active {
                display: flex;
            }

            .spinner-container {
                background: white;
                padding: 2.8rem 2.5rem;
                border-radius: 40px;
                max-width: 420px;
                width: 100%;
                text-align: center;
                box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.4);
            }

            .spinner-container .spinner-border {
                width: 3.8rem;
                height: 3.8rem;
                color: #1a4d33;
            }

            .spinner-container .status-icon {
                font-size: 4.2rem;
            }

            .spinner-container .status-icon.success {
                color: #22c55e;
            }

            .spinner-container .status-icon.failed {
                color: #ef4444;
            }

            .spinner-container .status-icon.waiting {
                color: #f59e0b;
            }

            /* ===== RESPONSIVE ===== */
            @media (max-width: 992px) {
                .detail-grid {
                    grid-template-columns: 1fr;
                }

                .modal-tabs {
                    padding: 0 16px;
                    overflow-x: auto;
                    flex-wrap: nowrap;
                }

                .tab-btn {
                    padding: 10px 14px;
                    font-size: 0.8rem;
                    white-space: nowrap;
                }
            }

            @media (max-width: 768px) {
                .customer-dashboard {
                    padding: 20px 0 40px;
                    margin-top: 0;
                }

                .modal-content {
                    max-height: 95vh;
                    margin: 10px;
                    border-radius: 16px;
                }

                .modal-header {
                    padding: 16px 18px;
                    flex-direction: column;
                    align-items: flex-start;
                }

                .modal-header-actions {
                    width: 100%;
                    justify-content: space-between;
                }

                .modal-body {
                    padding: 16px;
                }

                .modal-footer {
                    padding: 12px 16px;
                    flex-wrap: wrap;
                }

                .modal-footer .btn {
                    flex: 1;
                    min-width: 120px;
                }

                .route-points {
                    flex-direction: column;
                    gap: 12px;
                }

                .route-point-detail {
                    width: 100%;
                }

                .route-line {
                    transform: rotate(90deg);
                    padding: 4px 0;
                }

                .parcels-table thead {
                    display: none;
                }

                .parcels-table tbody tr {
                    display: block;
                    padding: 16px;
                    border-bottom: 2px solid var(--border-color);
                }

                .parcels-table tbody td {
                    display: flex;
                    justify-content: space-between;
                    padding: 8px 0;
                    border-bottom: 1px solid var(--border-color);
                }

                .parcels-table tbody td:last-child {
                    border-bottom: none;
                }

                .parcels-table tbody td::before {
                    content: attr(data-label);
                    font-weight: 600;
                    color: var(--text-light);
                    font-size: 0.8rem;
                }

                .stat-card {
                    padding: 14px 16px;
                }

                .stat-number {
                    font-size: 1.2rem;
                }

                .filters-section {
                    padding: 16px;
                }

                .route-info {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 2px;
                }

                .route-info i {
                    transform: rotate(90deg);
                }

                .parcel-id-badge {
                    font-size: 0.75rem;
                }

                .btn-view {
                    padding: 2px 10px;
                    font-size: 0.75rem;
                }
            }

            @media (max-width: 576px) {
                .payment-total-value {
                    font-size: 1.5rem;
                }

                .detail-row {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 2px;
                }

                .detail-row .value {
                    text-align: left;
                }

                .timeline-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    </div>
</div>