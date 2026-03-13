<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold d-inline">
                    <i class="fas fa-file-alt mr-2"></i>Policies
                </h3>
                <a href="{{ route('admin.policy.create') }}" class="btn btn-primary btn-sm float-right">
                    <i class="fas fa-plus mr-2"></i>Create New Policy
                </a>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <input type="text"
                                class="form-control"
                                placeholder="Search policies..."
                                wire:model.live.debounce.300ms="search">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" wire:model.live="perPage">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>

                <!-- Policies Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th wire:click="sortBy('title')" style="cursor: pointer;">
                                    Title
                                    @if($sortField === 'title')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('version')" style="cursor: pointer;">
                                    Version
                                    @if($sortField === 'version')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('published_on')" style="cursor: pointer;">
                                    Published On
                                    @if($sortField === 'published_on')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                    @endif
                                </th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($policies as $policy)
                            <tr>
                                <td>{{ $policy->title }}</td>
                                <td><code>{{ $policy->version }}</code></td>
                                <td>{{ $policy->published_on ? $policy->published_on->format('M d, Y') : 'Not set' }}</td>
                                <td>
                                    @if($policy->is_active)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.policy.view', $policy->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.policy.edit', $policy->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No policies found.</p>
                                    <a href="{{ route('admin.policy.create') }}" class="btn btn-primary">
                                        Create your first policy
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $policies->links() }}
                </div>
            </div>
        </div>
    </div>
</div>