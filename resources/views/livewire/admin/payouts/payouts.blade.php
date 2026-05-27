<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Payouts Management
                    </h3>
                </div>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-primary">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Overall Total</span>
                                <span class="info-box-number">KES {{ number_format($overallTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Payouts</span>
                                <span class="info-box-number">KES {{ number_format($pendingTotal, 2) }}</span>
                                <small>{{ $pendingCount }} pending</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-danger">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Approved</span>
                                <span class="info-box-number">KES {{ number_format($approvedTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Completed Payouts</span>
                                <span class="info-box-number">KES {{ number_format($completedTotal, 2) }}</span>
                                <small>{{ $completedCount }} completed</small>
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
                                        placeholder="Parcel #, Sender, Receiver, Partner...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Parcel Reference</label>
                                <input type="text" class="form-control"
                                    wire:model.live.debounce.300ms="reference"
                                    placeholder="e.g., PAR20240301123456">
                            </div>

                            <div class="col-md-4">
                                <label>Partner Name</label>
                                <input type="text" class="form-control"
                                    wire:model.live.debounce.300ms="partnerName"
                                    placeholder="Search by partner name">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Payout Type</label>
                                <select class="form-control" wire:model.live="type">
                                    @foreach($types as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Status</label>
                                <select class="form-control" wire:model.live="status">
                                    @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Destination Type</label>
                                <select class="form-control" wire:model.live="destinationType">
                                    @foreach($destinationTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Min Amount (KES)</label>
                                <input type="number" class="form-control" wire:model.live="minAmount" placeholder="0">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Max Amount (KES)</label>
                                <input type="number" class="form-control" wire:model.live="maxAmount" placeholder="1000000">
                            </div>

                            <div class="col-md-3">
                                <label>Created From</label>
                                <input type="date" class="form-control" wire:model.live="dateFrom">
                            </div>

                            <div class="col-md-3">
                                <label>Created To</label>
                                <input type="date" class="form-control" wire:model.live="dateTo">
                            </div>

                            <div class="col-md-3">
                                <label>Paid From</label>
                                <input type="date" class="form-control" wire:model.live="paidDateFrom">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Paid To</label>
                                <input type="date" class="form-control" wire:model.live="paidDateTo">
                            </div>
                        </div>

                        <!-- Active Filters Display -->
                        @if($search || $reference || $partnerName || $type || $status || $destinationType || $dateFrom || $dateTo || $paidDateFrom || $paidDateTo || $minAmount || $maxAmount)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="fas fa-filter mr-2"></i>
                                    <strong>Active Filters:</strong>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @if($search)
                                        <span class="badge badge-info">Search: {{ $search }}</span>
                                        @endif
                                        @if($reference)
                                        <span class="badge badge-info">Ref: {{ $reference }}</span>
                                        @endif
                                        @if($partnerName)
                                        <span class="badge badge-info">Partner: {{ $partnerName }}</span>
                                        @endif
                                        @if($type)
                                        <span class="badge badge-info">Type: {{ $type }}</span>
                                        @endif
                                        @if($status)
                                        <span class="badge badge-info">Status: {{ $status }}</span>
                                        @endif
                                        @if($destinationType)
                                        <span class="badge badge-info">Dest: {{ $destinationType }}</span>
                                        @endif
                                        @if($minAmount)
                                        <span class="badge badge-info">Min: {{ $minAmount }}</span>
                                        @endif
                                        @if($maxAmount)
                                        <span class="badge badge-info">Max: {{ $maxAmount }}</span>
                                        @endif
                                        @if($dateFrom)
                                        <span class="badge badge-info">Created From: {{ $dateFrom }}</span>
                                        @endif
                                        @if($dateTo)
                                        <span class="badge badge-info">Created To: {{ $dateTo }}</span>
                                        @endif
                                        @if($paidDateFrom)
                                        <span class="badge badge-info">Paid From: {{ $paidDateFrom }}</span>
                                        @endif
                                        @if($paidDateTo)
                                        <span class="badge badge-info">Paid To: {{ $paidDateTo }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Bulk Actions -->
                @if(count($selectedPayouts) > 0)
                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ count($selectedPayouts) }} payout(s) selected
                        </span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-success"
                                wire:click="openBulkActionModal('process')">
                                <i class="fas fa-check mr-1"></i> Process
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                wire:click="openBulkActionModal('cancel')">
                                <i class="fas fa-times mr-1"></i> Cancel
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
                                    <a href="#" wire:click.prevent="sortBy('id')">
                                        ID
                                        @if($sortField === 'id')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('parcel_id')">
                                        Parcel
                                        @if($sortField === 'parcel_id')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Partner</th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('type')">
                                        Type
                                        @if($sortField === 'type')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('amount')">
                                        Amount
                                        @if($sortField === 'amount')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>Origin/Destination</th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('status')">
                                        Status
                                        @if($sortField === 'status')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('created_at')">
                                        Created
                                        @if($sortField === 'created_at')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="#" wire:click.prevent="sortBy('paid_out_on')">
                                        Paid On
                                        @if($sortField === 'paid_out_on')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </a>
                                </th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payouts as $payout)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        wire:model.live="selectedPayouts"
                                        value="{{ $payout->id }}"
                                        @if($payout->status !== 'pending') disabled @endif>
                                </td>
                                <td>{{ $payout->id }}</td>
                                <td>
                                    @if($payout->parcel)
                                    <a href="{{ route('admin.parcels.view', $payout->parcel_id) }}" class="text-primary">
                                        {{ $payout->parcel->parcel_id }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit($payout->parcel->sender_name, 15) }} →
                                        {{ Str::limit($payout->parcel->receiver_name, 15) }}
                                    </small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payout->partner)
                                    <strong>{{ $payout->partner->company_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payout->partner->contact_person ?? 'N/A' }}</small>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                    @if($payout->type === 'pickup-dropoff') badge-primary
                                    @elseif($payout->type === 'transport') badge-info
                                    @elseif($payout->type === 'delivery') badge-success
                                    @else badge-secondary @endif">
                                        {{ ucfirst(str_replace('-', ' ', $payout->type)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-success">
                                        KES {{ number_format($payout->amount, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($payout->type === 'pickup-dropoff')
                                    <small>
                                        <i class="fas fa-map-marker-alt text-primary mr-1"></i>
                                        {{ $payout->origin->name ?? 'N/A' }}
                                    </small>
                                    @elseif($payout->type === 'transport')
                                    <small>
                                        <i class="fas fa-truck text-info mr-1"></i>
                                        {{ $payout->destination->name ?? 'N/A' }}
                                    </small>
                                    @else
                                    <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge 
                                    @if($payout->status === 'pending') badge-warning
                                    @elseif($payout->status === 'paid') badge-success
                                    @elseif($payout->status === 'cancelled') badge-danger
                                    @else badge-secondary @endif">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td>{{ $payout->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($payout->paid_out_on)
                                    {{ \Carbon\Carbon::parse($payout->paid_out_on)->format('d/m/Y H:i') }}
                                    @else
                                    <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-info"
                                            wire:click="viewPayout({{ $payout->id }})"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($payout->status === 'approved')
                                        <button class="btn btn-sm btn-success"
                                            wire:click="processPayout({{ $payout->id }})"
                                            title="Process Payout">
                                            <i class="fas fa-check-circle"></i>
                                        </button>

                                        <!-- <button class="btn btn-sm btn-warning"
                                            wire:click="cancelPayout({{ $payout->id }})"
                                            title="Cancel Payout"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-times-circle"></i>
                                        </button> -->
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-5">
                                    <i class="fas fa-money-bill-wave fa-4x text-muted mb-3"></i>
                                    <h5>No payouts found</h5>
                                    <p class="text-muted">
                                        Try adjusting your search filters.
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                    <div class="text-muted mb-2 mb-md-0">
                        Showing {{ $payouts->firstItem() }} to {{ $payouts->lastItem() }}
                        of {{ $payouts->total() }} entries
                    </div>
                    <div class="pagination-wrapper">
                        {{ $payouts->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- View Payout Modal -->
        @if($showViewModal && $selectedPayout)
        <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Payout Details - #{{ $selectedPayout->id }}
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
                                        <h6 class="mb-0">Payout Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th>ID:</th>
                                                <td>{{ $selectedPayout->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Amount:</th>
                                                <td class="font-weight-bold text-success">KES {{ number_format($selectedPayout->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Type:</th>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ ucfirst(str_replace('-', ' ', $selectedPayout->type)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    <span class="badge 
                                                    @if($selectedPayout->status === 'paid') badge-success
                                                    @elseif($selectedPayout->status === 'pending') badge-warning
                                                    @else badge-danger @endif">
                                                        {{ ucfirst($selectedPayout->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created:</th>
                                                <td>{{ $selectedPayout->created_at->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                            @if($selectedPayout->paid_out_on)
                                            <tr>
                                                <th>Paid On:</th>
                                                <td>{{ \Carbon\Carbon::parse($selectedPayout->paid_out_on)->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                            @endif
                                            @if($selectedPayout->cancelation_reason)
                                            <tr>
                                                <th>Cancellation Reason:</th>
                                                <td class="text-danger">{{ $selectedPayout->cancelation_reason }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Parcel Information</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($selectedPayout->parcel)
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Parcel #:</th>
                                                <td>
                                                    <a href="{{ route('admin.parcels.view', $selectedPayout->parcel_id) }}">
                                                        {{ $selectedPayout->parcel->parcel_id }}
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sender:</th>
                                                <td>{{ $selectedPayout->parcel->sender_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Receiver:</th>
                                                <td>{{ $selectedPayout->parcel->receiver_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>From/To:</th>
                                                <td>
                                                    {{ $selectedPayout->parcel->senderTown->name ?? 'N/A' }} →
                                                    {{ $selectedPayout->parcel->receiverTown->name ?? 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                        @else
                                        <p class="text-muted">No parcel associated</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">Partner Information</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($selectedPayout->partner)
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Company:</th>
                                                <td>{{ $selectedPayout->partner->company_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Contact Person:</th>
                                                <td>{{ $selectedPayout->partner->contact_person ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone:</th>
                                                <td>{{ $selectedPayout->partner->phone_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email:</th>
                                                <td>{{ $selectedPayout->partner->email ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                        @else
                                        <p class="text-muted">No partner associated</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">Location Details</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($selectedPayout->type === 'pickup-dropoff' && $selectedPayout->origin)
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Pickup Point:</th>
                                                <td>{{ $selectedPayout->origin?->name }}</td>
                                            </tr>
                                            @if($selectedPayout->origin?->address)
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $selectedPayout->origin?->address }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                        @elseif($selectedPayout->parcelDestination)
                                        <table class="table table-sm">
                                            <tr>
                                                <th>Destination:</th>
                                                <td>{{ $selectedPayout->parcelDestination?->name }}</td>
                                            </tr>
                                            @if($selectedPayout->parcelDestination?->address)
                                            <tr>
                                                <th>Address:</th>
                                                <td>{{ $selectedPayout->parcelDestination?->address }}</td>
                                            </tr>
                                            @endif
                                        </table>
                                        @else
                                        <p class="text-muted">No location details</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($selectedPayout->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-secondary text-white">
                                        <h6 class="mb-0">Notes</h6>
                                    </div>
                                    <div class="card-body">
                                        {{ $selectedPayout->notes }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if($selectedPayout->status === 'approved')
                        <button type="button" class="btn btn-success"
                            wire:click="processPayout({{ $selectedPayout->id }})"
                            wire:click="$set('showViewModal', false)">
                            <i class="fas fa-check-circle mr-1"></i> Process Payout
                        </button>
                        @endif
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showViewModal', false)">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Process Payout Modal -->
        @if($showProcessModal && $selectedPayout)
        <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
            style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-check-circle mr-2"></i>
                            Process Payout
                        </h5>
                        <button type="button" class="close text-white" wire:click="$set('showProcessModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="confirmProcess">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Parcel Reference</label>
                                <input type="text" class="form-control"
                                    value="{{ $selectedPayout->parcel->parcel_id ?? 'N/A' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Partner</label>
                                <input type="text" class="form-control"
                                    value="{{ $selectedPayout->partner->company_name ?? 'N/A' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Amount (KES)</label>
                                <input type="number" step="0.01" class="form-control"
                                    wire:model="processAmount" required readonly>
                                @error('processAmount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Payment Date</label>
                                <input type="date" class="form-control"
                                    wire:model="processDate" required>
                                @error('processDate') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Reference / Transaction ID</label>
                                <input type="text" class="form-control"
                                    wire:model="processReference"
                                    placeholder="Bank transaction ref, M-Pesa code, etc.">
                                @error('processReference') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" rows="3"
                                    wire:model="processNotes"
                                    placeholder="Additional payment notes..."></textarea>
                                @error('processNotes') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showProcessModal', false)">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle mr-1"></i> Confirm Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Delete Modal -->
        @if($showDeleteModal && $selectedPayout)
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
                        <p>Are you sure you want to delete this payout?</p>
                        <ul class="text-danger">
                            <li>ID: {{ $selectedPayout->id }}</li>
                            <li>Amount: KES {{ number_format($selectedPayout->amount, 2) }}</li>
                            <li>Partner: {{ $selectedPayout->partner->company_name ?? 'N/A' }}</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="delete">
                            <i class="fas fa-trash mr-1"></i> Delete Payout
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
                            {{ ucfirst($bulkAction) }} Payouts
                        </h5>
                        <button type="button" class="close" wire:click="$set('showBulkActionModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to {{ $bulkAction }} <strong>{{ count($selectedPayouts) }}</strong> payout(s).</p>
                        <p class="text-warning">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            This action will affect all selected payouts.
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

            .bg-gradient-warning {
                background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
                color: white;
            }

            .bg-gradient-success {
                background: linear-gradient(135deg, #28a745 0%, #218838 100%);
                color: white;
            }

            .bg-gradient-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
</div>