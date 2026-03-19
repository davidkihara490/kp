<div>
    <div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title font-weight-bold d-inline">
                        <i class="fas fa-folder mr-2"></i>{{ $category->name }}
                    </h3>
                    <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }} ml-2">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <div>

                <a href="{{ route('admin.blog-categories.edit', $category->id) }}" 
                       class="btn btn-warning btn-sm">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>

                    <a href="{{ route('admin.blog-categories.index') }}" 
                       class="btn btn-secondary btn-sm">
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
                       wire:click="setActiveTab('overview')"
                       href="javascript:void(0)">
                        <i class="fas fa-info-circle mr-2"></i>Overview
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'posts' ? 'active' : '' }}" 
                       wire:click="setActiveTab('posts')"
                       href="javascript:void(0)">
                        <i class="fas fa-newspaper mr-2"></i>Posts
                        <span class="badge badge-light ml-1">{{ $category->posts()->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'subcategories' ? 'active' : '' }}" 
                       wire:click="setActiveTab('subcategories')"
                       href="javascript:void(0)">
                        <i class="fas fa-sitemap mr-2"></i>Sub-categories
                        <span class="badge badge-light ml-1">{{ $category->children()->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'seo' ? 'active' : '' }}" 
                       wire:click="setActiveTab('seo')"
                       href="javascript:void(0)">
                        <i class="fas fa-search mr-2"></i>SEO
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                @if($activeTab === 'overview')
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>Category Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Basic Information</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Name:</th>
                                                <td>{{ $category->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Slug:</th>
                                                <td>
                                                    <code>{{ $category->slug }}</code>
                                                    <small class="text-muted ml-2">
                                                        /blog/category/{{ $category->slug }}
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Display Order:</th>
                                                <td>{{ $category->order }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    <span class="badge badge-{{ $category->is_active ? 'success' : 'danger' }}">
                                                        <i class="fas {{ $category->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    <button wire:click="toggleStatus" 
                                                            class="btn btn-sm btn-outline-{{ $category->is_active ? 'danger' : 'success' }} ml-2">
                                                        <i class="fas {{ $category->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                                        {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Parent Category:</th>
                                                <td>
                                                    @if($category->parent)
                                                        <a href="{{ route('admin.blog-categories.view', $category->parent->id) }}" 
                                                           class="badge badge-info">
                                                            <i class="fas fa-level-up-alt mr-1"></i>
                                                            {{ $category->parent->name }}
                                                        </a>
                                                    @else
                                                        <span class="badge badge-primary">
                                                            <i class="fas fa-home mr-1"></i>Main Category
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Statistics</h6>
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Total Posts:</th>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ $category->posts()->count() }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Sub-categories:</th>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $category->children()->count() }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created:</th>
                                                <td>
                                                    {{ $category->created_at }}
                                                    <small class="text-muted d-block">
                                                        {{ $category->created_at?->diffForHumans() }}
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Last Updated:</th>
                                                <td>
                                                    {{ $category->updated_at }}
                                                    <small class="text-muted d-block">
                                                        {{ $category->updated_at?->diffForHumans() }}
                                                    </small>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($category->description)
                                <div class="mt-3">
                                    <h6>Description</h6>
                                    <div class="border rounded p-3 bg-light">
                                        {{ $category->description }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        @if($category->featured_image)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-image mr-2"></i>Featured Image
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ asset('storage/' . $category->featured_image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid rounded"
                                     style="max-height: 400px;">
                            </div>
                        </div>
                        @endif
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
                                    {{-- <a href="{{ route('admin.blog-categories.edit', $category->id) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="fas fa-edit mr-2 text-primary"></i>Edit Category
                                    </a> --}}
                                    <a href="{{ route('admin.blog-posts.create', ['category_id' => $category->id]) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="fas fa-plus mr-2 text-success"></i>Add New Post
                                    </a>
                                    @if(!$category->parent_id)
                                    <a href="{{ route('admin.blog-categories.create', ['parent_id' => $category->id]) }}" 
                                       class="list-group-item list-group-item-action">
                                        <i class="fas fa-folder-plus mr-2 text-info"></i>Add Sub-category
                                    </a>
                                    @endif
                                    <button class="list-group-item list-group-item-action text-danger"
                                            onclick="confirm('Are you sure?') && Livewire.dispatch('deleteCategory')">
                                        <i class="fas fa-trash mr-2"></i>Delete Category
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Hierarchy -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-sitemap mr-2"></i>Category Hierarchy
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="category-tree">
                                    @if($category->parent)
                                        <div class="mb-2">
                                            <small class="text-muted">Parent:</small>
                                            <div class="ml-3">
                                                <i class="fas fa-level-up-alt text-muted mr-2"></i>
                                                <a href="{{ route('admin.blog-categories.view', $category->parent->id) }}">
                                                    {{ $category->parent->name }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">Current:</small>
                                        <div class="ml-3">
                                            <i class="fas fa-folder text-warning mr-2"></i>
                                            <strong>{{ $category->name }}</strong>
                                        </div>
                                    </div>
                                    
                                    @if($category->children()->count() > 0)
                                        <div>
                                            <small class="text-muted">Children ({{ $category->children()->count() }}):</small>
                                            <div class="ml-3">
                                                @foreach($category->children as $child)
                                                    <div class="mb-1">
                                                        <i class="fas fa-folder text-info mr-2"></i>
                                                        <a href="{{ route('admin.blog-categories.view', $child->id) }}">
                                                            {{ $child->name }}
                                                        </a>
                                                        <small class="text-muted ml-2">
                                                            ({{ $child->posts()->count() }} posts)
                                                        </small>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Posts Tab -->
                @if($activeTab === 'posts')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-newspaper mr-2"></i>Blog Posts in This Category
                        </h5>
                        <a href="{{ route('admin.blog-posts.create', ['category_id' => $category->id]) }}" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus mr-2"></i>Add Post
                        </a>
                    </div>
                    <div class="card-body">
                        @if($relatedPosts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Views</th>
                                            <th>Published</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($relatedPosts as $post)
                                        <tr>
                                            <td>
                                                <strong>{{ $post->title }}</strong><br>
                                                <small class="text-muted">
                                                    {{ Str::limit($post->excerpt, 50) }}
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $post->is_published ? 'success' : 'warning' }}">
                                                    {{ $post->is_published ? 'Published' : 'Draft' }}
                                                </span>
                                            </td>
                                            <td>{{ $post->views ?? 0 }}</td>
                                            <td>
                                                @if($post->published_at)
                                                    {{ $post->published_at }}
                                                @else
                                                    <span class="text-muted">Not published</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.blog-posts.view', $post->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($category->posts()->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.blog-posts.index', ['category' => $category->id]) }}" 
                                       class="btn btn-outline-primary">
                                        View All Posts ({{ $category->posts()->count() }})
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No posts in this category yet</p>
                                <a href="{{ route('admin.blog-posts.create', ['category_id' => $category->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Create First Post
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Sub-categories Tab -->
                @if($activeTab === 'subcategories')
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sitemap mr-2"></i>Sub-categories
                        </h5>
                        @if(!$category->parent_id)
                            <a href="{{ route('admin.blog-categories.create', ['parent_id' => $category->id]) }}" 
                               class="btn btn-sm btn-success">
                                <i class="fas fa-plus mr-2"></i>Add Sub-category
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($subCategories->count() > 0)
                            <div class="row">
                                @foreach($subCategories as $subCategory)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <a href="{{ route('admin.blog-categories.view', $subCategory->id) }}">
                                                            {{ $subCategory->name }}
                                                        </a>
                                                    </h6>
                                                    @if($subCategory->description)
                                                        <p class="small text-muted mb-2">
                                                            {{ Str::limit($subCategory->description, 80) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span class="badge badge-{{ $subCategory->is_active ? 'success' : 'danger' }}">
                                                    {{ $subCategory->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-newspaper mr-1"></i>
                                                    {{ $subCategory->posts()->count() }} posts
                                                </small>
                                                <small class="text-muted">
                                                    Order: {{ $subCategory->order }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-folder fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No sub-categories</p>
                                @if(!$category->parent_id)
                                    <a href="{{ route('admin.blog-categories.create', ['parent_id' => $category->id]) }}" 
                                       class="btn btn-primary">
                                        <i class="fas fa-plus mr-2"></i>Create First Sub-category
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- SEO Tab -->
                @if($activeTab === 'seo')
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
                                    {{ $category->meta_title ?? 'Not set' }}
                                </div>
                                
                                <h6>Meta Description</h6>
                                <div class="border rounded p-3 bg-light mb-3">
                                    {{ $category->meta_description ?? 'Not set' }}
                                </div>
                                
                                <h6>URL Structure</h6>
                                <div class="border rounded p-3 bg-light">
                                    <code>/blog/category/{{ $category->slug }}</code>
                                    @if($category->parent)
                                        <br>
                                        <small class="text-muted">
                                            Full path: 
                                            <code>/blog/category/{{ $category->parent->slug }}/{{ $category->slug }}</code>
                                        </small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Meta Keywords</h6>
                                @if($category->meta_keywords && count($category->meta_keywords) > 0)
                                    <div class="d-flex flex-wrap gap-2 mb-4">
                                        @foreach($category->meta_keywords as $keyword)
                                            <span class="badge badge-secondary">{{ $keyword }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info mb-4">
                                        No meta keywords set
                                    </div>
                                @endif
                                
                                <h6>SEO Preview</h6>
                                <div class="border rounded p-3 bg-white">
                                    <div class="mb-2">
                                        <strong class="text-primary" style="font-size: 18px;">
                                            {{ $category->meta_title ? Str::limit($category->meta_title, 60) : $category->name }}
                                        </strong>
                                    </div>
                                    <div class="mb-2">
                                        <code class="text-success">{{ url('/blog/category/' . $category->slug) }}</code>
                                    </div>
                                    <div>
                                        <small class="text-muted">
                                            {{ $category->meta_description ? Str::limit($category->meta_description, 160) : Str::limit($category->description, 160) }}
                                        </small>
                                    </div>
                                </div>
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
    .category-tree {
        font-size: 0.9rem;
    }
    .category-tree .fa {
        width: 16px;
    }
    .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    .list-group-item:first-child {
        border-top: none;
    }
    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush
</div>