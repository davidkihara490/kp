<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-tags mr-2"></i>Blog Tags
            </h3>
            <a href="{{ route('admin.blog-tags.create') }}" class="btn btn-success btn-sm float-right">
                <i class="fas fa-plus mr-2"></i>New Tag
            </a>
        </div>

        <div class="card-body">
            @include('components.alerts.response-alerts')

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control"
                            placeholder="Search tags by name, slug, or description..."
                            wire:model.live.debounce.300ms="search">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary btn-block" wire:click="resetFilters" title="Reset Filters">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
                <div class="col-md-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-block dropdown-toggle" type="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#"><i class="fas fa-file-excel mr-2"></i>Excel</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-csv mr-2"></i>CSV</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-pdf mr-2"></i>PDF</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-tags"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Tags</span>
                            <span class="info-box-number">{{ \App\Models\BlogTag::count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-fire"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Popular Tags</span>
                            <span
                                class="info-box-number">{{ \App\Models\BlogTag::where('post_count', '>=', 20)->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Trending Tags</span>
                            <span
                                class="info-box-number">{{ \App\Models\BlogTag::whereBetween('post_count', [5, 19])->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-secondary"><i class="fas fa-seedling"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">New Tags</span>
                            <span
                                class="info-box-number">{{ \App\Models\BlogTag::where('post_count', '<', 5)->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th wire:click="sortBy('name')" style="cursor: pointer; width: 25%;">
                                Tag Name
                                @if ($sortField !== 'name')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th style="width: 15%;">Slug</th>
                            <th wire:click="sortBy('post_count')" style="cursor: pointer; width: 15%;">
                                Posts
                                @if ($sortField !== 'post_count')
                                    <i class="fas fa-sort text-muted"></i>
                                @elseif($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            </th>
                            <th style="width: 20%;">Popularity</th>
                            <th style="width: 10%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                            @php
                                $popularity = $this->getPopularityBadge($tag->post_count);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <span class="badge badge-{{ $popularity['color'] }} p-2">
                                                <i class="fas {{ $popularity['icon'] }} fa-lg"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $tag->name }}</strong>
                                            @if ($tag->meta_title)
                                                <br>
                                                <small class="text-muted">
                                                    <i
                                                        class="fas fa-search mr-1"></i>{{ Str::limit($tag->meta_title, 40) }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="badge badge-light">{{ $tag->slug }}</code>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong class="d-block display-4">{{ $tag->post_count }}</strong>
                                        <small class="text-muted">posts</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-2">
                                        <span class="badge badge-{{ $popularity['color'] }}">
                                            <i class="fas {{ $popularity['icon'] }} mr-1"></i>
                                            {{ $popularity['text'] }}
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        @php
                                            $width = min(
                                                100,
                                                ($tag->post_count / max(1, $tags->max('post_count'))) * 100,
                                            );
                                        @endphp
                                        <div class="progress-bar bg-{{ $popularity['color'] }}" role="progressbar"
                                            style="width: {{ $width }}%"
                                            aria-valuenow="{{ $tag->post_count }}" aria-valuemin="0"
                                            aria-valuemax="{{ $tags->max('post_count') }}">
                                        </div>
                                    </div>
                                    <small class="text-muted">Relative popularity</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.blog-tags.view', $tag->id) }}" class="btn btn-info"
                                            title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.blog-tags.edit', $tag->id) }}" class="btn btn-warning"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger"
                                            wire:click="confirmDelete({{ $tag->id }})" title="Delete"
                                            {{ $tag->posts()->count() > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @if ($tag->posts()->count() > 0)
                                        <small class="text-danger d-block mt-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Used in {{ $tag->posts()->count() }} posts
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No tags found</p>
                                        <a href="{{ route('admin.blog-tags.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Create First Tag
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Delete Modal -->
            @if ($showDeleteModal && $tagToDelete)
                <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Tag</h5>
                                <button type="button" class="close" wire:click="$set('showDeleteModal', false)">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>{{ $tagToDelete->name }}</strong>?</p>

                                @if ($tagToDelete->posts()->count() > 0)
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        This tag is used in {{ $tagToDelete->posts()->count() }} blog posts.
                                        You must detach it from all posts first.
                                    </div>
                                @endif

                                @if ($tagToDelete->posts()->count() === 0)
                                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showDeleteModal', false)">
                                    Cancel
                                </button>
                                @if ($tagToDelete->posts()->count() === 0)
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
                        Showing {{ $tags->firstItem() }} to {{ $tags->lastItem() }}
                        of {{ $tags->total() }} tags
                    </p>
                </div>
                <div class="col-md-6">
                    {{ $tags->links() }}
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

        .info-box {
            background: #fff;
            border-radius: 0.25rem;
            padding: 15px;
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            margin-bottom: 0;
        }

        .info-box-icon {
            border-radius: 0.25rem;
            float: left;
            height: 70px;
            width: 70px;
            text-align: center;
            font-size: 30px;
            line-height: 70px;
            background: rgba(0, 0, 0, 0.2);
        }

        .info-box-content {
            padding: 5px 10px;
            margin-left: 80px;
        }

        .info-box-text {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 14px;
            color: #6c757d;
        }

        .info-box-number {
            font-weight: 700;
            font-size: 22px;
            color: #343a40;
        }

        .progress {
            background-color: #e9ecef;
        }
    </style>
@endpush
