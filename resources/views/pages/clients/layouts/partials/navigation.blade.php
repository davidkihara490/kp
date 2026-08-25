<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" style="backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95) !important;">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <div class="d-flex align-items-center gap-2">
                @if (file_exists(public_path('logo.jpeg')))
                <img src="{{ asset('logo.jpeg') }}" alt="{{ config('app.name') }}" height="40" style="border-radius: 8px;">
                @else
                <div class="brand-icon" style="width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="bi bi-truck"></i>
                </div>
                @endif
                <span class="fw-bold fs-5" style="color: var(--text-dark);">
                    {{ config('app.name') }}
                </span>
            </div>
        </a>

        <!-- Toggle Button -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Centered Navigation Links -->
            <ul class="navbar-nav mx-auto align-items-lg-center gap-1 gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('services*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-grid me-1"></i>Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tracking*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-geo-alt me-1"></i>Track
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-info-circle me-1"></i>About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact*') ? 'active' : '' }}" href="#">
                        <i class="bi bi-envelope me-1"></i>Contact
                    </a>
                </li>
            </ul>

            <!-- Right Side - Auth Links -->
            <ul class="navbar-nav align-items-lg-center gap-1 gap-lg-2">
                @auth('customer')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="d-none d-md-inline">{{ Auth::guard('customer')->user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('pudo.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-wallet me-2"></i>Wallet
                                <span class="badge bg-primary rounded-pill ms-2">0</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('pudo.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pudo.login') ? 'active' : '' }}" href="{{ route('pudo.login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary btn-sm px-4 rounded-pill" href="{{ route('pudo.register') }}" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border: none;">
                        <i class="bi bi-person-plus me-1"></i>Get Started
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        padding: 12px 0;
        transition: all 0.3s ease;
    }

    .navbar .nav-link {
        font-weight: 500;
        padding: 0.5rem 1rem !important;
        border-radius: 30px;
        transition: var(--transition);
        font-size: 0.9rem;
        color: var(--text-dark) !important;
        position: relative;
    }

    .navbar .nav-link:hover {
        background: var(--primary-light);
        color: var(--primary-color) !important;
    }

    .navbar .nav-link.active {
        background: var(--primary-color);
        color: white !important;
    }

    .navbar .nav-link i {
        font-size: 0.9rem;
    }

    .navbar .dropdown-menu {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        padding: 8px;
        min-width: 200px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .navbar .dropdown-item {
        border-radius: 8px;
        padding: 8px 16px;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .navbar .dropdown-item:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .navbar .dropdown-item i {
        width: 20px;
        font-size: 1rem;
    }

    .navbar .dropdown-item .badge {
        font-size: 0.65rem;
        padding: 2px 8px;
        margin-left: auto;
    }

    .navbar .dropdown-divider {
        margin: 6px 0;
    }

    /* Dropdown toggle styling */
    .navbar .dropdown-toggle::after {
        margin-left: 4px;
        vertical-align: middle;
    }

    .navbar .dropdown-toggle .d-none {
        display: inline !important;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .navbar .nav-link {
            padding: 0.5rem 0.75rem !important;
        }

        .navbar .btn {
            width: 100%;
            text-align: center;
            margin-top: 8px;
        }

        .navbar-nav.mx-auto {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        /* On mobile, move auth links below centered links */
        .navbar-collapse {
            flex-direction: column;
        }

        .navbar-collapse .navbar-nav:last-child {
            width: 100%;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
            margin-top: 10px;
        }

        .navbar-collapse .navbar-nav:last-child .nav-item {
            width: 100%;
        }

        .navbar-collapse .navbar-nav:last-child .btn {
            width: 100%;
            margin-top: 4px;
        }

        /* Dropdown on mobile */
        .navbar .dropdown-menu {
            border: none;
            box-shadow: none;
            padding-left: 10px;
            background: transparent;
        }

        .navbar .dropdown-item {
            padding: 6px 12px;
        }

        .navbar .dropdown-item:hover {
            background: var(--primary-light);
        }
    }

    @media (max-width: 576px) {
        .navbar-brand .fw-bold {
            font-size: 1rem;
        }

        .navbar-brand img {
            height: 32px;
        }

        .navbar .dropdown-toggle .d-none {
            display: none !important;
        }
    }

    /* Desktop specific - ensure proper alignment */
    @media (min-width: 993px) {
        .navbar-collapse {
            display: flex !important;
            flex-basis: auto;
            justify-content: space-between;
        }

        .navbar-nav.mx-auto {
            flex: 1;
            justify-content: center;
        }

        .navbar-nav:last-child {
            flex-shrink: 0;
        }
    }
</style>

<!-- JavaScript to handle responsive dropdown -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(function(dropdown) {
                const toggle = dropdown.closest('.dropdown').querySelector('.dropdown-toggle');
                if (toggle && !toggle.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });

        // Handle responsive behavior
        const navbarToggler = document.querySelector('.navbar-toggler');
        const navbarCollapse = document.querySelector('.navbar-collapse');

        if (navbarToggler && navbarCollapse) {
            navbarToggler.addEventListener('click', function() {
                // Toggle collapse
                navbarCollapse.classList.toggle('show');
            });
        }
    });
</script>
@endpush