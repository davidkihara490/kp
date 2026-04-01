{{-- resources/views/blogs/show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - Karibu Parcels Blog</title>
    <meta name="description" content="{{ Str::limit(strip_tags($post->content), 160) }}">
    <meta name="keywords" content="{{ $post->tags?->pluck('name')->implode(', ') }}">

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta property="og:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->created_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $post->author->name ?? 'Admin' }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($post->content), 200) }}">
    <meta name="twitter:image" content="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : asset('logo.jpeg') }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Syntax Highlighting for Code Blocks -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/atom-one-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>

    <style>
        :root {
            --primary-color: #008f40;
            --primary-dark: #007a36;
            --accent-color: #ff3519;
            --accent-dark: #e62e15;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --text-dark: #343a40;
            --text-light: #6c757d;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: var(--text-dark);
            background-color: #f8fafc;
            line-height: 1.7;
        }

        /* Navigation */
        .navbar {
            background: white !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 12px 0;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .logo-img {
            border-radius: 8px;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .navbar-nav .nav-link:hover {
            background: rgba(0, 143, 64, 0.05);
            color: var(--primary-color) !important;
        }

        .navbar-nav .nav-link.active {
            background: rgba(0, 143, 64, 0.1);
            color: var(--primary-color) !important;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 120px 0 100px;
            margin-top: 76px;
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

        .page-header .container {
            position: relative;
            z-index: 2;
        }

        .page-header h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header .lead {
            font-size: 1.2rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
        }

        .page-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin-top: 20px;
            justify-content: center;
        }

        .page-header .breadcrumb-item {
            color: rgba(255, 255, 255, 0.8);
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
            color: rgba(255, 255, 255, 0.5);
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
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }

        .post-meta-header-item i {
            font-size: 1.2rem;
        }

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

        /* Main Content */
        .blog-main {
            padding: 60px 0 80px;
        }

        .blog-content-wrapper {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        /* Featured Image */
        .featured-image-container {
            margin: -40px -40px 30px -40px;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            position: relative;
            height: 500px;
        }

        .featured-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .featured-image-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            color: white;
            padding: 20px;
            font-size: 0.9rem;
        }

        /* Author Box */
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

        .author-info h4 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-dark);
        }

        .author-role {
            color: var(--primary-color);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .author-bio {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-bottom: 0;
        }

        /* Post Content */
        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-dark);
        }

        .post-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin: 40px 0 20px;
            color: var(--text-dark);
        }

        .post-content h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 30px 0 15px;
            color: var(--text-dark);
        }

        .post-content h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 25px 0 15px;
            color: var(--text-dark);
        }

        .post-content p {
            margin-bottom: 1.5rem;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 30px 0;
        }

        .post-content blockquote {
            background: var(--light-bg);
            border-left: 4px solid var(--primary-color);
            padding: 20px 30px;
            margin: 30px 0;
            font-style: italic;
            font-size: 1.1rem;
            color: var(--text-dark);
            border-radius: 0 10px 10px 0;
        }

        .post-content blockquote p:last-child {
            margin-bottom: 0;
        }

        .post-content ul,
        .post-content ol {
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .post-content li {
            margin-bottom: 0.5rem;
        }

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

        .post-content pre code {
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .post-content table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }

        .post-content th {
            background: var(--primary-color);
            color: white;
            padding: 12px;
            font-weight: 600;
        }

        .post-content td {
            padding: 12px;
            border: 1px solid #e9ecef;
        }

        .post-content tr:nth-child(even) {
            background: var(--light-bg);
        }

        .post-content a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .post-content a:hover {
            text-decoration: underline;
        }

        /* Post Tags */
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

        /* Share Buttons */
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

        .share-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-right: 15px;
        }

        .share-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

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
        }

        .share-btn:hover {
            transform: translateY(-3px);
        }

        .share-btn.facebook {
            background: #1877f2;
        }

        .share-btn.twitter {
            background: #1da1f2;
        }

        .share-btn.whatsapp {
            background: #25d366;
        }

        .share-btn.linkedin {
            background: #0a66c2;
        }

        .share-btn.email {
            background: var(--text-dark);
        }

        .share-btn.copy-link {
            background: var(--primary-color);
        }

        /* Post Actions */
        .post-actions {
            display: flex;
            gap: 20px;
            margin: 30px 0;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border: 1px solid #e9ecef;
            border-radius: 50px;
            background: white;
            color: var(--text-dark);
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .action-btn.liked {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Comments Section */
        .comments-section {
            margin-top: 50px;
        }

        .comments-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-dark);
        }

        .comment-form {
            background: var(--light-bg);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
        }

        .comment-form h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

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
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
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

        .comment-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

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

        .comment-content {
            flex: 1;
        }

        .comment-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: 700;
            color: var(--text-dark);
        }

        .comment-date {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .comment-text {
            color: var(--text-light);
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .comment-reply {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* Related Posts */
        .related-posts {
            margin-top: 60px;
        }

        .related-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-dark);
            text-align: center;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            text-decoration: none;
            color: inherit;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 143, 64, 0.1);
            border-color: var(--primary-color);
        }

        .related-image {
            height: 180px;
            background-size: cover;
            background-position: center;
        }

        .related-content {
            padding: 20px;
        }

        .related-meta {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 10px;
        }

        .related-meta i {
            color: var(--primary-color);
            margin-right: 5px;
        }

        .related-card h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.4;
            color: var(--text-dark);
        }

        .related-card p {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Sidebar */
        .blog-sidebar {
            position: sticky;
            top: 100px;
        }

        .sidebar-widget {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
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

        /* Popular Posts */
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

        /* Categories */
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

        /* Tags Cloud */
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

        /* Newsletter Widget */
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

        .newsletter-form {
            margin-top: 20px;
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

        /* Table of Contents */
        .toc-widget {
            background: var(--light-bg);
        }

        .toc-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .toc-item {
            margin-bottom: 8px;
        }

        .toc-link {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: block;
            padding: 5px 0;
            border-bottom: 1px dashed #e9ecef;
        }

        .toc-link:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }

        .toc-link.h2 {
            font-weight: 600;
        }

        .toc-link.h3 {
            padding-left: 15px;
            font-size: 0.85rem;
        }

        .toc-link.h4 {
            padding-left: 30px;
            font-size: 0.8rem;
        }

        /* Progress Bar */
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

        /* Footer */
        footer {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0 20px;
            position: relative;
            margin-top: 40px;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 8px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            text-decoration: none;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
                /* Business Solutions Section */
        .business-solutions {
            background:  white;
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(0, 143, 64, 0.2);
            margin-top: 30px;
        }

        .business-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .marketplace-btn {
            background: var(--primary-color);
            color: white;
        }

        .partner-btn {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .business-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.2);
        }

        .business-btn .btn-text small {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        .business-btn .btn-text strong {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .business-solutions {
                padding: 20px;
            }
            
            .business-buttons {
                margin-top: 15px;
                justify-content: flex-start !important;
            }
        }



        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .page-header h1 {
                font-size: 2.5rem;
            }

            .featured-image-container {
                height: 400px;
            }

            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .page-header {
                padding: 100px 0 80px;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .featured-image-container {
                height: 300px;
                margin: -20px -20px 20px -20px;
            }

            .blog-content-wrapper {
                padding: 20px;
            }

            .post-meta-header {
                gap: 15px;
            }

            .author-box {
                flex-direction: column;
                text-align: center;
            }

            .share-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .related-grid {
                grid-template-columns: 1fr;
            }

            .comment-item {
                flex-direction: column;
            }

            .comment-avatar {
                margin: 0 auto;
            }

            .comment-header {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>
    <!-- Reading Progress Bar -->
    <div class="reading-progress" id="readingProgress"></div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                    <small class="d-block text-muted" style="font-size: 0.75rem; line-height: 1;">Professional Courier Service</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#tracking">Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#stations">PickUp/DropOff Points</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('blogs.index') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/#contact">Contact</a>
                    </li>
                </ul>

                <div class="d-none d-lg-block">
                    <a href="/#tracking" class="btn btn-outline-success btn-sm rounded-pill px-3">
                        <i class="bi bi-box-seam me-1"></i> Track
                    </a>
                </div>
            </div>
        </div>
    </nav>

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

            <nav aria-label="breadcrumb" class="animate-fade-up" style="animation-delay: 0.3s;">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 30) }}</li>
                </ol>
            </nav>
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
                        @if($post->featured_image)
                        <div class="featured-image-container">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="featured-image">
                            @if($post->featured_image_caption)
                            <div class="featured-image-caption">
                                {{ $post->featured_image_caption }}
                            </div>
                            @endif
                        </div>

                        @else

                        <div class="featured-image-container">
                            <img src="{{ asset('logo.jpeg') }}" alt="{{ $post->title }}" class="featured-image">
                            @if($post->featured_image_caption)
                            <div class="featured-image-caption">
                                {{ $post->featured_image_caption }}
                            </div>
                            @endif
                        </div>

                        @endif

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
                        @if ($post->tags)
                        @if($post->tags->count() > 0)
                        <div class="post-tags">
                            @foreach($post->tags as $tag)
                            <a href="{{ route('blogs.tag', $tag->slug) }}" class="post-tag">
                                #{{ $tag->name }}
                            </a>
                            @endforeach
                        </div>
                        @endif
                        @endif


                        <!-- Share Section -->
                        <div class="share-section">
                            <div class="d-flex align-items-center">
                                <span class="share-title">Share this article:</span>
                                <div class="share-buttons">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn facebook" title="Share on Facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-btn twitter" title="Share on Twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="share-btn whatsapp" title="Share on WhatsApp">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="share-btn linkedin" title="Share on LinkedIn">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode('Check out this article: ' . url()->current()) }}" class="share-btn email" title="Share via Email">
                                        <i class="bi bi-envelope"></i>
                                    </a>
                                    <button class="share-btn copy-link" onclick="copyToClipboard()" title="Copy link">
                                        <i class="bi bi-link"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Post Actions -->
                        <div class="post-actions">
                            <button class="action-btn" id="likeBtn" onclick="toggleLike()">
                                <i class="bi bi-heart"></i>
                                <span id="likeCount">{{ number_format($post->likes ?? 0) }}</span>
                            </button>
                            <button class="action-btn" onclick="window.print()">
                                <i class="bi bi-printer"></i> Print
                            </button>
                            <button class="action-btn" onclick="savePost()">
                                <i class="bi bi-bookmark"></i> Save
                            </button>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section">
                            <h3 class="comments-title">Comments ({{ $post->comments_count ?? 0 }})</h3>

                            <!-- Comment Form -->
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

                            <!-- Comments List -->
                            @if(isset($post->comments) && $post->comments->count() > 0)
                            @foreach($post->comments as $comment)
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    {{ substr($comment->author_name, 0, 1) }}
                                </div>
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $comment->author_name }}</span>
                                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="comment-text">{{ $comment->content }}</p>
                                    <a href="#" class="comment-reply" onclick="replyToComment('{{ $comment->author_name }}')">
                                        <i class="bi bi-reply me-1"></i>Reply
                                    </a>
                                </div>
                            </div>

                            <!-- Replies -->
                            @if($comment->replies->count() > 0)
                            @foreach($comment->replies as $reply)
                            <div class="comment-item" style="margin-left: 50px;">
                                <div class="comment-avatar" style="background: var(--primary-dark);">
                                    {{ substr($reply->author_name, 0, 1) }}
                                </div>
                                <div class="comment-content">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $reply->author_name }}</span>
                                        <span class="comment-date">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="comment-text">{{ $reply->content }}</p>
                                </div>
                            </div>
                            @endforeach
                            @endif
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

                        <!-- Author Info -->
                        <div class="sidebar-widget">
                            <h5 class="widget-title">About the Author</h5>
                            <div class="text-center">
                                <div class="author-avatar-large mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                    {{ substr($post->author->name ?? 'A', 0, 1) }}
                                </div>
                                <h6 class="fw-bold">{{ $post->author->name ?? 'Admin' }}</h6>
                                <p class="small text-muted">{{ $post->author->bio ?? 'Passionate about sharing knowledge and insights about courier services and logistics.' }}</p>
                                <div class="social-icons mt-2">
                                    <a href="#"><i class="bi bi-twitter"></i></a>
                                    <a href="#"><i class="bi bi-linkedin"></i></a>
                                    <a href="#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Popular Posts -->
                        @if(isset($popularPosts) && $popularPosts->count() > 0)
                        <div class="sidebar-widget">
                            <h5 class="widget-title">Popular Posts</h5>
                            @foreach($popularPosts as $popular)
                            <a href="{{ route('blog.show', $popular->slug ?? $popular->id) }}" class="popular-post-item">
                                <div class="popular-post-image" style="background-image: url('{{ $popular->featured_image ? asset('storage/' . $popular->featured_image) : 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80' }}')"></div>
                                <div class="popular-post-content">
                                    <h6>{{ $popular->title }}</h6>
                                    <span class="date"><i class="bi bi-calendar3"></i> {{ $popular->created_at->format('M d, Y') }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @endif

                        <!-- Categories -->
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

                        <!-- Tags -->
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

                        <!-- Newsletter -->
                        <div class="sidebar-widget newsletter-widget">
                            <h5 class="widget-title">Newsletter</h5>
                            <p class="small mb-3">Subscribe to get the latest updates</p>
                            <form class="newsletter-form">
                                <input type="email" class="newsletter-input" placeholder="Your email address" required>
                                <button type="submit" class="newsletter-submit">
                                    <i class="bi bi-send me-2"></i>Subscribe
                                </button>
                            </form>
                        </div>

                        <!-- Advertisement -->
                        <div class="sidebar-widget text-center">
                            <h5 class="widget-title">Advertise With Us</h5>
                            <div class="bg-light p-4 rounded" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
                                <i class="bi bi-megaphone" style="font-size: 2rem; color: var(--primary-color);"></i>
                                <h6 class="mt-2">Promote Your Business</h6>
                                <p class="small text-muted">Reach thousands of readers daily</p>
                                <a href="/contact" class="btn btn-sm btn-primary rounded-pill px-4">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">
                        <a class="navbar-brand d-flex align-items-center" href="/" style="color:white;">
                            <div class="logo-container me-2">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" style="border-radius: 10px;" onerror="this.src='https://placehold.co/45x45/008f40/white?text=KP'">
                            </div>
                            <div class="brand-text">
                                <span class="fw-bold fs-5">{{ config('app.name', 'Karibu Parcels') }}</span>
                                <small class="d-block text-muted" style="font-size: 0.75rem;">Professional Courier Service</small>
                            </div>
                        </a>
                    </h4>
                    <p class="opacity-75">Your trusted partner for fast, reliable, and secure courier services across Kenya.</p>
                    <div class="social-icons">
                        <a target="_blank" href="https://www.facebook.com/karibuparcels"><i class="bi bi-facebook"></i></a>
                        <a target="_blank" href="https://www.instagram.com/karibuparcels/"><i class="bi bi-instagram"></i></a>
                        <a target="_blank" href="https://www.tiktok.com/@karibuparcels"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="/#features">Town to Town Sending</a></li>
                        <li><a href="/#features">Parcel Receiving</a></li>
                        <li><a href="/#features">Forwarding Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                        <li><a href="/#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="/#faq">FAQs</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Legal</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="business-solutions mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="business-solutions-text">
                            <span class="badge bg-primary mb-2">BUSINESS SOLUTIONS</span>
                            <h4 class="mb-1">Grow Your Business With Karibu Parcels</h4>
                            <p class="text-muted">Explore our marketplace and partner programs designed for businesses</p>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="business-buttons d-flex gap-3 flex-wrap justify-content-lg-end">
                            <a href="{{ route('marketplace') }}" class="business-btn marketplace-btn">
                                <span class="btn-icon"><i class="bi bi-shop"></i></span>
                                <span class="btn-text"><small>Visit our</small><br>
                                <strong>Marketplace</strong></span>
                                <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
                            </a>
                            <a href="{{ route('partners.login') }}" class="business-btn partner-btn">
                                <span class="btn-icon"><i class="bi bi-briefcase"></i></span>
                                <span class="btn-text"><small>Partner Portal</small><br>
                                <strong>Login</strong></span>
                                <span class="btn-arrow"><i class="bi bi-box-arrow-in-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-shield-check me-1"></i>Secure & Reliable |
                        <i class="bi bi-truck ms-2 me-1"></i>40+ Counties |
                        <i class="bi bi-clock ms-2 me-1"></i>24/7 Support
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize syntax highlighting
            hljs.highlightAll();

            // Generate Table of Contents
            generateTOC();

            // Newsletter form
            $('.newsletter-form').on('submit', function(e) {
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
        });

        // Reading Progress Bar
        window.onscroll = function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            document.getElementById('readingProgress').style.width = scrolled + '%';
        };

        // Generate Table of Contents
        function generateTOC() {
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

                        var listItem = $('<li>', {
                            class: 'toc-item'
                        }).append(link);

                        toc.append(listItem);
                    });
                } else {
                    $('#toc-container').hide();
                }
            }
        }

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

        // Toggle like
        function toggleLike() {
            var btn = $('#likeBtn');
            var icon = btn.find('i');
            var count = $('#likeCount');

            if (btn.hasClass('liked')) {
                icon.removeClass('bi-heart-fill').addClass('bi-heart');
                btn.removeClass('liked');
                count.text(parseInt(count.text()) - 1);
            } else {
                icon.removeClass('bi-heart').addClass('bi-heart-fill');
                btn.addClass('liked');
                count.text(parseInt(count.text()) + 1);
            }
        }

        // Save post
        function savePost() {
            var btn = $(event.currentTarget);
            var icon = btn.find('i');

            if (icon.hasClass('bi-bookmark')) {
                icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                alert('Post saved to your bookmarks!');
            } else {
                icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                alert('Post removed from bookmarks!');
            }
        }

        // Reply to comment
        function replyToComment(authorName) {
            var commentForm = $('#commentForm');
            $('html, body').animate({
                scrollTop: commentForm.offset().top - 100
            }, 500);

            var textarea = commentForm.find('textarea');
            textarea.val('@' + authorName + ' ');
            textarea.focus();
        }

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
    </script>

    <!-- WhatsApp Button -->
    @php
    $phone = config('services.whatsapp.phone', '254700130759');
    $message = config('services.whatsapp.message', 'Hello, I need more information about your services');
    $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($message);
    @endphp

    <div class="whatsapp-wrapper" style="position: fixed; bottom: 30px; right: 30px; z-index: 1000;">
        <span class="whatsapp-tooltip" style="position: absolute; right: 70px; top: 15px; background: #333; color: white; padding: 8px 15px; border-radius: 20px; font-size: 14px; white-space: nowrap; opacity: 0; visibility: hidden; transition: all 0.3s ease;">Need help? Chat with us!</span>
        <a href="{{ $whatsappUrl }}" target="_blank" class="whatsapp-button" style="width: 60px; height: 60px; background: #25d366; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 2px 2px 10px rgba(0,0,0,0.3); transition: all 0.3s ease; animation: pulse 2s infinite; text-decoration: none;">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <style>
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }

        .whatsapp-wrapper:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
            right: 80px;
        }

        .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            right: -10px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 10px solid #333;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        @media (max-width: 768px) {
            .whatsapp-wrapper {
                bottom: 20px;
                right: 20px;
            }

            .whatsapp-button {
                width: 50px;
                height: 50px;
                font-size: 26px;
            }

            .whatsapp-tooltip {
                display: none;
            }
        }
    </style>
</body>

</html>