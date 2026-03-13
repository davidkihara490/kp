<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Karibu Parcels - Professional Courier Service</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/frontend.css') }}">
    <script src="https://cdn.tiny.cloud/1/3culyhhybbcchz5f5d6o066dedtcc2ugjb92n22l8ocyw9rv/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

</head>

<body>

    @yield('partner-content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Location data
            const locationData = {
                nairobi: {
                    subcounties: ['Westlands', 'Dagoretti', 'Langata', 'Kasarani', 'Embakasi', 'Starehe'],
                    towns: {
                        Westlands: ['Westlands CBD', 'Parklands', 'Lavington', 'Kileleshwa'],
                        Dagoretti: ['Dagoretti North', 'Dagoretti South', 'Uthiru'],
                        Langata: ['Karen', 'Langata', 'South C'],
                        Kasarani: ['Kasarani', 'Mwiki', 'Clay City'],
                        Embakasi: ['Embakasi Central', 'Embakasi East', 'Embakasi West'],
                        Starehe: ['CBD', 'Nairobi West', 'Buruburu']
                    }
                },
                mombasa: {
                    subcounties: ['Mvita', 'Changamwe', 'Kisauni', 'Likoni', 'Jomvu'],
                    towns: {
                        Mvita: ['Mombasa Island', 'Old Town', 'Tudor'],
                        Changamwe: ['Changamwe', 'Airport', 'Miritini'],
                        Kisauni: ['Kisauni', 'Bamburi', 'Mkomani'],
                        Likoni: ['Likoni', 'Mtongwe', 'Shelly Beach'],
                        Jomvu: ['Jomvu Kuu', 'Mikindani']
                    }
                },
                kisumu: {
                    subcounties: ['Kisumu Central', 'Kisumu East', 'Kisumu West', 'Seme', 'Nyando'],
                    towns: {
                        'Kisumu Central': ['Kisumu CBD', 'Milimani', 'Nyalenda'],
                        'Kisumu East': ['Kondele', 'Manyatta', 'Nyamasaria'],
                        'Kisumu West': ['Kiboswa', 'Maseno', 'Ojolla'],
                        'Seme': ['Seme', 'Awasi'],
                        'Nyando': ['Ahero', 'Kaduong', 'Kobura']
                    }
                },
                nakuru: {
                    subcounties: ['Nakuru Town East', 'Nakuru Town West', 'Naivasha', 'Gilgil', 'Molo'],
                    towns: {
                        'Nakuru Town East': ['Nakuru CBD', 'Milimani', 'Kiamunyi'],
                        'Nakuru Town West': ['Lanet', 'Barut', 'Kaptembwo'],
                        'Naivasha': ['Naivasha Town', 'Karai', 'Kinungi'],
                        'Gilgil': ['Gilgil Town', 'Mbaruk', 'Elementaita'],
                        'Molo': ['Molo Town', 'Elburgon', 'Turi']
                    }
                },
                eldoret: {
                    subcounties: ['Kapseret', 'Kesses', 'Soy', 'Turbo', 'Ainabkoi'],
                    towns: {
                        Kapseret: ['Eldoret Town', 'Kipkenyo', 'Kapsaret'],
                        Kesses: ['Kesses', 'Chebororwa', 'Tulwet'],
                        Soy: ['Soy', 'Kapsowar', 'Kapsimotwo'],
                        Turbo: ['Turbo', 'Kamagut', 'Ngeria'],
                        Ainabkoi: ['Ainabkoi', 'Kapsoya', 'Kimumu']
                    }
                }
            };

            // Initialize booking form
            initBookingForm();

            // Initialize tracking
            initTracking();

            // Initialize stations
            initCollapsibleStations();

            // Initialize FAQ
            initFAQ();

            // Initialize contact form
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                alert('Thank you for your message! We will get back to you shortly.');
                this.reset();
            });

            // Smooth scroll
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();

                const targetId = $(this).attr('href');
                if (targetId === '#') return;

                const targetElement = $(targetId);
                if (targetElement.length) {
                    $('html, body').animate({
                        scrollTop: targetElement.offset().top - 80
                    }, 500);
                }
            });

            // Booking form functions
            function initBookingForm() {
                // From location handlers
                $('#wideFromCounty').on('change', function() {
                    updateLocationOptions($(this), $('#wideFromSubcounty'), $('#wideFromTown'));
                });

                $('#wideFromSubcounty').on('change', function() {
                    updateTownOptions($('#wideFromCounty').val(), $(this).val(), $('#wideFromTown'));
                });

                // To location handlers
                $('#wideToCounty').on('change', function() {
                    updateLocationOptions($(this), $('#wideToSubcounty'), $('#wideToTown'));
                });

                $('#wideToSubcounty').on('change', function() {
                    updateTownOptions($('#wideToCounty').val(), $(this).val(), $('#wideToTown'));
                });

                // Weight buttons
                $('.weight-option').on('click', function() {
                    $('.weight-option').removeClass('btn-primary').addClass('btn-outline-primary');
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                    $('#wideItemWeight').val($(this).data('weight'));
                });

                // Form submission
                $('#wideQuoteForm').on('submit', function(e) {
                    e.preventDefault();

                    if (!validateWideForm()) {
                        return;
                    }

                    const quote = calculateWideQuote();
                    displayWideQuoteResult(quote);
                });

                // Clear form
                $('#wideClearForm').on('click', function() {
                    $('#wideQuoteForm')[0].reset();
                    $('.weight-option').removeClass('btn-primary').addClass('btn-outline-primary');
                    $('#wideItemWeight').val('');
                    $('#wideQuoteResult').removeClass('show').empty();

                    // Reset location dropdowns
                    $('[id$="Subcounty"], [id$="Town"]').each(function() {
                        $(this).html('<option value="">Select ' + ($(this).attr('id').includes(
                            'Town') ? 'Town' : 'Subcounty') + '</option>');
                        $(this).prop('disabled', true);
                    });
                });
            }

            function updateLocationOptions(countySelect, subcountySelect, townSelect) {
                const county = countySelect.val();

                if (!county) {
                    subcountySelect.html('<option value="">Select Subcounty</option>');
                    subcountySelect.prop('disabled', true);
                    townSelect.html('<option value="">Select Town</option>');
                    townSelect.prop('disabled', true);
                    return;
                }

                const subcounties = locationData[county]?.subcounties || [];

                let options = '<option value="">Select Subcounty</option>';
                $.each(subcounties, function(index, subcounty) {
                    options += `<option value="${subcounty}">${subcounty}</option>`;
                });

                subcountySelect.html(options);
                subcountySelect.prop('disabled', false);

                townSelect.html('<option value="">Select Town</option>');
                townSelect.prop('disabled', true);
            }

            function updateTownOptions(county, subcounty, townSelect) {
                if (!subcounty) {
                    townSelect.html('<option value="">Select Town</option>');
                    townSelect.prop('disabled', true);
                    return;
                }

                const towns = locationData[county]?.towns[subcounty] || [];

                let options = '<option value="">Select Town</option>';
                $.each(towns, function(index, town) {
                    options += `<option value="${town}">${town}</option>`;
                });

                townSelect.html(options);
                townSelect.prop('disabled', false);
            }

            function validateWideForm() {
                const required = ['wideFromCounty', 'wideFromSubcounty', 'wideFromTown', 'wideToCounty',
                    'wideToSubcounty', 'wideToTown'
                ];
                const itemWeight = $('#wideItemWeight').val();
                const itemDescription = $('#wideItemDescription').val().trim();

                // Check required fields
                for (const field of required) {
                    const value = $('#' + field).val();
                    if (!value) {
                        showAlert('Please fill in all required fields', 'warning');
                        $('#' + field).focus();
                        return false;
                    }
                }

                // Check weight selection
                if (!itemWeight) {
                    showAlert('Please select package weight', 'warning');
                    return false;
                }

                // Check item description
                if (!itemDescription) {
                    showAlert('Please describe your item', 'warning');
                    $('#wideItemDescription').focus();
                    return false;
                }

                // Check if pickup and delivery are same
                if ($('#wideFromCounty').val() === $('#wideToCounty').val() &&
                    $('#wideFromSubcounty').val() === $('#wideToSubcounty').val() &&
                    $('#wideFromTown').val() === $('#wideToTown').val()) {
                    showAlert('Pickup and delivery locations cannot be the same', 'warning');
                    return false;
                }

                return true;
            }

            function showAlert(message, type) {
                const alertClass = type === 'warning' ? 'alert-warning' : 'alert-danger';
                const alert = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                $('#wideQuoteForm').prepend(alert);

                setTimeout(() => {
                    alert.alert('close');
                }, 5000);
            }

            function calculateWideQuote() {
                const fromCounty = $('#wideFromCounty').val();
                const toCounty = $('#wideToCounty').val();
                const weight = $('#wideItemWeight').val();

                // Base price
                let basePrice = 300;

                // Add distance factor
                if (fromCounty !== toCounty) {
                    basePrice += 500;
                }

                // Add weight factor
                let weightFactor = 0;
                switch (weight) {
                    case '0-1':
                        weightFactor = 0;
                        break;
                    case '1-5':
                        weightFactor = 200;
                        break;
                    case '5-10':
                        weightFactor = 500;
                        break;
                    case '10+':
                        weightFactor = 1000;
                        break;
                }

                // Calculate total
                const subtotal = basePrice + weightFactor;
                const tax = subtotal * 0.16;
                const total = Math.round(subtotal + tax);

                return {
                    base: basePrice,
                    weight: weightFactor,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    weightCategory: weight
                };
            }

            function displayWideQuoteResult(quote) {
                const weightText = getWeightText(quote.weightCategory);
                const deliveryTime = getDeliveryTime($('#wideFromCounty').val(), $('#wideToCounty').val());

                const quoteHTML = `
                    <div class="mb-3">
                        <h5><i class="bi bi-receipt me-2"></i> Your Quote</h5>
                        <p class="text-muted">Estimated delivery: ${deliveryTime}</p>
                    </div>
                    <div class="row align-items-center mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                    <i class="bi bi-box-seam fs-3 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">${$('#wideItemDescription').val()}</h6>
                                    <small class="text-muted">${weightText}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-2 mt-md-0">
                            <span class="badge bg-primary fs-6 py-2 px-3">KES ${quote.total}</span>
                        </div>
                    </div>
                    <div class="bg-white rounded p-3 mb-4">
                        <h6 class="mb-3">Price Breakdown</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Base Delivery Fee</span>
                            <span>KES ${quote.base}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Weight Charge</span>
                            <span>KES ${quote.weight}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span>KES ${quote.subtotal}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">VAT (16%)</span>
                            <span>KES ${Math.round(quote.tax)}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total Estimated Cost</span>
                            <span class="text-primary">KES ${quote.total}</span>
                        </div>
                    </div>
                    <div class="d-grid gap-3">
                        <button class="btn btn-primary btn-lg py-3">
                            <i class="bi bi-cart-check me-2"></i> Book This Delivery Now
                        </button>
                        <button class="btn btn-outline-primary btn-lg py-3" id="closeWideQuote">
                            <i class="bi bi-x-circle me-2"></i> Close Quote
                        </button>
                    </div>
                    <div class="mt-4 text-muted small">
                        <i class="bi bi-info-circle me-1"></i> 
                        This is an estimate. Final price may vary based on actual package dimensions and specific delivery requirements.
                    </div>
                `;

                $('#wideQuoteResult').html(quoteHTML).addClass('show');

                // Scroll to quote result
                $('html, body').animate({
                    scrollTop: $('#wideQuoteResult').offset().top - 100
                }, 500);

                $('#closeWideQuote').on('click', function() {
                    $('#wideQuoteResult').removeClass('show').empty();
                });
            }

            function getWeightText(weightCategory) {
                switch (weightCategory) {
                    case '0-1':
                        return 'Light Package (0-1 kg)';
                    case '1-5':
                        return 'Small Package (1-5 kg)';
                    case '5-10':
                        return 'Medium Package (5-10 kg)';
                    case '10+':
                        return 'Large Package (10+ kg)';
                    default:
                        return '';
                }
            }

            function getDeliveryTime(fromCounty, toCounty) {
                if (fromCounty === toCounty) {
                    return 'Same day within city';
                } else {
                    return '1-2 business days';
                }
            }

            // Tracking functions
            function initTracking() {
                $('#trackPackage').on('click', function() {
                    const trackingNum = $('#trackingNumber').val().trim();

                    if (!trackingNum) {
                        alert('Please enter a tracking number');
                        return;
                    }

                    displayTrackingResult(trackingNum);
                });

                $('.tracking-example').on('click', function() {
                    $('#trackingNumber').val($(this).data('number'));
                });
            }

            function displayTrackingResult(trackingNum) {
                const sampleStatus = [{
                        date: 'Today, 08:30 AM',
                        status: 'Package picked up',
                        location: 'Nairobi Westlands'
                    },
                    {
                        date: 'Today, 12:15 PM',
                        status: 'Arrived at sorting facility',
                        location: 'Nairobi Hub'
                    },
                    {
                        date: 'Today, 02:45 PM',
                        status: 'Departed for destination',
                        location: 'En route to Mombasa'
                    },
                    {
                        date: 'Tomorrow, 10:00 AM',
                        status: 'Arrived at destination hub',
                        location: 'Mombasa Port'
                    },
                    {
                        date: 'Tomorrow, 02:30 PM',
                        status: 'Out for delivery',
                        location: 'Mombasa Island'
                    }
                ];

                let timelineHTML = '';
                sampleStatus.forEach((item, index) => {
                    timelineHTML += `
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    ${index + 1}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">${item.status}</h6>
                                <p class="mb-1 text-muted">${item.date}</p>
                                <small><i class="bi bi-geo-alt me-1"></i> ${item.location}</small>
                            </div>
                        </div>
                    `;
                });

                const resultHTML = `
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-1"><i class="bi bi-box-seam me-2"></i> ${trackingNum}</h5>
                                    <span class="badge bg-warning">In Transit</span>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" id="closeTracking">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            ${timelineHTML}
                            <div class="alert alert-light mt-3">
                                <i class="bi bi-clock me-2"></i>
                                <strong>Estimated Delivery:</strong> Tomorrow by 5:00 PM
                            </div>
                        </div>
                    </div>
                `;

                $('#trackingResult').html(resultHTML).slideDown();

                $('#closeTracking').on('click', function() {
                    $('#trackingResult').slideUp(function() {
                        $(this).empty();
                    });
                });
            }

            // Collapsible stations functions
            function initCollapsibleStations() {
                // Stations data structure
                const stationsData = {
                    nairobi: {
                        name: 'Nairobi County',
                        subcounties: {
                            'Westlands': {
                                towns: [{
                                        name: 'Westlands Hub',
                                        address: 'Westlands Mall, 3rd Floor',
                                        phone: '0700 111 222',
                                        hours: '24/7',
                                        type: 'both'
                                    },
                                    {
                                        name: 'Parklands Station',
                                        address: 'Parklands Road',
                                        phone: '0700 111 333',
                                        hours: 'Weekdays 8AM-6PM',
                                        type: 'both'
                                    },
                                    {
                                        name: 'Lavington Drop-off',
                                        address: 'Lavington Green',
                                        phone: '0700 111 444',
                                        hours: '24/7',
                                        type: 'dropoff'
                                    }
                                ]
                            },
                            'Kasarani': {
                                towns: [{
                                        name: 'Kasarani Main',
                                        address: 'Kasarani Stadium',
                                        phone: '0700 111 555',
                                        hours: 'Weekdays 8AM-8PM',
                                        type: 'both'
                                    },
                                    {
                                        name: 'Mwiki Station',
                                        address: 'Mwiki Shopping Centre',
                                        phone: '0700 111 666',
                                        hours: 'Weekdays 9AM-5PM',
                                        type: 'pickup'
                                    }
                                ]
                            },
                            'Embakasi': {
                                towns: [{
                                    name: 'Embakasi Hub',
                                    address: 'Embakasi Village Market',
                                    phone: '0700 111 777',
                                    hours: '24/7',
                                    type: 'both'
                                }]
                            }
                        }
                    },
                    mombasa: {
                        name: 'Mombasa County',
                        subcounties: {
                            'Mvita': {
                                towns: [{
                                        name: 'Mombasa Port Hub',
                                        address: 'Mombasa Port, Shed 5',
                                        phone: '0700 222 111',
                                        hours: 'Weekdays 7AM-7PM',
                                        type: 'both'
                                    },
                                    {
                                        name: 'Old Town Station',
                                        address: 'Old Town Market',
                                        phone: '0700 222 222',
                                        hours: 'Weekdays 8AM-6PM',
                                        type: 'pickup'
                                    }
                                ]
                            },
                            'Kisauni': {
                                towns: [{
                                        name: 'Kisauni Main',
                                        address: 'Kisauni Road',
                                        phone: '0700 222 333',
                                        hours: 'Weekdays 9AM-5PM',
                                        type: 'both'
                                    },
                                    {
                                        name: 'Bamburi Station',
                                        address: 'Bamburi Beach Road',
                                        phone: '0700 222 444',
                                        hours: 'Weekends 10AM-4PM',
                                        type: 'dropoff'
                                    }
                                ]
                            }
                        }
                    },
                    kisumu: {
                        name: 'Kisumu County',
                        subcounties: {
                            'Kisumu Central': {
                                towns: [{
                                        name: 'Kisumu Lakeside Hub',
                                        address: 'Mega Plaza, Kisumu',
                                        phone: '0700 333 111',
                                        hours: '24/7',
                                        type: 'both'
                                    },
                                    {
                                        name: 'CBD Main Station',
                                        address: 'Oginga Odinga Street',
                                        phone: '0700 333 222',
                                        hours: 'Weekdays 8AM-6PM',
                                        type: 'both'
                                    }
                                ]
                            },
                            'Nyando': {
                                towns: [{
                                    name: 'Ahero Station',
                                    address: 'Ahero Town Centre',
                                    phone: '0700 333 333',
                                    hours: 'Weekdays 9AM-5PM',
                                    type: 'pickup'
                                }]
                            }
                        }
                    }
                };

                // Render stations
                function renderStations() {
                    let stationsHTML = '';

                    Object.entries(stationsData).forEach(([countyKey, countyData]) => {
                        stationsHTML += `
                            <div class="station-county">
                                <div class="county-header">
                                    <h5>
                                        <i class="bi bi-geo-alt"></i>
                                        ${countyData.name}
                                        <span class="badge bg-light text-dark ms-2">
                                            ${Object.keys(countyData.subcounties).length} subcounties
                                        </span>
                                    </h5>
                                    <i class="bi bi-chevron-down"></i>
                                </div>
                                <div class="subcounty-list">
                        `;

                        Object.entries(countyData.subcounties).forEach(([subcountyName, subcountyData]) => {
                            stationsHTML += `
                                <div class="subcounty-item">
                                    <div class="subcounty-header">
                                        <h6 class="mb-0">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            ${subcountyName}
                                            <span class="badge bg-light text-dark ms-2">
                                                ${subcountyData.towns.length} towns
                                            </span>
                                        </h6>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="town-list">
                            `;

                            subcountyData.towns.forEach(town => {
                                const typeIcon = town.type === 'both' ?
                                    'bi-arrow-left-right' :
                                    town.type === 'pickup' ? 'bi-arrow-up' :
                                    'bi-arrow-down';
                                const typeText = town.type === 'both' ?
                                    'Pick-up & Drop-off' :
                                    town.type === 'pickup' ? 'Pick-up Only' :
                                    'Drop-off Only';

                                stationsHTML += `
                                    <div class="town-item">
                                        <div class="town-info">
                                            <h6 class="mb-1">${town.name}</h6>
                                            <p class="mb-1">
                                                <i class="bi bi-geo-alt"></i> ${town.address}<br>
                                                <i class="bi bi-telephone"></i> ${town.phone}<br>
                                                <i class="bi bi-clock"></i> ${town.hours}
                                            </p>
                                        </div>
                                        <span class="station-badge">
                                            <i class="bi ${typeIcon}"></i> ${typeText}
                                        </span>
                                    </div>
                                `;
                            });

                            stationsHTML += `
                                    </div>
                                </div>
                            `;
                        });

                        stationsHTML += `
                                </div>
                            </div>
                        `;
                    });

                    $('#collapsibleStations').html(stationsHTML);

                    // Add click handlers
                    $('.county-header').on('click', function() {
                        const stationCounty = $(this).closest('.station-county');
                        stationCounty.toggleClass('active');

                        // Close other counties
                        $('.station-county').not(stationCounty).removeClass('active');
                    });

                    $('.subcounty-header').on('click', function(e) {
                        e.stopPropagation();
                        $(this).closest('.subcounty-item').toggleClass('active');
                    });
                }

                renderStations();
            }

            // FAQ functions
            function initFAQ() {
                $('.faq-question').on('click', function() {
                    const faqItem = $(this).closest('.faq-item');
                    const icon = $(this).find('i');

                    // Close all other items
                    $('.faq-item').not(faqItem).removeClass('active').find('i').removeClass('bi-chevron-up')
                        .addClass('bi-chevron-down');

                    // Toggle current item
                    faqItem.toggleClass('active');

                    // Change icon
                    if (faqItem.hasClass('active')) {
                        icon.removeClass('bi-chevron-down').addClass('bi-chevron-up');
                    } else {
                        icon.removeClass('bi-chevron-up').addClass('bi-chevron-down');
                    }
                });
            }
        });
    </script>

    <script>
        // Ensure Bootstrap is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing dropdowns...');

            // Initialize all dropdowns
            const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            const dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });

            console.log('Dropdowns initialized:', dropdownList.length);

            // Handle feedback form submission
            window.submitFeedback = function() {
                const feedbackForm = document.getElementById('feedbackForm');
                if (feedbackForm) {
                    // Get form data
                    const type = document.getElementById('feedbackType').value;
                    const subject = document.getElementById('feedbackSubject').value;
                    const message = document.getElementById('feedbackMessage').value;
                    const priority = document.querySelector('input[name="priority"]:checked').value;

                    if (!type || !subject || !message) {
                        alert('Please fill in all required fields');
                        return;
                    }

                    const formData = {
                        type: type,
                        subject: subject,
                        message: message,
                        priority: priority
                    };

                    console.log('Feedback submitted:', formData);

                    // Close modal
                    const feedbackModal = bootstrap.Modal.getInstance(document.getElementById('feedbackModal'));
                    if (feedbackModal) {
                        feedbackModal.hide();
                    }

                    // Show success message
                    showToast('Feedback submitted successfully!', 'success');

                    // Reset form
                    feedbackForm.reset();
                }
            };

            // Mark notifications as read on click
            document.querySelectorAll('.notification-item.new').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.remove('new');
                    updateNotificationBadge();
                });
            });

            // Mark messages as read on click
            document.querySelectorAll('.message-item.unread').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.remove('unread');
                    updateMessageBadge();
                });
            });

            function updateNotificationBadge() {
                const newNotifications = document.querySelectorAll('.notification-item.new').length;
                const badge = document.querySelector('.notification-dropdown .icon-badge');
                const headerBadge = document.querySelector('.notification-dropdown .dropdown-header .badge');

                if (badge) {
                    badge.textContent = newNotifications;
                    if (newNotifications === 0) {
                        badge.style.display = 'none';
                    } else {
                        badge.style.display = 'flex';
                    }
                }

                if (headerBadge) {
                    headerBadge.textContent = newNotifications + ' New';
                }
            }

            function updateMessageBadge() {
                const unreadMessages = document.querySelectorAll('.message-item.unread').length;
                const badge = document.querySelector('.message-dropdown .icon-badge');
                const headerBadge = document.querySelector('.message-dropdown .dropdown-header .badge');

                if (badge) {
                    badge.textContent = unreadMessages;
                    if (unreadMessages === 0) {
                        badge.style.display = 'none';
                    } else {
                        badge.style.display = 'flex';
                    }
                }

                if (headerBadge) {
                    headerBadge.textContent = unreadMessages + ' Unread';
                }
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                // Create toast container if it doesn't exist
                let toastContainer = document.querySelector('.toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                    document.body.appendChild(toastContainer);
                }

                const toastId = 'toast-' + Date.now();
                const toast = document.createElement('div');
                toast.className =
                    `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
                toast.id = toastId;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');

                toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

                toastContainer.appendChild(toast);
                const bsToast = new bootstrap.Toast(toast, {
                    delay: 3000
                });
                bsToast.show();

                toast.addEventListener('hidden.bs.toast', function() {
                    toast.remove();
                });
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                const isDropdown = event.target.closest('.dropdown') ||
                    event.target.closest('.dropdown-toggle') ||
                    event.target.closest('.dropdown-menu');

                if (!isDropdown) {
                    // Close all open dropdowns
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(dropdown => {
                        const parent = dropdown.closest('.dropdown');
                        if (parent) {
                            const toggle = parent.querySelector('.dropdown-toggle');
                            if (toggle && bootstrap.Dropdown.getInstance(toggle)) {
                                bootstrap.Dropdown.getInstance(toggle).hide();
                            }
                        }
                    });
                }
            });

            // Prevent dropdown from closing when clicking inside
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
        });
    </script>

        <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                // Core editing features
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                // Your account includes a free trial of TinyMCE premium features
                // Try the most popular premium features until Mar 27, 2026:
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [{
                    value: 'First.Name',
                    title: 'First Name'
                },
                {
                    value: 'Email',
                    title: 'Email'
                },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            uploadcare_public_key: 'ed7312cf0958d607cbc6',
        });
    </script>


</body>

</html>