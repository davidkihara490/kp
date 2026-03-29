<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-users mr-2"></i>
                        Partners Management
                    </h3>

                    <!-- <div class="d-flex gap-2">
                        <a href="{{ route('admin.partners.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add New Partner
                        </a>

                        <button class="btn btn-outline-primary btn-sm" wire:click="exportPartners('csv')">
                            <i class="fas fa-download mr-1"></i> Export CSV
                        </button>

                        <button class="btn btn-outline-secondary btn-sm" wire:click="exportPartners('pdf')">
                            <i class="fas fa-file-pdf mr-1"></i> Export PDF
                        </button>
                    </div> -->
                </div>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-info">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Partners</span>
                                <span class="info-box-number">{{ $totalPartners }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-success">
                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Active Partners</span>
                                <span class="info-box-number">{{ $activePartners }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-warning">
                            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Pending Approval</span>
                                <span class="info-box-number">{{ $pendingPartners }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="info-box bg-gradient-primary">
                            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">This Month</span>
                                <span class="info-box-number">{{ \App\Models\Partner::whereMonth('created_at', now()->month)->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Simplified Filters -->
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-filter mr-2"></i>
                            Filters
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Partner Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-search"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control"
                                        wire:model.live.debounce.300ms="search"
                                        placeholder="Search...">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Town</label>
                                <select class="form-control" wire:model.live="town">
                                    <option value="">All Towns</option>
                                    @foreach($towns as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
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
                    </div>
                </div>

                @if(count($selectedPartners) > 0)
                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ count($selectedPartners) }} partner(s) selected
                        </span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-success"
                                wire:click="openBulkActionModal('activate')">
                                <i class="fas fa-check mr-1"></i> Activate
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                wire:click="openBulkActionModal('suspend')">
                                <i class="fas fa-pause mr-1"></i> Suspend
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                wire:click="openBulkActionModal('deactivate')">
                                <i class="fas fa-ban mr-1"></i> Deactivate
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info"
                                wire:click="openBulkActionModal('verify')">
                                <i class="fas fa-shield-alt mr-1"></i> Verify
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                wire:click="openBulkActionModal('delete')">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Partners Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" wire:model.live="selectAll">
                                </th>
                                <th width="60">#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Parcels Handled</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partners as $partner)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                        wire:model.live="selectedPartners"
                                        value="{{ $partner->id }}">
                                </td>
                                <td>{{ $loop->iteration + (($partners->currentPage() - 1) * $partners->perPage()) }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <strong>{{ $partner->company_name }}</strong>

                                    </div>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($partner->partner_type === 'transport') badge-primary
                                        @elseif($partner->partner_type === 'pickup-dropoff') badge-primary
                                        @endif">
                                        {{ ucfirst($partner->partner_type) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge 
                                        @if($partner->verification_status === 'active') badge-success
                                        @elseif($partner->verification_status === 'pending') badge-warning
                                        @elseif($partner->verification_status === 'suspended') badge-danger
                                        @elseif($partner->verification_status === 'verified') badge-success
                                        @else badge-secondary @endif">
                                        {{ ucfirst($partner->verification_status) }}
                                    </span>
                                </td>

                                <td>{{ 250 }}</td>


                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.partners.view', $partner->id) }}"
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.partners.edit', $partner->id) }}"
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button class="btn btn-sm btn-danger"
                                            wire:click="confirmDelete({{ $partner->id }})"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No partners found</h5>
                                    <p class="text-muted">Try adjusting your filters or add a new partner.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $partners->firstItem() }} to {{ $partners->lastItem() }}
                        of {{ $partners->total() }} entries
                    </div>
                    {{ $partners->links() }}
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        @if($showDeleteModal)
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
                        <p>Are you sure you want to delete this partner?</p>
                        <ul class="text-danger">
                            <li>This action cannot be undone</li>
                            <li>Associated user account will also be deleted</li>
                            <li>All related data will be removed</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="delete">
                            <i class="fas fa-trash mr-1"></i> Delete Partner
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
                            {{ ucfirst($bulkAction) }} Partners
                        </h5>
                        <button type="button" class="close" wire:click="$set('showBulkActionModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You are about to {{ $bulkAction }} <strong>{{ count($selectedPartners) }}</strong> partner(s).</p>
                        <p class="text-warning">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            This action will affect all selected partners.
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
    </div>

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
    </style>
</div>