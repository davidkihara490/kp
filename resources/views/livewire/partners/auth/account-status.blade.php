<div>
    <div>
        <div class="d-flex justify-content-center align-items-center vh-100 p-3" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
            <div class="account-status-card card border-0 shadow-lg rounded-4 overflow-hidden" style="width: 750px;">
                <!-- Card Header -->
                <div class="card-header bg-success text-white py-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="logo-circle bg-white text-success d-flex align-items-center justify-content-center me-3"
                                style="width: 36px; height: 36px; border-radius: 10px; font-size: 1.1rem;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="font-size: 1.2rem;">Account Status</h5>
                                <p class="mb-0 opacity-90" style="font-size: 0.9rem;">Verification Progress</p>
                            </div>
                        </div>
                        @if($isFullyVerified)
                        <span class="badge bg-white text-success py-1 px-3" style="font-size: 0.85rem;">
                            <i class="bi bi-patch-check-fill me-1"></i>Verified
                        </span>
                        @else
                        <span class="badge bg-warning text-dark py-1 px-3" style="font-size: 0.85rem;">
                            <i class="bi bi-clock-history me-1"></i>Pending
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <!-- Progress Summary -->
                    <div class="progress-summary mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-success" style="font-size: 0.95rem;">Verification Progress</span>
                            <span class="fw-bold" style="font-size: 1rem;">
                                {{ $verificationPercentage }}% Complete
                            </span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 5px;">
                            <div class="progress-bar bg-success"
                                role="progressbar"
                                style="width: {{ $verificationPercentage }}%; border-radius: 5px;"
                                aria-valuenow="{{ $verificationPercentage }}"
                                aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">
                            {{ $completedVerifications }} of 5 verifications completed
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
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-check-lg text-white" style="font-size: 1rem;"></i>
                                            </div>
                                            @else
                                            <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-clock text-dark" style="font-size: 1rem;"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">Owner Email</h6>
                                                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">{{ $ownerEmail }}</p>
                                                </div>
                                                @if($ownerEmailVerified)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                                    style="font-size: 0.75rem;">
                                                    Verified
                                                </span>
                                                @else
                                                <button wire:click="resendOwnerEmailVerification"
                                                    class="btn btn-sm btn-outline-success py-1 px-2"
                                                    style="font-size: 0.75rem;"
                                                    wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="resendOwnerEmailVerification">
                                                        Verify
                                                    </span>
                                                    <span wire:loading wire:target="resendOwnerEmailVerification">
                                                        <span class="spinner-border spinner-border-sm" style="width: 0.8rem; height: 0.8rem;"></span>
                                                    </span>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$ownerEmailVerified)
                                    <div class="verification-info p-2 rounded-2 mt-1"
                                        style="background: rgba(255, 193, 7, 0.05); font-size: 0.75rem;">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 0.9rem;"></i>
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
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-check-lg text-white" style="font-size: 1rem;"></i>
                                            </div>
                                            @else
                                            <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;">
                                                <i class="bi bi-clock text-dark" style="font-size: 1rem;"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">Owner Phone</h6>
                                                    <p class="mb-0 text-muted" style="font-size: 0.8rem;">{{ $ownerPhone }}</p>
                                                </div>
                                                @if($ownerPhoneVerified)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                                    style="font-size: 0.75rem;">
                                                    Verified
                                                </span>
                                                @else
                                                <button wire:click="sendOwnerPhoneVerification"
                                                    class="btn btn-sm btn-outline-success py-1 px-2"
                                                    style="font-size: 0.75rem;"
                                                    wire:loading.attr="disabled">
                                                    <span wire:loading.remove wire:target="sendOwnerPhoneVerification">
                                                        Verify
                                                    </span>
                                                    <span wire:loading wire:target="sendOwnerPhoneVerification">
                                                        <span class="spinner-border spinner-border-sm" style="width: 0.8rem; height: 0.8rem;"></span>
                                                    </span>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$ownerPhoneVerified)
                                    <div class="verification-info p-2 rounded-2 mt-1"
                                        style="background: rgba(255, 193, 7, 0.05); font-size: 0.75rem;">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 0.9rem;"></i>
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
                        <!-- Row 3: Admin Verification -->
                        <div class="verification-item {{ $adminVerified ? 'completed' : 'pending' }}">
                            <div class="d-flex align-items-center p-3 rounded-3"
                                style="background: {{ $adminVerified ? 'rgba(40, 167, 69, 0.05)' : 'rgba(255, 193, 7, 0.05)' }};">
                                <div class="verification-icon me-3">
                                    @if($adminVerified)
                                    <div class="verified-icon rounded-circle bg-success d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        <i class="bi bi-check-lg text-white" style="font-size: 1rem;"></i>
                                    </div>
                                    @else
                                    <div class="pending-icon rounded-circle bg-warning d-flex align-items-center justify-content-center"
                                        style="width: 32px; height: 32px;">
                                        <i class="bi bi-clock text-dark" style="font-size: 1rem;"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-semibold" style="font-size: 0.9rem;">Admin Verification</h6>
                                            <p class="mb-0 text-muted" style="font-size: 0.8rem;">Karibu Parcels Approval</p>
                                        </div>
                                        @if($adminVerified)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 py-1 px-2"
                                            style="font-size: 0.75rem;">
                                            Approved
                                        </span>
                                        @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 py-1 px-2"
                                            style="font-size: 0.75rem;">
                                            Under Review
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!$adminVerified)
                            <div class="verification-info p-2 rounded-2 mt-1"
                                style="background: rgba(255, 193, 7, 0.05); font-size: 0.75rem;">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle text-warning mt-1 me-2" style="font-size: 0.9rem;"></i>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="font-size: 0.8rem;">Your account is under review</p>
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
                            <p class="mb-0 text-muted" style="font-size: 0.8rem;">
                                <i class="bi bi-clock-history me-1"></i>
                                Registered {{ $registrationDate }}
                            </p>
                        </div>
                        <div>
                            @if(!$isFullyVerified)
                            <button wire:click="requestExpeditedReview"
                                class="btn btn-outline-success py-1 px-3 fw-medium"
                                style="font-size: 0.8rem;"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="requestExpeditedReview">
                                    <i class="bi bi-lightning-charge me-1"></i>Expedite Review
                                </span>
                                <span wire:loading wire:target="requestExpeditedReview">
                                    <span class="spinner-border spinner-border-sm me-1" style="width: 0.8rem; height: 0.8rem;"></span>
                                    Processing...
                                </span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

            .verified-icon {
                box-shadow: 0 2px 6px rgba(40, 167, 69, 0.2);
            }

            .pending-icon {
                box-shadow: 0 2px 6px rgba(255, 193, 7, 0.2);
            }

            .badge {
                font-weight: 500;
                letter-spacing: 0.3px;
            }

            .btn-outline-success {
                border-width: 1.5px;
                transition: all 0.2s ease;
            }

            .btn-outline-success:hover {
                background-color: rgba(40, 167, 69, 0.1);
            }

            .progress-bar {
                transition: width 0.6s ease;
            }

            /* Responsive styles */
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
            // Auto-refresh verification status every 30 seconds if pending
            document.addEventListener('livewire:load', function() {
                setInterval(() => {
                    if (@this.get('hasPendingVerifications')) {
                        Livewire.emit('refreshVerificationStatus');
                    }
                }, 30000);
            });

            // Show success toast on verification actions
            Livewire.on('verificationSent', (message) => {
                const toast = document.createElement('div');
                toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px; font-size: 0.9rem;';
                toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <div class="fw-medium">${message}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            `;
                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.remove();
                }, 4000);
            });
        </script>
    </div>
</div>