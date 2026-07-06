<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Tariff | Karibu Parcels</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('logo.jpeg') }}"> <!-- jQuery -->

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
        .tariff-hero {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 40px 0;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .tariff-hero h1 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .tariff-hero p {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        /* Tariff Card */
        .tariff-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 20px;
            margin-bottom: 30px;
            overflow-x: auto;
        }

        .tariff-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }

        .tariff-table th {
            background: var(--primary-color);
            color: white;
            padding: 8px 6px;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.75rem;
        }

        .tariff-table td {
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid var(--border-color);
            font-size: 0.75rem;
        }

        .tariff-table tr:hover td {
            background-color: var(--primary-light);
        }

        .source-cell {
            background-color: #f8f9fa;
            font-weight: 700;
            color: var(--primary-color);
            position: sticky;
            left: 0;
            background-color: white;
            border-right: 2px solid var(--primary-color);
            white-space: nowrap;
            font-size: 0.75rem;
        }

        .price-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.7rem;
        }

        .base-price {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .extra-price {
            background-color: #fff3e0;
            color: #ff9800;
            font-size: 0.65rem;
        }

        .price-cell {
            padding: 6px 4px !important;
        }

        .zone-name {
            font-weight: 600;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
        }

        /* Zones & Towns Table */
        .zones-section {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            padding: 20px;
            margin-bottom: 30px;
        }

        .zones-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.75rem;
        }

        .zones-table th {
            background: var(--primary-dark);
            color: white;
            padding: 8px 12px;
            font-weight: 600;
            text-align: left;
            font-size: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .zones-table td {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            vertical-align: top;
            font-size: 0.75rem;
        }

        .zones-table tr:hover td {
            background-color: var(--primary-light);
        }

        .zone-name-cell {
            font-weight: 700;
            color: var(--primary-color);
            width: 25%;
            background-color: #f8f9fa;
        }

        .towns-cell {
            color: var(--text-light);
            line-height: 1.5;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .stats-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
        }

        .stats-label {
            font-size: 0.7rem;
            color: var(--text-light);
            margin-top: 5px;
        }

        /* Download Button - Small at Bottom */
        .download-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-download-small {
            background: #dc3545;
            color: white;
            border-radius: 8px;
            padding: 6px 15px;
            font-size: 0.8rem;
            font-weight: 500;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-download-small:hover {
            background: #c82333;
            color: white;
            transform: translateY(-1px);
        }

        /* PDF Print Styles */
        @media print {
            body {
                padding-top: 0;
                background: white;
            }

            .navbar,
            .tariff-hero,
            footer,
            .download-section,
            .business-solutions {
                display: none !important;
            }

            .tariff-card,
            .zones-section {
                box-shadow: none;
                padding: 0;
                margin: 0;
                page-break-inside: avoid;
            }

            .tariff-table {
                font-size: 0.7rem;
            }

            .tariff-table th,
            .tariff-table td {
                padding: 4px;
            }

            .container {
                max-width: 100%;
                padding: 0;
                margin: 0;
            }
        }

        /* Footer */
        footer {
            background: var(--dark-bg);
            color: white;
            padding: 40px 0 20px;
            position: relative;
            margin-top: 20px;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.8rem;
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
            margin-right: 5px;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
        }

        .business-solutions {
            background: linear-gradient(to right, var(--primary-light), white);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--border-color);
        }

        .business-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 20px;
            border-radius: 40px;
            text-decoration: none;
            transition: var(--transition);
            font-size: 0.85rem;
        }

        .partner-btn {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .business-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.2);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }

            .tariff-hero h1 {
                font-size: 1.8rem;
            }

            .tariff-card {
                padding: 10px;
            }

            .tariff-table {
                font-size: 0.65rem;
            }

            .tariff-table th,
            .tariff-table td {
                padding: 4px 2px;
            }

            .zone-name {
                font-size: 0.6rem;
            }

            .zones-table th,
            .zones-table td {
                padding: 6px 8px;
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <div class="logo-container me-2">
                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="40" class="logo-img" onerror="this.src='https://placehold.co/40x40?text=KP'">
                </div>
                <div class="brand-text d-none d-md-block">
                    <span class="fw-bold fs-5">Karibu Parcels</span>
                    <small class="d-block text-muted" style="font-size: 0.7rem;">Send and receive parcels to/from town near you</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#tracking">Tracking</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#stations">PickUp/DropOff Points</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs.index') }}">Blog</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}#contact">Contact</a></li>
                </ul>

                <!-- <ul class="navbar-nav mx-auto">
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
                        <a class="nav-link" href="{{ route('pricing') }}">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul> -->


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
            <p>Nationwide delivery pricing for 0-5kg base rate and additional per kg charges across delivery zones</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-3">
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $zones->count() }}</div>
                    <div class="stats-label">Delivery Zones</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">{{ $pricingItems->count() }}</div>
                    <div class="stats-label">Pricing Routes</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">0-5 kg</div>
                    <div class="stats-label">Base Weight</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stats-card">
                    <div class="stats-number">40-90</div>
                    <div class="stats-label">Extra/kg (KSh)</div>
                </div>
            </div>
        </div>

        <!-- Tariff Table -->
        <div class="tariff-card" id="tariffContent">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                <h5 class="mb-1"><i class="fas fa-route text-primary me-2"></i> Delivery Pricing Tarrifs</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-success" style="font-size: 0.65rem;"><i class="fas fa-weight-hanging me-1"></i> 0-5kg: Base</span>
                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;"><i class="fas fa-plus-circle me-1"></i> Extra/kg</span>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="tariff-table" id="pricingTable">
                    <thead>
                        <tr>
                            <th style="background: var(--primary-dark);">
                                <div>FROM / TO</div>
                                <div class="small">Base / Extra</div>
                            </th>
                            @foreach($zones as $destination)
                            <th class="text-center">
                                <div class="zone-name">{{ $destination->name }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $source)
                        <tr>
                            <td class="source-cell">
                                <i class="fas fa-flag-checkered text-primary me-1"></i>
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
                                        KSh {{ number_format($pricing->cost) }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <span class="price-badge extra-price">
                                        +{{ number_format($pricing->extra) }}/kg
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

            <div class="alert alert-info mt-2 mb-0" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Guide:</strong> Base price for 0-5kg | Extra charge per kg beyond 5kg.
                <small class="text-muted">* Prices in KSh, subject to change.</small>
            </div>

            <div class="alert alert-info mt-2 mb-0" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Note:</strong> VAT 16%.
                <small class="text-muted">* Prices are subject to 16% VAT.</small>
            </div>
        </div>

        <!-- Zones and Towns Table -->
        <div class="zones-section" id="zonesContent">
            <h5 class="mb-3"><i class="fas fa-map-marked-alt text-primary me-2"></i> Zones and Covered Towns</h5>
            <div class="table-wrapper">
                <table class="zones-table">
                    <thead>
                        <tr>
                            <th>Zone Name</th>
                            <th>Towns / Locations Covered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($zones as $zone)
                        <tr>
                            <td class="zone-name-cell">
                                <i class="fas fa-location-dot text-primary me-1"></i>
                                {{ $zone->name }}
                            </td>
                            <td class="towns-cell">
                                @php
                                $t = [];
                                $towns = $zone->towns;
                                foreach($towns as $town){
                                $t[] = $town->town->name;
                                }
                                @endphp


                                @if(count($towns) > 0)
                                {{ implode(', ', $t) }}
                                @else
                                <span class="text-muted">Main city center, Surrounding areas</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="alert alert-secondary mt-2" style="font-size: 0.7rem; padding: 8px 12px;">
                <i class="fas fa-info-circle me-1"></i>
                <strong>Service Coverage:</strong> We deliver to all major towns and cities within each zone.
            </div>
        </div>

        <!-- Download Button - Small at Bottom -->
        <div class="download-section">
            <a target="_blank" class="btn btn-success" href="{{ route('pricing.download') }}">Download Tariff PDF</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <h4 class="mb-2">
                        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="color:white;">
                            <div class="logo-container me-2">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="35" class="logo-img" onerror="this.src='https://placehold.co/35x35?text=KP'">
                            </div>
                            <div class="brand-text">
                                <span class="fw-bold fs-5">Karibu Parcels</span>
                                <small class="d-block text-muted" style="font-size: 0.7rem;">Professional Courier Service</small>
                            </div>
                        </a>
                    </h4>
                    <p class="opacity-75" style="font-size: 0.8rem;">Your trusted partner for fast, reliable, and secure courier services across Kenya.</p>
                    <div class="social-icons">
                        <a target="_blank" href="https://www.facebook.com/karibuparcels"><i class="bi bi-facebook"></i></a>
                        <a target="_blank" href="https://www.instagram.com/karibuparcels/"><i class="bi bi-instagram"></i></a>
                        <a target="_blank" href="https://www.tiktok.com/@karibuparcels"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <h5 style="font-size: 0.9rem;">Services</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#features" style="font-size: 0.75rem;">Town to Town Sending</a></li>
                        <li><a href="{{ url('/') }}#features" style="font-size: 0.75rem;">Parcel Receiving</a></li>
                        <li><a href="{{ url('/') }}#features" style="font-size: 0.75rem;">Forwarding Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <h5 style="font-size: 0.9rem;">Company</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('pricing') }}" style="font-size: 0.75rem;">Delivery Tariff</a></li>
                        <li><a href="{{ url('/terms') }}" style="font-size: 0.75rem;">Terms & Conditions</a></li>
                        <li><a href="{{ url('/privacy') }}" style="font-size: 0.75rem;">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <h5 style="font-size: 0.9rem;">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}#faq" style="font-size: 0.75rem;">FAQs</a></li>
                        <li><a href="{{ url('/') }}#contact" style="font-size: 0.75rem;">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-3">
                    <h5 style="font-size: 0.9rem;">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/terms') }}" style="font-size: 0.75rem;">Terms of Service</a></li>
                        <li><a href="{{ url('/privacy') }}" style="font-size: 0.75rem;">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Business Solutions Section -->
            <div class="business-solutions mt-3 pt-3 border-top">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="business-solutions-text">
                            <span class="badge bg-primary mb-1" style="font-size: 0.65rem;">BUSINESS SOLUTIONS</span>
                            <h5 class="mb-0">Grow Your Business With Karibu Parcels</h5>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="business-buttons d-flex gap-2 flex-wrap justify-content-lg-end">
                            <a href="{{ route('partners.login') }}" class="business-btn partner-btn" style="font-size: 0.8rem; padding: 6px 18px;">
                                <span class="btn-icon"><i class="bi bi-briefcase"></i></span>
                                <span class="btn-text"><small>Partner Portal</small><strong>Login</strong></span>
                                <span class="btn-arrow"><i class="bi bi-box-arrow-in-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-3 mb-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0" style="font-size: 0.7rem;">&copy; {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 small text-muted" style="font-size: 0.7rem;">
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
    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

</body>

</html>