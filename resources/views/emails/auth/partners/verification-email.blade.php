    @extends('emails.components.layout')
    @section('email-content')
    <div class="text-center mb-3">
        <div class="status-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h1>Hi, {{ $user->first_name }}! 👋</h1>
    </div>

    <!-- Verification Section -->
    <div style="margin: 30px 0; text-align: center;">
        <a href="{{ $verificationUrl }}" class="btn btn-primary" style="display: inline-block;">
            <i class="bi bi-check-circle"></i> Verify Your Email
        </a>

        <p style="margin-top: 10px; font-size: 13px; color: #666;">
            <i class="bi bi-info-circle"></i>
            Please verify your email. Once you verify, wait for the admin to approve you for you to access your portal.
        </p>
    </div>


    <!-- Sign-off -->
    <div style="margin-top: 30px;">
        <p>Thanks,</p>
        <p><strong>{{ config('app.name') }} Team</strong></p>
    </div>

    @endsection