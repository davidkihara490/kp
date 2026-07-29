@if(isset($blogPosts) && $blogPosts->count() > 0)
<section id="blogs" class="blog-section">
    <div class="container">
        <div class="section-title">
            <h2>Latest from Our Blog</h2>
            <p class="text-muted">Stay updated with courier tips, news, and announcements</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($blogPosts->take(4) as $post)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="blog-card h-100">
                    <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="text-decoration-none">
                        @if($post->featured_image)
                        <div class="blog-image" style="background-image: url('{{ asset('storage/' . $post->featured_image) }}'); background-size: cover; background-position: center;">
                        </div>
                        @else
                        <div class="blog-image d-flex align-items-center justify-content-center">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        @endif
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="blog-category">{{ $post->category->name ?? 'General' }}</span>
                                <span class="blog-date"><i class="bi bi-calendar3"></i> {{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <h5 class="blog-title">{{ $post->title }}</h5>
                            <p class="blog-excerpt">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="blog-footer">
                                <span class="blog-author"><i class="bi bi-person"></i> {{ $post->author->name ?? 'Admin' }}</span>
                                <span class="blog-read-time"><i class="bi bi-clock"></i> {{ $post->read_time ?? '5' }} min read</span>
                            </div>
                            <span class="btn btn-link p-0 mt-3 small text-primary">
                                Read More <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- View More Button -->
        <div class="text-center mt-5 pt-3">
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill">
                <i class="bi bi-journal-text me-2"></i> View All Blog Posts
                <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif