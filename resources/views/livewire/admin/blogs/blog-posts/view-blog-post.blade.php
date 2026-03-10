<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold d-inline">
                            <i class="fas fa-newspaper mr-2"></i>{{ $post->title }}
                        </h3>
                        <span class="badge badge-{{ $statusBadge['color'] }} ml-2">
                            <i class="fas {{ $statusBadge['icon'] }} mr-1"></i>
                            {{ $statusBadge['text'] }}
                        </span>
                        @if ($post->is_featured)
                            <span class="badge badge-warning ml-2">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                        @endif
                    </div>
                    <div>
                        {{-- <a href="{{ route('admin.blog-posts.edit', $post->id) }}" 
                       class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a> --}}
                        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'content' ? 'active' : '' }}"
                            wire:click="setActiveTab('content')" href="javascript:void(0)">
                            <i class="fas fa-file-alt mr-2"></i>Content
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'seo' ? 'active' : '' }}" wire:click="setActiveTab('seo')"
                            href="javascript:void(0)">
                            <i class="fas fa-search mr-2"></i>SEO Analysis
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'statistics' ? 'active' : '' }}"
                            wire:click="setActiveTab('statistics')" href="javascript:void(0)">
                            <i class="fas fa-chart-bar mr-2"></i>Statistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'comments' ? 'active' : '' }}"
                            wire:click="setActiveTab('comments')" href="javascript:void(0)">
                            <i class="fas fa-comments mr-2"></i>Comments
                            <span class="badge badge-light ml-1">{{ $post->comments_count }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'preview' ? 'active' : '' }}"
                            wire:click="setActiveTab('preview')" href="javascript:void(0)">
                            <i class="fas fa-eye mr-2"></i>Preview
                        </a>
                    </li>
                </ul>

                <!-- Content Tab -->
                @if ($activeTab === 'content')
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-lg-8">
                            <!-- Featured Image -->
                            @if ($post->featured_image)
                                <div class="card mb-4">
                                    <div class="card-body text-center p-0">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}"
                                            alt="{{ $post->title }}" class="img-fluid rounded"
                                            style="max-height: 400px; width: 100%; object-fit: cover;">
                                    </div>
                                </div>
                            @endif

                            <!-- Post Content -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div>
                                            <h1 class="h3 mb-2">{{ $post->title }}</h1>
                                            <div class="text-muted mb-3">
                                                <i class="fas fa-user mr-1"></i>
                                                <strong>{{ $post->author->name ?? 'Unknown' }}</strong>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $post->published_at ? $post->published_at : $post->created_at }}
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $post->reading_time_minutes }} min read
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-eye mr-1"></i>
                                                {{ $post->views_count }} views
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ $post->full_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt mr-1"></i>View Live
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Category & Tags -->
                                    <div class="mb-4">
                                        @if ($post->category)
                                            <a href="{{ route('admin.blog-categories.show', $post->category->id) }}"
                                                class="badge badge-info mr-2 mb-2">
                                                <i class="fas fa-folder mr-1"></i>
                                                {{ $post->category->name }}
                                            </a>
                                        @endif

                                        {{-- @foreach ($post->tags ?? [] as $tag)
                                    <a href="{{ route('admin.blog-tags.show', $tag->id) }}" 
                                       class="badge badge-light mr-2 mb-2">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $tag->name }}
                                    </a>
                                @endforeach --}}
                                    </div>

                                    <!-- Excerpt -->
                                    @if ($post->excerpt)
                                        <div class="alert alert-light mb-4">
                                            <h6 class="alert-heading">Summary</h6>
                                            {{ $post->excerpt }}
                                        </div>
                                    @endif

                                    <!-- Content -->
                                    <div class="post-content">
                                        {!! nl2br(e($post->content)) !!}
                                    </div>

                                    <!-- Gallery Images -->
                                    @if ($post->gallery_images && count($post->gallery_images) > 0)
                                        <div class="mt-5">
                                            <h5 class="mb-3"><i class="fas fa-images mr-2"></i>Gallery</h5>
                                            <div class="row">
                                                @foreach ($post->gallery_images as $image)
                                                    <div class="col-md-4 mb-3">
                                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image"
                                                            class="img-fluid rounded"
                                                            style="height: 200px; width: 100%; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Post Meta -->
                                    <div class="mt-5 pt-4 border-top">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Post Information</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <th width="40%">Status:</th>
                                                        <td>
                                                            <span class="badge badge-{{ $statusBadge['color'] }}">
                                                                {{ $statusBadge['text'] }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Visibility:</th>
                                                        <td>{{ ucfirst($post->visibility) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Allow Comments:</th>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $post->allow_comments ? 'success' : 'danger' }}">
                                                                {{ $post->allow_comments ? 'Yes' : 'No' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Allow Sharing:</th>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $post->allow_sharing ? 'success' : 'danger' }}">
                                                                {{ $post->allow_sharing ? 'Yes' : 'No' }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Include in Sitemap:</th>
                                                        <td>
                                                            <span
                                                                class="badge badge-{{ $post->include_in_sitemap ? 'success' : 'danger' }}">
                                                                {{ $post->include_in_sitemap ? 'Yes' : 'No' }}
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
                                                            {{ $post->created_at }}
                                                            <small class="text-muted d-block">
                                                                {{-- {{ $post->created_at->diffForHumans() }} --}}
                                                                {{ $post->created_at }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Last Updated:</th>
                                                        <td>
                                                            {{ $post->updated_at }}
                                                            <small class="text-muted d-block">
                                                                {{-- {{ $post->updated_at->diffForHumans() }} --}}
                                                                {{ $post->updated_at }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    @if ($post->published_at)
                                                        <tr>
                                                            <th>Published:</th>
                                                            <td>
                                                                {{ $post->published_at }}
                                                                <small class="text-muted d-block">
                                                                    {{ $post->published_at }}
                                                                    {{-- {{ $post->published_at->diffForHumans() }} --}}
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($post->scheduled_for)
                                                        <tr>
                                                            <th>Scheduled For:</th>
                                                            <td>
                                                                {{ $post->scheduled_for }}
                                                                <small class="text-muted d-block">
                                                                    {{-- {{ $post->scheduled_for->diffForHumans() }} --}}
                                                                    {{ $post->scheduled_for }}
                                                                </small>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <!-- Quick Actions -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-bolt mr-2"></i>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        {{-- <a href="{{ route('admin.blog-posts.edit', $post->id) }}" 
                                   class="list-group-item list-group-item-action">
                                    <i class="fas fa-edit mr-2 text-primary"></i>Edit Post
                                </a> --}}
                                        <button class="list-group-item list-group-item-action"
                                            wire:click="toggleFeatured">
                                            <i
                                                class="fas fa-star mr-2 {{ $post->is_featured ? 'text-warning' : 'text-secondary' }}"></i>
                                            {{ $post->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                                        </button>
                                        <button class="list-group-item list-group-item-action"
                                            wire:click="toggleStatus">
                                            <i
                                                class="fas fa-toggle-{{ $post->status === 'published' ? 'on' : 'off' }} mr-2 
                                       {{ $post->status === 'published' ? 'text-success' : 'text-secondary' }}"></i>
                                            {{ $post->status === 'published' ? 'Move to Draft' : 'Publish Now' }}
                                        </button>
                                        <a href="{{ route('admin.blog-posts.create', ['category_id' => $post->category_id]) }}"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-plus mr-2 text-success"></i>Add Similar Post
                                        </a>
                                        <a href="{{ route('admin.blog-posts.index', ['category' => $post->category_id]) }}"
                                            class="list-group-item list-group-item-action">
                                            <i class="fas fa-list mr-2 text-info"></i>View All in Category
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Score -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>SEO Score</h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <div class="display-3 text-{{ $seoColor }} font-weight-bold">
                                            {{ $seoScore }}
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-{{ $seoColor }}" role="progressbar"
                                                style="width: {{ $seoScore }}%">
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" wire:click="analyzeSeo">
                                        <i class="fas fa-sync-alt mr-1"></i>Re-analyze SEO
                                    </button>
                                </div>
                            </div>

                            <!-- Engagement Stats -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-chart-bar mr-2"></i>Engagement</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="display-4 text-primary">{{ $post->views_count }}</div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="display-4 text-info">{{ $post->comments_count }}</div>
                                            <small class="text-muted">Comments</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="display-4 text-success">{{ $post->shares_count }}</div>
                                            <small class="text-muted">Shares</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Related Posts -->
                            @if ($relatedPosts->count() > 0)
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-link mr-2"></i>Related Posts</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($relatedPosts as $related)
                                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('admin.blog-posts.show', $related->id) }}">
                                                        {{ Str::limit($related->title, 50) }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $related->created_at }}
                                                    • {{ $related->reading_time_minutes }} min read
                                                </small>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- SEO Analysis Tab -->
                @if ($activeTab === 'seo')
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- SEO Overview -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>SEO Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <div class="display-4 text-{{ $seoColor }} font-weight-bold">
                                                    {{ $seoScore }}
                                                </div>
                                                <small class="text-muted">SEO Score</small>
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="d-flex align-items-center h-100">
                                                <div class="w-100">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>SEO Performance</span>
                                                        <span>{{ $seoScore }}/100</span>
                                                    </div>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar bg-{{ $seoColor }}"
                                                            role="progressbar" style="width: {{ $seoScore }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SEO Checks -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Basic SEO Checks</h6>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    @if (Str::length($post->title) >= 50 && Str::length($post->title) <= 60)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Title length is good
                                                            ({{ Str::length($post->title) }} chars)</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">Title should be 50-60 characters
                                                            ({{ Str::length($post->title) }} chars)</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if (
                                                        $post->meta_description &&
                                                            Str::length($post->meta_description) >= 120 &&
                                                            Str::length($post->meta_description) <= 160)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Meta description length is good
                                                            ({{ Str::length($post->meta_description) }} chars)</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">Meta description should be 120-160
                                                            characters</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($post->focus_keyword)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Focus keyword set:
                                                            "{{ $post->focus_keyword }}"</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">No focus keyword set</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($post->featured_image)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Featured image set</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">No featured image</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($totalWords >= 300)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Content length is good
                                                            ({{ $totalWords }} words)</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">Content too short
                                                            ({{ $totalWords }} words, minimum 300)</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Technical SEO</h6>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    @if (!$post->noindex)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Indexing allowed</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-danger mr-2"></i>
                                                        <span class="text-danger">Noindex enabled - search engines
                                                            won't index</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if (!$post->nofollow)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Link following allowed</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">Nofollow enabled - links won't pass
                                                            SEO value</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($post->include_in_sitemap)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Included in sitemap</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">Excluded from sitemap</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($post->canonical_url)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Canonical URL set</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">No canonical URL</span>
                                                    @endif
                                                </li>
                                                <li class="mb-2">
                                                    @if ($internalLinksCount > 0)
                                                        <i class="fas fa-check-circle text-success mr-2"></i>
                                                        <span class="text-success">Internal links:
                                                            {{ $internalLinksCount }}</span>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                                        <span class="text-warning">No internal links</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- SEO Issues -->
                                    @if ($post->seo_issues && count($post->seo_issues) > 0)
                                        <div class="alert alert-warning mt-4">
                                            <h6><i class="fas fa-exclamation-triangle mr-2"></i>SEO Issues Found</h6>
                                            <ul class="mb-0">
                                                @foreach ($post->seo_issues as $issue)
                                                    <li>{{ $issue }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Meta Data Preview -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-eye mr-2"></i>Search Preview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="seo-preview border rounded p-3 bg-light mb-3">
                                        <div class="mb-2">
                                            <strong class="text-primary" style="font-size: 18px;">
                                                {{ $post->meta_title ?: $post->title }}
                                            </strong>
                                        </div>
                                        <div class="mb-2">
                                            <code class="text-success" style="font-size: 14px;">
                                                {{ $post->full_url }}
                                            </code>
                                        </div>
                                        <div>
                                            <small class="text-muted" style="font-size: 14px; line-height: 1.4;">
                                                {{ $post->meta_description ?: $post->excerpt }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Open Graph (Facebook)</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="30%">Title:</th>
                                                    <td>{{ $post->og_title ?: $post->title }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Description:</th>
                                                    <td>{{ $post->og_description ?: $post->excerpt }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Type:</th>
                                                    <td>{{ $post->og_type ?: 'article' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Image:</th>
                                                    <td>
                                                        @if ($post->og_image)
                                                            <span class="badge badge-success">Set</span>
                                                        @else
                                                            <span class="badge badge-warning">Not set</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Twitter Card</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="30%">Title:</th>
                                                    <td>{{ $post->twitter_title ?: $post->title }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Description:</th>
                                                    <td>{{ $post->twitter_description ?: $post->excerpt }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Card Type:</th>
                                                    <td>{{ $post->twitter_card_type ?: 'summary_large_image' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Image:</th>
                                                    <td>
                                                        @if ($post->twitter_image)
                                                            <span class="badge badge-success">Set</span>
                                                        @else
                                                            <span class="badge badge-warning">Not set</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Schema Markup -->
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Schema Markup</h6>
                                            <button class="btn btn-sm btn-outline-primary"
                                                wire:click="generateSchema">
                                                <i class="fas fa-sync-alt mr-1"></i>Regenerate
                                            </button>
                                        </div>
                                        @if ($post->schema_markup)
                                            <pre class="bg-dark text-light p-3 rounded small" style="max-height: 200px; overflow: auto;">
{{ json_encode($post->schema_markup, JSON_PRETTY_PRINT) }}
                                    </pre>
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No schema markup generated. Click "Regenerate" to create.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Sidebar -->
                        <div class="col-lg-4">
                            <!-- Focus Keyword -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-bullseye mr-2"></i>Focus Keyword</h5>
                                </div>
                                <div class="card-body">
                                    @if ($post->focus_keyword)
                                        <div class="text-center">
                                            <span class="badge badge-primary p-2" style="font-size: 1.2rem;">
                                                {{ $post->focus_keyword }}
                                            </span>
                                            <div class="mt-3">
                                                <small class="text-muted d-block">Appears in title:</small>
                                                @if (Str::contains(Str::lower($post->title), Str::lower($post->focus_keyword)))
                                                    <span class="badge badge-success">Yes</span>
                                                @else
                                                    <span class="badge badge-danger">No</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-bullseye fa-3x mb-3"></i>
                                            <p>No focus keyword set</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Internal Links -->
                            @if ($post->internal_links && count($post->internal_links) > 0)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-link mr-2"></i>Internal Links
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            @foreach ($post->internal_links as $link)
                                                <a href="{{ $link }}" target="_blank"
                                                    class="list-group-item list-group-item-action py-2">
                                                    <i class="fas fa-external-link-alt mr-2 text-muted"></i>
                                                    {{ $link }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Meta Keywords -->
                            @if ($post->meta_keywords && count($post->meta_keywords) > 0)
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0"><i class="fas fa-tags mr-2"></i>Meta Keywords</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($post->meta_keywords as $keyword)
                                                <span class="badge badge-secondary">{{ $keyword }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Statistics Tab -->
                @if ($activeTab === 'statistics')
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Engagement Metrics -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>Engagement
                                        Metrics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-3 mb-4">
                                            <div class="display-4 text-primary">{{ $post->views_count }}</div>
                                            <small class="text-muted">Total Views</small>
                                        </div>
                                        <div class="col-md-3 mb-4">
                                            <div class="display-4 text-info">{{ $post->comments_count }}</div>
                                            <small class="text-muted">Comments</small>
                                        </div>
                                        <div class="col-md-3 mb-4">
                                            <div class="display-4 text-success">{{ $post->shares_count }}</div>
                                            <small class="text-muted">Shares</small>
                                        </div>
                                        <div class="col-md-3 mb-4">
                                            <div class="display-4 text-warning">{{ $post->likes_count }}</div>
                                            <small class="text-muted">Likes</small>
                                        </div>
                                    </div>

                                    <!-- Daily Views (Example) -->
                                    <div class="mt-4">
                                        <h6>View Trend (Last 30 Days)</h6>
                                        <div class="border rounded p-4 bg-light text-center">
                                            <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                                            <p class="text-muted">Analytics integration required</p>
                                            <small class="text-muted">
                                                Connect Google Analytics to view detailed view trends
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Content Analysis -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-file-alt mr-2"></i>Content Analysis
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Basic Metrics</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="60%">Total Words:</th>
                                                    <td>{{ $totalWords }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Reading Time:</th>
                                                    <td>{{ $post->reading_time_minutes }} minutes</td>
                                                </tr>
                                                <tr>
                                                    <th>Images:</th>
                                                    <td>{{ count($post->gallery_images ?? []) + ($post->featured_image ? 1 : 0) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Internal Links:</th>
                                                    <td>{{ $internalLinksCount }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Tags:</th>
                                                    <td>{{ $post->tags->count() }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>SEO Performance</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <th width="60%">SEO Score:</th>
                                                    <td>
                                                        <span class="badge badge-{{ $seoColor }}">
                                                            {{ $seoScore }}/100
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Last Analyzed:</th>
                                                    <td>
                                                        {{ $post->last_seo_analysis ? $post->last_seo_analysis : 'Never' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Sitemap Priority:</th>
                                                    <td>{{ $post->sitemap_priority }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Change Frequency:</th>
                                                    <td>{{ ucfirst($post->sitemap_change_frequency) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Article Type:</th>
                                                    <td>{{ $post->article_type }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Sidebar -->
                        <div class="col-lg-4">
                            <!-- Popularity Rank -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-trophy mr-2"></i>Popularity</h5>
                                </div>
                                <div class="card-body text-center">
                                    @php
                                        $viewsPercent = min(
                                            100,
                                            ($post->views_count / max(1, $popularPosts->max('views_count'))) * 100,
                                        );
                                        $viewsColor =
                                            $viewsPercent >= 80
                                                ? 'danger'
                                                : ($viewsPercent >= 60
                                                    ? 'warning'
                                                    : ($viewsPercent >= 40
                                                        ? 'info'
                                                        : 'secondary'));
                                    @endphp
                                    <div class="mb-3">
                                        <div class="display-4 text-{{ $viewsColor }}">{{ $post->views_count }}
                                        </div>
                                        <small class="text-muted">Views</small>
                                    </div>
                                    <div class="progress" style="height: 15px;">
                                        <div class="progress-bar bg-{{ $viewsColor }}" role="progressbar"
                                            style="width: {{ $viewsPercent }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Rank: {{ $popularPosts->search($post) + 1 }} of
                                        {{ $popularPosts->count() + 1 }}
                                    </small>
                                </div>
                            </div>

                            <!-- Popular Posts -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0"><i class="fas fa-fire mr-2"></i>Popular Posts</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($popularPosts as $index => $popular)
                                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="mr-3">
                                                    <span
                                                        class="badge badge-{{ $index < 3 ? 'danger' : 'secondary' }}">
                                                        #{{ $index + 1 }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('admin.blog-posts.show', $popular->id) }}"
                                                            class="{{ $popular->id === $post->id ? 'text-primary' : '' }}">
                                                            {{ Str::limit($popular->title, 40) }}
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $popular->views_count }} views
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Comments Tab -->
                @if ($activeTab === 'comments')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-comments mr-2"></i>Comments
                                <span class="badge badge-light ml-2">{{ $post->comments_count }}</span>
                            </h5>
                            <div>
                                <span class="badge badge-{{ $post->allow_comments ? 'success' : 'danger' }}">
                                    Comments {{ $post->allow_comments ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($post->allow_comments)
                                @if ($post->comments->count() > 0)
                                    <div class="comments-section">
                                        @foreach ($post->comments as $comment)
                                            <div class="media mb-4 pb-4 border-bottom">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'Anonymous') }}&background=random"
                                                    class="mr-3 rounded-circle" width="50"
                                                    alt="{{ $comment->user->name ?? 'Anonymous' }}">
                                                <div class="media-body">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h6 class="mt-0 mb-1">
                                                                {{ $comment->user->name ?? 'Anonymous' }}
                                                                @if ($comment->user_id === $post->author_id)
                                                                    <span
                                                                        class="badge badge-primary ml-2">Author</span>
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted">
                                                                {{ $comment->created_at }}
                                                            </small>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="badge badge-{{ $comment->is_approved ? 'success' : 'warning' }}">
                                                                {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="mb-2">{{ $comment->content }}</p>

                                                    @if ($comment->replies->count() > 0)
                                                        <div class="ml-4 mt-3">
                                                            <h6 class="small font-weight-bold mb-3">
                                                                <i class="fas fa-reply mr-2"></i>Replies
                                                                ({{ $comment->replies->count() }})
                                                            </h6>
                                                            @foreach ($comment->replies as $reply)
                                                                <div class="media mb-3">
                                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'Anonymous') }}&background=random"
                                                                        class="mr-3 rounded-circle" width="35"
                                                                        alt="{{ $reply->user->name ?? 'Anonymous' }}">
                                                                    <div class="media-body">
                                                                        <div class="d-flex justify-content-between">
                                                                            <h6 class="mt-0 mb-1 small">
                                                                                {{ $reply->user->name ?? 'Anonymous' }}
                                                                            </h6>
                                                                            <small class="text-muted">
                                                                                {{ $reply->created_at }}
                                                                            </small>
                                                                        </div>
                                                                        <p class="small mb-0">{{ $reply->content }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No comments yet</p>
                                        <p class="text-muted small">Be the first to comment on this post!</p>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Comments are disabled for this post</p>
                                    <p class="small text-muted">
                                        Enable comments in the post settings to allow user feedback
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Preview Tab -->
                @if ($activeTab === 'preview')
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-desktop mr-2"></i>Live Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                This is how your post will appear on the website.
                                <a href="{{ $post->full_url }}" target="_blank" class="alert-link">
                                    Click here to view the live version
                                </a>
                            </div>

                            <!-- Mock Website Preview -->
                            <div class="website-preview border rounded overflow-hidden">
                                <!-- Mock Header -->
                                <div class="bg-dark text-white p-3 d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-bars mr-2"></i>
                                        <strong>Blog Preview</strong>
                                    </div>
                                    <div>
                                        <i class="fas fa-search mr-2"></i>
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>

                                <!-- Mock Content -->
                                <div class="p-4">
                                    <!-- Breadcrumb -->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb bg-light p-2 rounded">
                                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Blog</a></li>
                                            @if ($post->category)
                                                <li class="breadcrumb-item"><a
                                                        href="#">{{ $post->category->name }}</a></li>
                                            @endif
                                            <li class="breadcrumb-item active">{{ Str::limit($post->title, 30) }}
                                            </li>
                                        </ol>
                                    </nav>

                                    <!-- Featured Image -->
                                    @if ($post->featured_image)
                                        <div class="mb-4">
                                            <img src="{{ asset('storage/' . $post->featured_image) }}"
                                                alt="{{ $post->title }}" class="img-fluid rounded"
                                                style="max-height: 300px; width: 100%; object-fit: cover;">
                                        </div>
                                    @endif

                                    <!-- Title & Meta -->
                                    <h1 class="h2 mb-3">{{ $post->title }}</h1>

                                    <div class="text-muted mb-4">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <span class="mr-3">
                                                <i class="fas fa-user mr-1"></i>
                                                {{ $post->author->name ?? 'Admin' }}
                                            </span>
                                            <span class="mr-3">
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $post->published_at ? $post->published_at : $post->created_at }}
                                            </span>
                                            <span class="mr-3">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $post->reading_time_minutes }} min read
                                            </span>
                                            <span>
                                                <i class="fas fa-eye mr-1"></i>
                                                {{ $post->views_count + 1 }} views
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Category & Tags -->
                                    <div class="mb-4">
                                        @if ($post->category)
                                            <a href="#" class="badge badge-primary mr-2 mb-2">
                                                <i class="fas fa-folder mr-1"></i>
                                                {{ $post->category->name }}
                                            </a>
                                        @endif

                                        @foreach ($post->tags as $tag)
                                            <a href="#" class="badge badge-secondary mr-2 mb-2">
                                                <i class="fas fa-tag mr-1"></i>
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>

                                    <!-- Excerpt -->
                                    @if ($post->excerpt)
                                        <div class="alert alert-light mb-4">
                                            <p class="lead mb-0">{{ $post->excerpt }}</p>
                                        </div>
                                    @endif

                                    <!-- Content -->
                                    <div class="post-content mb-5">
                                        {!! nl2br(e(Str::limit($post->content, 500))) !!}
                                        @if (strlen($post->content) > 500)
                                            <div class="mt-3">
                                                <span class="text-muted">[...]</span>
                                                <small class="text-muted d-block">
                                                    Content truncated for preview
                                                </small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Social Sharing -->
                                    @if ($post->allow_sharing)
                                        <div class="border-top pt-4 mt-4">
                                            <h6 class="mb-3"><i class="fas fa-share-alt mr-2"></i>Share this post
                                            </h6>
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-outline-primary mr-2">
                                                    <i class="fab fa-facebook-f mr-1"></i>Facebook
                                                </button>
                                                <button class="btn btn-sm btn-outline-info mr-2">
                                                    <i class="fab fa-twitter mr-1"></i>Twitter
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger mr-2">
                                                    <i class="fab fa-pinterest-p mr-1"></i>Pinterest
                                                </button>
                                                <button class="btn btn-sm btn-outline-dark">
                                                    <i class="fab fa-linkedin-in mr-1"></i>LinkedIn
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Comments Section -->
                                    @if ($post->allow_comments)
                                        <div class="border-top pt-4 mt-4">
                                            <h6 class="mb-3">
                                                <i class="fas fa-comments mr-2"></i>
                                                Comments ({{ $post->comments_count }})
                                            </h6>
                                            @if ($post->comments_count > 0)
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    {{ $post->comments_count }} comments would appear here
                                                </div>
                                            @else
                                                <div class="text-center py-4 bg-light rounded">
                                                    <i class="fas fa-comment-dots fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">No comments yet</p>
                                                    <button class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-plus mr-1"></i>Be the first to comment
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="mt-4 text-center">
                                <a href="{{ $post->full_url }}" target="_blank" class="btn btn-primary mr-3">
                                    <i class="fas fa-external-link-alt mr-2"></i>View Live Page
                                </a>
                                {{-- <a href="{{ route('admin.blog-posts.edit', $post->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-2"></i>Edit Post
                        </a> --}}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .nav-tabs .nav-link {
                cursor: pointer;
            }

            .post-content {
                line-height: 1.8;
                font-size: 1.1rem;
            }

            .post-content p {
                margin-bottom: 1.5rem;
            }

            .seo-preview {
                border-left: 4px solid #4dabf7 !important;
            }

            .website-preview {
                background: #f8f9fa;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .breadcrumb {
                font-size: 0.9rem;
            }

            .media img {
                object-fit: cover;
            }

            pre {
                background: #2d3748;
                color: #e2e8f0;
                border-radius: 6px;
                font-size: 0.85rem;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Initialize any interactive elements if needed
            document.addEventListener('DOMContentLoaded', function() {
                // Smooth scrolling for anchor links in preview
                const anchorLinks = document.querySelectorAll('a[href^="#"]');
                anchorLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('href');
                        if (targetId !== '#') {
                            const targetElement = document.querySelector(targetId);
                            if (targetElement) {
                                targetElement.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
</div>
