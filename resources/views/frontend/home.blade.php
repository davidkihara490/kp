<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karibu Parcels - Professional Courier Service</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/f2.css') }}">
    <script src="https://cdn.tiny.cloud/1/3culyhhybbcchz5f5d6o066dedtcc2ugjb92n22l8ocyw9rv/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                    <small class="d-block text-muted" style="font-size: 0.75rem; line-height: 1;">Professional Courier
                        Service</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tracking">Tracking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stations">PickUp/DropOff Points</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#blogs">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>

                <div class="d-none d-lg-block">
                    <a href="#tracking" class="btn btn-outline-success btn-sm rounded-pill px-3">
                        <i class="bi bi-box-seam me-1"></i> Track
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Tabbed Booking Engine -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="hero-content animate-fade-up">
                <h1>Fast & Reliable Courier Services Across Kenya</h1>
                <p class="lead">We deliver your packages with speed, security, and professionalism. Trusted by
                    businesses and individuals nationwide.</p>
            </div>

            <div class="compact-booking-engine animate-fade-up" style="animation-delay: 0.2s;">
                <!-- Booking Tabs -->
                <ul class="nav nav-tabs booking-tabs" id="bookingTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="town-tab" data-bs-toggle="tab" data-bs-target="#town" type="button" role="tab" aria-controls="town" aria-selected="true">
                            <i class="bi bi-building me-2"></i> Town to Town
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="international-tab" data-bs-toggle="tab" data-bs-target="#international" type="button" role="tab" aria-controls="international" aria-selected="false">
                            <i class="bi bi-globe me-2"></i> International
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <!-- Town to Town Tab -->
                <div class="tab-pane fade show active" id="town" role="tabpanel" aria-labelledby="town-tab">
                    <div class="booking-header">
                        <h6 class="text-muted mb-3">Get an instant quote for shipments between Kenyan towns</h6>
                    </div>

                    <form id="townQuoteForm">
                        <div class="booking-form-grid">
                            <!-- From Town with Search -->
                            <div class="booking-field">
                                <label><i class="bi bi-geo-alt-fill me-1 text-primary"></i> From Town</label>
                                <div class="searchable-select-container">
                                    <div class="searchable-select" id="fromTownSelect">
                                        <div class="searchable-select-display" data-target="fromTown">
                                            <span class="selected-text">Select pickup town</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div class="searchable-select-dropdown" id="fromTownDropdown">
                                            <div class="search-box">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="search-input" placeholder="Search towns..." id="fromTownSearch">
                                            </div>
                                            <div class="options-list" id="fromTownOptions">
                                                @foreach($towns as $town)
                                                <div class="option-item" data-value="{{ $town->name }}">
                                                    <i class="bi bi-building me-2"></i>
                                                    <span class="town-name">{{ $town->subCounty?->county?->name }}-{{ $town->name }}</span>
                                                    <small class="town-county text-muted">{{ $town->subCounty?->county?->name ?? 'Kenya' }}</small>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="fromTown" name="fromTown" required>
                                </div>
                            </div>

                            <!-- To Town with Search -->
                            <div class="booking-field">
                                <label><i class="bi bi-geo-alt-fill me-1 text-danger"></i> To Town</label>
                                <div class="searchable-select-container">
                                    <div class="searchable-select" id="toTownSelect">
                                        <div class="searchable-select-display" data-target="toTown">
                                            <span class="selected-text">Select delivery town</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div class="searchable-select-dropdown" id="toTownDropdown">
                                            <div class="search-box">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="search-input" placeholder="Search towns..." id="toTownSearch">
                                            </div>
                                            <div class="options-list" id="toTownOptions">
                                                @foreach($towns as $town)
                                                <div class="option-item" data-value="{{ $town->name }}">
                                                    <i class="bi bi-building me-2"></i>
                                                    <span class="town-name">{{ $town->subCounty?->county?->name }}-{{ $town->name }}</span>
                                                    <small class="town-county text-muted">{{ $town->subCounty?->county?->name ?? 'Kenya' }}</small>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="toTown" name="toTown" required>
                                </div>
                            </div>

                            <!-- Weight -->
                            <div class="booking-field">
                                <label><i class="bi bi-weight me-1"></i> Weight (kg)</label>
                                <div class="weight-input-group options-list">
                                    <input type="number" class="form-control compact-select" id="weight" placeholder="0.0" min="0.1" step="0.1" required>
                                    <span class="weight-unit">kg</span>
                                </div>
                            </div>

                            <!-- Parcel Type -->
                            <div class="booking-field">
                                <label><i class="bi bi-box me-1"></i> Parcel Type</label>
                                <div class="parcel-type-selector">
                                    <select class="form-select compact-select" id="parcelType" required>
                                        @foreach($itemCategories as $itemCategory)
                                        <option value="{{ $itemCategory->id }}">{{ $itemCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="booking-action">
                            <button type="submit" class="btn btn-primary quote-btn">
                                <i class="bi bi-calculator me-2"></i> Get Quote
                            </button>
                            <button type="button" class="btn btn-outline-secondary clear-btn" id="clearForm">
                                <i class="bi bi-arrow-clockwise me-2"></i> Clear
                            </button>
                        </div>
                    </form>
                    <div id="quoteResult" class="compact-quote-result"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Service Hours</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">40+</div>
                        <div class="stat-label">Counties</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">99%</div>
                        <div class="stat-label">On-time Delivery</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Packages Delivered</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Premium Services</h2>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h4>Town to Town Parcel Sending</h4>
                        <p>We provide our customers with a network of stations where they can deposit their parcels.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-box2-heart"></i>
                        </div>
                        <h4>Town to Town Parcels Receiving</h4>
                        <p>We provide our customers with a network of stations where they can pick up their parcels.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <h4>Parcel Forwarding Service</h4>
                        <p>This product saves time by connecting two courier services. The Sender sends the parcel to us, and then we forward it to the receiver's courier at a fee.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tracking Section - Improved Alignment -->
    <section id="tracking" class="tracking-section">
        <div class="container">
            <div class="section-title">
                <h2>Track Your Package</h2>
                <p class="text-muted">Enter your phone number and tracking ID to track your package</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="tracking-card">
                        <h3 class="mb-4"><i class="bi bi-box-seam me-2"></i> Track Your Shipment</h3>
                        <p class="mb-4 opacity-75">Real-time tracking for complete peace of mind</p>

                        <div class="tracking-form-wrapper">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="text-white small fw-bold mb-2">Phone Number</label>
                                    <div class="tracking-input-group">
                                        <i class="bi bi-telephone text-muted me-2"></i>
                                        <input type="tel" class="tracking-input" id="senderPhone"
                                            placeholder="0712345678">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label class="text-white small fw-bold mb-2">Tracking ID</label>
                                    <div class="tracking-input-group">
                                        <i class="bi bi-upc-scan text-muted me-2"></i>
                                        <input type="text" class="tracking-input" id="trackingId"
                                            placeholder="KP78945">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="tracking-btn w-100" id="trackPackage">
                                        <i class="bi bi-search me-2"></i>Track
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="small opacity-75 mb-0 d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                <i class="bi bi-info-circle"></i>
                                Try sample:
                                <button class="btn btn-sm btn-outline-light tracking-example"
                                    data-phone="0712345678" data-id="KP78945">0712345678 / KP78945</button>
                                <button class="btn btn-sm btn-outline-light tracking-example"
                                    data-phone="0723456789" data-id="KP12367">0723456789 / KP12367</button>
                            </p>
                        </div>

                        <div id="trackingResult" class="mt-4" style="display: none;">
                            <!-- Tracking result will appear here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trusted By Section - Modern & Professional -->
    <section class="trusted-by-section">
        <div class="container">
            <div class="section-title">
                <h2>Trusted By</h2>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <div class="trusted-description">
                        <p class="lead text-center">
                            Karibu Parcels serves a diverse range of customers who trust us with their delivery needs
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="trusted-card">
                        <div class="trusted-card-inner">
                            <div class="trusted-icon-wrapper">
                                <i class="bi bi-cart3"></i>
                                <div class="trusted-icon-bg"></div>
                            </div>
                            <h4>Online Sellers</h4>
                            <p>Sending parcels to buyers in other towns</p>
                            <div class="trusted-stats">
                                <span class="stat-badge">2,500+ sellers</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="trusted-card">
                        <div class="trusted-card-inner">
                            <div class="trusted-icon-wrapper">
                                <i class="bi bi-heart"></i>
                                <div class="trusted-icon-bg"></div>
                            </div>
                            <h4>Relatives & Families</h4>
                            <p>Sending parcels to loved ones across Kenya</p>
                            <div class="trusted-stats">
                                <span class="stat-badge">10,000+ families</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="trusted-card">
                        <div class="trusted-card-inner">
                            <div class="trusted-icon-wrapper">
                                <i class="bi bi-building"></i>
                                <div class="trusted-icon-bg"></div>
                            </div>
                            <h4>Institutions</h4>
                            <p>Sending documents to multiple recipients</p>
                            <div class="trusted-stats">
                                <span class="stat-badge">500+ institutions</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="trusted-card">
                        <div class="trusted-card-inner">
                            <div class="trusted-icon-wrapper">
                                <i class="bi bi-boxes"></i>
                                <div class="trusted-icon-bg"></div>
                            </div>
                            <h4>Wholesale Companies</h4>
                            <p>Sending products to their customers</p>
                            <div class="trusted-stats">
                                <span class="stat-badge">1,200+ companies</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="row mt-5 pt-4">
                <div class="col-12">
                    <div class="trust-indicators">
                        <div class="trust-indicator">
                            <i class="bi bi-shield-check"></i>
                            <span>Insured Deliveries</span>
                        </div>
                        <div class="trust-indicator">
                            <i class="bi bi-truck"></i>
                            <span>40+ Counties Covered</span>
                        </div>
                        <div class="trust-indicator">
                            <i class="bi bi-clock"></i>
                            <span>24/7 Support</span>
                        </div>
                        <div class="trust-indicator">
                            <i class="bi bi-star-fill"></i>
                            <span>99% Satisfaction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($pickUpAndDropOffPoints->count() > 0)
    <!-- Stations Section with Click-to-Expand Grid -->
    <section id="stations" class="stations-section">
        <div class="container-fluid px-4 px-lg-5">
            <div class="section-title">
                <h2>Pick-up Stations</h2>
                <p class="text-muted">Find our conveniently located stations across Kenya</p>
            </div>

            <!-- Counties Accordion -->
            <div class="counties-accordion" id="countiesAccordion">
                @foreach($counties as $countyIndex => $county)
                <div class="county-card mb-3">
                    <!-- County Header (Clickable) -->
                    <div class="county-header" data-county-id="county-{{ $county->id }}">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-chevron-right me-3 chevron-icon"></i>
                            <h4 class="mb-0">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                {{ $county->name }}
                            </h4>
                        </div>
                        <span class="badge bg-primary">{{ $county->subCounties->count() }} Subcounties</span>
                    </div>

                    <!-- Subcounties Container (Hidden by default) -->
                    <div class="subcounties-container" id="county-{{ $county->id }}" style="display: none;">
                        <div class="subcounties-grid">
                            @foreach($county->subCounties as $subCountyIndex => $subCounty)
                            <div class="subcounty-card mb-3">
                                <!-- Subcounty Header (Clickable) -->
                                <div class="subcounty-header" data-subcounty-id="subcounty-{{ $subCounty->id }}">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-chevron-right me-3 chevron-icon"></i>
                                        <h5 class="mb-0">
                                            <i class="bi bi-building me-2"></i>
                                            {{ $subCounty->name }}
                                        </h5>
                                    </div>
                                    <span class="badge bg-secondary">{{ $subCounty->pickupPoints->count() }} Stations</span>
                                </div>

                                <!-- Pickup Points Grid (Hidden by default) -->
                                <div class="points-container" id="subcounty-{{ $subCounty->id }}" style="display: none;">
                                    <div class="row g-4 points-grid">
                                        @foreach($subCounty->pickupPoints as $point)
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="station-card">
                                                <div class="station-card-header">
                                                    <h5 class="mb-0">{{ $point->name }}</h5>
                                                    <span class="station-type-badge {{ $point->type }}">
                                                        @if($point->type == 'both')
                                                        <i class="bi bi-arrow-left-right"></i>
                                                        @elseif($point->type == 'pickup')
                                                        <i class="bi bi-arrow-up"></i>
                                                        @else
                                                        <i class="bi bi-arrow-down"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="station-card-body">
                                                    <p class="mb-2">
                                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                                        {{ $point->address }}
                                                    </p>
                                                    <p class="mb-2">
                                                        <i class="bi bi-telephone text-primary me-2"></i>
                                                        {{ $point->contact_phone_number }}
                                                    </p>
                                                    @if($point->email)
                                                    <p class="mb-2">
                                                        <i class="bi bi-envelope text-primary me-2"></i>
                                                        {{ $point->contact_email }}
                                                    </p>
                                                    @endif
                                                    <p class="mb-0">
                                                        <i class="bi bi-clock text-primary me-2"></i>
                                                        {{ $point->opening_hours }} - {{ $point->closing_hours }}
                                                    </p>
                                                </div>
                                                <div class="station-card-footer">
                                                    <a href="#" class="btn btn-outline-primary btn-sm w-100">
                                                        <i class="bi bi-eye me-2"></i>View Details
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <!-- View More Button -->
                                    <div class="text-center mt-4">
                                        <a href="#" class="btn btn-outline-primary view-more-stations">
                                            <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                                            View All Stations in {{ $subCounty->name }}
                                            <i class="bi bi-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    @if($blogPosts->count() > 0)
    <!-- Blog Section - Smaller Width -->
    <section id="blogs" class="blog-section">
        <div class="container">
            <div class="section-title">
                <h2>Latest from Our Blog</h2>
                <p class="text-muted">Stay updated with courier tips, news, and announcements</p>
            </div>

            @if($blogPosts && $blogPosts->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($blogPosts as $post)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="blog-card h-100">
                        <a href="{{ route('blog.show', $post->slug ?? $post->id) }}" class="text-decoration-none">
                            @if($post->featured_image)
                            <div class="blog-image" style="background-image: url('{{ asset('logo.jpeg') }}'); background-size: cover; background-position: center;">
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

            <!-- View More Button Section -->
            <div class="text-center mt-5 pt-3">
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill">
                    <i class="bi bi-journal-text me-2"></i> View All Blog Posts
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
                <p class="text-muted mt-3 small">
                    <i class="bi bi-info-circle me-1"></i>
                    Explore more articles and updates
                </p>
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper" style="font-size: 4rem; color: #dee2e6;"></i>
                <h4 class="mt-3 text-muted">No blog posts yet</h4>
                <p class="text-muted">Check back soon for updates and news</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- FAQ Section -->
    @if($faqs->count() > 0)
    <section id="faq" class="faq-section">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
                <p class="text-muted">Find answers to common questions about our services</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    @foreach($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-question">
                            <span>{{ $faq->question }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p>{{ $faq->answer }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-title">
                <h2>Contact Us</h2>
                <p class="text-muted">Get in touch with our customer support team</p>
            </div>

            <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="contact-form">
                        <h4 class="mb-4">Send us a message</h4>
                        <form id="contactForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" id="contactName" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" id="contactEmail" placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="contactSubject" placeholder="Subject" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="contactMessage" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div id="contactFormMessage" class="mb-3" style="display: none;"></div>
                            <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn">
                                <i class="bi bi-send me-2"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="contact-info">
                        <h4 class="mb-4">Contact Information</h4>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-telephone fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Phone Support</h5>
                                <p>+254 700 130 759<br>
                                    <small class="opacity-75">24/7 Customer Service</small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-envelope fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Email Support</h5>
                                <p>karibuparcels@gmail.com<br>
                                    <small class="opacity-75">Response within 2 hours</small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-geo-alt fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Head Office</h5>
                                <p>Mashariki Breeze, Diani Beach Road, Office No.6 , Diani Beach Kwale County</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-mailbox2 fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>P.O. Box</h5>
                                <p>P.O. Box 5490-80401<br>
                                    <small class="opacity-75">Diani Beach, Kenya</small>
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Business Hours</h5>
                                <p>Monday - Friday: 8:00 AM - 8:00 PM<br>
                                    Saturday: 9:00 AM - 6:00 PM<br>
                                    Sunday: 10:00 AM - 4:00 PM</p>
                            </div>
                        </div>

                        <div class="social-icons mt-4">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-whatsapp"></i></a>
                            <a href="#"><i class="bi bi-tiktok"></i></a>
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
                        <a class="navbar-brand d-flex align-items-center" href="#">
                            <div class="logo-container me-2">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45"
                                    class="logo-img">
                            </div>
                            <div class="brand-text">
                                <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                                <small class="d-block text-muted"
                                    style="font-size: 0.75rem; line-height: 1;">Professional Courier
                                    Service</small>
                            </div>
                        </a>
                    </h4>
                    <p class="opacity-75">Your trusted partner for fast, reliable, and secure courier services across
                        Kenya.</p>
                    <div class="social-icons">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Services</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Express Delivery</a></li>
                        <li><a href="#features">Standard Delivery</a></li>
                        <li><a href="#features">Bulk Shipping</a></li>
                        <li><a href="#features">International</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Company</h5>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="#contact">Support</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="#tracking">Track Package</a></li>
                        <li><a href="#stations">Find Station</a></li>
                        <li><a href="#blogs">Blog</a></li>
                        <li><a href="#faq">Help Center</a></li>
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

            <!-- Business Solutions Section - Modern & Professional -->
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
                        <div class="business-buttons">
                            <a href="{{ route('marketplace') }}" target="_blank" class="business-btn marketplace-btn">
                                <span class="btn-icon"><i class="bi bi-shop"></i></span>
                                <span class="btn-text">
                                    <small>Visit our</small>
                                    <strong>Marketplace</strong>
                                </span>
                                <span class="btn-arrow"><i class="bi bi-arrow-right"></i></span>
                            </a>
                            <a href="{{ route('partners.login') }}" class="business-btn partner-btn">
                                <span class="btn-icon"><i class="bi bi-briefcase"></i></span>
                                <span class="btn-text">
                                    <small>Partner Portal</small>
                                    <strong>Login</strong>
                                </span>
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

    <!-- WhatsApp Button with Professional Design -->
    @php
    $phone = config('services.whatsapp.phone', '254700130759');
    $message = config('services.whatsapp.message', 'Hello, I need more information about your services');
    $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($message);
    @endphp

    <div class="whatsapp-float">
        <div class="whatsapp-tooltip">
            <span class="tooltip-text">Chat with us on WhatsApp</span>
            <span class="tooltip-time">Online 24/7</span>
        </div>
        <a href="{{ $whatsappUrl }}"
            target="_blank"
            class="whatsapp-button"
            aria-label="Chat with us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Town to town quote calculation
            initTownQuoteForm();

            // Initialize tracking with phone + ID
            initTracking();

            // Initialize FAQ
            initFAQ();

            // Initialize contact form
            initContactForm();

            // Smooth scroll
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();

                const targetId = $(this).attr('href');
                if (targetId === '#') return;

                const targetElement = $(targetId);
                if (targetElement.length) {
                    $('html, body').animate({
                        scrollTop: targetElement.offset().top - 80
                    }, 500);
                }
            });

            function initTownQuoteForm() {
                // Form submission
                $('#townQuoteForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!validateTownForm()) {
                        return;
                    }

                    // Show loading state
                    const submitBtn = $(this).find('button[type="submit"]');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Calculating...');

                    // Get form data
                    const formData = {
                        from_town_id: $('#fromTown').val(),
                        to_town_id: $('#toTown').val(),
                        weight: $('#weight').val(),
                        item_description: $('#itemDescription').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    // For demo purposes, simulate a response
                    setTimeout(function() {
                        const mockResponse = {
                            quote_id: 'Q' + Math.floor(Math.random() * 10000),
                            weight: $('#weight').val(),
                            item_description: $('#itemDescription').val() || 'Parcel',
                            base_price: 300,
                            weight_charge: $('#weight').val() <= 1 ? 0 : ($('#weight').val() <= 5 ? 200 : ($('#weight').val() <= 10 ? 500 : 1000 + (Math.ceil($('#weight').val() - 10) * 50))),
                            distance_charge: 500,
                            tax_rate: 16,
                            tax: 128,
                            additional_charges: 0,
                            total: Math.round((300 + ($('#weight').val() <= 1 ? 0 : ($('#weight').val() <= 5 ? 200 : ($('#weight').val() <= 10 ? 500 : 1000 + (Math.ceil($('#weight').val() - 10) * 50)))) + 500) * 1.16),
                            estimated_delivery: '2-3 business days'
                        };
                        displayTownQuoteResult(mockResponse);
                        submitBtn.prop('disabled', false).html(originalText);
                    }, 1000);

                    // Uncomment for actual AJAX
                    /*
                    $.ajax({
                        url: '/api/calculate-quote',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            displayTownQuoteResult(response);
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred while calculating the quote.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
                            }
                            showAlert(errorMessage, 'danger');
                        },
                        complete: function() {
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                    */
                });

                // Clear form
                $('#clearForm').on('click', function() {
                    $('#townQuoteForm')[0].reset();
                    $('#quoteResult').removeClass('show').empty();
                });
            }

            function validateTownForm() {
                const fromTown = $('#fromTown').val();
                const toTown = $('#toTown').val();
                const weight = $('#weight').val();

                if (!fromTown) {
                    showAlert('Please select from town', 'warning');
                    $('#fromTown').focus();
                    return false;
                }

                if (!toTown) {
                    showAlert('Please select to town', 'warning');
                    $('#toTown').focus();
                    return false;
                }

                if (!weight || weight <= 0) {
                    showAlert('Please enter a valid weight', 'warning');
                    $('#weight').focus();
                    return false;
                }

                if (fromTown === toTown) {
                    showAlert('Pickup and delivery towns cannot be the same', 'warning');
                    return false;
                }

                return true;
            }

            function showAlert(message, type) {
                const alertClass = type === 'warning' ? 'alert-warning' : 'alert-danger';
                const alert = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                `);

                $('#townQuoteForm').prepend(alert);

                setTimeout(() => {
                    alert.alert('close');
                }, 5000);
            }

            function displayTownQuoteResult(quoteData) {
                const fromTownText = $('#fromTownSelect .selected-text').text();
                const toTownText = $('#toTownSelect .selected-text').text();

                const quoteHTML = `
                    <div class="quote-result-card">
                        <div class="quote-header">
                            <div class="quote-badge">INSTANT QUOTE</div>
                            <button type="button" class="btn-close" id="closeQuote"></button>
                        </div>
                        
                        <div class="quote-body">
                            <div class="quote-route">
                                <div class="route-point">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                    <span>${fromTownText}</span>
                                </div>
                                <div class="route-arrow">
                                    <i class="bi bi-arrow-right"></i>
                                </div>
                                <div class="route-point">
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    <span>${toTownText}</span>
                                </div>
                            </div>
                            
                            <div class="quote-details">
                                <div class="detail-item">
                                    <span class="detail-label">Weight</span>
                                    <span class="detail-value">${quoteData.weight} kg</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Est. Delivery</span>
                                    <span class="detail-value">${quoteData.estimated_delivery}</span>
                                </div>
                            </div>
                            
                            <div class="price-breakdown">
                                <div class="breakdown-item">
                                    <span>Base Delivery</span>
                                    <span>KES ${quoteData.base_price}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span>Weight Charge</span>
                                    <span>KES ${quoteData.weight_charge}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span>Distance Charge</span>
                                    <span>KES ${quoteData.distance_charge}</span>
                                </div>
                                <div class="breakdown-item">
                                    <span>VAT (${quoteData.tax_rate}%)</span>
                                    <span>KES ${quoteData.tax}</span>
                                </div>
                                <div class="breakdown-item total">
                                    <span>Total Amount</span>
                                    <span class="total-amount">KES ${quoteData.total}</span>
                                </div>
                            </div>
                            
                            <div class="quote-message">
                                <i class="bi bi-info-circle-fill text-primary"></i>
                                <p>Please proceed to the nearest Karibu Parcels station with KES <strong>${quoteData.total}</strong> to send your parcel. Our station agents will assist you with the booking process.</p>
                            </div>
                            
                            <div class="quote-actions">
                                <a href="#stations" class="btn btn-outline-primary">
                                    <i class="bi bi-geo-alt me-2"></i>Find Nearest Station
                                </a>
                                <button class="btn btn-primary" id="newQuote">
                                    <i class="bi bi-arrow-repeat me-2"></i>New Quote
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                $('#quoteResult').html(quoteHTML).addClass('show');

                $('#closeQuote, #newQuote').on('click', function() {
                    $('#quoteResult').removeClass('show').empty();
                });
            }

            function initTracking() {
                $('#trackPackage').on('click', function() {
                    const phone = $('#senderPhone').val().trim();
                    const trackingId = $('#trackingId').val().trim();

                    if (!phone || !trackingId) {
                        showTrackingAlert('Please enter both phone number and tracking ID');
                        return;
                    }

                    // Validate phone number
                    if (!phone.match(/^(0|254|\+254)[0-9]{9}$/)) {
                        showTrackingAlert('Please enter a valid Kenyan phone number (e.g., 0712345678)');
                        return;
                    }

                    displayTrackingResult(phone, trackingId);
                });

                $('.tracking-example').on('click', function() {
                    $('#senderPhone').val($(this).data('phone'));
                    $('#trackingId').val($(this).data('id'));
                });
            }

            function showTrackingAlert(message) {
                const alert = $(`
                    <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                `);

                $('#trackingResult').html(alert).slideDown();

                setTimeout(() => {
                    alert.alert('close');
                }, 3000);
            }

            function displayTrackingResult(phone, trackingId) {
                const trackingData = {
                    status: 'in_transit',
                    estimated: 'Tomorrow, 5:00 PM',
                    origin: 'Nairobi Westlands',
                    destination: 'Mombasa Island',
                    current: 'Mombasa Hub',
                    timeline: [{
                            date: 'Today, 08:30 AM',
                            status: 'Package picked up',
                            location: 'Nairobi Westlands',
                            completed: true
                        },
                        {
                            date: 'Today, 12:15 PM',
                            status: 'Arrived at sorting facility',
                            location: 'Nairobi Hub',
                            completed: true
                        },
                        {
                            date: 'Today, 02:45 PM',
                            status: 'Departed for destination',
                            location: 'En route to Mombasa',
                            completed: true
                        },
                        {
                            date: 'Tomorrow, 10:00 AM',
                            status: 'Arrived at destination hub',
                            location: 'Mombasa Port',
                            completed: false
                        },
                        {
                            date: 'Tomorrow, 02:30 PM',
                            status: 'Out for delivery',
                            location: 'Mombasa Island',
                            completed: false
                        }
                    ]
                };

                let timelineHTML = '';
                trackingData.timeline.forEach((item, index) => {
                    const isCompleted = item.completed;
                    const statusClass = isCompleted ? 'completed' : 'pending';

                    timelineHTML += `
                        <div class="timeline-item ${statusClass}">
                            <div class="timeline-marker">
                                ${isCompleted ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-circle"></i>'}
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-header">
                                    <span class="timeline-status">${item.status}</span>
                                    <span class="timeline-date">${item.date}</span>
                                </div>
                                <div class="timeline-location">
                                    <i class="bi bi-geo-alt"></i>
                                    ${item.location}
                                </div>
                            </div>
                        </div>
                    `;
                });

                const statusBadge = trackingData.status === 'in_transit' ?
                    '<span class="badge bg-warning text-dark">In Transit</span>' :
                    '<span class="badge bg-success">Delivered</span>';

                const resultHTML = `
                    <div class="tracking-result-card">
                        <div class="tracking-header">
                            <div class="tracking-title">
                                <i class="bi bi-box-seam"></i>
                                <div>
                                    <h6>Tracking #${trackingId}</h6>
                                    <p>Phone: ${phone}</p>
                                </div>
                            </div>
                            <div class="tracking-status">
                                ${statusBadge}
                            </div>
                            <button class="btn-close" id="closeTracking"></button>
                        </div>
                        
                        <div class="tracking-progress">
                            <div class="progress-steps">
                                <div class="step active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>Picked Up</span>
                                </div>
                                <div class="step active">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>In Transit</span>
                                </div>
                                <div class="step">
                                    <i class="bi bi-circle"></i>
                                    <span>Out for Delivery</span>
                                </div>
                                <div class="step">
                                    <i class="bi bi-circle"></i>
                                    <span>Delivered</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tracking-details">
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Origin</span>
                                    <span class="value">${trackingData.origin}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Destination</span>
                                    <span class="value">${trackingData.destination}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Current Location</span>
                                    <span class="value">${trackingData.current}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Est. Delivery</span>
                                    <span class="value">${trackingData.estimated}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tracking-timeline">
                            <h6>Tracking History</h6>
                            <div class="timeline">
                                ${timelineHTML}
                            </div>
                        </div>
                        
                        <div class="tracking-footer">
                            <p class="small text-muted">
                                <i class="bi bi-info-circle"></i>
                                Updates every 30 minutes. Contact support for more details.
                            </p>
                        </div>
                    </div>
                `;

                $('#trackingResult').html(resultHTML).slideDown();

                $('#closeTracking').on('click', function() {
                    $('#trackingResult').slideUp(function() {
                        $(this).empty();
                    });
                });
            }

            function initFAQ() {
                $('.faq-question').on('click', function() {
                    const faqItem = $(this).closest('.faq-item');
                    const icon = $(this).find('i');

                    $('.faq-item').not(faqItem).removeClass('active').find('i').removeClass('bi-chevron-up')
                        .addClass('bi-chevron-down');

                    faqItem.toggleClass('active');

                    if (faqItem.hasClass('active')) {
                        icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
                    } else {
                        icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
                    }
                });
            }

            function initContactForm() {
                $('#contactForm').on('submit', function(e) {
                    e.preventDefault();

                    const submitBtn = $('#contactSubmitBtn');
                    const originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

                    const formData = {
                        name: $('#contactName').val(),
                        email: $('#contactEmail').val(),
                        subject: $('#contactSubject').val(),
                        message: $('#contactMessage').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    // Simulate email sending (replace with actual AJAX)
                    setTimeout(function() {
                        submitBtn.prop('disabled', false).html(originalText);

                        const successMessage = $(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Thank you for your message! We will get back to you within 2 hours.
                                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                            </div>
                        `);

                        $('#contactFormMessage').html(successMessage).slideDown();
                        $('#contactForm')[0].reset();

                        setTimeout(() => {
                            $('#contactFormMessage').slideUp();
                        }, 5000);
                    }, 1500);

                    // Uncomment for actual AJAX
                    /*
                    $.ajax({
                        url: '/send-contact-email',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            submitBtn.prop('disabled', false).html(originalText);
                            
                            const successMessage = $(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    ${response.message}
                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                            
                            $('#contactFormMessage').html(successMessage).slideDown();
                            $('#contactForm')[0].reset();
                            
                            setTimeout(() => {
                                $('#contactFormMessage').slideUp();
                            }, 5000);
                        },
                        error: function(xhr) {
                            submitBtn.prop('disabled', false).html(originalText);
                            
                            const errorMessage = $(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Failed to send message. Please try again or contact us directly.
                                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                            
                            $('#contactFormMessage').html(errorMessage).slideDown();
                            
                            setTimeout(() => {
                                $('#contactFormMessage').slideUp();
                            }, 5000);
                        }
                    });
                    */
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize searchable selects
            initSearchableSelects();

            // County click handler
            $('.county-header').on('click', function() {
                const countyId = $(this).data('county-id');
                const subcountiesContainer = $('#' + countyId);
                const chevron = $(this).find('.chevron-icon');

                if (subcountiesContainer.is(':visible')) {
                    subcountiesContainer.slideUp(300);
                    chevron.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    $(this).removeClass('active');
                } else {
                    $('.subcounties-container').slideUp(300);
                    $('.county-header').removeClass('active');
                    $('.county-header .chevron-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');

                    subcountiesContainer.slideDown(300);
                    chevron.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                    $(this).addClass('active');
                }

                return false;
            });

            // Subcounty click handler
            $('.subcounty-header').on('click', function(e) {
                e.stopPropagation();

                const subcountyId = $(this).data('subcounty-id');
                const pointsContainer = $('#' + subcountyId);
                const chevron = $(this).find('.chevron-icon');

                if (pointsContainer.is(':visible')) {
                    pointsContainer.slideUp(300);
                    chevron.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    $(this).removeClass('active');
                } else {
                    const parentCounty = $(this).closest('.county-card');
                    parentCounty.find('.points-container').slideUp(300);
                    parentCounty.find('.subcounty-header').removeClass('active');
                    parentCounty.find('.subcounty-header .chevron-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');

                    pointsContainer.slideDown(300);
                    chevron.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                    $(this).addClass('active');
                }
            });

            $('.subcounty-header').on('click', function(e) {
                e.stopPropagation();
            });

            function initSearchableSelects() {
                $('.searchable-select-display').on('click', function(e) {
                    e.stopPropagation();
                    const select = $(this).closest('.searchable-select');

                    $('.searchable-select').not(select).removeClass('active');

                    select.toggleClass('active');

                    if (select.hasClass('active')) {
                        select.find('.search-input').focus();
                    }
                });

                $('#fromTownSearch').on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    filterOptions('#fromTownOptions', searchTerm);
                });

                $('#toTownSearch').on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    filterOptions('#toTownOptions', searchTerm);
                });

                $('#fromTownOptions').on('click', '.option-item', function() {
                    const value = $(this).data('value');
                    const townName = $(this).find('.town-name').text();
                    const county = $(this).find('.town-county').text();

                    $('#fromTown').val(value);
                    $('#fromTownSelect .selected-text').text(`${townName} (${county})`);

                    $('#fromTownOptions .option-item').removeClass('selected');
                    $(this).addClass('selected');

                    $('#fromTownSelect').closest('.searchable-select').removeClass('active');

                    $('#fromTownSearch').val('');
                    $('#fromTownOptions .option-item').show();
                });

                $('#toTownOptions').on('click', '.option-item', function() {
                    const value = $(this).data('value');
                    const townName = $(this).find('.town-name').text();
                    const county = $(this).find('.town-county').text();

                    $('#toTown').val(value);
                    $('#toTownSelect .selected-text').text(`${townName} (${county})`);

                    $('#toTownOptions .option-item').removeClass('selected');
                    $(this).addClass('selected');

                    $('#toTownSelect').closest('.searchable-select').removeClass('active');

                    $('#toTownSearch').val('');
                    $('#toTownOptions .option-item').show();
                });

                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.searchable-select').length) {
                        $('.searchable-select').removeClass('active');
                    }
                });

                $('.searchable-select-dropdown').on('click', function(e) {
                    e.stopPropagation();
                });
            }

            function filterOptions(selector, searchTerm) {
                const options = $(`${selector} .option-item`);
                let hasResults = false;

                options.each(function() {
                    const townName = $(this).find('.town-name').text().toLowerCase();
                    const county = $(this).find('.town-county').text().toLowerCase();

                    if (townName.includes(searchTerm) || county.includes(searchTerm)) {
                        $(this).show();
                        hasResults = true;
                    } else {
                        $(this).hide();
                    }
                });

                const container = $(selector);
                container.find('.no-results').remove();

                if (!hasResults && searchTerm) {
                    container.append(`
                        <div class="no-results">
                            <i class="bi bi-search"></i>
                            <p>No towns found matching "${searchTerm}"</p>
                            <small>Try a different search term</small>
                        </div>
                    `);
                }
            }
        });
    </script>

    <style>
        :root {
            --primary-color: #008f40;
            --primary-dark: #007a36;
            --primary-light: #e8f5e9;
            --accent-color: #ff3519;
            --accent-dark: #e62e15;
            --accent-light: #ffe9e5;
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
        }

        /* Navigation Styles */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
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
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 150px 0 100px;
            min-height: 85vh;
            display: flex;
            align-items: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-content .lead {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 600px;
        }

        /* Compact Booking Engine */
        .compact-booking-engine {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
            width: 100%;
        }

        .booking-tabs {
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .booking-tabs .nav-link {
            color: var(--text-dark);
            font-weight: 600;
            padding: 10px 20px;
            border: none;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .booking-tabs .nav-link:hover {
            border-color: var(--primary-color);
            background: transparent;
        }

        .booking-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            background: transparent;
        }

        .booking-form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .booking-field label {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .booking-field label i {
            font-size: 1rem;
        }

        .compact-select {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 15px;
            font-size: 0.95rem;
            height: auto;
            background-color: white;
            transition: var(--transition);
        }

        .compact-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            outline: none;
        }

        .booking-action {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .quote-btn {
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            transition: var(--transition);
        }

        .quote-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.3);
        }

        .clear-btn {
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 12px;
            border: 2px solid var(--border-color);
            background: white;
            transition: var(--transition);
        }

        .clear-btn:hover {
            background: var(--light-bg);
            border-color: var(--text-light);
        }

        /* Quote Result Card - Modern & Professional */
        .compact-quote-result {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 2px dashed var(--border-color);
            display: none;
        }

        .compact-quote-result.show {
            display: block;
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .quote-result-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        .quote-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quote-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .quote-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .quote-header .btn-close:hover {
            opacity: 1;
        }

        .quote-body {
            padding: 25px;
        }

        .quote-route {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--primary-light);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .route-point {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .route-point i {
            font-size: 1.2rem;
        }

        .route-arrow i {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .quote-details {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background: var(--light-bg);
            border-radius: 12px;
        }

        .detail-item {
            flex: 1;
        }

        .detail-label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .price-breakdown {
            background: var(--light-bg);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: var(--text-light);
            border-bottom: 1px dashed var(--border-color);
        }

        .breakdown-item:last-child {
            border-bottom: none;
        }

        .breakdown-item.total {
            margin-top: 8px;
            padding-top: 12px;
            border-top: 2px solid var(--border-color);
            color: var(--text-dark);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .total-amount {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 700;
        }

        .quote-message {
            display: flex;
            gap: 12px;
            background: var(--primary-light);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }

        .quote-message i {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .quote-message p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .quote-message strong {
            color: var(--primary-color);
        }

        .quote-actions {
            display: flex;
            gap: 12px;
        }

        .quote-actions .btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-weight: 500;
            transition: var(--transition);
        }

        .quote-actions .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .quote-actions .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .quote-actions .btn-primary {
            background: var(--primary-color);
            border: none;
        }

        .quote-actions .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.2);
        }

        /* Stats Section */
        .stats-section {
            background: white;
            padding: 40px 0;
            margin-top: -30px;
            position: relative;
            z-index: 10;
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            text-align: center;
            padding: 15px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Features Section */
        .features-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title h2 {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
            font-size: 2rem;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            height: 100%;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            border: 1px solid transparent;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: var(--shadow-hover);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        /* Tracking Section */
        .tracking-section {
            padding: 60px 0;
            background: white;
        }

        .tracking-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            padding: 50px;
            color: white;
            text-align: center;
        }

        .tracking-form-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .tracking-input-group {
            background: white;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .tracking-input {
            border: none;
            padding: 8px 0;
            font-size: 1rem;
            width: 100%;
            background: transparent;
        }

        .tracking-input:focus {
            outline: none;
        }

        .tracking-input::placeholder {
            color: var(--text-light);
        }

        .tracking-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tracking-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 53, 25, 0.3);
        }

        /* Tracking Result Card - Modern & Professional */
        .tracking-result-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: left;
            margin-top: 20px;
        }

        .tracking-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .tracking-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .tracking-title i {
            font-size: 2rem;
        }

        .tracking-title h6 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .tracking-title p {
            margin: 4px 0 0;
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .tracking-status .badge {
            padding: 8px 15px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 30px;
        }

        .tracking-progress {
            padding: 25px;
            border-bottom: 1px solid var(--border-color);
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step i {
            width: 40px;
            height: 40px;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            color: var(--text-light);
            font-size: 1.2rem;
            position: relative;
            z-index: 2;
        }

        .step.active i {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .step span {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-light);
        }

        .step.active span {
            color: var(--primary-color);
        }

        .tracking-details {
            padding: 20px;
            background: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .detail-item .label {
            display: block;
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 4px;
        }

        .detail-item .value {
            font-weight: 600;
            color: var(--text-dark);
        }

        .tracking-timeline {
            padding: 25px;
        }

        .tracking-timeline h6 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .timeline-item {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 35px;
            bottom: -15px;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-marker {
            position: relative;
            z-index: 2;
        }

        .timeline-marker i {
            font-size: 1.2rem;
        }

        .timeline-item.completed .timeline-marker i {
            color: var(--primary-color);
        }

        .timeline-item.pending .timeline-marker i {
            color: var(--text-light);
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .timeline-status {
            font-weight: 600;
            color: var(--text-dark);
        }

        .timeline-date {
            font-size: 0.8rem;
            color: var(--text-light);
        }

        .timeline-location {
            font-size: 0.85rem;
            color: var(--text-light);
        }

        .timeline-location i {
            font-size: 0.8rem;
            margin-right: 4px;
        }

        .tracking-footer {
            padding: 15px 25px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
        }

        /* Trusted By Section - Modern & Professional */
        .trusted-by-section {
            padding: 60px 0;
            background: linear-gradient(135deg, var(--light-bg) 0%, white 100%);
        }

        .trusted-description {
            max-width: 800px;
            margin: 0 auto;
        }

        .trusted-description .lead {
            font-size: 1.2rem;
            color: var(--text-light);
            font-weight: 400;
            line-height: 1.6;
        }

        .trusted-card {
            height: 100%;
            cursor: pointer;
        }

        .trusted-card-inner {
            background: white;
            border-radius: 20px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .trusted-card-inner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            opacity: 0;
            transition: var(--transition);
        }

        .trusted-card:hover .trusted-card-inner {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: transparent;
        }

        .trusted-card:hover .trusted-card-inner::before {
            opacity: 1;
        }

        .trusted-icon-wrapper {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .trusted-icon-wrapper i {
            font-size: 2.5rem;
            color: var(--primary-color);
            position: relative;
            z-index: 2;
        }

        .trusted-icon-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--primary-light);
            border-radius: 20px;
            transform: rotate(45deg);
            transition: var(--transition);
        }

        .trusted-card:hover .trusted-icon-bg {
            transform: rotate(55deg);
            background: var(--primary-color);
        }

        .trusted-card:hover .trusted-icon-wrapper i {
            color: white;
        }

        .trusted-card-inner h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .trusted-card-inner p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .trusted-stats {
            margin-top: 15px;
        }

        .stat-badge {
            display: inline-block;
            padding: 5px 15px;
            background: var(--primary-light);
            color: var(--primary-color);
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .trusted-card:hover .stat-badge {
            background: var(--primary-color);
            color: white;
        }

        .trust-indicators {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            background: white;
            padding: 25px;
            border-radius: 50px;
            box-shadow: var(--shadow-md);
        }

        .trust-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-dark);
            font-weight: 500;
        }

        .trust-indicator i {
            font-size: 1.2rem;
            color: var(--primary-color);
        }

        /* Stations Section */
        .stations-section {
            padding: 60px 0;
            background: white;
        }

        .counties-accordion {
            max-width: 1400px;
            margin: 0 auto;
        }

        .county-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .county-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .county-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
        }

        .county-header:hover {
            background: linear-gradient(135deg, var(--primary-dark), #00662d);
        }

        .county-header.active {
            background: linear-gradient(135deg, var(--primary-dark), #00662d);
        }

        .county-header .chevron-icon {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .county-header h4 {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .county-header .badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .subcounties-container {
            padding: 20px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
        }

        .subcounties-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .subcounty-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .subcounty-header {
            background: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            border-bottom: 1px solid transparent;
        }

        .subcounty-header:hover {
            background: var(--light-bg);
        }

        .subcounty-header.active {
            background: var(--primary-light);
            border-bottom: 1px solid var(--primary-color);
        }

        .subcounty-header .chevron-icon {
            font-size: 1rem;
            color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .subcounty-header h5 {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .subcounty-header .badge {
            background: var(--light-bg);
            color: var(--text-dark);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .points-container {
            padding: 20px;
            background: white;
            border-top: 1px solid var(--border-color);
        }

        .station-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            border: 1px solid var(--border-color);
        }

        .station-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .station-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .station-card-header h5 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .station-type-badge {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .station-card-body {
            padding: 15px;
        }

        .station-card-body p {
            color: var(--text-dark);
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 8px;
        }

        .station-card-body i {
            width: 20px;
            display: inline-block;
        }

        .station-card-footer {
            padding: 15px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
        }

        .view-more-stations {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 500;
            transition: var(--transition);
        }

        .view-more-stations:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Blog Section */
        .blog-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .blog-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            height: 100%;
            border: 1px solid var(--border-color);
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary-color);
        }

        .blog-card a {
            color: inherit;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .blog-image {
            height: 180px;
            background: linear-gradient(135deg, var(--primary-light), #b8e0d2);
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
        }

        .blog-image i {
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.8;
        }

        .blog-content {
            padding: 25px;
        }

        .blog-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.8rem;
        }

        .blog-category {
            background: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 30px;
            font-weight: 500;
        }

        .blog-date {
            color: var(--text-light);
        }

        .blog-title {
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 1.1rem;
            line-height: 1.4;
            color: var(--text-dark);
        }

        .blog-excerpt {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .blog-footer {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--text-light);
            border-top: 1px solid var(--border-color);
            padding-top: 12px;
        }

        /* FAQ Section */
        .faq-section {
            padding: 60px 0;
            background: white;
        }

        .faq-item {
            margin-bottom: 12px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            background: var(--light-bg);
        }

        .faq-question {
            padding: 20px;
            background: var(--light-bg);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition);
            font-size: 1rem;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-answer {
            padding: 0 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: white;
            font-size: 0.95rem;
        }

        .faq-item.active .faq-answer {
            padding: 20px;
            max-height: 500px;
        }

        .faq-item.active .faq-question {
            background: var(--primary-color);
            color: white;
        }

        .faq-item.active .faq-question i {
            color: white;
        }

        /* Contact Section */
        .contact-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .contact-form {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        .contact-form .form-control {
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 15px;
            transition: var(--transition);
        }

        .contact-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            outline: none;
        }

        .contact-info {
            padding: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 20px;
            color: white;
            height: 100%;
        }

        .contact-info h5 {
            font-size: 1rem;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .contact-info p {
            font-size: 0.95rem;
            margin-bottom: 0;
            opacity: 0.9;
        }

        /* Business Solutions - Modern & Professional */
        .business-solutions {
            background: linear-gradient(to right, var(--primary-light), white);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        .business-solutions-text .badge {
            background: var(--primary-color);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 30px;
        }

        .business-solutions-text h4 {
            color: var(--text-dark);
            font-weight: 600;
        }

        .business-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .business-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .business-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
            z-index: 1;
        }

        .business-btn:hover::before {
            left: 100%;
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

        .business-btn .btn-icon {
            position: relative;
            z-index: 2;
            font-size: 1.2rem;
        }

        .business-btn .btn-text {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            line-height: 1.3;
        }

        .business-btn .btn-text small {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        .business-btn .btn-text strong {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .business-btn .btn-arrow {
            position: relative;
            z-index: 2;
            font-size: 1rem;
            transition: transform 0.3s ease;
        }

        .business-btn:hover .btn-arrow {
            transform: translateX(5px);
        }

        .marketplace-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.3);
        }

        .partner-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.2);
        }

        /* Footer */
        footer {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0 20px;
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
            background: rgba(255, 255, 255, 0.1);
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

        /* WhatsApp Button - Professional Design */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .whatsapp-button {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25d366, #128C7E);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
            transition: var(--transition);
            position: relative;
            z-index: 2;
        }

        .whatsapp-button:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 30px rgba(37, 211, 102, 0.4);
            color: white;
        }

        .whatsapp-button i {
            animation: pulse-whatsapp 2s infinite;
        }

        @keyframes pulse-whatsapp {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .whatsapp-tooltip {
            background: white;
            border-radius: 50px;
            padding: 12px 20px;
            margin-right: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            animation: slideInRight 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .whatsapp-tooltip::after {
            content: '';
            position: absolute;
            right: -8px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 8px solid white;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
        }

        .tooltip-text {
            display: block;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .tooltip-time {
            display: block;
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .whatsapp-float:hover .whatsapp-tooltip {
            animation: none;
        }

        /* Searchable Select Styles */
        .searchable-select-container {
            position: relative;
            width: 100%;
        }

        .searchable-select {
            position: relative;
            width: 100%;
        }

        .searchable-select-display {
            width: 100%;
            padding: 12px 15px;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            transition: var(--transition);
            min-height: 48px;
        }

        .searchable-select-display:hover {
            border-color: var(--primary-color);
            background: var(--light-bg);
        }

        .searchable-select.active .searchable-select-display {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
        }

        .searchable-select.active .searchable-select-display i {
            transform: rotate(180deg);
            color: var(--primary-color);
        }

        .searchable-select-dropdown {
            position: absolute;
            top: calc(100% + 5px);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 15px;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            display: none;
            max-height: 350px;
            overflow: hidden;
        }

        .searchable-select.active .searchable-select-dropdown {
            display: block;
            animation: slideDown 0.2s ease;
        }

        .search-box {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            position: relative;
            background: var(--light-bg);
        }

        .search-box i {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border-color);
            border-radius: 30px;
            font-size: 0.9rem;
            outline: none;
            transition: var(--transition);
            background: white;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(0, 143, 64, 0.1);
        }

        .options-list {
            max-height: 280px;
            overflow-y: auto;
            padding: 8px;
        }

        .option-item {
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 10px;
            transition: var(--transition);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .option-item .town-name {
            font-weight: 500;
            color: var(--text-dark);
            flex: 1;
        }

        .option-item .town-county {
            font-size: 0.75rem;
            background: var(--light-bg);
            padding: 2px 8px;
            border-radius: 12px;
            color: var(--text-light);
        }

        .option-item:hover {
            background: var(--primary-light);
        }

        .option-item.selected {
            background: var(--primary-color);
            color: white;
        }

        .option-item.selected .town-name {
            color: white;
        }

        .option-item.selected .town-county {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .option-item i {
            color: var(--primary-color);
            font-size: 1rem;
        }

        .option-item.selected i {
            color: white;
        }

        /* Weight Input Group */
        .weight-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .weight-input-group input {
            padding-right: 45px;
        }

        .weight-unit {
            position: absolute;
            right: 15px;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
            pointer-events: none;
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        /* Responsive Design */
        @media (max-width: 1400px) {
            .points-grid .col-xl-3 {
                width: 25%;
            }
        }

        @media (max-width: 1200px) {
            .points-grid .col-lg-4 {
                width: 33.333%;
            }
        }

        @media (max-width: 992px) {
            .hero-section {
                padding: 120px 0 50px;
                min-height: auto;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .booking-form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-section {
                margin-top: -20px;
                padding: 30px 0;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .points-grid .col-lg-4 {
                width: 50%;
            }

            .business-buttons {
                justify-content: flex-start;
                margin-top: 20px;
            }
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-content .lead {
                font-size: 1rem;
            }

            .compact-booking-engine {
                padding: 20px;
            }

            .booking-form-grid {
                grid-template-columns: 1fr;
            }

            .booking-action {
                flex-direction: column;
            }

            .quote-btn,
            .clear-btn {
                width: 100%;
            }

            .quote-actions {
                flex-direction: column;
            }

            .contact-form,
            .contact-info {
                padding: 25px;
            }

            .points-grid .col-md-6 {
                width: 100%;
            }

            .trust-indicators {
                flex-direction: column;
                align-items: center;
                gap: 15px;
                border-radius: 20px;
            }

            .business-buttons {
                flex-direction: column;
            }

            .whatsapp-float {
                bottom: 20px;
                right: 20px;
            }

            .whatsapp-button {
                width: 50px;
                height: 50px;
                font-size: 25px;
            }

            .whatsapp-tooltip {
                display: none;
            }
        }
    </style>

    <!-- Meta CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- WhatsApp Click Tracking -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappBtn = document.querySelector('.whatsapp-button');
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', function() {
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'whatsapp_click', {
                            'event_category': 'engagement',
                            'event_label': 'whatsapp_chat'
                        });
                    }

                    fetch('/track-whatsapp-click', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            page: window.location.pathname,
                            timestamp: new Date().toISOString()
                        })
                    }).catch(error => console.error('Error tracking WhatsApp click:', error));
                });
            }
        });
    </script>
</body>

</html>