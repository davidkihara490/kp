<div>
    <div>
        <div class="register-container">
            <div class="register-wrapper">
                <!-- Register Box -->
                <div class="register-box">
                    <!-- Header -->
                    <div class="register-header">
                        <div class="brand-section">
                            <div class="brand-logo">
                                @if (file_exists(public_path('logo.jpeg')))
                                <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels" height="45" class="logo-img">
                                @else
                                <i class="bi bi-truck"></i>
                                @endif
                            </div>
                            <div class="brand-text">
                                <h1>Karibu Parcels</h1>
                                <p class="tagline">Create Your Account</p>
                            </div>
                        </div>
                        <div class="register-title">
                            <h2>Get Started</h2>
                            <p>Join our courier network today</p>
                        </div>
                    </div>

                    <!-- Register Form -->
                    <div class="register-body">
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

                        <form wire:submit.prevent="register">
                            @csrf

                            <!-- Account Type Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Account Type
                                </label>
                                <div class="account-type-selector">
                                    <div class="account-type-option {{ $accountType === 'individual' ? 'active' : '' }}"
                                        wire:click="$set('accountType', 'individual')"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Individual Account">
                                        <div class="account-type-icon">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div class="account-type-info">
                                            <h6>Individual</h6>
                                            <p>Send single parcels</p>
                                        </div>
                                        <div class="account-type-check">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                    </div>
                                    <div class="account-type-option {{ $accountType === 'corporate' ? 'active' : '' }}"
                                        wire:click="$set('accountType', 'corporate')"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Corporate Account">
                                        <div class="account-type-icon corporate-icon">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="account-type-info">
                                            <h6>Corporate</h6>
                                            <p>Send parcels in bulk</p>
                                        </div>
                                        <div class="account-type-check">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('accountType')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Name Fields -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstName" class="form-label">
                                            <i class="bi bi-person me-2"></i>
                                            First Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" id="firstName"
                                                class="form-control @error('firstName') is-invalid @enderror"
                                                wire:model.defer="firstName"
                                                placeholder="Enter first name"
                                                autocomplete="given-name" required autofocus>
                                        </div>
                                        @error('firstName')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastName" class="form-label">
                                            <i class="bi bi-person me-2"></i>
                                            Last Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" id="lastName"
                                                class="form-control @error('lastName') is-invalid @enderror"
                                                wire:model.defer="lastName"
                                                placeholder="Enter last name"
                                                autocomplete="family-name" required>
                                        </div>
                                        @error('lastName')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Email Field -->
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
                                        placeholder="Enter your email address"
                                        autocomplete="email" required>
                                </div>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Field (Optional) -->
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-phone me-2"></i>
                                    Phone Number <small class="text-muted">(Optional)</small>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-phone"></i>
                                    </span>
                                    <input type="tel" id="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        wire:model.defer="phone"
                                        placeholder="Enter phone number"
                                        autocomplete="tel">
                                </div>
                                @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Corporate Fields -->
                            @if($accountType === 'corporate')
                            <div class="corporate-fields animate-fade-up">
                                <div class="divider d-flex align-items-center mb-3">
                                    <span class="flex-grow-1 border-bottom"></span>
                                    <span class="px-3 small text-muted">Company Details</span>
                                    <span class="flex-grow-1 border-bottom"></span>
                                </div>

                                <div class="form-group">
                                    <label for="companyName" class="form-label">
                                        <i class="bi bi-building me-2"></i>
                                        Company Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-building"></i>
                                        </span>
                                        <input type="text" id="companyName"
                                            class="form-control @error('companyName') is-invalid @enderror"
                                            wire:model.defer="companyName"
                                            placeholder="Enter company name">
                                    </div>
                                    @error('companyName')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="companyRegistrationNumber" class="form-label">
                                        <i class="bi bi-file-text me-2"></i>
                                        Registration Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-file-text"></i>
                                        </span>
                                        <input type="text" id="companyRegistrationNumber"
                                            class="form-control @error('companyRegistrationNumber') is-invalid @enderror"
                                            wire:model.defer="companyRegistrationNumber"
                                            placeholder="Enter company registration number">
                                    </div>
                                    @error('companyRegistrationNumber')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="address" class="form-label">
                                        <i class="bi bi-geo-alt me-2"></i>
                                        Address <small class="text-muted">(Optional)</small>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-geo-alt"></i>
                                        </span>
                                        <input type="text" id="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            wire:model.defer="address"
                                            placeholder="Enter street address">
                                    </div>
                                    @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" id="city"
                                                class="form-control @error('city') is-invalid @enderror"
                                                wire:model.defer="city"
                                                placeholder="City">
                                            @error('city')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="postalCode" class="form-label">Postal Code</label>
                                            <input type="text" id="postalCode"
                                                class="form-control @error('postalCode') is-invalid @enderror"
                                                wire:model.defer="postalCode"
                                                placeholder="Postal code">
                                            @error('postalCode')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Password Fields -->
                            <div class="form-group mt-3">
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
                                        placeholder="Create a strong password"
                                        autocomplete="new-password" required>
                                    <button type="button" class="btn btn-outline-secondary password-toggle"
                                        wire:click="togglePasswordVisibility"
                                        title="{{ $showPassword ? 'Hide password' : 'Show password' }}">
                                        <i class="bi bi-eye{{ $showPassword ? '-slash' : '' }}"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="password-hint small text-muted mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Must be at least 8 characters with uppercase, lowercase, number, and special character
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="passwordConfirmation" class="form-label">
                                    <i class="bi bi-lock me-2"></i>
                                    Confirm Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="{{ $showConfirmPassword ? 'text' : 'password' }}" id="passwordConfirmation"
                                        class="form-control @error('passwordConfirmation') is-invalid @enderror"
                                        wire:model="passwordConfirmation"
                                        placeholder="Confirm your password"
                                        autocomplete="new-password" required>
                                    <button type="button" class="btn btn-outline-secondary password-toggle"
                                        wire:click="toggleConfirmPasswordVisibility"
                                        title="{{ $showConfirmPassword ? 'Hide password' : 'Show password' }}">
                                        <i class="bi bi-eye{{ $showConfirmPassword ? '-slash' : '' }}"></i>
                                    </button>
                                </div>
                                @error('passwordConfirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Terms Checkbox -->
                            <div class="form-group mt-3">
                                <div class="form-check">
                                    <input type="checkbox" id="agreeTerms" class="form-check-input"
                                        wire:model="agreeTerms">
                                    <label for="agreeTerms" class="form-check-label">
                                        I agree to the
                                        <a href="#" class="text-decoration-none" style="color: var(--primary-color);">
                                            Terms of Service
                                        </a>
                                        and
                                        <a href="#" class="text-decoration-none" style="color: var(--primary-color);">
                                            Privacy Policy
                                        </a>
                                    </label>
                                </div>
                                @error('agreeTerms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100"
                                    wire:loading.attr="disabled" wire:target="register">
                                    <span wire:loading.remove wire:target="register">
                                        <i class="bi bi-person-plus me-2"></i>
                                        Create Account
                                    </span>
                                    <span wire:loading wire:target="register">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Creating Account...
                                    </span>
                                </button>
                            </div>
                        </form>

                        <!-- Login Link -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="text-muted mb-2">Already have an account?</p>
                            <a href="{{ route('pudo.login') }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Sign In
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            /* ===== MAIN CONTAINER ===== */
            .register-container {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 20px;
            }

            .register-wrapper {
                max-width: 700px;
                width: 100%;
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            /* ===== REGISTER BOX ===== */
            .register-box {
                padding: 40px;
            }

            .register-header {
                margin-bottom: 30px;
            }

            .brand-section {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 25px;
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

            .register-title h2 {
                font-size: 2rem;
                font-weight: 700;
                color: #333;
                margin-bottom: 10px;
            }

            .register-title p {
                color: #666;
                margin: 0;
            }

            /* ===== ACCOUNT TYPE SELECTOR ===== */
            .account-type-selector {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
                margin-top: 8px;
            }

            .account-type-option {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 15px 18px;
                border: 2px solid var(--border-color);
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.3s ease;
                background: white;
                position: relative;
            }

            .account-type-option:hover {
                border-color: var(--primary-color);
                background: var(--primary-light);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 143, 64, 0.1);
            }

            .account-type-option.active {
                border-color: var(--primary-color);
                background: var(--primary-light);
                box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
            }

            .account-type-option .account-type-icon {
                width: 45px;
                height: 45px;
                border-radius: 10px;
                background: var(--primary-light);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: var(--primary-color);
                transition: all 0.3s ease;
                flex-shrink: 0;
            }

            .account-type-option.active .account-type-icon {
                background: var(--primary-color);
                color: white;
            }

            .account-type-option .account-type-icon.corporate-icon {
                background: #fff3e0;
                color: #e65100;
            }

            .account-type-option.active .account-type-icon.corporate-icon {
                background: #e65100;
                color: white;
            }

            .account-type-option .account-type-info {
                flex: 1;
            }

            .account-type-option .account-type-info h6 {
                margin: 0;
                font-weight: 600;
                font-size: 0.95rem;
                color: var(--text-dark);
            }

            .account-type-option .account-type-info p {
                margin: 2px 0 0;
                font-size: 0.8rem;
                color: var(--text-light);
            }

            .account-type-option .account-type-check {
                opacity: 0;
                transform: scale(0.5);
                transition: all 0.3s ease;
                color: var(--primary-color);
                font-size: 1.2rem;
            }

            .account-type-option.active .account-type-check {
                opacity: 1;
                transform: scale(1);
            }

            /* ===== CORPORATE FIELDS ===== */
            .corporate-fields {
                animation: fadeInUp 0.4s ease-out;
            }

            .divider {
                margin: 20px 0;
            }

            .divider .border-bottom {
                border-color: var(--border-color) !important;
                opacity: 0.5;
            }

            /* ===== FORM STYLES ===== */
            .form-group {
                margin-bottom: 1.2rem;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 6px;
                display: block;
                font-size: 0.9rem;
            }

            .form-label i {
                color: var(--primary-color);
            }

            .form-label small {
                font-weight: 400;
            }

            .input-group-text {
                background-color: #f8f9fa;
                border-right: none;
                color: var(--primary-color);
                border-radius: 12px 0 0 12px;
            }

            .form-control {
                border-left: none;
                padding: 11px 15px;
                font-size: 0.95rem;
                border-radius: 0 12px 12px 0;
            }

            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.15);
                border-color: var(--primary-color);
            }

            .form-control.is-invalid {
                border-color: #dc3545;
            }

            .form-control.is-invalid:focus {
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
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
                font-size: 0.8rem;
                margin-top: 5px;
            }

            .password-hint {
                font-size: 0.75rem !important;
            }

            /* ===== BUTTONS ===== */
            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border: none;
                padding: 14px;
                font-weight: 600;
                font-size: 1.05rem;
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
                box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.15);
            }

            .form-check-label {
                cursor: pointer;
                color: #495057;
                padding-left: 4px;
                font-size: 0.9rem;
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

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .register-box {
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
                .register-container {
                    padding: 10px;
                }

                .register-box {
                    padding: 25px 18px;
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

                .register-title h2 {
                    font-size: 1.5rem;
                }

                .register-title {
                    text-align: center;
                }

                .account-type-selector {
                    grid-template-columns: 1fr;
                }

                .account-type-option {
                    padding: 12px 15px;
                }

                .btn {
                    font-size: 0.9rem;
                    padding: 8px 16px;
                }

                .btn-primary {
                    padding: 12px;
                    font-size: 0.95rem;
                }

                .input-group-text,
                .form-control,
                .password-toggle {
                    font-size: 0.9rem;
                }
            }

            @media (min-width: 577px) and (max-width: 768px) {
                .register-box {
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
                // Auto-focus first name input
                const firstNameInput = document.getElementById('firstName');
                if (firstNameInput) {
                    setTimeout(() => {
                        firstNameInput.focus();
                    }, 100);
                }

                // Prevent form resubmission on page refresh
                if (window.history.replaceState) {
                    window.history.replaceState(null, null, window.location.href);
                }
            });
        </script>
        @endpush
    </div>
</div>