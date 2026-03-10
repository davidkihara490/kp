<div>
    <div>
        @if ($assistant)
            <div class="dashboard-section">
                <!-- Header Section -->
                <div class="section-header">
                    <div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="assistant-avatar-large">
                                @if ($assistant->user && $assistant->user->avatar)
                                    <img src="{{ $assistant->user->avatar }}" alt="{{ $assistant->full_name }}"
                                        class="rounded-circle">
                                @else
                                    <div class="avatar-placeholder">
                                        {{ substr($assistant->first_name, 0, 1) }}{{ substr($assistant->last_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="section-title mb-1">{{ $assistant->full_name }}</h3>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge {{ $this->getRoleBadgeClass() }}">
                                        <i class="bi {{ $this->getRoleIcon() }} me-1"></i>
                                        {{ ucfirst($assistant->role) }}
                                    </span>
                                    <span class="status-badge {{ $this->getStatusBadgeClass() }}">
                                        <i class="bi {{ $this->getStatusIcon() }} me-1"></i>
                                        {{ ucfirst($assistant->status) }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="bi bi-person-badge me-1"></i>
                                        ID: {{ $assistant->id_number }}
                                    </span>
                                    @if ($assistant->user)
                                        <span class="badge bg-success">
                                            <i class="bi bi-person-check me-1"></i>
                                            User Account
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <p class="section-subtitle mt-2">
                            <i class="bi bi-telephone me-1"></i>
                            {{ $assistant->phone_number }}
                            <i class="bi bi-envelope ms-3 me-1"></i>
                            {{ $assistant->email }}
                        </p>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-outline-secondary" wire:click="$dispatch('closeView')">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back
                        </button>
                        <button class="btn btn-outline-primary"
                            wire:click="$dispatch('editAssistant', {{ $assistant->id }})">
                            <i class="bi bi-pencil me-2"></i>
                            Edit
                        </button>
                        @if ($assistant->status === 'active')
                            <button class="btn btn-outline-warning" wire:click="confirmSuspend">
                                <i class="bi bi-pause-circle me-2"></i>
                                Suspend
                            </button>
                        @elseif($assistant->status === 'suspended')
                            <button class="btn btn-outline-success" wire:click="activateAssistant">
                                <i class="bi bi-play-circle me-2"></i>
                                Activate
                            </button>
                        @elseif($assistant->status === 'inactive')
                            <button class="btn btn-outline-success" wire:click="activateAssistant">
                                <i class="bi bi-toggle-on me-2"></i>
                                Activate
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions Bar -->
                <div class="quick-actions-bar mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        @if ($assistant->user)
                            <button class="btn btn-outline-info" wire:click="sendLoginCredentials">
                                <i class="bi bi-envelope-arrow-up me-2"></i>
                                Send Login
                            </button>
                            <button class="btn btn-outline-secondary" wire:click="resetPassword">
                                <i class="bi bi-key me-2"></i>
                                Reset Password
                            </button>
                        @else
                            <button class="btn btn-outline-success" wire:click="createUserAccount">
                                <i class="bi bi-person-plus me-2"></i>
                                Create Account
                            </button>
                        @endif
                        <button class="btn btn-outline-primary" wire:click="showAssignStation">
                            <i class="bi bi-shop me-2"></i>
                            Assign Station
                        </button>
                        <button class="btn btn-outline-dark" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i>
                            Print Profile
                        </button>
                        <button class="btn btn-outline-danger" wire:click="deleteAssistant"
                            wire:confirm="Are you sure you want to delete this assistant?">
                            <i class="bi bi-trash me-2"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Stats Overview -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $stationAssigned->pickUpAndDropOffPoint->name }}</div>
                                        <div class="stat-label">Point Assigned</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $parcelsToday }}</div>
                                        <div class="stat-label">Parcels Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-week"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $parcelsThisWeek }}</div>
                                        <div class="stat-label">This Week</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-icon">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div class="stat-content">
                                        <div class="stat-value">{{ $lastActivity?->diffForHumans() ?? 'Never' }}</div>
                                        <div class="stat-label">Last Activity</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Cards -->
                        <div class="row">
                            <!-- Personal Details -->
                            <div class="col-md-6 mb-4">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <h5><i class="bi bi-person-badge me-2"></i>Personal Details</h5>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-person"></i>
                                                Full Name
                                            </div>
                                            <div class="info-value">{{ $assistant->full_name }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-credit-card"></i>
                                                ID Number
                                            </div>
                                            <div class="info-value">{{ $assistant->id_number }}</div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-telephone"></i>
                                                Phone Number
                                            </div>
                                            <div class="info-value">
                                                <a href="tel:{{ $assistant->phone_number }}"
                                                    class="text-decoration-none">
                                                    {{ $assistant->phone_number }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-envelope"></i>
                                                Email Address
                                            </div>
                                            <div class="info-value">
                                                <a href="mailto:{{ $assistant->email }}"
                                                    class="text-decoration-none">
                                                    {{ $assistant->email }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="col-md-6 mb-4">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <h5><i class="bi bi-shield-lock me-2"></i>Account Information</h5>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-person-gear"></i>
                                                Role
                                            </div>
                                            <div class="info-value">
                                                <span class="badge {{ $this->getRoleBadgeClass() }}">
                                                    {{ ucfirst($assistant->role) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">
                                                <i class="bi bi-activity"></i>
                                                Status
                                            </div>
                                            <div class="info-value">
                                                <span class="status-badge {{ $this->getStatusBadgeClass() }}">
                                                    {{ ucfirst($assistant->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($assistant->user)
                                            <div class="info-item">
                                                <div class="info-label">
                                                    <i class="bi bi-person-check"></i>
                                                    User Account
                                                </div>
                                                <div class="info-value">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success">Active</span>
                                                        <small class="text-muted">Username:
                                                            {{ $assistant->user->user_name }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">
                                                    <i class="bi bi-clock-history"></i>
                                                    Last Login
                                                </div>
                                                <div class="info-value">
                                                    {{ $assistant->user->last_login_at ? $assistant->user->last_login_at->format('M d, Y H:i') : 'Never' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="info-item">
                                                <div class="info-label">
                                                    <i class="bi bi-person-x"></i>
                                                    User Account
                                                </div>
                                                <div class="info-value">
                                                    <span class="badge bg-secondary">Not Created</span>
                                                    <small class="text-muted d-block">Assistant cannot login to
                                                        system</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="col-12 mb-4">
                                <div class="info-card">
                                    <div class="info-card-header d-flex justify-content-between align-items-center">
                                        <h5><i class="bi bi-activity me-2"></i>Recent Activity</h5>
                                        <a href="#" class="btn btn-sm btn-outline-secondary">View All</a>
                                    </div>
                                    <div class="info-card-body">
                                        {{-- <div class="activity-timeline">
                                            @if ($recentActivities->count() > 0)
                                                @foreach ($recentActivities as $activity)
                                                    <div class="activity-item">
                                                        <div class="activity-icon bg-{{ $activity['color'] }}">
                                                            <i class="bi {{ $activity['icon'] }}"></i>
                                                        </div>
                                                        <div class="activity-content">
                                                            <div class="activity-text">{{ $activity['text'] }}</div>
                                                            <div class="activity-time">{{ $activity['time'] }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-3">
                                                    <i class="bi bi-clock-history text-muted"></i>
                                                    <p class="text-muted mt-2">No recent activity</p>
                                                </div>
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Quick Stats -->
                        <div class="info-card mb-4">
                            <div class="info-card-header">
                                <h5><i class="bi bi-graph-up me-2"></i>Performance Overview</h5>
                            </div>
                            <div class="info-card-body">
                                <div class="performance-stats">
                                    <div class="performance-item">
                                        <div class="performance-label">Parcel Accuracy</div>
                                        <div class="performance-value">98%</div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 98%"></div>
                                        </div>
                                    </div>
                                    <div class="performance-item">
                                        <div class="performance-label">On-time Delivery</div>
                                        <div class="performance-value">95%</div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-info" style="width: 95%"></div>
                                        </div>
                                    </div>
                                    <div class="performance-item">
                                        <div class="performance-label">Customer Rating</div>
                                        <div class="performance-value">4.8/5</div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 96%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="info-card mb-4">
                            <div class="info-card-header">
                                <h5><i class="bi bi-info-circle me-2"></i>System Information</h5>
                            </div>
                            <div class="info-card-body">
                                <div class="system-info">
                                    <div class="system-item">
                                        <div class="system-label">Created On</div>
                                        <div class="system-value">
                                            {{ $assistant->created_at->format('M d, Y') }}
                                            <small
                                                class="text-muted d-block">{{ $assistant->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="system-item">
                                        <div class="system-label">Created By</div>
                                        <div class="system-value">{{ $assistant->creator->name ?? 'System' }}</div>
                                    </div>
                                    <div class="system-item">
                                        <div class="system-label">Last Updated</div>
                                        <div class="system-value">
                                            {{ $assistant->updated_at->format('M d, Y') }}
                                            <small
                                                class="text-muted d-block">{{ $assistant->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    @if ($assistant->user)
                                        <div class="system-item">
                                            <div class="system-label">Account Created</div>
                                            <div class="system-value">
                                                {{ $assistant->user->created_at->format('M d, Y') }}
                                                <small
                                                    class="text-muted d-block">{{ $assistant->user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <h5><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                            </div>
                            <div class="info-card-body">
                                <div class="quick-links">
                                    <a href="#" class="quick-link">
                                        <div class="quick-link-icon bg-primary">
                                            <i class="bi bi-qr-code"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div>Generate QR Badge</div>
                                            <small>Print assistant badge</small>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <div class="quick-link-icon bg-success">
                                            <i class="bi bi-calendar-plus"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div>Schedule Training</div>
                                            <small>Schedule new training session</small>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <div class="quick-link-icon bg-info">
                                            <i class="bi bi-file-text"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div>Performance Report</div>
                                            <small>Generate detailed report</small>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                    <a href="#" class="quick-link">
                                        <div class="quick-link-icon bg-warning">
                                            <i class="bi bi-chat-left-text"></i>
                                        </div>
                                        <div class="quick-link-text">
                                            <div>Send Message</div>
                                            <small>Send SMS/Email notification</small>
                                        </div>
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        @if ($assistant->notes)
                            <div class="info-card mt-4">
                                <div class="info-card-header">
                                    <h5><i class="bi bi-sticky me-2"></i>Notes</h5>
                                </div>
                                <div class="info-card-body">
                                    <div class="notes-content">
                                        {{ $assistant->notes }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Assign Station Modal -->
            @if ($showAssignStationModal)
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-shop me-2"></i>
                                    Assign to Station
                                </h5>
                                <button type="button" class="btn-close"
                                    wire:click="$set('showAssignStationModal', false)"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="selectedStation" class="form-label">Select Station</label>
                                    <select class="form-select @error('selectedStation') is-invalid @enderror"
                                        id="selectedStation" wire:model="selectedStation">
                                        <option value="">Choose a station...</option>
                                        @foreach ($stations as $station)
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

            <!-- Suspend Confirmation Modal -->
            @if ($showSuspendModal)
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Suspend Assistant
                                </h5>
                                <button type="button" class="btn-close"
                                    wire:click="$set('showSuspendModal', false)"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center py-3">
                                    <i class="bi bi-pause-circle text-warning display-4"></i>
                                    <h4 class="mt-3">Suspend {{ $assistant->first_name }}?</h4>
                                    <p class="text-muted">
                                        This will suspend the assistant's account and deactivate all station
                                        assignments.
                                    </p>
                                    <div class="alert alert-warning mt-3">
                                        <i class="bi bi-info-circle me-2"></i>
                                        <strong>Note:</strong> The assistant will not be able to login or perform any
                                        operations.
                                    </div>
                                    <div class="mt-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sendNotification"
                                                wire:model="sendSuspensionNotification">
                                            <label class="form-check-label" for="sendNotification">
                                                Send suspension notification email
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showSuspendModal', false)">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-warning" wire:click="suspendAssistant"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="suspendAssistant">
                                        <i class="bi bi-pause-circle me-2"></i>
                                        Suspend Assistant
                                    </span>
                                    <span wire:loading wire:target="suspendAssistant">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Suspending...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Create Account Modal -->
            @if ($showCreateAccountModal)
                <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5)" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Create User Account
                                </h5>
                                <button type="button" class="btn-close"
                                    wire:click="$set('showCreateAccountModal', false)"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <i class="bi bi-person-check display-4 text-success"></i>
                                    <h4 class="mt-3">Create Account for {{ $assistant->first_name }}</h4>
                                    <p class="text-muted">This will allow the assistant to login to the system.</p>
                                </div>

                                <div class="mb-3">
                                    <label for="generatedPassword" class="form-label">Generated Password</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="generatedPassword"
                                            value="{{ $generatedPassword }}" readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="copyToClipboard('{{ $generatedPassword }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Copy this password and share it with the assistant</div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sendWelcomeEmail"
                                            wire:model="sendWelcomeEmail">
                                        <label class="form-check-label" for="sendWelcomeEmail">
                                            <i class="bi bi-envelope-check me-2"></i>
                                            Send welcome email with login credentials
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="$set('showCreateAccountModal', false)">
                                    Cancel
                                </button>
                                <button type="button" class="btn btn-success" wire:click="createAccount"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="createAccount">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Create Account
                                    </span>
                                    <span wire:loading wire:target="createAccount">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <style>
                /* Header Styling */
                .assistant-avatar-large {
                    width: 80px;
                    height: 80px;
                }

                .assistant-avatar-large img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .assistant-avatar-large .avatar-placeholder {
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 600;
                    font-size: 2rem;
                }

                /* Status Badges */
                .status-badge {
                    display: inline-flex;
                    align-items: center;
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 0.85rem;
                    font-weight: 500;
                    white-space: nowrap;
                }

                .status-active {
                    background-color: rgba(40, 167, 69, 0.1);
                    color: #155724;
                    border: 1px solid rgba(40, 167, 69, 0.2);
                }

                .status-inactive {
                    background-color: rgba(108, 117, 125, 0.1);
                    color: #495057;
                    border: 1px solid rgba(108, 117, 125, 0.2);
                }

                .status-suspended {
                    background-color: rgba(220, 53, 69, 0.1);
                    color: #721c24;
                    border: 1px solid rgba(220, 53, 69, 0.2);
                }

                .status-pending {
                    background-color: rgba(255, 193, 7, 0.1);
                    color: #856404;
                    border: 1px solid rgba(255, 193, 7, 0.2);
                }

                /* Quick Actions Bar */
                .quick-actions-bar {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 10px;
                    border: 1px solid var(--border-color);
                }

                /* Stats Cards */
                .stats-overview .stat-card {
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    border: 1px solid var(--border-color);
                    display: flex;
                    align-items: center;
                    gap: 15px;
                    height: 100%;
                }

                .stats-overview .stat-icon {
                    width: 50px;
                    height: 50px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    color: white;
                }

                .stats-overview .stat-card:nth-child(1) .stat-icon {
                    background: linear-gradient(135deg, #17a2b8, #138496);
                }

                .stats-overview .stat-card:nth-child(2) .stat-icon {
                    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                }

                .stats-overview .stat-card:nth-child(3) .stat-icon {
                    background: linear-gradient(135deg, #6c757d, #495057);
                }

                .stats-overview .stat-card:nth-child(4) .stat-icon {
                    background: linear-gradient(135deg, #ffc107, #fd7e14);
                }

                .stats-overview .stat-content {
                    flex: 1;
                }

                .stats-overview .stat-value {
                    font-size: 1.8rem;
                    font-weight: 700;
                    color: var(--text-dark);
                    line-height: 1;
                }

                .stats-overview .stat-label {
                    font-size: 0.9rem;
                    color: var(--text-light);
                    margin-top: 5px;
                }

                /* Information Cards */
                .info-card {
                    background: white;
                    border-radius: 12px;
                    border: 1px solid var(--border-color);
                    height: 100%;
                }

                .info-card-header {
                    padding: 15px 20px;
                    border-bottom: 1px solid var(--border-color);
                    background: #f8f9fa;
                    border-radius: 12px 12px 0 0;
                }

                .info-card-header h5 {
                    font-size: 1rem;
                    font-weight: 600;
                    margin: 0;
                    color: var(--text-dark);
                    display: flex;
                    align-items: center;
                }

                .info-card-body {
                    padding: 20px;
                }

                .info-item {
                    margin-bottom: 15px;
                }

                .info-item:last-child {
                    margin-bottom: 0;
                }

                .info-label {
                    font-size: 0.85rem;
                    color: var(--text-light);
                    margin-bottom: 5px;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .info-value {
                    font-size: 1rem;
                    color: var(--text-dark);
                    font-weight: 500;
                }

                .info-value a {
                    color: var(--primary-color);
                    text-decoration: none;
                }

                .info-value a:hover {
                    text-decoration: underline;
                }

                /* Performance Stats */
                .performance-stats {
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 15px;
                }

                .performance-item {
                    margin-bottom: 15px;
                }

                .performance-item:last-child {
                    margin-bottom: 0;
                }

                .performance-label {
                    font-size: 0.9rem;
                    color: var(--text-dark);
                    margin-bottom: 5px;
                }

                .performance-value {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: var(--primary-color);
                    margin-bottom: 5px;
                }

                /* System Information */
                .system-info {
                    background: #f8f9fa;
                    border-radius: 8px;
                    padding: 15px;
                }

                .system-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    padding: 10px 0;
                    border-bottom: 1px dashed #dee2e6;
                }

                .system-item:last-child {
                    border-bottom: none;
                }

                .system-label {
                    font-weight: 600;
                    color: var(--text-dark);
                    font-size: 0.9rem;
                }

                .system-value {
                    text-align: right;
                    font-size: 0.85rem;
                }

                /* Quick Links */
                .quick-links {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }

                .quick-link {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px;
                    border-radius: 8px;
                    text-decoration: none;
                    color: inherit;
                    border: 1px solid var(--border-color);
                    transition: all 0.2s ease;
                }

                .quick-link:hover {
                    background-color: #f8f9fa;
                    border-color: var(--primary-color);
                    transform: translateX(5px);
                }

                .quick-link-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 1.2rem;
                }

                .quick-link-text {
                    flex: 1;
                }

                .quick-link-text div {
                    font-weight: 500;
                    font-size: 0.95rem;
                }

                .quick-link-text small {
                    font-size: 0.8rem;
                    color: var(--text-light);
                }

                /* Activity Timeline */
                .activity-timeline {
                    max-height: 300px;
                    overflow-y: auto;
                }

                .activity-item {
                    display: flex;
                    gap: 12px;
                    padding: 12px 0;
                    border-bottom: 1px solid var(--border-color);
                }

                .activity-item:last-child {
                    border-bottom: none;
                }

                .activity-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    flex-shrink: 0;
                }

                .activity-content {
                    flex: 1;
                }

                .activity-text {
                    font-size: 0.9rem;
                    font-weight: 500;
                    margin-bottom: 3px;
                }

                .activity-time {
                    font-size: 0.8rem;
                    color: var(--text-light);
                }

                /* Notes Content */
                .notes-content {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    border-left: 4px solid var(--primary-color);
                    white-space: pre-line;
                }

                /* Action Buttons */
                .action-buttons {
                    display: flex;
                    gap: 5px;
                }

                .action-buttons .btn {
                    padding: 5px 8px;
                    font-size: 0.875rem;
                    border-radius: 6px;
                }

                /* Modal Styling */
                .modal .modal-content {
                    border-radius: 12px;
                    border: none;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
                }

                .modal-header {
                    border-bottom: 1px solid var(--border-color);
                    background: #f8f9fa;
                    border-radius: 12px 12px 0 0;
                }

                .modal-body {
                    padding: 25px;
                }

                .modal-footer {
                    border-top: 1px solid var(--border-color);
                    padding: 20px;
                }

                /* Responsive Design */
                @media (max-width: 992px) {
                    .header-actions {
                        width: 100%;
                        margin-top: 15px;
                    }

                    .stats-overview .stat-card {
                        flex-direction: column;
                        text-align: center;
                    }

                    .stats-overview .stat-content {
                        width: 100%;
                    }
                }

                @media (max-width: 768px) {
                    .assistant-avatar-large {
                        width: 60px;
                        height: 60px;
                    }

                    .assistant-avatar-large .avatar-placeholder {
                        font-size: 1.5rem;
                    }

                    .section-title {
                        font-size: 1.3rem;
                    }

                    .quick-actions-bar .btn {
                        flex: 1;
                        min-width: 120px;
                    }

                    .info-card-header h5 {
                        font-size: 0.95rem;
                    }

                    .info-card-body {
                        padding: 15px;
                    }

                    .activity-item {
                        flex-direction: column;
                        text-align: center;
                    }

                    .activity-icon {
                        margin: 0 auto;
                    }
                }

                @media (max-width: 576px) {
                    .quick-actions-bar .d-flex {
                        flex-direction: column;
                    }

                    .quick-actions-bar .btn {
                        width: 100%;
                    }

                    .stats-overview .row {
                        gap: 15px;
                    }

                    .stats-overview .col-md-3 {
                        flex: 0 0 100%;
                    }

                    .quick-link {
                        flex-direction: column;
                        text-align: center;
                        padding: 15px;
                    }

                    .quick-link-icon {
                        margin: 0 auto;
                    }

                    .quick-link-text {
                        text-align: center;
                    }

                    .quick-link i.bi-chevron-right {
                        display: none;
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize tooltips
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });

                    // Copy to clipboard function
                    window.copyToClipboard = function(text) {
                        navigator.clipboard.writeText(text).then(function() {
                            // Show success message
                            const alert = document.createElement('div');
                            alert.className =
                                'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
                            alert.style.zIndex = '9999';
                            alert.innerHTML = `
                        <i class="bi bi-check-circle me-2"></i>
                        Copied to clipboard!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                            document.body.appendChild(alert);

                            setTimeout(() => {
                                alert.remove();
                            }, 3000);
                        });
                    }

                    // Close modals on escape key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            @if ($showAssignStationModal)
                                @this.set('showAssignStationModal', false);
                            @endif
                            @if ($showSuspendModal)
                                @this.set('showSuspendModal', false);
                            @endif
                            @if ($showCreateAccountModal)
                                @this.set('showCreateAccountModal', false);
                            @endif
                        }
                    });

                    // Print functionality
                    window.addEventListener('beforeprint', function() {
                        // Add print-specific styles
                        document.body.classList.add('printing');
                    });

                    window.addEventListener('afterprint', function() {
                        // Remove print-specific styles
                        document.body.classList.remove('printing');
                    });
                });

                // Livewire listeners
                Livewire.on('closeView', () => {
                    console.log('Closing assistant view');
                    // Navigate back to list
                });

                Livewire.on('editAssistant', (assistantId) => {
                    console.log('Edit assistant:', assistantId);
                    // Navigate to edit page
                });

                Livewire.on('assistantUpdated', () => {
                    console.log('Assistant updated, refreshing view...');
                    // Refresh view data
                });

                Livewire.on('accountCreated', () => {
                    console.log('Account created successfully');
                    // Show success message or refresh
                });
            </script>
        @else
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="bi bi-exclamation-triangle display-1 text-warning"></i>
                    <h4 class="mt-3">Assistant Not Found</h4>
                    <p class="text-muted">The assistant you're trying to view does not exist or has been removed.</p>
                    <button class="btn btn-primary mt-2" wire:click="$dispatch('closeView')">
                        <i class="bi bi-arrow-left me-2"></i>
                        Back to Assistants
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
