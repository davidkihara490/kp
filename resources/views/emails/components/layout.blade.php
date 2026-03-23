{{-- resources/views/components/email-layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name') }}</title>
    <style>
        /* Reset styles */
        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        table,
        td {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            -webkit-text-size-adjust: 100%;
        }

        /* Email container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Header styles */
        .email-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 25px 30px;
            text-align: left;
        }

        .logo-circle {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .email-title {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        .email-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin: 5px 0 0;
        }

        /* Content area */
        .email-content {
            padding: 30px;
            background: #ffffff;
        }

        /* Status icon */
        .status-icon {
            width: 70px;
            height: 70px;
            background: rgba(40, 167, 69, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        /* Typography */
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 20px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 15px;
        }

        h3 {
            font-size: 18px;
            font-weight: 600;
            color: #28a745;
            margin-bottom: 12px;
        }

        p {
            font-size: 15px;
            color: #666666;
            margin-bottom: 20px;
        }

        .logo-img {
            transition: transform 0.3s ease;
            max-width: 100%;
            height: auto;
        }

        /* Info cards */
        .info-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 20px 0;
        }

        .info-item {
            text-align: center;
            padding: 15px 10px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .info-item h4 {
            font-size: 14px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 5px;
        }

        .info-item p {
            font-size: 12px;
            color: #666666;
            margin: 0;
        }

        /* Data tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .data-table th {
            text-align: left;
            padding: 12px 15px;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
            color: #28a745;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 2px solid #28a745;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
            color: #666666;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.2);
        }

        .badge-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            margin: 5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: #ffffff;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #28a745;
            color: #28a745;
        }

        /* Progress indicators */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ffffff;
            border: 2px solid #28a745;
            color: #28a745;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .step-circle.completed {
            background: #28a745;
            border-color: #28a745;
            color: #ffffff;
        }

        .step-label {
            font-size: 12px;
            color: #666666;
        }

        /* Footer */
        .email-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        /* Social Links - Using inline SVG, Plain Icons, Centered */
        .social-links {
            margin: 0 0 20px 0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link svg {
            width: 28px;
            height: 28px;
            transition: all 0.3s ease;
        }

        .social-link:hover svg {
            transform: translateY(-2px);
        }

        /* Social icon colors */
        .social-link:hover .facebook-icon {
            fill: #1877f2;
        }
        
        .social-link:hover .instagram-icon {
            fill: #e4405f;
        }
        
        .social-link:hover .tiktok-icon {
            fill: #000000;
        }

        .footer-text {
            font-size: 12px;
            color: #999999;
            margin: 10px 0;
            text-align: center;
        }

        .footer-text a {
            color: #28a745;
            text-decoration: none;
        }

        /* Alert boxes */
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
            color: #856404;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .email-content {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                display: block;
                margin: 10px 0;
            }
            
            .social-links {
                gap: 20px;
            }
            
            .social-link svg {
                width: 24px;
                height: 24px;
            }
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }

        .text-success {
            color: #28a745;
        }

        .text-muted {
            color: #666666;
        }

        .mb-1 {
            margin-bottom: 10px;
        }

        .mb-2 {
            margin-bottom: 20px;
        }

        .mb-3 {
            margin-bottom: 30px;
        }

        .mt-1 {
            margin-top: 10px;
        }

        .mt-2 {
            margin-top: 20px;
        }

        .mt-3 {
            margin-top: 30px;
        }

        .fw-bold {
            font-weight: 700;
        }

        .fw-semibold {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td>
                        <div style="display: flex; align-items: center;">
                            <div class="logo-circle">
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                            </div>
                            <div>
                                <h1 class="email-title">Karibu Parcels</h1>
                                <p class="email-subtitle">Delivering Smiles Across Kenya</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Dynamic Content -->
        <div class="email-content">
            @yield('email-content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <!-- Social Links - Using inline SVG icons for guaranteed visibility -->
            <div class="social-links">
                <!-- Facebook Icon -->
                <a href="https://www.facebook.com/karibuparcels" target="_blank" class="social-link" aria-label="Facebook">
                    <svg class="facebook-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#3b5998">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>
                
                <!-- Instagram Icon -->
                <a href="https://www.instagram.com/karibuparcels/" target="_blank" class="social-link" aria-label="Instagram">
                    <svg class="instagram-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#e4405f">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.336 3.608 1.311.975.975 1.249 2.242 1.311 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.336 2.633-1.311 3.608-.975.975-2.242 1.249-3.608 1.311-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.336-3.608-1.311-.975-.975-1.249-2.242-1.311-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.336-2.633 1.311-3.608.975-.975 2.242-1.249 3.608-1.311 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.332.014 7.052.072 5.77.13 4.604.353 3.531 1.079 2.458 1.805 1.805 2.458 1.079 3.531.353 4.604.13 5.77.072 7.052.014 8.332 0 8.741 0 12s.014 3.668.072 4.948c.058 1.282.281 2.448 1.007 3.521.726 1.073 1.379 1.726 2.452 2.452 1.073.726 2.239.949 3.521 1.007 1.28.058 1.689.072 4.948.072s3.668-.014 4.948-.072c1.282-.058 2.448-.281 3.521-1.007 1.073-.726 1.726-1.379 2.452-2.452.726-1.073.949-2.239 1.007-3.521.058-1.28.072-1.689.072-4.948s-.014-3.668-.072-4.948c-.058-1.282-.281-2.448-1.007-3.521-.726-1.073-1.379-1.726-2.452-2.452-1.073-.726-2.239-.949-3.521-1.007C15.668.014 15.259 0 12 0z"/>
                        <path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8z"/>
                        <circle cx="18.406" cy="5.594" r="1.44"/>
                    </svg>
                </a>
                
                <!-- TikTok Icon -->
                <a href="https://www.tiktok.com/@karibuparcels" target="_blank" class="social-link" aria-label="TikTok">
                    <svg class="tiktok-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#010101">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.76-.08 1.4-.54 2.79-1.35 3.99-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.98 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.3-.69.3-1.07.1-2.76.05-5.52.07-8.28.01-2.72.01-5.44.02-8.16z"/>
                    </svg>
                </a>
            </div>

            <!-- Contact Information -->
            <p class="footer-text">
                <strong>Karibu Parcels</strong><br>
                Nairobi, Kenya | +254 700 130 759<br>
                <a href="mailto:admin@karibuparcels.com">admin@karibuparcels.com</a>
            </p>

            <!-- Copyright -->
            <p class="footer-text">
                © {{ date('Y') }} Karibu Parcels. All rights reserved.
            </p>

            <!-- Footer Links -->
            <p class="footer-text" style="margin-top: 15px;">
                <a href="#">Privacy Policy</a>
                |
                <a href="#">Terms of Service</a>
                |
                <a href="#">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>

</html>