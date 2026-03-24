<div>
    <div>
        <div>
            <div class="d-flex justify-content-center align-items-center vh-100 p-3" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                <div class="account-status-card card border-0 shadow-lg rounded-4 overflow-hidden" style="width: 750px;">
                    <!-- Card Header -->
                    <div class="card-header bg-success text-white py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="logo-circle bg-white text-success d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px; border-radius: 10px; font-size: 1.2rem;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold" style="font-size: 1.3rem;">Account Status</h5>
                                    <p class="mb-0 opacity-90" style="font-size: 0.95rem;">Verification Progress</p>
                                </div>
                            </div>
                            @if($isFullyVerified)
                            <span class="badge bg-white text-success py-1 px-3" style="font-size: 0.9rem;">
                                <i class="bi bi-patch-check-fill me-1"></i>Verified
                            </span>
                            @else
                            <span class="badge bg-warning text-dark py-1 px-3" style="font-size: 0.9rem;">
                                <i class="bi bi-clock-history me-1"></i>Pending
                            </span>
                            @endif
                        </div>
                    </div>
                    <x-alerts.response-alerts />
                    <!-- Card Body -->
                    <div class="card-body p-4">
                        <!-- Progress Summary -->
                        <div class="progress-summary mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-success" style="font-size: 1rem;">Verification Progress</span>
                                <span class="fw-bold" style="font-size: 1.1rem;">
                                    {{ $verificationPercentage }}% Complete
                                </span>
                            </div>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: {{ $verificationPercentage }}%; border-radius: 6px;"
                                    aria-valuenow="{{ $verificationPercentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.9rem;">
                                {{ $completedVerifications }} of 3 verifications completed
                            </p>
                        </div>

                        <!-- Verification Items -->
                        <div class="verification-items">
                            <!-- Row 1: Owner Email & Phone -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <!-- Owner Email Verification -->
                                    <div class="verification-item h-100 {{ $ownerEmailVerified ? 'completed' : 'pending' }}">
                                        <div class="d-flex align-items-center p-3 rounded-3"
                                            style="background: {{ $ownerEmailVerified ? 'rgba(40, 167, 69, 0.05)' : 'rgba(255, 193, 7, 0.05)' }};">
                                            <div class="verification-icon me-3">
                                                @if($ownerEmailVerified)
                                                <div class="verified-icon rounded-circle bg-success d-flex align-items-center justify-content-center"
                                                    style="width: 36px; height: 36px;">
                                                    <i class="bi bi-check-lg text-white" style="font-size: 1.1rem;"></i>
                                                </div>
                                                @else
                                                <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                                    style="width: 36px; height: 36px;">
                                                    <i class="bi bi-clock text-dark" style="font-size: 1.1rem;"></i>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold" style="font-size: 1rem;">Owner Email</h6>
                                                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $ownerEmail }}</p>
                                                    </div>
                                                    @if($ownerEmailVerified)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                                        style="font-size: 0.8rem;">
                                                        Verified
                                                    </span>
                                                    @else
                                                    <button wire:click="resendOwnerEmailVerification"
                                                        class="btn btn-sm btn-outline-success py-1 px-2"
                                                        style="font-size: 0.8rem;"
                                                        wire:loading.attr="disabled">
                                                        <span wire:loading.remove wire:target="resendOwnerEmailVerification">
                                                            <i class="bi bi-envelope me-1"></i>Resend Email
                                                        </span>
                                                        <span wire:loading wire:target="resendOwnerEmailVerification">
                                                            <span class="spinner-border spinner-border-sm" style="width: 0.9rem; height: 0.9rem;"></span>
                                                        </span>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if(!$ownerEmailVerified)
                                        <div class="verification-info p-2 rounded-2 mt-1"
                                            style="background: rgba(255, 193, 7, 0.05); font-size: 0.8rem;">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 1rem;"></i>
                                                <div>
                                                    <p class="mb-0">Verification required for dashboard access</p>
                                                    <p class="mb-0">Check your email for verification link</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Owner Phone Verification -->
                                    <div class="verification-item h-100 {{ $ownerPhoneVerified ? 'completed' : 'pending' }}">
                                        <div class="d-flex align-items-center p-3 rounded-3"
                                            style="background: {{ $ownerPhoneVerified ? 'rgba(40, 167, 69, 0.05)' : 'rgba(255, 193, 7, 0.05)' }};">
                                            <div class="verification-icon me-3">
                                                @if($ownerPhoneVerified)
                                                <div class="verified-icon rounded-circle bg-success d-flex align-items-center justify-content-center"
                                                    style="width: 36px; height: 36px;">
                                                    <i class="bi bi-check-lg text-white" style="font-size: 1.1rem;"></i>
                                                </div>
                                                @else
                                                <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                                    style="width: 36px; height: 36px;">
                                                    <i class="bi bi-clock text-dark" style="font-size: 1.1rem;"></i>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold" style="font-size: 1rem;">Owner Phone</h6>
                                                        <p class="mb-0 text-muted" style="font-size: 0.85rem;">{{ $ownerPhone }}</p>
                                                    </div>
                                                    @if($ownerPhoneVerified)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                                        style="font-size: 0.8rem;">
                                                        Verified
                                                    </span>
                                                    @else
                                                    <button wire:click="sendOwnerPhoneVerification"
                                                        class="btn btn-sm btn-outline-success py-1 px-2"
                                                        style="font-size: 0.8rem;"
                                                        wire:loading.attr="disabled">
                                                        <span wire:loading.remove wire:target="sendOwnerPhoneVerification">
                                                            <i class="bi bi-phone me-1"></i>Verify
                                                        </span>
                                                        <span wire:loading wire:target="sendOwnerPhoneVerification">
                                                            <span class="spinner-border spinner-border-sm" style="width: 0.9rem; height: 0.9rem;"></span>
                                                        </span>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        @if(!$ownerPhoneVerified)
                                        <div class="verification-info p-2 rounded-2 mt-1"
                                            style="background: rgba(255, 193, 7, 0.05); font-size: 0.8rem;">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 1rem;"></i>
                                                <div>
                                                    <p class="mb-0">SMS code will be sent for verification</p>
                                                    <p class="mb-0">Required for transaction notifications</p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2: Admin Verification -->
                            <div class="verification-item {{ $adminVerified ? 'completed' : 'pending' }}">
                                <div class="d-flex align-items-center p-3 rounded-3"
                                    style="background: {{ $adminVerified ? 'rgba(40, 167, 69, 0.05)' : 'rgba(255, 193, 7, 0.05)' }};">
                                    <div class="verification-icon me-3">
                                        @if($adminVerified)
                                        <div class="verified-icon rounded-circle bg-success d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="bi bi-check-lg text-white" style="font-size: 1.1rem;"></i>
                                        </div>
                                        @else
                                        <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="bi bi-clock text-dark" style="font-size: 1.1rem;"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0 fw-semibold" style="font-size: 1rem;">Admin Verification</h6>
                                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Karibu Parcels Approval</p>
                                            </div>
                                            @if($adminVerified)
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                                style="font-size: 0.8rem;">
                                                Approved
                                            </span>
                                            @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 py-1 px-2"
                                                style="font-size: 0.8rem;">
                                                Under Review
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(!$adminVerified)
                                <div class="verification-info p-2 rounded-2 mt-1"
                                    style="background: rgba(255, 193, 7, 0.05); font-size: 0.8rem;">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 1rem;"></i>
                                        <div>
                                            <p class="mb-0 fw-semibold" style="font-size: 0.85rem;">Your account is under review</p>
                                            <p class="mb-0">Documents will be verified within 2-3 business days</p>
                                            <p class="mb-0">You'll receive an email notification once approved</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Registered {{ $registrationDate }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Phone Verification Modal - Now properly inside the same root element -->
            @if($showPhoneModal)
            <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5); position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1050;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-phone me-2"></i>
                                Verify Phone Number
                            </h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="closePhoneModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                                    <i class="bi bi-envelope-paper fs-1 text-success"></i>
                                </div>
                                <h6>Enter the verification code</h6>
                                <p class="text-muted small">
                                    We've sent a 6-digit verification code to <strong>{{ $ownerPhone }}</strong>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Verification Code</label>
                                <input type="text"
                                    class="form-control form-control-lg text-center @error('verificationCode') is-invalid @enderror"
                                    wire:model="verificationCode"
                                    maxlength="6"
                                    placeholder="Enter 6-digit code"
                                    style="letter-spacing: 2px; font-size: 1.5rem;">
                                @error('verificationCode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button wire:click="verifyPhone"
                                    class="btn btn-success w-100 mb-2"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="bi bi-check-circle me-2"></i>Verify
                                    </span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Verifying...
                                    </span>
                                </button>

                                <button wire:click="resendPhoneCode"
                                    class="btn btn-link text-success text-decoration-none small"
                                    wire:loading.attr="disabled">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Resend Code
                                </button>
                            </div>

                            <hr class="my-3">
                            <div class="alert alert-info small mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Didn't receive the code? Check your SMS inbox or request a new code.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <style>
                .account-status-card {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                    animation: fadeInScale 0.5s ease-out;
                }

                @keyframes fadeInScale {
                    from {
                        opacity: 0;
                        transform: scale(0.95);
                    }

                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                .verification-item {
                    border-left: 4px solid;
                    transition: all 0.3s ease;
                }

                .verification-item.completed {
                    border-left-color: #28a745;
                }

                .verification-item.pending {
                    border-left-color: #ffc107;
                }

                .verification-item:hover {
                    transform: translateX(3px);
                }

                .verified-icon,
                .pending-icon {
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                }

                .progress-bar {
                    transition: width 0.6s ease;
                }

                @media (max-width: 768px) {
                    .d-flex.justify-content-center.align-items-center {
                        padding: 1rem !important;
                    }

                    .account-status-card {
                        width: 95% !important;
                        max-width: 600px;
                    }

                    .card-body {
                        padding: 1rem !important;
                    }

                    .verification-item {
                        margin-bottom: 1rem !important;
                    }

                    .row.g-3 {
                        gap: 0.75rem !important;
                    }

                    .col-md-6 {
                        width: 100% !important;
                    }
                }

                @media (max-width: 576px) {
                    .account-status-card {
                        width: 100% !important;
                    }

                    .card-header {
                        padding: 0.75rem !important;
                    }

                    .card-body {
                        padding: 0.75rem !important;
                    }

                    .verification-item>div:first-child {
                        padding: 0.75rem !important;
                    }

                    .card-footer {
                        padding: 0.75rem !important;
                    }

                    .card-footer .d-flex {
                        flex-direction: column;
                        align-items: flex-start !important;
                        gap: 0.5rem;
                    }

                    .card-footer .d-flex>div:last-child {
                        align-self: stretch;
                    }

                    .card-footer .btn {
                        width: 100%;
                    }
                }
            </style>

            <script>
                document.addEventListener('livewire:initialized', () => {
                    // Listen for verification events
                    Livewire.on('verificationSent', (message) => {
                        const toast = document.createElement('div');
                        toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; font-size: 0.9rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
                        toast.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div class="fw-medium">${message}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                `;
                        document.body.appendChild(toast);

                        setTimeout(() => {
                            toast.remove();
                        }, 5000);
                    });

                    Livewire.on('verificationError', (message) => {
                        const toast = document.createElement('div');
                        toast.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; font-size: 0.9rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
                        toast.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <div class="fw-medium">${message}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                `;
                        document.body.appendChild(toast);

                        setTimeout(() => {
                            toast.remove();
                        }, 5000);
                    });
                });
            </script>
        </div>
    </div>
</div>