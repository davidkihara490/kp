<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Pick Up And Drop Off Points
            </h3>
            <!-- <a href="{{ route('admin.points.create') }}" class="btn btn-success btn-sm float-right">New</a> -->
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Town</th>
                        <th>Station Partner</th>
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
                            <td>{{ $station->town->name ?? 'N/A' }}</td>
                            <td>{{ $station->stationPartner->name ?? 'N/A' }}</td>
                            <td>{{ $station->contact_email ?? 'N/A' }}</td>
                            <td>{{ $station->contact_phone_number ?? 'N/A' }}</td>
                            <td>
                                {{-- Assuming you have a parcels relationship --}}
                                {{-- {{ $station->parcels->count() ?? 0 }} --}}
                                {{-- Or if you have a method to count parcels --}}
                                0 {{-- Placeholder for parcels count --}}
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
                                <a href="{{ route('admin.points.view', $station->id) }}" class="btn btn-sm btn-primary"
                                    title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.points.edit', $station->id) }}"
                                    class="btn btn-sm btn-info"title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button class="btn btn-sm btn-danger" wire:click="confirm({{ $station->id }})"
                                    title="{{ __('Delete') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No points found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
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
                                <p><strong>Point:</strong> {{ $stationToDeleteName ?? '' }}</p>
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
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                // Disable DataTable's pagination if using Laravel pagination
                "paging": false,
                // Enable server-side processing if needed
                // "serverSide": true,
            });

            // Modal close on escape key
            $(document).keyup(function(e) {
                if (e.key === "Escape" && @this.showDeleteModal) {
                    @this.set('showDeleteModal', false);
                }
            });
        });

        // Confirm before delete
        window.addEventListener('show-delete-confirmation', event => {
            if (confirm('Are you sure you want to delete this station?')) {
                @this.delete(event.detail.id);
            }
        });
    </script>
@endsection
