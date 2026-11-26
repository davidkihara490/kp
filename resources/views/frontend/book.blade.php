<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUDO Express — Book Your Parcel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #008f40;
            --primary-dark: #007a36;
            --primary-light: #e8f5e9;
            --accent: #ff3519;
            --text-dark: #0b1e33;
            --text-muted: #64748b;
            --border: #e9edf2;
            --bg: #f5f7fa;
            --shadow-md: 0 4px 14px rgba(0, 0, 0, .06);
            --shadow-lg: 0 20px 40px rgba(0, 0, 0, .08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            padding: 24px 16px 60px;
        }

        .wizard {
            max-width: 1080px;
            margin: 0 auto;
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .wizard-header {
            padding: 24px 32px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wizard-header h3 {
            font-weight: 800;
            font-size: 1.35rem;
            margin: 0;
        }

        .wizard-header .subtitle {
            color: var(--text-muted);
            font-size: .9rem;
            margin-top: 2px;
        }

        .steps {
            display: flex;
            justify-content: space-between;
            padding: 24px 40px 8px;
            position: relative;
        }

        .steps::before {
            content: '';
            position: absolute;
            top: 46px;
            left: 60px;
            right: 60px;
            height: 3px;
            background: var(--border);
            z-index: 0;
        }

        .step {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .step-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 3px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-muted);
            transition: .25s ease;
        }

        .step.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 6px 18px rgba(0, 143, 64, .28);
        }

        .step.done .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .step-label {
            margin-top: 8px;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
            text-align: center;
        }

        .step.active .step-label,
        .step.done .step-label {
            color: var(--primary);
        }

        .wizard-body {
            padding: 24px 32px 32px;
        }

        .step-panel {
            display: none;
            animation: fade .25s ease;
        }

        .step-panel.active {
            display: block;
        }

        @keyframes fade {
            from {
                opacity: .4;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-title {
            font-weight: 700;
            font-size: 1.05rem;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-control,
        .form-select {
            border: 2px solid var(--border);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: .92rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(0, 143, 64, .12);
        }

        .btn {
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 24px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(0, 143, 64, .28);
        }

        .btn-outline-secondary {
            border-width: 2px;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        .actions .btn {
            flex: 1;
        }

        /* Item card */
        .item-card {
            background: #fafcff;
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 14px;
            position: relative;
        }

        .item-card .item-badge {
            position: absolute;
            top: -10px;
            left: 16px;
            background: var(--primary);
            color: #fff;
            padding: 2px 12px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .4px;
        }

        .item-card .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: var(--accent);
            font-size: 1.1rem;
            cursor: pointer;
        }

        .add-item-btn {
            width: 100%;
            padding: 12px;
            border: 2px dashed var(--border);
            border-radius: 14px;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            cursor: pointer;
        }

        .add-item-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }

        /* Partner cards */
        .partner-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px;
            border: 2px solid var(--border);
            border-radius: 16px;
            cursor: pointer;
            background: #fff;
            transition: .15s ease;
            margin-bottom: 12px;
        }

        .partner-card:hover {
            border-color: #c7dbd3;
            background: #fbfefc;
        }

        .partner-card.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            box-shadow: 0 4px 14px rgba(0, 143, 64, .12);
        }

        .partner-logo {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--border);
            flex-shrink: 0;
        }

        .partner-logo-placeholder {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .partner-main {
            flex: 1;
            min-width: 0;
        }

        .partner-name {
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }

        .partner-desc {
            font-size: .82rem;
            color: var(--text-muted);
            margin: 2px 0 0;
        }

        .partner-route {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: 6px;
        }

        .partner-route i {
            color: var(--primary);
        }

        .partner-price {
            text-align: right;
            flex-shrink: 0;
        }

        .partner-price .total {
            font-weight: 800;
            color: var(--primary);
            font-size: 1.15rem;
        }

        .partner-price .small-note {
            font-size: .7rem;
            color: var(--text-muted);
        }

        .partner-radio {
            display: none;
        }

        /* Stations */
        .station-list {
            max-height: 260px;
            overflow-y: auto;
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: .5rem;
            background: #fafcff;
        }

        .station-item {
            display: flex;
            gap: 10px;
            padding: 10px 12px;
            margin-bottom: 4px;
            border-radius: 12px;
            background: #fff;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .station-item:hover {
            background: #f2f7ff;
        }

        .station-item.selected {
            background: var(--primary-light);
            border-color: var(--primary);
        }

        .station-item .form-check-input {
            margin-top: 4px;
            flex-shrink: 0;
        }

        .station-item .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .station-name {
            font-weight: 600;
            font-size: .92rem;
        }

        .station-address {
            font-size: .8rem;
            color: var(--text-muted);
        }

        .station-meta {
            font-size: .74rem;
            color: var(--text-muted);
            margin-top: 4px;
            display: flex;
            gap: .9rem;
            flex-wrap: wrap;
        }

        /* Review */
        .review-card {
            background: #fff;
            border: 2px solid var(--border);
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 14px;
        }

        .review-card h6 {
            font-weight: 700;
            color: var(--primary);
            font-size: .95rem;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            margin-bottom: 14px;
        }

        .review-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: .9rem;
            padding: 4px 0;
        }

        .review-row .label {
            color: var(--text-muted);
        }

        .review-row.total {
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--primary);
        }

        .review-card .item-line {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed var(--border);
        }

        .review-card .item-line:last-child {
            border-bottom: none;
        }

        .login-prompt {
            background: #f0f7ff;
            border-left: 4px solid var(--primary);
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .terms-box {
            background: var(--primary-light);
            border: 2px solid var(--primary);
            border-radius: 16px;
            padding: 18px 20px;
            margin-top: 16px;
        }

        .modal-content {
            border-radius: 20px;
            border: none;
        }

        .modal-header,
        .modal-footer {
            border: none;
        }

        @media (max-width: 640px) {

            .wizard-header,
            .wizard-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .steps {
                padding: 18px 18px 6px;
            }

            .steps::before {
                left: 30px;
                right: 30px;
            }

            .step-circle {
                width: 38px;
                height: 38px;
                font-size: .85rem;
            }

            .partner-card {
                flex-wrap: wrap;
            }

            .partner-price {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="wizard">
        <div class="wizard-header">
            <div>
                <h3><i class="bi bi-box-seam me-2" style="color:var(--primary)"></i>Book your parcel</h3>
                <div class="subtitle">Complete in 5 quick steps</div>
            </div>
            <div id="authArea">
                <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-person-plus"></i> Register
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm d-none" id="logoutBtn">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>

        <div class="steps" id="stepIndicators">
            <div class="step active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Items</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Courier</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Stations & Details</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Review</div>
            </div>
            <div class="step" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Pay</div>
            </div>
        </div>

        @if($items->isEmpty())
        <div class="alert alert-warning">
            No parcel categories are currently available. Please contact support.
        </div>
        @endif

        <form id="bookingForm" class="wizard-body" method="POST" action="{{ route('booking.store') }}">
            @csrf
            <input type="hidden" name="sender_town_id" value="{{ $fromTownId }}">
            <input type="hidden" name="receiver_town_id" value="{{ $toTownId }}">
            <input type="hidden" name="transport_partner_id" id="transportPartnerId" value="">
            <input type="hidden" name="booking_type" value="instant">
            <input type="hidden" name="booking_source" value="web">
            <input type="hidden" name="payment_method" value="mpesa">
            <input type="hidden" name="payment_status" value="pending">
            <input type="hidden" name="sender_pick_up_drop_off_point_id" id="pickupPointHidden" value="">
            <input type="hidden" name="delivery_pick_up_drop_off_point_id" id="dropoffPointHidden" value="">

            <!-- STEP 1: Items -->
            <div class="step-panel active" data-panel="1">
                <div class="section-title"><i class="bi bi-cube"></i> Add items to your shipment</div>
                <!-- <p class="text-muted mb-3" style="font-size:.88rem">
                Route: <strong id="routeLabel">{{ $towns->firstWhere('id', $fromTownId)?->name ?? '—' }}</strong>
                <i class="bi bi-arrow-right"></i>
                <strong>{{ $towns->firstWhere('id', $toTownId)?->name ?? '—' }}</strong>
            </p> -->

                <div id="itemsContainer"></div>
                <button type="button" class="add-item-btn" id="addItemBtn">
                    <i class="bi bi-plus-circle me-1"></i> Add another item
                </button>

                <div class="actions">
                    <button type="button" class="btn btn-primary" data-next>Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 2: Courier (transport partner) -->
            <div class="step-panel" data-panel="2">
                <div class="section-title"><i class="bi bi-truck"></i> Choose your courier</div>
                <div id="partnerLoading" class="text-center text-muted py-4 d-none">
                    <div class="spinner-border text-success"></div>
                    <div class="mt-2" style="font-size:.9rem">Fetching couriers for your route…</div>
                </div>
                <div id="partnersList"></div>
                <div id="partnersEmpty" class="text-center text-muted py-4 d-none">
                    <i class="bi bi-exclamation-circle" style="font-size:1.6rem"></i>
                    <p class="mt-2 mb-0">No courier currently serves this route for the selected items.</p>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-outline-secondary" data-prev><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary" data-next disabled id="toStep3">Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 3: Stations & Details -->
            <div class="step-panel" data-panel="3">
                <div class="section-title"><i class="bi bi-geo-alt"></i> Pickup, dropoff & contact details</div>

                <div class="row g-4 mb-2">
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-box-arrow-up text-success me-1"></i>Pickup station</label>
                        <div class="station-list" id="pickupStationList">
                            @forelse($pickupPoints as $point)
                            <div class="station-item" data-id="{{ $point->id }}" data-type="pickup">
                                <input type="radio" class="form-check-input" name="pickupStation" value="{{ $point->id }}">
                                <div>
                                    <div class="station-name">{{ $point->name }}</div>
                                    <div class="station-address">{{ $point->address ?? $point->location ?? '' }}</div>
                                    <div class="station-meta">
                                        @if($point->contact_phone_number ?? false)
                                        <span><i class="bi bi-telephone"></i>{{ $point->contact_phone_number }}</span>
                                        @endif
                                        @if($point->hours ?? false)
                                        <span><i class="bi bi-clock"></i>{{ $point->hours }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-muted p-3">No pickup stations available.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi bi-box-arrow-down text-danger me-1"></i>Dropoff station</label>
                        <div class="station-list" id="dropoffStationList">
                            @forelse($dropoffPoints as $point)
                            <div class="station-item" data-id="{{ $point->id }}" data-type="dropoff">
                                <input type="radio" class="form-check-input" name="dropoffStation" value="{{ $point->id }}">
                                <div>
                                    <div class="station-name">{{ $point->name }}</div>
                                    <div class="station-address">{{ $point->address ?? $point->location ?? '' }}</div>
                                    <div class="station-meta">
                                        @if($point->contact_phone_number ?? false)
                                        <span><i class="bi bi-telephone"></i>{{ $point->contact_phone_number }}</span>
                                        @endif
                                        @if($point->hours ?? false)
                                        <span><i class="bi bi-clock"></i>{{ $point->hours }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-muted p-3">No dropoff stations available.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-success mb-2"><i class="bi bi-person"></i> Sender</h6>
                        <div class="mb-2"><label class="form-label">Full name *</label>
                            <input type="text" class="form-control" name="sender_name" id="senderName" required>
                        </div>
                        <div class="mb-2"><label class="form-label">Phone *</label>
                            <input type="tel" class="form-control" name="sender_phone" id="senderPhone" required>
                        </div>
                        <div><label class="form-label">Email</label>
                            <input type="email" class="form-control" name="sender_email" id="senderEmail">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-person"></i> Receiver</h6>
                        <div class="mb-2"><label class="form-label">Full name *</label>
                            <input type="text" class="form-control" name="receiver_name" id="receiverName" required>
                        </div>
                        <div class="mb-2"><label class="form-label">Phone *</label>
                            <input type="tel" class="form-control" name="receiver_phone" id="receiverPhone" required>
                        </div>
                        <div><label class="form-label">Email</label>
                            <input type="email" class="form-control" name="receiver_email" id="receiverEmail">
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-outline-secondary" data-prev><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary" data-next>Next <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 4: Review -->
            <div class="step-panel" data-panel="4">
                <div class="section-title"><i class="bi bi-clipboard-check"></i> Review your booking</div>
                <div id="reviewContent"></div>

                <div class="actions">
                    <button type="button" class="btn btn-outline-secondary" data-prev><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="button" class="btn btn-primary" data-next>Continue to payment <i class="bi bi-arrow-right"></i></button>
                </div>
            </div>

            <!-- STEP 5: Pay -->
            <div class="step-panel" data-panel="5">
                <div class="section-title"><i class="bi bi-credit-card"></i> Confirm & pay</div>

                <div id="authPrompt" class="login-prompt">
                    <div>
                        <i class="bi bi-person-circle me-2"></i>
                        <strong id="authStatusText">You are browsing as a guest</strong>
                        <span class="text-muted" id="authActionText"> — please login or register to complete booking</span>
                    </div>
                    <div id="authButtons">
                        <button type="button" class="btn btn-outline-secondary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">Register</button>
                    </div>
                    <div id="loggedInActions" class="d-none">
                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Logged in as <span id="loggedInName">User</span></span>
                        <button type="button" class="btn btn-outline-danger btn-sm ms-2" id="logoutBtn2">Logout</button>
                    </div>
                </div>

                <div id="paymentReview" class="review-card"></div>

                <div class="terms-box">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck" name="terms" value="1" required>
                        <label class="form-check-label" for="termsCheck">
                            <strong>I confirm the information is correct and I accept the terms.</strong><br>
                            <span class="text-muted small">
                                You agree to our <a href="{{ route('terms') }}" target="_blank">terms of service</a>
                                and <a href="{{ route('policy') }}" target="_blank">privacy policy</a>.
                            </span>
                        </label>
                    </div>
                </div>

                <div class="actions">
                    <button type="button" class="btn btn-outline-secondary" data-prev><i class="bi bi-arrow-left"></i> Back</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                        <i class="bi bi-lock"></i> Please login to book
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="loginMessage" class="alert d-none"></div>
                    <div class="mb-3"><label class="form-label">Email</label>
                        <input type="email" class="form-control" id="loginEmail" required>
                    </div>
                    <div><label class="form-label">Password</label>
                        <input type="password" class="form-control" id="loginPassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="loginSubmitBtn">Login</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="registerMessage" class="alert d-none"></div>
                    <div class="mb-2"><label class="form-label">Full name</label>
                        <input type="text" class="form-control" id="regName" required>
                    </div>
                    <div class="mb-2"><label class="form-label">Email</label>
                        <input type="email" class="form-control" id="regEmail" required>
                    </div>
                    <div class="mb-2"><label class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="regPhone" required>
                    </div>
                    <div class="mb-2"><label class="form-label">Password</label>
                        <input type="password" class="form-control" id="regPassword" required>
                    </div>
                    <div class="mb-2"><label class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="regConfirmPassword" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="registerSubmitBtn">Create account</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            'use strict';

            // ---------- Config ----------
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const endpoints = {
                partners: @json(route('api.booking.partners')),
                login: @json(url('/customer/login?type=api')),
                register: @json(url('/customer/register')),
                checkAuth: @json(url('/customer/check-auth')),
                logout: @json(url('/customer/logout')),
            };
            const fromTownId = @json((int) $fromTownId ? : null);
            const toTownId = @json((int) $toTownId ? : null);

            // ---------- State ----------
            const state = {
                step: 1,
                items: [],
                itemSeq: 0,
                partners: [],
                selectedPartner: null,
                selectedPickup: null,
                selectedDropoff: null,
                user: null,
                priceData: null,
            };

            const el = id => document.getElementById(id);
            const money = n => 'KES ' + Number(n || 0).toLocaleString(undefined, {
                maximumFractionDigits: 0
            });

            // ---------- Step navigation ----------
            function setStep(n) {
                state.step = n;
                document.querySelectorAll('.step-panel').forEach(p => p.classList.toggle('active', +p.dataset.panel === n));
                document.querySelectorAll('.step').forEach(s => {
                    const k = +s.dataset.step;
                    s.classList.toggle('active', k === n);
                    s.classList.toggle('done', k < n);
                });
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            // ---------- Items ----------
            function itemTemplate(idx) {
                return `
        <div class="item-card" data-idx="${idx}">
            <span class="item-badge">ITEM #${idx + 1}</span>
            <button type="button" class="remove-btn" data-remove><i class="bi bi-x-circle-fill"></i></button>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Parcel category *</label>
                    <select class="form-select" name="items[${idx}][parcel_category_id]" required>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label">Package type *</label>
                    <select class="form-select" name="items[${idx}][package_type]" required>
                        <option value="regular">Regular</option>
                        <option value="fragile">Fragile</option>
                        <option value="perishable">Perishable</option>
                        <option value="valuable">Valuable</option>
                        <option value="hazardous">Hazardous</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Content description *</label>
                    <input type="text" class="form-control" name="items[${idx}][content_description]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Special notes</label>
                    <input type="text" class="form-control" name="items[${idx}][special_notes]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Declared value (KES)</label>
                    <input type="number" min="0" step="1" value="0" class="form-control"
                           name="items[${idx}][declared_value]" data-declared>
                </div>
                <div class="col-md-6 d-flex flex-column justify-content-end">
                    <div class="form-check mb-1">
                        <input class="form-check-input" type="checkbox"
                               name="items[${idx}][insurance_required]" value="1" id="ins${idx}" data-insurance>
                        <label class="form-check-label" for="ins${idx}" style="font-size:.85rem">
                            Add insurance (2% of declared value)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="items[${idx}][payment_on_delivery]" value="1" id="pod${idx}" data-pod>
                        <label class="form-check-label" for="pod${idx}" style="font-size:.85rem">
                            Customer will pay on delivery
                        </label>
                    </div>
                </div>
            </div>
        </div>`;
            }

            const homeCategory = @json($parcelCategoryId ?? null);

            function addItem() {
                const idx = state.itemSeq++;
                state.items.push(idx);
                el('itemsContainer').insertAdjacentHTML('beforeend', itemTemplate(idx));

                // Pre-select the home-page category on item #1
                if (idx === 0 && homeCategory) {
                    const sel = el('itemsContainer')
                        .querySelector(`.item-card[data-idx="0"] [name*="[parcel_category_id]"]`);
                    if (sel) sel.value = String(homeCategory);
                }
                refreshRemoveButtons();
            }

            function refreshRemoveButtons() {
                const cards = el('itemsContainer').querySelectorAll('.item-card');
                cards.forEach(c => {
                    const btn = c.querySelector('[data-remove]');
                    btn.style.display = cards.length <= 1 ? 'none' : 'block';
                });
            }

            function bindItemEvents(root) {
                root.addEventListener('click', e => {
                    const rm = e.target.closest('[data-remove]');
                    if (rm) {
                        const card = rm.closest('.item-card');
                        card.remove();
                        const idx = +card.dataset.idx;
                        state.items = state.items.filter(i => i !== idx);
                        // Re-index remaining items so names align
                        reindexItems();
                        refreshRemoveButtons();
                    }
                });
            }

            function reindexItems() {
                const cards = el('itemsContainer').querySelectorAll('.item-card');
                state.items = [];
                cards.forEach((card, i) => {
                    const oldIdx = +card.dataset.idx;
                    card.dataset.idx = i;
                    card.querySelector('.item-badge').textContent = `ITEM #${i + 1}`;
                    card.querySelectorAll('[name]').forEach(input => {
                        input.name = input.name.replace(/items\[\d+\]/, `items[${i}]`);
                    });
                    card.querySelectorAll('label[for^="ins"]').forEach(l => l.htmlFor = 'ins' + i);
                    card.querySelectorAll('label[for^="pod"]').forEach(l => l.htmlFor = 'pod' + i);
                    card.querySelectorAll('input[id^="ins"]').forEach(l => l.id = 'ins' + i);
                    card.querySelectorAll('input[id^="pod"]').forEach(l => l.id = 'pod' + i);
                    state.items.push(i);
                });
            }

            // ---------- Validate items ----------
            function itemsPayloadForPricing() {
                const payload = [];
                el('itemsContainer').querySelectorAll('.item-card').forEach(card => {
                    const cat = card.querySelector('[name*="[parcel_category_id]"]').value;
                    const declared = parseFloat(card.querySelector('[data-declared]').value) || 0;
                    const insured = card.querySelector('[data-insurance]').checked;
                    payload.push({
                        item_id: +cat,
                        declared_value: declared,
                        insurance_required: insured
                    });
                });
                return payload;
            }

            function validateStep1() {
                const cards = el('itemsContainer').querySelectorAll('.item-card');
                for (const card of cards) {
                    const idx = +card.dataset.idx + 1;
                    const desc = card.querySelector('[name*="[content_description]"]');
                    if (!desc.value.trim()) {
                        alert(`Item #${idx}: Please enter a content description.`);
                        desc.focus();
                        return false;
                    }
                    const pod = card.querySelector('[data-pod]');
                    if (pod.checked) {
                        const declared = parseFloat(card.querySelector('[data-declared]').value) || 0;
                        const notes = card.querySelector('[name*="[special_notes]"]');
                        if (declared <= 0) {
                            alert(`Item #${idx}: Declared value must be > 0 for payment on delivery.`);
                            return false;
                        }
                        if (!notes.value.trim()) {
                            alert(`Item #${idx}: Special notes (payment details) required for payment on delivery.`);
                            notes.focus();
                            return false;
                        }
                    }
                }
                return true;
            }

            // ---------- Fetch couriers (step 2) ----------
            async function loadPartners() {
                el('partnerLoading').classList.remove('d-none');
                el('partnersList').innerHTML = '';
                el('partnersEmpty').classList.add('d-none');
                el('toStep3').disabled = true;

                try {
                    const res = await fetch(endpoints.partners, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({
                            from_town_id: fromTownId,
                            to_town_id: toTownId,
                            items: itemsPayloadForPricing(),
                        }),
                    });
                    const data = await res.json();

                    if (!data.success || !Array.isArray(data.partners) || !data.partners.length) {
                        el('partnersEmpty').classList.remove('d-none');
                        return;
                    }

                    state.partners = data.partners;
                    renderPartners(data);
                } catch (err) {
                    console.error(err);
                    el('partnersEmpty').classList.remove('d-none');
                } finally {
                    el('partnerLoading').classList.add('d-none');
                }
            }

            function renderPartners(data) {
                const fromName = data.from_town_name || '';
                const toName = data.to_town_name || '';

                el('partnersList').innerHTML = data.partners.map(p => {
                    const logo = p.logo ?
                        `<img src="${p.logo}" alt="${p.name}" class="partner-logo">` :
                        `<div class="partner-logo-placeholder"><i class="bi bi-truck"></i></div>`;
                    return `
            <label class="partner-card" data-partner-id="${p.id}">
                <input type="radio" class="partner-radio" name="partnerChoice" value="${p.id}">
                ${logo}
                <div class="partner-main">
                    <h6 class="partner-name">${escapeHtml(p.name)}</h6>
                    ${p.description ? `<p class="partner-desc">${escapeHtml(p.description)}</p>` : ''}
                    <div class="partner-route">
                        <i class="bi bi-geo-alt-fill"></i> ${escapeHtml(fromName)}
                        <i class="bi bi-arrow-right"></i>
                        <i class="bi bi-geo-alt-fill"></i> ${escapeHtml(toName)}
                    </div>
                </div>
                <div class="partner-price">
                    <div class="total">${money(p.total)}</div>
                    <div class="small-note">includes fees & tax</div>
                </div>
            </label>`;
                }).join('');

                el('partnersList').querySelectorAll('.partner-card').forEach(card => {
                    card.addEventListener('click', () => {
                        el('partnersList').querySelectorAll('.partner-card').forEach(c => c.classList.remove('selected'));
                        card.classList.add('selected');
                        const radio = card.querySelector('input[type=radio]');
                        radio.checked = true;
                        const id = +radio.value;
                        state.selectedPartner = state.partners.find(p => p.id === id);
                        el('transportPartnerId').value = id;
                        el('toStep3').disabled = false;
                    });
                });

                // Auto-select first
                const first = el('partnersList').querySelector('.partner-card');
                if (first) first.click();
            }

            // ---------- Stations ----------
            function bindStations() {
                ['pickupStationList', 'dropoffStationList'].forEach(listId => {
                    const list = el(listId);
                    list.querySelectorAll('.station-item').forEach(item => {
                        const radio = item.querySelector('input[type=radio]');
                        item.addEventListener('click', e => {
                            if (e.target === radio) return;
                            radio.checked = true;
                            radio.dispatchEvent(new Event('change'));
                        });
                        radio.addEventListener('change', () => {
                            list.querySelectorAll('.station-item').forEach(x => x.classList.remove('selected'));
                            item.classList.add('selected');
                            const id = +radio.value;
                            if (listId === 'pickupStationList') {
                                state.selectedPickup = id;
                                el('pickupPointHidden').value = id;
                            } else {
                                state.selectedDropoff = id;
                                el('dropoffPointHidden').value = id;
                            }
                        });
                    });
                });

                // Auto-select first if present
                const firstPickup = el('pickupStationList').querySelector('.station-item input');
                if (firstPickup) {
                    firstPickup.checked = true;
                    firstPickup.dispatchEvent(new Event('change'));
                }
                const firstDrop = el('dropoffStationList').querySelector('.station-item input');
                if (firstDrop) {
                    firstDrop.checked = true;
                    firstDrop.dispatchEvent(new Event('change'));
                }
            }

            function validateStep3() {
                if (!state.selectedPickup) {
                    alert('Please select a pickup station.');
                    return false;
                }
                if (!state.selectedDropoff) {
                    alert('Please select a dropoff station.');
                    return false;
                }
                const sName = el('senderName').value.trim();
                const sPhone = el('senderPhone').value.trim();
                const rName = el('receiverName').value.trim();
                const rPhone = el('receiverPhone').value.trim();
                if (!sName || !sPhone || !rName || !rPhone) {
                    alert('Please fill sender and receiver name and phone.');
                    return false;
                }
                if (!/^(0|254|\+254)[0-9]{9}$/.test(sPhone)) {
                    alert('Invalid sender phone.');
                    return false;
                }
                if (!/^(0|254|\+254)[0-9]{9}$/.test(rPhone)) {
                    alert('Invalid receiver phone.');
                    return false;
                }
                return true;
            }

            // ---------- Review ----------
            function renderReview() {
                const p = state.selectedPartner;
                const totals = p?.breakdown?.totals || {};
                const items = el('itemsContainer').querySelectorAll('.item-card');

                let itemsHtml = '';
                items.forEach((card, i) => {
                    const cat = card.querySelector('[name*="[parcel_category_id]"] option:checked').textContent;
                    const desc = card.querySelector('[name*="[content_description]"]').value;
                    const declared = card.querySelector('[data-declared]').value;
                    const insured = card.querySelector('[data-insurance]').checked;
                    const pod = card.querySelector('[data-pod]').checked;
                    itemsHtml += `
                <div class="item-line">
                    <div>
                        <strong>Item #${i + 1}:</strong> ${escapeHtml(desc)}<br>
                        <small class="text-muted">${escapeHtml(cat)} • Declared: KES ${declared}
                        ${insured ? ' • <span class="text-success">Insured</span>' : ''}
                        ${pod ? ' • <span class="text-danger">POD</span>' : ''}</small>
                    </div>
                </div>`;
                });

                el('reviewContent').innerHTML = `
            <div class="review-card">
                <h6><i class="bi bi-truck me-1"></i> Courier</h6>
                <div class="review-row"><span class="label">Partner</span><span>${escapeHtml(p?.name || '—')}</span></div>
            </div>
            <div class="review-card">
                <h6><i class="bi bi-geo-alt me-1"></i> Route & Stations</h6>
                <div class="review-row"><span class="label">Pickup</span><span>${stationName(state.selectedPickup)}</span></div>
                <div class="review-row"><span class="label">Dropoff</span><span>${stationName(state.selectedDropoff)}</span></div>
            </div>
            <div class="review-card">
                <h6><i class="bi bi-cube me-1"></i> Items</h6>
                ${itemsHtml || '<div class="text-muted">No items</div>'}
            </div>
            <div class="review-card">
                <h6><i class="bi bi-cash-coin me-1"></i> Price breakdown</h6>
                <div class="review-row"><span class="label">Courier price</span><span>${money(totals.courier_price)}</span></div>
                <div class="review-row"><span class="label">PUDO fees</span><span>${money(totals.pudo_amount)}</span></div>
                <div class="review-row"><span class="label">Transport fees</span><span>${money(totals.transport_fee)}</span></div>
                <div class="review-row"><span class="label">Platform fees</span><span>${money(totals.platform_amount)}</span></div>
                <div class="review-row"><span class="label">Insurance</span><span>${money(totals.insurance)}</span></div>
                <div class="review-row"><span class="label">Tax</span><span>${money(totals.tax_amount)}</span></div>
                <div class="review-row total"><span>Total</span><span>${money(totals.total)}</span></div>
            </div>`;
            }

            function renderPaymentReview() {
                const p = state.selectedPartner;
                const totals = p?.breakdown?.totals || {};
                el('paymentReview').innerHTML = `
            <h6><i class="bi bi-check2-circle me-1"></i> Booking summary</h6>
            <div class="review-row"><span class="label">Courier</span><span>${escapeHtml(p?.name || '—')}</span></div>
            <div class="review-row"><span class="label">Pickup</span><span>${stationName(state.selectedPickup)}</span></div>
            <div class="review-row"><span class="label">Dropoff</span><span>${stationName(state.selectedDropoff)}</span></div>
            <div class="review-row total"><span>Total payable</span><span>${money(totals.total)}</span></div>
            <p class="text-muted small mt-2 mb-0">Payment via M-Pesa. You will be prompted after confirming.</p>`;
            }

            function stationName(id) {
                if (!id) return '—';
                const s = document.querySelector(`.station-item[data-id="${id}"] .station-name`);
                return s ? s.textContent.trim() : '—';
            }

            function escapeHtml(s) {
                const d = document.createElement('div');
                d.textContent = s ?? '';
                return d.innerHTML;
            }

            // ---------- Auth ----------
            function setAuthState(user) {
                state.user = user;
                const isIn = !!user;
                if (isIn) {
                    el('authArea').querySelector('#logoutBtn').classList.remove('d-none');
                    el('authArea').querySelectorAll('[data-bs-target="#loginModal"],[data-bs-target="#registerModal"]').forEach(b => b.classList.add('d-none'));
                    el('authStatusText').textContent = `Logged in as ${user.name}`;
                    el('authActionText').textContent = '';
                    el('authButtons').classList.add('d-none');
                    el('loggedInActions').classList.remove('d-none');
                    el('loggedInName').textContent = user.name;
                    el('submitBtn').disabled = false;
                    el('submitBtn').innerHTML = '<i class="bi bi-check-circle"></i> Confirm & Book';
                    if (user.name) el('senderName').value = user.name;
                    if (user.email) el('senderEmail').value = user.email;
                    if (user.phone) el('senderPhone').value = user.phone;
                } else {
                    el('authArea').querySelector('#logoutBtn').classList.add('d-none');
                    el('authArea').querySelectorAll('[data-bs-target="#loginModal"],[data-bs-target="#registerModal"]').forEach(b => b.classList.remove('d-none'));
                    el('authStatusText').textContent = 'You are browsing as a guest';
                    el('authActionText').textContent = ' — please login or register to complete booking';
                    el('authButtons').classList.remove('d-none');
                    el('loggedInActions').classList.add('d-none');
                    el('submitBtn').disabled = true;
                    el('submitBtn').innerHTML = '<i class="bi bi-lock"></i> Please login to book';
                }
            }

            async function checkAuth() {
                try {
                    const r = await fetch(endpoints.checkAuth, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const d = await r.json();
                    if (d.authenticated && (d.user || d.customer)) {
                        setAuthState(d.user || d.customer);
                    } else {
                        setAuthState(null);
                    }
                } catch {
                    setAuthState(null);
                }
            }

            async function login() {
                const email = el('loginEmail').value.trim();
                const password = el('loginPassword').value;
                const msg = el('loginMessage');
                msg.classList.add('d-none');
                try {
                    const r = await fetch(endpoints.login, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            email,
                            password
                        })
                    });
                    const d = await r.json();
                    if (!r.ok || !d.success) throw new Error(d.message || 'Login failed');
                    const user = d.user || d.customer;
                    setAuthState(user);
                    bootstrap.Modal.getInstance(el('loginModal'))?.hide();
                } catch (e) {
                    msg.className = 'alert alert-danger';
                    msg.textContent = e.message;
                    msg.classList.remove('d-none');
                }
            }

            async function register() {
                const body = {
                    name: el('regName').value.trim(),
                    email: el('regEmail').value.trim(),
                    phone: el('regPhone').value.trim(),
                    password: el('regPassword').value,
                    password_confirmation: el('regConfirmPassword').value,
                    terms: true,
                };
                const msg = el('registerMessage');
                msg.classList.add('d-none');
                try {
                    const r = await fetch(endpoints.register, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(body)
                    });
                    const d = await r.json();
                    if (!r.ok || !d.success) throw new Error(d.message || 'Registration failed');
                    const user = d.user || d.customer;
                    setAuthState(user);
                    bootstrap.Modal.getInstance(el('registerModal'))?.hide();
                } catch (e) {
                    msg.className = 'alert alert-danger';
                    msg.textContent = e.message;
                    msg.classList.remove('d-none');
                }
            }

            async function logout() {
                try {
                    await fetch(endpoints.logout, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    });
                } finally {
                    setAuthState(null);
                }
            }

            // ---------- Submit guard ----------
            function bindSubmit() {
                el('bookingForm').addEventListener('submit', e => {
                    if (!state.user) {
                        e.preventDefault();
                        alert('Please login or register before booking.');
                        return;
                    }
                    if (!el('termsCheck').checked) {
                        e.preventDefault();
                        alert('Please accept the terms.');
                        return;
                    }
                    if (!state.selectedPartner) {
                        e.preventDefault();
                        alert('Please select a courier.');
                        return;
                    }
                    el('submitBtn').disabled = true;
                    el('submitBtn').innerHTML = '<span class="spinner-border spinner-border-sm"></span> Booking…';
                });
            }

            // ---------- Init ----------
            document.addEventListener('DOMContentLoaded', () => {
                    const hasCategories = @json($items->isNotEmpty());
    if (!hasCategories) {
        document.querySelectorAll('[data-next]').forEach(b => b.disabled = true);
        return; // stop the wizard from initializing further
    }
    
                // Items
                bindItemEvents(el('itemsContainer'));
                addItem();
                el('addItemBtn').addEventListener('click', addItem);

                // Next / Prev
                document.querySelectorAll('[data-next]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const cur = state.step;
                        if (cur === 1) {
                            if (!validateStep1()) return;
                            setStep(2);
                            loadPartners();
                        } else if (cur === 2) {
                            if (!state.selectedPartner) {
                                alert('Please select a courier.');
                                return;
                            }
                            setStep(3);
                        } else if (cur === 3) {
                            if (!validateStep3()) return;
                            renderReview();
                            setStep(4);
                        } else if (cur === 4) {
                            renderPaymentReview();
                            setStep(5);
                        }
                    });
                });
                document.querySelectorAll('[data-prev]').forEach(btn => {
                    btn.addEventListener('click', () => setStep(Math.max(1, state.step - 1)));
                });

                // Stations
                bindStations();

                // Auth
                checkAuth();
                el('loginSubmitBtn').addEventListener('click', login);
                el('registerSubmitBtn').addEventListener('click', register);
                el('logoutBtn').addEventListener('click', logout);
                el('logoutBtn2').addEventListener('click', logout);

                bindSubmit();
            });
        })();
    </script>
</body>

</html>