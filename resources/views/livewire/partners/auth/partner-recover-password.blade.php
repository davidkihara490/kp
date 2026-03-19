<div>
    <div class="recovery-container">
        <div class="recovery-wrapper">
            <div class="recovery-box">
                <!-- Header -->
                <div class="recovery-header">
                    <div class="brand-section">
                        <div class="brand-logo">
                            @if (file_exists(public_path('logo.jpeg')))
                            <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                            @else
                            <i class="bi bi-shield-lock"></i>
                            @endif
                        </div>
                        <div class="brand-text">
                            <h1>Karibu Parcels Partner Centre</h1>
                            <p class="tagline">Password Recovery</p>
                        </div>
                    </div>

                    <div class="recovery-title">
                        @if($step === 'request')
                        <h2>Forgot Password?</h2>
                        <p>Enter your email to reset your password</p>
                        @elseif($step === 'verify')
                        <h2>Check Your Email</h2>
                        <p>We've sent a reset link to your inbox</p>
                        @endif
                    </div>
                </div>

                <!-- Body -->
                <div class="recovery-body">
                    <!-- Error Alert -->
                    @if($errorMessage)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $errorMessage }}
                        <button type="button" class="btn-close" wire:click="$set('errorMessage', '')"></button>
                    </div>
                    @endif

                    <!-- Success Alert -->
                    @if($successMessage && $step === 'verify')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ $successMessage }}
                        <button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
                    </div>
                    @endif

                    <!-- Step 1: Request Reset -->
                    @if($step === 'request')
                    <form wire:submit.prevent="sendResetLink">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-2"></i>
                                Email Address
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" id="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    wire:model.defer="email" 
                                    placeholder="Enter your registered email"
                                    autocomplete="email"
                                    required autofocus>
                            </div>
                            @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-send me-2"></i>
                                    Send Reset Link
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to Login -->
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-link" wire:click="goToLogin" wire:loading.attr="disabled">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Login
                        </a>
                    </div>
                    @endif

                    <!-- Step 2: Verify Email Sent -->
                    @if($step === 'verify')
                    <div class="verify-section">
                        <div class="email-icon">
                            <i class="bi bi-envelope-check"></i>
                        </div>

                        <p class="email-sent-text">
                            We've sent a password reset link to:<br>
                            <strong>{{ $email }}</strong>
                        </p>

                        <div class="email-actions">
                            <button class="btn btn-outline-primary" 
                                wire:click="resendResetLink" 
                                wire:loading.attr="disabled"
                                {{ !$canResend ? 'disabled' : '' }}>
                                <span wire:loading.remove wire:target="resendResetLink">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    @if($canResend)
                                    Resend Email
                                    @else
                                    Resend in {{ $resendCooldown }}s
                                    @endif
                                </span>
                                <span wire:loading wire:target="resendResetLink">
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Sending...
                                </span>
                            </button>

                            <button class="btn btn-link" wire:click="$set('step', 'request')">
                                <i class="bi bi-pencil me-1"></i>
                                Use different email
                            </button>
                        </div>

                        <div class="email-tips mt-4">
                            <h6><i class="bi bi-lightbulb me-2"></i>Didn't receive the email?</h6>
                            <ul>
                                <li>Check your spam/junk folder</li>
                                <li>Make sure you entered the correct email</li>
                                <li>Add noreply@karibuparcels.com to your contacts</li>
                                <li>Wait a few minutes and try resending</li>
                            </ul>
                        </div>

                        <div class="text-center mt-4">
                            <a href="#" class="btn btn-link" wire:click="goToLogin">
                                <i class="bi bi-arrow-left me-1"></i>
                                Back to Login
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Security Notice -->
                    <div class="security-notice mt-4 pt-3 border-top">
                        <small class="text-muted">
                            <i class="bi bi-shield-check me-1"></i>
                            For your security, password reset links expire after 1 hour.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .recovery-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
        }

        .recovery-wrapper {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .recovery-box {
            padding: 40px;
        }

        .recovery-header {
            margin-bottom: 40px;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #008F40, #006832);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .brand-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .brand-text .tagline {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 0.9rem;
        }

        .recovery-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .recovery-title p {
            color: #666;
            margin: 0;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-control {
            border-left: none;
            padding: 12px 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
            border-color: #008F40;
        }

        .btn-primary {
            background: linear-gradient(135deg, #008F40, #006832);
            border: none;
            padding: 15px;
            font-weight: 600;
            font-size: 1.1rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 143, 64, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-outline-primary {
            border-color: #008F40;
            color: #008F40;
            padding: 10px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover:not(:disabled) {
            background-color: #008F40;
            color: white;
        }

        .btn-outline-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-link {
            color: #008F40;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #006832;
            text-decoration: underline;
        }

        /* Verify Section */
        .verify-section {
            text-align: center;
        }

        .email-icon {
            font-size: 4rem;
            color: #008F40;
            margin-bottom: 20px;
        }

        .email-sent-text {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 30px;
        }

        .email-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .email-tips {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: left;
        }

        .email-tips h6 {
            color: #333;
            margin-bottom: 10px;
        }

        .email-tips ul {
            margin: 0;
            padding-left: 20px;
        }

        .email-tips li {
            color: #666;
            margin-bottom: 5px;
        }

        /* Alert Styles */
        .alert {
            border-radius: 10px;
            border: none;
            animation: fadeIn 0.3s ease-out;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        /* Security Notice */
        .security-notice {
            text-align: center;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .recovery-box {
            animation: fadeIn 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .recovery-container {
                padding: 10px;
            }

            .recovery-box {
                padding: 25px;
            }

            .brand-section {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .brand-logo {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .brand-text h1 {
                font-size: 1.5rem;
            }

            .recovery-title h2 {
                font-size: 1.5rem;
            }

            .email-actions {
                flex-direction: column;
            }

            .btn {
                font-size: 0.9rem;
            }
        }
    </style>

    @script
    <script>
        document.addEventListener('livewire:init', () => {
            // Auto-focus first input
            const firstInput = document.querySelector('input:not([type="hidden"])');
            if (firstInput) {
                firstInput.focus();
            }

            // Handle cooldown timer
            let cooldownInterval;
            
            Livewire.on('start-cooldown', () => {
                if (cooldownInterval) {
                    clearInterval(cooldownInterval);
                }
                
                cooldownInterval = setInterval(() => {
                    Livewire.dispatch('updateCooldown');
                }, 1000);
            });

            // Clean up interval on navigation
            window.addEventListener('beforeunload', () => {
                if (cooldownInterval) {
                    clearInterval(cooldownInterval);
                }
            });
        });
    </script>
    @endscript
</div>