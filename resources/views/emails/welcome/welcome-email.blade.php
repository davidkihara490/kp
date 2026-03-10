    @extends('emails.components.layout')
    @section('email-content')
    <div class="text-center mb-3">
        <div class="status-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        
        <h1>Welcome, {{ $user->first_name }}! 👋</h1>
        <p class="text-muted">We're thrilled to have you on <strong>Karibu Parcels</strong>!</p>
    </div>

    <div class="info-card">
        <h3 style="margin-top: 0;">Your Login Details</h3>
        
        <table class="data-table">
            <tr>
                <th>Email / Username / Phone:</th>
                <td><strong>{{ $user->email ?? $user->username ?? $user->phone }}</strong></td>
            </tr>
            <tr>
                <th>Password:</th>
                <td>
                    <span style="background: #f0f0f0; padding: 3px 8px; border-radius: 4px; font-family: monospace;">
                        {{ $password }}
                    </span>
                </td>
            </tr>
        </table>
        
        <p style="font-size: 13px; color: #999; margin-top: 10px; margin-bottom: 0;">
            <i class="bi bi-shield-exclamation"></i> 
            Please change your password after first login for security.
        </p>
    </div>

    @if($includeVerification && $verificationUrl)
    <!-- Verification Section -->
    <div style="margin: 30px 0; text-align: center;">
        <a href="{{ $verificationUrl }}" class="btn btn-primary" style="display: inline-block;">
            <i class="bi bi-check-circle"></i> Verify Your Email
        </a>
        
        <p style="margin-top: 10px; font-size: 13px; color: #666;">
            <i class="bi bi-info-circle"></i> 
            Please verify your email to activate your account.
        </p>
    </div>
    @endif

    <!-- Login Link -->
    <div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <p style="margin-bottom: 5px;">You can log in here anytime:</p>
        <a href="{{ route('partners.login') }}" style="color: #28a745; font-weight: 600; text-decoration: none;">
            {{ config('app.name') }} Login →
        </a>
    </div>

    <!-- Sign-off -->
    <div style="margin-top: 30px;">
        <p>Thanks for joining,</p>
        <p><strong>{{ config('app.name') }} Team</strong></p>
    </div>

    @endsection
