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

        /* Header styles - matching registration theme */
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

        .logo-circle i {
            color: #28a745;
            font-size: 24px;
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

        /* Success/status icons */
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

        .status-icon i {
            font-size: 35px;
            color: #28a745;
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
        }

        .logo-img:hover {
            transform: scale(1.05);
        }

        /* Info cards - matching registration theme */
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

        .info-item i {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 8px;
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

        /* Dividers */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #28a745, transparent);
            margin: 25px 0;
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

        .social-links {
            margin: 15px 0;
        }

        .social-link {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border-radius: 50%;
            line-height: 35px;
            text-align: center;
            margin: 0 5px;
            text-decoration: none;
        }

        .footer-text {
            font-size: 12px;
            color: #999999;
            margin: 10px 0;
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
            <table width="100%">
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
            <div class="social-links">
                <a href="#" class="social-link">f</a>
                <a href="#" class="social-link">t</a>
                <a href="#" class="social-link">in</a>
                <a href="#" class="social-link">ig</a>
            </div>

            <p class="footer-text">
                © {{ date('Y') }} Karibu Parcels. All rights reserved.<br>
                Nairobi, Kenya | hello@karibuparcels.com | +254 700 000 000
            </p>

            <p class="footer-text" style="margin-top: 15px;">
                <a href="#" style="color: #28a745; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
                |
                <a href="#" style="color: #28a745; text-decoration: none; margin: 0 10px;">Terms of Service</a>
                |
                <a href="#" style="color: #28a745; text-decoration: none; margin: 0 10px;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>

</html>