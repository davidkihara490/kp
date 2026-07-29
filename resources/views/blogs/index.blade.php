@extends('layouts.app')

@section('title', 'Blog | Karibu Parcels - Latest News & Updates')

@section('meta')
    <meta name="description" content="Stay updated with the latest news, tips, and insights from Karibu Parcels. Read our blog for courier tips, industry updates, and company announcements.">
@endsection

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container text-center">
            <h1 class="animate-fade-up">Our Blog</h1>
            <p class="lead mb-4 animate-fade-up" style="animation-delay: 0.1s;">Stay updated with the latest news, tips, and insights from Karibu Parcels</p>
            @include('layouts.partials.breadcrumb', [
                'breadcrumbs' => [
                    ['label' => 'Home', 'url' => url('/')],
                    ['label' => 'Blog', 'url' => route('blogs.index')]
                ]
            ])
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-main">
        <div class="container">
            @if(isset($blogPosts) && $blogPosts->count() > 0)
                <!-- Blog Header with Search -->
                <div class="blog-header">
                    <div class="blog-header-title">
                        <h2>Latest Articles</h2>
                        <p>Discover our latest posts and updates</p>
                    </div>
                    <div class="blog-search">
                        <form action="{{ route('blogs.search') }}" method="GET">
                            <input type="text" class="blog-search-input" name="search" placeholder="Search articles..." value="{{ request('search') }}">
                            <button type="submit" class="blog-search-btn">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Category Pills -->
                @if(isset($categories) && $categories->count() > 0)
                <div class="category-pills">
                    <a href="{{ route('blogs.index') }}" class="category-pill {{ !request('category') ? 'active' : '' }}">All</a>
                    @foreach($categories as $category)
                    <a href="{{ route('blogs.category', $category->slug) }}" class="category-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
                @endif

                <!-- Featured Post -->
                @if(isset($featuredPost) && $featuredPost && $blogPosts->currentPage() == 1)
                <div class="featured-post animate-fade-up">
                    <div class="featured-post-grid">
                        <div class="featured-post-image" style="background-image: url('{{ $featuredPost->featured_image ? asset('storage/' . $featuredPost->featured_image) : asset('logo.jpeg') }}')">
                            <span class="featured-badge">Featured Post</span>
                        </div>
                        <div class="featured-post-content">
                            <div class="featured-meta">
                                <span><i class="bi bi-calendar3"></i> {{ $featuredPost->created_at->format('M d, Y') }}</span>
                                <span><i class="bi bi-clock"></i> {{ $featuredPost->read_time ?? '5' }} min read</span>
                                <span><i class="bi bi-eye"></i> {{ number_format($featuredPost->views ?? 0) }} views</span>
                            </div>
                            <h2 class="featured-title">
                                <a href="{{ route('blog.show', $featuredPost->slug ?? $featuredPost->id) }}">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            <p class="featured-excerpt">{{ Str::limit(strip_tags($featuredPost->content), 200) }}</p>
                            <div class="featured-footer">
                                <div class="featured-author">
                                    <div class="author-avatar-lg">
                                        {{ substr($featuredPost->author->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="author-info-lg">
                                        <span class="author-name-lg">{{ $featuredPost->author->name ?? 'Admin' }}</span>
                                        <span class="author-role-lg">{{ $featuredPost->author->role ?? 'Author' }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $featuredPost->slug ?? $featuredPost->id) }}" class="read-more-btn">
                                    Read Article <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Blog Grid and Sidebar -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="blog-grid">
                            @foreach($blogPosts as $post)
                                @if(!(isset($featuredPost) && $featuredPost && $post->id == $featuredPost->id && $blogPosts->currentPage() == 1))
                                <div class="blog-card animate-fade-up" style="animation-delay: {{ $loop->iteration * 0.1 }}s">
                                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="blog-card-image-link">
                                        <div class="blog-card-image" style="background-image: url('{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}')">
                                            <span class="blog-card-badge">{{ $post->category->name ?? 'General' }}</span>
                                        </div>
                                    </a>
                                    <div class="blog-card-content">
                                        <div class="blog-card-meta">
                                            <span><i class="bi bi-calendar3"></i> {{ $post->created_at->format('M d, Y') }}</span>
                                            <span><i class="bi bi-clock"></i> {{ $post->read_time ?? '5' }} min</span>
                                        </div>
                                        <h3 class="blog-card-title">
                                            <a href="{{ route('blog.show', $post->slug ?? $post->id) }}">
                                                {{ $post->title }}
                                            </a>
                                        </h3>
                                        <p class="blog-card-excerpt">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                                        <div class="blog-card-footer">
                                            <div class="blog-card-author">
                                                <div class="author-avatar-sm">
                                                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                                                </div>
                                                <span class="author-name-sm">{{ $post->author->name ?? 'Admin' }}</span>
                                            </div>
                                            <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="read-more-link">
                                                Read More <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($blogPosts, 'links'))
                        <div class="pagination-container">
                            {{ $blogPosts->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        @include('blogs.partials.sidebar')
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state animate-fade-up">
                    <i class="bi bi-newspaper"></i>
                    <h3>No Blog Posts Yet</h3>
                    <p class="text-muted">We're working on creating valuable content for you. Check back soon for updates!</p>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        padding: 120px 0 80px;
        margin-top: 0;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-repeat: no-repeat;
        background-position: bottom;
        background-size: cover;
        opacity: 0.3;
    }
    .page-header h1 {
        font-size: 3.2rem;
        font-weight: 800;
        margin-bottom: 20px;
        position: relative;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
    .page-header .lead {
        font-size: 1.2rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }
    .page-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin-top: 20px;
        position: relative;
        justify-content: center;
    }
    .page-header .breadcrumb-item {
        color: rgba(255,255,255,0.8);
    }
    .page-header .breadcrumb-item a {
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .page-header .breadcrumb-item a:hover {
        text-decoration: underline;
    }
    .page-header .breadcrumb-item.active {
        color: white;
        font-weight: 500;
    }
    .page-header .breadcrumb-item+.breadcrumb-item::before {
        color: rgba(255,255,255,0.5);
    }
    .blog-main {
        padding: 60px 0 80px;
    }
    .blog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
        gap: 20px;
    }
    .blog-header-title h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 5px;
    }
    .blog-header-title p {
        color: var(--text-light);
        margin-bottom: 0;
    }
    .blog-search {
        width: 350px;
        position: relative;
    }
    .blog-search-input {
        width: 100%;
        padding: 12px 50px 12px 20px;
        border: 2px solid #e9ecef;
        border-radius: 50px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .blog-search-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,143,64,0.1);
    }
    .blog-search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-color);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .blog-search-btn:hover {
        background: var(--primary-dark);
    }
    .category-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
    }
    .category-pill {
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-dark);
        background: white;
        border: 2px solid #e9ecef;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .category-pill:hover,
    .category-pill.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,143,64,0.2);
    }
    .featured-post {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-bottom: 50px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .featured-post:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,143,64,0.1);
        border-color: var(--primary-color);
    }
    .featured-post-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 0;
    }
    .featured-post-image {
        height: 400px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .featured-post-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.3), transparent);
    }
    .featured-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: var(--accent-color);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(255,53,25,0.3);
    }
    .featured-post-content {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .featured-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 0.9rem;
        color: var(--text-light);
    }
    .featured-meta i {
        color: var(--primary-color);
        margin-right: 5px;
    }
    .featured-title {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 20px;
        color: var(--text-dark);
    }
    .featured-title a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .featured-title a:hover {
        color: var(--primary-color);
    }
    .featured-excerpt {
        color: var(--text-light);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 25px;
    }
    .featured-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .featured-author {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .author-avatar-lg {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .author-info-lg {
        font-size: 0.95rem;
    }
    .author-name-lg {
        font-weight: 700;
        color: var(--text-dark);
        display: block;
    }
    .author-role-lg {
        color: var(--text-light);
        font-size: 0.85rem;
    }
    .read-more-btn {
        background: var(--primary-color);
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .read-more-btn:hover {
        background: var(--primary-dark);
        color: white;
        transform: translateX(5px);
    }
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }
    .blog-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .blog-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,143,64,0.1);
        border-color: var(--primary-color);
    }
    .blog-card-image-link {
        text-decoration: none;
        display: block;
        overflow: hidden;
        position: relative;
    }
    .blog-card-image {
        height: 220px;
        background-size: cover;
        background-position: center;
        transition: transform 0.5s ease;
        position: relative;
    }
    .blog-card:hover .blog-card-image {
        transform: scale(1.05);
    }
    .blog-card-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--primary-color);
        color: white;
        padding: 5px 15px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(0,143,64,0.3);
    }
    .blog-card-content {
        padding: 25px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-card-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 0.8rem;
        color: var(--text-light);
    }
    .blog-card-meta i {
        color: var(--primary-color);
        margin-right: 5px;
    }
    .blog-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 12px;
    }
    .blog-card-title a {
        color: var(--text-dark);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .blog-card-title a:hover {
        color: var(--primary-color);
    }
    .blog-card-excerpt {
        color: var(--text-light);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
    }
    .blog-card-author {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .author-avatar-sm {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .author-name-sm {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dark);
    }
    .read-more-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }
    .read-more-link:hover {
        gap: 8px;
        color: var(--primary-dark);
    }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }
    .empty-state i {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 20px;
    }
    .empty-state h3 {
        color: var(--text-dark);
        margin-bottom: 10px;
        font-size: 1.8rem;
    }
    .empty-state p {
        color: var(--text-light);
        margin-bottom: 0;
        font-size: 1.1rem;
    }
    .pagination-container {
        margin-top: 40px;
    }
    .pagination {
        justify-content: center;
        gap: 5px;
    }
    .page-link {
        border: none;
        padding: 10px 18px;
        border-radius: 50px !important;
        color: var(--text-dark);
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #e9ecef;
    }
    .page-link:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }
    .page-item.active .page-link {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    @media (max-width: 992px) {
        .page-header h1 { font-size: 2.5rem; }
        .featured-post-grid { grid-template-columns: 1fr; }
        .featured-post-image { height: 300px; }
        .blog-grid { grid-template-columns: repeat(2, 1fr); }
        .blog-search { width: 100%; }
        .blog-header { flex-direction: column; align-items: flex-start; }
    }
    @media (max-width: 768px) {
        .page-header h1 { font-size: 2rem; }
        .blog-grid { grid-template-columns: 1fr; }
        .featured-title { font-size: 1.5rem; }
        .featured-post-content { padding: 25px; }
        .featured-footer { flex-direction: column; gap: 20px; align-items: flex-start; }
    }
</style>
@endpush