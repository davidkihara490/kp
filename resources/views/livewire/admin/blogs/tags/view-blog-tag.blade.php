<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold d-inline">
                            <i class="fas fa-tag mr-2"></i>{{ $tag->name }}
                        </h3>
                        <span class="badge badge-{{ $popularity['color'] }} ml-2">
                            <i class="fas {{ $popularity['icon'] }} mr-1"></i>
                            {{ $popularity['text'] }}
                        </span>
                    </div>
                    <div>
                        {{-- <a href="{{ route('admin.blog-tags.edit', $tag->id) }}" class="btn btn-warning btn-sm mr-2">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a> --}}
                        <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}"
                            wire:click="setActiveTab('overview')" href="javascript:void(0)">
                            <i class="fas fa-info-circle mr-2"></i>Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'posts' ? 'active' : '' }}"
                            wire:click="setActiveTab('posts')" href="javascript:void(0)">
                            <i class="fas fa-newspaper mr-2"></i>Posts
                            <span class="badge badge-light ml-1">{{ $tag->post_count }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'seo' ? 'active' : '' }}" wire:click="setActiveTab('seo')"
                            href="javascript:void(0)">
                            <i class="fas fa-search mr-2"></i>SEO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'analytics' ? 'active' : '' }}"
                            wire:click="setActiveTab('analytics')" href="javascript:void(0)">
                            <i class="fas fa-chart-line mr-2"></i>Analytics
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Overview Tab -->
                    @if ($activeTab === 'overview')
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>Tag Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Basic Information</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th width="40%">Name:</th>
                                                        <td>{{ $tag->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Slug:</th>
                                                        <td>
                                                            <code>{{ $tag->slug }}</code>
                                                            <small class="text-muted ml-2">
                                                                /blog/tag/{{ $tag->slug }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Posts Count:</th>
                                                        <td>
                                                            <span class="badge badge-primary">
                                                                {{ $tag->post_count }} posts
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Popularity:</th>
                                                        <td>
                                                            <span class="badge badge-{{ $popularity['color'] }}">
                                                                <i class="fas {{ $popularity['icon'] }} mr-1"></i>
                                                                {{ $popularity['text'] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Timestamps</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th width="40%">Created:</th>
                                                        <td>
                                                            {{ $tag->created_at }}
                                                            <small class="text-muted d-block">
                                                                {{ $tag->created_at }}
                                                                {{-- {{ $tag->created_at->diffForHumans() }} --}}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Updated:</th>
                                                        <td>
                                                            {{ $tag->updated_at }}
                                                            <small class="text-muted d-block">
                                                                {{ $tag->updated_at }}
                                                                {{-- {{ $tag->updated_at->diffForHumans() }} --}}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    @if ($tag->deleted_at)
                                                        <tr>
                                                            <th>Deleted:</th>
                                                            <td class="text-danger">
                                                                {{ $tag->deleted_at }}
                                                                <small class="text-muted d-block">
                                                                    {{ $tag->deleted_at->diffForHumans() }}
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>

                                        @if ($tag->description)
                                            <div class="mt-4">
                                                <h6>Description</h6>
                                                <div class="border rounded p-3 bg-light">
                                                    {{ $tag->description }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Popularity Chart -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-chart-bar mr-2"></i>Popularity Growth
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span class="display-4 text-{{ $popularity['color'] }}">
                                                    {{ $tag->post_count }}
                                                </span>
                                                <p class="text-muted">Total Posts</p>
                                            </div>

                                            <div class="progress" style="height: 25px;">
                                                @php
                                                    $maxPosts = max(100, $tag->post_count * 2);
                                                    $width = min(100, ($tag->post_count / $maxPosts) * 100);
                                                @endphp
                                                <div class="progress-bar bg-{{ $popularity['color'] }} progress-bar-striped"
                                                    role="progressbar" style="width: {{ $width }}%"
                                                    aria-valuenow="{{ $tag->post_count }}" aria-valuemin="0"
                                                    aria-valuemax="{{ $maxPosts }}">
                                                    <strong>{{ $tag->post_count }} posts</strong>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-4">
                                                    <div class="border rounded p-2">
                                                        <small class="text-muted d-block">New</small>
                                                        <strong class="text-secondary">0-4</strong>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border rounded p-2">
                                                        <small class="text-muted d-block">Active</small>
                                                        <strong class="text-primary">5-9</strong>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="border rounded p-2">
                                                        <small class="text-muted d-block">Trending</small>
                                                        <strong class="text-info">10-19</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <div class="border rounded p-2">
                                                        <small class="text-muted d-block">Popular</small>
                                                        <strong class="text-warning">20-49</strong>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="border rounded p-2">
                                                        <small class="text-muted d-block">Very Popular</small>
                                                        <strong class="text-danger">50+</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Quick Actions -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-bolt mr-2"></i>Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            {{-- <a href="{{ route('admin.blog-tags.edit', $tag->id) }}"
                                                class="list-group-item list-group-item-action">
                                                <i class="fas fa-edit mr-2 text-primary"></i>Edit Tag
                                            </a> --}}
                                            <a href="{{ route('admin.blog-posts.create', ['tag' => $tag->id]) }}"
                                                class="list-group-item list-group-item-action">
                                                <i class="fas fa-plus mr-2 text-success"></i>Add New Post
                                            </a>
                                            <a href="{{ route('admin.blog-posts.index', ['tag' => $tag->id]) }}"
                                                class="list-group-item list-group-item-action">
                                                <i class="fas fa-list mr-2 text-info"></i>View All Posts
                                            </a>
                                            @if ($tag->posts()->count() === 0)
                                                <button class="list-group-item list-group-item-action text-danger"
                                                    onclick="confirm('Are you sure? This tag has no posts.') && Livewire.dispatch('deleteTag', {tagId: {{ $tag->id }}})">
                                                    <i class="fas fa-trash mr-2"></i>Delete Tag
                                                </button>
                                            @else
                                                <div class="list-group-item list-group-item-action text-muted">
                                                    <i class="fas fa-trash mr-2"></i>Delete Tag
                                                    <small class="d-block text-danger">
                                                        Cannot delete - used in {{ $tag->posts()->count() }} posts
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Related Tags -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-tags mr-2"></i>Popular Tags
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach (\App\Models\BlogTag::orderBy('post_count', 'desc')->limit(10)->get() as $popularTag)
                                                <a href="{{ route('admin.blog-tags.view', $popularTag->id) }}"
                                                    class="badge badge-{{ $popularTag->id === $tag->id ? $popularity['color'] : 'light' }} p-2">
                                                    {{ $popularTag->name }}
                                                    <small class="ml-1">({{ $popularTag->post_count }})</small>
                                                </a>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('admin.blog-tags.index') }}"
                                            class="btn btn-sm btn-outline-primary btn-block mt-3">
                                            <i class="fas fa-list mr-2"></i>View All Tags
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Posts Tab -->
                    @if ($activeTab === 'posts')
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-newspaper mr-2"></i>Posts with "{{ $tag->name }}" Tag
                                </h5>
                                <a href="{{ route('admin.blog-posts.create', ['tag' => $tag->id]) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-plus mr-2"></i>Add Post
                                </a>
                            </div>
                            <div class="card-body">
                                @if ($relatedPosts->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Author</th>
                                                    <th>Status</th>
                                                    <th>Views</th>
                                                    <th>Published</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($relatedPosts as $post)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $post->title }}</strong><br>
                                                            <small class="text-muted">
                                                                {{ Str::limit($post->excerpt, 50) }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            @if ($post->author)
                                                                {{ $post->author->name }}
                                                            @else
                                                                <span class="text-muted">Unknown</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $post->is_published ? 'success' : 'warning' }}">
                                                                {{ $post->is_published ? 'Published' : 'Draft' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $post->views ?? 0 }}</td>
                                                        <td>
                                                            @if ($post->published_at)
                                                                {{ $post->published_at }}
                                                            @else
                                                                <span class="text-muted">Not published</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.blog-posts.show', $post->id) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $relatedPosts->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No posts with this tag yet</p>
                                        <a href="{{ route('admin.blog-posts.create', ['tag' => $tag->id]) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus mr-2"></i>Create First Post
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SEO Tab -->
                    @if ($activeTab === 'seo')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-search mr-2"></i>SEO Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Meta Title</h6>
                                        <div class="border rounded p-3 bg-light mb-3">
                                            {{ $tag->meta_title ?? 'Not set' }}
                                            @if (!$tag->meta_title)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    Adding a meta title can improve SEO
                                                </small>
                                            @endif
                                        </div>

                                        <h6>Meta Description</h6>
                                        <div class="border rounded p-3 bg-light mb-3">
                                            {{ $tag->meta_description ?? 'Not set' }}
                                            @if (!$tag->meta_description)
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    Adding a meta description can improve click-through rates
                                                </small>
                                            @endif
                                        </div>

                                        <h6>URL Structure</h6>
                                        <div class="border rounded p-3 bg-light">
                                            <code>/blog/tag/{{ $tag->slug }}</code>
                                            <br>
                                            <a href="{{ url('/blog/tag/' . $tag->slug) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary mt-2">
                                                <i class="fas fa-external-link-alt mr-2"></i>Visit Tag Page
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6>SEO Preview</h6>
                                        <div class="seo-preview border rounded p-3 bg-white mb-4">
                                            <div class="mb-2">
                                                <strong class="text-primary" style="font-size: 18px;">
                                                    {{ $tag->meta_title ? Str::limit($tag->meta_title, 60) : Str::limit($tag->name . ' - Blog Tag', 60) }}
                                                </strong>
                                            </div>
                                            <div class="mb-2">
                                                <code class="text-success" style="font-size: 14px;">
                                                    {{ url('/blog/tag/' . $tag->slug) }}
                                                </code>
                                            </div>
                                            <div>
                                                <small class="text-muted" style="font-size: 14px; line-height: 1.4;">
                                                    {{ $tag->meta_description ? Str::limit($tag->meta_description, 160) : Str::limit($tag->description, 160) }}
                                                </small>
                                            </div>
                                        </div>

                                        <h6>SEO Recommendations</h6>
                                        <div class="border rounded p-3 bg-light">
                                            <ul class="mb-0 pl-3">
                                                @if (!$tag->meta_title)
                                                    <li class="text-warning">Add a descriptive meta title</li>
                                                @endif
                                                @if (!$tag->meta_description)
                                                    <li class="text-warning">Add a compelling meta description</li>
                                                @endif
                                                <li>Ensure tag name is relevant to content</li>
                                                <li>Use tag consistently across related posts</li>
                                                <li>Consider adding more posts to increase authority</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Analytics Tab -->
                    @if ($activeTab === 'analytics')
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line mr-2"></i>Tag Analytics
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-gradient-info">
                                            <span class="info-box-icon"><i class="fas fa-newspaper"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total Posts</span>
                                                <span class="info-box-number">{{ $tag->post_count }}</span>
                                                <div class="progress">
                                                    @php
                                                        $max = max(100, $tag->post_count * 2);
                                                        $percent = min(100, ($tag->post_count / $max) * 100);
                                                    @endphp
                                                    <div class="progress-bar" style="width: {{ $percent }}%">
                                                    </div>
                                                </div>
                                                <span class="progress-description">
                                                    {{ $popularity['text'] }} tag
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-box bg-gradient-success">
                                            <span class="info-box-icon"><i class="fas fa-eye"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Estimated Views</span>
                                                <span
                                                    class="info-box-number">{{ number_format($tag->post_count * 100) }}</span>
                                                <div class="progress">
                                                    <div class="progress-bar" style="width: 70%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Based on post count
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="info-box bg-gradient-warning">
                                            <span class="info-box-icon"><i class="fas fa-chart-pie"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Engagement Score</span>
                                                <span
                                                    class="info-box-number">{{ $tag->post_count >= 50 ? 'A+' : ($tag->post_count >= 20 ? 'A' : ($tag->post_count >= 10 ? 'B' : 'C')) }}</span>
                                                <div class="progress">
                                                    <div class="progress-bar"
                                                        style="width: {{ min(100, $tag->post_count * 2) }}%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    Based on popularity
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6>Tag Performance Over Time</h6>
                                    <div class="border rounded p-3 bg-light text-center">
                                        <p class="text-muted">
                                            <i class="fas fa-chart-bar fa-2x mb-3"></i><br>
                                            Analytics integration required
                                        </p>
                                        <small class="text-muted">
                                            Connect Google Analytics or other analytics tools to see detailed
                                            performance metrics
                                        </small>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6>Recommendations</h6>
                                    <div class="border rounded p-3 bg-light">
                                        @if ($tag->post_count < 5)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-lightbulb mr-2"></i>
                                                <strong>Low Activity:</strong> This tag has few posts. Consider:
                                                <ul class="mt-2 mb-0">
                                                    <li>Creating more content with this tag</li>
                                                    <li>Merging with similar tags</li>
                                                    <li>Promoting existing posts</li>
                                                </ul>
                                            </div>
                                        @elseif($tag->post_count < 20)
                                            <div class="alert alert-info">
                                                <i class="fas fa-lightbulb mr-2"></i>
                                                <strong>Growing Tag:</strong> This tag is gaining popularity. Consider:
                                                <ul class="mt-2 mb-0">
                                                    <li>Creating a tag-focused content series</li>
                                                    <li>Adding to popular posts</li>
                                                    <li>Updating SEO metadata</li>
                                                </ul>
                                            </div>
                                        @else
                                            <div class="alert alert-success">
                                                <i class="fas fa-lightbulb mr-2"></i>
                                                <strong>Popular Tag:</strong> This tag is performing well. Consider:
                                                <ul class="mt-2 mb-0">
                                                    <li>Creating a dedicated tag page</li>
                                                    <li>Promoting top-performing posts</li>
                                                    <li>Creating tag-specific email campaigns</li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .nav-tabs .nav-link {
                cursor: pointer;
            }

            .info-box {
                border-radius: 0.25rem;
                margin-bottom: 0;
            }

            .info-box-icon {
                border-radius: 0.25rem 0 0 0.25rem;
            }

            .seo-preview {
                border-left: 4px solid #4dabf7 !important;
            }

            .bg-gradient-info {
                background: linear-gradient(45deg, #17a2b8, #138496) !important;
            }

            .bg-gradient-success {
                background: linear-gradient(45deg, #28a745, #1e7e34) !important;
            }

            .bg-gradient-warning {
                background: linear-gradient(45deg, #ffc107, #e0a800) !important;
            }
        </style>
    @endpush
</div>
