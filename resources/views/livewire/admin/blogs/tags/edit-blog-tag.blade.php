<div>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold d-inline">
                            <i class="fas fa-edit mr-2"></i>Edit Tag: {{ $tag->name }}
                        </h3>
                        <span
                            class="badge badge-{{ $tag->post_count >= 20 ? 'danger' : ($tag->post_count >= 10 ? 'warning' : 'info') }} ml-2">
                            {{ $tag->post_count }} posts
                        </span>
                    </div>
                    <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Tags
                    </a>
                </div>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="update">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tag mr-2"></i>Tag Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Tag Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" wire:model="name" placeholder="Enter tag name">
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="slug">Slug *</label>
                                        <div class="input-group">
                                            <input type="text"
                                                class="form-control @error('slug') is-invalid @enderror" id="slug"
                                                wire:model="slug" placeholder="URL-friendly slug">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    wire:click="generateSlug">
                                                    <i class="fas fa-sync-alt"></i> Generate
                                                </button>
                                            </div>
                                        </div>
                                        @error('slug')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Changing the slug will break existing links. Use with caution.
                                        </small>
                                    </div>

                                    <div class="form-group" wire:ignore>
                                        <label for="description">Description</label>
                                        <textarea class="form-control tinymce @error('description') is-invalid @enderror" id="description" data-model="description"
                                            rows="4" placeholder="Brief description of what this tag represents"></textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            {{ strlen($description) }}/500 characters
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-search mr-2"></i>SEO Settings
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text"
                                            class="form-control @error('meta_title') is-invalid @enderror"
                                            id="meta_title" wire:model="meta_title"
                                            placeholder="SEO title for search engines">
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

                                    <div class="form-group" wire:ignore>
                                        <label for="meta_description">Meta Description</label>
                                        <textarea class="form-control tinymce @error('meta_description') is-invalid @enderror" id="meta_description"
                                            data-model="meta_description" rows="3" placeholder="SEO description for search engines"></textarea>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Statistics -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar mr-2"></i>Statistics
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @php
                                            $popularity = $this->getPopularityBadge($tag->post_count);
                                        @endphp
                                        <span class="badge badge-{{ $popularity['color'] }} p-3"
                                            style="font-size: 1.5rem;">
                                            <i class="fas {{ $popularity['icon'] }} mr-2"></i>
                                            {{ $tag->post_count }} posts
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Current URL:</strong>
                                        <div class="mt-1">
                                            <code class="small">/blog/tag/{{ $tag->slug }}</code>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>New URL:</strong>
                                        <div class="mt-1">
                                            <code class="small">/blog/tag/{{ $slug }}</code>
                                            @if ($slug !== $tag->slug)
                                                <small class="text-warning d-block">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    URL will change
                                                </small>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <strong>Created:</strong>
                                            <div class="mt-1">
                                                {{ $tag->created_at }}
                                                <small class="text-muted d-block">
                                                    {{ $tag->created_at }}
                                                    {{-- {{ $tag->created_at->diffForHumans() }} --}}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <strong>Last Updated:</strong>
                                            <div class="mt-1">
                                                {{ $tag->updated_at }}
                                                <small class="text-muted d-block">
                                                    {{ $tag->updated_at }}
                                                    {{-- {{ $tag->updated_at->diffForHumans() }} --}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Preview -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-search mr-2"></i>SEO Preview
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="seo-preview border rounded p-3 bg-white">
                                            <div class="mb-2">
                                                <strong class="text-primary" style="font-size: 18px;">
                                                    {{ $meta_title ? Str::limit($meta_title, 60) : Str::limit($name . ' - Tips & Resources', 60) }}
                                                </strong>
                                            </div>
                                            <div class="mb-2">
                                                <code class="text-success" style="font-size: 14px;">
                                                    {{ url('/blog/tag/' . $slug) }}
                                                </code>
                                            </div>
                                            <div>
                                                <small class="text-muted" style="font-size: 14px; line-height: 1.4;">
                                                    {{ $meta_description ? Str::limit($meta_description, 160) : Str::limit($description, 160) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-cogs mr-2"></i>Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                wire:click="generateMetaTitle">
                                                <i class="fas fa-magic mr-1"></i>Auto Title
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                wire:click="generateMetaDescription">
                                                <i class="fas fa-magic mr-1"></i>Auto Description
                                            </button>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('admin.blog-tags.view', $tag->id) }}" class="btn btn-info">
                                                <i class="fas fa-eye mr-2"></i>View
                                            </a>
                                            <button type="submit" class="btn btn-primary"
                                                wire:loading.attr="disabled">
                                                <span wire:loading.remove>
                                                    <i class="fas fa-save mr-2"></i>Update Tag
                                                </span>
                                                <span wire:loading>
                                                    <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                                </span>
                                            </button>
                                        </div>

                                        @if ($tag->posts()->count() > 0)
                                            <hr>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                This tag is used in {{ $tag->posts()->count() }} posts.
                                                Changes will affect all associated posts.
                                            </div>
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
            .seo-preview {
                border-left: 4px solid #4dabf7 !important;
            }

            .badge {
                transition: all 0.3s ease;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Add method to component
            Livewire.on('blog-tag-edit', {
                getPopularityBadge(postCount) {
                    if (postCount >= 50) {
                        return {
                            color: 'danger',
                            text: 'Very Popular',
                            icon: 'fa-fire'
                        };
                    } else if (postCount >= 20) {
                        return {
                            color: 'warning',
                            text: 'Popular',
                            icon: 'fa-star'
                        };
                    } else if (postCount >= 10) {
                        return {
                            color: 'info',
                            text: 'Trending',
                            icon: 'fa-chart-line'
                        };
                    } else if (postCount >= 5) {
                        return {
                            color: 'primary',
                            text: 'Active',
                            icon: 'fa-bolt'
                        };
                    } else {
                        return {
                            color: 'secondary',
                            text: 'New',
                            icon: 'fa-seedling'
                        };
                    }
                }
            });
        </script>
    @endpush
</div>


