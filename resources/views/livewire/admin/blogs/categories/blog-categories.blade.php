<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-folder mr-2"></i>Blog Categories
            </h3>
            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus mr-2"></i>New Category
            </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search categories..." wire:model.live.debounce.300ms="search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model.live="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" wire:model.live="parentFilter">
                        <option value="">All Categories</option>
                        <option value="none">No Parent (Main Categories)</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary btn-block" wire:click="resetFilters" title="Reset Filters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th wire:click="sortBy('name')" style="cursor: pointer; width: 20%;">
                                Name 
                                @if($sortField !== 'name')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th style="width: 10%;">Slug</th>
                            <th style="width: 15%;">Parent</th>
                            <th wire:click="sortBy('order')" style="cursor: pointer; width: 8%;">
                                Order 
                                @if($sortField !== 'order')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('is_active')" style="cursor: pointer; width: 8%;">
                                Status 
                                @if($sortField !== 'is_active')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th style="width: 12%;">Posts</th>
                            <th style="width: 12%;">Sub-Categories</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            @php
                                $hasChildren = $category->children()->count() > 0;
                                $hasPosts = $category->posts()->count() > 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            @if($category->featured_image)
                                                <img src="{{ $category->featured_image }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="rounded" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-folder text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->description)
                                                <br>
                                                <small class="text-muted text-truncate d-block" style="max-width: 200px;">
                                                    {{ \Illuminate\Support\Str::limit($category->description, 50) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="badge badge-light">{{ $category->slug }}</code>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge badge-info">
                                            <i class="fas fa-level-up-alt mr-1"></i>
                                            {{ $category->parent->name }}
                                        </span>
                                    @else
                                        <span class="badge badge-primary">
                                            <i class="fas fa-home mr-1"></i>Main Category
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                wire:click="moveUp({{ $category->id }})"
                                                title="Move Up">
                                            <i class="fas fa-arrow-up"></i>
                                        </button>
                                        <span class="btn btn-light disabled">{{ $category->order }}</span>
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                wire:click="moveDown({{ $category->id }})"
                                                title="Move Down">
                                            <i class="fas fa-arrow-down"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }}">
                                        <i class="fas {{ $category->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block">{{ $category->posts_count ?? $category->posts()->count() }}</strong>
                                        <small class="text-muted">Posts</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block">{{ $category->children_count ?? $category->children()->count() }}</strong>
                                        <small class="text-muted">Sub-categories</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.blog-categories.view', $category->id) }}" 
                                           class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blog-categories.edit', $category->id) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-{{ $category->is_active ? 'danger' : 'success' }}" 
                                                wire:click="toggleStatus({{ $category->id }})" 
                                                title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $category->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                        </button>
                                        <button class="btn btn-danger" 
                                                wire:click="confirmDelete({{ $category->id }})" 
                                                title="Delete"
                                                {{ $hasChildren || $hasPosts ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @if($hasChildren || $hasPosts)
                                        <small class="text-danger d-block mt-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Cannot delete
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No categories found</p>
                                        <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Create First Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Delete Modal -->
            @if($showDeleteModal && $categoryToDelete)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Category</h5>
                                <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $categoryToDelete->name }}</strong>?</p>
                                
                                @if($categoryToDelete->children()->count() > 0)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        This category has {{ $categoryToDelete->children()->count() }} sub-categories. 
                                        You must delete or move them first.
                                    </div>
                                @endif
                                
                                @if($categoryToDelete->posts()->count() > 0)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        This category has {{ $categoryToDelete->posts()->count() }} blog posts. 
                                        You must reassign them first.
                                    </div>
                                @endif
                                
                                @if($categoryToDelete->children()->count() === 0 && $categoryToDelete->posts()->count() === 0)
                                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" 
                                        wire:click="$set('showDeleteModal', false)">
                                    Cancel
                                </button>
                                @if($categoryToDelete->children()->count() === 0 && $categoryToDelete->posts()->count() === 0)
                                    <button type="button" class="btn btn-danger" wire:click="delete">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-footer">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} 
                        of {{ $categories->total() }} categories
                    </p>
                </div>
                <div class="col-md-6">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .badge-light {
        background-color: #f8f9fa;
        color: #495057;
    }
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    code {
        font-size: 0.85em;
        padding: 2px 4px;
        border-radius: 3px;
    }
</style>
@endpush