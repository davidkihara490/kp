<header class="main-header">
    @include('pages.partners.layouts.breadcrumb')

    <div class="header-right">
        <!-- Notification Dropdown -->
        <div class="dropdown notification-dropdown">
            <button class="header-icon dropdown-toggle" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                <span class="icon-badge">3</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
                <li class="dropdown-header">
                    <h6 class="mb-0">Notifications</h6>
                    <span class="badge bg-primary rounded-pill">3 New</span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item notification-item new">
                        <div class="notification-icon bg-primary">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">New Delivery Assigned</div>
                            <div class="notification-text">Delivery #DL-2024-00123 has been assigned to you</div>
                            <div class="notification-time">2 minutes ago</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item notification-item new">
                        <div class="notification-icon bg-success">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Delivery Completed</div>
                            <div class="notification-text">Delivery #DL-2024-00122 marked as delivered</div>
                            <div class="notification-time">1 hour ago</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item notification-item">
                        <div class="notification-icon bg-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">Route Update</div>
                            <div class="notification-text">Traffic alert: Mombasa Road heavy traffic</div>
                            <div class="notification-time">3 hours ago</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item notification-item">
                        <div class="notification-icon bg-info">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="notification-content">
                            <div class="notification-title">System Maintenance</div>
                            <div class="notification-text">Scheduled maintenance on Friday 10 PM - 2 AM</div>
                            <div class="notification-time">Yesterday</div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item text-center view-all">
                        <i class="bi bi-eye me-1"></i> View All Notifications
                    </a>
                </li>
            </ul>
        </div>

        <!-- Messages Dropdown -->
        <div class="dropdown message-dropdown">
            <button class="header-icon dropdown-toggle" type="button" id="messageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-chat"></i>
                <span class="icon-badge">5</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end message-menu" aria-labelledby="messageDropdown">
                <li class="dropdown-header">
                    <h6 class="mb-0">Messages</h6>
                    <span class="badge bg-primary rounded-pill">5 Unread</span>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item message-item unread">
                        <div class="message-avatar">
                            <div class="avatar-placeholder bg-success">JN</div>
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <div class="message-sender">John Ngugi</div>
                                <div class="message-time">09:45 AM</div>
                            </div>
                            <div class="message-text">Driver John has completed all deliveries for today...</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item message-item unread">
                        <div class="message-avatar">
                            <div class="avatar-placeholder bg-info">SM</div>
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <div class="message-sender">Sarah Mwangi</div>
                                <div class="message-time">Yesterday</div>
                            </div>
                            <div class="message-text">New shipment arrived at warehouse, needs assignment...</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item message-item">
                        <div class="message-avatar">
                            <div class="avatar-placeholder bg-warning">PK</div>
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <div class="message-sender">Peter Kamau</div>
                                <div class="message-time">Nov 12</div>
                            </div>
                            <div class="message-text">Monthly performance report is ready for review...</div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item message-item">
                        <div class="message-avatar">
                            <img src="https://ui-avatars.com/api/?name=Support+Team&background=007bff&color=fff" alt="Support Team">
                        </div>
                        <div class="message-content">
                            <div class="message-header">
                                <div class="message-sender">Support Team</div>
                                <div class="message-time">Nov 10</div>
                            </div>
                            <div class="message-text">Your query has been resolved. Thank you for contacting support.</div>
                        </div>
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item text-center view-all">
                        <i class="bi bi-chat-left-text me-1"></i> View All Messages
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item text-center compose-btn">
                        <i class="bi bi-pencil-square me-1"></i> Compose New
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown profile-dropdown">
            <button class="user-profile dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">JS</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->guard('partner')?->user()?->user_name  ?? '' }}</div>
                    <div class="user-role">{{ auth()->guard('partner')->user()->user_type ?? 'Partner' }}</div>
                </div>
                <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end profile-menu" aria-labelledby="profileDropdown">
                <li class="dropdown-header">
                    <div class="d-flex align-items-center">
                        <div class="avatar-large">JS</div>
                        <div class="ms-3">
                            <h6 class="mb-0">{{ auth()->guard('partner')?->user()?->user_name  ?? '' }}</h6>
                            <small class="text-muted">{{ auth()->guard('partner')->user()->email ?? 'email@example.com' }}</small>
                        </div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-person me-2"></i>
                        My Profile
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-gear me-2"></i>
                        Settings
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-clock-history me-2"></i>
                        Activity Log
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item">
                        <i class="bi bi-question-circle me-2"></i>
                        Help & Support
                    </a>
                </li>
                <li>
                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                        <i class="bi bi-chat-left-dots me-2"></i>
                        Send Feedback
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>

<!-- Hidden Logout Form -->
<form id="logout-form" action="#" method="POST" class="d-none">
    @csrf
</form>



<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">
                    <i class="bi bi-chat-left-dots me-2"></i>
                    Send Feedback
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="feedbackForm">
                    <div class="mb-3">
                        <label for="feedbackType" class="form-label">Feedback Type</label>
                        <select class="form-select" id="feedbackType" required>
                            <option value="">Select type</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">Feature Request</option>
                            <option value="improvement">Improvement Suggestion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="feedbackSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="feedbackSubject" required>
                    </div>
                    <div class="mb-3">
                        <label for="feedbackMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="feedbackMessage" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priority" id="priorityLow" value="low" checked>
                            <label class="form-check-label" for="priorityLow">Low</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priority" id="priorityMedium" value="medium">
                            <label class="form-check-label" for="priorityMedium">Medium</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="priority" id="priorityHigh" value="high">
                            <label class="form-check-label" for="priorityHigh">High</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitFeedback()">Send Feedback</button>
            </div>
        </div>
    </div>
</div>

