@extends('layouts.app')

@section('title', $post->title . ' | Karibu Parcels Blog')

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
    <meta name="keywords" content="{{ $post->tags?->pluck('name')->implode(', ') }}">
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta property="og:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name ?? 'Admin' }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta name="twitter:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}">
@endsection

@section('reading-progress')
    <div class="reading-progress" id="readingProgress"></div>
@endsection

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container text-center">
            <span class="post-category-badge animate-fade-up">{{ $post->category->name ?? 'General' }}</span>
            <h1 class="animate-fade-up" style="animation-delay: 0.1s;">{{ $post->title }}</h1>

            <div class="post-meta-header animate-fade-up" style="animation-delay: 0.2s;">
                <span class="post-meta-header-item">
                    <i class="bi bi-person-circle"></i>
                    {{ $post->author->name ?? 'Admin' }}
                </span>
                <span class="post-meta-header-item">
                    <i class="bi bi-calendar3"></i>
                    {{ $post->created_at->format('F d, Y') }}
                </span>
                <span class="post-meta-header-item">
                    <i class="bi bi-clock"></i>
                    {{ $post->read_time ?? '5' }} min read
                </span>
                <span class="post-meta-header-item">
                    <i class="bi bi-eye"></i>
                    {{ number_format($post->views ?? 0) }} views
                </span>
            </div>

            @include('layouts.partials.breadcrumb', [
                'breadcrumbs' => [
                    ['label' => 'Home', 'url' => url('/')],
                    ['label' => 'Blog', 'url' => route('blogs.index')],
                    ['label' => Str::limit($post->title, 30), 'url' => route('blog.show', $post->slug ?? $post->id)]
                ]
            ])
        </div>
    </section>

    <!-- Main Content -->
    <section class="blog-main">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <div class="blog-content-wrapper animate-fade-up">
                        <!-- Featured Image -->
                        <div class="featured-image-container">
                            <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}" alt="{{ $post->title }}" class="featured-image">
                            @if($post->featured_image_caption)
                            <div class="featured-image-caption">{{ $post->featured_image_caption }}</div>
                            @endif
                        </div>

                        <!-- Author Box -->
                        <div class="author-box">
                            <div class="author-avatar-large">
                                {{ substr($post->author->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="author-info">
                                <h4>{{ $post->author->name ?? 'Admin' }}</h4>
                                <span class="author-role">{{ $post->author->role ?? 'Author' }}</span>
                                <p class="author-bio">{{ $post->author->bio ?? 'Passionate about sharing knowledge and insights about courier services and logistics.' }}</p>
                            </div>
                        </div>

                        <!-- Post Content -->
                        <div class="post-content">
                            {!! $post->content !!}
                        </div>

                        <!-- Post Tags -->
                        @if($post->tags && $post->tags->count() > 0)
                        <div class="post-tags">
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blogs.tag', $tag->slug) }}" class="post-tag">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                        @endif

                        <!-- Share Section -->
                        <div class="share-section">
                            <div class="d-flex align-items-center">
                                <span class="share-title">Share this article:</span>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn facebook"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter"><i class="fab fa-twitter"></i></a>
                                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="share-btn whatsapp"><i class="fab fa-whatsapp"></i></a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="share-btn linkedin"><i class="fab fa-linkedin-in"></i></a>
                                    <button class="share-btn copy-link" onclick="copyToClipboard()"><i class="bi bi-link"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section">
                            <h3 class="comments-title">Comments ({{ $post->comments_count ?? 0 }})</h3>

                            <div class="comment-form">
                                <h4>Leave a Comment</h4>
                                <form id="commentForm">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <input type="text" class="comment-input" placeholder="Your Name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <input type="email" class="comment-input" placeholder="Your Email" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="comment-input" rows="4" placeholder="Your Comment" required></textarea>
                                    </div>
                                    <button type="submit" class="comment-submit">
                                        <i class="bi bi-send me-2"></i>Post Comment
                                    </button>
                                </form>
                            </div>

                            @if(isset($post->comments) && $post->comments->count() > 0)
                            @foreach($post->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">{{ substr($comment->author_name, 0, 1) }}</div>
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $comment->author_name }}</span>
                                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="comment-text">{{ $comment->content }}</p>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted text-center py-4">Be the first to comment on this article!</p>
                            @endif
                        </div>
                    </div>

                    <!-- Related Posts -->
                    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
                    <div class="related-posts">
                        <h3 class="related-title">Related Articles</h3>
                        <div class="related-grid">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug ?? $related->id) }}" class="related-card">
                                <div class="related-image" style="background-image: url('{{ $related->featured_image ? asset('storage/' . $related->featured_image) : asset('logo.jpeg') }}')"></div>
                                <div class="related-content">
                                    <div class="related-meta">
                                        <span><i class="bi bi-calendar3"></i> {{ $related->created_at->format('M d, Y') }}</span>
                                        <span><i class="bi bi-clock"></i> {{ $related->read_time ?? '5' }} min</span>
                                    </div>
                                    <h4>{{ $related->title }}</h4>
                                    <p>{{ Str::limit(strip_tags($related->content), 80) }}</p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="blog-sidebar">
                        <!-- Table of Contents -->
                        @if($post->toc_enabled ?? true)
                        <div class="sidebar-widget toc-widget">
                            <h5 class="widget-title">Table of Contents</h5>
                            <div id="toc-container">
                                <ul class="toc-list" id="toc-list"></ul>
                            </div>
                        </div>
                        @endif

                        @include('blogs.partials.sidebar')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .reading-progress {
        position: fixed;
        top: 76px;
        left: 0;
        width: 0;
        height: 4px;
        background: var(--accent-color);
        z-index: 999;
        transition: width 0.1s ease;
    }
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
    .page-header .container { position: relative; z-index: 2; }
    .page-header h1 { font-size: 3.2rem; font-weight: 800; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.2); }
    .page-header .lead { font-size: 1.2rem; opacity: 0.95; max-width: 700px; margin: 0 auto; }
    .post-category-badge {
        background: var(--accent-color);
        color: white;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-bottom: 20px;
    }
    .post-meta-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
        flex-wrap: wrap;
    }
    .post-meta-header-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.9);
        font-size: 1rem;
    }
    .post-meta-header-item i { font-size: 1.2rem; }
    .page-header .breadcrumb {
        background: transparent;
        padding: 0;
        margin-top: 20px;
        justify-content: center;
    }
    .page-header .breadcrumb-item { color: rgba(255,255,255,0.8); }
    .page-header .breadcrumb-item a { color: white; text-decoration: none; }
    .page-header .breadcrumb-item a:hover { text-decoration: underline; }
    .page-header .breadcrumb-item.active { color: white; font-weight: 500; }
    .blog-main { padding: 60px 0 80px; }
    .blog-content-wrapper {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
    }
    .featured-image-container {
        margin: -40px -40px 30px -40px;
        border-radius: 20px 20px 0 0;
        overflow: hidden;
        position: relative;
        height: 500px;
    }
    .featured-image { width: 100%; height: 100%; object-fit: cover; }
    .featured-image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        color: white;
        padding: 20px;
        font-size: 0.9rem;
    }
    .author-box {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 25px;
        background: var(--light-bg);
        border-radius: 15px;
        margin-bottom: 30px;
    }
    .author-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        font-weight: 600;
        flex-shrink: 0;
    }
    .author-info h4 { font-size: 1.3rem; font-weight: 700; margin-bottom: 5px; }
    .author-role { color: var(--primary-color); font-size: 0.9rem; font-weight: 600; display: block; margin-bottom: 8px; }
    .author-bio { color: var(--text-light); font-size: 0.95rem; margin-bottom: 0; }
    .post-content { font-size: 1.1rem; line-height: 1.8; color: var(--text-dark); }
    .post-content h2 { font-size: 2rem; font-weight: 700; margin: 40px 0 20px; }
    .post-content h3 { font-size: 1.5rem; font-weight: 700; margin: 30px 0 15px; }
    .post-content h4 { font-size: 1.2rem; font-weight: 700; margin: 25px 0 15px; }
    .post-content p { margin-bottom: 1.5rem; }
    .post-content img { max-width: 100%; height: auto; border-radius: 10px; margin: 30px 0; }
    .post-content blockquote {
        background: var(--light-bg);
        border-left: 4px solid var(--primary-color);
        padding: 20px 30px;
        margin: 30px 0;
        font-style: italic;
        font-size: 1.1rem;
        border-radius: 0 10px 10px 0;
    }
    .post-content ul, .post-content ol { margin-bottom: 1.5rem; padding-left: 1.5rem; }
    .post-content li { margin-bottom: 0.5rem; }
    .post-content pre {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 20px;
        border-radius: 10px;
        overflow-x: auto;
        margin: 30px 0;
    }
    .post-content code {
        background: #f0f0f0;
        color: var(--accent-color);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .post-content pre code { background: transparent; color: inherit; padding: 0; }
    .post-content a { color: var(--primary-color); text-decoration: none; font-weight: 500; }
    .post-content a:hover { text-decoration: underline; }
    .post-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 40px 0 30px;
        padding-top: 30px;
        border-top: 1px solid #e9ecef;
    }
    .post-tag {
        background: var(--light-bg);
        color: var(--text-dark);
        padding: 8px 18px;
        border-radius: 30px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .post-tag:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }
    .share-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        padding: 30px 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin: 30px 0;
    }
    .share-title { font-size: 1.1rem; font-weight: 600; margin-right: 15px; }
    .share-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        border: none;
        cursor: pointer;
    }
    .share-btn:hover { transform: translateY(-3px); }
    .share-btn.facebook { background: #1877f2; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.linkedin { background: #0a66c2; }
    .share-btn.copy-link { background: var(--primary-color); }
    .comments-section { margin-top: 50px; }
    .comments-title { font-size: 1.8rem; font-weight: 700; margin-bottom: 30px; }
    .comment-form {
        background: var(--light-bg);
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 40px;
    }
    .comment-form h4 { font-size: 1.3rem; font-weight: 600; margin-bottom: 20px; }
    .comment-input {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 15px;
        width: 100%;
        transition: all 0.3s ease;
    }
    .comment-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0,143,64,0.1);
    }
    .comment-submit {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .comment-submit:hover { background: var(--primary-dark); transform: translateY(-2px); }
    .comment-item {
        display: flex;
        gap: 20px;
        padding: 25px;
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }
    .comment-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
        flex-shrink: 0;
    }
    .comment-content { flex: 1; }
    .comment-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .comment-author { font-weight: 700; color: var(--text-dark); }
    .comment-date { font-size: 0.85rem; color: var(--text-light); }
    .comment-text { color: var(--text-light); margin-bottom: 10px; line-height: 1.6; }
    .related-posts { margin-top: 60px; }
    .related-title { font-size: 2rem; font-weight: 700; margin-bottom: 30px; text-align: center; }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
    .related-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        text-decoration: none;
        color: inherit;
    }
    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,143,64,0.1);
        border-color: var(--primary-color);
    }
    .related-image { height: 180px; background-size: cover; background-position: center; }
    .related-content { padding: 20px; }
    .related-meta {
        display: flex;
        gap: 15px;
        font-size: 0.8rem;
        color: var(--text-light);
        margin-bottom: 10px;
    }
    .related-meta i { color: var(--primary-color); margin-right: 5px; }
    .related-card h4 { font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; line-height: 1.4; }
    .related-card p { color: var(--text-light); font-size: 0.85rem; margin-bottom: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .toc-widget { background: var(--light-bg); }
    .toc-list { list-style: none; padding: 0; margin: 0; }
    .toc-item { margin-bottom: 8px; }
    .toc-link {
        color: var(--text-dark);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: block;
        padding: 5px 0;
        border-bottom: 1px dashed #e9ecef;
    }
    .toc-link:hover { color: var(--primary-color); padding-left: 5px; }
    .toc-link.h2 { font-weight: 600; }
    .toc-link.h3 { padding-left: 15px; font-size: 0.85rem; }
    .toc-link.h4 { padding-left: 30px; font-size: 0.8rem; }
    @media (max-width: 992px) {
        .page-header h1 { font-size: 2.5rem; }
        .featured-image-container { height: 400px; }
        .related-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .page-header { padding: 100px 0 80px; }
        .page-header h1 { font-size: 2rem; }
        .featured-image-container { height: 300px; margin: -20px -20px 20px -20px; }
        .blog-content-wrapper { padding: 20px; }
        .post-meta-header { gap: 15px; }
        .author-box { flex-direction: column; text-align: center; }
        .share-section { flex-direction: column; align-items: flex-start; }
        .related-grid { grid-template-columns: 1fr; }
        .comment-item { flex-direction: column; }
        .comment-avatar { margin: 0 auto; }
        .comment-header { flex-direction: column; gap: 5px; }
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Reading Progress Bar
    $(window).on('scroll', function() {
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = (winScroll / height) * 100;
        $('#readingProgress').css('width', scrolled + '%');
    });

    // Generate Table of Contents
    var toc = $('#toc-list');
    if (toc.length) {
        var headings = $('.post-content h2, .post-content h3, .post-content h4');
        if (headings.length > 0) {
            headings.each(function(index) {
                var heading = $(this);
                var id = 'heading-' + index;
                heading.attr('id', id);

                var level = heading.prop('tagName').toLowerCase();
                var text = heading.text();
                var link = $('<a>', {
                    class: 'toc-link ' + level,
                    href: '#' + id,
                    text: text
                });

                $('<li>', { class: 'toc-item' }).append(link).appendTo(toc);
            });
        } else {
            $('#toc-container').hide();
        }
    }

    // Newsletter form
    $('#sidebarNewsletter').on('submit', function(e) {
        e.preventDefault();
        alert('Thank you for subscribing to our newsletter!');
        this.reset();
    });

    // Comment form
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();
        alert('Thank you for your comment! It will be reviewed before publishing.');
        this.reset();
    });

    // Smooth scroll for TOC links
    $(document).on('click', '.toc-link', function(e) {
        e.preventDefault();
        var target = $(this.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
});

// Copy to clipboard
function copyToClipboard() {
    var dummy = document.createElement('input');
    dummy.value = window.location.href;
    document.body.appendChild(dummy);
    dummy.select();
    document.execCommand('copy');
    document.body.removeChild(dummy);
    alert('Link copied to clipboard!');
}
</script>
@endpush