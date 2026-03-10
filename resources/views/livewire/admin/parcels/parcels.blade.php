<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-box mr-2"></i>
                    Parcels Management
                </h3>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.parcels.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-1"></i> Add New Parcel
                    </a>

                    <button class="btn btn-outline-primary btn-sm" wire:click="export('csv')">
                        <i class="fas fa-download mr-1"></i> Export CSV
                    </button>

                    <button class="btn btn-outline-secondary btn-sm" wire:click="export('pdf')">
                        <i class="fas fa-file-pdf mr-1"></i> Export PDF
                    </button>

                    <button class="btn btn-outline-info btn-sm" wire:click="resetFilters">
                        <i class="fas fa-undo mr-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas fa-box"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Parcels</span>
                            <span class="info-box-number">{{ number_format($totalParcels) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending</span>
                            <span class="info-box-number">{{ number_format($pendingCount) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-truck"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">In Transit</span>
                            <span class="info-box-number">{{ number_format($inTransitCount) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Delivered</span>
                            <span class="info-box-number">{{ number_format($deliveredCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Revenue (Paid Parcels)</span>
                            <span class="info-box-number">KES {{ number_format($totalRevenue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-filter mr-2"></i>
                        Search & Filters
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Quick Search</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control"
                                    wire:model.live.debounce.300ms="search"
                                    placeholder="Parcel ID, Sender, Receiver, Phone...">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Parcel ID</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="parcelId"
                                placeholder="e.g., KP20240301123456">
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select class="form-control" wire:model.live="status">
                                @foreach($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Sender Name</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="senderName"
                                placeholder="Sender name">
                        </div>

                        <div class="col-md-3">
                            <label>Receiver Name</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="receiverName"
                                placeholder="Receiver name">
                        </div>

                        <div class="col-md-3">
                            <label>Sender Phone</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="senderPhone"
                                placeholder="07XXXXXXXX">
                        </div>

                        <div class="col-md-3">
                            <label>Receiver Phone</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="receiverPhone"
                                placeholder="07XXXXXXXX">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Payment Status</label>
                            <select class="form-control" wire:model.live="paymentStatus">
                                @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Parcel Type</label>
                            <select class="form-control" wire:model.live="parcelType">
                                @foreach($parcelTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Package Type</label>
                            <select class="form-control" wire:model.live="packageType">
                                @foreach($packageTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Transport Partner</label>
                            <select class="form-control" wire:model.live="transportPartnerId">
                                <option value="">All Partners</option>
                                @foreach($transportPartners as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label>Driver</label>
                            <select class="form-control" wire:model.live="driverId">
                                <option value="">All Drivers</option>
                                @foreach($drivers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Sender Town</label>
                            <select class="form-control" wire:model.live="senderTownId">
                                <option value="">All Towns</option>
                                @foreach($towns as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Receiver Town</label>
                            <select class="form-control" wire:model.live="receiverTownId">
                                <option value="">All Towns</option>
                                @foreach($towns as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-6">
                                    <label>Date From</label>
                                    <input type="date" class="form-control" wire:model.live="dateFrom">
                                </div>
                                <div class="col-6">
                                    <label>Date To</label>
                                    <input type="date" class="form-control" wire:model.live="dateTo">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if($search || $parcelId || $senderName || $receiverName || $status || $paymentStatus || $senderTownId || $receiverTownId || $dateFrom || $dateTo)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info py-2 mb-0">
                                <i class="fas fa-filter mr-2"></i>
                                <strong>Active Filters:</strong>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @if($search)
                                    <span class="badge badge-info">Search: {{ $search }}</span>
                                    @endif
                                    @if($parcelId)
                                    <span class="badge badge-info">Parcel ID: {{ $parcelId }}</span>
                                    @endif
                                    @if($senderName)
                                    <span class="badge badge-info">Sender: {{ $senderName }}</span>
                                    @endif
                                    @if($receiverName)
                                    <span class="badge badge-info">Receiver: {{ $receiverName }}</span>
                                    @endif
                                    @if($status)
                                    <span class="badge badge-info">Status: {{ $statuses[$status] ?? $status }}</span>
                                    @endif
                                    @if($paymentStatus)
                                    <span class="badge badge-info">Payment: {{ $paymentStatuses[$paymentStatus] ?? $paymentStatus }}</span>
                                    @endif
                                    @if($senderTownId)
                                    <span class="badge badge-info">From: {{ $towns[$senderTownId] ?? '' }}</span>
                                    @endif
                                    @if($receiverTownId)
                                    <span class="badge badge-info">To: {{ $towns[$receiverTownId] ?? '' }}</span>
                                    @endif
                                    @if($dateFrom)
                                    <span class="badge badge-info">From: {{ $dateFrom }}</span>
                                    @endif
                                    @if($dateTo)
                                    <span class="badge badge-info">To: {{ $dateTo }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bulk Actions -->
            @if(count($selectedParcels) > 0)
            <div class="alert alert-info mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ count($selectedParcels) }} parcel(s) selected
                    </span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary"
                            wire:click="openBulkActionModal('assign')">
                            <i class="fas fa-user-tag mr-1"></i> Assign
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info"
                            wire:click="openBulkActionModal('print')">
                            <i class="fas fa-print mr-1"></i> Print Labels
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger"
                            wire:click="openBulkActionModal('delete')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40">
                                <input type="checkbox" wire:model.live="selectAll">
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('parcel_id')">
                                    Parcel ID
                                    @if($sortField === 'parcel_id')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('sender_name')">
                                    Sender
                                    @if($sortField === 'sender_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('receiver_name')">
                                    Receiver
                                    @if($sortField === 'receiver_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>From → To</th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('total_amount')">
                                    Amount
                                    @if($sortField === 'total_amount')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Payment</th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('current_status')">
                                    Status
                                    @if($sortField === 'current_status')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Driver/Partner</th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('created_at')">
                                    Created
                                    @if($sortField === 'created_at')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($parcels as $parcel)
                        <tr>
                            <td>
                                <input type="checkbox"
                                    wire:model.live="selectedParcels"
                                    value="{{ $parcel->id }}">
                            </td>
                            <td>
                                <span class="font-weight-bold" wire:click="viewParcel({{ $parcel->id }})">{{ $parcel->parcel_id }}</span>
                                @if($parcel->requiresSpecialHandling())
                                <br>
                                <span class="badge badge-warning mt-1">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    {{ ucfirst($parcel->package_type) }}
                                </span>
                                @endif
                            </td>
                            <td>
                                {{ $parcel->sender_name }}
                                <br>
                                <small class="text-muted">{{ $parcel->sender_phone }}</small>
                            </td>
                            <td>
                                {{ $parcel->receiver_name }}
                                <br>
                                <small class="text-muted">{{ $parcel->receiver_phone }}</small>
                            </td>
                            <td>
                                {{ $parcel->senderTown->name ?? 'N/A' }}
                                <i class="fas fa-arrow-right mx-1"></i>
                                {{ $parcel->receiverTown->name ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="font-weight-bold text-success">
                                    KES {{ number_format($parcel->total_amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($parcel->payment_status === 'paid') badge-success
                                    @elseif($parcel->payment_status === 'pending') badge-warning
                                    @elseif($parcel->payment_status === 'failed') badge-danger
                                    @else badge-secondary @endif">
                                    {{ ucfirst($parcel->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                $statusBadge = $parcel->parcel_status_badge;
                                @endphp
                                <span class="badge badge-{{ $statusBadge['color'] }}">
                                    <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $parcel->current_status)) }}
                                </span>
                            </td>
                            <td>
                                @if($parcel->driver)
                                <i class="fas fa-user mr-1"></i> {{ $parcel->driver->full_name }}
                                <br>
                                @endif
                                @if($parcel->transportPartner)
                                <small class="text-muted">
                                    <i class="fas fa-building mr-1"></i> {{ $parcel->transportPartner->company_name }}
                                </small>
                                @endif
                            </td>
                            <td>
                                {{ $parcel->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $parcel->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">

                                    <a href="{{ route('admin.parcels.view', $parcel->id) }}"
                                        class="btn btn-sm btn-info"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <button class="btn btn-sm btn-danger"
                                        wire:click="confirmDelete({{ $parcel->id }})"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="fas fa-box fa-4x text-muted mb-3"></i>
                                <h5>No parcels found</h5>
                                <p class="text-muted">
                                    Try adjusting your search filters or create a new parcel.
                                </p>
                                <a href="{{ route('admin.parcels.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus mr-1"></i> Add New Parcel
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4">
                <div class="text-muted mb-3 mb-md-0">
                    @if($parcels->total() > 0)
                    Showing <span class="font-weight-medium">{{ $parcels->firstItem() }}</span>
                    to <span class="font-weight-medium">{{ $parcels->lastItem() }}</span>
                    of <span class="font-weight-medium">{{ number_format($parcels->total()) }}</span> entries
                    @else
                    No entries found
                    @endif
                </div>

                <div class="pagination-wrapper">
                    @if($parcels->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if($parcels->onFirstPage())
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" aria-hidden="true">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            </li>
                            @else
                            <li class="page-item">
                                <button class="page-link" wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach($parcels->getUrlRange(max(1, $parcels->currentPage() - 2), min($parcels->lastPage(), $parcels->currentPage() + 2)) as $page => $url)
                            @if($page == $parcels->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                            @else
                            <li class="page-item">
                                <button class="page-link" wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled">
                                    {{ $page }}
                                </button>
                            </li>
                            @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if($parcels->hasMorePages())
                            <li class="page-item">
                                <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled" rel="next">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </li>
                            @else
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link" aria-hidden="true">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                            @endif
                        </ul>
                    </nav>
                    @else
                    <span class="text-muted small">
                        Page 1 of 1
                    </span>
                    @endif
                </div>
            </div>

            <!-- Per Page Selector (Optional) -->
            <div class="d-flex justify-content-end mt-2">
                <div class="form-inline">
                    <label for="perPage" class="mr-2 small text-muted">Show:</label>
                    <select wire:model.live="perPage" id="perPage" class="form-control form-control-sm" style="width: 70px;">
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- View Parcel Modal -->
    @if($showViewModal && $selectedParcel)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-box mr-2"></i>
                        Parcel Details - {{ $selectedParcel->parcel_id }}
                    </h5>
                    <button type="button" class="close" wire:click="$set('showViewModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Sender Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $selectedParcel->sender_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $selectedParcel->sender_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $selectedParcel->sender_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Town:</th>
                                            <td>{{ $selectedParcel->senderTown->name ?? 'N/A' }}</td>
                                        </tr>
                                        @if($selectedParcel->sender_notes)
                                        <tr>
                                            <th>Notes:</th>
                                            <td>{{ $selectedParcel->sender_notes }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Receiver Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Name:</th>
                                            <td>{{ $selectedParcel->receiver_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $selectedParcel->receiver_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $selectedParcel->receiver_email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td>{{ $selectedParcel->receiver_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>Town:</th>
                                            <td>{{ $selectedParcel->receiverTown->name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <h6 class="mb-0">Parcel Details</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Type:</th>
                                            <td>{{ ucfirst($selectedParcel->parcel_type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Package Type:</th>
                                            <td>
                                                <span class="badge badge-warning">
                                                    {{ ucfirst($selectedParcel->package_type) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Dimensions:</th>
                                            <td>{{ $selectedParcel->parcel_dimensions }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight:</th>
                                            <td>{{ $selectedParcel->weight }} {{ $selectedParcel->weight_unit }}</td>
                                        </tr>
                                        <tr>
                                            <th>Volume:</th>
                                            <td>{{ $selectedParcel->parcel_volume }} cm³</td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $selectedParcel->content_description ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">Pricing & Payment</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Base Price:</th>
                                            <td>KES {{ number_format($selectedParcel->base_price, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Weight Charge:</th>
                                            <td>KES {{ number_format($selectedParcel->weight_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Distance Charge:</th>
                                            <td>KES {{ number_format($selectedParcel->distance_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Special Handling:</th>
                                            <td>KES {{ number_format($selectedParcel->special_handling_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Insurance:</th>
                                            <td>KES {{ number_format($selectedParcel->insurance_charge, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tax:</th>
                                            <td>KES {{ number_format($selectedParcel->tax_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Discount:</th>
                                            <td>KES {{ number_format($selectedParcel->discount_amount, 2) }}</td>
                                        </tr>
                                        <tr class="font-weight-bold">
                                            <th>Total:</th>
                                            <td class="text-success">KES {{ number_format($selectedParcel->total_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status:</th>
                                            <td>
                                                <span class="badge 
                                                    @if($selectedParcel->payment_status === 'paid') badge-success
                                                    @elseif($selectedParcel->payment_status === 'pending') badge-warning
                                                    @else badge-danger @endif">
                                                    {{ ucfirst($selectedParcel->payment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Assignment</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Pickup Point:</th>
                                            <td>{{ $selectedParcel->senderPickUpDropOffPoint->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Delivery Point:</th>
                                            <td>{{ $selectedParcel->deliveryStation->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transport Partner:</th>
                                            <td>{{ $selectedParcel->transportPartner->company_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Driver:</th>
                                            <td>{{ $selectedParcel->driver->full_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>PHA:</th>
                                            <td>{{ $selectedParcel->pha->full_name ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($selectedParcel->statuses && $selectedParcel->statuses->count() > 0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0">Status History</h6>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date/Time</th>
                                                <th>Status</th>
                                                <th>Changed By</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($selectedParcel->statuses as $status)
                                            <tr>
                                                <td>{{ $status->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($status->changed_by_type === 'driver' && $status->driver)
                                                    {{ $status->driver->full_name }} (Driver)
                                                    @elseif($status->changed_by_type === 'system')
                                                    System
                                                    @else
                                                    {{ $status->changer->name ?? 'Unknown' }}
                                                    @endif
                                                </td>
                                                <td>{{ $status->notes ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="{{ route('admin.parcels.edit', $selectedParcel->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-1"></i> Edit Parcel
                    </a>
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showViewModal', false)">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal && $selectedParcel)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this parcel?</p>
                    <ul class="text-danger">
                        <li>Parcel ID: {{ $selectedParcel->parcel_id }}</li>
                        <li>Sender: {{ $selectedParcel->sender_name }}</li>
                        <li>Receiver: {{ $selectedParcel->receiver_name }}</li>
                        <li>Amount: KES {{ number_format($selectedParcel->total_amount, 2) }}</li>
                        <li>This action cannot be undone</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showDeleteModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fas fa-trash mr-1"></i> Delete Parcel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Bulk Action Modal -->
    @if($showBulkActionModal)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-capitalize">
                        <i class="fas fa-cogs mr-2"></i>
                        {{ ucfirst($bulkAction) }} Parcels
                    </h5>
                    <button type="button" class="close" wire:click="$set('showBulkActionModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to {{ $bulkAction }} <strong>{{ count($selectedParcels) }}</strong> parcel(s).</p>
                    <p class="text-warning">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        This action will affect all selected parcels.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showBulkActionModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="executeBulkAction">
                        <i class="fas fa-check mr-1"></i> Confirm {{ ucfirst($bulkAction) }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: .25rem;
            background: #fff;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
        }

        .info-box-icon {
            border-radius: .25rem;
            -ms-flex-align: center;
            align-items: center;
            display: -ms-flexbox;
            display: flex;
            font-size: 1.875rem;
            -ms-flex-pack: center;
            justify-content: center;
            text-align: center;
            width: 70px;
        }

        .info-box-content {
            -ms-flex: 1;
            flex: 1;
            padding: 5px 10px;
        }

        .info-box-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 700;
            font-size: .875rem;
            text-transform: uppercase;
        }

        .info-box-number {
            display: block;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: white;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }

        table th a {
            text-decoration: none;
            color: inherit;
        }

        table th a:hover {
            color: #007bff;
        }

        .btn-group {
            white-space: nowrap;
        }

        .modal {
            overflow-y: auto;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item .page-link {
            padding: 0.5rem 0.75rem;
            margin: 0 2px;
            border-radius: 4px;
        }

        .pagination-wrapper .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Enhanced Pagination Styles */
        .pagination-wrapper {
            display: flex;
            align-items: center;
        }

        .pagination-wrapper .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 2px;
        }

        .pagination-wrapper .page-item .page-link {
            padding: 0.5rem 0.75rem;
            margin: 0;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #4a5568;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            min-width: 38px;
            text-align: center;
        }

        .pagination-wrapper .page-item .page-link:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
            color: #2d3748;
            z-index: 2;
        }

        .pagination-wrapper .page-item.active .page-link {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border-color: #0056b3;
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .pagination-wrapper .page-item.disabled .page-link {
            color: #a0aec0;
            background-color: #f7fafc;
            border-color: #e2e8f0;
            pointer-events: none;
            opacity: 0.6;
        }

        .pagination-wrapper .page-item:first-child .page-link,
        .pagination-wrapper .page-item:last-child .page-link {
            padding: 0.5rem 1rem;
            font-weight: 500;
        }

        .pagination-wrapper .page-item:first-child .page-link {
            border-top-left-radius: 6px;
            border-bottom-left-radius: 6px;
        }

        .pagination-wrapper .page-item:last-child .page-link {
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        /* Loading state for pagination */
        .pagination-wrapper .page-link[wire\:loading] {
            opacity: 0.6;
            cursor: wait;
        }

        /* Per page selector */
        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            border: 1px solid #e2e8f0;
            background-color: #fff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control-sm:focus {
            border-color: #007bff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .pagination-wrapper .pagination {
                justify-content: center;
            }

            .pagination-wrapper .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
                min-width: 32px;
            }

            .pagination-wrapper .page-item:first-child .page-link,
            .pagination-wrapper .page-item:last-child .page-link {
                padding: 0.4rem 0.8rem;
            }

            .text-muted {
                font-size: 0.875rem;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .pagination-wrapper {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .pagination-wrapper .pagination {
                flex-wrap: nowrap;
                justify-content: flex-start;
            }

            .pagination-wrapper .page-link {
                padding: 0.3rem 0.5rem;
                font-size: 0.7rem;
                min-width: 28px;
            }
        }

        @media (max-width: 768px) {
            .pagination-wrapper .pagination {
                justify-content: center;
            }

            .pagination-wrapper .page-link {
                padding: 0.4rem 0.6rem;
                font-size: 0.875rem;
            }
        }
    </style>
</div>