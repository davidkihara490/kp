<div class="blog-sidebar">
    <!-- About Widget -->
    <div class="sidebar-widget">
        <h5 class="widget-title">About Our Blog</h5>
        <p class="text-muted small">Stay updated with the latest news, tips, and insights from Karibu Parcels. We share valuable information about courier services, shipping tips, and industry updates.</p>
    </div>

    <!-- Popular Posts Widget -->
    @if(isset($popularPosts) && $popularPosts->count() > 0)
    <div class="sidebar-widget">
        <h5 class="widget-title">Popular Posts</h5>
        @foreach($popularPosts as $popular)
        <a href="{{ route('blog.show', $popular->slug ?? $popular->id) }}" class="popular-post-item">
            <div class="popular-post-image" style="background-image: url('{{ $popular->featured_image ? asset('storage/' . $popular->featured_image) : asset('logo.jpeg') }}')"></div>
            <div class="popular-post-content">
                <h6>{{ $popular->title }}</h6>
                <span class="date"><i class="bi bi-calendar3"></i> {{ $popular->created_at->format('M d, Y') }}</span>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    <!-- Categories Widget -->
    @if(isset($categories) && $categories->count() > 0)
    <div class="sidebar-widget">
        <h5 class="widget-title">Categories</h5>
        <ul class="categories-list">
            @foreach($categories as $category)
            <li class="category-item">
                <a href="{{ route('blogs.category', $category->slug) }}" class="category-link">
                    <span>{{ $category->name }}</span>
                    <span class="category-count">{{ $category->posts_count }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tags Widget -->
    @if(isset($tags) && $tags->count() > 0)
    <div class="sidebar-widget">
        <h5 class="widget-title">Popular Tags</h5>
        <div class="tags-cloud">
            @foreach($tags as $tag)
            <a href="{{ route('blogs.tag', $tag->slug) }}" class="tag-link">#{{ $tag->name }}</a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Newsletter Widget -->
    <div class="sidebar-widget newsletter-widget">
        <h5 class="widget-title">Newsletter</h5>
        <p class="small mb-3">Subscribe to get the latest updates</p>
        <form class="newsletter-form" id="sidebarNewsletter">
            <input type="email" class="newsletter-input" placeholder="Your email address" required>
            <button type="submit" class="newsletter-submit">
                <i class="bi bi-send me-2"></i>Subscribe
            </button>
        </form>
    </div>
</div>

<style>
    .blog-sidebar {
        position: sticky;
        top: 100px;
    }
    .sidebar-widget {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        border: 1px solid #e9ecef;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
    }
    .widget-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 12px;
    }
    .widget-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: var(--primary-color);
        border-radius: 2px;
    }
    .popular-post-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
        text-decoration: none;
        color: inherit;
    }
    .popular-post-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .popular-post-image {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
    }
    .popular-post-content h6 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 5px;
        line-height: 1.4;
        color: var(--text-dark);
    }
    .popular-post-content .date {
        font-size: 0.75rem;
        color: var(--text-light);
    }
    .popular-post-content .date i {
        color: var(--primary-color);
        margin-right: 3px;
    }
    .categories-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .category-item {
        margin-bottom: 12px;
    }
    .category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        color: var(--text-dark);
        text-decoration: none;
        transition: all 0.3s ease;
        border-bottom: 1px dashed #e9ecef;
    }
    .category-link:hover {
        color: var(--primary-color);
        padding-left: 8px;
    }
    .category-count {
        background: var(--light-bg);
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-light);
    }
    .tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .tag-link {
        background: var(--light-bg);
        color: var(--text-dark);
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .tag-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    .newsletter-widget {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        text-align: center;
    }
    .newsletter-widget .widget-title {
        color: white;
    }
    .newsletter-widget .widget-title::after {
        background: white;
        left: 50%;
        transform: translateX(-50%);
    }
    .newsletter-input {
        width: 100%;
        padding: 12px 15px;
        border: none;
        border-radius: 50px;
        margin-bottom: 10px;
    }
    .newsletter-input:focus {
        outline: none;
    }
    .newsletter-submit {
        width: 100%;
        background: var(--accent-color);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .newsletter-submit:hover {
        background: var(--accent-dark);
    }
</style>