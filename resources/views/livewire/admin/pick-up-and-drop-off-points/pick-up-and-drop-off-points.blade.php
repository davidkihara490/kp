<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Pick Up And Drop Off Points
            </h3>
            <a class="btn btn-success btn-sm float-right ml-2" href="{{ route('admin.points.create') }}"><i class="fas fa-plus"></i>New Warehouse</a>
            <button class="btn btn-info btn-sm float-right" wire:click="$toggle('showFilters')">
                <i class="fas fa-filter"></i> {{ $showFilters ? 'Hide Filters' : 'Show Filters' }}
            </button>
        </div>

        <div class="card-header bg-light">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="search">Search by Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control"
                                wire:model.live.debounce.300ms="search"
                                id="search"
                                placeholder="Search points...">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="town">Filter by Town</label>
                        <select class="form-control" wire:model.live="selectedTown" id="town">
                            <option value="">All Towns</option>
                            @foreach($towns as $town)
                            <option value="{{ $town->id }}">{{ $town->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="partner">Filter by Partner</label>
                        <select class="form-control" wire:model.live="selectedPartner" id="partner">
                            <option value="">All Partners</option>
                            @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Filter by Status</label>
                        <select class="form-control" wire:model.live="selectedStatus" id="status">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Filter by Type</label>
                        <select class="form-control" wire:model.live="selectedType" id="type">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Filter by Number of Parcels</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="number" class="form-control"
                                    wire:model.live="minParcels"
                                    placeholder="Min parcels"
                                    min="0">
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control"
                                    wire:model.live="maxParcels"
                                    placeholder="Max parcels"
                                    min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button class="btn btn-secondary" wire:click="resetFilters">
                                <i class="fas fa-undo"></i> Reset All Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Active Filters Display -->
            @if($search || $selectedTown || $selectedPartner || $selectedStatus || $minParcels || $maxParcels)
            <div class="alert alert-info">
                <strong>Active Filters:</strong>
                @if($search) <span class="badge badge-primary">Search: {{ $search }}</span> @endif
                @if($selectedTown) <span class="badge badge-primary">Town: {{ $towns->find($selectedTown)->name ?? 'N/A' }}</span> @endif
                @if($selectedPartner) <span class="badge badge-primary">Partner: {{ $partners->find($selectedPartner)->company_name ?? 'N/A' }}</span> @endif
                @if($selectedStatus) <span class="badge badge-primary">Status: {{ ucfirst($selectedStatus) }}</span> @endif
                @if($minParcels) <span class="badge badge-primary">Min Parcels: {{ $minParcels }}</span> @endif
                @if($maxParcels) <span class="badge badge-primary">Max Parcels: {{ $maxParcels }}</span> @endif
                <button class="btn btn-sm btn-link" wire:click="resetFilters">Clear All</button>
            </div>
            @endif

            <div class="table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Town</th>
                            <th>Partner</th>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                            <th>Parcels</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pickUpAndDropOffPoints as $station)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $station->name }}</td>
                            <td>{{ $station->type }}</td>
                            <td>{{ $station->town->name ?? 'N/A' }}</td>
                            <td>{{ $station->partner->company_name ?? 'N/A' }}</td>
                            <td>{{ $station->contact_email ?? 'N/A' }}</td>
                            <td>{{ $station->contact_phone_number ?? 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $station->parcels_count > 0 ? 'badge-info' : 'badge-secondary' }}">
                                    {{ $station->parcels_count ?? 0 }} parcels
                                </span>
                            </td>
                            <td>
                                @if ($station->status == 'active')
                                <span class="badge badge-success">Active</span>
                                @elseif ($station->status == 'inactive')
                                <span class="badge badge-danger">Inactive</span>
                                @elseif ($station->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                                @elseif ($station->status == 'suspended')
                                <span class="badge badge-info">Suspended</span>
                                @else
                                <span class="badge badge-secondary">{{ $station->status }}</span>
                                @endif
                            </td>
                            <td>
                                @if(Auth::guard('admin')->user()->can('pickup-and-dropoff-point.update'))
                                <a href="{{ route('admin.points.view', $station->id) }}" class="btn btn-sm btn-primary"
                                    title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                @if(Auth::guard('admin')->user()->can('pickup-and-dropoff-point.update'))
                                <a href="{{ route('admin.points.edit', $station->id) }}"
                                    class="btn btn-sm btn-info" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(Auth::guard('admin')->user()->can('pickup-and-dropoff-point.update'))
                                <button class="btn btn-sm btn-danger" wire:click="confirm({{ $station->id }})"
                                    title="{{ __('Delete') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="alert alert-info mb-0">
                                    No points found matching your criteria
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Town</th>
                            <th>Station Partner</th>
                            <th>Contact Email</th>
                            <th>Contact Phone</th>
                            <th>Parcels</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Delete Modal -->
            @if ($showDeleteModal)
            <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
                style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Point</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                wire:click="$set('showDeleteModal', false)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this point? This operation is not reversible.</p>
                            <p><strong>Point:</strong> {{ $stationToDeleteName }}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showDeleteModal', false)">Cancel</button>
                            <button type="button" class="btn btn-danger" wire:click="delete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="card-footer">
            {{ $pickUpAndDropOffPoints->links() }}
        </div>
    </div>
</div>

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<style>
    .badge {
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .filter-section {
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(function() {
        // Initialize DataTable
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
            "paging": false, // Disable DataTable pagination since we use Laravel pagination
            "searching": false, // Disable DataTable search since we use Livewire search
            "ordering": true,
            "info": false,
        });
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape" && @this.showDeleteModal) {
            @this.set('showDeleteModal', false);
        }
    });
</script>
@endsection