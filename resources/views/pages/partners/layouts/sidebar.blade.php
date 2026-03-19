<div class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <a href="/" class="brand-logo">
            <img src="{{ asset('logo.jpeg') }}" alt="Karibu Parcels">
            <div class="brand-text">
                <h1>{{ config('app.name') }}</h1>
                {{-- TODO::Add role here --}}
                <p class="tagline">Partner</p>
            </div>
        </a>
    </div>

    @php
    $partnerType = Auth::guard('partner')->user()->partner?->partner_type ?? Auth::guard('partner')->user()->driver?->partner ?? Auth::guard('partner')->user()->parcelHandlingAssistant?->partner;

    $dashboardRoute = match($partnerType) {
    'pick_up_and_drop_off' => 'partners.pd.dashboard',
    'transport' => 'partners.transport.dashboard',
    'driver' => 'partners.driver.dashboard',
    'pha' => 'partners.pha.dashboard',
    default => 'partners.login'
    };
    @endphp

    <nav class="sidebar-nav">
        <!-- Main Section -->
        <div class="nav-section">
            <div class="nav-section-title">Main</div>
            <a href="{{ route($dashboardRoute) }}" class="nav-link active">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('partners.parcels.index') }}" class="nav-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Parcels</span>
            </a>
            @if($partnerType == "pickup-dropoff")
            <a href="#" class="nav-link">
                <i class="bi bi-inbox"></i>
                <span>Incoming Parcels</span>
            </a>
            @endif

            <a href="#" class="nav-link">
                <i class="bi bi-bank"></i>
                <span>Earnings</span>
            </a>
        </div>

        @if (auth()->guard('partner')?->user()?->user_type == 'pickup-dropoff' || auth()->guard('partner')?->user()?->user_type == 'transport')
        <div class="nav-section">
            <div class="nav-section-title">Management</div>
            @if (auth()->guard('partner')?->user()?->user_type == 'pickup-dropoff')
            <a href="{{ route('partners.pha.index') }}" class="nav-link">
                <i class="bi bi-people"></i>
                <span>Parcel Handling Ass.</span>
            </a>
            <a href="{{ route('partners.pd.index') }}" class="nav-link">
                <i class="bi bi-truck"></i>
                <span>PickUp & DropOff Points</span>
            </a>
            @endif

            @if (auth()->guard('partner')->user()->user_type == 'transport')
            <a href="{{ route('partners.fleet.index') }}" class="nav-link">
                <i class="bi bi-box-seam"></i>
                <span>Fleet</span>
            </a>
            <a href="{{ route('partners.drivers.index') }}" class="nav-link">
                <i class="bi bi-cash"></i>
                <span>Drivers</span>
            </a>
            <a href="{{ route('marketplace') }}" class="nav-link" target="_blank">
                <i class="bi bi-bank"></i>
                <span>Marketplace</span>
            </a>
            @endif
        </div>
        @endif

        <!-- Reports Section -->
        <div class="nav-section">
            <div class="nav-section-title">Reports</div>
            <a href="#" class="nav-link">
                <i class="bi bi-bar-chart"></i>
                <span>Daily Report</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-pie-chart"></i>
                <span>Monthly Report</span>
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Settings</div>
            @if ( auth()->guard('partner')->user()->user_type == 'pickup-dropoff' || auth()->guard('partner')->user()->user_type == 'transport' || auth()->guard('partner')->user()->user_type == 'in-charge')
            <a href="{{ route('partners.profile.edit') }}" class="nav-link">
                <i class="bi bi-person-circle"></i>
                <span>Partner Profile</span>
            </a>
            @endif
            <a href="{{ route('partners.roles-and-permissions.index') }}" class="nav-link">
                <i class="bi bi-lock"></i>
                <span>Roles And Permissions</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-shield-check"></i>
                <span>Security</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-headset"></i>
                <span>Support</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="station-info">
            <div class="station-status">
                <form method="POST" action="{{ route('partners.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger bg-transparent border-0 p-0">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>