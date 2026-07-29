<section id="tracking" class="tracking-section">
    <div class="container">
        <div class="section-title">
            <h2>Track Your Package</h2>
            <p class="text-muted">Enter your tracking ID to track your package in real-time</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="tracking-card">
                    <h3 class="mb-4"><i class="bi bi-box-seam me-2"></i> Track Your Shipment</h3>
                    <p class="mb-4 opacity-75">Real-time tracking for complete peace of mind</p>

                    <div class="tracking-form-wrapper">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-10">
                                <label class="text-white small fw-bold mb-2">Parcel Number</label>
                                <div class="tracking-input-group">
                                    <i class="bi bi-upc-scan text-muted me-2"></i>
                                    <input type="text" class="tracking-input" id="trackingId" placeholder="KP78945" value="{{ request('tracking_id') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="tracking-btn w-100" id="trackPackage">
                                    <i class="bi bi-search me-2"></i>Track
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="trackingResult" class="mt-4" style="display: none;"></div>

                    <div class="mt-3 text-center">
                        <small class="opacity-75">
                            <i class="bi bi-info-circle me-1"></i>
                            Enter your tracking number (e.g., KP78945) to get real-time updates
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>