<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Karibu Parcels | All Pickup & Drop-off Points by County</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #008f40;
            --primary-dark: #007a36;
            --primary-light: #e8f5e9;
            --accent-color: #ff3519;
            --accent-dark: #e62e15;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--light-bg);
            padding-top: 80px;
        }

        /* Navigation */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: var(--shadow-sm);
        }
        .navbar-brand .brand-text span {
            color: var(--text-dark);
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 30px;
            transition: var(--transition);
            font-size: 0.95rem;
            color: var(--text-dark) !important;
        }
        .navbar-nav .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary-color) !important;
        }
        .navbar-nav .nav-link.active {
            background: var(--primary-color);
            color: white !important;
        }

        /* Hero Section */
        .stations-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .stations-hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        .stations-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }
        .stats-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 40px;
            padding: 8px 20px;
            display: inline-block;
            margin-top: 20px;
            font-weight: 500;
        }

        /* Main Container */
        .stations-main {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 1.5rem 3rem;
        }

        /* Search & Filter Bar */
        .filter-bar {
            background: white;
            border-radius: 60px;
            padding: 6px;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
        .search-wrapper {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        .search-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }
        .search-input-custom {
            width: 100%;
            padding: 14px 20px 14px 48px;
            border: none;
            border-radius: 50px;
            background: var(--light-bg);
            font-size: 0.95rem;
            outline: none;
            transition: var(--transition);
        }
        .search-input-custom:focus {
            background: white;
            box-shadow: 0 0 0 2px var(--primary-light);
        }
        .stats-info {
            padding: 0 20px;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .stats-info strong {
            color: var(--primary-color);
        }

        /* County Accordion Cards */
        .county-accordion-card {
            background: white;
            border-radius: 24px;
            margin-bottom: 1.25rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
            overflow: hidden;
        }
        .county-accordion-card:hover {
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        /* County Header - Clickable */
        .county-header {
            background: white;
            padding: 1.2rem 1.8rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }
        .county-header:hover {
            background: var(--primary-light);
        }
        .county-header.active {
            background: linear-gradient(135deg, var(--primary-light), white);
            border-bottom: 1px solid var(--border-color);
        }
        .county-title {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .county-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-light), #d4f0e0);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        .county-name {
            font-weight: 700;
            font-size: 1.35rem;
            margin: 0;
            color: var(--text-dark);
        }
        .points-count-badge {
            background: var(--primary-color);
            color: white;
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .chevron-icon {
            font-size: 1.3rem;
            color: var(--text-light);
            transition: transform 0.3s ease;
        }
        .county-header.active .chevron-icon {
            transform: rotate(90deg);
            color: var(--primary-color);
        }

        /* Points Panel */
        .points-panel {
            display: none;
            padding: 1.5rem 1.8rem;
            background: #fefefe;
            border-top: 1px solid var(--border-color);
        }
        .points-panel.show {
            display: block;
            animation: fadeSlide 0.3s ease-out;
        }
        @keyframes fadeSlide {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Station Cards Grid */
        .stations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .station-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            height: 100%;
        }
        .station-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .station-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 1rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .station-card-header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: white;
        }
        .station-type-badge {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: white;
        }

        .station-card-body {
            padding: 1.2rem;
        }
        .station-detail {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            color: var(--text-light);
            line-height: 1.4;
        }
        .station-detail i {
            width: 20px;
            color: var(--primary-color);
            margin-top: 2px;
            font-size: 0.9rem;
        }
        .station-detail strong {
            color: var(--text-dark);
            font-weight: 600;
        }
        .hours-badge {
            background: var(--primary-light);
            border-radius: 30px;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 24px;
            color: var(--text-light);
        }

        /* View All Button */
        .view-all-wrapper {
            text-align: center;
            margin-top: 2.5rem;
        }
        .btn-view-all {
            background: white;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            text-decoration: none;
            display: inline-block;
        }
        .btn-view-all:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,143,64,0.2);
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
            transition: var(--transition);
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
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            margin-right: 8px;
            transition: var(--transition);
            font-size: 1.1rem;
        }
        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }
        .business-solutions {
            background: linear-gradient(to right, var(--primary-light), white);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }
        .business-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            transition: var(--transition);
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
            box-shadow: 0 10px 20px rgba(0,143,64,0.2);
        }

        @media (max-width: 768px) {
            body { padding-top: 70px; }
            .stations-hero h1 { font-size: 2rem; }
            .stations-main { padding: 0 1rem 2rem; }
            .county-header { padding: 1rem 1.2rem; flex-wrap: wrap; gap: 10px; }
            .county-name { font-size: 1.1rem; }
            .points-panel { padding: 1rem; }
            .filter-bar { border-radius: 20px; flex-direction: column; gap: 10px; padding: 12px; }
            .stats-info { text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" style="border-radius: 10px;" onerror="this.src='https://placehold.co/45x45/008f40/white?text=KP'">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">{{ config('app.name', 'Karibu Parcels') }}</span>
                    <small class="d-block text-muted" style="font-size: 0.75rem;">Professional Courier Service</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#tracking">Tracking</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('points') }}">PickUp/DropOff Points</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#blogs">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#contact">Contact</a></li>
                </ul>
                <div class="d-none d-lg-block">
                    <a href="{{ url('/') }}#tracking" class="btn btn-outline-success btn-sm rounded-pill px-3">
                        <i class="bi bi-box-seam me-1"></i> Track
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="stations-hero">
        <div class="container">
            <h1><i class="bi bi-geo-alt-fill me-2"></i> PickUp & DropOff Points</h1>
            <p>Find our conveniently located stations across Kenya — click any county to explore pickup and drop-off locations</p>
            <div class="stats-badge">
                <i class="bi bi-building me-2"></i> {{ $counties->count() }} Counties • {{ $totalPoints ?? $counties->sum('points_count') }} Service Points • Nationwide Coverage
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="stations-main">
        <!-- Filter Bar with Search -->
        <div class="filter-bar">
            <div class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="text" id="countySearch" class="search-input-custom" placeholder="Search county by name...">
            </div>
            <div class="stats-info" id="statsInfo">
                <i class="bi bi-info-circle-fill me-1"></i> <span id="visibleCount">{{ $counties->count() }}</span> counties displayed
            </div>
        </div>

        <!-- Counties Accordion Container -->
        <div id="countiesContainer" class="counties-accordion">
            @if($counties->count() > 0)
                @foreach($counties as $county)
                <div class="county-accordion-card" data-county-name="{{ strtolower($county->name) }}">
                    <div class="county-header" data-target="county-{{ $county->id }}">
                        <div class="county-title">
                            <div class="county-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4 class="county-name">{{ $county->name }}</h4>
                            <span class="points-count-badge">{{ $county->points_count ?? $county->pickup_points->count() }} Point{{ ($county->points_count ?? $county->pickup_points->count()) != 1 ? 's' : '' }}</span>
                        </div>
                        <i class="bi bi-chevron-right chevron-icon"></i>
                    </div>
                    <div class="points-panel" id="county-{{ $county->id }}">
                        <div class="stations-grid">
                            @forelse($county->pickup_points as $point)
                            <div class="station-card">
                                <div class="station-card-header">
                                    <h5>{{ $point->name }}</h5>
                                    <div class="station-type-badge" title="{{ $point->type == 'both' ? 'Pickup & Dropoff' : ($point->type == 'pickup' ? 'Pickup Only' : 'Dropoff Only') }}">
                                        @if($point->type == 'both')
                                        <i class="bi bi-arrow-left-right"></i>
                                        @elseif($point->type == 'pickup')
                                        <i class="bi bi-arrow-up"></i>
                                        @else
                                        <i class="bi bi-arrow-down"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="station-card-body">
                                    <div class="station-detail">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        <span><strong>Address:</strong> {{ $point->address }}</span>
                                    </div>
                                    <div class="station-detail">
                                        <i class="bi bi-telephone-fill"></i>
                                        <span><strong>Phone:</strong> {{ $point->contact_phone_number }}</span>
                                    </div>
                                    @if($point->contact_email)
                                    <div class="station-detail">
                                        <i class="bi bi-envelope-fill"></i>
                                        <span><strong>Email:</strong> {{ $point->contact_email }}</span>
                                    </div>
                                    @endif
                                    <div class="hours-badge">
                                        <i class="bi bi-clock"></i> {{ $point->opening_hours }} - {{ $point->closing_hours }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="bi bi-info-circle fs-1"></i>
                                <p class="mt-2">No pickup points available in this county yet.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="bi bi-geo-alt fs-1"></i>
                    <h5 class="mt-3">No counties found</h5>
                    <p class="text-muted">No service points are currently available.</p>
                </div>
            @endif
        </div>

        <!-- View All Button -->
        <div class="view-all-wrapper">
            <a href="{{ url('/') }}" class="btn-view-all">
                <i class="bi bi-arrow-left me-2"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">
                        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="color:white;">
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
                        <li><a href="{{ url('/') }}#features">Town to Town Sending</a></li>
                        <li><a href="{{ url('/') }}#features">Parcel Receiving</a></li>
                        <li><a href="{{ url('/') }}#features">Forwarding Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                        <li><a href="{{ url('/') }}#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#faq">FAQs</a></li>
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
                                <span class="btn-text"><small>Visit our</small><strong>Marketplace</strong></span>
                                <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
                            </a>
                            <a href="{{ route('partners.login') }}" class="business-btn partner-btn">
                                <span class="btn-icon"><i class="bi bi-briefcase"></i></span>
                                <span class="btn-text"><small>Partner Portal</small><strong>Login</strong></span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // County search and accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('countySearch');
            const countyCards = document.querySelectorAll('.county-accordion-card');
            const visibleCountSpan = document.getElementById('visibleCount');

            // Function to filter counties
            function filterCounties() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                countyCards.forEach(card => {
                    const countyName = card.getAttribute('data-county-name');
                    if (countyName && countyName.includes(searchTerm)) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        // Close panel if it's open and being hidden
                        const header = card.querySelector('.county-header');
                        const panel = card.querySelector('.points-panel');
                        if (header && header.classList.contains('active')) {
                            header.classList.remove('active');
                            if (panel) panel.classList.remove('show');
                        }
                    }
                });

                if (visibleCountSpan) {
                    visibleCountSpan.textContent = visibleCount;
                }
            }

            // Add search event listener
            if (searchInput) {
                searchInput.addEventListener('input', filterCounties);
            }

            // Accordion toggle functionality
            const countyHeaders = document.querySelectorAll('.county-header');
            
            countyHeaders.forEach(header => {
                header.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const targetId = this.getAttribute('data-target');
                    const panel = document.getElementById(targetId);
                    const isActive = this.classList.contains('active');
                    
                    // Close all other open panels
                    countyHeaders.forEach(h => {
                        if (h !== header && h.classList.contains('active')) {
                            h.classList.remove('active');
                            const otherId = h.getAttribute('data-target');
                            const otherPanel = document.getElementById(otherId);
                            if (otherPanel) otherPanel.classList.remove('show');
                        }
                    });
                    
                    // Toggle current panel
                    if (!isActive) {
                        this.classList.add('active');
                        if (panel) panel.classList.add('show');
                    } else {
                        this.classList.remove('active');
                        if (panel) panel.classList.remove('show');
                    }
                });
            });

            // Optional: Auto-open first county if there are points
            if (countyHeaders.length > 0 && document.querySelectorAll('.station-card').length > 0) {
                // First county header click to show initial content (optional)
                // Uncomment if you want first county open by default
                // setTimeout(() => {
                //     countyHeaders[0].click();
                // }, 100);
            }
        });
    </script>
</body>
</html>