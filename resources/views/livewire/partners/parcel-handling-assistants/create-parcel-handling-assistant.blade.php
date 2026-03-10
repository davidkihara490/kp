<div>
<div>
    <div class="dashboard-section">
        <!-- Form Header -->
        <div class="section-header">
            <div>
                <h3 class="section-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Create New Assistant
                </h3>
                <p class="section-subtitle">Add a new parcel handling assistant to the system</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-secondary" wire:click="$dispatch('cancelCreate')">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to List
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form -->
        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Left Column - Personal Information -->
                <div class="col-lg-6">
                    <!-- Personal Information Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <h5><i class="bi bi-person-badge me-2"></i>Personal Information</h5>
                        </div>
                        <div class="form-card-body">
                            <!-- Name Fields -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">
                                            <i class="bi bi-person me-2"></i>
                                            First Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                               id="first_name" wire:model="first_name" 
                                               placeholder="Enter first name" autofocus>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="second_name" class="form-label">
                                            Middle Name
                                        </label>
                                        <input type="text" class="form-control @error('second_name') is-invalid @enderror" 
                                               id="second_name" wire:model="second_name" 
                                               placeholder="Enter middle name">
                                        @error('second_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">
                                            Last Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                               id="last_name" wire:model="last_name" 
                                               placeholder="Enter last name">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone_number" class="form-label">
                                            <i class="bi bi-telephone me-2"></i>
                                            Phone Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                               id="phone_number" wire:model="phone_number" 
                                               placeholder="e.g., 0712345678">
                                        @error('phone_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope me-2"></i>
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" wire:model="email" 
                                               placeholder="e.g., assistant@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ID Number -->
                            <div class="mb-3">
                                <label for="id_number" class="form-label">
                                    <i class="bi bi-credit-card me-2"></i>
                                    ID Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('id_number') is-invalid @enderror" 
                                       id="id_number" wire:model="id_number" 
                                       placeholder="Enter national ID/passport number">
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings Card -->
                    <div class="form-card mt-4">
                        <div class="form-card-header">
                            <h5><i class="bi bi-shield-lock me-2"></i>Account Settings</h5>
                        </div>
                        <div class="form-card-body">
                            @if($generate_user_account)
                            <!-- Password Section -->
                            <div class="password-section">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="bi bi-key me-2"></i>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" wire:model="password" 
                                               placeholder="Enter password">
                                        <button class="btn btn-outline-secondary" type="button" 
                                                wire:click="generateNewPassword" 
                                                title="Generate new password">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="password-strength mt-1">
                                        <div class="progress" style="height: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: 85%;"></div>
                                        </div>
                                        <small class="text-muted">Strong password</small>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">
                                        <i class="bi bi-key-fill me-2"></i>
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" 
                                           id="confirm_password" wire:model="confirm_password" 
                                           placeholder="Confirm password">
                                    @error('confirm_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Generated Password Preview -->
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-info-circle me-2"></i>
                                            <strong>Generated Password:</strong>
                                            <code class="ms-2">{{ $password }}</code>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="copyToClipboard('{{ $password }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Role & Status -->
                <div class="col-lg-6">
                    <!-- Role & Status Card -->
                    <div class="form-card">
                        <div class="form-card-header">
                            <h5><i class="bi bi-person-gear me-2"></i>Status</h5>
                        </div>
                        <div class="form-card-body">

                            <!-- Status Selection -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-activity me-2"></i>
                                    Status <span class="text-danger">*</span>
                                </label>
                                <div class="status-badges">
                                    <span class="status-badge @if($status === 'active') active @endif" 
                                          wire:click="$set('status', 'active')">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Active
                                    </span>
                                    <span class="status-badge @if($status === 'inactive') active @endif" 
                                          wire:click="$set('status', 'inactive')">
                                        <i class="bi bi-pause-circle me-1"></i>
                                        Inactive
                                    </span>
                                    <span class="status-badge @if($status === 'suspended') active @endif" 
                                          wire:click="$set('status', 'suspended')">
                                        <i class="bi bi-ban me-1"></i>
                                        Suspended
                                    </span>
                                    <span class="status-badge @if($status === 'pending') active @endif" 
                                          wire:click="$set('status', 'pending')">
                                        <i class="bi bi-clock me-1"></i>
                                        Pending
                                    </span>
                                </div>
                                <input type="hidden" wire:model="status">
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                           
                        </div>
                    </div>
               
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" wire:click="$dispatch('cancelCreate')">
                        <i class="bi bi-x-circle me-2"></i>
                        Cancel
                    </button>
                    <div>
                        <button type="button" class="btn btn-outline-danger me-2" 
                                onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset Form
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">
                                <i class="bi bi-check-circle me-2"></i>
                                Create Assistant
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Creating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        /* Card Styling */
        .form-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 20px;
        }

        .form-card-header {
            padding: 20px 20px 0 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .form-card-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
            padding-bottom: 15px;
        }

        .form-card-body {
            padding: 20px;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-control, .form-select {
            border: 1px solid var(--border-color);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: var(--border-color);
        }

        /* Toggle Switch */
        .form-check.form-switch {
            padding-left: 3.5rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Role Cards */
        .card-role {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }

        .card-role.active {
            border-color: var(--primary-color);
            background-color: rgba(0, 143, 64, 0.05);
        }

        .card-role:hover:not(.active) {
            border-color: #adb5bd;
            background-color: #f8f9fa;
        }

        .card-role .form-check-input {
            display: none;
        }

        .card-role .form-check-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            margin: 0;
            width: 100%;
        }

        .role-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: white;
        }

        .card-role[wire\\:click*="assistant"] .role-icon {
            background-color: #17a2b8;
        }

        .card-role[wire\\:click*="supervisor"] .role-icon {
            background-color: #ffc107;
        }

        .card-role[wire\\:click*="manager"] .role-icon {
            background-color: var(--primary-color);
        }

        .card-role.active[wire\\:click*="assistant"] {
            border-color: #17a2b8;
            background-color: rgba(23, 162, 184, 0.05);
        }

        .card-role.active[wire\\:click*="supervisor"] {
            border-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.05);
        }

        .card-role.active[wire\\:click*="manager"] {
            border-color: var(--primary-color);
            background-color: rgba(0, 143, 64, 0.05);
        }

        .role-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 3px;
        }

        .role-text small {
            color: var(--text-light);
            font-size: 0.8rem;
        }

        /* Status Badges */
        .status-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }

        .status-badge[wire\\:click*="active"] {
            background-color: rgba(40, 167, 69, 0.1);
            color: #155724;
            border-color: rgba(40, 167, 69, 0.2);
        }

        .status-badge[wire\\:click*="inactive"] {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
            border-color: rgba(108, 117, 125, 0.2);
        }

        .status-badge[wire\\:click*="suspended"] {
            background-color: rgba(220, 53, 69, 0.1);
            color: #721c24;
            border-color: rgba(220, 53, 69, 0.2);
        }

        .status-badge[wire\\:click*="pending"] {
            background-color: rgba(255, 193, 7, 0.1);
            color: #856404;
            border-color: rgba(255, 193, 7, 0.2);
        }

        .status-badge.active {
            border-color: currentColor;
            font-weight: 600;
        }

        .status-badge:hover:not(.active) {
            opacity: 0.8;
        }

        /* Permissions Preview */
        .permissions-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .permission-item {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .permission-item:last-child {
            border-bottom: none;
        }

        /* Password Strength */
        .password-strength .progress {
            border-radius: 2px;
        }

        .password-strength .progress-bar {
            transition: width 0.3s ease;
        }

        /* Summary */
        .summary-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .summary-value {
            text-align: right;
            font-size: 0.9rem;
        }

        .summary-value .badge {
            font-size: 0.8rem;
            padding: 4px 8px;
        }

        /* Password Alert */
        .alert-info code {
            background: white;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            font-family: monospace;
        }

        /* Form Actions */
        .form-actions {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }

        /* Alert Styling */
        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        .alert-info {
            background-color: rgba(23, 162, 184, 0.1);
            border-left: 4px solid #17a2b8;
        }

        /* Loading State */
        .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .status-badges {
                justify-content: center;
            }

            .summary-item {
                flex-direction: column;
                gap: 5px;
            }

            .summary-value {
                text-align: left;
            }
        }

        @media (max-width: 768px) {
            .form-card {
                margin-bottom: 15px;
            }

            .form-card-header,
            .form-card-body {
                padding: 15px;
            }

            .card-role {
                padding: 10px;
            }

            .status-badges {
                flex-direction: column;
                align-items: stretch;
            }

            .status-badge {
                text-align: center;
                justify-content: center;
            }

            .form-actions .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .form-actions .d-flex > div {
                width: 100%;
                display: flex;
                gap: 10px;
            }

            .form-actions .btn {
                flex: 1;
            }
        }

        @media (max-width: 576px) {
            .role-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Copy password to clipboard
            window.copyToClipboard = function(text) {
                navigator.clipboard.writeText(text).then(function() {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                    alert.style.zIndex = '9999';
                    alert.innerHTML = `
                        <i class="bi bi-check-circle me-2"></i>
                        Password copied to clipboard!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                });
            }

            // Auto-validate password match
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('confirm_password');
            
            if (passwordInput && confirmInput) {
                const validatePasswords = () => {
                    if (passwordInput.value && confirmInput.value) {
                        if (passwordInput.value !== confirmInput.value) {
                            confirmInput.classList.add('is-invalid');
                        } else {
                            confirmInput.classList.remove('is-invalid');
                        }
                    }
                };
                
                passwordInput.addEventListener('input', validatePasswords);
                confirmInput.addEventListener('input', validatePasswords);
            }

            // Focus on first name field on load
            const firstNameField = document.getElementById('first_name');
            if (firstNameField) {
                firstNameField.focus();
            }
        });
    </script>
</div></div>
