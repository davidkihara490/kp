<div>
    <div>
        <div class="rates-container">
            <div class="container py-4">
                <!-- Header -->
                <!-- <div class="dashboard-header mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-tags me-2 text-primary"></i>
                                Partner Rates
                            </h2>
                            <p class="text-muted">Manage your item rates across all zones</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="{{ route('partners.rates.create') }}" class="btn btn-outline-primary rounded-pill px-4 d-none">
                                <i class="bi bi-plus-circle me-2"></i> New Rate
                            </a>
                        </div>
                    </div>
                </div> -->

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-primary-light">
                                <i class="bi bi-box-seam text-primary"></i>
                            </div>
                            <div class="stat-info">
                                <h5 class="stat-number">{{ $totalItems }}</h5>
                                <span class="stat-label">Total Items</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-success-light">
                                <i class="bi bi-check-circle text-success"></i>
                            </div>
                            <div class="stat-info">
                                <h5 class="stat-number">{{ $itemsWithRates }}</h5>
                                <span class="stat-label">Items with Rates</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon bg-warning-light">
                                <i class="bi bi-exclamation-circle text-warning"></i>
                            </div>
                            <div class="stat-info">
                                <h5 class="stat-number">{{ $itemsWithoutRates }}</h5>
                                <span class="stat-label">Items without Rates</span>
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
                                    wire:model.live.debounce.300ms="search"
                                    placeholder="Search by name, code...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-lg" wire:model.live="statusFilter">
                                @foreach($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-lg" wire:model.live="rateFilter">
                                <option value="">All Items</option>
                                <option value="with_rates">With Rates</option>
                                <option value="without_rates">Without Rates</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                @if($items->count() > 0)
                <div class="table-responsive">
                    <table class="table rates-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" wire:model.live="selectAll">
                                </th>
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Item
                                    @if($sortField === 'name')
                                    <i class="bi bi-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-fill"></i>
                                    @endif
                                </th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Rates</th>
                                <th>Rate Count</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        class="form-check-input"
                                        wire:model.live="selectedItems"
                                        value="{{ $item->id }}">
                                </td>
                                <td>
                                    <div class="item-info">
                                        <span class="item-name">{{ $item->name }}</span>
                                        @if($item->description)
                                        <span class="item-description">{{ Str::limit($item->description, 60) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="item-code">{{ $item->code ?? '—' }}</span>
                                </td>
                                <td>
                                    @php
                                    $badge = $this->getStatusBadge($item->status);
                                    @endphp
                                    <span class="status-badge status-{{ $badge['color'] }}">
                                        <i class="fas {{ $badge['icon'] }} me-1"></i>
                                        {{ $badge['text'] }}
                                    </span>
                                </td>
                                <td>
                                    @if(in_array($item->id, $partnerRateItemIds))
                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle-fill me-1"></i> Configured
                                    </span>
                                    @else
                                    <span class="status-badge status-warning">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Not Set
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="rate-count-badge">
                                        {{ $rateCounts[$item->id] ?? 0 }} zone(s)
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('partners.rates.edit', $item->id) }}"
                                            class="btn-action btn-edit"
                                            title="Edit Rates">
                                            <i class="bi bi-pencil"></i> Edit Rates
                                        </a>
                                        @if(in_array($item->id, $partnerRateItemIds))
                                        <button class="btn-action btn-delete"
                                            wire:click="confirmDeleteRates({{ $item->id }})"
                                            title="Delete All Rates">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $items->links() }}
                </div>
                @else
                <div class="empty-state text-center py-5">
                    <div class="empty-state-icon">
                        <i class="bi bi-tags"></i>
                    </div>
                    <h4 class="fw-bold mt-3">No Items Found</h4>
                    <p class="text-muted mb-4">
                        @if($search || $statusFilter || $rateFilter)
                        No items match your current filters.
                        @else
                        No items available in the system yet.
                        @endif
                    </p>
                    @if($search || $statusFilter || $rateFilter)
                    <button class="btn btn-outline-secondary" wire:click="resetFilters">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Clear Filters
                    </button>
                    @endif
                </div>
                @endif

                <!-- Bulk Actions Bar -->
                @if($showBulkActions)
                <div class="bulk-actions-bar">
                    <span class="bulk-count">{{ count($selectedItems) }} selected</span>
                    <button class="btn btn-sm btn-danger" wire:click="bulkDeleteRates"
                        wire:confirm="Are you sure you want to delete rates for {{ count($selectedItems) }} item(s)?">
                        <i class="bi bi-trash me-1"></i> Delete Selected Rates
                    </button>
                </div>
                @endif
            </div>

            <!-- Delete Modal -->
            @if($showDeleteModal)
            <div class="modal-overlay" wire:click.self="$set('showDeleteModal', false)">
                <div class="modal-content-small">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                            Delete Rates
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete all rates for this item? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">Cancel</button>
                        <button class="btn btn-danger" wire:click="deleteRates">
                            <i class="bi bi-trash me-1"></i> Delete Rates
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <style>
                .rates-container {
                    background: var(--light-bg);
                    min-height: 100vh;
                    padding: 40px 0 60px;
                }

                .stat-card {
                    background: white;
                    border-radius: 16px;
                    padding: 18px 20px;
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                    border: 1px solid #e9ecef;
                    height: 100%;
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
                    background: #e8f5e9;
                }

                .bg-success-light {
                    background: #d4edda;
                }

                .bg-warning-light {
                    background: #fff3cd;
                }

                .stat-number {
                    font-size: 1.5rem;
                    font-weight: 700;
                    margin: 0;
                }

                .stat-label {
                    font-size: 0.8rem;
                    color: #6c757d;
                    font-weight: 500;
                }

                .filters-section {
                    background: white;
                    border-radius: 16px;
                    padding: 20px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                    border: 1px solid #e9ecef;
                }

                .search-box {
                    position: relative;
                }

                .search-box i {
                    position: absolute;
                    left: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #6c757d;
                }

                .search-box .form-control {
                    padding-left: 45px;
                    border-radius: 12px;
                    border: 2px solid #e9ecef;
                }

                .form-select-lg {
                    border-radius: 12px;
                    border: 2px solid #e9ecef;
                    padding: 12px 16px;
                }

                .rates-table {
                    background: white;
                    border-radius: 16px;
                    overflow: hidden;
                    border-collapse: separate;
                    border-spacing: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                    border: 1px solid #e9ecef;
                    width: 100%;
                }

                .rates-table thead {
                    background: #f8f9fa;
                }

                .rates-table thead th {
                    padding: 14px 16px;
                    font-weight: 600;
                    font-size: 0.8rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    color: #6c757d;
                    border-bottom: 2px solid #e9ecef;
                    white-space: nowrap;
                }

                .rates-table tbody tr {
                    transition: all 0.15s;
                }

                .rates-table tbody tr:hover {
                    background: #f8f9fa;
                }

                .rates-table tbody td {
                    padding: 14px 16px;
                    vertical-align: middle;
                    border-bottom: 1px solid #e9ecef;
                    font-size: 0.9rem;
                }

                .rates-table tbody tr:last-child td {
                    border-bottom: none;
                }

                .item-info {
                    display: flex;
                    flex-direction: column;
                    gap: 2px;
                }

                .item-name {
                    font-weight: 600;
                    color: #1e293b;
                }

                .item-description {
                    font-size: 0.8rem;
                    color: #6c757d;
                }

                .item-code {
                    font-family: monospace;
                    background: #f1f5f9;
                    padding: 2px 8px;
                    border-radius: 4px;
                    font-size: 0.85rem;
                }

                .status-badge {
                    display: inline-flex;
                    align-items: center;
                    padding: 4px 12px;
                    border-radius: 30px;
                    font-size: 0.75rem;
                    font-weight: 600;
                }

                .status-success {
                    background: #d4edda;
                    color: #155724;
                }

                .status-warning {
                    background: #fff3cd;
                    color: #856404;
                }

                .status-secondary {
                    background: #e9ecef;
                    color: #495057;
                }

                .status-danger {
                    background: #f8d7da;
                    color: #721c24;
                }

                .status-info {
                    background: #d1ecf1;
                    color: #0c5460;
                }

                .rate-count-badge {
                    background: #e8f5e9;
                    color: #008f40;
                    padding: 3px 10px;
                    border-radius: 12px;
                    font-size: 0.8rem;
                    font-weight: 600;
                }

                .action-buttons {
                    display: flex;
                    gap: 6px;
                    justify-content: flex-end;
                }

                .btn-action {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    padding: 5px 12px;
                    border-radius: 8px;
                    border: none;
                    font-size: 0.8rem;
                    font-weight: 500;
                    text-decoration: none;
                    transition: all 0.15s;
                    cursor: pointer;
                }

                .btn-edit {
                    background: #008f40;
                    color: white;
                }

                .btn-edit:hover {
                    background: #007a36;
                    color: white;
                }

                .btn-delete {
                    background: #f8d7da;
                    color: #721c24;
                }

                .btn-delete:hover {
                    background: #f5c2c7;
                }

                .empty-state-icon {
                    font-size: 6rem;
                    color: #e9ecef;
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

                .bulk-actions-bar {
                    position: fixed;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #1e293b;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 50px;
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                    z-index: 1000;
                }

                .bulk-count {
                    font-weight: 600;
                }

                .modal-overlay {
                    position: fixed;
                    inset: 0;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(4px);
                    z-index: 9999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 20px;
                }

                .modal-content-small {
                    background: white;
                    border-radius: 16px;
                    max-width: 500px;
                    width: 100%;
                    overflow: hidden;
                }

                .modal-header {
                    padding: 16px 20px;
                    border-bottom: 1px solid #e9ecef;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .modal-body {
                    padding: 20px;
                }

                .modal-footer {
                    padding: 16px 20px;
                    border-top: 1px solid #e9ecef;
                    display: flex;
                    justify-content: flex-end;
                    gap: 8px;
                }

                @media (max-width: 768px) {
                    .rates-container {
                        padding: 20px 0 40px;
                    }

                    .bulk-actions-bar {
                        flex-direction: column;
                        gap: 10px;
                        width: 90%;
                    }

                    .rates-table thead {
                        display: none;
                    }

                    .rates-table tbody tr {
                        display: block;
                        padding: 16px;
                        border-bottom: 2px solid #e9ecef;
                    }

                    .rates-table tbody td {
                        display: flex;
                        justify-content: space-between;
                        padding: 6px 0;
                        border-bottom: 1px solid #f1f5f9;
                    }

                    .rates-table tbody td:last-child {
                        border-bottom: none;
                    }
                }
            </style>
        </div>
    </div>
</div>