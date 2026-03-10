<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karibu Parcels - Professional Courier Service</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/f2.css') }}">
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
                <ul class="navbar-nav ms-auto">
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
                    <li class="nav-item ms-2">
                        <a target="_blank" href="{{ route('marketplace') }}" class="btn btn-success">
                            Marketplace
                        </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="{{ route('partners.login') }}" class="btn btn-success">
                            Partners
                        </a>
                    </li>
                </ul>
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
                <div class="tab-content" id="bookingTabContent">
                    <!-- Town to Town Tab -->
                    <div class="tab-pane fade show active" id="town" role="tabpanel" aria-labelledby="town-tab">
                        <div class="booking-header">
                            <h6 class="text-muted mb-3">Get an instant quote for shipments between Kenyan towns</h6>
                        </div>

                        <form id="townQuoteForm">
                            <div class="booking-form-grid">
                                <!-- From Town -->
                                <div class="booking-field">
                                    <label>From Town</label>
                                    <select class="form-select compact-select" id="fromTown" required>
                                        <option value="">Select Town</option>
                                        @foreach($towns as $town)
                                        <option value="{{ $town->name }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- To Town -->
                                <div class="booking-field">
                                    <label>To Town</label>
                                    <select class="form-select compact-select" id="toTown" required>
                                        <option value="">Select Town</option>
                                        @foreach($towns as $town)
                                        <option value="{{ $town->name }}">{{ $town->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Weight -->
                                <div class="booking-field">
                                    <label>Weight (kg)</label>
                                    <input type="number" class="form-control compact-select" id="weight" placeholder="e.g., 5" min="0.1" step="0.1" required>
                                </div>

                                <!-- Item Description - Full Width -->
                                <div class="booking-field full-width">
                                    <label>Item Description</label>
                                    <textarea class="form-control compact-select" id="itemDescription" rows="2" placeholder="Describe your item (e.g., Documents, Electronics, Clothing, etc.)..." required></textarea>
                                </div>
                            </div>

                            <div class="booking-action">
                                <button type="submit" class="btn btn-primary quote-btn">
                                    <i class="bi bi-calculator me-2"></i> Get a Quote
                                </button>
                                <button type="button" class="btn btn-outline-secondary clear-btn" id="clearForm">
                                    <i class="bi bi-arrow-clockwise me-2"></i> Clear
                                </button>
                            </div>
                        </form>

                        <div id="quoteResult" class="compact-quote-result"></div>
                    </div>

                    <!-- International Tab -->
                    <div class="tab-pane fade" id="international" role="tabpanel" aria-labelledby="international-tab">
                        <div class="international-coming-soon">
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-globe2" style="font-size: 4rem; color: var(--primary-color);"></i>
                                </div>
                                <h5 class="mb-2">International Shipping Coming Soon!</h5>
                                <p class="text-muted mb-3">We're expanding our services to serve you better. International shipping will be available shortly.</p>
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="alert alert-info py-2 mb-3">
                                            <i class="bi bi-info-circle me-2"></i>
                                            Get notified when we launch
                                        </div>
                                        <div class="input-group">
                                            <input type="email" class="form-control form-control-sm" placeholder="Enter your email">
                                            <button class="btn btn-primary btn-sm" type="button">Notify Me</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <div class="brand-text d-none d-md-block">
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
                        <li><a href="#">Partners</a></li>
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

            <hr class="mt-4 mb-4">

            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
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
                    alert('Booking functionality will be implemented here. Quote: KES ' + quote.total);
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
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .booking-field.full-width {
            grid-column: span 3;
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
    </style>
</body>

</html>