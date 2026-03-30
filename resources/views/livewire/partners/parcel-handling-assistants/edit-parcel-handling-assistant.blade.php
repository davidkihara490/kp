<div>
    <div class="dashboard-section">
        <!-- Form Header -->
        <div class="section-header">
            <div>
                <h3 class="section-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Update New Assistant
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
        <form wire:submit.prevent="update">
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

                </div>

                <div class="col-lg-6">
                    <div class="form-card mt-4">
                        <div class="form-card-header">
                            <h5><i class="bi bi-activity me-2"></i>Account Status</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="mb-4">
                                <label for="status" class="form-label">
                                    <i class="bi bi-activity me-2"></i>
                                    Select Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" wire:model="status">
                                    <option value="">-- Choose a status --</option>
                                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>
                                        <i class="bi bi-check-circle"></i> Active
                                    </option>
                                    <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>
                                        <i class="bi bi-pause-circle"></i> Inactive
                                    </option>
                                    <option value="suspended" {{ $status == 'suspended' ? 'selected' : '' }}>
                                        <i class="bi bi-ban"></i> Suspended
                                    </option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>
                                        <i class="bi bi-clock"></i> Pending
                                    </option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions mt-4">
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
                            <span wire:loading.remove wire:target="update">
                                <i class="bi bi-check-circle me-2"></i>
                                Update Assistant
                            </span>
                            <span wire:loading wire:target="update">
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
        .form-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-card-header {
            padding: 20px 20px 0 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-card-header h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
            padding-bottom: 15px;
        }

        .form-card-body {
            padding: 20px;
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .form-control,
        .form-select {
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #008f40;
            box-shadow: 0 0 0 0.25rem rgba(0, 143, 64, 0.25);
        }

        /* Dropdown Styling */
        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            appearance: none;
            cursor: pointer;
        }

        .form-select option {
            padding: 10px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        /* Toggle Switch */
        .form-check.form-switch {
            padding-left: 3.5rem;
        }

        .form-check-input:checked {
            background-color: #008f40;
            border-color: #008f40;
        }

        /* Role Description & Status Description */
        .role-description,
        .status-description {
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        /* Password Strength */
        .password-strength .progress {
            border-radius: 2px;
        }

        .password-strength .progress-bar {
            transition: width 0.3s ease;
        }

        /* Form Actions */
        .form-actions {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-top: 20px;
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
        @media (max-width: 768px) {
            .form-card {
                margin-bottom: 15px;
            }

            .form-card-header,
            .form-card-body {
                padding: 15px;
            }

            .form-actions .d-flex {
                flex-direction: column;
                gap: 10px;
            }

            .form-actions .d-flex>div {
                width: 100%;
                display: flex;
                gap: 10px;
            }

            .form-actions .btn {
                flex: 1;
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
                }).catch(function(err) {
                    // Fallback for older browsers
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);

                    alert('Password copied to clipboard!');
                });
            }

            // Focus on first name field on load
            const firstNameField = document.getElementById('first_name');
            if (firstNameField) {
                firstNameField.focus();
            }
        });

        // Livewire event listeners
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('cancelCreate', () => {
                window.location.href = '{{ route("partners.pha.index") }}';
            });
        });
    </script>
</div>