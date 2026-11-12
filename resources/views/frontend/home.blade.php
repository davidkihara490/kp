@extends('layouts.app')

@section('title', config('app.name') . ' - Send and receive parcels to/from town near you')

@section('meta')
<meta name="description" content="Send and receive parcels anywhere in Kenya with Karibu Parcels. Fast, reliable, and secure courier services across 38+ counties with 170+ pickup points.">
@endsection

@section('content')
<!-- Hero Section -->
@include('frontend.partials.hero')

<!-- Stats Section -->
@include('frontend.partials.stats')

<!-- Features Section -->
@include('frontend.partials.features')

<!-- Tracking Section -->
@include('frontend.partials.tracking')

<!-- Trusted By Section -->
@include('frontend.partials.trusted')

<!-- Stations Section -->
@include('frontend.partials.stations')

<!-- Blog Section -->
@include('frontend.partials.blogs')

<!-- FAQ Section -->
@include('frontend.partials.faqs')

<!-- Contact Section -->
@include('frontend.partials.contact')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '#fromTownOptions .option-item', function() {
            const townId = $(this).data('value');
            const townName = $(this).find('.town-name').text();
            // 1. Update hidden input value (so form submission & JS read the ID)
            $('#fromTown').val(townId);
            // 2. Update display text in the select box
            $('#fromTownSelect .selected-text').text(townName);
            // 3. Close the dropdown menu
            $('#fromTownDropdown').removeClass('show'); // or hide it based on your toggle class
        });

        $(document).on('click', '#toTownOptions .option-item', function() {
            const townId = $(this).data('value');
            const townName = $(this).find('.town-name').text();
            // 1. Update hidden input value (so form submission & JS read the ID)
            $('#toTown').val(townId);
            // 2. Update display text in the select box
            $('#toTownSelect .selected-text').text(townName);
            // 3. Close the dropdown menu
            $('#toTownDropdown').removeClass('show'); // or hide it based on your toggle class
        });

        //Category
        $(document).on('click', '#parcelCategoryOptions .option-item', function() {
            const parcelCategoryId = $(this).data('value');
            const parcelCategoryName = $(this).find('.town-name').text();
            // 1. Update hidden input value (so form submission & JS read the ID)
            $('#parcelCategory').val(parcelCategoryId);
            // 2. Update display text in the select box
            $('#parcelCategorySelect .selected-text').text(parcelCategoryName);
            // 3. Close the dropdown menu
            $('#parcelCategoryDropDown').removeClass('show'); // or hide it based on your toggle class
        });

        // ============================================
        // BOOKING ENGINE
        // ============================================
        // ============================================
        // BOOKING ENGINE
        // ============================================
        $('#townQuoteForm').on('submit', function(e) {
            e.preventDefault();

            const fromTown = $('#fromTown').val();
            const toTown = $('#toTown').val();
            const parcelCategory = $('#parcelCategory').val();

            if (!fromTown || !toTown || !parcelCategory) {
                showAlert('Please fill in all required fields', 'warning', '#quoteResult');
                return;
            }

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Searching partners...');

            $.ajax({
                url: '/api/calculate-quote',
                method: 'POST',
                data: {
                    from_town_id: fromTown,
                    to_town_id: toTown,
                    parcel_category_id: parcelCategory,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (!response.success || !response.partners || response.partners.length === 0) {
                        showAlert(response.message || 'No transport partners available for this route.', 'warning', '#quoteResult');
                        return;
                    }
                    displayPartnerOptions(response);
                },
                error: function(xhr) {
                    let message = 'An error occurred while fetching transport partners.';
                    if (xhr.responseJSON?.message) message = xhr.responseJSON.message;
                    showAlert(message, 'danger', '#quoteResult');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        $('#clearForm').on('click', function() {
            $('#townQuoteForm')[0].reset();
            $('#fromTown, #toTown, #parcelCategory').val('');
            $('#fromTownSelect .selected-text, #toTownSelect .selected-text, #parcelCategorySelect .selected-text')
                .text(function(i, txt) {
                    return i === 0 ? 'Select pickup town' :
                        i === 1 ? 'Select delivery town' : 'Select parcel category';
                });
            $('#quoteResult').removeClass('show').empty();
        });

        // --------------------------------------------
        // Render selectable transport partner cards
        // --------------------------------------------
        function displayPartnerOptions(data) {
            const fromText = $('#fromTownSelect .selected-text').text();
            const toText = $('#toTownSelect .selected-text').text();

            const cardsHtml = data.partners.map(function(partner, index) {
                const logo = partner.logo ?
                    `<img src="${partner.logo}" alt="${partner.name}" class="partner-logo">` :
                    `<div class="partner-logo-placeholder"><i class="bi bi-truck"></i></div>`;

                const eta = partner.estimated_days ?
                    `<span class="partner-eta"><i class="bi bi-clock me-1"></i>~${partner.estimated_days} day${partner.estimated_days > 1 ? 's' : ''}</span>` :
                    '';

                const description = partner.description ?
                    `<p class="partner-desc">${partner.description}</p>` :
                    '';

                return `
            <label class="partner-option" for="partner_${partner.id}">
                <input type="radio"
                       name="selectedPartner"
                       id="partner_${partner.id}"
                       value="${partner.id}"
                       data-price="${partner.price}"
                       data-name="${partner.name}"
                       class="partner-radio"
                       ${index === 0 ? 'checked' : ''}>
                <div class="partner-card">
                    <div class="partner-brand">
                        ${logo}
                        <div class="partner-info">
                            <h6 class="partner-name">${partner.name}</h6>
                            ${description}
                            ${eta}
                        </div>
                    </div>
                    <div class="partner-price">
                        <span class="price-label">KES</span>
                        <span class="price-amount">${Number(partner.price).toLocaleString()}</span>
                    </div>
                </div>
            </label>
        `;
            }).join('');

            const html = `
        <div class="quote-result-card">
            <div class="quote-header">
                <span class="quote-badge">AVAILABLE TRANSPORT PARTNERS</span>
                <button type="button" class="btn-close" id="closeQuote"></button>
            </div>
            <div class="quote-body">
                <div class="quote-route">
                    <div class="route-point text-primary">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>${fromText}</span>
                    </div>
                    <div class="route-arrow"><i class="bi bi-arrow-right"></i></div>
                    <div class="route-point text-danger">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>${toText}</span>
                    </div>
                </div>

                <div class="partners-list">
                    ${cardsHtml}
                </div>

                <div class="quote-message">
                    <i class="bi bi-info-circle-fill text-primary"></i>
                    <p>Select a transport partner above, then proceed to book your parcel online.</p>
                </div>

                <div class="quote-actions">
                    <a href="{{ route('points') }}" class="btn btn-outline-primary">
                        <i class="bi bi-geo-alt me-1"></i> Find Station
                    </a>
                    <button type="button" class="btn btn-primary quote-btn" id="proceedWithPartner">
                        <i class="bi bi-box-seam me-1"></i> Book With Selected Partner
                    </button>
                </div>
            </div>
        </div>
    `;

            $('#quoteResult').html(html).addClass('show');

            // Highlight the selected card
            $('#quoteResult').on('change', '.partner-radio', function() {
                $('.partner-option').removeClass('selected');
                $(this).closest('.partner-option').addClass('selected');
            });
            $('.partner-option').first().addClass('selected');

            $('#closeQuote').on('click', function() {
                $('#quoteResult').removeClass('show').empty();
            });

            $('#proceedWithPartner').on('click', function() {
                const selected = $('#quoteResult .partner-radio:checked');
                if (!selected.length) {
                    showAlert('Please select a transport partner.', 'warning', '#quoteResult');
                    return;
                }

                const partnerId = selected.val();
                const partnerName = selected.data('name');
                const price = selected.data('price');

                const bookingUrl = `{{ route('online-booking') }}` +
                    `?from_town_id=${data.from_town_id}` +
                    `&to_town_id=${data.to_town_id}` +
                    `&category=${data.parcel_category_id}` +
                    `&partner_id=${encodeURIComponent(partnerId)}` +
                    `&partner_name=${encodeURIComponent(partnerName)}` +
                    `&price=${encodeURIComponent(price)}`;

                window.location.href = bookingUrl;
            });
        }

        // function displayQuoteResult(data) {
        //     const fromText = $('#fromTownSelect .selected-text').text();
        //     const toText = $('#toTownSelect .selected-text').text();

        //     const bookingUrl = `{{ route('online-booking') }}?from_town_id=${data.from_town_id}&to_town_id=${data.to_town_id}&category=${data.parcel_category_id}&price=${data.total}`;

        //     const html = `
        //     <div class="quote-result-card">
        //         <div class="quote-header">
        //             <span class="quote-badge">INSTANT QUOTE</span>
        //             <button type="button" class="btn-close" id="closeQuote"></button>
        //         </div>
        //         <div class="quote-body">
        //             <div class="quote-route">
        //                 <div class="route-point text-primary">
        //                     <i class="bi bi-geo-alt-fill"></i>
        //                     <span>${fromText}</span>
        //                 </div>
        //                 <div class="route-arrow"><i class="bi bi-arrow-right"></i></div>
        //                 <div class="route-point text-danger">
        //                     <i class="bi bi-geo-alt-fill"></i>
        //                     <span>${toText}</span>
        //                 </div>
        //             </div>

        //             <div class="price-breakdown">
        //                 <div class="breakdown-item total">
        //                     <span>Total Amount</span>
        //                     <span class="total-amount">KES ${data.total}</span>
        //                 </div>
        //             </div>

        //             <div class="quote-message">
        //                 <i class="bi bi-info-circle-fill text-primary"></i>
        //                 <p>Please proceed to the nearest Karibu Parcels station to send your parcel.</p>
        //             </div>

        //             <div class="quote-actions">
        //                 <a href="{{ route('points') }}" class="btn btn-outline-primary">
        //                     <i class="bi bi-geo-alt me-1"></i> Find Station
        //                 </a>
        //                 <a href="${bookingUrl}" class="btn btn-primary quote-btn">
        //                     <i class="bi bi-box-seam me-1"></i> Book Online
        //                 </a>
        //             </div>
        //         </div>
        //     </div>
        // `;

        //     $('#quoteResult').html(html).addClass('show');
        //     $('#closeQuote').on('click', function() {
        //         $('#quoteResult').removeClass('show').empty();
        //     });
        // }

        // ============================================
        // TRACKING
        // ============================================
        $('#trackPackage').on('click', function() {
            const trackingId = $('#trackingId').val().trim();
            if (!trackingId) {
                showAlert('Please enter a tracking number', 'warning', '#trackingResult');
                return;
            }

            const btn = $(this);
            const originalText = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>');

            $.ajax({
                url: '/api/track-parcel',
                method: 'GET',
                data: {
                    tracking_id: trackingId
                },
                success: function(response) {
                    if (response.success && response.data) {
                        displayTrackingResult(response.data);
                    } else {
                        showAlert(response.message || 'No parcel found', 'warning', '#trackingResult');
                    }
                },
                error: function() {
                    showAlert('Error tracking parcel. Please try again.', 'danger', '#trackingResult');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        function displayTrackingResult(parcel) {
            const statusMap = {
                'created': {
                    label: 'Created',
                    icon: 'bi-file-earmark',
                    class: 'bg-secondary'
                },
                'booked': {
                    label: 'Booked',
                    icon: 'bi-calendar-check',
                    class: 'bg-info'
                },
                'accepted': {
                    label: 'Accepted',
                    icon: 'bi-check-circle',
                    class: 'bg-primary'
                },
                'assigned': {
                    label: 'Assigned',
                    icon: 'bi-person-check',
                    class: 'bg-primary'
                },
                'in_transit': {
                    label: 'In Transit',
                    icon: 'bi-truck',
                    class: 'bg-warning text-dark'
                },
                'warehouse': {
                    label: 'At Warehouse',
                    icon: 'bi-building-warehouse',
                    class: 'bg-info'
                },
                'arrived_at_destination': {
                    label: 'Arrived',
                    icon: 'bi-geo-alt-fill',
                    class: 'bg-success'
                },
                'picked': {
                    label: 'Picked Up',
                    icon: 'bi-box-seam',
                    class: 'bg-primary'
                },
                'delivered': {
                    label: 'Delivered',
                    icon: 'bi-check-circle-fill',
                    class: 'bg-success'
                },
                'failed': {
                    label: 'Failed',
                    icon: 'bi-x-circle',
                    class: 'bg-danger'
                }
            };

            const status = statusMap[parcel.current_status] || {
                label: parcel.current_status || 'Unknown',
                icon: 'bi-question-circle',
                class: 'bg-secondary'
            };
            const history = parcel.status_history || [];

            let timelineHtml = '';
            history.forEach(item => {
                const s = statusMap[item.status] || {
                    label: item.status,
                    icon: 'bi-circle'
                };
                timelineHtml += `
                <div class="timeline-item completed">
                    <div class="timeline-marker"><i class="bi bi-check-circle-fill text-success"></i></div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <span class="timeline-status">${s.label}</span>
                            <span class="timeline-date">${formatDate(item.created_at)}</span>
                        </div>
                        ${item.location ? `<div class="timeline-location"><i class="bi bi-geo-alt"></i> ${item.location}</div>` : ''}
                    </div>
                </div>
            `;
            });

            const html = `
            <div class="tracking-result-card">
                <div class="tracking-header">
                    <div class="tracking-title">
                        <i class="bi bi-box-seam"></i>
                        <div>
                            <h6>Parcel #${parcel.parcel_id || parcel.tracking_id}</h6>
                            <p>${parcel.sender_phone || parcel.phone || ''}</p>
                        </div>
                    </div>
                    <div class="tracking-status">
                        <span class="badge ${status.class}">
                            <i class="bi ${status.icon} me-1"></i> ${status.label}
                        </span>
                    </div>
                    <button class="btn-close" id="closeTracking"></button>
                </div>

                <div class="tracking-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="label">From</span>
                            <span class="value">${parcel.from_location || parcel.sender_town || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">To</span>
                            <span class="value">${parcel.to_location || parcel.receiver_town || 'N/A'}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Current Location</span>
                            <span class="value">${parcel.current_location || parcel.delivery_point || 'N/A'}</span>
                        </div>
                    </div>
                </div>

                ${history.length ? `
                <div class="tracking-timeline">
                    <h6><i class="bi bi-clock-history me-2"></i>Tracking History</h6>
                    <div class="timeline">${timelineHtml}</div>
                </div>
                ` : ''}

                <div class="tracking-footer">
                    <p class="small text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        ${parcel.last_updated ? `Last updated: ${formatDate(parcel.last_updated)}` : 'Updates available in real-time'}
                    </p>
                </div>
            </div>
        `;

            $('#trackingResult').html(html).slideDown();
            $('#closeTracking').on('click', function() {
                $('#trackingResult').slideUp().empty();
            });
        }

        // ============================================
        // CONTACT FORM
        // ============================================
        $('#contactForm').on('submit', function(e) {
            e.preventDefault();

            const btn = $('#contactSubmitBtn');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Sending...');

            $.ajax({
                url: '/send-contact-email',
                method: 'POST',
                data: {
                    name: $('#contactName').val(),
                    email: $('#contactEmail').val(),
                    subject: $('#contactSubject').val(),
                    message: $('#contactMessage').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    showAlert(response.message || 'Message sent successfully!', 'success', '#contactFormMessage');
                    $('#contactForm')[0].reset();
                },
                error: function() {
                    showAlert('Failed to send message. Please try again.', 'danger', '#contactFormMessage');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ============================================
        // STATIONS ACCORDION
        // ============================================
        $('.county-header').on('click', function() {
            const countyId = $(this).data('county-id');
            const container = $('#' + countyId);
            const chevron = $(this).find('.chevron-icon');

            if (container.is(':visible')) {
                container.slideUp(300);
                chevron.removeClass('bi-chevron-down').addClass('bi-chevron-right');
                $(this).removeClass('active');
            } else {
                $('.subcounties-container').slideUp(300);
                $('.county-header').removeClass('active');
                $('.county-header .chevron-icon').removeClass('bi-chevron-down').addClass('bi-chevron-right');

                container.slideDown(300);
                chevron.removeClass('bi-chevron-right').addClass('bi-chevron-down');
                $(this).addClass('active');
            }
        });
    });
</script>
@endpush