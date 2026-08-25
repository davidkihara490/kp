<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="/" class="brand-logo">
            <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels">

            <div class="brand-text">
                <h1>{{ config('app.name') }}</h1>

                <p class="tagline">
                    {{ ucfirst(str_replace(['-', '_'], ' ', auth()->guard('partner')->user()->user_type ?? 'Partner')) }}
                </p>
            </div>
        </a>
    </div>

    @php
    $partnerUser = auth()->guard('partner')->user();

    $partnerType = $partnerUser?->user_type;

    $dashboardRoute = match ($partnerType) {
    'pickup-dropoff',
    'pick_up_and_drop_off' => 'partners.pd.dashboard',

    'transport' => 'partners.transport.dashboard',
    'driver' => 'partners.driver.dashboard',
    'pha' => 'partners.pha.dashboard',

    default => 'partners.login',
    };
    @endphp

    <nav class="sidebar-nav">

        <!-- Main -->
        <div class="nav-section">
            <div class="nav-section-title">Main</div>

            <a href="{{ route($dashboardRoute) }}"
                class="nav-link {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('partners.parcels.index') }}"
                class="nav-link {{ request()->routeIs('partners.parcels.*') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Parcels</span>
            </a>

            <a href="{{ route('partners.commissions') }}"
                class="nav-link {{ request()->routeIs('partners.commissions') ? 'active' : '' }}">
                <i class="bi bi-bank"></i>
                <span>Commission Structure</span>
            </a>
        </div>

        @if (in_array($partnerType, ['pickup-dropoff', 'pick_up_and_drop_off', 'transport']))
        <div class="nav-section">
            <div class="nav-section-title">Management</div>

            @if (in_array($partnerType, ['pickup-dropoff', 'pick_up_and_drop_off']))
            <a href="{{ route('partners.pha.index') }}"
                class="nav-link {{ request()->routeIs('partners.pha.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Parcel Handling Ass.</span>
            </a>

            <a href="{{ route('partners.pd.index') }}"
                class="nav-link {{ request()->routeIs('partners.pd.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i>
                <span>PickUp & DropOff Points</span>
            </a>
            @endif

            @if ($partnerType === 'transport')
            <a href="{{ route('partners.fleet.index') }}"
                class="nav-link {{ request()->routeIs('partners.fleet.*') ? 'active' : '' }}">
                <i class="bi bi-truck-front"></i>
                <span>Fleet</span>
            </a>

            <a href="{{ route('partners.drivers.index') }}"
                class="nav-link {{ request()->routeIs('partners.drivers.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Drivers</span>
            </a>
            @endif
        </div>
        @endif

        <!-- Settings -->
        <div class="nav-section">
            <div class="nav-section-title">Settings</div>

            @if (in_array($partnerType, ['pickup-dropoff', 'pick_up_and_drop_off', 'transport']))
            <a href="{{ route('partners.profile.edit') }}"
                class="nav-link {{ request()->routeIs('partners.profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>Partner Profile</span>
            </a>
            @endif

            <a href="{{ route('partners.password-change') }}"
                class="nav-link {{ request()->routeIs('partners.password-change') ? 'active' : '' }}">
                <i class="bi bi-shield-check"></i>
                <span>Security</span>
            </a>
        </div>

    </nav>
</div>


<header class="main-header">

    @include('pages.partners.layouts.breadcrumb')

    <div class="header-right">

        <div class="dropdown profile-dropdown">
            <button
                class="user-profile dropdown-toggle"
                type="button"
                id="profileDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->guard('partner')->user()->user_name ?? 'P', 0, 1)) }}
                </div>

                <div class="user-info">
                    <div class="user-name">
                        {{ auth()->guard('partner')->user()->user_name ?? 'Partner' }}
                    </div>

                    <div class="user-role">
                        {{ ucfirst(str_replace(
                    ['-', '_'],
                    ' ',
                    auth()->guard('partner')->user()->user_type ?? 'Partner'
                )) }}
                    </div>
                </div>

                <i class="bi bi-chevron-down"></i>
            </button>

            <ul
                class="dropdown-menu dropdown-menu-end profile-menu"
                aria-labelledby="profileDropdown">
                <li>
                    <div class="dropdown-header">
                        <strong>
                            {{ auth()->guard('partner')->user()->user_name ?? 'Partner' }}
                        </strong>

                        <small class="d-block text-muted">
                            {{ auth()->guard('partner')->user()->email ?? '' }}
                        </small>
                    </div>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a
                        href="{{ route('partners.password-change') }}"
                        class="dropdown-item">
                        <i class="bi bi-shield-lock me-2"></i>
                        Security
                    </a>
                </li>

                <li>
                    <a
                        href="#"
                        class="dropdown-item"
                        data-bs-toggle="modal"
                        data-bs-target="#feedbackModal">
                        <i class="bi bi-chat-left-dots me-2"></i>
                        Send Feedback
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form method="POST" action="{{ route('partners.logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>

</header>


<!-- Feedback Modal -->
<div
    class="modal fade"
    id="feedbackModal"
    tabindex="-1"
    aria-labelledby="feedbackModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">
                    <i class="bi bi-chat-left-dots me-2"></i>
                    Send Feedback
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form id="feedbackForm">

                    <div class="mb-3">
                        <label for="feedbackType" class="form-label">
                            Feedback Type
                        </label>

                        <select
                            class="form-select"
                            id="feedbackType"
                            required>
                            <option value="">Select type</option>
                            <option value="bug">Bug Report</option>
                            <option value="feature">Feature Request</option>
                            <option value="improvement">Improvement Suggestion</option>
                            <option value="other">Other</option>
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="feedbackSubject" class="form-label">
                            Subject
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="feedbackSubject"
                            required>
                    </div>


                    <div class="mb-3">
                        <label for="feedbackMessage" class="form-label">
                            Message
                        </label>

                        <textarea
                            class="form-control"
                            id="feedbackMessage"
                            rows="4"
                            required></textarea>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Priority</label>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="priority"
                                id="priorityLow"
                                value="low"
                                checked>

                            <label
                                class="form-check-label"
                                for="priorityLow">
                                Low
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="priority"
                                id="priorityMedium"
                                value="medium">

                            <label
                                class="form-check-label"
                                for="priorityMedium">
                                Medium
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="priority"
                                id="priorityHigh"
                                value="high">

                            <label
                                class="form-check-label"
                                for="priorityHigh">
                                High
                            </label>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="submitFeedback()">
                    Send Feedback
                </button>

            </div>

        </div>
    </div>
</div>