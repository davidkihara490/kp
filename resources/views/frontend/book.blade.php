<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Karibu Parcels - Book Your Parcel</title>
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('logo.jpeg') }}"> <!-- jQuery -->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #008f40;
      --primary-dark: #007a36;
      --primary-light: #e8f5e9;
      --accent-color: #ff3519;
      --accent-dark: #e62e15;
      --light-bg: #f8f9fa;
      --dark-bg: #212529;
      --text-dark: #343a40;
      --text-light: #6c757d;
      --border-color: #e9ecef;
      --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
      --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.05);
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f5f7fa;
      color: var(--text-dark);
      padding-top: 80px;
    }

    .booking-container {
      max-width: 900px;
      margin: 2rem auto;
      background: white;
      border-radius: 28px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
      padding: 2rem 2rem 2.5rem;
      border: 1px solid var(--border-color);
    }

    .step-indicators {
      display: flex;
      justify-content: space-between;
      margin-bottom: 2.5rem;
      position: relative;
      padding: 0 10px;
    }

    .step-indicators::before {
      content: '';
      position: absolute;
      top: 28px;
      left: 40px;
      right: 40px;
      height: 3px;
      background: var(--border-color);
      z-index: 0;
    }

    .step-badge {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 1;
      flex: 1;
    }

    .step-circle {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: white;
      border: 3px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 1.1rem;
      color: var(--text-light);
      transition: var(--transition);
      background: #f0f2f5;
    }

    .step-badge.active .step-circle {
      border-color: var(--primary-color);
      background: var(--primary-color);
      color: white;
      box-shadow: 0 6px 14px rgba(0, 143, 64, 0.25);
    }

    .step-badge.completed .step-circle {
      border-color: var(--primary-color);
      background: var(--primary-color);
      color: white;
    }

    .step-label {
      margin-top: 8px;
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--text-light);
      text-align: center;
    }

    .step-badge.active .step-label,
    .step-badge.completed .step-label {
      color: var(--primary-color);
    }

    .step-panel {
      display: none;
      animation: fadeIn 0.3s ease;
    }

    .step-panel.active {
      display: block;
    }

    @keyframes fadeIn {
      from {
        opacity: 0.5;
        transform: translateY(8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-label {
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-dark);
    }

    .form-control,
    .form-select {
      border: 2px solid var(--border-color);
      border-radius: 14px;
      padding: 12px 16px;
      font-size: 0.95rem;
      transition: var(--transition);
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(0, 143, 64, 0.1);
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
      border: none;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 143, 64, 0.25);
    }

    .btn-outline-secondary {
      border-radius: 50px;
      padding: 12px 30px;
      border-width: 2px;
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      border: none;
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      transition: var(--transition);
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(40, 167, 69, 0.25);
    }

    .step-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 2rem;
      gap: 12px;
    }

    .step-actions .btn {
      flex: 1;
    }

    .review-summary {
      background: white;
      border-radius: 16px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      border: 2px solid var(--border-color);
    }

    .review-summary h6 {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 1rem;
      border-bottom: 2px solid var(--primary-light);
      padding-bottom: 0.5rem;
    }

    .review-summary .row {
      margin-bottom: 0.5rem;
    }

    .review-summary .label {
      color: var(--text-light);
      font-size: 0.85rem;
      font-weight: 500;
    }

    .review-summary .value {
      font-weight: 500;
      color: var(--text-dark);
    }

    .confirmation-section {
      background: var(--primary-light);
      border-radius: 16px;
      padding: 1.5rem;
      margin-top: 1.5rem;
      border: 2px solid var(--primary-color);
    }

    .confirmation-section .form-check-label {
      font-weight: 500;
    }

    .login-prompt {
      background: #f0f7ff;
      border-radius: 20px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      border-left: 5px solid var(--primary-color);
    }

    .login-prompt .btn-link {
      color: var(--primary-color);
      font-weight: 600;
    }

    .whatsapp-float {
      position: fixed;
      bottom: 30px;
      right: 30px;
      z-index: 1000;
    }

    .whatsapp-button {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #25d366, #128C7E);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      box-shadow: 0 10px 20px rgba(37, 211, 102, 0.3);
      transition: var(--transition);
    }

    .whatsapp-button:hover {
      transform: scale(1.08);
      color: white;
    }

    .modal-content {
      border-radius: 24px;
      border: none;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
      border-bottom: none;
      padding-bottom: 0;
    }

    .modal-footer {
      border-top: none;
    }

    .price-display {
      background: var(--primary-light);
      border-radius: 12px;
      padding: 1rem 1.5rem;
      border: 2px solid var(--primary-color);
    }

    .price-display .label {
      font-weight: 500;
      color: var(--text-light);
    }

    .price-display .amount {
      font-weight: 700;
      font-size: 1.3rem;
      color: var(--primary-color);
    }

    /* User info bar */
    .user-info-bar {
      background: var(--primary-light);
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      margin-bottom: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid var(--primary-color);
    }

    .user-info-bar .user-name {
      font-weight: 600;
      color: var(--primary-color);
    }

    .user-info-bar .logout-btn {
      cursor: pointer;
      color: var(--text-danger);
      font-size: 0.9rem;
    }

    .user-info-bar .logout-btn:hover {
      text-decoration: underline;
    }

    .user-info-bar .guest-text {
      color: var(--text-light);
      font-weight: 500;
    }

    @media (max-width: 640px) {
      .booking-container {
        padding: 1.5rem;
      }

      .step-indicators {
        padding: 0;
      }

      .step-indicators::before {
        left: 20px;
        right: 20px;
      }

      .step-circle {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
      }

      .user-info-bar {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="booking-container">
      <h3 class="mb-1 fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Book your parcel</h3>
      <p class="text-muted mb-4">Fill in the details below – 3 easy steps</p>

      <!-- User Info Bar -->
      <div class="user-info-bar" id="userInfoBar">
        <div id="userInfoDisplay">
          <span class="guest-text"><i class="bi bi-person-circle me-2"></i>You are browsing as a guest</span>
        </div>
        <div>
          <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
            <i class="bi bi-person-plus me-1"></i> Register
          </button>
        </div>
      </div>

      <!-- Step indicators -->
      <div class="step-indicators">
        <div class="step-badge active" id="stepIndicator1">
          <div class="step-circle">1</div>
          <div class="step-label">Parcel details</div>
        </div>
        <div class="step-badge" id="stepIndicator2">
          <div class="step-circle">2</div>
          <div class="step-label">Sender & receiver</div>
        </div>
        <div class="step-badge" id="stepIndicator3">
          <div class="step-circle">3</div>
          <div class="step-label">Review & Confirm</div>
        </div>
      </div>

      <!-- Main Form -->
      <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
        @csrf

        <!-- STEP 1: Parcel details -->
        <div class="step-panel active" id="step1">
          <h5 class="fw-semibold mb-3"><i class="bi bi-box me-2 text-primary"></i>Parcel information</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">From Town <span class="text-danger">*</span></label>
              <input type="number" name="sender_town_id" value="{{ $fromTownId }}" class="d-none">
              <select class="form-select" name="sender_town_id" id="fromTown" disabled required>
                <option value="">Select pickup town</option>
                @foreach($towns as $town)
                <option value="{{ $town->id }}" {{ (isset($fromTownId) && $fromTownId == $town->id) ? 'selected' : '' }}>
                  {{ $town->name }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">To Town <span class="text-danger">*</span></label>
              <input type="number" name="receiver_town_id" value="{{ $toTownId }}" class="d-none">
              <select class="form-select" name="receiver_town_id" id="toTown" disabled required>
                <option value="">Select delivery town</option>
                @foreach($towns as $town)
                <option value="{{ $town->id }}" {{ (isset($toTownId) && $toTownId == $town->id) ? 'selected' : '' }}>
                  {{ $town->name }}
                </option>
                @endforeach
              </select>
            </div>

            <!-- Pickup Point -->
            <div class="col-md-6">
              <label class="form-label">Pickup Point <span class="text-danger">*</span></label>
              <select class="form-select" name="sender_pick_up_drop_off_point_id" id="pickupPoint" required>
                <option value="">Select pickup point</option>
                @foreach($pickupPoints as $point)
                <option value="{{ $point->id }}">{{ $point->name }} - {{ $point->town->name }}</option>
                @endforeach
              </select>
            </div>

            <!-- Dropoff Point -->
            <div class="col-md-6">
              <label class="form-label">Dropoff Point <span class="text-danger">*</span></label>
              <select class="form-select" name="delivery_pick_up_drop_off_point_id" id="dropoffPoint" required>
                <option value="">Select dropoff point</option>
                @foreach($dropoffPoints as $point)
                <option value="{{ $point->id }}">{{ $point->name }} - {{ $point->town->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Parcel type <span class="text-danger">*</span></label>
              <select class="form-select" name="parcel_type" id="parcelType" required>
                <option value="document">Document</option>
                <option value="package" selected>Package</option>
                <option value="envelope">Envelope</option>
                <option value="box">Box</option>
                <option value="pallet">Pallet</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Package type <span class="text-danger">*</span></label>
              <select class="form-select" name="package_type" id="packageType" required>
                <option value="regular">Regular</option>
                <option value="fragile">Fragile</option>
                <option value="perishable">Perishable</option>
                <option value="valuable">Valuable</option>
                <option value="hazardous">Hazardous</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Weight (kg) <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="weight" id="weight"
                value="{{ $parcelWeight}}" readonly required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Declared Value (KES)</label>
              <input type="number" class="form-control" name="declared_value" id="declaredValue"
                placeholder="e.g., 5000" min="0" step="1" value="0">
            </div>
            <div class="col-md-4">
              <label class="form-label">Insurance (2% of value)</label>
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="insurance_required" id="insuranceRequired" value="1">
                <label class="form-check-label" for="insuranceRequired">
                  Add insurance <span class="text-muted small">(KES <span id="insuranceAmount">0</span>)</span>
                </label>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Content description <span class="text-danger">*</span> <span><a target="__blank" href="{{ route('prohibited-items') }}" class="text-primary">Please refer to our prohibited items.</a></span></label>
              <input type="text" class="form-control" name="content_description" id="parcelContent"
                placeholder="Describe what you are sending" required>
            </div>
            <span class="text-muted small"> Karibu Parcels Limited and its agents has the right to reject the prohibited items</span>

            <div class="col-12">
              <label class="form-label">Special instructions (optional)</label>
              <input type="text" class="form-control" name="special_instructions" id="instructions"
                placeholder="Fragile, handle with care">
            </div>

            <!-- Price Display -->
            <div class="col-12 mt-3">
              <div class="price-display d-flex justify-content-between align-items-center">
                <span class="label">Estimated Total:</span>
                <span class="amount" id="estimatedTotal">KES {{ $price }}</span>
              </div>
            </div>
          </div>
          <div class="step-actions">
            <button type="button" class="btn btn-primary" id="toStep2">Next <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </div>

        <!-- STEP 2: Sender & Receiver -->
        <div class="step-panel" id="step2">
          <h5 class="fw-semibold mb-3"><i class="bi bi-people me-2 text-primary"></i>Sender & receiver details</h5>
          <div class="row g-3">
            <!-- Sender column -->
            <div class="col-md-6">
              <h6 class="fw-semibold text-primary"><i class="bi bi-person me-1"></i>Sender</h6>
              <div class="mb-2">
                <label class="form-label">Full name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sender_name" id="senderName" placeholder="John Mwangi" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="sender_phone" id="senderPhone" placeholder="0712345678" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="sender_email" id="senderEmail" placeholder="john@example.com">
              </div>
            </div>
            <!-- Receiver column -->
            <div class="col-md-6">
              <h6 class="fw-semibold text-danger"><i class="bi bi-person me-1"></i>Receiver</h6>
              <div class="mb-2">
                <label class="form-label">Full name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="receiver_name" id="receiverName" placeholder="Jane Akinyi" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="receiver_phone" id="receiverPhone" placeholder="0722334455" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="receiver_email" id="receiverEmail" placeholder="jane@example.com">
              </div>
            </div>
          </div>

          <div class="step-actions">
            <button type="button" class="btn btn-outline-secondary" id="backToStep1"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="button" class="btn btn-primary" id="toStep3">Review & Confirm <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </div>

        <div class="step-panel" id="step3">
          <h5 class="fw-semibold mb-3"><i class="bi bi-clipboard-check me-2 text-primary"></i>Review & Confirm</h5>

          <div class="login-prompt d-flex justify-content-between align-items-center flex-wrap" id="authPrompt">
            <div>
              <i class="bi bi-person-circle me-2 fs-4"></i>
              <span class="fw-semibold" id="authStatusText">You are browsing as a guest</span>
              <span class="text-muted" id="authActionText"> - Please login or register to complete booking</span>
            </div>
            <div id="authButtons">
              <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-box-arrow-in-right"></i> Login</button>
              <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal"><i class="bi bi-person-plus"></i> Register</button>
            </div>
            <div id="loggedInActions" style="display: none;">
              <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Logged in as <span id="loggedInName">User</span></span>
              <button type="button" class="btn btn-outline-danger btn-sm ms-2" id="logoutBtn"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </div>
          </div>

          <div class="review-summary">
            <h6><i class="bi bi-box me-2"></i>Parcel Details</h6>
            <div class="row">
              <div class="col-md-6"><span class="label">From:</span> <span class="value" id="revFrom">-</span></div>
              <div class="col-md-6"><span class="label">To:</span> <span class="value" id="revTo">-</span></div>
              <div class="col-md-6"><span class="label">Pickup Point:</span> <span class="value" id="revPickup">-</span></div>
              <div class="col-md-6"><span class="label">Dropoff Point:</span> <span class="value" id="revDropoff">-</span></div>
              <div class="col-md-6"><span class="label">Parcel Type:</span> <span class="value" id="revType">-</span></div>
              <div class="col-md-6"><span class="label">Package Type:</span> <span class="value" id="revPackageType">-</span></div>
              <div class="col-md-6"><span class="label">Weight:</span> <span class="value" id="revWeight">-</span></div>
              <div class="col-md-6"><span class="label">Declared Value:</span> <span class="value" id="revValue">KES 0</span></div>
              <div class="col-md-6"><span class="label">Insurance:</span> <span class="value" id="revInsurance">No</span></div>
              <div class="col-md-6"><span class="label">Insurance Amount:</span> <span class="value" id="revInsuranceAmount">KES 0</span></div>
              <div class="col-12"><span class="label">Content:</span> <span class="value" id="revContent">-</span></div>
              <div class="col-12"><span class="label">Special Instructions:</span> <span class="value" id="revInstructions">None</span></div>
              <div class="col-12 mt-2"><span class="label">Total Amount:</span> <span class="value fw-bold text-success" id="revTotal">KES 0</span></div>
            </div>
          </div>

          <div class="review-summary">
            <h6><i class="bi bi-person me-2"></i>Sender Details</h6>
            <div class="row">
              <div class="col-md-6"><span class="label">Name:</span> <span class="value" id="revSender">-</span></div>
              <div class="col-md-6"><span class="label">Phone:</span> <span class="value" id="revSenderPhone">-</span></div>
              <div class="col-md-6"><span class="label">Email:</span> <span class="value" id="revSenderEmail">-</span></div>
            </div>
          </div>

          <div class="review-summary">
            <h6><i class="bi bi-person me-2"></i>Receiver Details</h6>
            <div class="row">
              <div class="col-md-6"><span class="label">Name:</span> <span class="value" id="revReceiver">-</span></div>
              <div class="col-md-6"><span class="label">Phone:</span> <span class="value" id="revReceiverPhone">-</span></div>
              <div class="col-md-6"><span class="label">Email:</span> <span class="value" id="revReceiverEmail">-</span></div>
            </div>
          </div>

          <input type="hidden" name="booking_type" value="instant">
          <input type="hidden" name="booking_source" value="web">
          <input type="hidden" name="payment_method" value="cash">
          <input type="hidden" name="payment_status" value="pending">
          <input type="hidden" name="total_amount" id="totalAmount" value="0">
          <input type="hidden" name="insurance_amount" id="insuranceAmountHidden" value="0">
          <input type="hidden" name="customer_id" id="customerId" value="">

          <div class="confirmation-section">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="termsCheck" name="terms" required>
              <label class="form-check-label" for="termsCheck">
                <strong>I confirm that all the information provided above is correct.</strong><br>
                <span class="text-muted small">By checking this box, you agree to our <a target="__blank" href="{{ route('terms') }}" class="text-primary">terms of service</a> and <a target="__blank" href="{{ route('policy') }}" class="text-primary">privacy policy</a> and confirm that you are authorized to send this parcel.</span>
              </label>
            </div>
          </div>

          <div class="step-actions">
            <button type="button" class="btn btn-outline-secondary" id="backToStep2"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="submit" class="btn btn-success" id="saveParcelBtn" disabled>
              <i class="bi bi-lock me-1"></i> Please Login to Book
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="whatsapp-float">
    <a href="#" class="whatsapp-button" target="_blank"><i class="fab fa-whatsapp"></i></a>
  </div>

  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="loginModalLabel"><i class="bi bi-box-arrow-in-right me-2 text-primary"></i>Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="loginMessage" class="alert" style="display: none;"></div>
          <form id="loginForm">
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" id="loginEmail" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" id="loginPassword" placeholder="••••••••" required>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label small" for="rememberMe">Remember me</label>
              </div>
              <a href="#" class="small text-primary">Forgot password?</a>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="loginSubmitBtn">
            <span id="loginBtnText">Login</span>
            <span id="loginBtnSpinner" class="spinner-border spinner-border-sm" style="display: none;" role="status"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="registerModalLabel"><i class="bi bi-person-plus me-2 text-primary"></i>Register</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="registerMessage" class="alert" style="display: none;"></div>
          <form id="registerForm">
            <div class="mb-3">
              <label class="form-label">Full name</label>
              <input type="text" class="form-control" id="regName" placeholder="John Mwangi" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" class="form-control" id="regEmail" placeholder="name@example.com" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone number</label>
              <input type="tel" class="form-control" id="regPhone" placeholder="0712345678" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" class="form-control" id="regPassword" placeholder="••••••••" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm password</label>
              <input type="password" class="form-control" id="regConfirmPassword" placeholder="••••••••" required>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="regTerms" required>
              <label class="form-check-label small" for="regTerms">I agree to the <a href="#" class="text-primary">terms and conditions</a></label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="registerSubmitBtn">
            <span id="registerBtnText">Create account</span>
            <span id="registerBtnSpinner" class="spinner-border spinner-border-sm" style="display: none;" role="status"></span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function() {
      // ============================================
      // AUTH STATE MANAGEMENT
      // ============================================
      let isLoggedIn = false;
      let currentUser = null;

      // ============================================
      // UI UPDATES
      // ============================================
      function updateUIForLoggedInUser(user) {
        if (!user) return;

        // Update top user bar
        const userInfoDisplay = document.getElementById('userInfoDisplay');
        userInfoDisplay.innerHTML = `
          <span class="user-name"><i class="bi bi-person-check-fill me-2 text-primary"></i>Welcome, ${user.name}!</span>
          <span class="text-muted ms-2 small">(${user.email})</span>
        `;

        // Replace buttons with logout
        const userInfoBar = document.getElementById('userInfoBar');
        const existingButtons = userInfoBar.querySelector('div:last-child');
        existingButtons.innerHTML = `
          <button type="button" class="btn btn-outline-danger btn-sm" id="logoutBtnTop">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </button>
        `;
        document.getElementById('logoutBtnTop').addEventListener('click', handleLogout);

        // Update auth prompt in step 3
        document.getElementById('authStatusText').textContent = `You are logged in as ${user.name}`;
        document.getElementById('authActionText').textContent = '';
        document.getElementById('authButtons').style.display = 'none';
        document.getElementById('loggedInActions').style.display = 'inline-block';
        document.getElementById('loggedInName').textContent = user.name;

        // Enable booking button
        document.getElementById('saveParcelBtn').disabled = false;
        document.getElementById('saveParcelBtn').innerHTML = '<i class="bi bi-check-circle me-1"></i> Confirm & Book';

        // Set customer_id hidden field
        document.getElementById('customerId').value = user.id;

        // Pre-fill sender details if available
        if (user.name) {
          document.getElementById('senderName').value = user.name;
        }
        if (user.email) {
          document.getElementById('senderEmail').value = user.email;
        }
        if (user.phone) {
          document.getElementById('senderPhone').value = user.phone;
        }

        isLoggedIn = true;
        currentUser = user;
      }

      function updateUIForLoggedOutUser() {
        // Reset top user bar
        const userInfoDisplay = document.getElementById('userInfoDisplay');
        userInfoDisplay.innerHTML = `
          <span class="guest-text"><i class="bi bi-person-circle me-2"></i>You are browsing as a guest</span>
        `;

        const userInfoBar = document.getElementById('userInfoBar');
        const existingButtons = userInfoBar.querySelector('div:last-child');
        existingButtons.innerHTML = `
          <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
            <i class="bi bi-person-plus me-1"></i> Register
          </button>
        `;

        // Reset auth prompt
        document.getElementById('authStatusText').textContent = 'You are browsing as a guest';
        document.getElementById('authActionText').textContent = ' - Please login or register to complete booking';
        document.getElementById('authButtons').style.display = 'inline-block';
        document.getElementById('loggedInActions').style.display = 'none';

        // Disable booking button
        document.getElementById('saveParcelBtn').disabled = true;
        document.getElementById('saveParcelBtn').innerHTML = '<i class="bi bi-lock me-1"></i> Please Login to Book';

        // Clear customer_id
        document.getElementById('customerId').value = '';

        isLoggedIn = false;
        currentUser = null;
      }

      // ============================================
      // AUTH FUNCTIONS WITH REAL API CALLS
      // ============================================

      function handleLogin(e) {
        e.preventDefault();

        const email = document.getElementById('loginEmail').value.trim();
        const password = document.getElementById('loginPassword').value.trim();
        const messageDiv = document.getElementById('loginMessage');

        if (!email || !password) {
          showMessage(messageDiv, 'Please fill in both fields.', 'warning');
          return;
        }

        // Show loading
        document.getElementById('loginBtnText').style.display = 'none';
        document.getElementById('loginBtnSpinner').style.display = 'inline-block';
        document.getElementById('loginSubmitBtn').disabled = true;
        document.getElementById('loginMessage').style.display = 'none';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Make API call to login endpoint
        fetch('/customer/login?type=api', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              email: email,
              password: password,
              remember: document.getElementById('rememberMe').checked
            })
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(err => {
                throw new Error(err.message || 'Login failed');
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Update UI with user data from server
              updateUIForLoggedInUser(data.user);
              showMessage(messageDiv, data.message || 'Login successful!', 'success');

              // Close modal after delay
              setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                modal.hide();
                // Reset form
                document.getElementById('loginForm').reset();
                document.getElementById('loginBtnText').style.display = 'inline';
                document.getElementById('loginBtnSpinner').style.display = 'none';
                document.getElementById('loginSubmitBtn').disabled = false;
                document.getElementById('loginMessage').style.display = 'none';
              }, 1000);
            } else {
              showMessage(messageDiv, data.message || 'Login failed. Please try again.', 'danger');
              document.getElementById('loginBtnText').style.display = 'inline';
              document.getElementById('loginBtnSpinner').style.display = 'none';
              document.getElementById('loginSubmitBtn').disabled = false;
            }
          })
          .catch(error => {
            console.error('Login error:', error);
            showMessage(messageDiv, error.message || 'Network error. Please try again.', 'danger');
            document.getElementById('loginBtnText').style.display = 'inline';
            document.getElementById('loginBtnSpinner').style.display = 'none';
            document.getElementById('loginSubmitBtn').disabled = false;
          });
      }

      function handleRegister(e) {
        e.preventDefault();

        const name = document.getElementById('regName').value.trim();
        const email = document.getElementById('regEmail').value.trim();
        const phone = document.getElementById('regPhone').value.trim();
        const password = document.getElementById('regPassword').value;
        const confirm = document.getElementById('regConfirmPassword').value;
        const terms = document.getElementById('regTerms').checked;
        const messageDiv = document.getElementById('registerMessage');

        // Client-side validation
        if (!name || !email || !phone || !password || !confirm) {
          showMessage(messageDiv, 'Please fill all fields.', 'warning');
          return;
        }

        if (password !== confirm) {
          showMessage(messageDiv, 'Passwords do not match.', 'warning');
          return;
        }

        if (password.length < 8) {
          showMessage(messageDiv, 'Password must be at least 8 characters.', 'warning');
          return;
        }

        if (!phone.match(/^(0|254|\+254)[0-9]{9}$/)) {
          showMessage(messageDiv, 'Please enter a valid phone number (e.g. 0712345678).', 'warning');
          return;
        }

        if (!terms) {
          showMessage(messageDiv, 'Please agree to the terms and conditions.', 'warning');
          return;
        }

        // Show loading
        document.getElementById('registerBtnText').style.display = 'none';
        document.getElementById('registerBtnSpinner').style.display = 'inline-block';
        document.getElementById('registerSubmitBtn').disabled = true;
        document.getElementById('registerMessage').style.display = 'none';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Make API call to register endpoint
        fetch('/customer/register', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              name: name,
              email: email,
              phone: phone,
              password: password,
              password_confirmation: confirm,
              terms: true
            })
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(err => {
                // Handle validation errors
                if (err.errors) {
                  const errorMessages = Object.values(err.errors).flat().join(', ');
                  throw new Error(errorMessages);
                }
                throw new Error(err.message || 'Registration failed');
              });
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              // Update UI with user data from server
              updateUIForLoggedInUser(data.customer);
              showMessage(messageDiv, data.message || 'Registration successful!', 'success');

              // Close modal after delay
              setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                modal.hide();
                // Reset form
                document.getElementById('registerForm').reset();
                document.getElementById('registerBtnText').style.display = 'inline';
                document.getElementById('registerBtnSpinner').style.display = 'none';
                document.getElementById('registerSubmitBtn').disabled = false;
                document.getElementById('registerMessage').style.display = 'none';
              }, 1000);
            } else {
              showMessage(messageDiv, data.message || 'Registration failed. Please try again.', 'danger');
              document.getElementById('registerBtnText').style.display = 'inline';
              document.getElementById('registerBtnSpinner').style.display = 'none';
              document.getElementById('registerSubmitBtn').disabled = false;
            }
          })
          .catch(error => {
            console.error('Registration error:', error);
            showMessage(messageDiv, error.message || 'Network error. Please try again.', 'danger');
            document.getElementById('registerBtnText').style.display = 'inline';
            document.getElementById('registerBtnSpinner').style.display = 'none';
            document.getElementById('registerSubmitBtn').disabled = false;
          });
      }

      // ============================================
      // CHECK LOGIN STATUS ON PAGE LOAD
      // ============================================
      function checkLoginStatus() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/customer/check-auth', {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
          })
          .then(response => response.json())
          .then(data => {
            if (data.authenticated && data.customer) {
              updateUIForLoggedInUser(data.customer);
            } else {
              updateUIForLoggedOutUser();
            }
          })
          .catch(error => {
            console.error('Auth check error:', error);
            updateUIForLoggedOutUser();
          });
      }

      function handleLogout() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/customer/logout', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
          })
          .then(response => response.json())
          .then(data => {
            updateUIForLoggedOutUser();
            document.getElementById('loginForm').reset();
            document.getElementById('registerForm').reset();
            alert(data.message || 'You have been logged out successfully.');
          })
          .catch(error => {
            console.error('Logout error:', error);
            updateUIForLoggedOutUser();
            alert('You have been logged out.');
          });
      }

      function showMessage(container, message, type) {
        container.style.display = 'block';
        container.className = `alert alert-${type}`;
        container.textContent = message;
      }

      // ============================================
      // STEP NAVIGATION
      // ============================================
      let currentStep = 1;
      const stepPanels = {
        1: document.getElementById('step1'),
        2: document.getElementById('step2'),
        3: document.getElementById('step3')
      };
      const indicators = {
        1: document.getElementById('stepIndicator1'),
        2: document.getElementById('stepIndicator2'),
        3: document.getElementById('stepIndicator3')
      };

      function updateStep(step) {
        Object.values(stepPanels).forEach(p => p.classList.remove('active'));
        if (stepPanels[step]) stepPanels[step].classList.add('active');
        for (let i = 1; i <= 3; i++) {
          const ind = indicators[i];
          ind.classList.remove('active', 'completed');
          if (i < step) ind.classList.add('completed');
          else if (i === step) ind.classList.add('active');
        }
        currentStep = step;
        if (step === 3) updateReviewDetails();
      }

      // ============================================
      // CALCULATIONS
      // ============================================
      function calculateTotal() {
        const weight = parseFloat(document.getElementById('weight').value) || 1;
        const basePrice = {{ $price }};
        const weightCharge = (weight - 1) * 150;
        const declaredValue = parseFloat(document.getElementById('declaredValue').value) || 0;
        const insuranceRequired = document.getElementById('insuranceRequired').checked;
        const insuranceAmount = insuranceRequired ? declaredValue * 0.02 : 0;
        const total = basePrice + insuranceAmount;

        document.getElementById('insuranceAmount').textContent = insuranceAmount.toFixed(0);
        document.getElementById('insuranceAmountHidden').value = insuranceAmount.toFixed(2);
        document.getElementById('estimatedTotal').textContent = 'KES ' + total.toFixed(0);
        document.getElementById('totalAmount').value = total.toFixed(2);

        return {
          total: total,
          insuranceAmount: insuranceAmount,
          declaredValue: declaredValue,
          insuranceRequired: insuranceRequired
        };
      }

      function updateReviewDetails() {
        const fromTown = document.getElementById('fromTown');
        const toTown = document.getElementById('toTown');
        const pickupPoint = document.getElementById('pickupPoint');
        const dropoffPoint = document.getElementById('dropoffPoint');

        document.getElementById('revFrom').textContent = fromTown.options[fromTown.selectedIndex]?.text || '—';
        document.getElementById('revTo').textContent = toTown.options[toTown.selectedIndex]?.text || '—';
        document.getElementById('revPickup').textContent = pickupPoint.options[pickupPoint.selectedIndex]?.text || '—';
        document.getElementById('revDropoff').textContent = dropoffPoint.options[dropoffPoint.selectedIndex]?.text || '—';
        document.getElementById('revType').textContent = document.getElementById('parcelType').options[document.getElementById('parcelType').selectedIndex]?.text || '—';
        document.getElementById('revPackageType').textContent = document.getElementById('packageType').options[document.getElementById('packageType').selectedIndex]?.text || '—';
        document.getElementById('revWeight').textContent = document.getElementById('weight').value ? document.getElementById('weight').value + ' kg' : '—';

        const declaredValue = parseFloat(document.getElementById('declaredValue').value) || 0;
        document.getElementById('revValue').textContent = declaredValue > 0 ? 'KES ' + declaredValue : 'KES 0';

        const insuranceRequired = document.getElementById('insuranceRequired').checked;
        document.getElementById('revInsurance').textContent = insuranceRequired ? 'Yes' : 'No';
        const insuranceAmount = insuranceRequired ? declaredValue * 0.02 : 0;
        document.getElementById('revInsuranceAmount').textContent = insuranceAmount > 0 ? 'KES ' + insuranceAmount.toFixed(0) : 'KES 0';

        document.getElementById('revContent').textContent = document.getElementById('parcelContent').value || '—';
        document.getElementById('revInstructions').textContent = document.getElementById('instructions').value || 'None';

        const totalData = calculateTotal();
        document.getElementById('revTotal').textContent = 'KES ' + totalData.total.toFixed(0);

        document.getElementById('revSender').textContent = document.getElementById('senderName').value || '—';
        document.getElementById('revSenderPhone').textContent = document.getElementById('senderPhone').value || '—';
        document.getElementById('revSenderEmail').textContent = document.getElementById('senderEmail').value || '—';
        document.getElementById('revSenderAddress').textContent = document.getElementById('senderAddress').value || '—';

        document.getElementById('revReceiver').textContent = document.getElementById('receiverName').value || '—';
        document.getElementById('revReceiverPhone').textContent = document.getElementById('receiverPhone').value || '—';
        document.getElementById('revReceiverEmail').textContent = document.getElementById('receiverEmail').value || '—';
        document.getElementById('revReceiverAddress').textContent = document.getElementById('receiverAddress').value || '—';
      }

      // ============================================
      // EVENT LISTENERS
      // ============================================

      // Login
      document.getElementById('loginSubmitBtn').addEventListener('click', handleLogin);
      document.getElementById('loginForm').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleLogin(e);
      });

      // Register
      document.getElementById('registerSubmitBtn').addEventListener('click', handleRegister);
      document.getElementById('registerForm').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleRegister(e);
      });

      // Logout
      document.getElementById('logoutBtn').addEventListener('click', handleLogout);

      // Step navigation
      document.getElementById('toStep2').addEventListener('click', function() {
        const from = document.getElementById('fromTown').value;
        const to = document.getElementById('toTown').value;
        const pickup = document.getElementById('pickupPoint').value;
        const dropoff = document.getElementById('dropoffPoint').value;
        const type = document.getElementById('parcelType').value;
        const weight = document.getElementById('weight').value;
        const content = document.getElementById('parcelContent').value.trim();

        if (!from || !to || !pickup || !dropoff || !type || !weight || weight <= 0) {
          alert('Please fill all parcel details (from, to, pickup point, dropoff point, type, weight > 0)');
          return;
        }
        if (!content) {
          alert('Please describe the parcel content.');
          return;
        }
        updateStep(2);
      });

      document.getElementById('backToStep1').addEventListener('click', function() {
        updateStep(1);
      });

      document.getElementById('toStep3').addEventListener('click', function() {
        const sName = document.getElementById('senderName').value.trim();
        const sPhone = document.getElementById('senderPhone').value.trim();
        const rName = document.getElementById('receiverName').value.trim();
        const rPhone = document.getElementById('receiverPhone').value.trim();

        if (!sName || !sPhone || !rName || !rPhone) {
          alert('Please fill sender and receiver names and phone numbers.');
          return;
        }
        if (!sPhone.match(/^(0|254|\+254)[0-9]{9}$/)) {
          alert('Please enter a valid sender phone (e.g. 0712345678)');
          return;
        }
        if (!rPhone.match(/^(0|254|\+254)[0-9]{9}$/)) {
          alert('Please enter a valid receiver phone (e.g. 0712345678)');
          return;
        }
        updateStep(3);
      });

      document.getElementById('backToStep2').addEventListener('click', function() {
        updateStep(2);
      });

      // Real-time updates
      document.getElementById('weight').addEventListener('input', function() {
        calculateTotal();
        if (currentStep === 3) updateReviewDetails();
      });

      document.getElementById('declaredValue').addEventListener('input', function() {
        calculateTotal();
        if (currentStep === 3) updateReviewDetails();
      });

      document.getElementById('insuranceRequired').addEventListener('change', function() {
        calculateTotal();
        if (currentStep === 3) updateReviewDetails();
      });

      document.querySelectorAll('#senderName, #senderPhone, #senderEmail, #senderAddress, #receiverName, #receiverPhone, #receiverEmail, #receiverAddress, #parcelContent, #instructions, #fromTown, #toTown, #pickupPoint, #dropoffPoint, #parcelType, #packageType, #weight, #declaredValue, #insuranceRequired').forEach(el => {
        el.addEventListener('input', function() {
          if (currentStep === 3) updateReviewDetails();
        });
        el.addEventListener('change', function() {
          if (currentStep === 3) updateReviewDetails();
        });
      });

      // Form submit - check authentication before submitting
      document.getElementById('bookingForm').addEventListener('submit', function(e) {
        if (!isLoggedIn) {
          e.preventDefault();
          alert('Please login or register to book a parcel.');
          return;
        }
        // Ensure customer_id is set
        if (currentUser) {
          document.getElementById('customerId').value = currentUser.id;
        }
        // Form will submit normally
      });

      // ============================================
      // INITIALIZE
      // ============================================
      // Check login status with server
      checkLoginStatus();
      updateStep(1);
      calculateTotal();
    })();
  </script>
</body>

</html>