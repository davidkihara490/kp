<div>
    <div>
        <div class="dashboard-section">
            <!-- Header Section -->
            <div class="section-header">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="parcel-avatar">
                            <div class="avatar-placeholder-lg bg-primary rounded-circle">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="section-title mb-1">
                                Parcel #{{ $parcel->parcel_number }}
                                <small class="text-muted fs-6">({{ ucfirst($parcel->parcel_type) }})</small>
                            </h3>
                            <p class="section-subtitle mb-2">
                                <i class="bi bi-person-up me-1"></i>{{ $parcel->sender_name }} •
                                <i class="bi bi-person-down me-1 ms-2"></i>{{ $parcel->receiver_name }} •
                                <i class="bi bi-calendar me-1 ms-2"></i>{{ $parcel->created_at->format('M d, Y') }}
                            </p>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                @php
                                $statusBadge = $this->getStatusBadge($parcel->current_status ?? 'pending');
                                $paymentBadge = $this->getPaymentStatusBadge($parcel->payment_status);
                                @endphp
                                <span class="status-badge status-{{ $statusBadge['color'] }}">
                                    <i class="bi {{ $statusBadge['icon'] }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $parcel->current_status ?? 'pending')) }}
                                </span>
                                <span class="status-badge status-{{ $paymentBadge['color'] }}">
                                    <i class="bi {{ $paymentBadge['icon'] }} me-1"></i>
                                    Payment: {{ ucfirst(str_replace('_', ' ', $parcel->payment_status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('partners.parcels.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back to Parcels
                    </a>
                    <a href="{{ route('partners.parcels.edit', $parcelId) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i>
                        Edit
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear me-2"></i>
                            Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <button class="dropdown-item" onclick="window.print()">
                                    <i class="bi bi-printer text-secondary me-2"></i>
                                    Print Label
                                </button>
                            </li>
                            <li>
                                <button class="dropdown-item" wire:click="generateReceipt">
                                    <i class="bi bi-receipt text-info me-2"></i>
                                    Generate Receipt
                                </button>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @if($parcel->current_status !== 'assigned' && !$parcel->driver_id)
                            <li>
                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                    <i class="bi bi-person-plus text-success me-2"></i>
                                    Assign Driver
                                </button>
                            </li>
                            @endif
                            @if($parcel->current_status !== 'picked_up')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('picked_up')">
                                    <i class="bi bi-box-seam text-primary me-2"></i>
                                    Mark as Picked Up
                                </button>
                            </li>
                            @endif
                            @if($parcel->current_status !== 'in_transit')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('in_transit')">
                                    <i class="bi bi-truck text-warning me-2"></i>
                                    Mark as In Transit
                                </button>
                            </li>
                            @endif
                            @if($parcel->current_status !== 'out_for_delivery')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('out_for_delivery')">
                                    <i class="bi bi-bicycle text-info me-2"></i>
                                    Out for Delivery
                                </button>
                            </li>
                            @endif
                            @if($parcel->current_status !== 'delivered')
                            <li>
                                <button class="dropdown-item" wire:click="updateStatus('delivered')">
                                    <i class="bi bi-check-circle text-success me-2"></i>
                                    Mark as Delivered
                                </button>
                            </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <button class="dropdown-item text-danger"
                                    onclick="confirmCancel('{{ $parcel->parcel_number }}')">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Cancel Parcel
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">KES {{ number_format($parcel->total_amount, 2) }}</div>
                            <div class="stat-label">Total Amount</div>
                            @php
                            $totalPaid = $parcel->payments->where('status', 'completed')->sum('amount');
                            $remainingBalance = $parcel->total_amount - $totalPaid;
                            @endphp
                            <div class="stat-subtext">
                                Paid: KES {{ number_format($totalPaid, 2) }} |
                                Balance: KES {{ number_format($remainingBalance, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon distance">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($parcel->weight, 2) }} {{ $parcel->weight_unit }}</div>
                            <div class="stat-label">Weight</div>
                            @if($parcel->length && $parcel->width && $parcel->height)
                            <div class="stat-subtext">{{ $parcel->length }}×{{ $parcel->width }}×{{ $parcel->height }} {{ $parcel->dimension_unit }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon rating">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $parcel->driver?->full_name ?? 'Not Assigned' }}</div>
                            <div class="stat-label">Driver</div>
                            @if($parcel->driver)
                            <div class="stat-subtext">{{ $parcel->driver->phone_number }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stat-card">
                        <div class="stat-icon experience">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ $parcel->senderTown->name ?? 'N/A' }}</div>
                            <div class="stat-label">From → To</div>
                            <div class="stat-subtext">{{ $parcel->receiverTown->name?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="tabs-navigation mb-4">
                <ul class="nav nav-tabs" id="parcelTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}"
                            wire:click="changeTab('overview')"
                            type="button">
                            <i class="bi bi-info-circle me-2"></i>
                            Overview
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'payments' ? 'active' : '' }}"
                            wire:click="changeTab('payments')"
                            type="button">
                            <i class="bi bi-cash-stack me-2"></i>
                            Payments
                            <span class="badge bg-secondary ms-2">{{ $parcel->payments->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'tracking' ? 'active' : '' }}"
                            wire:click="changeTab('tracking')"
                            type="button">
                            <i class="bi bi-map me-2"></i>
                            Tracking
                            <span class="badge bg-secondary ms-2">{{$parcel->statuses->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                @if($activeTab === 'overview')
                <div class="row">
                    <!-- Left Column - Parcel Details -->
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Parcel Details
                                </h5>
                                @if($remainingBalance > 0)
                                <button class="btn btn-sm btn-primary" wire:click="openPaymentModal">
                                    <i class="bi bi-cash me-2"></i>
                                    Make Payment
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="border-bottom pb-2 mb-3">Sender Information</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Name:</th>
                                                <td><strong>{{ $parcel->sender_name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>
                                                    <a href="tel:{{ $parcel->sender_phone }}" class="text-decoration-none">
                                                        {{ $parcel->sender_phone }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @if($parcel->sender_email)
                                            <tr>
                                                <th>Email:</th>
                                                <td>
                                                    <a href="mailto:{{ $parcel->sender_email }}" class="text-decoration-none">
                                                        {{ $parcel->sender_email }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $parcel->sender_address }}</td>
                                            </tr>

                                            <tr>
                                                <th>PickUp Town</th>
                                                <td>{{ $parcel->senderTown->name }}</td>

                                            </tr>
                                            <tr>
                                                <th>PickUp Point</th>
                                                <td>{{ $parcel->senderPickUpDropOffPoint->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>PickUp Point Info</th>
                                                <td>{{ $parcel->senderPickUpDropOffPoint->address }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="border-bottom pb-2 mb-3">Receiver Information</h6>
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Name:</th>
                                                <td><strong>{{ $parcel->receiver_name }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>
                                                    <a href="tel:{{ $parcel->receiver_phone }}" class="text-decoration-none">
                                                        {{ $parcel->receiver_phone }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @if($parcel->receiver_email)
                                            <tr>
                                                <th>Email:</th>
                                                <td>
                                                    <a href="mailto:{{ $parcel->receiver_email }}" class="text-decoration-none">
                                                        {{ $parcel->receiver_email }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $parcel->receiver_address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Drop Off Town</th>
                                                <td>{{ $parcel->receiverTown->name }}</td>

                                            </tr>
                                            <tr>
                                                <th>Drop Off Point</th>
                                                <td>{{ $parcel->deliveryStation->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Drop Off Point Info</th>
                                                <td>{{ $parcel->deliveryStation->address }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h6 class="border-bottom pb-2 mb-3">Parcel Information</h6>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Type:</th>
                                                <td>{{ ucfirst($parcel->parcel_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Package Type:</th>
                                                <td>{{ ucfirst($parcel->package_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Weight:</th>
                                                <td>{{ $parcel->weight }} {{ $parcel->weight_unit }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Dimensions:</th>
                                                <td>
                                                    @if($parcel->length && $parcel->width && $parcel->height)
                                                    {{ $parcel->length }} × {{ $parcel->width }} × {{ $parcel->height }} {{ $parcel->dimension_unit }}
                                                    @else
                                                    N/A
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Declared Value:</th>
                                                <td>KES {{ number_format($parcel->declared_value, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Insurance:</th>
                                                <td>
                                                    @if($parcel->insurance_required)
                                                    <span class="badge bg-success">Yes</span> (KES {{ number_format($parcel->insurance_amount, 2) }})
                                                    @else
                                                    <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-4">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Booking Type:</th>
                                                <td>{{ ucfirst($parcel->booking_type) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Booking Source:</th>
                                                <td>{{ ucfirst($parcel->booking_source) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Created:</th>
                                                <td>{{ $parcel->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($parcel->content_description)
                                <div class="mt-3">
                                    <h6 class="border-bottom pb-2 mb-3">Content Description</h6>
                                    <p class="mb-0">{{ $parcel->content_description }}</p>
                                </div>
                                @endif

                                @if($parcel->special_instructions)
                                <div class="mt-3">
                                    <h6 class="border-bottom pb-2 mb-3">Special Instructions</h6>
                                    <p class="mb-0">{{ $parcel->special_instructions }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-calculator me-2"></i>
                                    Price Breakdown
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th>Base Price:</th>
                                                <td class="text-end">KES {{ number_format($parcel->base_price, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Weight Charge:</th>
                                                <td class="text-end">KES {{ number_format($parcel->weight_charge, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Distance Charge:</th>
                                                <td class="text-end">KES {{ number_format($parcel->distance_charge, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Special Handling:</th>
                                                <td class="text-end">KES {{ number_format($parcel->special_handling_charge, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            @if($parcel->insurance_required)
                                            <tr>
                                                <th>Insurance:</th>
                                                <td class="text-end">KES {{ number_format($parcel->insurance_charge, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Tax (16%):</th>
                                                <td class="text-end">KES {{ number_format($parcel->tax_amount, 2) }}</td>
                                            </tr>
                                            @if($parcel->discount_amount > 0)
                                            <tr>
                                                <th>Discount:</th>
                                                <td class="text-end text-success">-KES {{ number_format($parcel->discount_amount, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr class="border-top">
                                                <th class="fw-bold">Total:</th>
                                                <td class="text-end fw-bold text-primary h5">KES {{ number_format($parcel->total_amount, 2) }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Important Information -->
                    <div class="col-lg-4">
                        <!-- Payment Summary -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-cash-stack me-2"></i>
                                    Payment Summary
                                </h5>
                                @if($remainingBalance > 0)
                                <button class="btn btn-sm btn-primary" wire:click="openPaymentModal">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    Pay
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                @php
                                $totalPaid = $parcel->payments->where('status', 'completed')->sum('amount');
                                $remainingBalance = $parcel->total_amount - $totalPaid;
                                @endphp

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Amount:</span>
                                        <span class="fw-bold">KES {{ number_format($parcel->total_amount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Total Paid:</span>
                                        <span class="fw-bold text-success">KES {{ number_format($totalPaid, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-2 border-top">
                                        <span class="fw-bold">Balance:</span>
                                        <span class="fw-bold {{ $remainingBalance > 0 ? 'text-danger' : 'text-success' }}">
                                            KES {{ number_format($remainingBalance, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Verify Code -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-truck me-2"></i>
                                    Driver Information
                                </h5>
                                @if(!$parcel->driver)
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Assign
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($parcel->driver)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar bg-success me-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $parcel->driver->full_name }}</h6>
                                        <small class="text-muted">{{ $parcel->driver->phone_number }}</small>
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Assigned At:</th>
                                        <td>{{ $parcel->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    </tr>
                                </table>


                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openDriverVerificationModal">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Verify Code
                                </button>

                                @else
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-truck display-6"></i>
                                    <p class="mt-2">No driver assigned yet</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!------Receive Parcel-------->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-truck me-2"></i>
                                    Parcel Receiving
                                </h5>
                                @if(!$parcel->driver)
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignDriverModal">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Receive Parcel
                                </button>
                                @endif
                            </div>
                            <div class="card-body">
                                @if($parcel->driver)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar bg-success me-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $parcel->driver->full_name }}</h6>
                                        <small class="text-muted">{{ $parcel->driver->phone_number }}</small>
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Assigned At:</th>
                                        <td>{{ $parcel->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    </tr>
                                </table>


                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="receiveParcelFromDriver">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Receive Parcel
                                </button>

                                @else
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-truck display-6"></i>
                                    <p class="mt-2">No driver assigned yet</p>
                                </div>
                                @endif
                            </div>
                        </div>



                        <!-- Pick Up Modal-->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-truck me-2"></i>
                                    Recipient Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar bg-success me-3">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">{{ $parcel->receiver_name }}</h6>
                                        <small class="text-muted">{{ $parcel->receiver_phone }}</small> <br>
                                        <small class="text-muted">{{ $parcel->receiver_email }}</small> <br>
                                        <small class="text-muted">{{ $parcel->receiver_notes }}</small> <br>
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th>Picked Up At:</th>
                                        <td>{{ $parcel->updated_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    </tr>
                                </table>


                                @if($parcel->parcelPickUp)
                                <button type="button" class="btn btn-sm btn-success" >
                                    <i class="bi bi-check me-1"></i>
                                    Picked
                                </button>

                                @else
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="openPickUpModal">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Pick Up
                                </button>

                                @endif
                            </div>
                        </div>



                        <!-- Recent Payments -->
                        @if($parcel->payments->where('status', 'completed')->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Recent Payments
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($parcel->payments->where('status', 'completed')->sortByDesc('created_at')->take(5) as $payment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="fw-medium">KES {{ number_format($payment->amount, 2) }}</span>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($payment->payment_method) }}</small>
                                                @if($payment->reference_number)
                                                <br>
                                                <small class="text-muted">Ref: {{ $payment->reference_number }}</small>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">Completed</span>
                                                <br>
                                                <small class="text-muted">{{ $payment->created_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @if($parcel->payments->count() > 5)
                                <div class="card-footer text-center">
                                    <button class="btn btn-sm btn-link" wire:click="changeTab('payments')">
                                        View All Payments
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Payments Tab -->
                @if($activeTab === 'payments')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-cash-stack me-2"></i>
                            Payment History
                        </h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: auto;"
                                wire:model.live="paymentStatusFilter">
                                <option value="">All Statuses</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                            @php
                            $totalPaid = $parcel->payments->where('status', 'completed')->sum('amount');
                            $remainingBalance = $parcel->total_amount - $totalPaid;
                            @endphp
                            @if($remainingBalance > 0)
                            <button class="btn btn-sm btn-primary" wire:click="openPaymentModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                New Payment
                            </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                        <td class="fw-bold">KES {{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            {{ ucfirst($payment->payment_method) }}
                                            @if($payment->payment_method === 'mpesa' && $payment->phone)
                                            <br>
                                            <small class="text-muted">{{ $payment->phone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment->reference_number)
                                            <code>{{ $payment->reference_number }}</code>
                                            @else
                                            <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                            $statusColors = [
                                            'completed' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger'
                                            ];
                                            $color = $statusColors[$payment->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info"
                                                onclick="alert('Receipt generation coming soon')"
                                                title="Print Receipt">
                                                <i class="bi bi-receipt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-secondary">
                                        <th colspan="1">Totals:</th>
                                        <th class="fw-bold">KES {{ number_format($payments->sum('amount'), 2) }}</th>
                                        <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $payments->links() }}
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-cash-stack display-1 text-muted"></i>
                            <h4 class="mt-3">No Payments Found</h4>
                            <p class="text-muted">No payments have been recorded for this parcel yet.</p>
                            @if($remainingBalance > 0)
                            <button class="btn btn-primary" wire:click="openPaymentModal">
                                <i class="bi bi-plus-circle me-2"></i>
                                Record Payment
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tracking Tab -->
                @if($activeTab === 'tracking')
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-map me-2"></i>
                            Tracking History
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($parcel->statuses->count() > 0)
                        <div class="timeline">
                            @foreach($parcel->statuses as $track)
                            <div class="timeline-item">
                                <div class="timeline-marker {{ $track->status === 'delivered' ? 'bg-success' : 'bg-primary' }}">
                                    <i class="bi {{ $this->getStatusBadge($track->status)['icon'] }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ ucfirst(str_replace('_', ' ', $track->status)) }}</h6>
                                        <small class="text-muted">{{ $track->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($track->notes)
                                    <p class="mb-0 text-muted">{{ $track->notes }}</p>
                                    @endif
                                    @if($track->otp && $track->otp_verified == false)
                                    <strong class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $track->otp }}
                                    </strong>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-map display-1 text-muted"></i>
                            <h4 class="mt-3">No Tracking Updates</h4>
                            <p class="text-muted">Tracking information will appear here as the parcel moves.</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Modal -->
        @if($showPaymentModal)
        <div class="modal fade show d-block" id="paymentModal" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-cash-stack me-2"></i>
                            Make Payment
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePaymentModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($showMpesaStatus)
                        <!-- M-Pesa Status Display -->
                        <div class="text-center py-4">
                            @if($paymentStatus === 'pending')
                            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <h5 class="mb-3">Processing Payment</h5>
                            <p class="text-muted">{{ $paymentStatusMessage }}</p>
                            <div class="alert alert-info mt-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Please check your phone and enter your M-Pesa PIN to complete the transaction.
                            </div>
                            <button class="btn btn-outline-secondary mt-2" wire:click="closePaymentModal">
                                Cancel
                            </button>
                            @elseif($paymentStatus === 'completed')
                            <div class="text-success mb-3">
                                <i class="bi bi-check-circle-fill" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="mb-3 text-success">Payment Successful!</h5>
                            <p class="text-muted">{{ $paymentStatusMessage }}</p>
                            <button class="btn btn-primary mt-3" wire:click="closePaymentModal">
                                <i class="bi bi-check me-2"></i>
                                Done
                            </button>
                            @elseif($paymentStatus === 'failed')
                            <div class="text-danger mb-3">
                                <i class="bi bi-x-circle-fill" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="mb-3 text-danger">Payment Failed</h5>
                            <p class="text-muted">{{ $paymentStatusMessage }}</p>
                            <div class="mt-3">
                                <button class="btn btn-primary me-2" wire:click="$set('showMpesaStatus', false)">
                                    <i class="bi bi-arrow-repeat me-2"></i>
                                    Try Again
                                </button>
                                <button class="btn btn-outline-secondary" wire:click="closePaymentModal">
                                    Cancel
                                </button>
                            </div>
                            @endif
                        </div>
                        @else
                        <!-- Payment Form -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong>KES {{ number_format($parcel->total_amount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span>Paid Amount:</span>
                                <strong class="text-success">KES {{ number_format($totalPaid, 2) }}</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Balance Due:</span>
                                <span class="text-danger">KES {{ number_format($remainingBalance, 2) }}</span>
                            </div>
                        </div>

                        <form wire:submit.prevent="processPayment">
                            <div class="mb-3">
                                <label class="form-label required">Payment Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">KES</span>
                                    <input type="number"
                                        class="form-control @error('paymentAmount') is-invalid @enderror"
                                        wire:model="paymentAmount"
                                        step="0.01"
                                        min="1"
                                        max="{{ $remainingBalance }}">
                                </div>
                                @error('paymentAmount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Payment Method</label>
                                <select class="form-select @error('paymentMethod') is-invalid @enderror"
                                    wire:model.live="paymentMethod">
                                    <option value="">Select payment method</option>
                                    <option value="mpesa">M-Pesa</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="wallet">Wallet</option>
                                </select>
                                @error('paymentMethod')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($paymentMethod === 'mpesa')
                            <div class="mb-3">
                                <label class="form-label required">Phone Number</label>
                                <input type="text"
                                    class="form-control @error('paymentPhone') is-invalid @enderror"
                                    wire:model="paymentPhone"
                                    placeholder="0712345678">
                                @error('paymentPhone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the M-Pesa registered phone number</small>
                            </div>
                            @endif

                            @if($paymentMethod && $paymentMethod !== 'mpesa')
                            <div class="mb-3">
                                <label class="form-label">Reference Number</label>
                                <input type="text"
                                    class="form-control"
                                    wire:model="paymentReference"
                                    placeholder="Transaction reference (optional)">
                                <small class="text-muted">Optional. Will be auto-generated if not provided.</small>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control"
                                    wire:model="paymentNotes"
                                    rows="2"
                                    placeholder="Any payment notes (optional)"></textarea>
                            </div>

                            @if($paymentMethod === 'mpesa')
                            <div class="alert alert-warning">
                                <i class="bi bi-info-circle me-2"></i>
                                Clicking "Pay with M-Pesa" will send an STK push to <strong>{{ $paymentPhone }}</strong>. Please check your phone to complete the payment.
                            </div>
                            @endif
                        </form>
                        @endif
                    </div>
                    @if(!$showMpesaStatus)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closePaymentModal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="processPayment" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="processPayment">
                                <i class="bi bi-{{ $paymentMethod === 'mpesa' ? 'phone' : 'check-circle' }} me-2"></i>
                                {{ $paymentMethod === 'mpesa' ? 'Pay with M-Pesa' : 'Record Payment' }}
                            </span>
                            <span wire:loading wire:target="processPayment">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Processing...
                            </span>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($showDriverVerificationModal)
        <div class="modal fade show d-block" id="paymentModal" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-cash-stack me-2"></i>
                            Assign Driver
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDriverVerificationModal"></button>
                    </div>
                    <form wire:submit.prevent="verifyDriverCode">
                        <div class="modal-body">
                            <!-- Driver Information -->
                            <div class="driver-info-card mb-4 p-3 bg-light rounded border">
                                <h6 class="text-primary mb-3">Selected Driver Information</h6>

                                @if($selectedDriver)
                                <div class="driver-details">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="driver-avatar me-3">
                                            @if($selectedDriver->profile_photo)
                                            <img src="{{ $selectedDriver->profile_photo }}" alt="{{ $selectedDriver->full_name }}" class="rounded-circle" width="50" height="50">
                                            @else
                                            <div class="avatar-placeholder bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px;">
                                                <i class="bi bi-person fs-4"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $selectedDriver->full_name }}</h5>
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-telephone me-1"></i> {{ $selectedDriver->phone }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mt-2 small">
                                        <div class="col-6">
                                            <span class="text-muted">Driver ID:</span>
                                            <span class="fw-medium ms-1">#{{ $selectedDriver->id }}</span>
                                        </div>
                                        <div class="col-6">
                                            <span class="text-muted">Status:</span>
                                            <span class="badge {{ $selectedDriver->is_available ? 'bg-success' : 'bg-warning' }} ms-1">
                                                {{ $selectedDriver->is_available ? 'Available' : 'Busy' }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($selectedDriver->vehicle)
                                    <div class="mt-2 pt-2 border-top">
                                        <div class="row">
                                            <div class="col-6">
                                                <span class="text-muted">Vehicle:</span>
                                                <span class="fw-medium ms-1">{{ $selectedDriver->vehicle->make ?? '' }} {{ $selectedDriver->vehicle->model ?? '' }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="text-muted">Plate:</span>
                                                <span class="fw-medium ms-1">{{ $selectedDriver->vehicle->plate_number ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="text-center py-3">
                                    <i class="bi bi-person-x fs-1 text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">No driver selected</p>
                                    <small class="text-muted">Please select a driver first</small>
                                </div>
                                @endif
                            </div>

                            <!-- Code Input Section -->
                            <div class="code-input-section mb-3">
                                <label for="driverCode" class="form-label fw-medium">
                                    <i class="bi bi-key me-1"></i>
                                    Enter Driver Assignment Code
                                </label>
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control form-control-lg"
                                        id="driverCode"
                                        wire:model="driver_code"
                                        placeholder="Enter 6-digit code"
                                        maxlength="6"
                                        autocomplete="off">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-shield-lock text-muted"></i>
                                    </span>
                                </div>
                                @error('driver_code')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter the 6-digit code sent to the driver's phone
                                </div>

                                @if($driverVerificationError)
                                <div class="text-danger small mt-1">
                                    {{ $driverVerificationError }}
                                </div>
                                @endif


                            </div>

                            <!-- Additional Info -->
                            <div class="alert alert-info py-2 small">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                This code is required to verify driver assignment. The driver should provide this code.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeDriverVerificationModal">
                                <i class="bi bi-x me-1"></i>
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary"
                                wire:click="verifyDriverCode">
                                <span wire:loading.remove wire:target="verifyDriverCode">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Verify & Issue
                                </span>
                                <span wire:loading wire:target="verifyDriverCode">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Verifying...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif


        @if($showPickUpModal)
        <div class="modal fade show d-block" id="pickupModal" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-box-seam me-2"></i>
                            Parcel Pickup Verification
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePickUpModal"></button>
                    </div>
                    <form wire:submit.prevent="verifyPickup">
                        <div class="modal-body">
                            <!-- Parcel Information Banner -->
                            <div class="parcel-info-banner bg-primary bg-opacity-10 p-3 rounded-3 mb-4 border-start border-primary border-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small">Parcel Number</span>
                                        <h4 class="mb-0 text-primary">{{ $parcel->parcel_id }}</h4>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted small">Status</span>
                                        <div>
                                            <span class="badge bg-primary">{{ ucfirst($parcel->current_status) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2 g-3">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">From</small>
                                        <p class="mb-0 fw-medium">{{ $parcel->senderTown?->county?->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->senderTown->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->senderPickUpDropOffPoint->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->senderPickUpDropOffPoint->address }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block">To</small>
                                        <p class="mb-0 fw-medium">{{ $parcel->receiverTown?->county?->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->receiverTown->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->deliveryStation->name }}</p>
                                        <p class="mb-0 fw-medium">{{ $parcel->deliveryStation->address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Owner Information (Read-only) -->
                            <div class="owner-info-card mb-4 p-3 bg-light rounded border">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Parcel Owner Details
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Full Name</small>
                                        <p class="fw-medium">{{ $parcel->receiver_name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Phone Number</small>
                                        <p class="fw-medium">{{ $parcel->receiver_phone }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">ID Number</small>
                                        <p class="fw-medium">{{ $parcel->receiver_id_number ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Pickup Person Selection -->
                            <div class="pickup-person-section mb-4">
                                <h6 class="mb-3">
                                    <i class="bi bi-person-check me-2"></i>
                                    Who is picking up the parcel?
                                </h6>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="pickupPerson" id="ownerPickup"
                                        wire:model.live="pickup_person_type" value="owner" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="ownerPickup">
                                        <i class="bi bi-person me-2"></i>
                                        Owner
                                    </label>

                                    <input type="radio" class="btn-check" name="pickupPerson" id="otherPickup"
                                        wire:model.live="pickup_person_type" value="other" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="otherPickup">
                                        <i class="bi bi-people me-2"></i>
                                        Other Person
                                    </label>
                                </div>
                            </div>

                            <!-- Other Person Details Form (shown only when 'other' is selected) -->
                            @if($pickup_person_type === 'other')
                            <div class="other-person-card mb-4 p-3 bg-light rounded border" x-data x-init="$el.scrollIntoView({ behavior: 'smooth', block: 'center' })">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Person Picking Up Details
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('picker_name') is-invalid @enderror"
                                            wire:model="picker_name" placeholder="Enter full name">
                                        @error('picker_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control @error('picker_phone') is-invalid @enderror"
                                            wire:model="picker_phone" placeholder="Enter phone number">
                                        @error('picker_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ID Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('picker_id_number') is-invalid @enderror"
                                            wire:model="picker_id_number" placeholder="Enter ID number">
                                        @error('picker_id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Relationship to Owner</label>
                                        <input type="text" class="form-control"
                                            wire:model="picker_relationship" placeholder="e.g., Family, Friend, Colleague">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Reason for picking up (Optional)</label>
                                        <textarea class="form-control" wire:model="picker_reason" rows="2"
                                            placeholder="Brief reason why they're picking up on behalf of the owner"></textarea>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Verification Code Section -->
                            <div class="code-input-section mb-3">
                                <label for="pickupCode" class="form-label fw-medium">
                                    <i class="bi bi-key me-1"></i>
                                    Enter Pickup Verification Code
                                </label>
                                <div class="input-group">
                                    <input type="text"
                                        class="form-control form-control-lg @error('pickup_code') is-invalid @enderror"
                                        id="pickupCode"
                                        wire:model="pickup_code"
                                        placeholder="Enter 6-digit code"
                                        maxlength="6"
                                        autocomplete="off">
                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-shield-lock text-muted"></i>
                                    </span>
                                </div>
                                @error('pickup_code')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enter the verification code provided to the recipient
                                </div>

                                @if($pickupVerificationError)
                                <div class="text-danger small mt-1">
                                    {{ $pickupVerificationError }}
                                </div>
                                @endif
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" wire:model="confirm_terms" id="confirmTerms">
                                <label class="form-check-label small" for="confirmTerms">
                                    I confirm that the information provided is accurate and I have verified the identity of the person picking up the parcel.
                                </label>
                                @error('confirm_terms')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closePickUpModal">
                                <i class="bi bi-x me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="verifyPickup">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Complete Pickup
                                </span>
                                <span wire:loading wire:target="verifyPickup">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Assign Driver Modal -->
        @if($activeTab === 'overview' && !$parcel->driver)
        <div class="modal fade" id="assignDriverModal" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>
                            Assign Driver
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Select a driver to assign to this parcel:</p>
                        <!-- Driver selection form would go here -->
                        <p class="text-muted">Driver list coming soon...</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" disabled>Assign Driver</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <style>
            .dashboard-section {
                padding: 20px;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 30px;
                flex-wrap: wrap;
                gap: 20px;
            }

            .section-title {
                font-size: 1.8rem;
                font-weight: 600;
                color: var(--text-dark);
                margin: 0;
            }

            .section-subtitle {
                color: var(--text-light);
                margin: 5px 0 0 0;
            }

            .header-actions {
                display: flex;
                gap: 10px;
                align-items: center;
            }

            .parcel-avatar .avatar-placeholder-lg {
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 4px solid #e9ecef;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 500;
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

            .status-info {
                background-color: rgba(23, 162, 184, 0.1);
                color: #0c5460;
                border: 1px solid rgba(23, 162, 184, 0.2);
            }

            .status-dark {
                background-color: rgba(52, 58, 64, 0.1);
                color: #343a40;
                border: 1px solid rgba(52, 58, 64, 0.2);
            }

            .status-primary {
                background-color: rgba(0, 123, 255, 0.1);
                color: #004085;
                border: 1px solid rgba(0, 123, 255, 0.2);
            }

            /* Quick Stats */
            .stat-card {
                background: white;
                border-radius: 12px;
                padding: 20px;
                border: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                gap: 15px;
                height: 100%;
            }

            .stat-card .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                color: white;
            }

            .stat-icon.total {
                background: linear-gradient(135deg, #6f42c1, #6610f2);
            }

            .stat-icon.distance {
                background: linear-gradient(135deg, #20c997, #17a2b8);
            }

            .stat-icon.rating {
                background: linear-gradient(135deg, #ffc107, #fd7e14);
            }

            .stat-icon.experience {
                background: linear-gradient(135deg, #6c757d, #495057);
            }

            .stat-content {
                flex: 1;
            }

            .stat-value {
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-dark);
                line-height: 1;
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--text-light);
                margin-top: 5px;
            }

            .stat-subtext {
                font-size: 0.8rem;
                color: var(--text-light);
                margin-top: 2px;
            }

            /* Tabs */
            .tabs-navigation .nav-tabs {
                border-bottom: 2px solid var(--border-color);
            }

            .tabs-navigation .nav-link {
                border: none;
                color: var(--text-light);
                font-weight: 500;
                padding: 12px 20px;
            }

            .tabs-navigation .nav-link.active {
                color: var(--primary-color);
                border-bottom: 2px solid var(--primary-color);
                background: transparent;
            }

            /* Table Styling */
            .table th {
                background-color: #f8f9fa;
                font-weight: 600;
                color: var(--text-dark);
                border-bottom: 2px solid var(--border-color);
            }

            .table-borderless th,
            .table-borderless td {
                border: none;
                padding: 8px 0;
            }

            /* Avatar */
            .avatar {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.2rem;
            }

            .avatar.bg-primary {
                background-color: #007bff;
            }

            .avatar.bg-success {
                background-color: #28a745;
            }

            /* Timeline */
            .timeline {
                position: relative;
                padding: 20px 0;
            }

            .timeline-item {
                position: relative;
                padding-left: 50px;
                margin-bottom: 30px;
            }

            .timeline-marker {
                position: absolute;
                left: 0;
                top: 0;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1rem;
                z-index: 1;
            }

            .timeline-item:not(:last-child):before {
                content: '';
                position: absolute;
                left: 18px;
                top: 40px;
                bottom: -20px;
                width: 2px;
                background: var(--border-color);
            }

            .timeline-content {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 15px;
                border: 1px solid var(--border-color);
            }

            .bg-success {
                background-color: #28a745;
            }

            .bg-primary {
                background-color: #007bff;
            }

            .bg-warning {
                background-color: #ffc107;
            }

            .bg-danger {
                background-color: #dc3545;
            }

            /* Modal */
            .modal.show.d-block {
                display: block;
                overflow-y: auto;
            }

            .form-label.required::after {
                content: " *";
                color: #dc3545;
            }

            .driver-info-card {
                background: linear-gradient(to bottom, #f8fafc, #ffffff);
                border-left: 4px solid var(--primary);
            }

            .driver-avatar img,
            .avatar-placeholder {
                transition: transform 0.2s;
            }

            .driver-avatar:hover img,
            .avatar-placeholder:hover {
                transform: scale(1.05);
            }

            .avatar-placeholder {
                background: linear-gradient(135deg, #6b7280, #4b5563);
            }

            #driverCode {
                font-family: monospace;
                font-size: 1.25rem;
                letter-spacing: 2px;
                text-align: center;
            }

            #driverCode:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            }

            /* Optional: Add a subtle animation for the verification process */
            @keyframes pulse {
                0% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.7;
                }

                100% {
                    opacity: 1;
                }
            }

            .btn-primary:disabled {
                animation: pulse 1.5s infinite;
            }

            @media (max-width: 768px) {
                .section-header {
                    flex-direction: column;
                    align-items: stretch;
                }

                .header-actions {
                    justify-content: flex-start;
                    flex-wrap: wrap;
                }

                .tabs-navigation .nav-link {
                    padding: 8px 12px;
                    font-size: 0.9rem;
                }

                .stat-card {
                    flex-direction: column;
                    text-align: center;
                    padding: 15px;
                }

                .stat-icon {
                    width: 50px;
                    height: 50px;
                    font-size: 1.5rem;
                }

                .stat-value {
                    font-size: 1.5rem;
                }
            }

            /* Additional styles for the pickup form */
            .btn-group .btn-outline-primary {
                border: 2px solid #e5e7eb;
                background: white;
                color: #4b5563;
            }

            .btn-group .btn-outline-primary:hover {
                background: #eef2ff;
                color: var(--primary);
                border-color: var(--primary);
            }

            .btn-check:checked+.btn-outline-primary {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
            }

            .parcel-info-banner {
                background: linear-gradient(45deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.1));
            }

            .owner-info-card,
            .other-person-card,
            .driver-info-card {
                transition: all 0.3s ease;
                border: 1px solid #e5e7eb;
            }

            .owner-info-card:hover,
            .other-person-card:hover,
            .driver-info-card:hover {
                border-color: var(--primary);
                box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
            }

            .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            }

            .modal-lg {
                max-width: 800px;
            }
        </style>

        <script>
            function confirmCancel(parcelNumber) {
                if (confirm(`Are you sure you want to cancel parcel #${parcelNumber}?`)) {
                    @this.call('updateStatus', 'cancelled');
                }
            }

            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal') && e.target.id === 'paymentModal') {
                    @this.call('closePaymentModal');
                }
            });

            // Auto-hide flash messages after 5 seconds
            document.addEventListener('livewire:init', () => {
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        if (!alert.closest('#paymentModal')) {
                            alert.style.transition = 'opacity 0.5s';
                            alert.style.opacity = '0';
                            setTimeout(() => alert.remove(), 500);
                        }
                    });
                }, 5000);
            });
        </script>

        <script>
            document.addEventListener('livewire:init', () => {
                let mpesaPollingInterval = null;

                // Start polling when M-Pesa payment is initiated
                Livewire.on('mpesa-payment-initiated', (data) => {
                    console.log('M-Pesa payment initiated', data);

                    // Clear any existing interval
                    if (mpesaPollingInterval) {
                        clearInterval(mpesaPollingInterval);
                    }

                    // Start polling every 5 seconds
                    mpesaPollingInterval = setInterval(() => {
                        Livewire.dispatch('checkMpesaStatus');
                    }, 5000);
                });

                // Stop polling when payment is completed or failed
                Livewire.on('mpesa-payment-completed', () => {
                    console.log('M-Pesa payment completed');
                    if (mpesaPollingInterval) {
                        clearInterval(mpesaPollingInterval);
                        mpesaPollingInterval = null;
                    }
                });

                Livewire.on('mpesa-payment-failed', () => {
                    console.log('M-Pesa payment failed');
                    if (mpesaPollingInterval) {
                        clearInterval(mpesaPollingInterval);
                        mpesaPollingInterval = null;
                    }
                });

                // Listen for status check events
                Livewire.on('checkMpesaStatus', () => {
                    @this.call('checkMpesaStatus');
                });

                // Clean up on page unload
                window.addEventListener('beforeunload', () => {
                    if (mpesaPollingInterval) {
                        clearInterval(mpesaPollingInterval);
                    }
                });
            });
        </script>

        <!-- Add this JavaScript for automatic code formatting if needed -->
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Auto-format code input to uppercase or handle specific formatting
                const codeInput = document.getElementById('driverCode');
                if (codeInput) {
                    codeInput.addEventListener('input', function(e) {
                        // Option 1: Force uppercase
                        // this.value = this.value.toUpperCase();

                        // Option 2: Only allow numbers (if code is numeric)
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }
            });
        </script>
    </div>
</div>