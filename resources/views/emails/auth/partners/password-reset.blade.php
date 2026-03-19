<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #008F40, #006832);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: white;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 40px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(135deg, #008F40, #006832);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: linear-gradient(135deg, #006832, #004d25);
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .expiry-note {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            margin: 20px 0;
        }
        .brand-logo {
            color: white;
            font-size: 48px;
            margin-bottom: 10px;
        }
        .brand-logo i {
            font-style: normal;
            background: rgba(255,255,255,0.2);
            padding: 15px 20px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand-logo">
                <i>📦</i>
            </div>
            <h1>Karibu Parcels Partner Centre</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $name }}!</h2>

            <p>We received a request to reset the password for your Karibu Parcels Partner account.</p>

            <p>To reset your password, click the button below:</p>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">
                    Reset Your Password
                </a>
            </div>

            <div class="expiry-note">
                <strong>⚠️ This link will expire in {{ $expiryHours }} hour.</strong>
                <p style="margin: 5px 0 0 0; font-size: 13px;">For security reasons, this link can only be used once.</p>
            </div>

            <p>If you didn't request a password reset, please ignore this email or contact support if you have concerns.</p>

            <p>For security reasons, we recommend:</p>
            <ul>
                <li>Using a strong, unique password</li>
                <li>Never sharing your password with anyone</li>
                <li>Enabling two-factor authentication if available</li>
            </ul>

            <p>If you're having trouble clicking the button, copy and paste this URL into your browser:</p>
            <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">
                {{ $resetUrl }}
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
            <p>This email was sent to {{ __('admin@karibuparcels.com') }} regarding your Karibu Parcels Partner account.</p>
            <p>For assistance, contact our support team.</p>
        </div>
    </div>
</body>
</html>