<div>
    <div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title font-weight-bold d-inline">
                <i class="fas fa-plus-circle mr-2"></i>Create New Category
            </h3>
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary btn-sm float-right">
                <i class="fas fa-arrow-left mr-2"></i>Back to Categories
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
                                    <i class="fas fa-info-circle mr-2"></i>Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Category Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           wire:model="name"
                                           placeholder="Enter category name">
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="slug">Slug *</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control @error('slug') is-invalid @enderror" 
                                               id="slug" 
                                               wire:model="slug"
                                               placeholder="URL-friendly slug">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="generateSlug()">
                                                <i class="fas fa-sync-alt"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    @error('slug')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        This will be used in the URL: /blog/category/<strong>{{ $slug ?: 'your-slug' }}</strong>
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              wire:model="description"
                                              rows="3"
                                              placeholder="Brief description of the category"></textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ strlen($description) }}/500 characters
                                    </small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="parent_id">Parent Category</label>
                                            <select class="form-control @error('parent_id') is-invalid @enderror" 
                                                    id="parent_id" 
                                                    wire:model="parent_id">
                                                <option value="">No Parent (Main Category)</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('parent_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="order">Display Order *</label>
                                            <input type="number" 
                                                   class="form-control @error('order') is-invalid @enderror" 
                                                   id="order" 
                                                   wire:model="order"
                                                   min="0">
                                            @error('order')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
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
                                           id="meta_title" 
                                           wire:model="meta_title"
                                           placeholder="SEO title for search engines">
                                    @error('meta_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended: 50-60 characters. Current: {{ strlen($meta_title) }}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" 
                                              wire:model="meta_description"
                                              rows="2"
                                              placeholder="SEO description for search engines"></textarea>
                                    @error('meta_description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended: 150-160 characters. Current: {{ strlen($meta_description) }}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Meta Keywords</label>
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               class="form-control" 
                                               wire:model="keyword"
                                               placeholder="Add keyword and press Enter"
                                               wire:keydown.enter.prevent="addKeyword">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button" 
                                                    wire:click="addKeyword">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if(count($meta_keywords) > 0)
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($meta_keywords as $index => $keyword)
                                                <span class="badge badge-primary d-flex align-items-center">
                                                    {{ $keyword }}
                                                    <button type="button" 
                                                            class="btn btn-xs btn-link text-white ml-2 p-0"
                                                            wire:click="removeKeyword({{ $index }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <small class="text-muted">No keywords added yet</small>
                                    @endif
                                    @error('meta_keywords')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Featured Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-image mr-2"></i>Featured Image
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                @if($temp_featured_image || $featured_image)
                                    <div class="mb-3">
                                        <img src="{{ $temp_featured_image ?: ($featured_image ? $featured_image->temporaryUrl() : '') }}" 
                                             alt="Featured Image Preview" 
                                             class="img-fluid rounded"
                                             style="max-height: 200px;">
                                    </div>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            wire:click="removeFeaturedImage">
                                        <i class="fas fa-trash mr-2"></i>Remove Image
                                    </button>
                                @else
                                    <div class="mb-3 text-muted">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>No image selected</p>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input" 
                                               id="featured_image" 
                                               wire:model="featured_image"
                                               accept="image/*">
                                        <label class="custom-file-label" for="featured_image">
                                            Choose image...
                                        </label>
                                    </div>
                                    @error('featured_image')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended: 1200x600px, max 2MB
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Status & Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-toggle-on mr-2"></i>Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               wire:model="is_active"
                                               checked>
                                        <label class="custom-control-label" for="is_active">
                                            @if($is_active)
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle mr-1"></i>Active
                                                </span>
                                            @else
                                                <span class="text-danger">
                                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                                </span>
                                            @endif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Inactive categories won't be displayed on the website
                                    </small>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between">
                                    <button type="button" 
                                            class="btn btn-secondary"
                                            wire:click="$dispatch('close-modal')">
                                        <i class="fas fa-times mr-2"></i>Cancel
                                    </button>
                                    <button type="submit" 
                                            class="btn btn-primary"
                                            wire:loading.attr="disabled">
                                        <span wire:loading.remove>
                                            <i class="fas fa-save mr-2"></i>Create Category
                                        </span>
                                        <span wire:loading>
                                            <i class="fas fa-spinner fa-spin mr-2"></i>Saving...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-eye mr-2"></i>Preview
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="small">
                                    <p><strong>Name:</strong> {{ $name ?: 'Not set' }}</p>
                                    <p><strong>Slug:</strong> {{ $slug ?: 'Not set' }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge badge-{{ $is_active ? 'success' : 'danger' }}">
                                            {{ $is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                    @if($parent_id && $categories->find($parent_id))
                                        <p><strong>Parent:</strong> {{ $categories->find($parent_id)->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateSlug() {
    const name = document.getElementById('name').value;
    if (name) {
        @this.set('slug', name.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim());
    }
}

// Update custom file input label
document.querySelector('.custom-file-input')?.addEventListener('change', function(e) {
    var fileName = e.target.files[0]?.name || 'Choose file...';
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});
</script>
@endpush
</div>