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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                <ul class="navbar-nav mx-auto"> <!-- Changed ms-auto to mx-auto for center alignment -->
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

                <!-- Optional: Add a small call-to-action or search icon for balance -->
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
                                                    <small class="town-county text-muted">{{ $town->subCounty->county->name ?? 'Kenya' }}</small>
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
                                                    <small class="town-county text-muted">{{ $town->subCounty->county->name ?? 'Kenya' }}</small>
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
                                        @foreach($parcelTypes as $parcelType)
                                        <option value="{{ $parcelType->id }}">{{ $parcelType->name }}</option>

                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Item Description - Full Width -->
                            <div class="booking-field full-width">
                                <label><i class="bi bi-card-text me-1"></i> Item Description</label>
                                <textarea class="form-control compact-select" id="itemDescription" rows="2" placeholder="Describe your item (e.g., Documents, Electronics, Clothing, etc.)..." style="resize: none;"></textarea>
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
                <p class="text-muted">Comprehensive courier solutions for all your delivery needs</p>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4>Express Delivery</h4>
                        <p>Same-day and next-day delivery options for urgent packages within major cities.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4>Standard Delivery</h4>
                        <p>Reliable 2-3 day delivery service across all counties at affordable rates.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <h4>Bulk Shipping</h4>
                        <p>Special rates for businesses with regular or large volume shipments.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Secure Delivery</h4>
                        <p>All packages are insured and handled with care throughout the journey.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-laptop"></i>
                        </div>
                        <h4>E-commerce Integration</h4>
                        <p>Seamless integration with major e-commerce platforms for automatic shipping.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <h4>International Shipping</h4>
                        <p>Global delivery services with customs clearance assistance. Coming soon!</p>
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

    <!-- Partners Section -->
    <section class="partners-section">
        <div class="container">
            <div class="section-title">
                <h2>Trusted By</h2>
                <p class="text-muted">Leading businesses trust Karibu Parcels for their shipping needs</p>
            </div>

            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-shop"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-hospital"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-industry"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-phone"></i>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="partner-logo">
                        <i class="bi bi-laptop"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                            </div>
                                        </div>
                                        @endforeach
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

    <!-- Blog Section - Pure Blade, No JavaScript -->
    <section id="blogs" class="blog-section">
        <div class="container-fluid px-4 px-lg-5">
            <div class="section-title">
                <h2>Latest from Our Blog</h2>
                <p class="text-muted">Stay updated with courier tips, news, and announcements</p>
            </div>

            @if($blogPosts && $blogPosts->count() > 0)
            <div class="row g-4">
                @foreach($blogPosts as $post)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="blog-card h-100">
                        <a href="#" class="text-decoration-none">
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

            @if($blogPosts->count() >= 8)
            <div class="text-center mt-5">
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-arrow-right me-2"></i> View All Posts
                </a>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="bi bi-newspaper" style="font-size: 4rem; color: #dee2e6;"></i>
                <h4 class="mt-3 text-muted">No blog posts yet</h4>
                <p class="text-muted">Check back soon for updates and news</p>
            </div>
            @endif
        </div>
    </section>

    <!-- FAQ Section -->
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" placeholder="Your Email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Subject" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">
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
                                <p>+254 700 123 456<br>
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
                                <p>info@karibuparcels.co.ke<br>
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
                                <p>Karibu Plaza, Westlands<br>
                                    Nairobi, Kenya</p>
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
                            <a href="#"><i class="bi bi-twitter"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
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
                        <a href="#"><i class="bi bi-twitter"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
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

            <!-- Marketplace & Partners Section -->
            <div class="marketplace-partners-section mt-4 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center">
                            <span class="text-muted small fw-semibold me-3">BUSINESS SOLUTIONS:</span>
                            <div class="d-flex gap-3">
                                <a href="{{ route('marketplace') }}" target="_blank" class="footer-business-link">
                                    <i class="bi bi-shop me-2"></i>
                                    Marketplace
                                </a>
                                <a href="{{ route('partners.login') }}" class="footer-business-link">
                                    <i class="bi bi-briefcase me-2"></i>
                                    Partners
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
                        <div class="d-flex gap-2 justify-content-lg-end">
                            <a href="{{ route('marketplace') }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-4">
                                <i class="bi bi-shop me-2"></i>
                                Visit Marketplace
                            </a>
                            <a href="{{ route('partners.login') }}" class="btn btn-outline-success btn-sm rounded-pill px-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Partner Login
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
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your message! We will get back to you shortly.');
                this.reset();
            });

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

            // Town to town form functions
            function initTownQuoteForm() {
                // Form submission
                $('#townQuoteForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!validateTownForm()) {
                        return;
                    }

                    const quote = calculateTownQuote();
                    displayTownQuoteResult(quote);
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
                const itemDescription = $('#itemDescription').val().trim();

                // Check required fields
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

                if (!itemDescription) {
                    showAlert('Please describe your item', 'warning');
                    $('#itemDescription').focus();
                    return false;
                }

                // Check if pickup and delivery are same
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

            function calculateTownQuote() {
                const fromTown = $('#fromTown').val();
                const toTown = $('#toTown').val();
                const weight = parseFloat($('#weight').val());

                // Base price
                let basePrice = 300;

                // Add distance factor (simplified)
                const majorRoutes = [
                    ['Nairobi', 'Mombasa'],
                    ['Mombasa', 'Nairobi'],
                    ['Nairobi', 'Kisumu'],
                    ['Kisumu', 'Nairobi'],
                    ['Nairobi', 'Nakuru'],
                    ['Nakuru', 'Nairobi'],
                    ['Nairobi', 'Eldoret'],
                    ['Eldoret', 'Nairobi']
                ];

                const isLongDistance = majorRoutes.some(route =>
                    (route[0] === fromTown && route[1] === toTown) ||
                    (route[0] === toTown && route[1] === fromTown)
                );

                if (isLongDistance) {
                    basePrice += 500;
                } else if (fromTown !== toTown) {
                    basePrice += 200;
                }

                // Add weight factor
                let weightCharge = 0;
                if (weight <= 1) {
                    weightCharge = 0;
                } else if (weight <= 5) {
                    weightCharge = 200;
                } else if (weight <= 10) {
                    weightCharge = 500;
                } else {
                    weightCharge = 1000 + (Math.ceil(weight - 10) * 50);
                }

                // Calculate total
                const subtotal = basePrice + weightCharge;
                const tax = subtotal * 0.16;
                const total = Math.round(subtotal + tax);

                return {
                    base: basePrice,
                    weight: weightCharge,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    weight: weight
                };
            }

            function displayTownQuoteResult(quote) {
                const fromTown = $('#fromTown option:selected').text();
                const toTown = $('#toTown option:selected').text();
                const deliveryTime = getDeliveryTime($('#fromTown').val(), $('#toTown').val());

                const quoteHTML = `
                    <div class="quote-result-content">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1">Estimated Quote</h6>
                                <p class="mb-0 small text-muted">${fromTown} → ${toTown} | ${quote.weight} kg</p>
                                <p class="mb-0 small text-muted">${$('#itemDescription').val().substring(0, 50)}${$('#itemDescription').val().length > 50 ? '...' : ''}</p>
                            </div>
                            <span class="quote-amount">KES ${quote.total}</span>
                        </div>
                        
                        <div class="price-breakdown small mb-3">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Base delivery:</span>
                                <span>KES ${quote.base}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Weight charge:</span>
                                <span>KES ${quote.weight}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">VAT (16%):</span>
                                <span>KES ${Math.round(quote.tax)}</span>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm flex-grow-1" id="proceedBooking">
                                Proceed <i class="bi bi-arrow-right"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" id="closeQuote">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        
                        <p class="small text-muted mt-2 mb-0">
                            <i class="bi bi-info-circle"></i> 
                            Estimated delivery: ${deliveryTime}. Final price may vary.
                        </p>
                    </div>
                `;

                $('#quoteResult').html(quoteHTML).addClass('show');

                $('#closeQuote').on('click', function() {
                    $('#quoteResult').removeClass('show').empty();
                });

                $('#proceedBooking').on('click', function() {
                    alert('Please priceed to the nearest parcel station to send your parcel  ' + quote.total);
                });
            }

            function getDeliveryTime(fromTown, toTown) {
                if (fromTown === toTown) {
                    return 'Same day within city';
                } else {
                    return '1-2 business days';
                }
            }

            // Tracking functions
            function initTracking() {
                $('#trackPackage').on('click', function() {
                    const phone = $('#senderPhone').val().trim();
                    const trackingId = $('#trackingId').val().trim();

                    if (!phone || !trackingId) {
                        alert('Please enter both phone number and tracking ID');
                        return;
                    }

                    // Validate phone number (simple Kenyan format)
                    if (!phone.match(/^(0|254|\+254)[0-9]{9}$/)) {
                        alert('Please enter a valid Kenyan phone number (e.g., 0712345678)');
                        return;
                    }

                    displayTrackingResult(phone, trackingId);
                });

                $('.tracking-example').on('click', function() {
                    $('#senderPhone').val($(this).data('phone'));
                    $('#trackingId').val($(this).data('id'));
                });
            }

            function displayTrackingResult(phone, trackingId) {
                const sampleStatus = [{
                        date: 'Today, 08:30 AM',
                        status: 'Package picked up',
                        location: 'Nairobi Westlands'
                    },
                    {
                        date: 'Today, 12:15 PM',
                        status: 'Arrived at sorting facility',
                        location: 'Nairobi Hub'
                    },
                    {
                        date: 'Today, 02:45 PM',
                        status: 'Departed for destination',
                        location: 'En route to Mombasa'
                    },
                    {
                        date: 'Tomorrow, 10:00 AM',
                        status: 'Arrived at destination hub',
                        location: 'Mombasa Port'
                    },
                    {
                        date: 'Tomorrow, 02:30 PM',
                        status: 'Out for delivery',
                        location: 'Mombasa Island'
                    }
                ];

                let timelineHTML = '';
                sampleStatus.forEach((item, index) => {
                    timelineHTML += `
                        <div class="d-flex mb-2">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                    ${index + 1}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0 small fw-bold">${item.status}</h6>
                                <p class="mb-0 small text-muted">${item.date}</p>
                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> ${item.location}</small>
                            </div>
                        </div>
                    `;
                });

                const resultHTML = `
                    <div class="card mt-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1"><i class="bi bi-box-seam me-2"></i> Tracking: ${trackingId}</h6>
                                    <span class="badge bg-warning small">In Transit</span>
                                    <p class="mb-0 small mt-1"><i class="bi bi-telephone me-1"></i> Sender: ${phone}</p>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" id="closeTracking">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            ${timelineHTML}
                            <div class="alert alert-light py-2 mt-2 mb-0 small">
                                <i class="bi bi-clock me-2"></i>
                                <strong>Estimated Delivery:</strong> Tomorrow by 5:00 PM
                            </div>
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

            // FAQ functions
            function initFAQ() {
                $('.faq-question').on('click', function() {
                    const faqItem = $(this).closest('.faq-item');
                    const icon = $(this).find('i');

                    // Close all other items
                    $('.faq-item').not(faqItem).removeClass('active').find('i').removeClass('bi-chevron-up')
                        .addClass('bi-chevron-down');

                    // Toggle current item
                    faqItem.toggleClass('active');

                    // Change icon
                    if (faqItem.hasClass('active')) {
                        icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
                    } else {
                        icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // County click handler
            $('.county-header').on('click', function() {
                const countyId = $(this).data('county-id');
                const subcountiesContainer = $('#' + countyId);
                const chevron = $(this).find('.chevron-icon');

                // Toggle current county
                if (subcountiesContainer.is(':visible')) {
                    subcountiesContainer.slideUp(300);
                    chevron.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    $(this).removeClass('active');
                } else {
                    // Close all other counties first
                    $('.subcounties-container').slideUp(300);
                    $('.county-header').removeClass('active');
                    $('.county-header .chevron-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');

                    // Open this county
                    subcountiesContainer.slideDown(300);
                    chevron.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                    $(this).addClass('active');
                }

                // Prevent event bubbling
                return false;
            });

            // Subcounty click handler
            $('.subcounty-header').on('click', function(e) {
                e.stopPropagation();

                const subcountyId = $(this).data('subcounty-id');
                const pointsContainer = $('#' + subcountyId);
                const chevron = $(this).find('.chevron-icon');

                // Toggle current subcounty
                if (pointsContainer.is(':visible')) {
                    pointsContainer.slideUp(300);
                    chevron.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                    $(this).removeClass('active');
                } else {
                    // Close all other subcounties within same county
                    const parentCounty = $(this).closest('.county-card');
                    parentCounty.find('.points-container').slideUp(300);
                    parentCounty.find('.subcounty-header').removeClass('active');
                    parentCounty.find('.subcounty-header .chevron-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');

                    // Open this subcounty
                    pointsContainer.slideDown(300);
                    chevron.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                    $(this).addClass('active');
                }
            });

            // Prevent county click when clicking on subcounty header
            $('.subcounty-header').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

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
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .logo-icon {
            color: var(--primary-color);
            margin-right: 10px;
        }

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
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            margin-top: 30px;
            width: 100%;
        }

        /* Booking Tabs */
        .booking-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .booking-tabs .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 8px 16px;
            border: none;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
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
            font-weight: 600;
        }

        .booking-header {
            margin-bottom: 20px;
        }

        .booking-form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .booking-field.full-width {
            grid-column: span 4;
        }

        .booking-field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .compact-select {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 0.9rem;
            height: auto;
            background-color: white;
        }

        .compact-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(0, 143, 64, 0.1);
        }

        textarea.compact-select {
            min-height: 60px;
            resize: vertical;
        }

        .booking-action {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .quote-btn {
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 6px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            transition: all 0.3s ease;
        }

        .quote-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.3);
        }

        .clear-btn {
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            background: white;
        }

        .clear-btn:hover {
            background: #f8f9fa;
        }

        .compact-quote-result {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #dee2e6;
            display: none;
        }

        .compact-quote-result.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        .quote-result-content {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .quote-amount {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .price-breakdown {
            background: white;
            border-radius: 6px;
            padding: 10px;
            border: 1px solid #e9ecef;
        }

        .price-breakdown div {
            padding: 2px 0;
        }

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

        /* International Coming Soon */
        .international-coming-soon {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
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
            color: var(--primary-color);
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
            background: var(--accent-color);
            border-radius: 2px;
        }

        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            height: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid transparent;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 1.6rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .feature-card h4 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        /* Tracking Section - Improved */
        .tracking-section {
            padding: 60px 0;
            background: white;
        }

        .tracking-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 15px;
            padding: 40px;
            color: white;
            text-align: center;
        }

        .tracking-form-wrapper {
            max-width: 800px;
            margin: 0 auto;
        }

        .tracking-input-group {
            background: white;
            border-radius: 8px;
            padding: 8px 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .tracking-input {
            border: none;
            padding: 8px 0;
            font-size: 0.95rem;
            width: 100%;
            background: transparent;
        }

        .tracking-input:focus {
            outline: none;
        }

        .tracking-input::placeholder {
            color: #adb5bd;
            font-size: 0.9rem;
        }

        .tracking-btn {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tracking-btn:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        .tracking-example {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .tracking-example:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
        }

        /* Partners Section */
        .partners-section {
            padding: 40px 0;
            background: var(--light-bg);
        }

        .partner-logo {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .partner-logo:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .partner-logo i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        /* Stations Section */
        .stations-section {
            padding: 60px 0;
            background: white;
        }

        /* Counties Accordion Styles */
        .counties-accordion {
            max-width: 1400px;
            margin: 0 auto;
        }

        .county-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .county-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .county-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 25px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
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

        /* Subcounties Grid */
        .subcounties-container {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .subcounties-grid {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .subcounty-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid #e9ecef;
        }

        .subcounty-header {
            background: white;
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border-bottom: 1px solid transparent;
        }

        .subcounty-header:hover {
            background: #f8f9fa;
        }

        .subcounty-header.active {
            background: #f0f7f0;
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

        /* Points Grid */
        .points-container {
            padding: 20px;
            background: white;
            border-top: 1px solid #e9ecef;
        }

        .points-grid {
            margin: 0;
        }

        .station-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }

        .station-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 80%;
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
            word-break: break-word;
        }

        .station-card-body i {
            width: 20px;
            display: inline-block;
        }

        .station-card-footer {
            padding: 12px 15px;
            background: var(--light-bg);
            border-top: 1px solid #e9ecef;
        }

        .station-card-footer .badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            background: var(--primary-color);
        }

        /* Blog Section - Pure CSS */
        .blog-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .blog-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e9ecef;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .blog-card a {
            color: inherit;
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .blog-image {
            height: 160px;
            background: linear-gradient(135deg, #e0f2e9, #b8e0d2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: var(--primary-color);
            background-size: cover;
            background-position: center;
        }

        .blog-image i {
            font-size: 3rem;
            color: var(--primary-color);
            opacity: 0.8;
        }

        .blog-content {
            padding: 20px;
        }

        .blog-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.75rem;
        }

        .blog-category {
            background: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .blog-date {
            color: var(--text-light);
        }

        .blog-title {
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            color: var(--text-dark);
        }

        .blog-excerpt {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-bottom: 15px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .blog-footer {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-light);
            border-top: 1px solid #e9ecef;
            padding-top: 10px;
        }

        /* FAQ Section */
        .faq-section {
            padding: 60px 0;
            background: white;
        }

        .faq-item {
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            background: var(--light-bg);
        }

        .faq-question {
            padding: 15px;
            background: var(--light-bg);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-answer {
            padding: 0 15px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: white;
            font-size: 0.9rem;
        }

        .faq-item.active .faq-answer {
            padding: 0 15px 15px 15px;
            max-height: 500px;
        }

        .faq-item.active .faq-question {
            background: var(--primary-color);
            color: white;
        }

        /* Contact Section */
        .contact-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .contact-info {
            padding: 30px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 15px;
            color: white;
            height: 100%;
        }

        .contact-info h5 {
            font-size: 1rem;
            margin-bottom: 2px;
        }

        .contact-info p {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        /* Footer Marketplace & Partners Section */
        .marketplace-partners-section {
            background: linear-gradient(to right, rgba(0, 143, 64, 0.02), rgba(255, 53, 25, 0.02));
            border-radius: 12px;
            padding: 20px;
        }

        .footer-business-link {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 20px;
            background: white;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            border: 1px solid #e9ecef;
        }

        .footer-business-link:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
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
            padding: 8px 12px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .searchable-select-display:hover {
            border-color: var(--primary-color);
        }

        .searchable-select-display i {
            color: var(--text-light);
            font-size: 0.8rem;
            transition: transform 0.3s ease;
        }

        .searchable-select.active .searchable-select-display {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(0, 143, 64, 0.1);
        }

        .searchable-select.active .searchable-select-display i {
            transform: rotate(180deg);
            color: var(--primary-color);
        }

        .searchable-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-top: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 300px;
            overflow: hidden;
        }

        .searchable-select.active .searchable-select-dropdown {
            display: block;
            animation: fadeIn 0.2s ease;
        }

        .search-box {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            font-size: 0.85rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.15rem rgba(0, 143, 64, 0.1);
        }

        .options-list {
            max-height: 250px;
            overflow-y: auto;
            padding: 5px;
        }

        .option-item {
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .option-item:hover {
            background: #f0f7f0;
            color: var(--primary-color);
        }

        .option-item.selected {
            background: var(--primary-color);
            color: white;
        }

        .option-item.selected i {
            color: white;
        }

        .option-item i {
            color: var(--text-light);
            font-size: 0.9rem;
            width: 20px;
        }

        /* No results message */
        .no-results {
            padding: 15px;
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .no-results i {
            display: block;
            font-size: 2rem;
            margin-bottom: 8px;
            opacity: 0.5;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .searchable-select-dropdown {
                position: fixed;
                top: auto;
                left: 15px;
                right: 15px;
                bottom: 15px;
                max-height: 70vh;
                margin-top: 0;
            }

            .options-list {
                max-height: calc(70vh - 70px);
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .marketplace-partners-section .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px !important;
            }

            .footer-business-link {
                width: 100%;
                justify-content: center;
            }
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

        .footer-links h5 {
            color: white;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1rem;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 6px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .county-header {
                padding: 15px;
            }

            .county-header h4 {
                font-size: 1.1rem;
            }

            .county-header .badge {
                padding: 5px 10px;
                font-size: 0.8rem;
            }

            .subcounty-header {
                padding: 12px 15px;
            }

            .subcounty-header h5 {
                font-size: 1rem;
            }

            .points-container {
                padding: 15px;
            }

            .tracking-card {
                padding: 25px;
            }

            .tracking-form-wrapper .row {
                flex-direction: column;
            }

            .tracking-form-wrapper .col-md-5,
            .tracking-form-wrapper .col-md-2 {
                width: 100%;
                margin-bottom: 10px;
            }

            .tracking-btn {
                width: 100%;
            }
        }

        /* Animation for chevron */
        .chevron-icon {
            transition: transform 0.3s ease;
        }

        .county-header.active .chevron-icon,
        .subcounty-header.active .chevron-icon {
            transform: rotate(90deg);
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

            .booking-field.full-width {
                grid-column: span 2;
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
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-content .lead {
                font-size: 1rem;
                margin-bottom: 2rem;
            }

            .compact-booking-engine {
                padding: 15px;
            }

            .booking-form-grid {
                grid-template-columns: 1fr;
            }

            .booking-field.full-width {
                grid-column: span 1;
            }

            .booking-action {
                flex-direction: column;
            }

            .quote-btn,
            .clear-btn {
                width: 100%;
            }

            .contact-form,
            .contact-info {
                padding: 20px;
            }

            .points-grid .col-md-6 {
                width: 100%;
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

        /* Footer Business Links Styles */
        .business-links-section {
            background: linear-gradient(to right, rgba(0, 143, 64, 0.02), rgba(255, 53, 25, 0.02));
            border-radius: 12px;
            padding: 20px;
        }

        .business-link {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 30px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            border: 1px solid #e9ecef;
        }

        .business-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.2);
            border-color: var(--primary-color);
        }

        .business-link-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--accent-color);
            color: white;
            font-size: 0.6rem;
            padding: 2px 6px;
            border-radius: 12px;
            font-weight: 600;
        }

        .business-feature-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            border: 1px solid #e9ecef;
        }

        .business-features-row {
            border: 1px solid #e9ecef;
        }

        /* Navbar adjustments */
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(0, 143, 64, 0.05);
            color: var(--primary-color) !important;
        }

        .navbar-nav .nav-link.active {
            background: rgba(0, 143, 64, 0.1);
            color: var(--primary-color) !important;
        }

        /* Enhanced Searchable Select Styles */
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
            border: 2px solid #e9ecef;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            min-height: 48px;
        }

        .searchable-select-display .selected-text {
            font-weight: 500;
            color: var(--text-dark);
        }

        .searchable-select-display:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }

        .searchable-select.active .searchable-select-display {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            background: white;
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
            border: 1px solid #e9ecef;
            border-radius: 12px;
            margin-top: 4px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
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
            border-bottom: 1px solid #e9ecef;
            position: relative;
            background: #f8f9fa;
        }

        .search-box i {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #dee2e6;
            border-radius: 30px;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.3s ease;
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
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }

        .option-item .town-name {
            font-weight: 600;
            color: var(--text-dark);
            margin-right: 8px;
        }

        .option-item .town-county {
            font-size: 0.75rem;
            background: #f0f0f0;
            padding: 2px 8px;
            border-radius: 12px;
            margin-left: auto;
        }

        .option-item:hover {
            background: #f0f7f0;
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
            width: 24px;
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
            right: 12px;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.9rem;
            pointer-events: none;
        }

        /* Parcel Type Selector */
        .parcel-type-selector select {
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.95rem;
            background-color: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        .parcel-type-selector select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            outline: none;
        }

        /* No results message */
        .no-results {
            padding: 30px 20px;
            text-align: center;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .no-results i {
            display: block;
            font-size: 3rem;
            margin-bottom: 15px;
            color: #dee2e6;
        }

        .no-results p {
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .no-results small {
            color: var(--text-light);
        }

        /* Field labels */
        .booking-field label {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .booking-field label i {
            font-size: 1rem;
        }

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

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .searchable-select-dropdown {
                position: fixed;
                top: 50%;
                left: 15px;
                right: 15px;
                transform: translateY(-50%);
                max-height: 70vh;
                margin-top: 0;
                z-index: 1050;
            }

            .options-list {
                max-height: calc(70vh - 70px);
            }

            .searchable-select.active .searchable-select-dropdown {
                animation: fadeInUp 0.3s ease;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px) translateY(-50%);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) translateY(-50%);
                }
            }
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .business-links-section .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 10px !important;
            }

            .business-link {
                width: 100%;
            }

            .business-features-row .col-md-4 {
                margin-bottom: 15px;
            }
        }
    </style>

    <!-- WhatsApp Button with Tooltip -->
    @php
    $phone = config('services.whatsapp.phone', '254700130759');
    $message = config('services.whatsapp.message', 'Hello, I need more information about your services');
    $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($message);
    @endphp

    <div class="whatsapp-wrapper">
        <span class="whatsapp-tooltip">Need more info? Chat with us!</span>
        <a href="{{ $whatsappUrl }}"
            target="_blank"
            class="whatsapp-button"
            title="Contact us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <style>
        /* WhatsApp Button Styles */
        .whatsapp-wrapper {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .whatsapp-button {
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            line-height: 60px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .whatsapp-button:hover {
            background-color: #128C7E;
            transform: scale(1.1);
            color: white;
            text-decoration: none;
            box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
        }

        .whatsapp-button i {
            font-size: 32px;
        }

        .whatsapp-tooltip {
            position: absolute;
            right: 70px;
            top: 15px;
            background-color: #333;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
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

        .whatsapp-wrapper:hover .whatsapp-tooltip {
            opacity: 1;
            visibility: visible;
            right: 80px;
        }

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

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .whatsapp-wrapper {
                bottom: 20px;
                right: 20px;
            }

            .whatsapp-button {
                width: 50px;
                height: 50px;
            }

            .whatsapp-button i {
                font-size: 26px;
            }

            .whatsapp-tooltip {
                display: none;
            }
        }

        /* Optional: Add a subtle glow effect on hover */
        .whatsapp-button:hover {
            filter: brightness(1.1);
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize searchable selects
            initSearchableSelects();

            function initSearchableSelects() {
                // Toggle dropdown on display click
                $('.searchable-select-display').on('click', function(e) {
                    e.stopPropagation();
                    const select = $(this).closest('.searchable-select');

                    // Close all other dropdowns
                    $('.searchable-select').not(select).removeClass('active');

                    // Toggle current dropdown
                    select.toggleClass('active');

                    // Focus search input when dropdown opens
                    if (select.hasClass('active')) {
                        select.find('.search-input').focus();
                    }
                });

                // Search functionality for From Town
                $('#fromTownSearch').on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    filterOptions('#fromTownOptions', searchTerm);
                });

                // Search functionality for To Town
                $('#toTownSearch').on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    filterOptions('#toTownOptions', searchTerm);
                });

                // Select option for From Town
                $('#fromTownOptions').on('click', '.option-item', function() {
                    const value = $(this).data('value');
                    const townName = $(this).find('.town-name').text();
                    const county = $(this).find('.town-county').text();

                    // Update hidden input
                    $('#fromTown').val(value);

                    // Update display text with town and county
                    $('#fromTownSelect .selected-text').text(`${townName} (${county})`);

                    // Update selected state
                    $('#fromTownOptions .option-item').removeClass('selected');
                    $(this).addClass('selected');

                    // Close dropdown
                    $('#fromTownSelect').closest('.searchable-select').removeClass('active');

                    // Clear search
                    $('#fromTownSearch').val('');
                    $('#fromTownOptions .option-item').show();
                });

                // Select option for To Town
                $('#toTownOptions').on('click', '.option-item', function() {
                    const value = $(this).data('value');
                    const townName = $(this).find('.town-name').text();
                    const county = $(this).find('.town-county').text();

                    // Update hidden input
                    $('#toTown').val(value);

                    // Update display text with town and county
                    $('#toTownSelect .selected-text').text(`${townName} (${county})`);

                    // Update selected state
                    $('#toTownOptions .option-item').removeClass('selected');
                    $(this).addClass('selected');

                    // Close dropdown
                    $('#toTownSelect').closest('.searchable-select').removeClass('active');

                    // Clear search
                    $('#toTownSearch').val('');
                    $('#toTownOptions .option-item').show();
                });

                // Close dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!$(e.target).closest('.searchable-select').length) {
                        $('.searchable-select').removeClass('active');
                    }
                });

                // Prevent dropdown from closing when clicking inside
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

                // Show no results message if needed
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

            // Form validation
            function validateTownForm() {
                const fromTown = $('#fromTown').val();
                const toTown = $('#toTown').val();
                const weight = $('#weight').val();
                const parcelType = $('#parcelType').val();

                if (!fromTown) {
                    showAlert('Please select pickup town', 'warning');
                    $('#fromTownSelect').addClass('error');
                    return false;
                }

                if (!toTown) {
                    showAlert('Please select delivery town', 'warning');
                    $('#toTownSelect').addClass('error');
                    return false;
                }

                if (!weight || weight <= 0) {
                    showAlert('Please enter a valid weight', 'warning');
                    $('#weight').focus();
                    return false;
                }

                if (!parcelType) {
                    showAlert('Please select parcel type', 'warning');
                    $('#parcelType').focus();
                    return false;
                }

                if (fromTown === toTown) {
                    showAlert('Pickup and delivery towns cannot be the same', 'warning');
                    return false;
                }

                return true;
            }

            // Quote calculation with parcel type
            function calculateTownQuote() {
                const fromTown = $('#fromTown').val();
                const toTown = $('#toTown').val();
                const weight = parseFloat($('#weight').val());
                const parcelType = $('#parcelType').val();

                // Base price
                let basePrice = 300;

                // Add distance factor
                const majorRoutes = [
                    ['Nairobi', 'Mombasa'],
                    ['Mombasa', 'Nairobi'],
                    ['Nairobi', 'Kisumu'],
                    ['Kisumu', 'Nairobi'],
                    ['Nairobi', 'Nakuru'],
                    ['Nakuru', 'Nairobi'],
                    ['Nairobi', 'Eldoret'],
                    ['Eldoret', 'Nairobi']
                ];

                const isLongDistance = majorRoutes.some(route =>
                    (route[0] === fromTown && route[1] === toTown) ||
                    (route[0] === toTown && route[1] === fromTown)
                );

                if (isLongDistance) {
                    basePrice += 500;
                } else if (fromTown !== toTown) {
                    basePrice += 200;
                }

                // Parcel type multiplier
                const typeMultipliers = {
                    'document': 1,
                    'small': 1.2,
                    'medium': 1.5,
                    'large': 2,
                    'extra-large': 2.5,
                    'fragile': 2.2,
                    'electronics': 2,
                    'perishable': 2.3
                };

                const multiplier = typeMultipliers[parcelType] || 1;

                // Weight charge
                let weightCharge = 0;
                if (weight <= 1) {
                    weightCharge = 0;
                } else if (weight <= 5) {
                    weightCharge = 200;
                } else if (weight <= 10) {
                    weightCharge = 500;
                } else {
                    weightCharge = 1000 + (Math.ceil(weight - 10) * 50);
                }

                // Calculate total with multiplier
                const subtotal = (basePrice + weightCharge) * multiplier;
                const tax = subtotal * 0.16;
                const total = Math.round(subtotal + tax);

                return {
                    base: basePrice,
                    weight: weightCharge,
                    multiplier: multiplier,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    weight: weight,
                    parcelType: parcelType
                };
            }

            // Form submission
            $('#townQuoteForm').on('submit', function(e) {
                e.preventDefault();

                if (!validateTownForm()) {
                    return;
                }

                const quote = calculateTownQuote();
                displayTownQuoteResult(quote);
            });

            // Display quote result
            function displayTownQuoteResult(quote) {
                const fromTown = $('#fromTownSelect .selected-text').text();
                const toTown = $('#toTownSelect .selected-text').text();
                const parcelType = $('#parcelType option:selected').text();

                const quoteHTML = `
            <div class="quote-result-content">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="mb-1">Estimated Quote</h6>
                        <p class="mb-0 small text-muted">${fromTown} → ${toTown}</p>
                        <p class="mb-0 small text-muted">${parcelType} | ${quote.weight} kg</p>
                    </div>
                    <span class="quote-amount">KES ${quote.total}</span>
                </div>
                
                <div class="price-breakdown small mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Base delivery:</span>
                        <span>KES ${quote.base}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Weight charge:</span>
                        <span>KES ${quote.weight}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Type multiplier:</span>
                        <span>${quote.multiplier}x</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">VAT (16%):</span>
                        <span>KES ${Math.round(quote.tax)}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold pt-2 border-top">
                        <span>Total:</span>
                        <span>KES ${quote.total}</span>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm flex-grow-1" id="proceedBooking">
                        Proceed to Booking <i class="bi bi-arrow-right"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" id="closeQuote">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                
                <p class="small text-muted mt-2 mb-0">
                    <i class="bi bi-info-circle"></i> 
                    Final price may vary based on actual measurements.
                </p>
            </div>
        `;

                $('#quoteResult').html(quoteHTML).addClass('show');

                $('#closeQuote').on('click', function() {
                    $('#quoteResult').removeClass('show').empty();
                });

                $('#proceedBooking').on('click', function() {
                    alert('Proceed to complete your booking with KES ' + quote.total);
                });
            }

            // Clear form
            $('#clearForm').on('click', function() {
                $('#townQuoteForm')[0].reset();
                $('#fromTown').val('');
                $('#toTown').val('');
                $('#fromTownSelect .selected-text').text('Select pickup town');
                $('#toTownSelect .selected-text').text('Select delivery town');
                $('#fromTownOptions .option-item').removeClass('selected');
                $('#toTownOptions .option-item').removeClass('selected');
                $('#quoteResult').removeClass('show').empty();
            });

            // Show alert function
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
        });
    </script>

    <!-- Make sure Font Awesome is included in your layout -->
    <script>
        // Optional: Track WhatsApp clicks
        document.addEventListener('DOMContentLoaded', function() {
            const whatsappBtn = document.querySelector('.whatsapp-button');
            if (whatsappBtn) {
                whatsappBtn.addEventListener('click', function() {
                    // Track with Google Analytics if available
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'whatsapp_click', {
                            'event_category': 'engagement',
                            'event_label': 'whatsapp_chat',
                            'value': 1
                        });
                    }

                    // Optional: Send to your analytics endpoint
                    fetch('/track-whatsapp-click', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

    <!-- <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Mar 27, 2026:
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            uploadcare_public_key: 'ed7312cf0958d607cbc6',
        });
    </script> -->
    <textarea>
  Welcome to TinyMCE!
</textarea>

</body>

</html>