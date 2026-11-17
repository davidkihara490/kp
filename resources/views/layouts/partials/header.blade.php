<style>
    .pudo-navbar .pudo-brand {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
        margin-right: 20px;
    }

    .pudo-navbar .pudo-logo {
        display: block;
        width: auto;
        height: 75px;
        max-width: 240px;
        object-fit: contain;
    }

    .pudo-navbar .brand-text small {
        font-size: 0.75rem;
        line-height: 1.3;
    }

    @media (max-width: 991.98px) {
        .pudo-navbar > .container {
            gap: 12px;
        }

        .pudo-navbar .pudo-brand {
            flex: 1;
            min-width: 0;
            margin-right: 0;
            padding: 0;
        }

        .pudo-navbar .pudo-logo {
            width: 100%;
            max-width: none;
            height: 85px;
            object-fit: contain;
            object-position: left center;
        }

        .pudo-navbar .navbar-toggler {
            flex-shrink: 0;
        }

        .pudo-navbar .navbar-collapse {
            flex-basis: 100%;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top pudo-navbar">
    <div class="container">
        <a class="navbar-brand pudo-brand" href="{{ url('/') }}">
            <img
                src="{{ asset('logo.jpeg') }}"
                alt="{{ config('app.name') }}"
                class="pudo-logo"
            >

            <div class="brand-text d-none d-lg-block">
                <span class="fw-bold fs-5">
                    {{ config('app.name') }}
                </span>

                <small class="d-block text-muted">
                    Send and receive parcels to/from town near you
                </small>
            </div>
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                        href="{{ url('/') }}#home"
                    >
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#tracking">
                        Tracking
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('points') ? 'active' : '' }}"
                        href="{{ route('points') }}"
                    >
                        PickUp/DropOff Points
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}"
                        href="{{ route('pricing') }}"
                    >
                        Pricing
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}"
                        href="{{ route('blogs.index') }}"
                    >
                        Blog
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#faq">
                        FAQ
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#contact">
                        Contact
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <a
                    href="{{ url('/') }}#tracking"
                    class="btn btn-outline-success btn-sm rounded-pill px-3"
                >
                    <i class="bi bi-box-seam me-1" aria-hidden="true"></i>
                    Track
                </a>

                <a
                    href="{{ route('pudo.login') }}"
                    class="btn btn-success btn-sm rounded-pill px-3"
                >
                    <i class="bi bi-person-circle me-1" aria-hidden="true"></i>
                    Client Area
                </a>
            </div>
        </div>
    </div>
</nav>