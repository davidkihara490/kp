<section id="home" class="hero-section">
    <div class="container">
        <div class="hero-content animate-fade-up">
            <h1>Send your parcel anywhere in Kenya, from wherever you are.</h1>
            <p class="lead">Book online or visit your nearest Karibu Pick-Up & Drop-Off Point (PUDO). We'll deliver it through our nationwide network of 170+ pickup points across 38+ counties.</p>
        </div>

        <div class="compact-booking-engine animate-fade-up" style="animation-delay: 0.2s;">
            <!-- Booking Tabs -->
            <ul class="nav nav-tabs booking-tabs" id="bookingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="town-tab" data-bs-toggle="tab" data-bs-target="#town" type="button" role="tab" aria-controls="town" aria-selected="true">
                        <i class="bi bi-building me-2"></i> Town to Town
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="international-tab" data-bs-toggle="tab" data-bs-target="#international" type="button" role="tab" aria-controls="international" aria-selected="false">
                        <i class="bi bi-globe me-2"></i> Point-to-Point
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Town to Town Tab -->
                <div class="tab-pane fade show active" id="town" role="tabpanel" aria-labelledby="town-tab">
                    <div class="booking-header">
                        <h6 class="text-muted mb-3">Get an instant quote for shipments between Kenyan towns</h6>
                    </div>
                    <form id="townQuoteForm">
                        <div class="booking-form-row">
                            <!-- From Town with Search -->
                            <div class="booking-field">
                                <label><i class="bi bi-geo-alt-fill me-1 text-primary"></i> From Town</label>
                                <div class="searchable-select-container">
                                    <div class="searchable-select" id="fromTownSelect">
                                        <div class="searchable-select-display" data-target="fromTown">
                                            <span class="selected-text">Select pickup town</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div class="searchable-select-dropdown" id="fromTownDropdown">
                                            <div class="search-box">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="search-input" placeholder="Search towns..." id="fromTownSearch">
                                            </div>
                                            <div class="options-list" id="fromTownOptions">
                                                @foreach($towns ?? [] as $town)
                                                <div class="option-item" data-value="{{ $town->id }}">
                                                    <i class="bi bi-building me-2"></i>
                                                    <span class="town-name">{{ $town->name }}</span>
                                                    <small class="town-county text-muted">{{ $town->subCounty?->county?->name ?? 'Kenya' }}</small>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="fromTown" name="fromTown" required>
                                </div>
                            </div>

                            <!-- To Town with Search -->
                            <div class="booking-field">
                                <label><i class="bi bi-geo-alt-fill me-1 text-danger"></i> To Town</label>
                                <div class="searchable-select-container">
                                    <div class="searchable-select" id="toTownSelect">
                                        <div class="searchable-select-display" data-target="toTown">
                                            <span class="selected-text">Select delivery town</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                        <div class="searchable-select-dropdown" id="toTownDropdown">
                                            <div class="search-box">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="search-input" placeholder="Search towns..." id="toTownSearch">
                                            </div>
                                            <div class="options-list" id="toTownOptions">
                                                @foreach($towns ?? [] as $town)
                                                <div class="option-item" data-value="{{ $town->id }}">
                                                    <i class="bi bi-building me-2"></i>
                                                    <span class="town-name">{{ $town->name }}</span>
                                                    <small class="town-county text-muted">{{ $town->subCounty?->county?->name ?? 'Kenya' }}</small>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="toTown" name="toTown" required>
                                </div>
                            </div>

                            <!-- Parcel Weight -->
                            <div class="booking-field">
                                <label><i class="bi bi-box me-1"></i> Parcel Weight (KGS)</label>
                                <input type="number" class="form-control compact-select" id="parcelWeight" placeholder="Enter weight" required>
                            </div>

                            <div class="booking-action">
                                <button class="btn btn-primary quote-btn" type="submit">
                                    <i class="bi bi-box-seam me-2"></i> Book
                                </button>
                                <button type="button" class="btn btn-outline-secondary clear-btn" id="clearForm">
                                    <i class="bi bi-arrow-counterclockwise"></i> Clear
                                </button>
                            </div>
                        </div>
                    </form>
                    <div id="quoteResult" class="compact-quote-result"></div>
                </div>

                <!-- Point-to-Point Tab -->
                <div class="tab-pane fade" id="international" role="tabpanel" aria-labelledby="international-tab">
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <div class="text-center">
                            <i class="bi bi-globe" style="font-size: 3rem; color: var(--primary-color); opacity: 0.5;"></i>
                            <h5 class="text-muted mt-3">Coming Soon</h5>
                            <p class="text-muted">You will soon be able to book your parcels and packages from this internet booking engine. Once you book, just relax while a courier is dispatched to drop off your parcels seamlessly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


