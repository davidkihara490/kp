<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @if (file_exists(public_path('logo.jpeg')))
                        <img src="{{ asset('logo.jpeg') }}" alt="{{ config('app.name') }}" height="35" style="border-radius: 6px;">
                        @else
                        <div style="width: 35px; height: 35px; background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="bi bi-truck"></i>
                        </div>
                        @endif
                        <span class="fw-bold fs-5 text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-light opacity-75" style="font-size: 0.9rem; max-width: 300px;">
                        Professional courier services for individuals and businesses. Fast, reliable, and secure deliveries across Kenya.
                    </p>
                    <div class="social-icons mt-3">
                        <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Track Parcel</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Our Services</h6>
                <ul class="footer-links">
                    <li><a href="#">Same Day Delivery</a></li>
                    <li><a href="#">Next Day Delivery</a></li>
                    <li><a href="#">International Shipping</a></li>
                    <li><a href="#">Bulk Courier</a></li>
                    <li><a href="#">Corporate Solutions</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading">Contact Info</h6>
                <ul class="footer-contact">
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Nairobi, Kenya</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope"></i>
                        <a href="mailto:info@karibuparcels.com">info@karibuparcels.com</a>
                    </li>
                    <li>
                        <i class="bi bi-telephone"></i>
                        <a href="tel:+254712345678">+254 712 345 678</a>
                    </li>
                    <li>
                        <i class="bi bi-clock"></i>
                        <span>Mon - Fri: 8:00 AM - 6:00 PM</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 small text-light opacity-75">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="footer-bottom-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        background: var(--dark-bg);
        color: white;
        padding: 60px 0 20px;
        position: relative;
        margin-top: 40px;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .footer-heading {
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        font-size: 1rem;
        letter-spacing: 0.5px;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: #adb5bd;
        text-decoration: none;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .footer-links a:hover {
        color: white;
        padding-left: 5px;
    }

    .footer-contact {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-contact li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 0.9rem;
        color: #adb5bd;
    }

    .footer-contact li i {
        color: var(--primary-color);
        font-size: 1.1rem;
        width: 20px;
        flex-shrink: 0;
    }

    .footer-contact li a {
        color: #adb5bd;
        text-decoration: none;
        transition: var(--transition);
    }

    .footer-contact li a:hover {
        color: white;
    }

    .social-icons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .social-icons a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        color: white;
        transition: var(--transition);
        font-size: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .social-icons a:hover {
        background: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 143, 64, 0.3);
        border-color: var(--primary-color);
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding-top: 20px;
        margin-top: 40px;
    }

    .footer-bottom-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        gap: 20px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .footer-bottom-links li a {
        color: #adb5bd;
        text-decoration: none;
        font-size: 0.8rem;
        transition: var(--transition);
    }

    .footer-bottom-links li a:hover {
        color: white;
    }

    @media (max-width: 768px) {
        .footer {
            padding: 40px 0 15px;
        }

        .footer-bottom-links {
            justify-content: center;
            margin-top: 10px;
            gap: 15px;
        }

        .footer-bottom-links li a {
            font-size: 0.75rem;
        }
    }
</style>