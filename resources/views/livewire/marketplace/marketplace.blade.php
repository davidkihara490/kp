<div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <div class="logo-container me-2">
                    <i class="bi bi-box-seam text-white"></i>
                </div>
                Karibu Marketplace
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="bi bi-grid me-1"></i> Marketplace</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-truck me-1"></i> My Deliveries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-wallet2 me-1"></i> Earnings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-graph-up me-1"></i> Performance</a>
                    </li>
                </ul>

                <div class="user-profile ms-lg-3 mt-3 mt-lg-0">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <strong style="font-size: 0.9rem;">John Courier</strong>
                            <div class="small opacity-75" style="font-size: 0.75rem;">Verified Partner</div>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown" style="color: var(--primary-color);">
                                <i class="bi bi-person-circle fs-5"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Tab Navigation -->
            <div class="tab-navigation">
                <ul class="nav nav-tabs" id="marketplaceTabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#available">Available</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#accepted">My Deliveries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#completed">Completed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#earnings">Earnings</a>
                    </li>
                </ul>
            </div>

            <!-- Alert Banner -->
            <div class="alert-marketplace">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h5 class="mb-2" style="font-size: 1.1rem;"><i class="bi bi-lightning-charge me-2"></i> New Deliveries Available!</h5>
                        <p class="mb-0" style="font-size: 0.9rem; opacity: 0.9;">24 new parcels added today. Claim your deliveries now!</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-light" id="refreshMarketplace" style="font-size: 0.9rem; padding: 8px 16px;">
                            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="quick-stats mb-4">
                <div class="stat-item-compact">
                    <div class="stat-number-compact" id="availableParcels">{{ $parcels->count() }}</div>
                    <div class="stat-label-compact">Available</div>
                </div>
                <div class="stat-item-compact">
                    <div class="stat-number-compact" id="totalValue">{{ $parcels->sum('total_amount') }}</div>
                    <div class="stat-label-compact">Total Value</div>
                </div>
                <div class="stat-item-compact">
                    <div class="stat-number-compact" id="avgPrice">KES 1,750</div>
                    <div class="stat-label-compact">Avg. Payout</div>
                </div>
                <div class="stat-item-compact">
                    <div class="stat-number-compact" id="expressCount">{{ $parcels->count() }}</div>
                    <div class="stat-label-compact">Express</div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <h5 class="section-title" style="font-size: 1.1rem; margin-bottom: 15px;">Filter Deliveries</h5>
                <div class="row align-items-center">
                    <div class="col-lg-9">
                        <div class="row g-2">
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Location</label>
                                <select class="form-select" id="filterLocation" style="font-size: 0.85rem;">
                                    <option value="">All Locations</option>
                                    <option value="nairobi">Nairobi</option>
                                    <option value="mombasa">Mombasa</option>
                                    <option value="kisumu">Kisumu</option>
                                    <option value="nakuru">Nakuru</option>
                                    <option value="eldoret">Eldoret</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Service Type</label>
                                <select class="form-select" id="filterService" style="font-size: 0.85rem;">
                                    <option value="">All Types</option>
                                    <option value="express">Express</option>
                                    <option value="standard">Standard</option>
                                    <option value="economy">Economy</option>
                                    <option value="priority">Priority</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Package Size</label>
                                <select class="form-select" id="filterSize" style="font-size: 0.85rem;">
                                    <option value="">All Sizes</option>
                                    <option value="small">Small (0-5kg)</option>
                                    <option value="medium">Medium (5-15kg)</option>
                                    <option value="large">Large (15kg+)</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-check form-check-inline me-3 mb-2">
                                    <input class="form-check-input" type="checkbox" id="filterPriority" value="priority">
                                    <label class="form-check-label" for="filterPriority" style="font-size: 0.85rem;">
                                        <i class="bi bi-star-fill text-warning me-1"></i> Priority
                                    </label>
                                </div>
                                <div class="form-check form-check-inline me-3 mb-2">
                                    <input class="form-check-input" type="checkbox" id="filterSameDay" value="same-day">
                                    <label class="form-check-label" for="filterSameDay" style="font-size: 0.85rem;">
                                        <i class="bi bi-lightning-fill text-warning me-1"></i> Same Day
                                    </label>
                                </div>
                                <div class="form-check form-check-inline me-3 mb-2">
                                    <input class="form-check-input" type="checkbox" id="filterCashOnDelivery" value="cod">
                                    <label class="form-check-label" for="filterCashOnDelivery" style="font-size: 0.85rem;">
                                        <i class="bi bi-cash-coin text-info me-1"></i> COD
                                    </label>
                                </div>
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input" type="checkbox" id="filterHighValue" value="high-value">
                                    <label class="form-check-label" for="filterHighValue" style="font-size: 0.85rem;">
                                        <i class="bi bi-gem text-danger me-1"></i> High Value
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mt-3 mt-lg-0">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" id="applyFilters" style="font-size: 0.9rem; padding: 10px;">
                                <i class="bi bi-funnel me-2"></i> Apply Filters
                            </button>
                            <button class="btn btn-outline-secondary" id="clearFilters" style="font-size: 0.9rem; padding: 10px;">
                                <i class="bi bi-x-circle me-2"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deliveries List -->
            <h5 class="section-title mt-4" style="font-size: 1.2rem;">Available Deliveries</h5>
            <div id="kkdeliveriesList">
                @forelse($parcels as $parcel)
                <div class="delivery-row">
                    <!-- Header Section -->
                    <div class="delivery-header-compact">
                        <div class="mb-2">
                            <div class="info-label">TRACKING</div>
                            <div class="info-value fw-bold" style="font-size: 0.85rem;">{{ $parcel->parcel_id }}</div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="delivery-content">
                        <!-- Route Info -->
                        <div class="route-info">
                            <div class="mb-2">
                                <div class="info-label">FROM</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ $parcel->senderTown->name ?? 'N/A' }}</div>
                            </div>
                            <div class="route-indicator">
                                <div class="route-line"></div>
                                <div class="route-dot start"></div>
                                <div class="route-dot moving"></div>
                                <div class="route-dot end"></div>
                            </div>
                            <div class="mt-2">
                                <div class="info-label">TO</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ $parcel->receiverTown->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <!-- Package Info - Row Layout -->
                        <div class="package-info">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <!-- Package Type -->
                                <div class="flex-grow-1">
                                    <div class="info-label">PACKAGE</div>
                                    <div class="info-value" style="font-size: 0.85rem;">{{ $parcel->parcel_type }}</div>
                                    <small class="text-muted" style="font-size: 0.75rem;">{{ $parcel->package_type ?? 'Standard' }}</small>
                                </div>

                                <!-- Weight (if available) -->
                                @if(isset($parcel->weight))
                                <div class="flex-grow-1">
                                    <div class="info-label">WEIGHT</div>
                                    <div class="info-value" style="font-size: 0.85rem;">{{ $parcel->weight }} kg</div>
                                </div>
                                @endif

                                <!-- Dimensions (if available) -->
                                @if(isset($parcel->dimensions))
                                <div class="flex-grow-1">
                                    <div class="info-label">DIMENSIONS</div>
                                    <div class="info-value" style="font-size: 0.85rem;">{{ $parcel->dimensions }}</div>
                                </div>
                                @endif

                                <!-- Service Type Badge -->
                                <div class="flex-grow-1">
                                    <span class="service-badge" style="font-size: 0.7rem; padding: 4px 12px; background-color: #e1f0fa; color: #0066a1; border-radius: 20px; font-weight: 500;">
                                        {{ strtoupper($parcel->service_type ?? 'STANDARD') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <div class="d-flex justify-content-center mb-2">
                            <div class="price-tag">KSH {{ number_format($parcel->total_amount * 0.85 ?? 0) }}</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn-view" wire:click="viewParcelDetails('{{ $parcel->id }}')" type="button">
                                <i class="bi bi-eye me-2"></i> Details
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    No Parcels at the moment. Check in a few minutes.
                </div>
                @endforelse
            </div>

            <!-- Parcel Details Modal -->
            @if($showParcelDetailsModal)
            <div class="modal fade show" id="parcelModal" tabindex="-1" aria-labelledby="parcelModalLabel" aria-hidden="true" style="display: block; background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="parcelModalLabel" style="font-size: 1.2rem;">
                                <i class="bi bi-box-seam me-2"></i> Delivery Details
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeParcelModal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="font-size: 0.9rem;">
                            @if($selectedParcel)
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="mb-3" style="font-size: 1rem;">Delivery Information</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Tracking Number:</th>
                                                <td><strong>{{ $selectedParcel->parcel_id ?? $selectedParcel->id }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Service Type:</th>
                                                <td>
                                                    @php
                                                    $serviceType = strtolower($selectedParcel->service_type ?? 'standard');
                                                    $badgeClass = 'bg-success';
                                                    if(in_array($serviceType, ['express', 'same-day'])) {
                                                    $badgeClass = 'bg-danger';
                                                    } elseif($serviceType === 'priority') {
                                                    $badgeClass = 'bg-warning';
                                                    }
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ strtoupper($selectedParcel->service_type ?? 'STANDARD') }}
                                                    </span>
                                                    @if($selectedParcel->is_priority ?? false)
                                                    <span class="badge bg-warning ms-1">Priority</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pickup Time:</th>
                                                <td>
                                                    <i class="bi bi-clock text-success me-1"></i>
                                                    {{ $selectedParcel->pickup_date ? $selectedParcel->pickup_date->format('M d, g:i A') : 'Today, ' . now()->format('g:i A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Delivery Deadline:</th>
                                                <td>
                                                    <i class="bi bi-calendar-check text-success me-1"></i>
                                                    {{ $selectedParcel->delivery_window ?? 'Tomorrow, ' . now()->addDay()->format('g:i A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Items/Package Type:</th>
                                                <td>{{ $selectedParcel->parcel_type }} - {{ $selectedParcel->package_type ?? 'Standard' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Declared Value:</th>
                                                <td>KES {{ number_format($selectedParcel->declared_value ?? 0) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Insurance:</th>
                                                <td>
                                                    @if($selectedParcel->has_insurance ?? false)
                                                    <span class="badge bg-success">Yes</span>
                                                    @else
                                                    <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Cash on Delivery:</th>
                                                <td>
                                                    @if($selectedParcel->is_cod ?? false)
                                                    <span class="badge bg-info">Yes - KES {{ number_format($selectedParcel->cod_amount ?? 0) }}</span>
                                                    @else
                                                    <span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    @if($selectedParcel->special_instructions)
                                    <div class="alert alert-warning mt-3" style="font-size: 0.9rem;">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <strong>Special Instructions:</strong> {{ $selectedParcel->special_instructions }}
                                    </div>
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <h6 class="mb-3" style="font-size: 1rem;">Route Details</h6>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <small class="text-muted">FROM</small>
                                                <div class="fw-bold" style="font-size: 0.95rem;">
                                                    {{ $selectedParcel->senderTown->name ?? 'N/A' }},
                                                    {{ $selectedParcel->senderCounty()->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">TO</small>
                                                <div class="fw-bold" style="font-size: 0.95rem;">
                                                    {{ $selectedParcel->receiverTown->name ?? 'N/A' }},
                                                    {{ $selectedParcel->receiverCounty()->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted">DISTANCE</small>
                                                <div class="fw-bold" style="font-size: 0.95rem;">{{ rand(50, 500) }} km</div>
                                            </div>
                                            <hr>
                                            <div>
                                                <small class="text-muted">SENDER</small>
                                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $selectedParcel->sender_name }}</div>
                                                <small>{{ $selectedParcel->sender_phone }}</small>
                                            </div>
                                            <hr>
                                            <div>
                                                <small class="text-muted">RECEIVER</small>
                                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $selectedParcel->receiver_name }}</div>
                                                <small>{{ $selectedParcel->receiver_phone }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading parcel details...</p>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeParcelModal" style="font-size: 0.9rem; padding: 10px;">
                                <i class="bi bi-x-circle me-2"></i> Close
                            </button>
                            @if($selectedParcel)
                            <button type="button" class="btn btn-success" wire:click="acceptParcel({{ $selectedParcel->id ?? $selectedParcel->id }})" style="font-size: 0.9rem; padding: 10px;">
                                <i class="bi bi-check-circle me-2"></i> Accept This Delivery
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Driver Assignment Modal -->
            @if($showDriverAssignmentModal)
            <div class="modal fade show" id="driverAssignmentModal" tabindex="-1" aria-labelledby="driverAssignmentModalLabel" aria-hidden="true" style="display: block; background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="driverAssignmentModalLabel">
                                <i class="bi bi-truck me-2"></i> Assign Driver
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeDriverModal" aria-label="Close"></button>
                        </div>
                        <form wire:submit.prevent="assignDriver">
                            <div class="modal-body">
                                @if($selectedParcel)
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Parcel</label>
                                    <p class="form-control-static">{{ $selectedParcel->parcel_id ?? $selectedParcel->id }} - From {{ $selectedParcel->senderTown->name ?? 'N/A' }} to {{ $selectedParcel->receiverTown->name ?? 'N/A' }}</p>
                                </div>

                                <div class="mb-3">
                                    <label for="selectedDriver" class="form-label fw-semibold">Select Driver <span class="text-danger">*</span></label>
                                    <select class="form-select" id="selectedDriver" wire:model="selectedDriver" required>
                                        <option value="">Choose a driver...</option>
                                        @foreach($drivers as $driver)
                                        <option value="{{ $driver->id }}">
                                            {{ $driver->first_name }} {{ $driver->last_name }} -
                                            {{ $driver->currentFleet()->fleet->registration_number ?? 'No vehicle' }}
                                            ({{ $driver->currentFleet()->fleet->vehicle_type ?? 'Unknown' }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('selectedDriver') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="assignment_notes" class="form-label fw-semibold">Assignment Notes</label>
                                    <textarea class="form-control" id="assignment_notes" wire:model="assignment_notes" rows="2" placeholder="Any instructions for the driver..."></textarea>
                                    @error('assignment_notes') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                @if(isset($availableVehicles) && count($availableVehicles) > 0)
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Available vehicles:
                                    @foreach($availableVehicles as $type => $count)
                                    <span class="badge bg-secondary me-1">{{ ucfirst($type) }}: {{ $count }}</span>
                                    @endforeach
                                </div>
                                @endif
                                @else
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading...</p>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeDriverModal">
                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" @if(!$selectedParcel) disabled @endif>
                                    <i class="bi bi-check-circle me-2"></i> Assign Driver
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Pagination -->
            <nav aria-label="Pagination">
                <ul class="pagination" id="pagination">
                    <!-- Pagination will be generated via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action">
        <button class="floating-btn" onclick="refreshMarketplace()">
            <i class="bi bi-arrow-clockwise"></i>
        </button>
    </div>
</div>