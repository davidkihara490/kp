<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | Karibu Parcels</title>
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
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-lg: 0 10px 15px rgba(0,0,0,0.05);
            --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--light-bg);
            padding-top: 80px;
        }

        /* Navigation - matching main site */
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
        .terms-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .terms-hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        .terms-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Terms Card */
        .terms-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 40px;
            margin-bottom: 50px;
            border: 1px solid var(--border-color);
        }
        .terms-card h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            padding-left: 18px;
        }
        .terms-card h2:first-of-type {
            margin-top: 0;
        }
        .terms-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
        }
        .terms-card p {
            font-size: 1rem;
            line-height: 1.6;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        .terms-card ul, .terms-card ol {
            margin-bottom: 1.2rem;
            padding-left: 1.5rem;
        }
        .terms-card li {
            margin-bottom: 0.5rem;
            color: var(--text-light);
            line-height: 1.6;
        }
        .terms-card .highlight-box {
            background: var(--primary-light);
            border-left: 4px solid var(--primary-color);
            padding: 20px 25px;
            border-radius: 16px;
            margin: 25px 0;
        }
        .terms-card .last-updated {
            background: var(--light-bg);
            padding: 12px 20px;
            border-radius: 40px;
            display: inline-block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 30px;
        }
        .terms-card .last-updated i {
            color: var(--primary-color);
            margin-right: 6px;
        }

        /* Footer - matching main site */
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
            position: relative;
            overflow: hidden;
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
            .terms-hero h1 { font-size: 2rem; }
            .terms-card { padding: 25px; }
            .terms-card h2 { font-size: 1.4rem; }
        }
    </style>
</head>
<body>

    <!-- Navigation (identical to main site) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img" onerror="this.src='https://placehold.co/45x45?text=KP'">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">Karibu Parcels</span>
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#stations">PickUp/DropOff Points</a></li>
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
    <div class="terms-hero">
        <div class="container">
            <h1><i class="bi bi-file-text-fill me-2"></i> Terms & Conditions</h1>
            <p>Please read these terms carefully before using our courier services. By accessing or using Karibu Parcels, you agree to be bound by these terms.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="terms-card">
            <div class="last-updated">
                <i class="bi bi-calendar-check"></i> Last Updated: {{ $terms->updated_at->format('Y-M-d') }}
            </div>

            <p>
                {!! $terms->content !!}
            </p>

            <div class="highlight-box mt-4">
                <i class="bi bi-shield-check me-2" style="color: var(--primary-color);"></i>
                By using Karibu Parcels services, you confirm that you have read, understood, and agreed to these Terms and Conditions. Thank you for trusting us with your deliveries.
            </div>
        </div>
    </div>

    <!-- Footer - Matching main site -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3">
                        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="color:white;">
                            <div class="logo-container me-2">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img" onerror="this.src='https://placehold.co/45x45?text=KP'">
                            </div>
                            <div class="brand-text">
                                <span class="fw-bold fs-5">Karibu Parcels</span>
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
                        <li><a href="{{ url('/') }}#blogs">Blog</a></li>
                        <li><a href="{{ url('/') }}#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#faq">FAQs</a></li>
                        <li><a href="{{ url('/terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Legal</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/terms') }}">Terms of Service</a></li>
                        <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Business Solutions Section -->
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
                            <a href="{{ route('marketplace') }}" target="_blank" class="business-btn marketplace-btn">
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll for anchor links that reference main page sections (optional)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if(this.getAttribute('href') !== '#') {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if(targetId === '#') return;
                    // if linking to main page sections, but we are on terms page, we redirect to home with hash
                    if(!targetId.startsWith('#')) return;
                    window.location.href = '/' + targetId;
                }
            });
        });
    </script>
</body>
</html>