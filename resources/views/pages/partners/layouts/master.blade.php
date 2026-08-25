<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Karibu Parcels - Professional Courier Service</title>

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    >

    <!-- Favicon -->
    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('favicon.jpeg') }}"
    >

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('frontend/frontend.css') }}"
    >

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- TinyMCE -->
    <script
        src="https://cdn.tiny.cloud/1/3culyhhybbcchz5f5d6o066dedtcc2ugjb92n22l8ocyw9rv/tinymce/8/tinymce.min.js"
        referrerpolicy="origin"
        crossorigin="anonymous"
    ></script>
</head>

<body>

    @yield('partner-content')


    <!-- Bootstrap 5 JS Bundle -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
    </script>


    <script>
        $(document).ready(function () {

            /*
            |--------------------------------------------------------------------------
            | Location Data
            |--------------------------------------------------------------------------
            */

            const locationData = {

                nairobi: {
                    subcounties: [
                        'Westlands',
                        'Dagoretti',
                        'Langata',
                        'Kasarani',
                        'Embakasi',
                        'Starehe'
                    ],

                    towns: {
                        Westlands: [
                            'Westlands CBD',
                            'Parklands',
                            'Lavington',
                            'Kileleshwa'
                        ],

                        Dagoretti: [
                            'Dagoretti North',
                            'Dagoretti South',
                            'Uthiru'
                        ],

                        Langata: [
                            'Karen',
                            'Langata',
                            'South C'
                        ],

                        Kasarani: [
                            'Kasarani',
                            'Mwiki',
                            'Clay City'
                        ],

                        Embakasi: [
                            'Embakasi Central',
                            'Embakasi East',
                            'Embakasi West'
                        ],

                        Starehe: [
                            'CBD',
                            'Nairobi West',
                            'Buruburu'
                        ]
                    }
                },

                mombasa: {
                    subcounties: [
                        'Mvita',
                        'Changamwe',
                        'Kisauni',
                        'Likoni',
                        'Jomvu'
                    ],

                    towns: {
                        Mvita: [
                            'Mombasa Island',
                            'Old Town',
                            'Tudor'
                        ],

                        Changamwe: [
                            'Changamwe',
                            'Airport',
                            'Miritini'
                        ],

                        Kisauni: [
                            'Kisauni',
                            'Bamburi',
                            'Mkomani'
                        ],

                        Likoni: [
                            'Likoni',
                            'Mtongwe',
                            'Shelly Beach'
                        ],

                        Jomvu: [
                            'Jomvu Kuu',
                            'Mikindani'
                        ]
                    }
                },

                kisumu: {
                    subcounties: [
                        'Kisumu Central',
                        'Kisumu East',
                        'Kisumu West',
                        'Seme',
                        'Nyando'
                    ],

                    towns: {
                        'Kisumu Central': [
                            'Kisumu CBD',
                            'Milimani',
                            'Nyalenda'
                        ],

                        'Kisumu East': [
                            'Kondele',
                            'Manyatta',
                            'Nyamasaria'
                        ],

                        'Kisumu West': [
                            'Kiboswa',
                            'Maseno',
                            'Ojolla'
                        ],

                        Seme: [
                            'Seme',
                            'Awasi'
                        ],

                        Nyando: [
                            'Ahero',
                            'Kaduong',
                            'Kobura'
                        ]
                    }
                },

                nakuru: {
                    subcounties: [
                        'Nakuru Town East',
                        'Nakuru Town West',
                        'Naivasha',
                        'Gilgil',
                        'Molo'
                    ],

                    towns: {
                        'Nakuru Town East': [
                            'Nakuru CBD',
                            'Milimani',
                            'Kiamunyi'
                        ],

                        'Nakuru Town West': [
                            'Lanet',
                            'Barut',
                            'Kaptembwo'
                        ],

                        Naivasha: [
                            'Naivasha Town',
                            'Karai',
                            'Kinungi'
                        ],

                        Gilgil: [
                            'Gilgil Town',
                            'Mbaruk',
                            'Elementaita'
                        ],

                        Molo: [
                            'Molo Town',
                            'Elburgon',
                            'Turi'
                        ]
                    }
                },

                eldoret: {
                    subcounties: [
                        'Kapseret',
                        'Kesses',
                        'Soy',
                        'Turbo',
                        'Ainabkoi'
                    ],

                    towns: {
                        Kapseret: [
                            'Eldoret Town',
                            'Kipkenyo',
                            'Kapsaret'
                        ],

                        Kesses: [
                            'Kesses',
                            'Chebororwa',
                            'Tulwet'
                        ],

                        Soy: [
                            'Soy',
                            'Kapsowar',
                            'Kapsimotwo'
                        ],

                        Turbo: [
                            'Turbo',
                            'Kamagut',
                            'Ngeria'
                        ],

                        Ainabkoi: [
                            'Ainabkoi',
                            'Kapsoya',
                            'Kimumu'
                        ]
                    }
                }

            };


            /*
            |--------------------------------------------------------------------------
            | Initialize
            |--------------------------------------------------------------------------
            */

            initBookingForm();
            initTracking();
            initCollapsibleStations();
            initFAQ();


            /*
            |--------------------------------------------------------------------------
            | Contact Form
            |--------------------------------------------------------------------------
            */

            const contactForm = $('#contactForm');

            if (contactForm.length) {

                contactForm.on('submit', function (e) {

                    e.preventDefault();

                    alert(
                        'Thank you for your message! We will get back to you shortly.'
                    );

                    this.reset();

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Smooth Scroll
            |--------------------------------------------------------------------------
            */

            $('a[href^="#"]').on('click', function (e) {

                const targetId = $(this).attr('href');

                if (!targetId || targetId === '#') {
                    return;
                }

                const targetElement = $(targetId);

                if (!targetElement.length) {
                    return;
                }

                e.preventDefault();

                $('html, body').animate({
                    scrollTop: targetElement.offset().top - 80
                }, 500);

            });


            /*
            |--------------------------------------------------------------------------
            | Booking Form
            |--------------------------------------------------------------------------
            */

            function initBookingForm() {

                const fromCounty = $('#wideFromCounty');
                const fromSubcounty = $('#wideFromSubcounty');
                const fromTown = $('#wideFromTown');

                const toCounty = $('#wideToCounty');
                const toSubcounty = $('#wideToSubcounty');
                const toTown = $('#wideToTown');


                if (fromCounty.length) {

                    fromCounty.on('change', function () {

                        updateLocationOptions(
                            $(this),
                            fromSubcounty,
                            fromTown
                        );

                    });

                }


                if (fromSubcounty.length) {

                    fromSubcounty.on('change', function () {

                        updateTownOptions(
                            fromCounty.val(),
                            $(this).val(),
                            fromTown
                        );

                    });

                }


                if (toCounty.length) {

                    toCounty.on('change', function () {

                        updateLocationOptions(
                            $(this),
                            toSubcounty,
                            toTown
                        );

                    });

                }


                if (toSubcounty.length) {

                    toSubcounty.on('change', function () {

                        updateTownOptions(
                            toCounty.val(),
                            $(this).val(),
                            toTown
                        );

                    });

                }


                $('.weight-option').on('click', function () {

                    $('.weight-option')
                        .removeClass('btn-primary')
                        .addClass('btn-outline-primary');

                    $(this)
                        .removeClass('btn-outline-primary')
                        .addClass('btn-primary');

                    $('#wideItemWeight').val(
                        $(this).data('weight')
                    );

                });


                const quoteForm = $('#wideQuoteForm');

                if (quoteForm.length) {

                    quoteForm.on('submit', function (e) {

                        e.preventDefault();

                        if (!validateWideForm()) {
                            return;
                        }

                        const quote = calculateWideQuote();

                        displayWideQuoteResult(quote);

                    });

                }


                const clearButton = $('#wideClearForm');

                if (clearButton.length) {

                    clearButton.on('click', function () {

                        if (quoteForm.length && quoteForm[0]) {
                            quoteForm[0].reset();
                        }

                        $('.weight-option')
                            .removeClass('btn-primary')
                            .addClass('btn-outline-primary');

                        $('#wideItemWeight').val('');

                        $('#wideQuoteResult')
                            .removeClass('show')
                            .empty();

                        $('[id$="Subcounty"], [id$="Town"]')
                            .each(function () {

                                const isTown = $(this)
                                    .attr('id')
                                    .includes('Town');

                                $(this).html(
                                    '<option value="">Select ' +
                                    (isTown ? 'Town' : 'Subcounty') +
                                    '</option>'
                                );

                                $(this).prop(
                                    'disabled',
                                    true
                                );

                            });

                    });

                }

            }


            function updateLocationOptions(
                countySelect,
                subcountySelect,
                townSelect
            ) {

                const county = countySelect.val();

                if (!county) {

                    subcountySelect.html(
                        '<option value="">Select Subcounty</option>'
                    );

                    subcountySelect.prop(
                        'disabled',
                        true
                    );

                    townSelect.html(
                        '<option value="">Select Town</option>'
                    );

                    townSelect.prop(
                        'disabled',
                        true
                    );

                    return;

                }


                const subcounties =
                    locationData[county]?.subcounties || [];


                let options =
                    '<option value="">Select Subcounty</option>';


                $.each(
                    subcounties,
                    function (index, subcounty) {

                        options +=
                            `<option value="${subcounty}">${subcounty}</option>`;

                    }
                );


                subcountySelect.html(options);

                subcountySelect.prop(
                    'disabled',
                    false
                );


                townSelect.html(
                    '<option value="">Select Town</option>'
                );

                townSelect.prop(
                    'disabled',
                    true
                );

            }


            function updateTownOptions(
                county,
                subcounty,
                townSelect
            ) {

                if (!subcounty) {

                    townSelect.html(
                        '<option value="">Select Town</option>'
                    );

                    townSelect.prop(
                        'disabled',
                        true
                    );

                    return;

                }


                const towns =
                    locationData[county]?.towns?.[subcounty] || [];


                let options =
                    '<option value="">Select Town</option>';


                $.each(
                    towns,
                    function (index, town) {

                        options +=
                            `<option value="${town}">${town}</option>`;

                    }
                );


                townSelect.html(options);

                townSelect.prop(
                    'disabled',
                    false
                );

            }


            function validateWideForm() {

                const required = [
                    'wideFromCounty',
                    'wideFromSubcounty',
                    'wideFromTown',
                    'wideToCounty',
                    'wideToSubcounty',
                    'wideToTown'
                ];


                const itemWeight =
                    $('#wideItemWeight').val();


                const itemDescription =
                    $('#wideItemDescription').val()?.trim() || '';


                for (const field of required) {

                    const element =
                        $('#' + field);

                    if (!element.length) {
                        continue;
                    }

                    const value =
                        element.val();

                    if (!value) {

                        showAlert(
                            'Please fill in all required fields',
                            'warning'
                        );

                        element.focus();

                        return false;

                    }

                }


                if (!itemWeight) {

                    showAlert(
                        'Please select package weight',
                        'warning'
                    );

                    return false;

                }


                if (!itemDescription) {

                    showAlert(
                        'Please describe your item',
                        'warning'
                    );

                    $('#wideItemDescription').focus();

                    return false;

                }


                if (
                    $('#wideFromCounty').val() ===
                    $('#wideToCounty').val()
                    &&
                    $('#wideFromSubcounty').val() ===
                    $('#wideToSubcounty').val()
                    &&
                    $('#wideFromTown').val() ===
                    $('#wideToTown').val()
                ) {

                    showAlert(
                        'Pickup and delivery locations cannot be the same',
                        'warning'
                    );

                    return false;

                }


                return true;

            }


            function showAlert(
                message,
                type
            ) {

                const quoteForm =
                    $('#wideQuoteForm');

                if (!quoteForm.length) {
                    return;
                }


                const alertClass =
                    type === 'warning'
                        ? 'alert-warning'
                        : 'alert-danger';


                const alert = $(`
                    <div
                        class="alert ${alertClass} alert-dismissible fade show"
                        role="alert"
                    >

                        <i
                            class="bi bi-exclamation-triangle me-2"
                        ></i>

                        ${message}

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                        ></button>

                    </div>
                `);


                quoteForm.prepend(alert);


                setTimeout(function () {

                    alert.alert('close');

                }, 5000);

            }


            function calculateWideQuote() {

                const fromCounty =
                    $('#wideFromCounty').val();

                const toCounty =
                    $('#wideToCounty').val();

                const weight =
                    $('#wideItemWeight').val();


                let basePrice = 300;


                if (
                    fromCounty !== toCounty
                ) {

                    basePrice += 500;

                }


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


                const subtotal =
                    basePrice + weightFactor;


                const tax =
                    subtotal * 0.16;


                const total =
                    Math.round(
                        subtotal + tax
                    );


                return {
                    base: basePrice,
                    weight: weightFactor,
                    subtotal: subtotal,
                    tax: tax,
                    total: total,
                    weightCategory: weight
                };

            }


            function displayWideQuoteResult(
                quote
            ) {

                const result =
                    $('#wideQuoteResult');

                if (!result.length) {
                    return;
                }


                const weightText =
                    getWeightText(
                        quote.weightCategory
                    );


                const deliveryTime =
                    getDeliveryTime(
                        $('#wideFromCounty').val(),
                        $('#wideToCounty').val()
                    );


                const description =
                    $('#wideItemDescription').val() || 'Parcel';


                const quoteHTML = `

                    <div class="mb-3">

                        <h5>
                            <i class="bi bi-receipt me-2"></i>
                            Your Quote
                        </h5>

                        <p class="text-muted">
                            Estimated delivery:
                            ${deliveryTime}
                        </p>

                    </div>


                    <div class="row align-items-center mb-4">

                        <div class="col-md-8">

                            <div class="d-flex align-items-center">

                                <div
                                    class="rounded-circle bg-primary bg-opacity-10 p-3 me-3"
                                >

                                    <i
                                        class="bi bi-box-seam fs-3 text-primary"
                                    ></i>

                                </div>


                                <div>

                                    <h6 class="mb-1">
                                        ${description}
                                    </h6>

                                    <small class="text-muted">
                                        ${weightText}
                                    </small>

                                </div>

                            </div>

                        </div>


                        <div
                            class="col-md-4 text-md-end mt-2 mt-md-0"
                        >

                            <span
                                class="badge bg-primary fs-6 py-2 px-3"
                            >
                                KES ${quote.total}
                            </span>

                        </div>

                    </div>


                    <div
                        class="bg-white rounded p-3 mb-4"
                    >

                        <h6 class="mb-3">
                            Price Breakdown
                        </h6>


                        <div
                            class="d-flex justify-content-between mb-2"
                        >

                            <span class="text-muted">
                                Base Delivery Fee
                            </span>

                            <span>
                                KES ${quote.base}
                            </span>

                        </div>


                        <div
                            class="d-flex justify-content-between mb-2"
                        >

                            <span class="text-muted">
                                Weight Charge
                            </span>

                            <span>
                                KES ${quote.weight}
                            </span>

                        </div>


                        <div
                            class="d-flex justify-content-between mb-2"
                        >

                            <span class="text-muted">
                                Subtotal
                            </span>

                            <span>
                                KES ${quote.subtotal}
                            </span>

                        </div>


                        <div
                            class="d-flex justify-content-between mb-3"
                        >

                            <span class="text-muted">
                                VAT (16%)
                            </span>

                            <span>
                                KES ${Math.round(quote.tax)}
                            </span>

                        </div>


                        <hr>


                        <div
                            class="d-flex justify-content-between fw-bold fs-5"
                        >

                            <span>
                                Total Estimated Cost
                            </span>

                            <span class="text-primary">
                                KES ${quote.total}
                            </span>

                        </div>

                    </div>


                    <div class="d-grid gap-3">

                        <button
                            type="button"
                            class="btn btn-primary btn-lg py-3"
                        >

                            <i
                                class="bi bi-cart-check me-2"
                            ></i>

                            Book This Delivery Now

                        </button>


                        <button
                            type="button"
                            class="btn btn-outline-primary btn-lg py-3"
                            id="closeWideQuote"
                        >

                            <i
                                class="bi bi-x-circle me-2"
                            ></i>

                            Close Quote

                        </button>

                    </div>

                `;


                result
                    .html(quoteHTML)
                    .addClass('show');


                const offset =
                    result.offset();

                if (offset) {

                    $('html, body').animate({
                        scrollTop: offset.top - 100
                    }, 500);

                }


                $('#closeWideQuote').on(
                    'click',
                    function () {

                        result
                            .removeClass('show')
                            .empty();

                    }
                );

            }


            function getWeightText(
                weightCategory
            ) {

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


            function getDeliveryTime(
                fromCounty,
                toCounty
            ) {

                if (
                    fromCounty === toCounty
                ) {

                    return 'Same day within city';

                }


                return '1-2 business days';

            }


            /*
            |--------------------------------------------------------------------------
            | Tracking
            |--------------------------------------------------------------------------
            */

            function initTracking() {

                const trackButton =
                    $('#trackPackage');


                if (!trackButton.length) {
                    return;
                }


                trackButton.on(
                    'click',
                    function () {

                        const input =
                            $('#trackingNumber');


                        if (!input.length) {
                            return;
                        }


                        const trackingNum =
                            input.val()?.trim();


                        if (!trackingNum) {

                            alert(
                                'Please enter a tracking number'
                            );

                            return;

                        }


                        displayTrackingResult(
                            trackingNum
                        );

                    }
                );


                $('.tracking-example')
                    .on(
                        'click',
                        function () {

                            const input =
                                $('#trackingNumber');


                            if (!input.length) {
                                return;
                            }


                            input.val(
                                $(this).data('number')
                            );

                        }
                    );

            }


            function displayTrackingResult(
                trackingNum
            ) {

                const trackingResult =
                    $('#trackingResult');


                if (!trackingResult.length) {
                    return;
                }


                const sampleStatus = [

                    {
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


                sampleStatus.forEach(
                    function (item, index) {

                        timelineHTML += `

                            <div class="d-flex mb-3">

                                <div class="flex-shrink-0">

                                    <div
                                        class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;"
                                    >

                                        ${index + 1}

                                    </div>

                                </div>


                                <div class="flex-grow-1 ms-3">

                                    <h6 class="mb-1">
                                        ${item.status}
                                    </h6>

                                    <p class="mb-1 text-muted">
                                        ${item.date}
                                    </p>

                                    <small>

                                        <i class="bi bi-geo-alt me-1"></i>

                                        ${item.location}

                                    </small>

                                </div>

                            </div>

                        `;

                    }
                );


                const resultHTML = `

                    <div class="card mt-4">

                        <div class="card-body">

                            <div
                                class="d-flex justify-content-between align-items-center mb-4"
                            >

                                <div>

                                    <h5 class="mb-1">

                                        <i
                                            class="bi bi-box-seam me-2"
                                        ></i>

                                        ${trackingNum}

                                    </h5>

                                    <span
                                        class="badge bg-warning"
                                    >
                                        In Transit
                                    </span>

                                </div>


                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-secondary"
                                    id="closeTracking"
                                >

                                    <i class="bi bi-x"></i>

                                </button>

                            </div>


                            ${timelineHTML}


                            <div
                                class="alert alert-light mt-3"
                            >

                                <i
                                    class="bi bi-clock me-2"
                                ></i>

                                <strong>
                                    Estimated Delivery:
                                </strong>

                                Tomorrow by 5:00 PM

                            </div>

                        </div>

                    </div>

                `;


                trackingResult
                    .html(resultHTML)
                    .slideDown();


                $('#closeTracking')
                    .on(
                        'click',
                        function () {

                            trackingResult.slideUp(
                                function () {

                                    $(this).empty();

                                }
                            );

                        }
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Stations
            |--------------------------------------------------------------------------
            */

            function initCollapsibleStations() {

                const container =
                    $('#collapsibleStations');


                if (!container.length) {
                    return;
                }


                const stationsData = {

                    nairobi: {

                        name: 'Nairobi County',

                        subcounties: {

                            Westlands: {

                                towns: [

                                    {
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


                            Kasarani: {

                                towns: [

                                    {
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


                            Embakasi: {

                                towns: [

                                    {
                                        name: 'Embakasi Hub',
                                        address: 'Embakasi Village Market',
                                        phone: '0700 111 777',
                                        hours: '24/7',
                                        type: 'both'
                                    }

                                ]

                            }

                        }

                    },


                    mombasa: {

                        name: 'Mombasa County',

                        subcounties: {

                            Mvita: {

                                towns: [

                                    {
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


                            Kisauni: {

                                towns: [

                                    {
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

                                towns: [

                                    {
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


                            Nyando: {

                                towns: [

                                    {
                                        name: 'Ahero Station',
                                        address: 'Ahero Town Centre',
                                        phone: '0700 333 333',
                                        hours: 'Weekdays 9AM-5PM',
                                        type: 'pickup'
                                    }

                                ]

                            }

                        }

                    }

                };


                let stationsHTML = '';


                Object.entries(stationsData)
                    .forEach(
                        function ([countyKey, countyData]) {

                            stationsHTML += `

                                <div class="station-county">

                                    <div class="county-header">

                                        <h5>

                                            <i
                                                class="bi bi-geo-alt"
                                            ></i>

                                            ${countyData.name}

                                            <span
                                                class="badge bg-light text-dark ms-2"
                                            >

                                                ${
                                                    Object.keys(
                                                        countyData.subcounties
                                                    ).length
                                                }

                                                subcounties

                                            </span>

                                        </h5>


                                        <i
                                            class="bi bi-chevron-down"
                                        ></i>

                                    </div>


                                    <div class="subcounty-list">

                            `;


                            Object.entries(
                                countyData.subcounties
                            )
                            .forEach(
                                function (
                                    [
                                        subcountyName,
                                        subcountyData
                                    ]
                                ) {

                                    stationsHTML += `

                                        <div class="subcounty-item">

                                            <div class="subcounty-header">

                                                <h6 class="mb-0">

                                                    <i
                                                        class="bi bi-geo-alt-fill"
                                                    ></i>

                                                    ${subcountyName}

                                                    <span
                                                        class="badge bg-light text-dark ms-2"
                                                    >

                                                        ${
                                                            subcountyData
                                                                .towns
                                                                .length
                                                        }

                                                        towns

                                                    </span>

                                                </h6>


                                                <i
                                                    class="bi bi-chevron-down"
                                                ></i>

                                            </div>


                                            <div class="town-list">

                                    `;


                                    subcountyData.towns
                                        .forEach(
                                            function (town) {

                                                const typeIcon =
                                                    town.type === 'both'
                                                        ? 'bi-arrow-left-right'
                                                        : town.type === 'pickup'
                                                            ? 'bi-arrow-up'
                                                            : 'bi-arrow-down';


                                                const typeText =
                                                    town.type === 'both'
                                                        ? 'Pick-up & Drop-off'
                                                        : town.type === 'pickup'
                                                            ? 'Pick-up Only'
                                                            : 'Drop-off Only';


                                                stationsHTML += `

                                                    <div class="town-item">

                                                        <div class="town-info">

                                                            <h6 class="mb-1">
                                                                ${town.name}
                                                            </h6>

                                                            <p class="mb-1">

                                                                <i
                                                                    class="bi bi-geo-alt"
                                                                ></i>

                                                                ${town.address}

                                                                <br>

                                                                <i
                                                                    class="bi bi-telephone"
                                                                ></i>

                                                                ${town.phone}

                                                                <br>

                                                                <i
                                                                    class="bi bi-clock"
                                                                ></i>

                                                                ${town.hours}

                                                            </p>

                                                        </div>


                                                        <span
                                                            class="station-badge"
                                                        >

                                                            <i
                                                                class="bi ${typeIcon}"
                                                            ></i>

                                                            ${typeText}

                                                        </span>

                                                    </div>

                                                `;

                                            }
                                        );


                                    stationsHTML += `

                                            </div>

                                        </div>

                                    `;

                                }
                            );


                            stationsHTML += `

                                    </div>

                                </div>

                            `;

                        }
                    );


                container.html(
                    stationsHTML
                );


                $('.county-header')
                    .on(
                        'click',
                        function () {

                            const stationCounty =
                                $(this)
                                    .closest(
                                        '.station-county'
                                    );


                            stationCounty
                                .toggleClass(
                                    'active'
                                );


                            $('.station-county')
                                .not(stationCounty)
                                .removeClass(
                                    'active'
                                );

                        }
                    );


                $('.subcounty-header')
                    .on(
                        'click',
                        function (e) {

                            e.stopPropagation();

                            $(this)
                                .closest(
                                    '.subcounty-item'
                                )
                                .toggleClass(
                                    'active'
                                );

                        }
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | FAQ
            |--------------------------------------------------------------------------
            */

            function initFAQ() {

                const questions =
                    $('.faq-question');


                if (!questions.length) {
                    return;
                }


                questions.on(
                    'click',
                    function () {

                        const faqItem =
                            $(this)
                                .closest(
                                    '.faq-item'
                                );


                        const icon =
                            $(this)
                                .find('i');


                        $('.faq-item')
                            .not(faqItem)
                            .removeClass('active')
                            .find('i')
                            .removeClass(
                                'bi-chevron-up'
                            )
                            .addClass(
                                'bi-chevron-down'
                            );


                        faqItem
                            .toggleClass(
                                'active'
                            );


                        if (
                            faqItem.hasClass(
                                'active'
                            )
                        ) {

                            icon
                                .removeClass(
                                    'bi-chevron-down'
                                )
                                .addClass(
                                    'bi-chevron-up'
                                );

                        } else {

                            icon
                                .removeClass(
                                    'bi-chevron-up'
                                )
                                .addClass(
                                    'bi-chevron-down'
                                );

                        }

                    }
                );

            }

        });
    </script>


    <script>
        /*
        |--------------------------------------------------------------------------
        | Profile Dropdown
        |--------------------------------------------------------------------------
        |
        | Bootstrap handles opening, closing, keyboard navigation and clicking
        | outside automatically.
        |
        */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const profileDropdown =
                    document.getElementById(
                        'profileDropdown'
                    );


                if (
                    profileDropdown &&
                    typeof bootstrap !== 'undefined'
                ) {

                    bootstrap.Dropdown
                        .getOrCreateInstance(
                            profileDropdown
                        );

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Feedback
        |--------------------------------------------------------------------------
        */

        window.submitFeedback =
            function () {

                const feedbackForm =
                    document.getElementById(
                        'feedbackForm'
                    );


                if (!feedbackForm) {
                    return;
                }


                const typeElement =
                    document.getElementById(
                        'feedbackType'
                    );


                const subjectElement =
                    document.getElementById(
                        'feedbackSubject'
                    );


                const messageElement =
                    document.getElementById(
                        'feedbackMessage'
                    );


                const priorityElement =
                    document.querySelector(
                        'input[name="priority"]:checked'
                    );


                const type =
                    typeElement?.value || '';


                const subject =
                    subjectElement?.value || '';


                const message =
                    messageElement?.value || '';


                const priority =
                    priorityElement?.value || 'low';


                if (
                    !type ||
                    !subject ||
                    !message
                ) {

                    alert(
                        'Please fill in all required fields'
                    );

                    return;

                }


                console.log(
                    'Feedback submitted:',
                    {
                        type: type,
                        subject: subject,
                        message: message,
                        priority: priority
                    }
                );


                const modalElement =
                    document.getElementById(
                        'feedbackModal'
                    );


                if (
                    modalElement &&
                    typeof bootstrap !== 'undefined'
                ) {

                    const modal =
                        bootstrap.Modal
                            .getOrCreateInstance(
                                modalElement
                            );


                    modal.hide();

                }


                feedbackForm.reset();


                showToast(
                    'Feedback submitted successfully!',
                    'success'
                );

            };


        /*
        |--------------------------------------------------------------------------
        | Toast
        |--------------------------------------------------------------------------
        */

        function showToast(
            message,
            type = 'success'
        ) {

            let toastContainer =
                document.querySelector(
                    '.toast-container'
                );


            if (!toastContainer) {

                toastContainer =
                    document.createElement(
                        'div'
                    );


                toastContainer.className =
                    'toast-container position-fixed bottom-0 end-0 p-3';


                toastContainer.style.zIndex =
                    '9999';


                document.body.appendChild(
                    toastContainer
                );

            }


            const toast =
                document.createElement(
                    'div'
                );


            toast.className =
                `toast align-items-center text-white ${
                    type === 'success'
                        ? 'bg-success'
                        : 'bg-danger'
                } border-0`;


            toast.setAttribute(
                'role',
                'alert'
            );


            toast.setAttribute(
                'aria-live',
                'assertive'
            );


            toast.setAttribute(
                'aria-atomic',
                'true'
            );


            toast.innerHTML = `

                <div class="d-flex">

                    <div class="toast-body">

                        <i
                            class="bi ${
                                type === 'success'
                                    ? 'bi-check-circle'
                                    : 'bi-exclamation-triangle'
                            } me-2"
                        ></i>

                        ${message}

                    </div>


                    <button
                        type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"
                    ></button>

                </div>

            `;


            toastContainer.appendChild(
                toast
            );


            if (
                typeof bootstrap === 'undefined'
            ) {

                console.error(
                    'Bootstrap JavaScript is not loaded.'
                );

                return;

            }


            const bsToast =
                bootstrap.Toast
                    .getOrCreateInstance(
                        toast,
                        {
                            delay: 3000
                        }
                    );


            bsToast.show();


            toast.addEventListener(
                'hidden.bs.toast',
                function () {

                    toast.remove();

                }
            );

        }
    </script>


    <script>
        /*
        |--------------------------------------------------------------------------
        | TinyMCE
        |--------------------------------------------------------------------------
        |
        | Only initialize TinyMCE when matching textareas exist.
        |
        */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const textareas =
                    document.querySelectorAll(
                        'textarea'
                    );


                if (
                    !textareas.length ||
                    typeof tinymce === 'undefined'
                ) {

                    return;

                }


                tinymce.init({

                    selector: 'textarea',

                    plugins: [
                        'anchor',
                        'autolink',
                        'charmap',
                        'codesample',
                        'emoticons',
                        'link',
                        'lists',
                        'media',
                        'searchreplace',
                        'table',
                        'visualblocks',
                        'wordcount'
                    ],

                    toolbar:
                        'undo redo | blocks | bold italic underline | link media table | alignleft aligncenter alignright | numlist bullist | removeformat',

                    menubar: false,

                    branding: false

                });

            }
        );
    </script>


    @stack('scripts')

</body>
</html>