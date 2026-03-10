<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold d-inline">
                    <i class="fas fa-newspaper mr-2"></i>Blog Posts
                </h3>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-success btn-sm float-right">
                    <i class="fas fa-plus mr-2"></i>New Post
                </a>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-primary"><i class="fas fa-newspaper"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Posts</span>
                                <span class="info-box-number">{{ $totalPosts }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Published</span>
                                <span class="info-box-number">{{ $publishedPosts }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-secondary"><i class="fas fa-edit"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Drafts</span>
                                <span class="info-box-number">{{ $draftPosts }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-trash"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Trashed</span>
                                <span class="info-box-number">{{ $trashedPosts }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search posts..."
                                wire:model.live.debounce.300ms="search">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model.live="statusFilter">
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model.live="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model.live="authorFilter">
                            <option value="">All Authors</option>
                            @foreach ($authors as $author)
                                <option value="">{{ $author }}</option>
                            @endforeach
                            {{-- @foreach ($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model.live="tagFilter">
                            <option value="">All Tags</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary btn-block" wire:click="resetFilters"
                            title="Reset Filters">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>
                </div>

                <!-- Bulk Actions -->
                @if ($showBulkActions)
                    <div class="alert alert-info mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tasks mr-2"></i>
                                <strong>{{ count($selectedPosts) }}</strong> posts selected
                            </div>
                            <div>
                                @if ($statusFilter === 'trashed')
                                    <button class="btn btn-sm btn-success mr-2" wire:click="bulkRestore">
                                        <i class="fas fa-undo mr-1"></i>Restore
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="bulkDelete">
                                        <i class="fas fa-trash mr-1"></i>Delete Permanently
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-success mr-2" wire:click="bulkPublish">
                                        <i class="fas fa-check-circle mr-1"></i>Publish
                                    </button>
                                    <button class="btn btn-sm btn-secondary mr-2" wire:click="bulkDraft">
                                        <i class="fas fa-edit mr-1"></i>Move to Draft
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="bulkDelete">
                                        <i class="fas fa-trash mr-1"></i>Move to Trash
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 30px;">
                                    @if ($statusFilter !== 'trashed')
                                        <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                                    @endif
                                </th>
                                <th wire:click="sortBy('title')" style="cursor: pointer; width: 30%;">
                                    Title
                                    @if ($sortField !== 'title')
                                        <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th style="width: 15%;">Category</th>
                                <th style="width: 10%;">Author</th>
                                <th wire:click="sortBy('status')" style="cursor: pointer; width: 10%;">
                                    Status
                                    @if ($sortField !== 'status')
                                        <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th wire:click="sortBy('created_at')" style="cursor: pointer; width: 15%;">
                                    Date
                                    @if ($sortField !== 'created_at')
                                        <i class="fas fa-sort text-muted"></i>
                                    @elseif($sortDirection === 'asc')
                                        <i class="fas fa-sort-up"></i>
                                    @else
                                        <i class="fas fa-sort-down"></i>
                                    @endif
                                </th>
                                <th style="width: 10%;">Stats</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                                @php
                                    $statusBadge = $this->getStatusBadge(
                                        $post->status,
                                        $post->published_at,
                                        $post->scheduled_for,
                                    );
                                @endphp
                                <tr>
                                    <td>
                                        @if ($statusFilter !== 'trashed')
                                            <input type="checkbox" wire:model.live="selectedPosts"
                                                value="{{ $post->id }}" class="form-check-input">
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start">
                                            @if ($post->featured_image)
                                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                                    class="rounded mr-2"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center mr-2"
                                                    style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $post->title }}</strong>
                                                @if ($post->is_featured)
                                                    <span class="badge badge-warning ml-2">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                @endif
                                                <br>
                                                <small class="text-muted">{{ $post->slug }}</small>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $post->reading_time_minutes }} min read
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($post->category)
                                            <span class="badge badge-info">
                                                {{ $post->category->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">No category</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $post->author->name ?? 'Unknown' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $statusBadge['color'] }}">
                                            <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                                            {{ $statusBadge['text'] }}
                                        </span>
                                        @if ($post->status === 'scheduled' && $post->scheduled_for)
                                            <br>
                                            <small class="text-muted">
                                                {{ $post->scheduled_for->format('M d, Y h:i A') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">
                                            @if ($post->published_at)
                                                <strong>Published:</strong><br>
                                                {{ $post->published_at->format('M d, Y') }}
                                            @else
                                                <strong>Created:</strong><br>
                                                {{ $post->created_at->format('M d, Y') }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge badge-light" title="Views">
                                                <i class="fas fa-eye mr-1"></i>{{ $post->views_count }}
                                            </span>
                                            <span class="badge badge-light" title="Comments">
                                                <i class="fas fa-comment mr-1"></i>{{ $post->comments_count }}
                                            </span>
                                            <span class="badge badge-light" title="Shares">
                                                <i class="fas fa-share mr-1"></i>{{ $post->shares_count }}
                                            </span>
                                            <span class="badge badge-light" title="Likes">
                                                <i class="fas fa-heart mr-1"></i>{{ $post->likes_count }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.blog-posts.view', $post->id) }}" class="btn btn-info"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($post->trashed())
                                                <button class="btn btn-success"
                                                    wire:click="restore({{ $post->id }})" title="Restore">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button class="btn btn-danger"
                                                    wire:click="confirmDelete({{ $post->id }})"
                                                    title="Delete Permanently">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @else
                                                <a href="{{ route('admin.blog-posts.edit', $post->id) }}"
                                                    class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button
                                                    class="btn btn-{{ $post->is_featured ? 'warning' : 'secondary' }}"
                                                    wire:click="toggleFeatured({{ $post->id }})"
                                                    title="{{ $post->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}">
                                                    <i
                                                        class="fas {{ $post->is_featured ? 'fa-star' : 'fa-star' }}"></i>
                                                </button>
                                                <button class="btn btn-danger"
                                                    wire:click="confirmDelete({{ $post->id }})"
                                                    title="Move to Trash">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $statusFilter === 'trashed' ? '7' : '8' }}" class="text-center">
                                        <div class="py-4">
                                            @if ($statusFilter === 'trashed')
                                                <i class="fas fa-trash fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Trash is empty</p>
                                            @else
                                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No posts found</p>
                                                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus mr-2"></i>Create First Post
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Delete Modal -->
                @if ($showDeleteModal && $postToDelete)
                    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        {{ $postToDelete->trashed() ? 'Permanently Delete Post' : 'Move to Trash' }}
                                    </h5>
                                    <button type="button" class="close"
                                        wire:click="$set('showDeleteModal', false)">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        {{ $postToDelete->trashed()
                                            ? 'Are you sure you want to permanently delete "' . $postToDelete->title . '"?'
                                            : 'Are you sure you want to move "' . $postToDelete->title . '" to trash?' }}
                                    </p>
                                    @if ($postToDelete->trashed())
                                        <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.
                                            All comments and related data will be deleted.</p>
                                    @else
                                        <p class="text-warning"><small>Post will be moved to trash and can be restored
                                                later.</small></p>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="$set('showDeleteModal', false)">
                                        Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger" wire:click="delete">
                                        {{ $postToDelete->trashed() ? 'Delete Permanently' : 'Move to Trash' }}
                                    </button>
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
                            Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }}
                            of {{ $posts->total() }} posts
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $posts->links() }}
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

            .badge-light {
                background-color: #f8f9fa;
                color: #495057;
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
        </style>
    @endpush
</div>
