@if(isset($pickUpAndDropOffPoints) && $pickUpAndDropOffPoints->count() > 0)
<section id="stations" class="stations-section">
    <div class="container-fluid px-4 px-lg-5">
        <div class="section-title">
            <h2>Pick-up Stations</h2>
            <p class="text-muted">Find our conveniently located stations across Kenya</p>
        </div>

        <!-- Counties Accordion -->
        <div class="counties-accordion" id="countiesAccordion">
            @foreach($counties ?? [] as $countyIndex => $county)
            <div class="county-card mb-3">
                <!-- County Header (Clickable) -->
                <div class="county-header" data-county-id="county-{{ $county->id }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-chevron-right me-3 chevron-icon"></i>
                        <h4 class="mb-0">
                            <i class="bi bi-geo-alt-fill me-2"></i>
                            {{ $county->name }} County
                        </h4>
                    </div>
                    <span class="badge bg-primary">{{ $county->points_count ?? $county->pickup_points->count() }} Pick-up/DropOff Points</span>
                </div>

                <div class="subcounties-container" id="county-{{ $county->id }}" style="display: none;">
                    <div class="subcounties-grid">
                        <div class="row g-4 points-grid">
                            @foreach($county->pickup_points as $point)
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="station-card">
                                    <div class="station-card-header">
                                        <h5 class="mb-0">{{ $point->town->name }}</h5>
                                        <span class="station-type-badge">
                                            <i class="bi bi-building"></i>
                                        </span>
                                    </div>
                                    <div class="station-card-body">
                                        <p class="mb-2">
                                            <i class="bi bi-house text-primary me-2"></i>
                                            <strong>{{ $point->name }}</strong>
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-geo-alt text-primary me-2"></i>
                                            {{ $point->address }}
                                        </p>
                                        <p class="mb-2">
                                            <i class="bi bi-telephone text-primary me-2"></i>
                                            {{ $point->contact_phone_number }}
                                        </p>
                                        <p class="mb-0">
                                            <i class="bi bi-clock text-primary me-2"></i>
                                            {{ \Carbon\Carbon::today()->setTime(8, 0)->format('h:i A') }} - {{ \Carbon\Carbon::today()->setTime(17, 0)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="text-center mt-4">
                <a target="_blank" href="{{ route('points') }}" class="btn btn-outline-primary view-more-stations">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    View All Pick Up/Drop Off Points
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
@endif