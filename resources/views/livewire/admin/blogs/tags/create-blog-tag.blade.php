<div>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title font-weight-bold d-inline">
                    <i class="fas fa-plus-circle mr-2"></i>Create New Tag
                </h3>
                <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-secondary btn-sm float-right">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tags
                </a>
            </div>

            <div class="card-body">
                @include('components.alerts.response-alerts')

                <form wire:submit.prevent="save">
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
                                            id="name" wire:model="name"
                                            placeholder="Enter tag name (e.g., Laravel, Web Development)">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Choose a descriptive name that represents your content
                                        </small>
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
                                            This will be used in the URL:
                                            /blog/tag/<strong>{{ $slug ?: 'your-tag' }}</strong>
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
                                            {{ strlen($description) }}/500 characters.
                                            Helps users understand what content to expect.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-search mr-2"></i>SEO Settings
                                        <small class="float-right">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="generateMetaTitle">
                                                <i class="fas fa-magic mr-1"></i>Auto Generate
                                            </button>
                                        </small>
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
                                        <textarea class="form-control tinymce @error('meta_description') is-invalid @enderror" id="meta_description" data-model="meta_description"
                                            rows="3" placeholder="SEO description for search engines"></textarea>
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
                                            <i class="fas fa-magic mr-1"></i>Auto Generate Description
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Tag Preview -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-eye mr-2"></i>Tag Preview
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <span class="badge badge-primary p-3" style="font-size: 1.5rem;">
                                            <i class="fas fa-tag mr-2"></i>
                                            {{ $name ?: 'Your Tag' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <strong>URL:</strong>
                                        <div class="mt-1">
                                            <code class="small">/blog/tag/{{ $slug ?: 'your-tag-slug' }}</code>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Posts Count:</strong>
                                        <div class="mt-1">
                                            <span class="badge badge-secondary">0 posts</span>
                                            <small class="text-muted d-block">This will increase as you tag
                                                posts</small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <strong>Popularity:</strong>
                                        <div class="mt-1">
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-seedling mr-1"></i>New Tag
                                            </span>
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
                                                {{ $meta_title ? Str::limit($meta_title, 60) : ($name ? Str::limit($name . ' - Tips & Resources', 60) : 'Your Meta Title') }}
                                            </strong>
                                        </div>
                                        <div class="mb-2">
                                            <code class="text-success" style="font-size: 14px;">
                                                {{ url('/blog/tag/' . ($slug ?: 'your-tag')) }}
                                            </code>
                                        </div>
                                        <div>
                                            <small class="text-muted" style="font-size: 14px; line-height: 1.4;">
                                                {{ $meta_description ? Str::limit($meta_description, 160) : ($description ? Str::limit($description, 160) : 'Your meta description will appear here...') }}
                                            </small>
                                        </div>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        This is how your tag might appear in search results
                                    </small>
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
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('admin.blog-tags.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times mr-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                            <span wire:loading.remove>
                                                <i class="fas fa-save mr-2"></i>Create Tag
                                            </span>
                                            <span wire:loading>
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                            </span>
                                        </button>
                                    </div>

                                    <hr>

                                    <div class="small text-muted">
                                        <p><strong>Tips:</strong></p>
                                        <ul class="pl-3">
                                            <li>Keep tag names simple and descriptive</li>
                                            <li>Use lowercase for slugs</li>
                                            <li>Add SEO meta for better search visibility</li>
                                            <li>Tags can be assigned to multiple posts</li>
                                        </ul>
                                    </div>
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

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
    @endpush
</div>