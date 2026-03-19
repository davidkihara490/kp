<div>
<div>
    <div class="reset-container">
        <div class="reset-wrapper">
            <div class="reset-box">
                <!-- Header -->
                <div class="reset-header">
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
                            <p class="tagline">Reset Password</p>
                        </div>
                    </div>

                    <div class="reset-title">
                        <h2>Create New Password</h2>
                        <p>Enter your new password below</p>
                    </div>
                </div>

                <!-- Body -->
                <div class="reset-body">
                    <!-- Error Alert -->
                    @if($errorMessage)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $errorMessage }}
                        <button type="button" class="btn-close" wire:click="$set('errorMessage', '')"></button>
                    </div>
                    @endif

                    <!-- Success Alert -->
                    @if($successMessage)
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ $successMessage }}
                    </div>
                    
                    <!-- Auto redirect message -->
                    <div class="text-center mt-3">
                        <p class="text-muted">Redirecting to login in 3 seconds...</p>
                        <a href="{{ route('partners.login') }}" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Go to Login Now
                        </a>
                    </div>
                    @elseif(!$validToken)
                    <!-- Invalid Token Message -->
                    <div class="invalid-token-section text-center">
                        <div class="error-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h4>Invalid or Expired Link</h4>
                        <p class="text-muted mb-4">This password reset link is invalid or has expired.</p>
                        <button class="btn btn-primary" wire:click="goToForgotPassword">
                            <i class="bi bi-arrow-repeat me-2"></i>
                            Request New Link
                        </button>
                        <div class="mt-3">
                            <a href="#" class="btn btn-link" wire:click="goToLogin">
                                <i class="bi bi-arrow-left me-1"></i>
                                Back to Login
                            </a>
                        </div>
                    </div>
                    @else
                    <!-- Reset Password Form -->
                    <form wire:submit.prevent="resetPassword">
                        <!-- New Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-2"></i>
                                New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="{{ $showPassword ? 'text' : 'password' }}" 
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password" 
                                    placeholder="Enter new password"
                                    autocomplete="new-password"
                                    required>
                                <button type="button" class="btn btn-outline-secondary" 
                                    wire:click="togglePasswordVisibility">
                                    <i class="bi bi-eye{{ $showPassword ? '-slash' : '' }}"></i>
                                </button>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Password Strength Indicator -->
                            @if($password)
                            <div class="password-strength mt-2">
                                @php
                                    $strength = 0;
                                    if(strlen($password) >= 8) $strength++;
                                    if(preg_match('/[A-Z]/', $password)) $strength++;
                                    if(preg_match('/[0-9]/', $password)) $strength++;
                                    if(preg_match('/[^a-zA-Z0-9]/', $password)) $strength++;
                                    
                                    $strengthText = ['Weak', 'Fair', 'Good', 'Strong'];
                                    $strengthClass = ['danger', 'warning', 'info', 'success'];
                                @endphp
                                
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar bg-{{ $strengthClass[$strength-1] ?? 'danger' }}" 
                                        style="width: {{ $strength * 25 }}%"></div>
                                </div>
                                <small class="text-muted">
                                    Password strength: {{ $strengthText[$strength-1] ?? 'Very Weak' }}
                                </small>
                            </div>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mt-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock me-2"></i>
                                Confirm New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="{{ $showConfirmPassword ? 'text' : 'password' }}" 
                                    id="password_confirmation"
                                    class="form-control @error('password') is-invalid @enderror"
                                    wire:model="password_confirmation" 
                                    placeholder="Confirm new password"
                                    autocomplete="new-password"
                                    required>
                                <button type="button" class="btn btn-outline-secondary" 
                                    wire:click="toggleConfirmPasswordVisibility">
                                    <i class="bi bi-eye{{ $showConfirmPassword ? '-slash' : '' }}"></i>
                                </button>
                            </div>
                            
                            <!-- Password Match Indicator -->
                            @if($password_confirmation)
                            <div class="mt-2">
                                @if($password === $password_confirmation)
                                <small class="text-success">
                                    <i class="bi bi-check-circle me-1"></i>Passwords match
                                </small>
                                @else
                                <small class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>Passwords do not match
                                </small>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Password Requirements -->
                        <div class="password-requirements mt-3">
                            <h6>Password Requirements:</h6>
                            <ul class="list-unstyled">
                                <li class="{{ strlen($password ?? '') >= 8 ? 'text-success' : 'text-muted' }}">
                                    <i class="bi bi-{{ strlen($password ?? '') >= 8 ? 'check-circle-fill' : 'circle' }} me-2"></i>
                                    At least 8 characters
                                </li>
                                <li class="{{ preg_match('/[A-Z]/', $password ?? '') ? 'text-success' : 'text-muted' }}">
                                    <i class="bi bi-{{ preg_match('/[A-Z]/', $password ?? '') ? 'check-circle-fill' : 'circle' }} me-2"></i>
                                    At least one uppercase letter
                                </li>
                                <li class="{{ preg_match('/[0-9]/', $password ?? '') ? 'text-success' : 'text-muted' }}">
                                    <i class="bi bi-{{ preg_match('/[0-9]/', $password ?? '') ? 'check-circle-fill' : 'circle' }} me-2"></i>
                                    At least one number
                                </li>
                                <li class="{{ preg_match('/[^a-zA-Z0-9]/', $password ?? '') ? 'text-success' : 'text-muted' }}">
                                    <i class="bi bi-{{ preg_match('/[^a-zA-Z0-9]/', $password ?? '') ? 'check-circle-fill' : 'circle' }} me-2"></i>
                                    At least one special character
                                </li>
                            </ul>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100" 
                                wire:loading.attr="disabled"
                                wire:target="resetPassword">
                                <span wire:loading.remove wire:target="resetPassword">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Reset Password
                                </span>
                                <span wire:loading wire:target="resetPassword">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Resetting...
                                </span>
                            </button>
                        </div>
                    </form>

                    <!-- Back to Login -->
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-link" wire:click="goToLogin">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Login
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Main Container */
        .reset-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
        }

        .reset-wrapper {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .reset-box {
            padding: 40px;
        }

        .reset-header {
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

        .reset-title h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .reset-title p {
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

        .btn-link {
            color: #008F40;
            text-decoration: none;
        }

        .btn-link:hover {
            color: #006832;
            text-decoration: underline;
        }

        /* Password Requirements */
        .password-requirements {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .password-requirements h6 {
            color: #333;
            margin-bottom: 10px;
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        .password-requirements .text-success {
            color: #28a745 !important;
        }

        /* Invalid Token Section */
        .invalid-token-section {
            padding: 20px 0;
        }

        .error-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 20px;
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

        .reset-box {
            animation: fadeIn 0.6s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .reset-container {
                padding: 10px;
            }

            .reset-box {
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

            .reset-title h2 {
                font-size: 1.5rem;
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

            // Handle redirect after successful reset
            Livewire.on('redirect-to-login', () => {
                setTimeout(() => {
                    window.location.href = '{{ route("partners.login") }}';
                }, 3000);
            });
        });
    </script>
    @endscript
</div></div>
