<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="logo-container me-2">
                <img src="{{ asset('logo.jpeg') }}" alt="{{ config('app.name') }}" height="45" class="logo-img" onerror="this.src='https://placehold.co/45x45?text=KP'">
            </div>
            <div class="brand-text d-none d-md-block">
                <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                <small class="d-block text-muted" style="font-size: 0.75rem; line-height: 1;">Send and receive parcels to/from town near you</small>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#tracking">Tracking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('points') ? 'active' : '' }}" href="{{ route('points') }}">PickUp/DropOff Points</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}" href="{{ route('pricing') }}">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}" href="{{ route('blogs.index') }}">Blog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#faq">FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}#contact">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <a href="{{ url('/') }}#tracking"
                    class="btn btn-outline-success btn-sm rounded-pill px-3">
                    <i class="bi bi-box-seam me-1"></i>
                    Track
                </a>

                <a href="{{ route('pudo.login') }}" class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="bi bi-person-circle me-1"></i>
                    Client Area
                </a>

            </div>
        </div>
    </div>
</nav>