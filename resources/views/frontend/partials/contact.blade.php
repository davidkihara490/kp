<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-title">
            <h2>Contact Us</h2>
            <p class="text-muted">Get in touch with our customer support team</p>
        </div>

        <div class="row">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="contact-form">
                    <h4 class="mb-4">Send us a message</h4>
                    <form id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" id="contactName" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control" id="contactEmail" placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="contactSubject" placeholder="Subject" required>
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" id="contactMessage" rows="5" placeholder="Your Message" required></textarea>
                        </div>
                        <div id="contactFormMessage" class="mb-3" style="display: none;"></div>
                        <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn">
                            <i class="bi bi-send me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="contact-info">
                    <h4 class="mb-4">Contact Information</h4>

                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-telephone fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Phone Support</h5>
                            <p>+254 700 130 759<br>
                                <small class="opacity-75">24/7 Customer Service</small>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-envelope fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Email Support</h5>
                            <p>karibuparcels@gmail.com<br>
                                <small class="opacity-75">Response within 2 hours</small>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-geo-alt fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Head Office</h5>
                            <p>Mashariki Breeze, Diani Beach Road, Office No.6, Diani Beach, Kwale County</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0">
                            <i class="bi bi-mailbox2 fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>P.O. Box</h5>
                            <p>P.O. Box 5490-80401<br>
                                <small class="opacity-75">Diani Beach, Kenya</small>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-clock fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5>Business Hours</h5>
                            <p>Monday - Friday: 8:00 AM - 8:00 PM<br>
                                Saturday: 9:00 AM - 6:00 PM<br>
                                Sunday: 10:00 AM - 4:00 PM</p>
                        </div>
                    </div>

                    <div class="social-icons mt-4">
                        <a target="_blank" href="https://www.facebook.com/karibuparcels"><i class="bi bi-facebook"></i></a>
                        <a target="_blank" href="https://www.instagram.com/karibuparcels/"><i class="bi bi-instagram"></i></a>
                        <a target="_blank" href="https://www.tiktok.com/@karibuparcels"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>