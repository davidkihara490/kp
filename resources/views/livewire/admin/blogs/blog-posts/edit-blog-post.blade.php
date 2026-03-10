<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold d-inline">
                            <i class="fas fa-edit mr-2"></i>Edit Post: {{ $post->title }}
                        </h3>
                        @php
                            $statusBadge = $this->getStatusBadge();
                        @endphp
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
                        {{-- <a href="{{ route('admin.blog-posts.view', $post->id) }}" class="btn btn-info btn-sm mr-2">
                            <i class="fas fa-eye mr-2"></i>View
                        </a> --}}
                        <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Posts
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="update" x-data="{ activeTab: 'content' }">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'content' }"
                                @click.prevent="activeTab = 'content'">
                                <i class="fas fa-edit mr-2"></i>Content
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'seo' }"
                                @click.prevent="activeTab = 'seo'">
                                <i class="fas fa-search mr-2"></i>SEO
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'images' }"
                                @click.prevent="activeTab = 'images'">
                                <i class="fas fa-image mr-2"></i>Images
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'settings' }"
                                @click.prevent="activeTab = 'settings'">
                                <i class="fas fa-cog mr-2"></i>Settings
                            </a>
                        </li>
                    </ul>

                    <!-- Content Tab -->
                    <div x-show="activeTab === 'content'" class="space-y-6">
                        <!-- Basic Information -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-info-circle mr-2"></i>Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" wire:model.live="title" placeholder="Enter post title">
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                            id="slug" wire:model="slug" placeholder="URL-friendly slug">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button"
                                                wire:click="generateSlug">
                                                <i class="fas fa-sync-alt"></i> Regenerate
                                            </button>
                                        </div>
                                    </div>
                                    @error('slug')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        URL: /blog/<strong>{{ $slug }}</strong>
                                        @if ($slug !== $post->slug)
                                            <span class="text-warning ml-2">
                                                <i class="fas fa-exclamation-triangle"></i> URL will change
                                            </span>
                                        @endif
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id">Category *</label>
                                            <select class="form-control @error('category_id') is-invalid @enderror"
                                                id="category_id" wire:model="category_id">
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $category->id == $category_id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="reading_time">Reading Time (minutes)</label>
                                            <div class="input-group">
                                                <input type="number"
                                                    class="form-control @error('reading_time') is-invalid @enderror"
                                                    id="reading_time" wire:model="reading_time" min="1"
                                                    placeholder="Auto-calculated">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        wire:click="calculateReadingTime">
                                                        <i class="fas fa-calculator"></i> Calculate
                                                    </button>
                                                </div>
                                            </div>
                                            @error('reading_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" wire:model.live="excerpt"
                                        rows="3" placeholder="Brief summary of the post"></textarea>
                                    @error('excerpt')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ strlen($excerpt) }}/500 characters
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Content Editor -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-file-alt mr-2"></i>Content *</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" wire:model="content"
                                        rows="15" placeholder="Write your post content here..."></textarea>
                                    @error('content')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                    <small class="form-text text-muted">
                                        Supports HTML and Markdown
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-tags mr-2"></i>Tags</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Select Tags</label>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($availableTags as $tag)
                                            <button type="button"
                                                class="btn btn-sm {{ in_array($tag->id, $selectedTags) ? 'btn-primary' : 'btn-outline-primary' }}"
                                                wire:click="{{ in_array($tag->id, $selectedTags) ? 'removeTag(' . $tag->id . ')' : 'addTag(' . $tag->id . ')' }}">
                                                {{ $tag->name }}
                                                @if (in_array($tag->id, $selectedTags))
                                                    <i class="fas fa-check ml-1"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>

                                    <div class="input-group">
                                        <input type="text" class="form-control" wire:model="newTag"
                                            placeholder="Create new tag" wire:keydown.enter.prevent="createNewTag">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success" type="button"
                                                wire:click="createNewTag">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Press Enter or click Add to create a new tag
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Tab -->
                    <div x-show="activeTab === 'seo'" class="space-y-6">
                        <!-- SEO Statistics -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-line mr-2"></i>SEO Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div
                                                class="display-4 {{ $post->seo_score >= 80 ? 'text-success' : ($post->seo_score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                {{ $post->seo_score ?? 0 }}
                                            </div>
                                            <small class="text-muted">SEO Score</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-primary">
                                                {{ $post->views_count }}
                                            </div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-info">
                                                {{ $post->shares_count }}
                                            </div>
                                            <small class="text-muted">Shares</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-success">
                                                {{ $post->reading_time_minutes ?? 0 }}
                                            </div>
                                            <small class="text-muted">Min Read</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic SEO -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-search mr-2"></i>Basic SEO</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text"
                                        class="form-control @error('meta_title') is-invalid @enderror" id="meta_title"
                                        wire:model="meta_title" placeholder="SEO title for search engines">
                                    @error('meta_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div class="d-flex justify-content-between">
                                        <small class="form-text text-muted">
                                            Recommended: 50-60 characters
                                        </small>
                                        <small
                                            class="form-text {{ strlen($meta_title) > 60 ? 'text-danger' : 'text-success' }}">
                                            {{ strlen($meta_title) }} characters
                                        </small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                        wire:model="meta_description" rows="3" placeholder="SEO description for search engines"></textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <div class="d-flex justify-content-between">
                                        <small class="form-text text-muted">
                                            Recommended: 150-160 characters
                                        </small>
                                        <small
                                            class="form-text {{ strlen($meta_description) > 160 ? 'text-danger' : 'text-success' }}">
                                            {{ strlen($meta_description) }} characters
                                        </small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        wire:click="generateMetaDescription">
                                        <i class="fas fa-magic mr-1"></i>Generate from Excerpt
                                    </button>
                                </div>

                                <div class="form-group">
                                    <label for="focus_keyword">Focus Keyword</label>
                                    <input type="text"
                                        class="form-control @error('focus_keyword') is-invalid @enderror"
                                        id="focus_keyword" wire:model="focus_keyword"
                                        placeholder="Main keyword for SEO">
                                    @error('focus_keyword')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="canonical_url">Canonical URL</label>
                                    <input type="url"
                                        class="form-control @error('canonical_url') is-invalid @enderror"
                                        id="canonical_url" wire:model="canonical_url"
                                        placeholder="https://example.com/canonical-url">
                                    @error('canonical_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Open Graph -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fab fa-facebook mr-2"></i>Open Graph (Facebook)
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="og_title">OG Title</label>
                                    <input type="text"
                                        class="form-control @error('og_title') is-invalid @enderror" id="og_title"
                                        wire:model="og_title" placeholder="Title for Facebook shares">
                                    @error('og_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="og_description">OG Description</label>
                                    <textarea class="form-control @error('og_description') is-invalid @enderror" id="og_description"
                                        wire:model="og_description" rows="2" placeholder="Description for Facebook shares"></textarea>
                                    @error('og_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="og_type">OG Type</label>
                                    <select class="form-control @error('og_type') is-invalid @enderror"
                                        id="og_type" wire:model="og_type">
                                        <option value="article">Article</option>
                                        <option value="website">Website</option>
                                        <option value="blog">Blog</option>
                                    </select>
                                    @error('og_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Twitter Card -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fab fa-twitter mr-2"></i>Twitter Card</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="twitter_title">Twitter Title</label>
                                    <input type="text"
                                        class="form-control @error('twitter_title') is-invalid @enderror"
                                        id="twitter_title" wire:model="twitter_title"
                                        placeholder="Title for Twitter shares">
                                    @error('twitter_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="twitter_description">Twitter Description</label>
                                    <textarea class="form-control @error('twitter_description') is-invalid @enderror" id="twitter_description"
                                        wire:model="twitter_description" rows="2" placeholder="Description for Twitter shares"></textarea>
                                    @error('twitter_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="twitter_card_type">Twitter Card Type</label>
                                    <select class="form-control @error('twitter_card_type') is-invalid @enderror"
                                        id="twitter_card_type" wire:model="twitter_card_type">
                                        <option value="summary">Summary</option>
                                        <option value="summary_large_image">Summary Large Image</option>
                                    </select>
                                    @error('twitter_card_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>
                            </div>
                        </div>

                        <!-- Technical SEO -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-cogs mr-2"></i>Technical SEO</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="noindex"
                                                    wire:model="noindex">
                                                <label class="custom-control-label" for="noindex">
                                                    Noindex
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Prevent search engines from indexing this page
                                            </small>
                                        </div>

                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="nofollow"
                                                    wire:model="nofollow">
                                                <label class="custom-control-label" for="nofollow">
                                                    Nofollow
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Prevent search engines from following links on this page
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="include_in_sitemap" wire:model="include_in_sitemap">
                                                <label class="custom-control-label" for="include_in_sitemap">
                                                    Include in Sitemap
                                                </label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="sitemap_priority">Sitemap Priority</label>
                                            <select
                                                class="form-control @error('sitemap_priority') is-invalid @enderror"
                                                id="sitemap_priority" wire:model="sitemap_priority">
                                                <option value="0.1">0.1 - Low</option>
                                                <option value="0.3">0.3 - Medium-Low</option>
                                                <option value="0.5">0.5 - Medium</option>
                                                <option value="0.7">0.7 - Medium-High</option>
                                                <option value="0.9">0.9 - High</option>
                                                <option value="1.0">1.0 - Very High</option>
                                            </select>
                                            @error('sitemap_priority')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="sitemap_change_frequency">Sitemap Change Frequency</label>
                                    <select
                                        class="form-control @error('sitemap_change_frequency') is-invalid @enderror"
                                        id="sitemap_change_frequency" wire:model="sitemap_change_frequency">
                                        <option value="always">Always</option>
                                        <option value="hourly">Hourly</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                        <option value="never">Never</option>
                                    </select>
                                    @error('sitemap_change_frequency')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                <div class="form-group">
                                    <label for="article_type">Article Type</label>
                                    <select class="form-control @error('article_type') is-invalid @enderror"
                                        id="article_type" wire:model="article_type">
                                        <option value="Article">Article</option>
                                        <option value="BlogPosting">Blog Posting</option>
                                        <option value="NewsArticle">News Article</option>
                                    </select>
                                    @error('article_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images Tab -->
                    <div x-show="activeTab === 'images'" class="space-y-6">
                        <!-- Featured Image -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-image mr-2"></i>Featured Image</h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($temp_featured_image || $current_featured_image)
                                    <div class="mb-3">
                                        <img src="{{ $temp_featured_image ?: ($current_featured_image ? asset('storage/' . $current_featured_image) : '') }}"
                                            alt="Featured Image" class="img-fluid rounded"
                                            style="max-height: 300px;">
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger"
                                            wire:click="removeFeaturedImage">
                                            <i class="fas fa-trash mr-2"></i>Remove
                                        </button>
                                        <label class="btn btn-primary mb-0">
                                            <i class="fas fa-upload mr-2"></i>Change
                                            <input type="file" class="d-none" wire:model="featured_image"
                                                accept="image/*">
                                        </label>
                                    </div>
                                @else
                                    <div class="mb-3 text-muted">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>No featured image</p>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="featured_image"
                                            wire:model="featured_image" accept="image/*">
                                        <label class="custom-file-label" for="featured_image">
                                            Choose featured image...
                                        </label>
                                    </div>
                                    @error('featured_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror>
                                    <small class="form-text text-muted">
                                        Recommended: 1200x630px, max 5MB
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><i class="fas fa-images mr-2"></i>Gallery Images</h5>
                                <button type="button" class="btn btn-sm btn-primary" wire:click="addGalleryImage">
                                    <i class="fas fa-plus mr-1"></i>Add New Image
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Existing Gallery Images -->
                                @if (count($existing_gallery_images) > 0)
                                    <div class="row mb-4">
                                        @foreach ($existing_gallery_images as $index => $image)
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <img src="{{ asset('storage/' . $image) }}"
                                                            alt="Gallery Image {{ $index + 1 }}"
                                                            class="img-fluid rounded mb-2" style="max-height: 150px;">
                                                        <button type="button" class="btn btn-sm btn-danger btn-block"
                                                            wire:click="removeExistingGalleryImage({{ $index }})">
                                                            <i class="fas fa-trash mr-1"></i>Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- New Gallery Images -->
                                @if (count($gallery_images) > 0)
                                    <div class="row">
                                        @foreach ($gallery_images as $index => $image)
                                            <div class="col-md-4 mb-3">
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        @if (isset($temp_gallery_images[$index]) && $temp_gallery_images[$index])
                                                            <img src="{{ $temp_gallery_images[$index] }}"
                                                                alt="New Gallery Image {{ $index + 1 }}"
                                                                class="img-fluid rounded mb-2"
                                                                style="max-height: 150px;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="height: 150px;">
                                                                <i class="fas fa-image text-muted fa-2x"></i>
                                                            </div>
                                                        @endif
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input"
                                                                wire:model="gallery_images.{{ $index }}"
                                                                accept="image/*">
                                                            <label class="custom-file-label">
                                                                Choose image...
                                                            </label>
                                                        </div>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger btn-block mt-2"
                                                            wire:click="removeGalleryImage({{ $index }})">
                                                            <i class="fas fa-trash mr-1"></i>Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                @if (count($existing_gallery_images) === 0 && count($gallery_images) === 0)
                                    <div class="text-center text-muted">
                                        <i class="fas fa-images fa-3x mb-3"></i>
                                        <p>No gallery images</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Settings Tab -->
                    <div x-show="activeTab === 'settings'" class="space-y-6">
                        <!-- Post Statistics -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-bar mr-2"></i>Post Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-primary">
                                                {{ $post->views_count }}
                                            </div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-info">
                                                {{ $post->comments_count }}
                                            </div>
                                            <small class="text-muted">Comments</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-success">
                                                {{ $post->shares_count }}
                                            </div>
                                            <small class="text-muted">Shares</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-center">
                                            <div class="display-4 text-warning">
                                                {{ $post->likes_count }}
                                            </div>
                                            <small class="text-muted">Likes</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <small class="text-muted">Created:</small>
                                            <div>{{ $post->created_at }}</div>
                                        </div>
                                        <div>
                                            <small class="text-muted">Last Updated:</small>
                                            <div>{{ $post->updated_at }}</div>
                                        </div>
                                        @if ($post->published_at)
                                            <div>
                                                <small class="text-muted">Published:</small>
                                                <div>{{ $post->published_at }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Publishing Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-paper-plane mr-2"></i>Publishing Settings
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        wire:model.live="status">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="scheduled">Scheduled</option>
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                @if ($status === 'published')
                                    <div class="form-group">
                                        <label for="published_at">Publish Date & Time</label>
                                        <input type="datetime-local"
                                            class="form-control @error('published_at') is-invalid @enderror"
                                            id="published_at" wire:model="published_at">
                                        @error('published_at')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror>
                                    </div>
                                @endif

                                @if ($status === 'scheduled')
                                    <div class="form-group">
                                        <label for="scheduled_for">Schedule For</label>
                                        <input type="datetime-local"
                                            class="form-control @error('scheduled_for') is-invalid @enderror"
                                            id="scheduled_for" wire:model="scheduled_for"
                                            min="{{ now()->format('Y-m-d\TH:i') }}">
                                        @error('scheduled_for')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="visibility">Visibility</label>
                                    <select class="form-control @error('visibility') is-invalid @enderror"
                                        id="visibility" wire:model.live="visibility">
                                        <option value="public">Public</option>
                                        <option value="private">Private</option>
                                        <option value="password_protected">Password Protected</option>
                                    </select>
                                    @error('visibility')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror>
                                </div>

                                @if ($visibility === 'password_protected')
                                    <div class="form-group">
                                        <label for="password">Password *</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            id="password" wire:model="password"
                                            placeholder="Enter new password or leave empty to keep current">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Post Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-cog mr-2"></i>Post Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_featured"
                                            wire:model="is_featured">
                                        <label class="custom-control-label" for="is_featured">
                                            Featured Post
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Mark this post as featured (will appear in featured sections)
                                    </small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="allow_comments"
                                            wire:model="allow_comments">
                                        <label class="custom-control-label" for="allow_comments">
                                            Allow Comments
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="allow_sharing"
                                            wire:model="allow_sharing">
                                        <label class="custom-control-label" for="allow_sharing">
                                            Allow Sharing
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>

                                <div>
                                    <button type="submit" name="action" value="draft"
                                        class="btn btn-outline-primary mr-2" wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save mr-2"></i>Save Draft
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                        </span>
                                    </button>

                                    @if ($status === 'published')
                                        <button type="submit" name="action" value="publish"
                                            class="btn btn-success" wire:loading.attr="disabled">
                                            <span wire:loading.remove>
                                                <i class="fas fa-paper-plane mr-2"></i>Update & Publish
                                            </span>
                                            <span wire:loading>
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Updating...
                                            </span>
                                        </button>
                                    @elseif($status === 'scheduled')
                                        <button type="submit" name="action" value="schedule"
                                            class="btn btn-warning" wire:loading.attr="disabled">
                                            <span wire:loading.remove>
                                                <i class="fas fa-clock mr-2"></i>Update & Schedule
                                            </span>
                                            <span wire:loading>
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Scheduling...
                                            </span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .nav-tabs .nav-link {
                cursor: pointer;
            }

            .custom-file-label::after {
                content: "Browse";
            }

            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Update file input labels
            document.querySelectorAll('.custom-file-input').forEach(input => {
                input.addEventListener('change', function(e) {
                    var fileName = e.target.files[0]?.name || 'Choose file...';
                    var nextSibling = e.target.nextElementSibling;
                    nextSibling.innerText = fileName;
                });
            });

            // Initialize Alpine.js for tabs
            document.addEventListener('alpine:init', () => {
                Alpine.data('tabs', () => ({
                    activeTab: 'content',
                    setActiveTab(tab) {
                        this.activeTab = tab;
                    }
                }));
            });
        </script>
    @endpush
</div>
