{{-- resources/views/blogs/index.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog - Karibu Parcels | Latest News & Updates</title>
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
            padding: 120px 0 80px;
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

        .page-header h1 {
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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

        /* Blog Section */
        .blog-section {
            padding: 60px 0 80px;
        }

        /* Blog Header with Search */
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
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
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

        /* Category Pills */
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
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.2);
        }

        /* Featured Post */
        .featured-post {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 50px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .featured-post:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 143, 64, 0.1);
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
            background: linear-gradient(to right, rgba(0, 0, 0, 0.3), transparent);
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
            box-shadow: 0 4px 10px rgba(255, 53, 25, 0.3);
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

        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 50px;
        }

        /* Blog Card */
        .blog-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 143, 64, 0.1);
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
            box-shadow: 0 4px 10px rgba(0, 143, 64, 0.3);
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

        /* Sidebar */
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
        }

        .popular-post-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .popular-post-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background-size: cover;
            background-position: center;
            flex-shrink: 0;
        }

        .popular-post-content {
            flex: 1;
        }

        .popular-post-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .popular-post-title a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .popular-post-title a:hover {
            color: var(--primary-color);
        }

        .popular-post-meta {
            font-size: 0.75rem;
            color: var(--text-light);
            display: flex;
            gap: 10px;
        }

        .popular-post-meta i {
            color: var(--primary-color);
            margin-right: 3px;
        }

        /* Categories List */
        .categories-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list-item {
            margin-bottom: 12px;
        }

        .category-list-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px dashed #e9ecef;
        }

        .category-list-link:hover {
            color: var(--primary-color);
            padding-left: 8px;
        }

        .category-list-count {
            background: var(--light-bg);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-light);
        }

        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tag-item {
            background: var(--light-bg);
            color: var(--text-dark);
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .tag-item:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
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

        .newsletter-input-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .newsletter-input {
            padding: 12px 15px;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
        }

        .newsletter-input:focus {
            outline: none;
        }

        .newsletter-submit {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .newsletter-submit:hover {
            background: var(--accent-dark);
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            gap: 5px;
            margin-top: 40px;
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

        .page-item.disabled .page-link {
            color: var(--text-light);
            pointer-events: none;
            background: #f8f9fa;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
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

        /* Footer */
        footer {
            background: var(--dark-bg);
            color: white;
            padding: 50px 0 20px;
            position: relative;
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
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
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
        @media (max-width: 1200px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .featured-post-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-post-image {
                height: 300px;
            }
            
            .blog-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .blog-search {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2.2rem;
            }
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .featured-title {
                font-size: 1.5rem;
            }
            
            .featured-post-content {
                padding: 25px;
            }
            
            .featured-footer {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
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
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="animate-fade-up">Our Blog</h1>
                    <p class="lead mb-4 animate-fade-up" style="animation-delay: 0.1s;">Stay updated with the latest news, tips, and insights from Karibu Parcels</p>
                    <nav aria-label="breadcrumb" class="animate-fade-up" style="animation-delay: 0.2s;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Blog</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-section">
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

                <!-- Featured Post (Only show on first page) -->
                @if(isset($featuredPost) && $featuredPost && $blogPosts->currentPage() == 1)
                <div class="featured-post animate-fade-up">
                    <div class="featured-post-grid">
                        <div class="featured-post-image" style="background-image: url('{{ $featuredPost->featured_image ? asset('storage/' . $featuredPost->featured_image) : 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80' }}')">
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
                        <!-- Blog Grid -->
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
                                <div class="popular-post-item">
                                    <div class="popular-post-image" style="background-image: url('{{ $popular->featured_image ? asset('storage/' . $popular->featured_image) : 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80' }}')"></div>
                                    <div class="popular-post-content">
                                        <h6 class="popular-post-title">
                                            <a href="{{ route('blog.show', $popular->slug ?? $popular->id) }}">{{ $popular->title }}</a>
                                        </h6>
                                        <div class="popular-post-meta">
                                            <span><i class="bi bi-calendar3"></i> {{ $popular->created_at->format('M d, Y') }}</span>
                                            <span><i class="bi bi-eye"></i> {{ number_format($popular->views ?? 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- Categories Widget -->
                            @if(isset($categories) && $categories->count() > 0)
                            <div class="sidebar-widget">
                                <h5 class="widget-title">Categories</h5>
                                <ul class="categories-list">
                                    @foreach($categories as $category)
                                    <li class="category-list-item">
                                        <a href="{{ route('blogs.category', $category->slug) }}" class="category-list-link">
                                            <span>{{ $category->name }}</span>
                                            <span class="category-list-count">{{ $category->posts_count }}</span>
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
                                    <a href="{{ route('blogs.tag', $tag->slug) }}" class="tag-item">#{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Newsletter Widget -->
                            <div class="sidebar-widget newsletter-widget">
                                <h5 class="widget-title">Newsletter</h5>
                                <p class="small mb-3">Subscribe to get the latest updates</p>
                                <form class="newsletter-form">
                                    <div class="newsletter-input-group">
                                        <input type="email" class="newsletter-input" placeholder="Your email address" required>
                                        <button type="submit" class="newsletter-submit">
                                            <i class="bi bi-send me-2"></i>Subscribe
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
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

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">
                        <a class="navbar-brand d-flex align-items-center" href="/">
                            <div class="logo-container me-2">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                            </div>
                            <div class="brand-text">
                                <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                                <small class="d-block text-muted" style="font-size: 0.75rem; line-height: 1;">Professional Courier Service</small>
                            </div>
                        </a>
                    </h4>
                    <p class="opacity-75">Your trusted partner for fast, reliable, and secure courier services across Kenya.</p>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="/#features">Express Delivery</a></li>
                        <li><a href="/#features">Standard Delivery</a></li>
                        <li><a href="/#features">Bulk Shipping</a></li>
                        <li><a href="/#features">International</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="/#contact">Support</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="/#tracking">Track Package</a></li>
                        <li><a href="/#stations">Find Station</a></li>
                        <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                        <li><a href="/#faq">Help Center</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Legal</h5>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                    </ul>
                </div>
            </div>

            <hr class="mt-4 mb-4">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Secure & Reliable |
                        <i class="bi bi-truck ms-2 me-1"></i>
                        40+ Counties |
                        <i class="bi bi-clock ms-2 me-1"></i>
                        24/7 Support
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Newsletter form submission
            $('.newsletter-form').on('submit', function(e) {
                e.preventDefault();
                alert('Thank you for subscribing to our newsletter!');
                this.reset();
            });

            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
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
            0% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(37, 211, 102, 0); }
            100% { box-shadow: 0 0 0 0 rgba(37, 211, 102, 0); }
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
            .whatsapp-wrapper { bottom: 20px; right: 20px; }
            .whatsapp-button { width: 50px; height: 50px; font-size: 26px; }
            .whatsapp-tooltip { display: none; }
        }
    </style>
</body>

</html>