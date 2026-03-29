<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Towns
            </h3>
            <a href="{{ route('admin.towns.create') }}" class="btn btn-success btn-sm float-right">New </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <div>
                <div class="row">
                    <div class="col-4">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control mb-3" placeholder="Search by town name...">
                    </div>
                </div>
            </div>

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Town</th>
                        <th>Sub County</th>
                        <th>County</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($towns as $town)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $town->name }}</td>
                            <td>{{ $town->subCounty->name }}</td>
                            <td>{{ $town->subCounty->county->name }}</td>
                            <td>
                                @if ($town->status == true)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.towns.edit', $town->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <button class="btn btn-sm btn-danger"
                                    wire:click="confirm({{ $town->id }})">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Town</th>
                        <th>Sub County</th>
                        <th>County</th>
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
                                <h5 class="modal-title">Delete Record</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <p>Are you sure you want to delete this record? This operation is not
                                    reversible.</p>
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
            {{ $towns->links() }}
        </div>
    </div>
</div>
