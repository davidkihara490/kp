<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Users
            </h3>
            @if(Auth::guard('admin')->user()->can('user.update'))

            <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm float-right">New </a>

            @endif
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name </th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>User Type</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->getFullName() }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->user_type }}</td>
                        <td>{{ $user->getRoleNames()->first() ? ucfirst($user->getRoleNames()->first()) : '--' }}</td>
                        <td>
                            @if(Auth::guard('admin')->user()->can('user.update'))
                            <a href="{{ $user->user_type == 'admin'  ? route('admin.users.edit', $user->id)  : '#' }}"
                                class="btn btn-sm btn-info {{ $user->user_type == 'admin' ? '' : 'disabled' }}"
                                {{ $user->user_type == 'admin' ? '' : 'onclick=return false;' }}>
                                Edit
                            </a>
                            @endif
                            @if(Auth::guard('admin')->user()->can('user.update'))
                            <button class="btn btn-sm btn-danger"
                                wire:click="confirm({{ $user->id }})" {{ $user->user_type == 'admin' ? '' : 'disabled' }}>Delete</button>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name </th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
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
            {{ $users->links() }}
        </div>
    </div>
</div>