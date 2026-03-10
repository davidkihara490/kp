<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-credit-card mr-2"></i>
                    Payments Management
                </h3>

                <div class="d-flex gap-2">
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
                        <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Today</span>
                            <span class="info-box-number">KES {{ number_format($todayTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Month</span>
                            <span class="info-box-number">KES {{ number_format($monthTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-warning">
                        <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">This Year</span>
                            <span class="info-box-number">KES {{ number_format($yearTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Overall</span>
                            <span class="info-box-number">KES {{ number_format($overallTotal, 2) }}</span>
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
                                    placeholder="Ref, Phone, Sender, Receiver...">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label>Reference Number</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="reference"
                                placeholder="e.g., MP20240301123456">
                        </div>

                        <div class="col-md-4">
                            <label>M-Pesa Code</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="mpesaCode"
                                placeholder="e.g., PGI22L4QK7">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Paid By (Name/Email)</label>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="paidBy"
                                placeholder="Search by user name or email">
                        </div>

                        <div class="col-md-2">
                            <label>Status</label>
                            <select class="form-control" wire:model.live="status">
                                @foreach($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Payment Method</label>
                            <select class="form-control" wire:model.live="paymentMethod">
                                @foreach($paymentMethods as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>Date From</label>
                            <input type="date" class="form-control" wire:model.live="dateFrom">
                        </div>

                        <div class="col-md-2">
                            <label>Date To</label>
                            <input type="date" class="form-control" wire:model.live="dateTo">
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if($search || $reference || $mpesaCode || $paidBy || $status || $paymentMethod || $dateFrom || $dateTo)
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
                                    @if($mpesaCode)
                                    <span class="badge badge-info">M-Pesa: {{ $mpesaCode }}</span>
                                    @endif
                                    @if($paidBy)
                                    <span class="badge badge-info">Paid By: {{ $paidBy }}</span>
                                    @endif
                                    @if($status)
                                    <span class="badge badge-info">Status: {{ $status }}</span>
                                    @endif
                                    @if($paymentMethod)
                                    <span class="badge badge-info">Method: {{ $paymentMethod }}</span>
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
            @if(count($selectedPayments) > 0)
            <div class="alert alert-info mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ count($selectedPayments) }} payment(s) selected
                    </span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-success"
                            wire:click="openBulkActionModal('complete')">
                            <i class="fas fa-check mr-1"></i> Complete
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning"
                            wire:click="openBulkActionModal('refund')">
                            <i class="fas fa-undo mr-1"></i> Refund
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
                                <a href="#" wire:click.prevent="sortBy('reference_number')">
                                    Reference #
                                    @if($sortField === 'reference_number')
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
                            <th>
                                <a href="#" wire:click.prevent="sortBy('amount')">
                                    Amount
                                    @if($sortField === 'amount')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>M-Pesa Code</th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('payment_method')">
                                    Method
                                    @if($sortField === 'payment_method')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('payment_date')">
                                    Date
                                    @if($sortField === 'payment_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('status')">
                                    Status
                                    @if($sortField === 'status')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Phone</th>
                            <th>Paid By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $payment)
                        <tr>
                            <td>
                                <input type="checkbox"
                                    wire:model.live="selectedPayments"
                                    value="{{ $payment->id }}">
                            </td>
                            <td>
                                <span class="font-weight-bold">{{ $payment->reference_number }}</span>
                            </td>
                            <td>
                                @if($payment->parcel)
                                <a href="{{ route('admin.parcels.view', $payment->parcel_id) }}" class="text-primary">
                                    {{ $payment->parcel->parcel_id }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ Str::limit($payment->parcel->sender_name, 15) }} →
                                    {{ Str::limit($payment->parcel->receiver_name, 15) }}
                                </small>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-weight-bold text-success">
                                    KES {{ number_format($payment->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->isMpesa() && $payment->mpesaTransaction)
                                <span class="badge badge-info">
                                    {{ $payment->mpesaTransaction->mpesa_receipt_number }}
                                </span>
                                @elseif($payment->isMpesa())
                                <span class="badge badge-secondary">Pending</span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge 
                                    @if($payment->payment_method === 'mpesa') badge-primary
                                    @elseif($payment->payment_method === 'cash') badge-success
                                    @elseif($payment->payment_method === 'card') badge-info
                                    @elseif($payment->payment_method === 'bank_transfer') badge-warning
                                    @else badge-secondary @endif">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td>
                                {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td>
                                <span class="badge 
                                    @if($payment->status === 'completed') badge-success
                                    @elseif($payment->status === 'pending') badge-warning
                                    @elseif($payment->status === 'failed') badge-danger
                                    @elseif($payment->status === 'refunded') badge-info
                                    @else badge-secondary @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->phone)
                                {{ $payment->phone }}
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->paidBy)
                                {{ $payment->paidBy->name }}
                                <br>
                                <small class="text-muted">{{ $payment->paidBy->email }}</small>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info"
                                        wire:click="viewPayment({{ $payment->id }})"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger"
                                        wire:click="confirmDelete({{ $payment->id }})"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-5">
                                <i class="fas fa-credit-card fa-4x text-muted mb-3"></i>
                                <h5>No payments found</h5>
                                <p class="text-muted">
                                    Try adjusting your search filters or create a new payment.
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <!-- Pagination -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3">
                <div class="text-muted mb-2 mb-md-0">
                    Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }}
                    of {{ $payments->total() }} entries
                </div>
                <div class="pagination-wrapper">
                    {{ $payments->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

    <!-- View Payment Modal -->
    @if($showViewModal && $selectedPayment)
    <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
        style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payment Details - {{ $selectedPayment->reference_number }}
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
                                    <h6 class="mb-0">Payment Information</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Reference:</th>
                                            <td>{{ $selectedPayment->reference_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount:</th>
                                            <td class="font-weight-bold text-success">KES {{ number_format($selectedPayment->amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Method:</th>
                                            <td>
                                                <span class="badge 
                                                    @if($selectedPayment->payment_method === 'mpesa') badge-primary
                                                    @elseif($selectedPayment->payment_method === 'cash') badge-success
                                                    @else badge-info @endif">
                                                    {{ ucfirst($selectedPayment->payment_method) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge 
                                                    @if($selectedPayment->status === 'completed') badge-success
                                                    @elseif($selectedPayment->status === 'pending') badge-warning
                                                    @elseif($selectedPayment->status === 'failed') badge-danger
                                                    @else badge-secondary @endif">
                                                    {{ ucfirst($selectedPayment->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date:</th>
                                            <td>{{ $selectedPayment->payment_date?->format('d M Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $selectedPayment->phone ?? 'N/A' }}</td>
                                        </tr>
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
                                    @if($selectedPayment->parcel)
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Parcel #:</th>
                                            <td>
                                                <a href="{{ route('admin.parcels.view', $selectedPayment->parcel_id) }}">
                                                    {{ $selectedPayment->parcel->parcel_id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sender:</th>
                                            <td>{{ $selectedPayment->parcel->sender_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Receiver:</th>
                                            <td>{{ $selectedPayment->parcel->receiver_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>From/To:</th>
                                            <td>
                                                {{ $selectedPayment->parcel->senderTown->name ?? 'N/A' }} →
                                                {{ $selectedPayment->parcel->receiverTown->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    </table>
                                    @else
                                    <p class="text-muted">No parcel associated</p>
                                    @endif
                                </div>
                            </div>

                            @if($selectedPayment->isMpesa() && $selectedPayment->mpesaTransaction)
                            <div class="card mt-3">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">M-Pesa Transaction Details</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>Receipt:</th>
                                            <td>{{ $selectedPayment->mpesaTransaction->mpesa_receipt_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Transaction ID:</th>
                                            <td>{{ $selectedPayment->mpesaTransaction->transaction_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td>{{ $selectedPayment->mpesaTransaction->phone_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date:</th>
                                            <td>{{ $selectedPayment->mpesaTransaction->transaction_date?->format('d M Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($selectedPayment->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0">Notes</h6>
                                </div>
                                <div class="card-body">
                                    {{ $selectedPayment->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
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
    @if($showDeleteModal && $selectedPayment)
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
                    <p>Are you sure you want to delete this payment?</p>
                    <ul class="text-danger">
                        <li>Reference: {{ $selectedPayment->reference_number }}</li>
                        <li>Amount: KES {{ number_format($selectedPayment->amount, 2) }}</li>
                        <li>This action cannot be undone</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        wire:click="$set('showDeleteModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="delete">
                        <i class="fas fa-trash mr-1"></i> Delete Payment
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
                        {{ ucfirst($bulkAction) }} Payments
                    </h5>
                    <button type="button" class="close" wire:click="$set('showBulkActionModal', false)">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>You are about to {{ $bulkAction }} <strong>{{ count($selectedPayments) }}</strong> payment(s).</p>
                    <p class="text-warning">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        This action will affect all selected payments.
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

        .pagination-wrapper .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
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