<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                Terms & Conditions
            </h3>
            <a href="{{ route('admin.terms.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus"></i> New Terms
            </a>
        </div>

        <div class="card-body">

            @include('components.alerts.response-alerts')

            {{-- Filters --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <select wire:model="localeFilter" class="form-control form-control-sm">
                        <option value="">All Languages</option>
                        <option value="en">English</option>
                        <option value="es">Spanish</option>
                        <option value="fr">French</option>
                        <option value="de">German</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model="statusFilter" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" wire:model.debounce.300ms="search" class="form-control form-control-sm" 
                           placeholder="Search by title or version...">
                </div>
            </div>

            <table id="terms-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Version</th>
                        <th>Locale</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Requires Acceptance</th>
                        <th>Acceptances</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terms as $term)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $term->title }}
                                @if($term->trashed())
                                    <span class="badge badge-warning">Deleted</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $term->version ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge badge-secondary">
                                    {{ strtoupper($term->locale) }}
                                </span>
                            </td>
                            <td>
                                @if($term->effective_date)
                                    {{ $term->effective_date->format('M d, Y') }}
                                    @if($term->effective_date->isFuture())
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                @if ($term->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($term->requires_acceptance)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">
                                    {{ $term->acceptances_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.terms.view', $term->id) }}"
                                        class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.terms.edit', $term->id) }}"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($term->is_active)
                                        <button class="btn btn-sm btn-warning" 
                                            wire:click="deactivate({{ $term->id }})"
                                            title="Deactivate"
                                            onclick="confirm('Are you sure you want to deactivate this version?') || event.stopImmediatePropagation()">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success" 
                                            wire:click="activate({{ $term->id }})"
                                            title="Activate"
                                            onclick="confirm('This will deactivate other active versions. Continue?') || event.stopImmediatePropagation()">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    @endif

                                    @if($term->trashed())
                                        <button class="btn btn-sm btn-secondary" 
                                            wire:click="restore({{ $term->id }})"
                                            title="Restore"
                                            onclick="confirm('Restore this terms?') || event.stopImmediatePropagation()">
                                            <i class="fas fa-trash-restore"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" 
                                            wire:click="forceDelete({{ $term->id }})"
                                            title="Permanently Delete"
                                            onclick="confirm('This action is irreversible! Continue?') || event.stopImmediatePropagation()">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-danger" 
                                            wire:click="confirmDelete({{ $term->id }})"
                                            title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-3">
                                    <i class="fas fa-file-contract fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No terms and conditions found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Version</th>
                        <th>Locale</th>
                        <th>Effective Date</th>
                        <th>Status</th>
                        <th>Requires Acceptance</th>
                        <th>Acceptances</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>

            {{-- Delete Confirmation Modal --}}
            @if ($showDeleteModal)
                <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
                    style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Terms & Conditions</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>"{{ $selectedTermTitle }}"</strong>?</p>
                                <p class="text-danger mb-0"><small>This operation can be reversed from the trash if soft deletes are enabled.</small></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showDeleteModal', false)">Cancel</button>
                                <button type="button" class="btn btn-danger" wire:click="delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Version History Modal (Optional) --}}
            @if ($showVersionModal)
                <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
                    style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Version History - {{ $selectedTermTitle }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                    wire:click="$set('showVersionModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Version</th>
                                            <th>Locale</th>
                                            <th>Effective Date</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($versionHistory as $version)
                                            <tr>
                                                <td>{{ $version->version }}</td>
                                                <td>{{ strtoupper($version->locale) }}</td>
                                                <td>{{ $version->effective_date ? $version->effective_date->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    @if($version->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $version->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showVersionModal', false)">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-footer clearfix">
            <div class="float-left">
                <small class="text-muted">
                    Showing {{ $terms->firstItem() ?? 0 }} to {{ $terms->lastItem() ?? 0 }} 
                    of {{ $terms->total() }} entries
                </small>
            </div>
            <div class="float-right">
                {{ $terms->links() }}
            </div>
        </div>
    </div>
</div>