<div>
    <div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 1rem;">
    <div class="registration-wrapper" style="max-width: 1100px; width: 100%; max-height: 95vh;">
        <div class="registration-card shadow-lg" style="height: 100%; display: flex; flex-direction: column;">
            <!-- Header Section -->
            <div class="registration-header bg-success text-white rounded-top-3" style="flex-shrink: 0;">
                <div class="container-fluid py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="logo-circle bg-white text-success d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px; border-radius: 10px; font-size: 0.9rem;">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <h3 class="mb-0 fw-bold" style="font-size: 1.1rem;">Partner Registration</h3>
                                    <p class="mb-0 opacity-90" style="font-size: 0.75rem;">Become a Karibu Parcels Partner</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="step-badge bg-white text-success px-2 py-1 rounded-pill d-inline-block" style="font-size: 0.8rem;">
                                <span class="fw-semibold">Step {{ $step }} of 6</span>
                                <span class="ms-1 badge bg-success text-white" style="font-size: 0.7rem; padding: 2px 6px;">
                                    {{ $this->getProgressPercentage() }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small" style="font-size: 0.75rem;">Progress</span>
                            <span class="small fw-semibold" style="font-size: 0.75rem;">{{ $this->getProgressPercentage() }}% Complete</span>
                        </div>
                        <div class="progress" style="height: 6px; background: rgba(255, 255, 255, 0.2);">
                            <div class="progress-bar bg-white" 
                                 role="progressbar" 
                                 style="width: {{ $this->getProgressPercentage() }}%"
                                 aria-valuenow="{{ $this->getProgressPercentage() }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        
                        <!-- Step Indicators -->
                        <div class="step-indicators d-flex justify-content-between mt-3">
                            @foreach(['Type', 'Personal', 'Company', 'Finance', 'Operations', 'Areas'] as $index => $label)
                            <div class="step-indicator text-center position-relative" style="flex: 1; padding: 0 2px;">
                                <div class="step-circle mx-auto {{ $step == ($index + 1) ? 'active' : ($step > ($index + 1) ? 'completed' : '') }}"
                                     style="width: 30px; height: 30px; border-radius: 50%; background: {{ $step > ($index + 1) ? '#28a745' : ($step == ($index + 1) ? '#ffffff' : 'rgba(255,255,255,0.2)') }};
                                            border: 2px solid {{ $step >= ($index + 1) ? '#ffffff' : 'rgba(255,255,255,0.3)' }};
                                            color: {{ $step >= ($index + 1) ? ($step == ($index + 1) ? '#28a745' : '#ffffff') : 'rgba(255,255,255,0.7)' }};
                                            display: flex; align-items: center; justify-content: center;
                                            font-weight: 600; font-size: 0.75rem;">
                                    @if($step > ($index + 1))
                                    <i class="bi bi-check" style="font-size: 0.8rem;"></i>
                                    @else
                                    {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="step-label mt-1">
                                    <span class="fw-medium" style="color: rgba(255,255,255,0.9); font-size: 0.7rem; display: block; white-space: nowrap;">
                                        @if($index == 4)
                                            {{ $partnerType === 'transport' ? 'Fleet' : 'Station' }}
                                        @else
                                            {{ $label }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content - Scrollable only for Step 3 -->
            <div class="registration-body bg-white p-3" style="flex: 1; overflow: {{ $step == 3 ? 'auto' : 'visible' }};">
                @if($submissionSuccess)
                <!-- Success State -->
                <div class="success-state text-center py-3">
                    <div class="success-icon mb-3">
                        <div class="mx-auto rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" 
                             style="width: 70px; height: 70px;">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h3 class="text-success fw-bold mb-2" style="font-size: 1.1rem;">Registration Submitted!</h3>
                    <p class="text-muted mb-3 mx-auto" style="max-width: 400px; font-size: 0.85rem;">
                        Thank you for registering as a 
                        <span class="fw-semibold text-success">{{ $partnerType === 'transport' ? 'Transport Partner' : 'Pick-up/Drop-off Partner' }}</span>.
                    </p>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-3">
                            <div class="info-card text-center p-2 border rounded-2">
                                <i class="bi bi-clock-history text-success mb-1" style="font-size: 0.9rem;"></i>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.7rem;">2-3 Day Review</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-card text-center p-2 border rounded-2">
                                <i class="bi bi-telephone text-success mb-1" style="font-size: 0.9rem;"></i>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.7rem;">Verification Call</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-card text-center p-2 border rounded-2">
                                <i class="bi bi-envelope text-success mb-1" style="font-size: 0.9rem;"></i>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.7rem;">Credentials Email</h6>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-card text-center p-2 border rounded-2">
                                <i class="bi bi-box-seam text-success mb-1" style="font-size: 0.9rem;"></i>
                                <h6 class="mb-0 fw-semibold" style="font-size: 0.7rem;">Start Operations</h6>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-center">
                        <button wire:click="restartRegistration" class="btn btn-outline-success px-3 py-1" style="font-size: 0.85rem;">
                            <i class="bi bi-plus-circle me-1"></i> Register Another
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-success px-3 py-1" style="font-size: 0.85rem;">
                            <i class="bi bi-house me-1"></i> Return Home
                        </a>
                    </div>
                </div>
                @else
                <!-- Form Steps -->
                <div class="step-content">
                    <!-- Step 1: Partner Type -->
                    @if($step == 1)
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-people-fill me-1"></i>
                            Select Partner Type
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Choose how you want to partner with Karibu Parcels</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="partner-card card border-2 h-100 {{ $partnerType == 'transport' ? 'border-success' : 'border-light' }} 
                                       {{ $partnerType == 'transport' ? 'shadow-sm' : '' }} hover-shadow"
                                 wire:click="selectPartnerType('transport')"
                                 style="cursor: pointer; transition: all 0.3s; min-height: 220px;">
                                <div class="card-body p-3">
                                    <div class="text-center mb-2">
                                        <div class="icon-wrapper mx-auto mb-2" 
                                             style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05)); 
                                                    border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-truck text-success" style="font-size: 1.8rem;"></i>
                                        </div>
                                        <h5 class="card-title fw-bold text-success mb-1" style="font-size: 0.9rem;">Transport Partner</h5>
                                        <p class="card-text text-muted mb-2" style="font-size: 0.75rem;">Deliver parcels between locations</p>
                                    </div>
                                    
                                    <ul class="list-unstyled mb-2">
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Registered company</span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Insured vehicles</span>
                                        </li>
                                        <li class="mb-0">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Certified drivers</span>
                                        </li>
                                    </ul>
                                    
                                    <div class="text-center mt-2">
                                        <span class="badge bg-success bg-opacity-10 text-success py-1 px-2" style="font-size: 0.7rem;">
                                            <i class="bi bi-star-fill me-1"></i> Best for Logistics
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="partner-card card border-2 h-100 {{ $partnerType == 'pickup-dropoff' ? 'border-success' : 'border-light' }} 
                                       {{ $partnerType == 'pickup-dropoff' ? 'shadow-sm' : '' }} hover-shadow"
                                 wire:click="selectPartnerType('pickup-dropoff')"
                                 style="cursor: pointer; transition: all 0.3s; min-height: 220px;">
                                <div class="card-body p-3">
                                    <div class="text-center mb-2">
                                        <div class="icon-wrapper mx-auto mb-2" 
                                             style="width: 60px; height: 60px; background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05)); 
                                                    border-radius: 15px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-shop text-success" style="font-size: 1.8rem;"></i>
                                        </div>
                                        <h5 class="card-title fw-bold text-success mb-1" style="font-size: 0.9rem;">Pick-up/Drop-off</h5>
                                        <p class="card-text text-muted mb-2" style="font-size: 0.75rem;">Operate parcel collection points</p>
                                    </div>
                                    
                                    <ul class="list-unstyled mb-2">
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Physical location</span>
                                        </li>
                                        <li class="mb-1">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Storage space</span>
                                        </li>
                                        <li class="mb-0">
                                            <i class="bi bi-check-circle-fill text-success me-1" style="font-size: 0.8rem;"></i>
                                            <span style="font-size: 0.75rem;">Business registration</span>
                                        </li>
                                    </ul>
                                    
                                    <div class="text-center mt-2">
                                        <span class="badge bg-success bg-opacity-10 text-success py-1 px-2" style="font-size: 0.7rem;">
                                            <i class="bi bi-star-fill me-1"></i> Best for Retail
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 2: Personal Information -->
                    @if($step == 2)
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-person-badge me-1"></i>
                            Personal Information
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Owner/Proprietor details</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('owner_first_name') ? 'is-invalid' : '' }}" 
                                       placeholder="First name" wire:model="owner_first_name">
                                @error('owner_first_name')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Middle Name</label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('owner_second_name') ? 'is-invalid' : '' }}" 
                                       placeholder="Middle name" wire:model="owner_second_name">
                                @error('owner_second_name')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('owner_last_name') ? 'is-invalid' : '' }}" 
                                       placeholder="Last name" wire:model="owner_last_name">
                                @error('owner_last_name')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-sm {{ $errors->has('owner_email') ? 'is-invalid' : '' }}" 
                                       placeholder="email@example.com" wire:model="owner_email">
                                @error('owner_email')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control form-control-sm {{ $errors->has('owner_phone_number') ? 'is-invalid' : '' }}" 
                                       placeholder="0712345678" wire:model="owner_phone_number">
                                @error('owner_phone_number')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12 mt-3">
                            <div class="card border-light">
                                <div class="card-body p-3">
                                    <h6 class="fw-semibold mb-2 text-success" style="font-size: 0.85rem;">
                                        <i class="bi bi-shield-check me-1"></i>Agreements
                                    </h6>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input {{ $errors->has('terms_and_conditions') ? 'is-invalid' : '' }}" 
                                               type="checkbox" id="terms" wire:model="terms_and_conditions">
                                        <label class="form-check-label" for="terms" style="font-size: 0.8rem;">
                                            I agree to the <a href="#" class="text-success fw-semibold">Terms and Conditions</a>
                                        </label>
                                        @error('terms_and_conditions')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input {{ $errors->has('privacy_policy') ? 'is-invalid' : '' }}" 
                                               type="checkbox" id="privacy" wire:model="privacy_policy">
                                        <label class="form-check-label" for="privacy" style="font-size: 0.8rem;">
                                            I agree to the <a href="#" class="text-success fw-semibold">Privacy Policy</a>
                                        </label>
                                        @error('privacy_policy')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 3: Company Details - SCROLLABLE SECTION -->
                    @if($step == 3)
                    <div class="company-details-scrollable" style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                        <div class="step-header mb-3">
                            <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                                <i class="bi bi-building me-1"></i>
                                Company Details
                            </h4>
                            <p class="text-muted mb-2" style="font-size: 0.8rem;">Business registration information</p>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('company_name') ? 'is-invalid' : '' }}" 
                                           placeholder="Your company name" wire:model="company_name">
                                    @error('company_name')
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Registration Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('registration_number') ? 'is-invalid' : '' }}" 
                                           placeholder="CP12345678" wire:model="registration_number">
                                    @error('registration_number')
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">KRA PIN <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm {{ $errors->has('kra_pin') ? 'is-invalid' : '' }}" 
                                           placeholder="A123456789Z" wire:model="kra_pin">
                                    @error('kra_pin')
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-file-earmark-text me-1"></i> Registration Certificate
                                    </label>
                                    <input type="file" class="form-control form-control-sm {{ $errors->has('registration_certificate') ? 'is-invalid' : '' }}" 
                                           wire:model="registration_certificate">
                                    <small class="form-text text-muted" style="font-size: 0.7rem;">PDF, JPG, or PNG (Max 5MB)</small>
                                    @error('registration_certificate')
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-file-earmark-text me-1"></i> KRA PIN Certificate
                                    </label>
                                    <input type="file" class="form-control form-control-sm {{ $errors->has('pin_certificate') ? 'is-invalid' : '' }}" 
                                           wire:model="pin_certificate">
                                    <small class="form-text text-muted" style="font-size: 0.7rem;">PDF, JPG, or PNG (Max 5MB)</small>
                                    @error('pin_certificate')
                                    <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-12 mt-3 pt-2 border-top">
                                <h5 class="fw-bold text-success mb-2" style="font-size: 0.9rem;">
                                    <i class="bi bi-person-badge me-1"></i> Responsible Officer
                                </h5>
                                <p class="text-muted mb-3" style="font-size: 0.8rem;">Primary contact person for company matters</p>
                                
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm {{ $errors->has('responsible_officer_first_name') ? 'is-invalid' : '' }}" 
                                                   placeholder="Officer's first name" wire:model="responsible_officer_first_name">
                                            @error('responsible_officer_first_name')
                                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm {{ $errors->has('responsible_officer_last_name') ? 'is-invalid' : '' }}" 
                                                   placeholder="Officer's last name" wire:model="responsible_officer_last_name">
                                            @error('responsible_officer_last_name')
                                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control form-control-sm {{ $errors->has('responsible_officer_phone') ? 'is-invalid' : '' }}" 
                                                   placeholder="0712345678" wire:model="responsible_officer_phone">
                                            @error('responsible_officer_phone')
                                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Email Address</label>
                                            <input type="email" class="form-control form-control-sm {{ $errors->has('responsible_officer_email') ? 'is-invalid' : '' }}" 
                                                   placeholder="officer@company.com" wire:model="responsible_officer_email">
                                            @error('responsible_officer_email')
                                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 4: Finance Details -->
                    @if($step == 4)
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-bank me-1"></i>
                            Finance Details
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Bank account for payments</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Account Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('bank_account_number') ? 'is-invalid' : '' }}" 
                                       placeholder="Account number" wire:model="bank_account_number">
                                @error('bank_account_number')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Account Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('bank_account_name') ? 'is-invalid' : '' }}" 
                                       placeholder="Account name" wire:model="bank_account_name">
                                @error('bank_account_name')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Bank Name <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm {{ $errors->has('bank_name') ? 'is-invalid' : '' }}" 
                                        wire:model="bank_name">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->name }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                @error('bank_name')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Bank Branch <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm {{ $errors->has('bank_branch') ? 'is-invalid' : '' }}" 
                                       placeholder="Branch name" wire:model="bank_branch">
                                @error('bank_branch')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Finance Email</label>
                                <input type="email" class="form-control form-control-sm {{ $errors->has('finance_email') ? 'is-invalid' : '' }}" 
                                       placeholder="finance@company.com" wire:model="finance_email">
                                @error('finance_email')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Step 5: Fleet/Station Details -->
                    @if($step == 5)
                    @if($partnerType == 'transport')
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-truck me-1"></i>
                            Fleet & Operations
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Transport capacity and details</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Number of Vehicles <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm {{ $errors->has('vehicle_count') ? 'is-invalid' : '' }}" 
                                       wire:model="vehicle_count" min="1">
                                @error('vehicle_count')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Vehicle Ownership <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm {{ $errors->has('vehicle_ownership') ? 'is-invalid' : '' }}" 
                                        wire:model="vehicle_ownership">
                                    <option value="owned">Owned</option>
                                    <option value="subcontracted">Subcontracted</option>
                                    <option value="both">Both</option>
                                </select>
                                @error('vehicle_ownership')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Vehicle Types</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="motorcycles" wire:model="has_motorcycles">
                                        <label class="form-check-label" for="motorcycles" style="font-size: 0.8rem;">
                                            Motorcycles
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="vans" wire:model="has_vans">
                                        <label class="form-check-label" for="vans" style="font-size: 0.8rem;">
                                            Vans
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="trucks" wire:model="has_trucks">
                                        <label class="form-check-label" for="trucks" style="font-size: 0.8rem;">
                                            Trucks
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Number of Drivers <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm {{ $errors->has('driver_count') ? 'is-invalid' : '' }}" 
                                       wire:model="driver_count" min="1">
                                @error('driver_count')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="vehicles_insured" wire:model="vehicles_insured">
                                        <label class="form-check-label fw-semibold" for="vehicles_insured" style="font-size: 0.8rem;">
                                            Vehicles insured?
                                        </label>
                                        @error('vehicles_insured')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="vehicles_compliant" wire:model="vehicles_compliant">
                                        <label class="form-check-label fw-semibold" for="vehicles_compliant" style="font-size: 0.8rem;">
                                            Vehicles compliant?
                                        </label>
                                        @error('vehicles_compliant')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="drivers_compliant" wire:model="drivers_compliant">
                                        <label class="form-check-label fw-semibold" for="drivers_compliant" style="font-size: 0.8rem;">
                                            Drivers compliant?
                                        </label>
                                        @error('drivers_compliant')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <!-- Station Partner -->
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-shop me-1"></i>
                            Station Details
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Pick-up/Drop-off point information</p>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Number of Points <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm {{ $errors->has('points_count') ? 'is-invalid' : '' }}" 
                                       wire:model="points_count" min="1">
                                @error('points_count')
                                <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">Station Facilities</label>
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="points_phone" wire:model="points_have_phone">
                                        <label class="form-check-label" for="points_phone" style="font-size: 0.8rem;">
                                            Phone
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="points_computer" wire:model="points_have_computer">
                                        <label class="form-check-label" for="points_computer" style="font-size: 0.8rem;">
                                            Computer
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="points_internet" wire:model="points_have_internet">
                                        <label class="form-check-label" for="points_internet" style="font-size: 0.8rem;">
                                            Internet
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="officers_knowledgeable" wire:model="officers_knowledgeable">
                                        <label class="form-check-label fw-semibold" for="officers_knowledgeable" style="font-size: 0.8rem;">
                                            Officers knowledgeable?
                                        </label>
                                        @error('officers_knowledgeable')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" 
                                               id="points_compliant" wire:model="points_compliant">
                                        <label class="form-check-label fw-semibold" for="points_compliant" style="font-size: 0.8rem;">
                                            Points compliant?
                                        </label>
                                        @error('points_compliant')
                                        <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <!-- Step 6: Service Areas -->
                    @if($step == 6)
                    <div class="step-header mb-3">
                        <h4 class="text-success fw-bold mb-1" style="font-size: 1rem;">
                            <i class="bi bi-geo-alt me-1"></i>
                            Service Areas
                        </h4>
                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Select towns where you will operate</p>
                    </div>
                    
                    <div class="service-areas-section">
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1" style="font-size: 0.8rem;">
                                Select Service Towns <span class="text-danger">*</span>
                            </label>
                            <select multiple class="form-select {{ $errors->has('selectedTowns') ? 'is-invalid' : '' }}" 
                                    wire:model="selectedTowns" size="6" style="font-size: 0.85rem;">
                                @foreach($towns as $town)
                                <option value="{{ $town->id }}">
                                    {{ $town->name }} - {{ $town->subcounty->county->name ?? 'N/A' }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle text-success me-1"></i>
                                Hold Ctrl (Cmd on Mac) to select multiple towns
                            </div>
                            @error('selectedTowns')
                            <div class="invalid-feedback d-block" style="font-size: 0.75rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if(count($selectedTowns) > 0)
                        <div class="selected-towns-section mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-semibold text-success mb-0" style="font-size: 0.85rem;">
                                    Selected Towns ({{ count($selectedTowns) }})
                                </h6>
                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" 
                                        wire:click="$set('selectedTowns', [])" style="font-size: 0.75rem;">
                                    Clear All
                                </button>
                            </div>
                            <div class="selected-tags p-2 border rounded-2" style="max-height: 80px; overflow-y: auto; font-size: 0.75rem;">
                                @foreach($serviceTowns as $town)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 
                                             py-1 px-2 me-1 mb-1 d-inline-flex align-items-center">
                                    {{ $town->name }}
                                    <button type="button" class="btn-close btn-close-sm ms-1" 
                                            wire:click="removeTown({{ $town->id }})"
                                            style="font-size: 0.5rem; padding: 2px;"></button>
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <div class="alert alert-warning border-warning bg-warning bg-opacity-10 p-2" style="font-size: 0.75rem;">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                <div>
                                    <strong>Important:</strong> Select at least one service town
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Footer Section -->
            <div class="registration-footer bg-light border-top py-3 rounded-bottom-3" style="flex-shrink: 0;">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            @if(!$submissionSuccess)
                            <div class="d-flex align-items-center">
                                @if($step > 1)
                                <button type="button" class="btn btn-outline-secondary px-3 py-1" wire:click="previousStep" style="font-size: 0.85rem;">
                                    <i class="bi bi-arrow-left me-1"></i> Previous
                                </button>
                                @else
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary px-3 py-1" style="font-size: 0.85rem;">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if(!$submissionSuccess)
                            <div class="d-flex justify-content-end gap-2">
                                @if($step < 6)
                                <button type="button" class="btn btn-success px-3 py-1" wire:click="nextStep" style="font-size: 0.85rem;">
                                    Continue <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                @else
                                <button type="button" class="btn btn-success px-3 py-1" 
                                        wire:click="submitRegistration" 
                                        wire:loading.attr="disabled"
                                        wire:target="submitRegistration"
                                        style="font-size: 0.85rem;">
                                    <span wire:loading.remove wire:target="submitRegistration">
                                        <i class="bi bi-check-circle me-1"></i> Submit
                                    </span>
                                    <span wire:loading wire:target="submitRegistration">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Processing...
                                    </span>
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Security Badges -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center gap-4">
                                <div class="text-center">
                                    <div class="mb-1">
                                        <i class="bi bi-shield-check text-success" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <span class="fw-semibold text-muted" style="font-size: 0.7rem;">Secure</span>
                                </div>
                                <div class="text-center">
                                    <div class="mb-1">
                                        <i class="bi bi-lightning-charge text-success" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <span class="fw-semibold text-muted" style="font-size: 0.7rem;">Fast</span>
                                </div>
                                <div class="text-center">
                                    <div class="mb-1">
                                        <i class="bi bi-headset text-success" style="font-size: 0.9rem;"></i>
                                    </div>
                                    <span class="fw-semibold text-muted" style="font-size: 0.7rem;">Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.registration-card {
    border-radius: 15px;
    overflow: hidden;
}

.partner-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.1) !important;
}

.hover-shadow:hover {
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
}

.form-control-sm:focus, .form-select-sm:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1ba87e 100%);
    transform: translateY(-1px);
    box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
}

.btn-outline-success:hover {
    background: #28a745;
    color: white;
}

.step-indicators::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 1px;
    background: rgba(255, 255, 255, 0.3);
    z-index: 1;
}

.step-indicator {
    position: relative;
    z-index: 2;
}

.company-details-scrollable::-webkit-scrollbar {
    width: 6px;
}

.company-details-scrollable::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.company-details-scrollable::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 3px;
}

.selected-tags::-webkit-scrollbar {
    width: 4px;
}

.selected-tags::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.selected-tags::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 2px;
}

.btn-close-sm {
    padding: 0.15rem;
    font-size: 0.5rem;
}

/* Custom scrollbar for step 3 */
.registration-body[style*="overflow: auto"]::-webkit-scrollbar {
    width: 6px;
}

.registration-body[style*="overflow: auto"]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.registration-body[style*="overflow: auto"]::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 3px;
}

@media (max-width: 768px) {
    .registration-wrapper {
        padding: 0.5rem;
    }
    
    .step-circle {
        width: 25px !important;
        height: 25px !important;
        font-size: 0.7rem !important;
    }
    
    .step-label span {
        font-size: 0.65rem !important;
    }
    
    .btn {
        padding: 0.25rem 0.75rem !important;
        font-size: 0.8rem !important;
    }
}
</style>

<script>
// Scroll to top on step change
Livewire.on('scroll-to-top', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// Prevent form submission on enter key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
    }
});

// Auto-scroll company details to top when entering step 3
Livewire.hook('commit', ({ component, commit, succeed }) => {
    succeed(() => {
        if (component.serverMemo.data.step === 3) {
            const scrollableDiv = document.querySelector('.company-details-scrollable');
            if (scrollableDiv) {
                scrollableDiv.scrollTop = 0;
            }
        }
    });
});

// Initialize tooltips on small screens
document.addEventListener('livewire:load', function () {
    if (window.innerWidth < 768) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
</div>