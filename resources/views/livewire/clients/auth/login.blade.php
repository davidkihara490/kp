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
                                <h1>Karibu Parcels Client Centre</h1>
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
                            @csrf

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
                                        wire:model.defer="identifier"
                                        wire:keydown.enter="login"
                                        placeholder="Enter email, phone, or username"
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
                                        class="form-control @error('password') is-invalid @enderror"
                                        wire:model="password"
                                        wire:keydown.enter="login"
                                        placeholder="Enter your password"
                                        autocomplete="current-password" required>
                                    <button type="button" class="btn btn-outline-secondary password-toggle"
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
                                    <a href="{{ route('partners.recover-password') }}" class="btn btn-link btn-sm p-0 text-decoration-none">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100"
                                    wire:loading.attr="disabled" wire:target="login">
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
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('partners.recover-password') }}">
                                <i class="bi bi-key me-1"></i>Forgot Password
                            </a>
                        </div>

                        <!-- New Account Creation Link -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-2">Don't have an account?</p>
                            <a href="{{ route('pudo.register') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus me-2"></i>
                                Create New Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* ===== MAIN CONTAINER ===== */
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

            /* ===== LOGIN BOX ===== */
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
                flex-shrink: 0;
            }

            .brand-logo .logo-img {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                object-fit: cover;
            }

            .brand-text h1 {
                font-size: 1.8rem;
                font-weight: 700;
                color: #333;
                margin: 0;
                line-height: 1.2;
            }

            .brand-text .tagline {
                color: var(--primary-color);
                margin: 5px 0 0 0;
                font-size: 0.9rem;
                font-weight: 500;
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

            /* ===== FORM STYLES ===== */
            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                display: block;
            }

            .form-label i {
                color: var(--primary-color);
            }

            .input-group-text {
                background-color: #f8f9fa;
                border-right: none;
                color: var(--primary-color);
                border-radius: 12px 0 0 12px;
            }

            .form-control {
                border-left: none;
                padding: 12px 15px;
                font-size: 1rem;
                border-radius: 0 12px 12px 0;
            }

            .form-control:focus {
                box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
                border-color: var(--primary-color);
            }

            .form-control.is-invalid {
                border-color: #dc3545;
            }

            .form-control.is-invalid:focus {
                box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            }

            .password-toggle {
                border-left: none;
                border-radius: 0 12px 12px 0;
                background: white;
                color: #6c757d;
                transition: all 0.2s ease;
            }

            .password-toggle:hover {
                background: #f8f9fa;
                color: #333;
            }

            .password-toggle:focus {
                box-shadow: none;
            }

            .invalid-feedback {
                font-size: 0.85rem;
                margin-top: 6px;
            }

            /* ===== BUTTONS ===== */
            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border: none;
                padding: 15px;
                font-weight: 600;
                font-size: 1.1rem;
                border-radius: 12px;
                transition: all 0.3s ease;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .btn-primary::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.1);
                transition: left 0.4s ease;
            }

            .btn-primary:hover::before {
                left: 100%;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 30px rgba(0, 143, 64, 0.3);
            }

            .btn-primary:disabled {
                opacity: 0.7;
                cursor: not-allowed;
                transform: none;
            }

            .btn-outline-primary {
                border-color: var(--primary-color);
                color: var(--primary-color);
                padding: 10px 25px;
                font-weight: 600;
                border-radius: 10px;
                transition: all 0.3s ease;
            }

            .btn-outline-primary:hover {
                background-color: var(--primary-color);
                color: white;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 143, 64, 0.2);
            }

            .btn-outline-secondary {
                padding: 8px 16px;
                border-radius: 8px;
                transition: all 0.3s ease;
                border-color: #dee2e6;
                color: #6c757d;
            }

            .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: white;
                border-color: #6c757d;
            }

            .btn-link {
                color: var(--primary-color);
                font-weight: 500;
            }

            .btn-link:hover {
                color: var(--primary-dark);
                text-decoration: underline !important;
            }

            /* ===== CUSTOM CHECKBOX ===== */
            .form-check-input {
                width: 18px;
                height: 18px;
                border-radius: 4px;
                border: 2px solid #dee2e6;
                cursor: pointer;
                margin-top: 2px;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .form-check-input:focus {
                box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
            }

            .form-check-label {
                cursor: pointer;
                color: #495057;
                padding-left: 4px;
            }

            /* ===== ALERT STYLES ===== */
            .alert {
                border-radius: 12px;
                border: none;
                animation: fadeIn 0.3s ease-out;
                padding: 12px 16px;
            }

            .alert-success {
                background: rgba(40, 167, 69, 0.1);
                border-left: 4px solid #28a745;
                color: #155724;
            }

            .alert-danger {
                background: rgba(220, 53, 69, 0.1);
                border-left: 4px solid #dc3545;
                color: #721c24;
            }

            .alert .btn-close {
                padding: 0.5rem;
            }

            /* ===== DIVIDER ===== */
            .border-top {
                border-top: 1px solid #dee2e6 !important;
            }

            /* ===== ANIMATIONS ===== */
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

            @keyframes pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }

                100% {
                    transform: scale(1);
                }
            }

            .brand-logo {
                animation: pulse 3s infinite;
            }

            /* ===== RESPONSIVE DESIGN ===== */
            @media (max-width: 576px) {
                .login-container {
                    padding: 10px;
                }

                .login-box {
                    padding: 25px 20px;
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

                .brand-logo .logo-img {
                    width: 40px;
                    height: 40px;
                }

                .brand-text h1 {
                    font-size: 1.5rem;
                }

                .login-title h2 {
                    font-size: 1.5rem;
                }

                .login-title {
                    text-align: center;
                }

                .btn {
                    font-size: 0.9rem;
                    padding: 8px 16px;
                }

                .btn-primary {
                    padding: 12px;
                    font-size: 1rem;
                }

                .d-flex.justify-content-between {
                    flex-direction: column;
                    gap: 10px;
                    align-items: flex-start !important;
                }

                .input-group-text,
                .form-control,
                .password-toggle {
                    font-size: 0.9rem;
                }
            }

            @media (min-width: 577px) and (max-width: 768px) {
                .login-box {
                    padding: 30px;
                }

                .brand-text h1 {
                    font-size: 1.6rem;
                }
            }
        </style>

        @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                // Auto-focus identifier input
                const identifierInput = document.getElementById('identifier');
                if (identifierInput) {
                    setTimeout(() => {
                        identifierInput.focus();
                    }, 100);
                }

                // Handle Enter key for form submission
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        const activeElement = document.activeElement;
                        if (activeElement && activeElement.tagName === 'INPUT' &&
                            (activeElement.type === 'text' || activeElement.type === 'password')) {
                            e.preventDefault();
                            Livewire.dispatch('login');
                        }
                    }
                });

                // Prevent form resubmission on page refresh
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            });
        </script>
        @endpush
    </div>
</div>