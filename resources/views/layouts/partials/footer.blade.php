<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h4 class="mb-3">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}" style="color:white;">
                        <div class="logo-container me-2">
                            <img src="{{ asset('logo.jpeg') }}" alt="{{ config('app.name') }}" height="45" style="border-radius: 10px;" onerror="this.src='https://placehold.co/45x45/008f40/white?text=KP'">
                        </div>
                        <div class="brand-text">
                            <span class="fw-bold fs-5">{{ config('app.name') }}</span>
                            <small class="d-block text-muted" style="font-size: 0.75rem;">Send your parcel anywhere in Kenya, from wherever you are.</small>
                        </div>
                    </a>
                </h4>
                <p class="opacity-75">Your trusted partner for fast, reliable, and secure courier services across Kenya.</p>
                <div class="social-icons">
                    <a target="_blank" href="https://www.facebook.com/karibuparcels"><i class="bi bi-facebook"></i></a>
                    <a target="_blank" href="https://www.instagram.com/karibuparcels/"><i class="bi bi-instagram"></i></a>
                    <a target="_blank" href="https://www.tiktok.com/@karibuparcels"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Services</h5>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#features">Town to Town Sending</a></li>
                    <li><a href="{{ url('/') }}#features">Parcel Receiving</a></li>
                    <li><a href="{{ url('/') }}#features">Forwarding Service</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Company</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('blogs.index') }}">Blog</a></li>
                    <li><a href="{{ url('/') }}#contact">Contact</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Resources</h5>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#faq">FAQs</a></li>
                    <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Legal</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                    <li><a href="{{ route('policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('prohibited-items') }}">Prohibited Items</a></li>
                </ul>
            </div>
        </div>

        <!-- Business Solutions Section -->
        <div class="business-solutions mt-5 pt-4 border-top">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="business-solutions-text">
                        <span class="badge bg-primary mb-2">BUSINESS SOLUTIONS</span>
                        <h4 class="mb-1 text-black">Send your parcel anywhere in Kenya, from wherever you are.</h4>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="business-buttons d-flex gap-3 flex-wrap justify-content-lg-end">
                        <a href="{{ route('partners.login') }}" class="business-btn partner-btn">
                            <span class="btn-icon"><i class="bi bi-briefcase"></i></span>
                            <span class="btn-text">
                                <small>Partner Portal</small><br>
                                <strong>Login</strong>
                            </span>
                            <span class="btn-arrow"><i class="bi bi-box-arrow-in-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mt-4 mb-4">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} Karibu Parcels. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="mb-0 small text-muted">
                    <i class="bi bi-shield-check me-1"></i>Secure & Reliable |
                    <i class="bi bi-truck ms-2 me-1"></i>40+ Counties |
                    <i class="bi bi-clock ms-2 me-1"></i>24/7 Support
                </p>
            </div>
        </div>
    </div>
</footer>