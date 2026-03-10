<div>
    <div>
        <div class="login-container">
            <div class="login-wrapper">
                <!-- Login Box -->
                <div class="login-box">
                    <!-- Header -->
                    <div class="login-header">
                        <div class="brand-section">

                        
                            <div class="brand-logo">
                                @if (file_exists(public_path('logo.jpeg')))
                                    <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                                @else
                                    <i class="bi bi-truck"></i>
                                @endif
                            </div>
                            <div class="brand-text">
                                <h1>Karibu Parcels Partner Centre</h1>
                                <p class="tagline">Professional Courier Services</p>
                            </div>
                        </div>
                        <div class="login-title">
                            <h2>Welcome Back</h2>
                            <p>Sign in to your account</p>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div class="login-body">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($errorMessage)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                {{ $errorMessage }}
                                <button type="button" class="btn-close" wire:click="$set('errorMessage', '')"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="login">
                            <!-- Identifier Input -->
                            <div class="form-group">
                                <label for="identifier" class="form-label">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Email, Phone, or Username
                                </label>

                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person-circle"></i>
                                    </span>

                                    <input type="text" id="identifier"
                                        class="form-control @error('identifier') is-invalid @enderror"
                                        wire:model.defer="identifier" placeholder="Enter email, phone, or username"
                                        autocomplete="username" required autofocus>
                                </div>

                                @error('identifier')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Password Input -->
                            <div class="form-group mt-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>
                                    Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="{{ $showPassword ? 'text' : 'password' }}" id="password"
                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        wire:model="password" placeholder="Enter your password"
                                        autocomplete="current-password" required>
                                    <button type="button" class="btn btn-outline-secondary"
                                        wire:click="togglePasswordVisibility"
                                        title="{{ $showPassword ? 'Hide password' : 'Show password' }}">
                                        <i class="bi bi-eye{{ $showPassword ? '-slash' : '' }}"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="form-check">
                                    <input type="checkbox" id="remember" class="form-check-input"
                                        wire:model="remember">
                                    <label for="remember" class="form-check-label small">
                                        Remember me
                                    </label>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-link btn-sm p-0 text-decoration-none"
                                        wire:click="forgotPassword">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100" wire:loading.attr="disabled"
                                    wire:target="login">
                                    <span wire:loading.remove wire:target="login">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Sign In
                                    </span>
                                    <span wire:loading wire:target="login">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Signing In...
                                    </span>
                                </button>
                            </div>
                        </form>
                        
                        <!-- Forgot Password Link (Visible Button) -->
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                wire:click="forgotPassword">
                                <i class="bi bi-key me-1"></i>
                                Reset Password
                            </button>
                        </div>
                        
                        <!-- New Account Creation Link -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-2">Don't have an account?</p>
                            <a href="{{ route('partners.onboarding') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                Create New Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* Main Container */
            .login-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 20px;
            }

            .login-wrapper {
                max-width: 600px;
                width: 100%;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            /* Login Box */
            .login-box {
                padding: 40px;
            }

            .login-header {
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
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
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

            .login-title h2 {
                font-size: 2rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 10px;
            }

            .login-title p {
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
                border-color: var(--primary-color);
            }

            .btn-primary {
                /* background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); */
                                background: 0 10px 20px rgba(0, 143, 64, 0.3);

                border: none;
                padding: 15px;
                font-weight: 600;
                font-size: 1.1rem;
                border-radius: 10px;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0, 143, 64, 0.3);
            }

            .btn-primary:disabled {
                opacity: 0.7;
                cursor: not-allowed;
            }
            
            .btn-outline-primary {
                border-color: var(--primary-color);
                color: var(--primary-color);
                padding: 10px 20px;
                font-weight: 600;
                border-radius: 10px;
                transition: all 0.3s ease;
            }
            
            /* .btn-outline-primary:hover {
                background-color: var(--primary-color);
                color: white;
                transform: translateY(-2px);
            } */
            
            .btn-outline-secondary {
                padding: 8px 16px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }
            
            .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: white;
            }

            /* Custom Checkbox */
            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
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

            /* Responsive Design */
            @media (max-width: 576px) {
                .login-container {
                    padding: 10px;
                }

                .login-box {
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

                .login-title h2 {
                    font-size: 1.5rem;
                }
                
                .btn {
                    font-size: 0.9rem;
                    padding: 8px 16px;
                }
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

            .login-box {
                animation: fadeIn 0.6s ease-out;
            }
            
            /* Border top for new account section */
            .border-top {
                border-top: 1px solid #dee2e6 !important;
            }
        </style>

        <script>
            // Auto-focus identifier input
            document.addEventListener('livewire:init', () => {
                const identifierInput = document.getElementById('identifier');
                if (identifierInput) {
                    identifierInput.focus();
                }
            });

            // Enter key submits form
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    const activeElement = document.activeElement;
                    if (activeElement && activeElement.tagName === 'INPUT' &&
                        (activeElement.type === 'text' || activeElement.type === 'password')) {
                        e.preventDefault();
                        Livewire.dispatch('submit');
                    }
                }
            });

            // Prevent form resubmission on page refresh
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            
            // Add smooth scrolling for new users
            document.addEventListener('DOMContentLoaded', function() {
                const newAccountBtn = document.querySelector('a[href="{{ route('partners.onboarding') }}"]');
                if (newAccountBtn) {
                    newAccountBtn.addEventListener('click', function(e) {
                        if (this.getAttribute('href') === '#') {
                            e.preventDefault();
                            // Trigger Livewire event for new account creation
                            Livewire.dispatch('createNewAccount');
                        }
                    });
                }
            });
        </script>
    </div>
</div>