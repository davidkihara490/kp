<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karibu Parcels - Partner Marketplace</title>

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
            background-color: var(--light-bg);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .logo-icon {
            color: var(--primary-color);
            margin-right: 10px;
        }

        .logo-container {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            padding: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .main-content {
            padding: 20px 0;
            min-height: calc(100vh - 76px);
        }

        /* Delivery Row Styles */
        .delivery-row {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
            overflow: hidden;
            min-height: 100px;
            display: flex;
            align-items: center;
        }

        .delivery-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
            border-color: var(--primary-color);
        }

        .delivery-row.priority {
            border-left: 4px solid var(--accent-color);
        }

        .delivery-row.express {
            border-left: 4px solid var(--accent-color);
        }

        .delivery-row.standard {
            border-left: 4px solid var(--primary-color);
        }

        .delivery-row.economy {
            border-left: 4px solid #6c757d;
        }

        .delivery-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            background: rgba(0, 143, 64, 0.02);
            flex-shrink: 0;
            min-width: 200px;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .status-available {
            background: rgba(0, 143, 64, 0.1);
            color: var(--primary-dark);
            border: 1px solid rgba(0, 143, 64, 0.2);
        }

        .price-tag {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 3px 8px rgba(0, 143, 64, 0.2);
            display: inline-block;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .stats-card {
            text-align: center;
            padding: 20px 15px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
        }

        .stats-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1;
        }

        .stats-label {
            color: var(--text-light);
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .page-link {
            border: none;
            color: var(--primary-color);
            margin: 0 3px;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 600;
            background: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 10px rgba(0, 143, 64, 0.3);
        }

        .btn-accept {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            box-shadow: 0 3px 8px rgba(0, 143, 64, 0.2);
            white-space: nowrap;
        }

        .btn-accept:hover {
            background: linear-gradient(135deg, var(--primary-dark), #00662e);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 143, 64, 0.3);
        }

        .btn-view {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-view:hover {
            background: rgba(0, 143, 64, 0.05);
            color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 143, 64, 0.1);
        }

        .route-indicator {
            height: 60px;
            min-width: 100px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .route-line {
            position: absolute;
            height: 2px;
            width: 80%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            z-index: 1;
        }

        .route-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
            border: 2px solid var(--primary-color);
            z-index: 2;
        }

        .route-dot.start {
            left: 0;
        }

        .route-dot.end {
            right: 0;
            border-color: var(--accent-color);
        }

        .route-dot.moving {
            left: 50%;
            transform: translateX(-50%);
            animation: moveDot 2s linear infinite;
            background: var(--primary-color);
        }

        @keyframes moveDot {
            0% {
                left: 0;
            }

            100% {
                left: 100%;
            }
        }

        .alert-marketplace {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.2);
        }

        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 0;
            padding: 20px 25px;
        }

        .close {
            color: white;
            opacity: 0.9;
            font-size: 1.2rem;
        }

        .form-select,
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            height: 42px;
        }

        .form-select:focus,
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 3px 8px rgba(0, 143, 64, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), #00662e);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 143, 64, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }

        .user-profile {
            background: rgba(0, 143, 64, 0.1);
            padding: 8px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid rgba(0, 143, 64, 0.2);
        }

        .green-glow {
            box-shadow: 0 0 15px rgba(0, 143, 64, 0.2);
        }

        .tab-navigation {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-light);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            margin-right: 8px;
            font-size: 0.9rem;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 3px 8px rgba(0, 143, 64, 0.2);
        }

        .badge-green {
            background: rgba(0, 143, 64, 0.1);
            color: var(--primary-dark);
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            border: 1px solid rgba(0, 143, 64, 0.2);
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid rgba(0, 143, 64, 0.2);
        }

        .delivery-info {
            padding: 15px 20px;
            flex: 1;
        }

        .info-label {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 500;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .action-column {
            padding: 15px 20px;
            min-width: 180px;
            border-left: 1px solid #e9ecef;
            background: rgba(0, 143, 64, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        .service-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 5px;
            display: inline-block;
        }

        .badge-express {
            background: linear-gradient(135deg, var(--accent-color), var(--accent-dark));
            color: white;
        }

        .badge-priority {
            background: linear-gradient(135deg, #ff8c19, #e67e22);
            color: white;
        }

        .badge-standard {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .badge-economy {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }

        .floating-action {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .floating-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 143, 64, 0.3);
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .floating-btn:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 6px 20px rgba(0, 143, 64, 0.4);
        }

        /* Compact Row Layout */
        .delivery-row {
            display: flex;
            align-items: stretch;
        }

        .delivery-header-compact {
            padding: 12px 15px;
            min-width: 180px;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .delivery-content {
            padding: 12px 15px;
            flex: 1;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .route-info {
            min-width: 100px;
        }

        .package-info {
            flex: 1;
        }

        .action-buttons {
            min-width: 180px;
            padding: 12px 15px;
            border-left: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 8px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .delivery-header-compact {
                min-width: 160px;
            }

            .action-buttons {
                min-width: 160px;
            }
        }

        @media (max-width: 992px) {
            .delivery-row {
                flex-direction: column;
            }

            .delivery-header-compact {
                border-right: none;
                border-bottom: 1px solid #e9ecef;
                min-width: 100%;
            }

            .delivery-content {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                padding: 15px;
            }

            .action-buttons {
                border-left: none;
                border-top: 1px solid #e9ecef;
                min-width: 100%;
                padding: 15px;
            }

            .route-info,
            .package-info {
                min-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .delivery-header-compact {
                padding: 10px 12px;
            }

            .delivery-content {
                padding: 12px;
            }

            .action-buttons {
                padding: 12px;
            }

            .btn-accept,
            .btn-view {
                padding: 6px 15px;
                font-size: 0.85rem;
            }

            .price-tag {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item-compact {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .stat-number-compact {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            margin-bottom: 5px;
            line-height: 1;
        }

        .stat-label-compact {
            color: var(--text-light);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>

@yield('martketplace-content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>