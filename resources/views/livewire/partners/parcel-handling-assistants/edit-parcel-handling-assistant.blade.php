<div>
<div>
    @if($assistant)
    <div class="dashboard-section">
        <!-- Form Header -->
        <div class="section-header">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="assistant-avatar">
                        @if($assistant->user && $assistant->user->avatar)
                            <img src="{{ $assistant->user->avatar }}" alt="{{ $assistant->full_name }}" class="rounded-circle">
                        @else
                            <div class="avatar-placeholder">
                                {{ substr($assistant->first_name, 0, 1) }}{{ substr($assistant->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="section-title mb-1">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Assistant
                        </h3>
                        <p class="section-subtitle">
                            {{ $assistant->full_name }}
                            <span class="badge bg-light text-dark ms-2">ID: {{ $assistant->id }}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-secondary" wire:click="$dispatch('cancelEdit')">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats mb-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">Created</div>
                            <div class="stat-label">{{ $assistant->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">Last Updated</div>
                            <div class="stat-label">{{ $assistant->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">Station</div>
                            <div class="stat-label">{{ $assistant->assignment()->pickUpAndDropOffPoint->name}}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            @if($assistant->user)
                                <i class="bi bi-person-check"></i>
                            @else
                                <i class="bi bi-person-x"></i>
                            @endif
                        </div>
                        <div class="stat-info">
                            <div class="stat-value">User Account</div>
                            <div class="stat-label">{{ $assistant->user ? 'Active' : 'Not created' }}</div>
                        </div>
                    </div>
                </div>
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

                    <!-- User Account Management Card -->
                    <div class="form-card mt-4">
                        <div class="form-card-header">
                            <h5><i class="bi bi-shield-lock me-2"></i>User Account Management</h5>
                        </div>
                        <div class="form-card-body">
                            @if($assistant->user)
                                <!-- Existing User Account -->
                                <div class="existing-account mb-4">
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-check me-2"></i>
                                            <div>
                                                <strong>User account exists</strong>
                                                <div class="small">Username: {{ $assistant->user->user_name }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reset Password -->
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="bi bi-key me-2"></i>
                                            Reset Password
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                   id="new_password" wire:model="new_password" 
                                                   placeholder="Leave blank to keep current password">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    wire:click="generateNewPassword" 
                                                    title="Generate new password">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </div>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($new_password)
                                        <div class="alert alert-warning mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    <strong>New Password:</strong>
                                                    <code class="ms-2">{{ $new_password }}</code>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="copyToClipboard('{{ $new_password }}')">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Confirm Password -->
                                    @if($new_password)
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">
                                            <i class="bi bi-key-fill me-2"></i>
                                            Confirm Password
                                        </label>
                                        <input type="password" class="form-control @error('confirm_password') is-invalid @enderror" 
                                               id="confirm_password" wire:model="confirm_password" 
                                               placeholder="Confirm new password">
                                        @error('confirm_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @endif
                                    
                                    <!-- Send Email Notification -->
                                    @if($new_password)
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_password_email" 
                                                   wire:model="send_password_email">
                                            <label class="form-check-label" for="send_password_email">
                                                <i class="bi bi-envelope-check me-2"></i>
                                                Send password reset email
                                            </label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <!-- No User Account -->
                                <div class="no-account mb-4">
                                    <div class="alert alert-warning">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-x me-2"></i>
                                            <div>
                                                <strong>No user account exists</strong>
                                                <div class="small">Assistant cannot login to the system</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Create Account Toggle -->
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="create_user_account" 
                                                   wire:model="create_user_account">
                                            <label class="form-check-label" for="create_user_account">
                                                <i class="bi bi-person-plus me-2"></i>
                                                Create User Account
                                            </label>
                                        </div>
                                    </div>
                                    
                                    @if($create_user_account)
                                    <!-- Password for new account -->
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">
                                            <i class="bi bi-key me-2"></i>
                                            Password <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                   id="new_password" wire:model="new_password" 
                                                   placeholder="Enter password for new account">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    wire:click="generateNewPassword" 
                                                    title="Generate new password">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </div>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($new_password)
                                        <div class="alert alert-info mt-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="bi bi-info-circle me-2"></i>
                                                    <strong>Generated Password:</strong>
                                                    <code class="ms-2">{{ $new_password }}</code>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                        onclick="copyToClipboard('{{ $new_password }}')">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @endif
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
                                    
                                    <!-- Send Welcome Email -->
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_welcome_email" 
                                                   wire:model="send_welcome_email">
                                            <label class="form-check-label" for="send_welcome_email">
                                                <i class="bi bi-envelope-check me-2"></i>
                                                Send welcome email with credentials
                                            </label>
                                        </div>
                                    </div>
                                    @endif
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
                            <h5><i class="bi bi-person-gear me-2"></i>Role & Status</h5>
                        </div>
                        <div class="form-card-body">
                            <!-- Role Selection -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Role <span class="text-danger">*</span>
                                </label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check card-role @if($role === 'assistant') active @endif" 
                                             wire:click="$set('role', 'assistant')">
                                            <input class="form-check-input" type="radio" name="role" 
                                                   id="roleAssistant" wire:model="role" value="assistant">
                                            <label class="form-check-label" for="roleAssistant">
                                                <div class="role-icon">
                                                    <i class="bi bi-box"></i>
                                                </div>
                                                <div class="role-text">
                                                    <div class="role-title">Assistant</div>
                                                    <small>Parcel Handling</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check card-role @if($role === 'supervisor') active @endif" 
                                             wire:click="$set('role', 'supervisor')">
                                            <input class="form-check-input" type="radio" name="role" 
                                                   id="roleSupervisor" wire:model="role" value="supervisor">
                                            <label class="form-check-label" for="roleSupervisor">
                                                <div class="role-icon">
                                                    <i class="bi bi-eye"></i>
                                                </div>
                                                <div class="role-text">
                                                    <div class="role-title">Supervisor</div>
                                                    <small>Team Lead</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check card-role @if($role === 'manager') active @endif" 
                                             wire:click="$set('role', 'manager')">
                                            <input class="form-check-input" type="radio" name="role" 
                                                   id="roleManager" wire:model="role" value="manager">
                                            <label class="form-check-label" for="roleManager">
                                                <div class="role-icon">
                                                    <i class="bi bi-gear"></i>
                                                </div>
                                                <div class="role-text">
                                                    <div class="role-title">Manager</div>
                                                    <small>Operations</small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

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
                                
                                <!-- Status Change Warning -->
                                @if($originalStatus !== $status)
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Status will be changed from {{ ucfirst($originalStatus) }} to {{ ucfirst($status) }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Change History -->
                    <div class="form-card mt-4">
                        <div class="form-card-header">
                            <h5><i class="bi bi-clock-history me-2"></i>Change History</h5>
                        </div>
                        <div class="form-card-body">
                            <div class="history-list">
                                <div class="history-item">
                                    <div class="history-date">Created</div>
                                    <div class="history-details">
                                        {{ $assistant->created_at->format('M d, Y H:i') }}
                                        <small class="text-muted d-block">by {{ $assistant->creator->name ?? 'System' }}</small>
                                    </div>
                                </div>
                                <div class="history-item">
                                    <div class="history-date">Last Updated</div>
                                    <div class="history-details">
                                        {{ $assistant->updated_at->format('M d, Y H:i') }}
                                        <small class="text-muted d-block">{{ $assistant->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if($assistant->user)
                                <div class="history-item">
                                    <div class="history-date">User Account</div>
                                    <div class="history-details">
                                        Created {{ $assistant->user->created_at->format('M d, Y') }}
                                        <small class="text-muted d-block">Last login: 
                                            {{ $assistant->user->last_login_at ? $assistant->user->last_login_at->diffForHumans() : 'Never' }}
                                        </small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions mt-4 pt-4 border-top">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-outline-danger" 
                                wire:click="deleteAssistant"
                                wire:confirm="Are you sure you want to delete this assistant? This action cannot be undone.">
                            <i class="bi bi-trash me-2"></i>
                            Delete Assistant
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary me-2" 
                                wire:click="resetForm"
                                wire:confirm="Discard all changes?">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset Changes
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="update">
                                <i class="bi bi-check-circle me-2"></i>
                                Update Assistant
                            </span>
                            <span wire:loading wire:target="update">
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Updating...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Assign Station Modal -->
    @if($showAssignStationModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-shop me-2"></i>
                        Assign to Station
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showAssignStationModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="selectedStation" class="form-label">Select Station</label>
                        <select class="form-select @error('selectedStation') is-invalid @enderror" 
                                id="selectedStation" wire:model="selectedStation">
                            <option value="">Choose a station...</option>
                            @foreach($stations as $station)
                                <option value="{{ $station->id }}">{{ $station->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedStation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" 
                            wire:click="$set('showAssignStationModal', false)">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-primary" wire:click="assignStation"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="assignStation">
                            <i class="bi bi-check-circle me-2"></i>
                            Assign
                        </span>
                        <span wire:loading wire:target="assignStation">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Assigning...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        /* Header Styling */
        .assistant-avatar {
            width: 70px;
            height: 70px;
        }

        .assistant-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .assistant-avatar .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.5rem;
        }

        /* Quick Stats */
        .quick-stats .stat-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .quick-stats .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
        }

        .quick-stats .stat-info {
            flex: 1;
        }

        .quick-stats .stat-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .quick-stats .stat-label {
            font-size: 0.85rem;
            color: var(--text-light);
        }

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

        /* Assignments List */
        .assignments-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
        }

        .assignment-item {
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .assignment-item:last-child {
            border-bottom: none;
        }

        .assignment-actions {
            display: flex;
            gap: 5px;
        }

        .assignment-actions .btn {
            padding: 4px 8px;
            font-size: 0.8rem;
        }

        /* History List */
        .history-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .history-item:last-child {
            border-bottom: none;
        }

        .history-date {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .history-details {
            text-align: right;
            font-size: 0.85rem;
        }

        /* Password Alert */
        .alert-info code,
        .alert-warning code {
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
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }

        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
        }

        .alert-info {
            background-color: rgba(23, 162, 184, 0.1);
            border-left: 4px solid #17a2b8;
        }

        .alert-secondary {
            background-color: rgba(108, 117, 125, 0.1);
            border-left: 4px solid #6c757d;
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

            .quick-stats .row {
                gap: 15px;
            }

            .quick-stats .col-md-3 {
                flex: 0 0 calc(50% - 7.5px);
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

            .assistant-avatar {
                width: 50px;
                height: 50px;
            }

            .assistant-avatar .avatar-placeholder {
                font-size: 1.2rem;
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

            .quick-stats .col-md-3 {
                flex: 0 0 100%;
            }
        }

        @media (max-width: 576px) {
            .role-icon {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }

            .assignment-actions {
                flex-direction: column;
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
            const passwordInput = document.getElementById('new_password');
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

            // Close modals on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && @this.showAssignStationModal) {
                    @this.set('showAssignStationModal', false);
                }
            });
        });
    </script>
    @else
        <div class="text-center py-5">
            <div class="empty-state">
                <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                <h4 class="mt-3">Assistant Not Found</h4>
                <p class="text-muted">The assistant you're trying to edit does not exist.</p>
                <button class="btn btn-primary mt-2" wire:click="$dispatch('cancelEdit')">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Assistants
                </button>
            </div>
        </div>
    @endif
</div></div>
