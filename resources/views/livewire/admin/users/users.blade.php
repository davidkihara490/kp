<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Users
            </h3>
            @if(Auth::guard('admin')->user()->can('user.update'))
                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm float-right">New User</a>
            @endif
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')
            
            <!-- Filters Section -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search" 
                           class="form-control" 
                           placeholder="Search by name, email, phone...">
                </div>
                
                <div class="col-md-2">
                    <select wire:model.live="userType" class="form-control">
                        <option value="">All User Types</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select wire:model.live="role" class="form-control">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <select wire:model.live="perPage" class="form-control">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                </div>
                
                <div class="col-md-1">
                    <button wire:click="resetFilters" class="btn btn-secondary btn-block" title="Reset Filters">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
            
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th wire:click="sortBy('id')" style="cursor: pointer;">
                            # 
                            @if($sortField === 'id')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('first_name')" style="cursor: pointer;">
                            Name
                            @if($sortField === 'first_name')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('email')" style="cursor: pointer;">
                            Email
                            @if($sortField === 'email')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('phone_number')" style="cursor: pointer;">
                            Phone
                            @if($sortField === 'phone_number')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('user_type')" style="cursor: pointer;">
                            User Type
                            @if($sortField === 'user_type')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('status')" style="cursor: pointer;">
                            Status
                            @if($sortField === 'status')
                                <i class="fas fa-sort-{{ $sortDirection === 'ASC' ? 'up' : 'down' }}"></i>
                            @endif
                        </th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->getFullName() }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number ?? '--' }}</td>
                        <td>
                            <span class="badge badge-info">{{ ucfirst($user->user_type ?? '--') }}</span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'pending' => 'info',
                                    'suspended' => 'danger'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$user->status] ?? 'secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td>{{ $user->getRoleNames()->first() ? ucfirst($user->getRoleNames()->first()) : '--' }}</td>
                        <td>
                            @if(Auth::guard('admin')->user()->can('user.update'))
                                <a href="{{ $user->user_type == 'admin' ? route('admin.users.edit', $user->id) : '#' }}"
                                    class="btn btn-sm btn-info {{ $user->user_type == 'admin' ? '' : 'disabled' }}">
                                    Edit
                                </a>
                            @endif
                            @if(Auth::guard('admin')->user()->can('user.update'))
                                <button class="btn btn-sm btn-danger"
                                    wire:click="confirm({{ $user->id }})" 
                                    {{ $user->user_type == 'admin' ? '' : 'disabled' }}>
                                    Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
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
                            <p>Are you sure you want to delete this record? This operation is not reversible.</p>
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