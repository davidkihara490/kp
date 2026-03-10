<div>
        <!-- Full-height Registration Container -->
        <div class="registration-container">
            <div class="registration-box">
                <!-- Box Header - Fixed Height -->
                <div class="registration-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="brand-section">
                            <div class="d-flex align-items-center">
                                <div class="logo-container me-3">
                                    <i class="bi bi-person-plus-fill text-white"></i>
                                </div>
                                <div>
                                    <h3 class="m-0 text-white">Partner Registration</h3>
                                    <p class="m-0 text-white opacity-90">Become a Karibu Parcels Partner</p>
                                </div>
                            </div>
                        </div>
                        <div class="step-display">
                            <div class="step-badge">
                                <span class="step-text">Step</span>
                                <span class="step-number">{{ $step }}</span>
                                <span class="step-text">of 6</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar - Compact -->
                    <div class="progress-container mt-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="progress-text text-white fw-medium">{{ $step }}/6 Steps</span>
                            <span class="progress-text text-white fw-medium">{{ $this->getProgressPercentage() }}%
                                Complete</span>
                        </div>
                        <div class="progress" style="height: 10px; background: rgba(255, 255, 255, 0.2);">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $this->getProgressPercentage() }}%; 
                                    background: linear-gradient(90deg, #ffffff, #e6ffe6);"
                                aria-valuenow="{{ $this->getProgressPercentage() }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>

                        <!-- Step Indicators - Compact Horizontal -->
                        <div class="step-indicators d-flex justify-content-between mt-3">
                            @foreach ([1, 2, 3, 4, 5, 6] as $stepNumber)
                            <div class="step-item text-center" style="position: relative; flex: 1;">
                                <div class="step-circle {{ $step >= $stepNumber ? 'active' : '' }} 
                                             {{ $step == $stepNumber ? 'current' : '' }}"
                                    data-step="{{ $stepNumber }}">
                                    {{ $stepNumber }}
                                </div>
                                <div class="step-name mt-1">
                                    <span
                                        class="{{ $step >= $stepNumber ? 'text-white fw-bold' : 'text-white opacity-75' }}"
                                        style="font-size: 0.75rem; display: block; white-space: nowrap;">
                                        @switch($stepNumber)
                                        @case(1)
                                        Type
                                        @break

                                        @case(2)
                                        Personal
                                        @break

                                        @case(3)
                                        Company
                                        @break

                                        @case(4)
                                        Finance
                                        @break

                                        @case(5)
                                        {{ $partnerType === 'transport' ? 'Fleet' : 'Station' }}
                                        @break

                                        @case(6)
                                        Areas
                                        @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Box Content - Scrollable Content Area -->
                <div class="registration-content">
                    @if ($submissionSuccess)
                    <!-- Success Message - Centered -->
                    <div
                        class="success-container d-flex flex-column align-items-center justify-content-center h-100">
                        <div class="success-icon mb-3">
                            <i class="bi bi-check-circle-fill"
                                style="color: var(--primary-color); font-size: 3.5rem;"></i>
                        </div>
                        <h4 class="text-primary mb-2 text-center" style="font-weight: 700;">Registration Submitted!
                        </h4>
                        <p class="text-muted mb-4 text-center">
                            Thank you for registering as a
                            {{ $partnerType === 'transport' ? 'Transport Partner' : 'Pick-up/Drop-off Partner' }}.
                        </p>

                        <div class="row g-2 mb-4 w-100">
                            <div class="col-md-3 col-6">
                                <div class="info-card success">
                                    <i class="bi bi-clock-history" style="color: var(--primary-color);"></i>
                                    <span class="d-block mt-1 fw-medium" style="font-size: 0.85rem;">2-3 Day
                                        Review</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="info-card success">
                                    <i class="bi bi-telephone" style="color: var(--primary-color);"></i>
                                    <span class="d-block mt-1 fw-medium" style="font-size: 0.85rem;">Verification
                                        Call</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="info-card success">
                                    <i class="bi bi-envelope" style="color: var(--primary-color);"></i>
                                    <span class="d-block mt-1 fw-medium" style="font-size: 0.85rem;">Credentials
                                        Sent</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="info-card success">
                                    <i class="bi bi-box-seam" style="color: var(--primary-color);"></i>
                                    <span class="d-block mt-1 fw-medium" style="font-size: 0.85rem;">Start
                                        Immediately</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-center">
                            <button wire:click="restartRegistration" class="btn btn-outline-primary px-3">
                                <i class="bi bi-plus-circle me-1"></i> Register Another
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-primary px-3">
                                <i class="bi bi-house me-1"></i> Return Home
                            </a>
                        </div>
                    </div>
                    @else
                    <!-- Step Content - Scrollable Sections -->
                    <div class="step-content h-100">
                        @if ($step == 1)
                        <!-- Step 1: Partner Type - No Scroll Needed -->
                        <div class="step-header mb-3">
                            <h4 class="text-primary mb-1" style="font-weight: 700; font-size: 1.3rem;">
                                <i class="bi bi-people-fill me-2"></i>
                                Select Partner Type
                            </h4>
                        </div>

                        <div class="row g-2 h-100">
                            <div class="col-lg-6 d-flex">
                                <div class="partner-card {{ $partnerType == 'transport' ? 'selected' : '' }} w-100 d-flex flex-column"
                                    wire:click="selectPartnerType('transport')">
                                    <div class="card-icon transport mx-auto mb-2"
                                        style="width: 60px; height: 60px; font-size: 2rem;">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="card-content text-center flex-grow-1">
                                        <h5 class="card-title mb-1"
                                            style="font-weight: 700; font-size: 1.1rem;">Transport Partner</h5>
                                        <p class="card-text mb-2" style="font-size: 0.9rem;">Transport parcels
                                            between locations</p>

                                        <div class="card-features mb-2">
                                            <div class="feature-item" style="font-size: 0.85rem;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Registered company</span>
                                            </div>
                                            <div class="feature-item" style="font-size: 0.85rem;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Insured vehicles</span>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <span class="badge bg-primary px-2 py-1"
                                                style="background: var(--primary-color); font-size: 0.75rem;">Best
                                                for Logistics</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 d-flex">
                                <div class="partner-card {{ $partnerType == 'station' ? 'selected' : '' }} w-100 d-flex flex-column"
                                    wire:click="selectPartnerType('station')">
                                    <div class="card-icon station mx-auto mb-2"
                                        style="width: 60px; height: 60px; font-size: 2rem;">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    <div class="card-content text-center flex-grow-1">
                                        <h5 class="card-title mb-1"
                                            style="font-weight: 700; font-size: 1.1rem;">Pick-up/Drop-off</h5>
                                        <p class="card-text mb-2" style="font-size: 0.9rem;">Operate parcel
                                            PickUp/DropOff Points</p>

                                        <div class="card-features mb-2">
                                            <div class="feature-item" style="font-size: 0.85rem;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Physical location</span>
                                            </div>
                                            <div class="feature-item" style="font-size: 0.85rem;">
                                                <i class="bi bi-check-circle-fill"></i>
                                                <span>Storage space</span>
                                            </div>
                                        </div>

                                        <div class="mt-auto">
                                            <span class="badge bg-primary px-2 py-1"
                                                style="background: var(--primary-color); font-size: 0.75rem;">Best
                                                for Retail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($step == 2)
                        <!-- Step 2: Personal Information - Scrollable -->
                        <div class="step-content-scrollable">
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1" style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Personal Information
                                </h4>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">First
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="owner_first_name" placeholder="First name">
                                        @error('owner_first_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label" style="font-size: 0.9rem;">Middle
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="owner_second_name" placeholder="Middle name">
                                        @error('owner_second_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Last
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="owner_last_name" placeholder="Last name">
                                        @error('owner_last_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Email
                                            Address</label>
                                        <input type="email" class="form-control form-control-sm"
                                            wire:model="owner_email" placeholder="email@example.com">
                                        @error('owner_email')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Phone
                                            Number</label>
                                        <input type="tel" class="form-control form-control-sm"
                                            placeholder="0712345678" wire:model="owner_phone_number">
                                        @error('owner_phone_number')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="agreement-box p-2"
                                        style="background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox" id="terms"
                                                wire:model="terms_and_conditions"
                                                style="width: 1em; height: 1em;">
                                            <label class="form-check-label" for="terms"
                                                style="font-size: 0.85rem;">
                                                I agree to the <a href="#"
                                                    class="text-primary fw-bold">Terms and Conditions</a>
                                            </label>
                                        </div>
                                        @error('terms_and_conditions')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="privacy"
                                                wire:model="privacy_policy" style="width: 1em; height: 1em;">
                                            <label class="form-check-label" for="privacy"
                                                style="font-size: 0.85rem;">
                                                I agree to the <a href="#"
                                                    class="text-primary fw-bold">Privacy Policy</a>
                                            </label>
                                        </div>
                                        @error('privacy_policy')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($step == 3)
                        <!-- Step 3: Company Details - Scrollable -->
                        <div class="step-content-scrollable">
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1" style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-building me-2"></i>
                                    Company Details
                                </h4>
                            </div>

                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Company
                                            Name / Business Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="company_name" placeholder="Company name">
                                        @error('company_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Registration Number</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="registration_number" placeholder="CP12345678">
                                        @error('registration_number')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">KRA PIN
                                            Number</label>
                                        <input type="text" class="form-control form-control-sm"
                                            placeholder="A123456789Z" wire:model="kra_pin">
                                        @error('kra_pin')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Registration Certificate</label>
                                        <input type="file" class="form-control form-control-sm"
                                            wire:model="registration_certificate">
                                        @error('registration_certificate')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">KRA PIN
                                            Certificate</label>
                                        <input type="file" class="form-control form-control-sm"
                                            wire:model="pin_certificate">
                                        @error('pin_certificate')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Responsible Officer Section -->
                                <div class="col-12 mt-2 pt-2 border-top">
                                    <h6 class="text-primary mb-2" style="font-weight: 700; font-size: 1rem;">
                                        <i class="bi bi-person-badge me-2"></i>
                                        Responsible Officer
                                    </h6>
                                    <p class="text-muted mb-2" style="font-size: 0.8rem;">Primary contact
                                        person for company matters</p>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">First
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="responsible_officer_first_name"
                                            placeholder="Officer's full name">
                                        @error('responsible_officer_first_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                 <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Last
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="responsible_officer_last_name"
                                            placeholder="Officer's full name">
                                        @error('responsible_officer_last_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Phone
                                            Number</label>
                                        <input type="tel" class="form-control form-control-sm"
                                            placeholder="0712345678" wire:model="responsible_officer_phone">
                                        @error('responsible_officer_phone')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label" style="font-size: 0.9rem;">Email
                                            Address</label>
                                        <input type="email" class="form-control form-control-sm"
                                            wire:model="responsible_officer_email"
                                            placeholder="officer@company.com">
                                        @error('responsible_officer_email')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($step == 4)
                        <!-- Step 4: Finance Details - Scrollable -->
                        <div class="step-content-scrollable">
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1" style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-bank me-2"></i>
                                    Finance Details
                                </h4>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Bank
                                            Account Number</label>
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model="bank_account_number" placeholder="1234567890">
                                        @error('bank_account_number')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Account
                                            Name</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="bank_account_name" placeholder="Company Name">
                                        @error('bank_account_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Bank
                                            Name</label>
                                        <select class="form-select form-select-sm" wire:model="bank_name">
                                            <option value="">Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->name }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank_name')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required" style="font-size: 0.9rem;">Bank
                                            Branch</label>
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model="bank_branch" placeholder="Branch name">
                                        @error('bank_branch')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label" style="font-size: 0.9rem;">Finance
                                            Department Email</label>
                                        <input type="email" class="form-control form-control-sm"
                                            wire:model="finance_email" placeholder="finance@company.com">
                                        @error('finance_email')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($step == 5)
                        <!-- Step 5: Fleet/Station Details - Scrollable -->
                        <div class="step-content-scrollable">
                            @if ($partnerType == 'transport')
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1"
                                    style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-truck me-2"></i>
                                    Fleet & Operations
                                </h4>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Number of Vehicles</label>
                                        <input type="number" class="form-control form-control-sm"
                                            min="1" wire:model="vehicle_count">
                                        @error('vehicle_count')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Vehicle Ownership</label>
                                        <select class="form-select form-select-sm"
                                            wire:model="vehicle_ownership">
                                            <option value="owned">Owned</option>
                                            <option value="subcontracted">Subcontracted</option>
                                            <option value="both">Both</option>
                                        </select>
                                        @error('vehicle_ownership')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label" style="font-size: 0.9rem;">Vehicle
                                            Types</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    id="motorcycles" wire:model="has_motorcycles">
                                                <label class="form-check-label" for="motorcycles"
                                                    style="font-size: 0.85rem;">Motorcycles</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    id="vans" wire:model="has_vans">
                                                <label class="form-check-label" for="vans"
                                                    style="font-size: 0.85rem;">Vans</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                    id="trucks" wire:model="has_trucks">
                                                <label class="form-check-label" for="trucks"
                                                    style="font-size: 0.85rem;">Trucks</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="vehicles_insured" wire:model="vehicles_insured">
                                        <label class="form-check-label" for="vehicles_insured"
                                            style="font-size: 0.9rem;">Vehicles insured?</label>
                                        @error('vehicles_insured')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="vehicles_compliant" wire:model="vehicles_compliant">
                                        <label class="form-check-label" for="vehicles_compliant"
                                            style="font-size: 0.9rem;">Vehicles compliant?</label>
                                        @error('vehicles_compliant')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Number of Drivers</label>
                                        <input type="number" class="form-control form-control-sm"
                                            min="1" wire:model="driver_count">
                                        @error('driver_count')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="drivers_compliant" wire:model="drivers_compliant">
                                        <label class="form-check-label" for="drivers_compliant"
                                            style="font-size: 0.9rem;">Drivers compliant?</label>
                                        @error('drivers_compliant')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Station Partner Details - Scrollable -->
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1"
                                    style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-shop me-2"></i>
                                    Station Details
                                </h4>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label class="form-label required"
                                            style="font-size: 0.9rem;">Number of PickUp/DropOff Points</label>
                                        <input type="number" class="form-control form-control-sm"
                                            min="1" wire:model="points_count">
                                        @error('points_count')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label" style="font-size: 0.9rem;">Station
                                            Facilities</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="points_phone" wire:model="points_have_phone">
                                                <label class="form-check-label" for="points_phone"
                                                    style="font-size: 0.85rem;">Phone</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="points_computer"
                                                    wire:model="points_have_computer">
                                                <label class="form-check-label" for="points_computer"
                                                    style="font-size: 0.85rem;">Computer</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="points_internet"
                                                    wire:model="points_have_internet">
                                                <label class="form-check-label" for="points_internet"
                                                    style="font-size: 0.85rem;">Internet</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="officers_knowledgeable"
                                            wire:model="officers_knowledgeable">
                                        <label class="form-check-label" for="officers_knowledgeable"
                                            style="font-size: 0.9rem;">Officers knowledgeable?</label>
                                        @error('officers_knowledgeable')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="points_compliant" wire:model="points_compliant">
                                        <label class="form-check-label" for="points_compliant"
                                            style="font-size: 0.9rem;">PickUp/DropOff Points compliant?</label>
                                        @error('points_compliant')
                                        <div class="error-message"
                                            style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @elseif($step == 6)
                        <!-- Step 6: Service Areas - Scrollable -->
                        <div class="step-content-scrollable">
                            <div class="step-header mb-3">
                                <h4 class="text-primary mb-1" style="font-weight: 700; font-size: 1.3rem;">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    Service Areas
                                </h4>
                            </div>

                            <div class="service-areas-container">
                                <div class="form-group mb-2">
                                    <label class="form-label required" style="font-size: 0.9rem;">Select
                                        Service Towns</label>
                                    <select class="form-select" wire:model="selectedTowns" multiple
                                        style="height: 180px; font-size: 0.9rem;">
                                        @foreach ($towns as $town)
                                        <option value="{{ $town->id }}">
                                            {{ $town->name }} - {{ $town->subcounty->county->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text mt-1" style="font-size: 0.8rem;">Hold Ctrl (Cmd on
                                        Mac) to select multiple towns</div>
                                    @error('selectedTowns')
                                    <div class="error-message"
                                        style="color: #dc3545; font-size: 0.8rem; font-weight: 500;">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                @if (count($selectedTowns) > 0)
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="text-primary fw-medium"
                                            style="font-size: 0.9rem;">Selected
                                            ({{ count($selectedTowns) }} towns):</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="$set('selectedTowns', [])"
                                            style="padding: 2px 8px; font-size: 0.8rem;">
                                            Clear All
                                        </button>
                                    </div>
                                    <div class="selected-tags"
                                        style="max-height: 80px; overflow-y: auto; padding: 5px; border: 1px solid #e9ecef; border-radius: 8px; background: #f8f9fa;">
                                        @foreach ($serviceTowns as $town)
                                        <span class="selected-tag d-inline-block me-1 mb-1"
                                            style="background: rgba(0, 143, 64, 0.1); color: var(--primary-color); padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">
                                            {{ $town->name }}
                                            <button type="button"
                                                class="tag-remove border-0 bg-transparent ms-1"
                                                wire:click="removeTown({{ $town->id }})"
                                                style="color: var(--primary-color); font-size: 0.9rem; padding: 0;">
                                                ×
                                            </button>
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Box Footer - Fixed at Bottom -->
                <div class="registration-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="navigation-left">
                            @if ($step > 1 && !$submissionSuccess)
                            <button type="button" class="btn btn-secondary px-3" wire:click="previousStep"
                                style="background: linear-gradient(135deg, #6c757d, #495057); border: none; color: white; font-weight: 600; font-size: 0.9rem;">
                                <i class="bi bi-arrow-left me-1"></i> Previous
                            </button>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            @if (!$submissionSuccess)
                            @if ($step < 6)
                                <button type="button" class="btn btn-primary px-3" wire:click="nextStep"
                                style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border: none; color: white; font-weight: 600; font-size: 0.9rem;">
                                Continue <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-success px-3"
                                    wire:click="submitRegistration" wire:loading.attr="disabled"
                                    wire:target="submitRegistration"
                                    style="background: linear-gradient(135deg, #28a745, #218838); border: none; color: white; font-weight: 600; font-size: 0.9rem;">
                                    <span wire:loading.remove wire:target="submitRegistration">
                                        <i class="bi bi-check-circle me-1"></i> Submit
                                    </span>
                                    <span wire:loading wire:target="submitRegistration">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Processing...
                                    </span>
                                </button>
                                @endif
                                @endif

                                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-3"
                                    style="border: 2px solid #6c757d; color: #6c757d; font-weight: 600; font-size: 0.9rem;">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                        </div>
                    </div>

                    <!-- Info Section - Compact -->
                    <div class="info-section mt-3 pt-3 border-top">
                        <div class="row g-2">
                            <div class="col-4 text-center">
                                <div class="info-item">
                                    <i class="bi bi-shield-check"
                                        style="color: var(--primary-color); font-size: 1.2rem;"></i>
                                    <h6 class="mt-1 mb-0 fw-bold" style="font-size: 0.8rem;">Secure</h6>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="info-item">
                                    <i class="bi bi-clock-history"
                                        style="color: var(--primary-color); font-size: 1.2rem;"></i>
                                    <h6 class="mt-1 mb-0 fw-bold" style="font-size: 0.8rem;">Fast</h6>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="info-item">
                                    <i class="bi bi-headset"
                                        style="color: var(--primary-color); font-size: 1.2rem;"></i>
                                    <h6 class="mt-1 mb-0 fw-bold" style="font-size: 0.8rem;">Support</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php

namespace App\Livewire\Partners\Auth;


use App\Models\Bank;
use App\Models\Town;
use App\Models\County;
use App\Models\Partner;
use App\Models\PartnerFinanceAccount;
use App\Models\PartnerInCharge;
use App\Models\PartnerOwner;
use App\Models\PartnerTown;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PartnerRegistration extends Component
{
    use WithFileUploads;

    public $step = 1;
    public $partnerType;

    // Common Fields
    public $owner_first_name;
    public $owner_second_name;
    public $owner_last_name;
    public $owner_email;
    public $owner_phone_number;
    public $terms_and_conditions = false;
    public $privacy_policy = false;

    // Company Details
    public $company_name;
    public $registration_number;
    public $registration_certificate;
    public $kra_pin;
    public $pin_certificate;
    public $responsible_officer_first_name;
    public $responsible_officer_last_name;
    public $responsible_officer_phone;
    public $responsible_officer_email;

    // Finance Details
    public $bank_account_number;
    public $bank_account_name;
    public $bank_name;
    public $bank_branch;
    public $finance_email;

    // Transport Partner Specific
    public $vehicle_count = 1;
    public $vehicle_ownership = 'owned';
    public $vehicles_insured = false;
    public $insurance_certificate;
    public $vehicles_compliant = false;
    public $compliance_certificate;
    public $driver_count = 1;
    public $drivers_compliant = false;
    public $drivers_certificate;

    public $has_motorcycles = false;
    public $has_vans = false;
    public $has_trucks = false;
    public $has_refrigerated = false;
    public $other_vehicle_types = '';

    public $has_computer = false;
    public $has_internet = false;
    public $booking_emails = [];
    public $booking_email_input = '';
    public $has_dedicated_allocator = false;
    public $allocator_name = '';
    public $allocator_phone = '';

    public $maximum_daily_capacity = 50;
    public $maximum_distance = 100;
    public $operating_radius = 50;
    public $can_handle_fragile = false;
    public $can_handle_perishable = false;
    public $can_handle_valuables = false;

    // Station Partner Specific
    public $points_count = 1;
    public $points_have_phone = false;
    public $points_have_computer = false;
    public $points_have_internet = false;
    public $officers_knowledgeable = false;
    public $points_compliant = false;
    public $station_compliance_certificate;

    public $operating_hours = '8:00 AM - 6:00 PM';
    public $maximum_capacity_per_day = 100;
    public $storage_facility_type = 'standard';
    public $security_measures = '';
    public $insurance_coverage = '';

    // Service Areas
    public $selectedCounties = [];
    public $selectedSubcounties = [];
    public $selectedTowns = [];
    public $availableTowns = [];
    public $serviceTowns = [];

    // Additional
    public $additional_notes = '';

    // System
    public $submissionSuccess = false;
    public $partnerId = null;

    public $banks = [];

    protected $rules = [
        // Step 1: Partner Type
        'partnerType' => 'required|in:transport,station',

        // Step 2: Personal Information
        'owner_first_name' => 'required|min:2|max:50',
        'owner_second_name' => 'nullable|min:2|max:50',
        'owner_last_name' => 'required|min:2|max:50',
        'owner_email' => 'required|email|unique:users,email',
        'owner_phone_number' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed regex
        'terms_and_conditions' => 'accepted',
        'privacy_policy' => 'accepted',

        // Step 3: Company Details
        'company_name' => 'required|min:3|max:200',
        'registration_number' => 'required|min:3|max:50',
        'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'kra_pin' => ['required', 'min:11', 'max:11', 'regex:/^[A-Z]\d{9}[A-Z]$/'], // Fixed regex
        'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'responsible_officer_first_name' => 'required|min:3|max:100',
        'responsible_officer_last_name' => 'required|min:3|max:100',

        'responsible_officer_phone' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed regex
        'responsible_officer_email' => 'nullable|email',

        // Step 4: Finance Details
        'bank_account_number' => 'required|numeric|digits_between:10,20',
        'bank_account_name' => 'required|min:3|max:100',
        'bank_name' => 'required|min:3|max:100',
        'bank_branch' => 'required|min:3|max:100',
        'finance_email' => 'nullable|email',

        // Transport Specific Rules
        'vehicle_count' => 'nullable|integer|min:1|max:1000',
        'vehicle_ownership' => 'nullable|in:owned,subcontracted,both',
        'vehicles_insured' => 'nullable|boolean',
        'insurance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'vehicles_compliant' => 'nullable|boolean',
        'compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'driver_count' => 'nullable|integer|min:1|max:100',
        'drivers_compliant' => 'nullable|boolean',
        'drivers_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        // Station Specific Rules
        'points_count' => 'nullable|integer|min:1|max:100',
        'points_have_phone' => 'nullable|boolean',
        'points_have_computer' => 'nullable|boolean',
        'points_have_internet' => 'nullable|boolean',
        'officers_knowledgeable' => 'nullable|boolean',
        'points_compliant' => 'nullable|boolean',
        // 'station_compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];

    protected $messages = [
        'phone_number.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678 or +254712345678)',
        'kra_pin.regex' => 'Please enter a valid KRA PIN (format: A123456789Z)',
        'terms_and_conditions.accepted' => 'You must accept the terms and conditions',
        'privacy_policy.accepted' => 'You must accept the privacy policy',
        'registration_certificate.required' => 'Registration certificate is required',
        'pin_certificate.required' => 'KRA PIN certificate is required',
        'insurance_certificate.required_if' => 'Insurance certificate is required when vehicles are insured',
        'compliance_certificate.required_if' => 'Compliance certificate is required when vehicles are compliant',
        'drivers_certificate.required_if' => 'Drivers certificate is required when drivers are compliant',
        // 'station_compliance_certificate.required_if' => 'Compliance certificate is required when points are compliant',
        'selectedTowns.required' => 'Please select at least one service town',
    ];

    public function mount()
    {
        $this->banks = Bank::all();
        $this->selectedTowns = [];
        $this->availableTowns = Town::with('subCounty.county')
            ->orderBy('name')
            ->get()
            ->map(function ($town) {
                return [
                    'id' => $town->id,
                    'name' => $town->name,
                    'subcounty_id' => $town->subcounty_id,
                    'subcounty' => $town->subcounty ? [
                        'id' => $town->subcounty->id,
                        'name' => $town->subcounty->name,
                        'county_id' => $town->subcounty->county_id,
                        'county' => $town->subcounty->county ? [
                            'id' => $town->subcounty->county->id,
                            'name' => $town->subcounty->county->name,
                        ] : null,
                    ] : null,
                ];
            })
            ->groupBy(function ($town) {
                return $town['subCounty']['county']['name'] ?? 'Unknown';
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.partners.auth.partner-registration', [
            'counties' => County::with('subCounties.towns')->orderBy('name')->get(),
            'towns' => Town::orderBy('name')->get(),
            'availableTowns' => $this->availableTowns,
        ]);
    }

    public function nextStep()
    {

        Log::info('Step' . $this->step);

        if ($this->step == 3) {
            dd([
                'owner_first_name' => $this->owner_first_name,
                'owner_second_name' => $this->owner_second_name,
                'owner_last_name' => $this->owner_last_name,
                'owner_email' => $this->owner_email,
                'owner_phone_number' => $this->owner_phone_number,
                'terms_and_conditions' => $this->terms_and_conditions,
                'privacy_policy' => $this->privacy_policy,
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                'registration_certificate' => $this->registration_certificate,
                'kra_pin' => $this->kra_pin,
                'pin_certificate' => $this->pin_certificate,
                'responsible_officer_first_name' => $this->responsible_officer_first_name,
                'responsible_officer_last_name' => $this->responsible_officer_last_name,
                'responsible_officer_phone' => $this->responsible_officer_phone,
                'responsible_officer_email' => $this->responsible_officer_email,
                'bank_account_number' => $this->bank_account_number,
                'bank_account_name' => $this->bank_account_name,
                'bank_name' => $this->bank_name,
                'bank_branch' => $this->bank_branch,
                'finance_email' => $this->finance_email,
            ]);
        }
        // $this->validateCurrentStep();
        $this->step++;
        Log::info('Step' . $this->step);


        // Scroll to top
        $this->dispatch('scroll-to-top');
    }

    public function previousStep()
    {
        $this->step--;
        $this->dispatch('scroll-to-top');
    }

    public function selectPartnerType($type)
    {
        $this->partnerType = $type;
        $this->nextStep();
    }

    public function addBookingEmail()
    {
        if (!empty($this->booking_email_input)) {
            $this->booking_emails[] = $this->booking_email_input;
            $this->booking_email_input = '';
        }
    }

    public function removeBookingEmail($index)
    {
        unset($this->booking_emails[$index]);
        $this->booking_emails = array_values($this->booking_emails);
    }

    public function updatedSelectedCounties($value)
    {
        $this->selectedSubcounties = [];
        $this->selectedTowns = [];
        $this->serviceTowns = [];
    }

    public function updatedSelectedSubcounties($value)
    {
        $this->selectedTowns = [];
        $this->serviceTowns = [];
    }

    public function updatedSelectedTowns($value)
    {
        $this->loadServiceTowns();
    }

    protected function loadServiceTowns()
    {
        if (empty($this->selectedTowns)) {
            $this->serviceTowns = [];
            return;
        }

        // Load towns from database based on selected IDs
        $this->serviceTowns = Town::whereIn('id', $this->selectedTowns)->get();
    }

    private function validateCurrentStep()
    {
        switch ($this->step) {
            case 1:
                $this->validateOnly('partnerType');
                break;

            case 2:
                $this->validate([
                    'owner_first_name' => 'required|min:2|max:50',
                    'owner_last_name' => 'required|min:2|max:50',
                    'owner_email' => 'required|email|unique:users,email',
                    'owner_phone_number' => 'required', // Fixed
                    // 'owner_phone_number' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed
                    'terms_and_conditions' => 'accepted',
                    'privacy_policy' => 'accepted',
                ]);
                break;

            case 3:
                $rules = [
                    'company_name' => 'required|min:3|max:200',
                    'registration_number' => 'required|min:3|max:50',
                    'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'kra_pin' => ['required', 'min:11', 'max:11', 'regex:/^[A-Z]\d{9}[A-Z]$/'], // Fixed
                    'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'responsible_officer_first_name' => 'required|min:3|max:100',
                    'responsible_officer_last_name' => 'required|min:3|max:100',

                    // 'responsible_officer_phone' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed
                    'responsible_officer_phone' => 'required', // Fixed

                    'responsible_officer_email' => 'nullable|email',
                ];

                $this->validate($rules);
                break;

            case 4:
                $rules = [
                    'bank_account_number' => 'required|numeric|digits_between:10,20',
                    'bank_account_name' => 'required|min:3|max:100',
                    'bank_name' => 'required|min:3|max:100',
                    'bank_branch' => 'required|min:3|max:100',
                    'finance_email' => 'nullable|email',
                ];

                $this->validate($rules);
                break;

            case 5:
                if ($this->partnerType === 'transport') {
                    $rules = [
                        'vehicle_count' => 'required|integer|min:1|max:1000',
                        'vehicle_ownership' => 'required|in:owned,subcontracted,both',
                        'vehicles_insured' => 'required|boolean',
                        'vehicles_compliant' => 'required|boolean',
                        'driver_count' => 'required|integer|min:1|max:100',
                        'drivers_compliant' => 'required|boolean',
                    ];

                    if ($this->vehicles_insured) {
                        $rules['insurance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }

                    if ($this->vehicles_compliant) {
                        $rules['compliance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }

                    if ($this->drivers_compliant) {
                        $rules['drivers_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                } else {
                    $rules = [
                        'points_count' => 'required|integer|min:1|max:100',
                        'points_have_phone' => 'required|boolean',
                        'points_have_computer' => 'required|boolean',
                        'points_have_internet' => 'required|boolean',
                        'officers_knowledgeable' => 'required|boolean',
                        'points_compliant' => 'required|boolean',
                    ];

                    if ($this->points_compliant) {
                        $rules['station_compliance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                }

                $this->validate($rules);
                break;

            case 6:
                $rules = [
                    'selectedTowns' => 'required|array|min:1',
                    'selectedTowns.*' => 'exists:towns,id',
                ];

                $this->validate($rules);
                break;
        }
    }
    public function submitRegistration()
    {

        dd($this->all());

        Log::info('Step 2 Data before validation:', [
            'owner_first_name' => $this->owner_first_name,
            'owner_last_name' => $this->owner_last_name,
            'owner_email' => $this->owner_email,
            'owner_phone_number' => $this->owner_phone_number,
        ]);



        // dd($this->all());
        $this->validateCurrentStep();
        Log::info('Partner Registration started');
        Log::info('selectedTowns type:', ['type' => gettype($this->selectedTowns)]);
        Log::info('selectedTowns value:', ['value' => $this->selectedTowns]);
        // Check what type selectedTowns is
        if (!is_array($this->selectedTowns)) {
            Log::error('selectedTowns is not an array! Type: ' . gettype($this->selectedTowns));

            // Try to convert it to array if it's a string
            if (is_string($this->selectedTowns)) {
                $this->selectedTowns = json_decode($this->selectedTowns, true) ?? [];
                Log::info('Converted string to array:', $this->selectedTowns);
            } elseif ($this->selectedTowns === null) {
                $this->selectedTowns = [];
                Log::info('Set null to empty array');
            }
        }
        try {
            DB::beginTransaction();
            // TODO::HAndle Documents
            $data = [
                // Step 1: Partner Type
                'partnerType' => $this->partnerType,

                // Step 2: Personal Information
                'owner_first_name' => $this->owner_first_name,
                'owner_second_name' => $this->owner_second_name,
                'owner_last_name' => $this->owner_last_name,
                'owner_email' => $this->owner_email,
                'owner_phone_number' => $this->owner_phone_number,
                'terms_and_conditions' => $this->terms_and_conditions,
                'privacy_policy' => $this->privacy_policy,

                // Step 3: Company Details
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                'kra_pin' => $this->kra_pin,
                'responsible_officer_first_name' => $this->responsible_officer_first_name,
                'responsible_officer_last_name' => $this->responsible_officer_last_name,
                'responsible_officer_phone' => $this->responsible_officer_phone,
                'responsible_officer_email' => $this->responsible_officer_email,
                'registration_certificate' => $this->registration_certificate,
                'pin_certificate' => $this->pin_certificate,

                // Step 4: Finance Details
                'bank_account_number' => $this->bank_account_number,
                'bank_account_name' => $this->bank_account_name,
                'bank_name' => $this->bank_name,
                'bank_branch' => $this->bank_branch,
                'finance_email' => $this->finance_email,

                // Step 5: Fleet/Station Details
                'vehicle_count' => $this->vehicle_count ?? null,
                'vehicle_ownership' => $this->vehicle_ownership ?? null,
                'vehicles_insured' => $this->vehicles_insured ?? null,
                'vehicles_compliant' => $this->vehicles_compliant ?? null,
                'driver_count' => $this->driver_count ?? null,
                'drivers_compliant' => $this->drivers_compliant ?? null,
                'has_motorcycles' => $this->has_motorcycles ?? false,
                'has_vans' => $this->has_vans ?? false,
                'has_trucks' => $this->has_trucks ?? false,
                'has_refrigerated' => $this->has_refrigerated ?? false,
                'has_computer' => $this->has_computer ?? false,
                'has_internet' => $this->has_internet ?? false,
                'booking_emails' => $this->booking_emails ?? null,
                'has_dedicated_allocator' => $this->has_dedicated_allocator ?? false,
                'allocator_name' => $this->allocator_name ?? null,
                'allocator_phone' => $this->allocator_phone ?? null,
                'maximum_daily_capacity' => $this->maximum_daily_capacity ?? null,
                'maximum_distance' => $this->maximum_distance ?? null,
                'operating_radius' => $this->operating_radius ?? null,
                'can_handle_fragile' => $this->can_handle_fragile ?? false,
                'can_handle_perishable' => $this->can_handle_perishable ?? false,
                'can_handle_valuables' => $this->can_handle_valuables ?? false,

                // Station specific fields
                'points_count' => $this->points_count ?? null,
                'points_have_phone' => $this->points_have_phone ?? false,
                'points_have_computer' => $this->points_have_computer ?? false,
                'points_have_internet' => $this->points_have_internet ?? false,
                'officers_knowledgeable' => $this->officers_knowledgeable ?? false,
                'points_compliant' => $this->points_compliant ?? false,
                'operating_hours' => $this->operating_hours ?? null,
                'maximum_capacity_per_day' => $this->maximum_capacity_per_day ?? null,
                'storage_facility_type' => $this->storage_facility_type ?? null,
                'security_measures' => $this->security_measures ?? null,
                'insurance_coverage' => $this->insurance_coverage ?? null,

                // Step 6: Service Areas
                'selectedTowns' => $this->selectedTowns,

                // Additional fields that might be in blade
                'insurance_certificate' => $this->insurance_certificate ?? null,
                'compliance_certificate' => $this->compliance_certificate ?? null,
                'drivers_certificate' => $this->drivers_certificate ?? null,
                'station_compliance_certificate' => $this->station_compliance_certificate ?? null,
                'other_vehicle_types' => $this->other_vehicle_types ?? null,
                'additional_notes' => $this->additional_notes ?? null,
                'years_in_operation' => $this->years_in_operation ?? null,
                'previous_courier_experience' => $this->previous_courier_experience ?? null,
                'insurance_coverage_amount' => $this->insurance_coverage_amount ?? null,
                'safety_measures' => $this->safety_measures ?? null,
                'tracking_system' => $this->tracking_system ?? null,
            ];
            $partner = Partner::create($data);
            $owner = User::create([
                'first_name'  => $data['owner_first_name'],
                'second_name' => $data['owner_second_name'],
                'last_name' => $data['owner_last_name'],
                'user_name' => explode('@', $data['owner_email'])[0],
                'user_type' => $data['partnerType'],
                'email' => $data['owner_email'],
                'phone_number' => $data['owner_phone_number'],
                'password' => Hash::make('password'),
                'terms_and_conditions' => $data['terms_and_conditions'],
                'privacy_policy' => $data['privacy_policy'],
                'status' => 'active',
            ]);

            $inCharge = User::create([
                'first_name'  => $data['responsible_officer_first_name'],
                'last_name' => $data['responsible_officer_last_name'],
                'user_name' => explode('@', $data['responsible_officer_email'])[0],
                'user_type' => NULL,
                'email' => $data['responsible_officer_email'],
                'phone_number' => $data['responsible_officer_phone'],
                'password' => Hash::make('password'),
                'terms_and_conditions' => $data['terms_and_conditions'],
                'privacy_policy' => $data['privacy_policy'],
                'status' => 'active',

            ]);
            Log::info('Created Partner' . $partner);
            Log::info('Created Owner' . $owner);
            Log::info('Created In Charge' . $inCharge);
            $partnerOwner = PartnerOwner::create([
                'partner_id' => $partner->id,
                'user_id' => $owner->id,
                'from' => null,
                'to' => null,
                'status' => 'active',
            ]);
            $partnerInCharge = PartnerInCharge::create([
                'partner_id' => $partner->id,
                'user_id' => $inCharge->id,
                'from' => null,
                'to' => null,
                'status' => 'active',
            ]);
            $partnerFinanceAccount = PartnerFinanceAccount::create([
                'partner_id' => $partner->id,
                'bank_account_number' => $this->bank_account_number ?? null,
                'bank_account_name' => $this->bank_account_name ?? null,
                'bank_name' => $this->bank_name ?? null,
                'bank_branch' => $this->bank_branch ?? null,
                'finance_email' => $this->finance_email ?? null,
            ]);
            if (is_array($this->selectedTowns) && count($this->selectedTowns) > 0) {
                foreach ($this->selectedTowns as $index => $townId) {
                    $serviceArea = new PartnerTown([
                        'partner_id' => $partner->id,
                        'town_id' => $townId,
                        'status' => 'active',
                    ]);
                    $serviceArea->save();
                }
            }


            // TODO: Send email notification to Admin, Owner, Incharge

            // TODO: Send confirmation email to partner
            // TODO  Send sms notification to partner

            $this->submissionSuccess = true;
            $this->step = 7;

            DB::commit();

            return redirect()->route('partners.login')->with('success', 'Account created successfully. Please verify email to login');
        } catch (\Exception $e) {
            dd('Error:' . $e->getMessage());
            session()->flash('error', 'Registration failed: ' . $e->getMessage());
        }
    }

      public function restartRegistration()
    {
        $this->reset();
        $this->step = 1;
        $this->submissionSuccess = false;
        $this->partnerId = null;
    }

    public function getStepTitle()
    {
        $titles = [
            1 => 'Select Partner Type',
            2 => 'Personal Information',
            3 => 'Company Details',
            4 => 'Finance Details',
            5 => $this->partnerType === 'transport' ? 'Fleet & Operations' : 'Station Details',
            6 => 'Service Areas',
            7 => 'Registration Complete',
        ];

        return $titles[$this->step] ?? 'Step ' . $this->step;
    }

    public function getProgressPercentage()
    {
        return round(($this->step / 6) * 100);
    }

    public function removeTown($townId)
    {
        // Ensure selectedTowns is an array
        if (!is_array($this->selectedTowns)) {
            $this->selectedTowns = [];
        }

        $this->selectedTowns = array_filter($this->selectedTowns, function ($id) use ($townId) {
            return $id != $townId;
        });

        // Re-index array
        $this->selectedTowns = array_values($this->selectedTowns);

        // Update service towns
        if (count($this->selectedTowns) > 0) {
            $this->serviceTowns = Town::whereIn('id', $this->selectedTowns)->get();
        } else {
            $this->serviceTowns = collect();
        }
    }
}