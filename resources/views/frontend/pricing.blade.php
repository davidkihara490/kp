<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tariff | Karibu Parcels</title>
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
        .tariff-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 60px 0;
            color: white;
            text-align: center;
            margin-bottom: 50px;
        }
        .tariff-hero h1 {
            font-weight: 800;
            font-size: 2.8rem;
            margin-bottom: 15px;
        }
        .tariff-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Tariff Card */
        .tariff-card {
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow-lg);
            padding: 30px;
            margin-bottom: 50px;
            overflow-x: auto;
        }
        
        .tariff-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
        }
        
        .tariff-table th {
            background: var(--primary-color);
            color: white;
            padding: 12px 8px;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            border: none;
        }
        
        .tariff-table td {
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }
        
        .tariff-table tr:hover td {
            background-color: var(--primary-light);
        }
        
        .source-cell {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--primary-color);
            position: sticky;
            left: 0;
            background-color: white;
            border-right: 2px solid var(--primary-color);
        }
        
        .price-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .base-price {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .extra-price {
            background-color: #fff3e0;
            color: #ff9800;
            font-size: 0.75rem;
            margin-top: 4px;
        }
        
        .price-cell {
            min-width: 80px;
        }
        
        .zone-name {
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .table-wrapper {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }
        
        /* Filter Section */
        .filter-section {
            background: var(--light-bg);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        
        .form-select, .form-control {
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        
        .btn-filter {
            background: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 10px 25px;
            margin-top: 28px;
        }
        
        .btn-filter:hover {
            background: var(--primary-dark);
            color: white;
        }
        
        .btn-reset {
            background: #6c757d;
            color: white;
            border-radius: 12px;
            padding: 10px 25px;
            margin-top: 28px;
        }
        
        .btn-reset:hover {
            background: #5a6268;
            color: white;
        }
        
        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--border-color);
            margin-bottom: 25px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary-color);
        }
        
        .stats-label {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 5px;
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
            .tariff-hero h1 { font-size: 2rem; }
            .tariff-card { padding: 15px; }
            .price-cell { min-width: 70px; }
            .zone-name { font-size: 0.75rem; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img" onerror="this.src='https://placehold.co/45x45?text=KP'">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">Karibu Parcels</span>
                    <small class="d-block text-muted" style="font-size: 0.75rem;">Send and receive parcels to/from town near you</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#tracking">Tracking</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('pricing') }}">Tariff</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#stations">PickUp/DropOff Points</a></li>
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
    <div class="tariff-hero">
        <div class="container">
            <h1><i class="bi bi-table me-2"></i> Delivery Tariff</h1>
            <p>Nationwide delivery pricing for 0-5kg base rate and additional per kg charges across 16 delivery zones</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $zones->count() }}</div>
                    <div class="stats-label">Delivery Zones</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $pricingItems->count() }}</div>
                    <div class="stats-label">Pricing Routes</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">0-5 kg</div>
                    <div class="stats-label">Base Weight</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-number">40-90</div>
                    <div class="stats-label">Extra/kg Rate (KSh)</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <!-- <form method="GET" action="{{ route('pricing') }}" class="filter-section">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label"><i class="fas fa-search text-primary me-1"></i> Search Zones</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search by zone name..."
                           value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-box text-primary me-1"></i> Filter by Item</label>
                    <select name="item_filter" class="form-select">
                        <option value="">All Items</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ $itemFilter == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fas fa-location-dot text-primary me-1"></i> Filter by Zone</label>
                    <select name="zone_filter" class="form-select">
                        <option value="">All Zones</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" {{ $zoneFilter == $zone->id ? 'selected' : '' }}>
                                {{ $zone->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter w-100">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                    @if($search || $itemFilter || $zoneFilter)
                        <a href="{{ route('pricing') }}" class="btn btn-reset w-100 mt-2">
                            <i class="fas fa-undo-alt me-1"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form> -->

        <!-- Tariff Table -->
        <div class="tariff-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                <h4 class="mb-2"><i class="fas fa-route text-primary me-2"></i> Delivery Pricing Matrix</h4>
                <div class="d-flex gap-2">
                    <span class="badge bg-success"><i class="fas fa-weight-hanging me-1"></i> 0-5 kg: Base Price</span>
                    <span class="badge bg-warning text-dark"><i class="fas fa-plus-circle me-1"></i> Extra/kg</span>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table class="tariff-table table table-hover">
                    <thead>
                        <tr>
                            <th style="min-width: 180px; background: var(--primary-dark);">
                                <div>
                                    <i class="fas fa-truck me-1"></i> FROM / TO
                                </div>
                                <div class="small mt-1 opacity-75">
                                    <i class="fas fa-weight-hanging me-1"></i>Base (0-5kg) / <i class="fas fa-plus-circle me-1"></i>Extra/kg
                                </div>
                            </th>
                            @foreach($zones as $destination)
                                <th class="text-center" style="min-width: 100px;">
                                    <div class="zone-name">{{ $destination->name }}</div>
                                    <div class="small mt-1 opacity-75">KSh (Base / Extra)</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $source)
                            <tr>
                                <td class="source-cell fw-bold bg-light">
                                    <i class="fas fa-flag-checkered text-primary me-2"></i>
                                    {{ $source->name }}
                                </td>
                                @foreach($zones as $destination)
                                    @php
                                        $pricing = $pricingItems->where('source_zone_id', $source->id)
                                            ->where('destination_zone_id', $destination->id)
                                            ->first();
                                    @endphp
                                    <td class="price-cell text-center">
                                        @if($pricing)
                                            <div>
                                                <span class="price-badge base-price">
                                                    
                                                    KSH {{ number_format($pricing->cost) }}
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <span class="price-badge extra-price">
                                                    KSH {{ number_format($pricing->extra) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($pricingItems->count() == 0)
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No pricing data found matching your filters. Please try different criteria or reset the filters.
                </div>
            @endif
            
            <div class="alert alert-info mt-3 mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Pricing Guide:</strong> First row shows base price for 0-5kg. Second row shows additional charge per kg beyond 5kg.
                <br><small class="text-muted">* Prices are in Kenyan Shillings (KSh) and subject to change. Contact customer service for bulk discounts.</small>
            </div>
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
                        <li><a href="{{ route('pricing') }}">Delivery Tariff</a></li>
                        <li><a href="{{ url('/terms') }}">Terms & Conditions</a></li>
                        <li><a href="{{ url('/privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Resources</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#faq">FAQs</a></li>
                        <li><a href="{{ url('/') }}#contact">Contact</a></li>
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
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="business-buttons d-flex gap-3 flex-wrap justify-content-lg-end">
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
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if(this.getAttribute('href') !== '#') {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if(targetId === '#') return;
                    if(!targetId.startsWith('#')) return;
                    window.location.href = '/' + targetId;
                }
            });
        });
    </script>
</body>
</html>