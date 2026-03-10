<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Pricings
            </h3>
            <a href="{{ route('admin.pricing.create') }}" class="btn btn-success btn-sm float-right">New </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Min Weight</th>
                        <th>Max Weight</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pricings as $pricing)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pricing->item->name }}</td>
                        <td>{{ $pricing->min_weight }}</td>
                        <td>{{ $pricing->max_weight }}</td>

                        <td>
                            <a href="{{ route('admin.pricing.view', $pricing->id) }}" class="btn btn-sm btn-success">View</a>
                            <a href="{{ route('admin.pricing.edit', $pricing->id) }}" class="btn btn-sm btn-info">Edit</a>

                            <!-- <button class="btn btn-sm btn-danger"
                                    wire:click="confirm({{ $pricing->id }})">Delete</button> -->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No data was found</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Min Weight</th>
                        <th>Max Weight</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
            <!-- @if ($showDeleteModal)
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
            @endif -->
        </div>

        <div class="card-footer">
            {{ $pricings->links() }}
        </div>
    </div>
</div>