<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Karibu Parcels - Book Your Parcel</title>
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="{{ asset('favicon.jpeg') }}"> <!-- jQuery -->

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
      max-width: 1100px;
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

    .btn-danger {
      border-radius: 50px;
      padding: 8px 16px;
      font-weight: 600;
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
      color: var(--accent-color);
      font-size: 0.9rem;
    }

    .user-info-bar .logout-btn:hover {
      text-decoration: underline;
    }

    .user-info-bar .guest-text {
      color: var(--text-light);
      font-weight: 500;
    }

    /* Station list - scrollable radio cards */
    .station-list {
      max-height: 270px;
      overflow-y: auto;
      border: 2px solid #e9edf2;
      border-radius: 20px;
      padding: 0.75rem 0.25rem 0.25rem 0.25rem;
      background: #fafcff;
      scroll-behavior: smooth;
    }

    .station-list::-webkit-scrollbar {
      width: 6px;
    }

    .station-list::-webkit-scrollbar-track {
      background: #eef2f6;
      border-radius: 10px;
    }

    .station-list::-webkit-scrollbar-thumb {
      background: #b9c4d0;
      border-radius: 10px;
    }

    .station-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      margin: 0 0.25rem 0.25rem 0.25rem;
      border-radius: 16px;
      background: white;
      border: 1.5px solid transparent;
      transition: all 0.15s;
      cursor: pointer;
    }

    .station-item:hover {
      background: #f2f7ff;
      border-color: #dbe7f5;
    }

    .station-item.selected {
      background: #e7f3e7;
      border-color: #008f40;
      box-shadow: 0 2px 8px rgba(0, 143, 64, 0.08);
    }

    .station-item .form-check-input {
      margin-top: 4px;
      flex-shrink: 0;
      width: 1.2rem;
      height: 1.2rem;
      border-radius: 50%;
      border: 2px solid #cbd5e1;
      transition: 0.15s;
      cursor: pointer;
    }

    .station-item .form-check-input:checked {
      background-color: #008f40;
      border-color: #008f40;
    }

    .station-item .station-info {
      flex: 1;
      line-height: 1.4;
    }

    .station-item .station-name {
      font-weight: 600;
      font-size: 0.95rem;
      color: #0b1e33;
    }

    .station-item .station-address {
      font-size: 0.82rem;
      color: #475569;
      margin-top: 2px;
    }

    .station-item .station-meta {
      font-size: 0.75rem;
      color: #64748b;
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem 1.2rem;
      margin-top: 6px;
    }

    .station-item .station-meta i {
      margin-right: 4px;
      width: 16px;
    }

    .station-item .badge-pickup {
      background: #dff0d8;
      color: #1e6f3f;
      font-weight: 500;
      font-size: 0.7rem;
      padding: 0.2rem 0.7rem;
      border-radius: 30px;
      margin-left: 0.25rem;
    }

    /* Multiple items styles */
    .item-card {
      background: #f8f9fa;
      border-radius: 16px;
      padding: 1.25rem;
      margin-bottom: 1rem;
      border: 2px solid var(--border-color);
      position: relative;
      transition: var(--transition);
    }

    .item-card:hover {
      border-color: var(--primary-color);
      box-shadow: var(--shadow-md);
    }

    .item-card .item-number {
      position: absolute;
      top: -12px;
      left: 16px;
      background: var(--primary-color);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .item-card .remove-item-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: none;
      border: none;
      color: var(--accent-color);
      font-size: 1.2rem;
      cursor: pointer;
      padding: 4px 8px;
      border-radius: 50%;
      transition: var(--transition);
    }

    .item-card .remove-item-btn:hover {
      background: #fee2e2;
      transform: scale(1.1);
    }

    .add-item-btn {
      width: 100%;
      padding: 12px;
      border: 2px dashed var(--border-color);
      border-radius: 16px;
      background: transparent;
      color: var(--text-light);
      font-weight: 500;
      transition: var(--transition);
      cursor: pointer;
    }

    .add-item-btn:hover {
      border-color: var(--primary-color);
      color: var(--primary-color);
      background: var(--primary-light);
    }

    .items-summary {
      background: var(--primary-light);
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      margin-top: 0.5rem;
      border: 2px solid var(--primary-color);
    }

    .item-insurance-label {
      font-size: 0.85rem;
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

      .station-item {
        padding: 0.65rem 0.75rem;
      }

      .item-card {
        padding: 1rem;
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

        <div id="guestTopActions">
          <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
            <i class="bi bi-person-plus me-1"></i> Register
          </button>
        </div>

        <div id="customerTopActions" class="d-none">
          <button type="button" class="btn btn-outline-danger btn-sm" id="logoutBtnTop">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
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
          <h5 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Pickup & dropoff locations</h5>
          <div class="row g-4">

            <!-- Pickup Section -->
            <div class="col-md-6">
              <!-- From Town -->
              <div class="mb-3">
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

              <!-- Pickup Stations -->
              <label class="fw-semibold mb-2"><i class="bi bi-box-arrow-up text-primary me-1"></i> Pickup Station <span class="text-danger">*</span></label>
              <div class="station-list" id="pickupStationList">
                @if(isset($pickupPoints) && count($pickupPoints) > 0)
                @foreach($pickupPoints as $point)
                <div class="station-item" data-id="{{ $point->id }}" data-type="pickup">
                  <input type="radio" class="form-check-input" name="pickupStation" value="{{ $point->id }}">
                  <div class="station-info">
                    <div class="station-name">{{ $point->name }} </div>
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
                @endforeach
                @else
                <div class="text-muted p-3">No pickup stations available</div>
                @endif
              </div>
              <input type="hidden" name="sender_pick_up_drop_off_point_id" id="pickupPointHidden" value="">
            </div>

            <!-- Dropoff Section -->
            <div class="col-md-6">
              <!-- To Town -->
              <div class="mb-3">
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

              <!-- Dropoff Stations -->
              <label class="fw-semibold mb-2"><i class="bi bi-box-arrow-down text-danger me-1"></i> Dropoff Station <span class="text-danger">*</span></label>
              <div class="station-list" id="dropoffStationList">
                @if(isset($dropoffPoints) && count($dropoffPoints) > 0)
                @foreach($dropoffPoints as $point)
                <div class="station-item" data-id="{{ $point->id }}" data-type="dropoff">
                  <input type="radio" class="form-check-input" name="dropoffStation" value="{{ $point->id }}">
                  <div class="station-info">
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
                @endforeach
                @else
                <div class="text-muted p-3">No dropoff stations available</div>
                @endif
              </div>
              <input type="hidden" name="delivery_pick_up_drop_off_point_id" id="dropoffPointHidden" value="">
            </div>
          </div>

          <!-- Items Section -->
          <div class="mt-4">
            <h5 class="fw-semibold mb-3"><i class="bi bi-cube me-2 text-primary"></i>Items in this shipment</h5>

            <div id="itemsContainer">
              <!-- Item 1 (default) -->
              <div class="item-card" data-item-index="0">
                <span class="item-number">Item #1</span>
                <button type="button" class="remove-item-btn" onclick="removeItem(0)" style="display: none;">
                  <i class="bi bi-x-circle-fill"></i>
                </button>
                <div class="row g-3">
                  <div class="col-md-3">
                    <label class="form-label">Parcel category <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[0][parcel_category_id]" required>
                      @foreach($categories as $category)
                      <option value="{{ $category->id }}" data-price="{{ $price }}" {{ $category->id == $parcelCategoryId ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Parcel type <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[0][parcel_type]" required>
                      <option value="document">Document</option>
                      <option value="package" selected>Package</option>
                      <option value="envelope">Envelope</option>
                      <option value="box">Box</option>
                      <option value="pallet">Pallet</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Package type <span class="text-danger">*</span></label>
                    <select class="form-select" name="items[0][package_type]" required>
                      <option value="regular">Regular</option>
                      <option value="fragile">Fragile</option>
                      <option value="perishable">Perishable</option>
                      <option value="valuable">Valuable</option>
                      <option value="hazardous">Hazardous</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label">Declared Value (KES)</label>
                    <input type="number" class="form-control item-declared-value" name="items[0][declared_value]" placeholder="e.g., 5000" min="0" step="1" value="0">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Content description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="items[0][content_description]" placeholder="Describe this item" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Special notes (optional)</label>
                    <input type="text" class="form-control" name="items[0][special_notes]" placeholder="Any special notes for this item">
                  </div>
                  <div class="col-md-12">
                    <div class="form-check">
                      <input class="form-check-input item-insurance" type="checkbox" name="items[0][insurance_required]" value="1" id="insurance0">
                      <label class="form-check-label item-insurance-label" for="insurance0">
                        Add insurance for this item (2% of declared value) - <span class="text-muted">KES <span class="item-insurance-amount" id="insuranceAmount0">0</span></span>
                      </label>
                    </div>
                  </div>
                  <!-- Add this after the insurance checkbox in the item card -->
                  <div class="col-md-12">
                    <div class="row g-2">
                      <div class="col-md-3">
                        <small class="text-muted">Base Price:</small>
                        <div class="fw-bold text-primary" id="itemBasePrice0">KES 0</div>
                        <input type="hidden" name="items[0][base_price]" id="itemBasePriceHidden0" value="0">
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted">Insurance:</small>
                        <div class="fw-bold text-success" id="itemInsurance0">KES 0</div>
                        <input type="hidden" name="items[0][item_insurance_amount]" id="itemInsuranceHidden0" value="0">
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted">Tax (16%):</small>
                        <div class="fw-bold text-warning" id="itemTax0">KES 0</div>
                        <input type="hidden" name="items[0][tax_amount]" id="itemTaxHidden0" value="0">
                      </div>
                      <div class="col-md-3">
                        <small class="text-muted">Total:</small>
                        <div class="fw-bold text-success" id="itemTotal0">KES 0</div>
                        <input type="hidden" name="items[0][item_total]" id="itemTotalHidden0" value="0">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <button type="button" class="add-item-btn mt-2" id="addItemBtn">
              <i class="bi bi-plus-circle me-2"></i>Add another item
            </button>
          </div>

          <!-- Price Display -->
          <div class="col-12 mt-4">
            <div class="price-display d-flex justify-content-between align-items-center">
              <span class="label">Estimated Total:</span>
              <span class="amount" id="estimatedTotal">KES {{ $price }}</span>
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

        <!-- STEP 3: Review & Confirm -->
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
              <div class="col-md-6"><span class="label">Pickup Station:</span> <span class="value" id="revPickup">-</span></div>
              <div class="col-md-6"><span class="label">Dropoff Station:</span> <span class="value" id="revDropoff">-</span></div>
              <div class="col-md-6"><span class="label">From:</span> <span class="value" id="revFrom">-</span></div>
              <div class="col-md-6"><span class="label">To:</span> <span class="value" id="revTo">-</span></div>
            </div>
          </div>

          <div class="review-summary">
            <h6><i class="bi bi-cube me-2"></i>Items</h6>
            <div id="reviewItems"></div>
          </div>

          <div class="review-summary">
            <h6><i class="bi bi-cash me-2"></i>Summary</h6>
            <div class="row">
              <div class="col-md-6"><span class="label">Total Insurance Amount:</span> <span class="value" id="revTotalInsurance">KES 0</span></div>
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
          <input type="hidden" name="payment_method" value="mpesa">
          <input type="hidden" name="payment_status" value="pending">
          <input type="hidden" name="total_amount" id="totalAmount" value="0">
          <input type="hidden" name="total_insurance_amount" id="totalInsuranceHidden" value="0">
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

  <!-- Login Modal -->
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

  <!-- Register Modal -->
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
    (() => {
      'use strict';

      let currentStep = 1;
      let isLoggedIn = false;
      let currentUser = null;
      let selectedPickup = null;
      let selectedDropoff = null;
      let itemCounter = 1;
      const basePrice = Number(@json((float) $price));

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

      const endpoints = {
        login: @json(url('/customer/login?type=api')),
        register: @json(url('/customer/register')),
        checkAuth: @json(url('/customer/check-auth')),
        logout: @json(url('/customer/logout')),
      };

      const element = (id) => document.getElementById(id);

      const stepPanels = {
        1: element('step1'),
        2: element('step2'),
        3: element('step3'),
      };

      const indicators = {
        1: element('stepIndicator1'),
        2: element('stepIndicator2'),
        3: element('stepIndicator3'),
      };

      function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
      }

      function normalizeCustomer(data) {
        return data?.user ?? data?.customer ?? null;
      }

      function customerPhone(customer) {
        return customer?.phone ?? customer?.phone_number ?? '';
      }

      function showMessage(container, message, type = 'danger') {
        container.className = `alert alert-${type}`;
        container.textContent = message;
        container.style.display = 'block';
      }

      function hideMessage(container) {
        container.style.display = 'none';
        container.textContent = '';
      }

      function setButtonLoading(button, textElement, spinnerElement, loading) {
        button.disabled = loading;
        textElement.style.display = loading ? 'none' : 'inline';
        spinnerElement.style.display = loading ? 'inline-block' : 'none';
      }

      async function parseResponse(response) {
        const contentType = response.headers.get('content-type') ?? '';
        const payload = contentType.includes('application/json') ?
          await response.json() : {
            message: await response.text()
          };

        if (!response.ok) {
          if (payload.errors) {
            const messages = Object.values(payload.errors).flat();
            throw new Error(messages.join(' '));
          }
          throw new Error(payload.message || `Request failed with status ${response.status}.`);
        }
        return payload;
      }

      // ---- Item Management ----
      function addItem() {
        const container = element('itemsContainer');
        const index = itemCounter;
        itemCounter++;

        // Get the category options from the first item (including data attributes)
        const firstCategorySelect = container.querySelector('select[name*="parcel_category_id"]');
        const categoryOptions = firstCategorySelect ? firstCategorySelect.innerHTML : '';

        const itemHtml = `
    <div class="item-card" data-item-index="${index}">
      <span class="item-number">Item #${index + 1}</span>
      <button type="button" class="remove-item-btn" onclick="removeItem(${index})">
        <i class="bi bi-x-circle-fill"></i>
      </button>
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Parcel category <span class="text-danger">*</span></label>
          <select class="form-select" name="items[${index}][parcel_category_id]" required>
            ${categoryOptions}
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Parcel type <span class="text-danger">*</span></label>
          <select class="form-select" name="items[${index}][parcel_type]" required>
            <option value="document">Document</option>
            <option value="package" selected>Package</option>
            <option value="envelope">Envelope</option>
            <option value="box">Box</option>
            <option value="pallet">Pallet</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Package type <span class="text-danger">*</span></label>
          <select class="form-select" name="items[${index}][package_type]" required>
            <option value="regular">Regular</option>
            <option value="fragile">Fragile</option>
            <option value="perishable">Perishable</option>
            <option value="valuable">Valuable</option>
            <option value="hazardous">Hazardous</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Declared Value (KES)</label>
          <input type="number" class="form-control item-declared-value" name="items[${index}][declared_value]" placeholder="e.g., 5000" min="0" step="1" value="0">
        </div>
        <div class="col-md-6">
          <label class="form-label">Content description <span class="text-danger">*</span></label>
          <input type="text" class="form-control" name="items[${index}][content_description]" placeholder="Describe this item" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Special notes (optional)</label>
          <input type="text" class="form-control" name="items[${index}][special_notes]" placeholder="Any special notes for this item">
        </div>
        <div class="col-md-12">
          <div class="form-check">
            <input class="form-check-input item-insurance" type="checkbox" name="items[${index}][insurance_required]" value="1" id="insurance${index}">
            <label class="form-check-label item-insurance-label" for="insurance${index}">
              Add insurance for this item (2% of declared value) - <span class="text-muted">KES <span class="item-insurance-amount" id="insuranceAmount${index}">0</span></span>
            </label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row g-2">
            <div class="col-md-3">
              <small class="text-muted">Base Price:</small>
              <div class="fw-bold text-primary" id="itemBasePrice${index}">KES 0</div>
              <input type="hidden" name="items[${index}][base_price]" id="itemBasePriceHidden${index}" value="0">
            </div>
            <div class="col-md-3">
              <small class="text-muted">Insurance:</small>
              <div class="fw-bold text-success" id="itemInsurance${index}">KES 0</div>
              <input type="hidden" name="items[${index}][item_insurance_amount]" id="itemInsuranceHidden${index}" value="0">
            </div>
            <div class="col-md-3">
              <small class="text-muted">Tax (16%):</small>
              <div class="fw-bold text-warning" id="itemTax${index}">KES 0</div>
              <input type="hidden" name="items[${index}][tax_amount]" id="itemTaxHidden${index}" value="0">
            </div>
            <div class="col-md-3">
              <small class="text-muted">Total:</small>
              <div class="fw-bold text-success" id="itemTotal${index}">KES 0</div>
              <input type="hidden" name="items[${index}][item_total]" id="itemTotalHidden${index}" value="0">
            </div>
          </div>
        </div>
      </div>
    </div>
  `;

        container.insertAdjacentHTML('beforeend', itemHtml);

        // Add event listeners to new item
        const newItem = container.querySelector(`.item-card[data-item-index="${index}"]`);
        const declaredValueInput = newItem.querySelector('.item-declared-value');
        const insuranceCheckbox = newItem.querySelector('.item-insurance');
        const categorySelect = newItem.querySelector('select[name*="parcel_category_id"]');
        const parcelTypeSelect = newItem.querySelector('select[name*="parcel_type"]');
        const packageTypeSelect = newItem.querySelector('select[name*="package_type"]');

        // Add event listeners for all inputs that affect pricing
        declaredValueInput.addEventListener('input', () => {
          calculateTotal();
          if (currentStep === 3) updateReviewDetails();
        });

        insuranceCheckbox.addEventListener('change', () => {
          calculateTotal();
          if (currentStep === 3) updateReviewDetails();
        });

        [categorySelect, parcelTypeSelect, packageTypeSelect].forEach(select => {
          select.addEventListener('change', () => {
            calculateTotal();
            if (currentStep === 3) updateReviewDetails();
          });
        });

        // Show remove button if more than 1 item
        updateRemoveButtons();
        calculateTotal();
      }

      // Make removeItem globally accessible
      window.removeItem = function(index) {
        const container = element('itemsContainer');
        const items = container.querySelectorAll('.item-card');

        if (items.length <= 1) {
          alert('You must have at least one item.');
          return;
        }

        const itemToRemove = container.querySelector(`.item-card[data-item-index="${index}"]`);
        if (itemToRemove) {
          itemToRemove.remove();
          // Renumber remaining items
          renumberItems();
          updateRemoveButtons();
          calculateTotal();
          if (currentStep === 3) updateReviewDetails();
        }
      };

      function renumberItems() {
        const container = element('itemsContainer');
        const items = container.querySelectorAll('.item-card');

        items.forEach((item, idx) => {
          const numberSpan = item.querySelector('.item-number');
          if (numberSpan) {
            numberSpan.textContent = `Item #${idx + 1}`;
          }
          item.dataset.itemIndex = idx;

          // Update name attributes
          const inputs = item.querySelectorAll('select, input');
          inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
              const newName = name.replace(/items\[\d+\]/, `items[${idx}]`);
              input.setAttribute('name', newName);
            }
          });

          // Update insurance checkbox id and label for
          const insuranceCheckbox = item.querySelector('.item-insurance');
          if (insuranceCheckbox) {
            insuranceCheckbox.id = `insurance${idx}`;
            const label = item.querySelector(`label[for="insurance${idx}"]`);
            if (label) {
              // Update the for attribute on the label
              const newLabel = item.querySelector(`label[for^="insurance"]`);
              if (newLabel) {
                newLabel.setAttribute('for', `insurance${idx}`);
              }
            }
          }

          // Update insurance amount span id
          const insuranceAmountSpan = item.querySelector('.item-insurance-amount');
          if (insuranceAmountSpan) {
            insuranceAmountSpan.id = `insuranceAmount${idx}`;
          }
        });
      }

      function updateRemoveButtons() {
        const container = element('itemsContainer');
        const items = container.querySelectorAll('.item-card');
        const removeButtons = container.querySelectorAll('.remove-item-btn');

        removeButtons.forEach(btn => {
          if (items.length <= 1) {
            btn.style.display = 'none';
          } else {
            btn.style.display = 'block';
          }
        });
      }

      // ---- Initialize station selection ----
      function initializeStations() {
        const pickupItems = document.querySelectorAll('#pickupStationList .station-item');
        pickupItems.forEach((item) => {
          const radio = item.querySelector('input[type="radio"]');
          if (!radio) return;

          item.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'radio') return;
            const radioInput = this.querySelector('input[type="radio"]');
            if (radioInput) {
              radioInput.checked = true;
              radioInput.dispatchEvent(new Event('change'));
            }
          });

          radio.addEventListener('change', function(e) {
            e.stopPropagation();
            const parent = this.closest('.station-list');
            parent.querySelectorAll('.station-item').forEach(it => it.classList.remove('selected'));
            const item = this.closest('.station-item');
            if (this.checked) {
              item.classList.add('selected');
              const id = parseInt(this.value);
              selectedPickup = id;
              element('pickupPointHidden').value = id;
            } else {
              item.classList.remove('selected');
              selectedPickup = null;
              element('pickupPointHidden').value = '';
            }
            if (currentStep === 3) updateReviewDetails();
          });
        });

        const dropoffItems = document.querySelectorAll('#dropoffStationList .station-item');
        dropoffItems.forEach((item) => {
          const radio = item.querySelector('input[type="radio"]');
          if (!radio) return;

          item.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'radio') return;
            const radioInput = this.querySelector('input[type="radio"]');
            if (radioInput) {
              radioInput.checked = true;
              radioInput.dispatchEvent(new Event('change'));
            }
          });

          radio.addEventListener('change', function(e) {
            e.stopPropagation();
            const parent = this.closest('.station-list');
            parent.querySelectorAll('.station-item').forEach(it => it.classList.remove('selected'));
            const item = this.closest('.station-item');
            if (this.checked) {
              item.classList.add('selected');
              const id = parseInt(this.value);
              selectedDropoff = id;
              element('dropoffPointHidden').value = id;
            } else {
              item.classList.remove('selected');
              selectedDropoff = null;
              element('dropoffPointHidden').value = '';
            }
            if (currentStep === 3) updateReviewDetails();
          });
        });

        setTimeout(() => {
          const firstPickup = document.querySelector('#pickupStationList .station-item');
          if (firstPickup) {
            const radio = firstPickup.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change'));
            }
          }
          const firstDropoff = document.querySelector('#dropoffStationList .station-item');
          if (firstDropoff) {
            const radio = firstDropoff.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change'));
            }
          }
        }, 100);
      }

      function updateUIForLoggedInUser(customer) {
        if (!customer) {
          updateUIForLoggedOutUser();
          return;
        }

        const safeName = escapeHtml(customer.name || 'Customer');
        const safeEmail = escapeHtml(customer.email || '');

        element('userInfoDisplay').innerHTML = `
          <span class="user-name">
            <i class="bi bi-person-check-fill me-2"></i>Welcome, ${safeName}!
          </span>
          ${safeEmail ? `<span class="text-muted ms-2 small">(${safeEmail})</span>` : ''}
        `;

        element('guestTopActions').classList.add('d-none');
        element('customerTopActions').classList.remove('d-none');

        element('authStatusText').textContent = `You are logged in as ${customer.name || 'Customer'}`;
        element('authActionText').textContent = '';
        element('authButtons').style.display = 'none';
        element('loggedInActions').style.display = 'inline-block';
        element('loggedInName').textContent = customer.name || 'Customer';

        element('saveParcelBtn').disabled = false;
        element('saveParcelBtn').innerHTML = '<i class="bi bi-check-circle me-1"></i> Confirm & Book';
        element('customerId').value = customer.id ?? '';

        if (customer.name) element('senderName').value = customer.name;
        if (customer.email) element('senderEmail').value = customer.email;
        if (customerPhone(customer)) element('senderPhone').value = customerPhone(customer);

        currentUser = customer;
        isLoggedIn = true;
      }

      function updateUIForLoggedOutUser() {
        element('userInfoDisplay').innerHTML = `
          <span class="guest-text">
            <i class="bi bi-person-circle me-2"></i>You are browsing as a guest
          </span>
        `;

        element('guestTopActions').classList.remove('d-none');
        element('customerTopActions').classList.add('d-none');

        element('authStatusText').textContent = 'You are browsing as a guest';
        element('authActionText').textContent = ' - Please login or register to complete booking';
        element('authButtons').style.display = 'inline-block';
        element('loggedInActions').style.display = 'none';

        element('saveParcelBtn').disabled = true;
        element('saveParcelBtn').innerHTML = '<i class="bi bi-lock me-1"></i> Please Login to Book';
        element('customerId').value = '';

        currentUser = null;
        isLoggedIn = false;
      }

      async function handleLogin(event) {
        event?.preventDefault();

        const email = element('loginEmail').value.trim();
        const password = element('loginPassword').value;
        const message = element('loginMessage');
        const button = element('loginSubmitBtn');

        hideMessage(message);

        if (!email || !password) {
          showMessage(message, 'Please enter your email address and password.', 'warning');
          return;
        }

        setButtonLoading(button, element('loginBtnText'), element('loginBtnSpinner'), true);

        try {
          const response = await fetch(endpoints.login, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              email,
              password,
              remember: element('rememberMe').checked,
            }),
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (!data.success || !customer) {
            throw new Error(data.message || 'Login succeeded, but customer details were not returned.');
          }

          updateUIForLoggedInUser(customer);
          updateReviewDetails();
          showMessage(message, data.message || 'Login successful.', 'success');

          bootstrap.Modal.getOrCreateInstance(element('loginModal')).hide();
          element('loginForm').reset();
          hideMessage(message);
        } catch (error) {
          console.error('Login error:', error);
          showMessage(message, error.message || 'Login failed. Please try again.', 'danger');
        } finally {
          setButtonLoading(button, element('loginBtnText'), element('loginBtnSpinner'), false);
        }
      }

      async function handleRegister(event) {
        event?.preventDefault();

        const name = element('regName').value.trim();
        const email = element('regEmail').value.trim();
        const phone = element('regPhone').value.trim();
        const password = element('regPassword').value;
        const passwordConfirmation = element('regConfirmPassword').value;
        const termsAccepted = element('regTerms').checked;
        const message = element('registerMessage');
        const button = element('registerSubmitBtn');

        hideMessage(message);

        if (!name || !email || !phone || !password || !passwordConfirmation) {
          showMessage(message, 'Please fill in all required fields.', 'warning');
          return;
        }

        if (password !== passwordConfirmation) {
          showMessage(message, 'Passwords do not match.', 'warning');
          return;
        }

        if (password.length < 8) {
          showMessage(message, 'Password must contain at least 8 characters.', 'warning');
          return;
        }

        if (!/^(0|254|\+254)[0-9]{9}$/.test(phone)) {
          showMessage(message, 'Please enter a valid Kenyan phone number, for example 0712345678.', 'warning');
          return;
        }

        if (!termsAccepted) {
          showMessage(message, 'Please agree to the terms and conditions.', 'warning');
          return;
        }

        setButtonLoading(button, element('registerBtnText'), element('registerBtnSpinner'), true);

        try {
          const response = await fetch(endpoints.register, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              name,
              email,
              phone,
              password,
              password_confirmation: passwordConfirmation,
              terms: true,
            }),
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (!data.success || !customer) {
            throw new Error(data.message || 'Registration succeeded, but customer details were not returned.');
          }

          updateUIForLoggedInUser(customer);
          updateReviewDetails();
          showMessage(message, data.message || 'Registration successful.', 'success');

          bootstrap.Modal.getOrCreateInstance(element('registerModal')).hide();
          element('registerForm').reset();
          hideMessage(message);
        } catch (error) {
          console.error('Registration error:', error);
          showMessage(message, error.message || 'Registration failed. Please try again.', 'danger');
        } finally {
          setButtonLoading(button, element('registerBtnText'), element('registerBtnSpinner'), false);
        }
      }

      async function checkLoginStatus() {
        try {
          const response = await fetch(endpoints.checkAuth, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (data.authenticated && customer) {
            updateUIForLoggedInUser(customer);
          } else {
            updateUIForLoggedOutUser();
          }
        } catch (error) {
          console.error('Authentication check failed:', error);
          updateUIForLoggedOutUser();
        }
      }

      async function handleLogout() {
        try {
          const response = await fetch(endpoints.logout, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
          });

          await parseResponse(response);
        } catch (error) {
          console.error('Logout error:', error);
        } finally {
          updateUIForLoggedOutUser();
          element('loginForm').reset();
          element('registerForm').reset();
        }
      }

      function updateStep(step) {
        Object.values(stepPanels).forEach((panel) => panel.classList.remove('active'));
        stepPanels[step]?.classList.add('active');

        for (let index = 1; index <= 3; index++) {
          indicators[index].classList.remove('active', 'completed');

          if (index < step) indicators[index].classList.add('completed');
          if (index === step) indicators[index].classList.add('active');
        }

        currentStep = step;

        if (step === 3) {
          updateReviewDetails();
        }

        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }

      function calculateItemCost(item, index) {
        const declaredValue = Number.parseFloat(item.querySelector('.item-declared-value')?.value) || 0;
        const insuranceRequired = item.querySelector('.item-insurance')?.checked || false;
        const insuranceAmount = insuranceRequired ? declaredValue * 0.02 : 0;

        // Get base price from the selected category's data attribute (NOT affected by declared value)
        let basePrice = 100; // Default fallback
        const categorySelect = item.querySelector('select[name*="parcel_category_id"]');
        if (categorySelect) {
          const selectedOption = categorySelect.options[categorySelect.selectedIndex];
          // Get price from data attribute
          const categoryPrice = selectedOption?.dataset?.price;
          if (categoryPrice && !isNaN(parseFloat(categoryPrice))) {
            basePrice = parseFloat(categoryPrice);
          }
        }

        // Add premium for package type (affects base price)
        const packageType = item.querySelector('select[name*="package_type"]');
        if (packageType) {
          const type = packageType.value;
          switch (type) {
            case 'fragile':
            case 'valuable':
              basePrice *= 1.2;
              break;
            case 'hazardous':
              basePrice *= 1.5;
              break;
            case 'perishable':
              basePrice *= 1.3;
              break;
          }
        }

        // Round base price to 2 decimal places
        basePrice = Math.round(basePrice * 100) / 100;

        // Calculate tax (16% of base price + insurance)
        const taxAmount = (basePrice + insuranceAmount) * 0.16;
        const total = basePrice + insuranceAmount + taxAmount;

        return {
          basePrice: Math.round(basePrice),
          insuranceAmount: Math.round(insuranceAmount),
          taxAmount: Math.round(taxAmount),
          total: Math.round(total),
          declaredValue: Math.round(declaredValue)
        };
      }

      function updateItemCostDisplay(item, index) {
        const costs = calculateItemCost(item, index);

        // Update display
        const basePriceEl = document.getElementById(`itemBasePrice${index}`);
        const insuranceEl = document.getElementById(`itemInsurance${index}`);
        const taxEl = document.getElementById(`itemTax${index}`);
        const totalEl = document.getElementById(`itemTotal${index}`);

        if (basePriceEl) basePriceEl.textContent = `KES ${costs.basePrice}`;
        if (insuranceEl) insuranceEl.textContent = `KES ${costs.insuranceAmount}`;
        if (taxEl) taxEl.textContent = `KES ${costs.taxAmount}`;
        if (totalEl) totalEl.textContent = `KES ${costs.total}`;

        // Update hidden inputs
        document.getElementById(`itemBasePriceHidden${index}`).value = costs.basePrice;
        document.getElementById(`itemInsuranceHidden${index}`).value = costs.insuranceAmount;
        document.getElementById(`itemTaxHidden${index}`).value = costs.taxAmount;
        document.getElementById(`itemTotalHidden${index}`).value = costs.total;
      }

      function calculateTotal() {
        const items = document.querySelectorAll('.item-card');
        let totalInsuranceAmount = 0;
        let totalDeclaredValue = 0;
        let grandTotal = 0;
        const itemCount = items.length;

        items.forEach((item, idx) => {
          // Update cost display for each item
          updateItemCostDisplay(item, idx);

          const declaredValue = Number.parseFloat(item.querySelector('.item-declared-value')?.value) || 0;
          const insuranceRequired = item.querySelector('.item-insurance')?.checked || false;
          const insuranceAmount = insuranceRequired ? declaredValue * 0.02 : 0;

          totalDeclaredValue += declaredValue;
          totalInsuranceAmount += insuranceAmount;

          // Get item total from hidden input
          const itemTotal = Number.parseFloat(document.getElementById(`itemTotalHidden${idx}`)?.value) || 0;
          grandTotal += itemTotal;

          // Update individual insurance amount display
          const insuranceSpan = item.querySelector('.item-insurance-amount');
          if (insuranceSpan) {
            insuranceSpan.textContent = insuranceAmount.toFixed(0);
          }
        });

        element('estimatedTotal').textContent = `KES ${grandTotal.toFixed(0)}`;
        element('totalAmount').value = grandTotal.toFixed(2);
        element('totalInsuranceHidden').value = totalInsuranceAmount.toFixed(2);

        return {
          grandTotal,
          totalInsuranceAmount,
          totalDeclaredValue,
          itemCount
        };
      }

      function selectedText(selectId) {
        const select = element(selectId);
        return select.options[select.selectedIndex]?.text?.trim() || '—';
      }

      function getStationName(id) {
        if (!id) return '—';
        const allStations = [...document.querySelectorAll('#pickupStationList .station-item, #dropoffStationList .station-item')];
        for (let item of allStations) {
          if (parseInt(item.dataset.id) === id) {
            const nameEl = item.querySelector('.station-name');
            if (nameEl) {
              const badgeEl = nameEl.querySelector('.badge-pickup');
              if (badgeEl) badgeEl.remove();
              return nameEl.textContent.trim() || '—';
            }
          }
        }
        return '—';
      }

      function updateReviewDetails() {
        element('revFrom').textContent = selectedText('fromTown');
        element('revTo').textContent = selectedText('toTown');
        element('revPickup').textContent = getStationName(selectedPickup);
        element('revDropoff').textContent = getStationName(selectedDropoff);

        // Review items
        const itemsContainer = element('itemsContainer');
        const items = itemsContainer.querySelectorAll('.item-card');
        let itemsHtml = '';
        let totalInsurance = 0;

        items.forEach((item, idx) => {
          const categoryName = item.querySelector('select[name*="parcel_category_id"] option:checked')?.textContent || '—';
          const parcelType = item.querySelector('select[name*="parcel_type"] option:checked')?.textContent || '—';
          const packageType = item.querySelector('select[name*="package_type"] option:checked')?.textContent || '—';
          const content = item.querySelector('input[name*="content_description"]')?.value || '—';
          const value = item.querySelector('input[name*="declared_value"]')?.value || '0';
          const notes = item.querySelector('input[name*="special_notes"]')?.value || '';
          const insuranceChecked = item.querySelector('.item-insurance')?.checked || false;
          const insuranceAmount = insuranceChecked ? Number(value) * 0.02 : 0;

          totalInsurance += insuranceAmount;

          itemsHtml += `
            <div class="row mb-3">
              <div class="col-12">
                <strong>Item #${idx + 1}:</strong> 
                ${content}
                <br>
                <small class="text-muted">
                  Category: ${categoryName} | Type: ${parcelType} | Package: ${packageType} | Value: KES ${value}
                  ${insuranceChecked ? `| <span class="text-success">Insurance: KES ${insuranceAmount.toFixed(0)}</span>` : '| <span class="text-muted">No insurance</span>'}
                  ${notes ? `| Notes: ${notes}` : ''}
                </small>
              </div>
            </div>
          `;
        });

        element('reviewItems').innerHTML = itemsHtml || '<div class="text-muted">No items</div>';

        const totals = calculateTotal();
        element('revTotalInsurance').textContent = `KES ${totals.totalInsuranceAmount.toFixed(0)}`;
        element('revTotal').textContent = `KES ${totals.total.toFixed(0)}`;

        element('revSender').textContent = element('senderName').value.trim() || '—';
        element('revSenderPhone').textContent = element('senderPhone').value.trim() || '—';
        element('revSenderEmail').textContent = element('senderEmail').value.trim() || '—';
        element('revReceiver').textContent = element('receiverName').value.trim() || '—';
        element('revReceiverPhone').textContent = element('receiverPhone').value.trim() || '—';
        element('revReceiverEmail').textContent = element('receiverEmail').value.trim() || '—';
      }

      function validKenyanPhone(phone) {
        return /^(0|254|\+254)[0-9]{9}$/.test(phone);
      }

      function validateItems() {
        const items = document.querySelectorAll('.item-card');
        let isValid = true;

        items.forEach((item, idx) => {
          const content = item.querySelector('input[name*="content_description"]');

          if (!content?.value.trim()) {
            alert(`Item #${idx + 1}: Please describe the content.`);
            isValid = false;
            return;
          }
        });

        return isValid;
      }

      function bindEvents() {
        element('loginSubmitBtn').addEventListener('click', handleLogin);
        element('loginForm').addEventListener('submit', handleLogin);
        element('registerSubmitBtn').addEventListener('click', handleRegister);
        element('registerForm').addEventListener('submit', handleRegister);
        element('logoutBtn').addEventListener('click', handleLogout);
        element('logoutBtnTop').addEventListener('click', handleLogout);

        // Add item button
        element('addItemBtn').addEventListener('click', addItem);

        // Add event listeners to existing declared value inputs and insurance checkboxes
        document.querySelectorAll('.item-declared-value').forEach(input => {
          input.addEventListener('input', () => {
            calculateTotal();
            if (currentStep === 3) updateReviewDetails();
          });
        });

        document.querySelectorAll('.item-insurance').forEach(checkbox => {
          checkbox.addEventListener('change', () => {
            calculateTotal();
            if (currentStep === 3) updateReviewDetails();
          });
        });

        element('toStep2').addEventListener('click', () => {
          if (!selectedPickup || !selectedDropoff) {
            alert('Please select both a pickup and a dropoff station.');
            return;
          }

          if (!validateItems()) {
            return;
          }

          updateStep(2);
        });

        element('backToStep1').addEventListener('click', () => updateStep(1));
        element('backToStep2').addEventListener('click', () => updateStep(2));

        element('toStep3').addEventListener('click', () => {
          const senderName = element('senderName').value.trim();
          const senderPhone = element('senderPhone').value.trim();
          const receiverName = element('receiverName').value.trim();
          const receiverPhone = element('receiverPhone').value.trim();

          if (!senderName || !senderPhone || !receiverName || !receiverPhone) {
            alert('Please enter the sender and receiver names and phone numbers.');
            return;
          }

          if (!validKenyanPhone(senderPhone)) {
            alert('Please enter a valid sender phone number, for example 0712345678.');
            return;
          }

          if (!validKenyanPhone(receiverPhone)) {
            alert('Please enter a valid receiver phone number, for example 0712345678.');
            return;
          }

          updateStep(3);
        });

        // Reactive fields for review updates
        ['senderName', 'senderPhone', 'senderEmail',
          'receiverName', 'receiverPhone', 'receiverEmail',
          'fromTown', 'toTown'
        ].forEach((id) => {
          ['input', 'change'].forEach((eventName) => {
            element(id).addEventListener(eventName, () => {
              if (currentStep === 3) updateReviewDetails();
            });
          });
        });

        element('bookingForm').addEventListener('submit', (event) => {
          if (!isLoggedIn || !currentUser) {
            event.preventDefault();
            alert('Please login or register before booking the parcel.');
            bootstrap.Modal.getOrCreateInstance(element('loginModal')).show();
            return;
          }

          if (!element('termsCheck').checked) {
            event.preventDefault();
            alert('Please confirm the parcel information and accept the terms.');
            return;
          }

          if (!validateItems()) {
            event.preventDefault();
            return;
          }

          element('customerId').value = currentUser.id ?? '';
          calculateTotal();
          element('saveParcelBtn').disabled = true;
          element('saveParcelBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Booking parcel...';
        });
      }

      // ---- Initialize ----
      function init() {
        initializeStations();
        bindEvents();
        updateStep(1);
        calculateTotal();
        checkLoginStatus();
        updateRemoveButtons();
      }

      document.addEventListener('DOMContentLoaded', init);
    })();
  </script>
</body>

</html>

{{--<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Karibu Parcels - Book Your Parcel</title>
<!-- Bootstrap 5 + Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('favicon.jpeg') }}"> <!-- jQuery -->

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
    color: var(--accent-color);
    font-size: 0.9rem;
  }

  .user-info-bar .logout-btn:hover {
    text-decoration: underline;
  }

  .user-info-bar .guest-text {
    color: var(--text-light);
    font-weight: 500;
  }

  /* Station list - scrollable radio cards */
  .station-list {
    max-height: 270px;
    overflow-y: auto;
    border: 2px solid #e9edf2;
    border-radius: 20px;
    padding: 0.75rem 0.25rem 0.25rem 0.25rem;
    background: #fafcff;
    scroll-behavior: smooth;
  }

  .station-list::-webkit-scrollbar {
    width: 6px;
  }

  .station-list::-webkit-scrollbar-track {
    background: #eef2f6;
    border-radius: 10px;
  }

  .station-list::-webkit-scrollbar-thumb {
    background: #b9c4d0;
    border-radius: 10px;
  }

  .station-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    margin: 0 0.25rem 0.25rem 0.25rem;
    border-radius: 16px;
    background: white;
    border: 1.5px solid transparent;
    transition: all 0.15s;
    cursor: pointer;
  }

  .station-item:hover {
    background: #f2f7ff;
    border-color: #dbe7f5;
  }

  .station-item.selected {
    background: #e7f3e7;
    border-color: #008f40;
    box-shadow: 0 2px 8px rgba(0, 143, 64, 0.08);
  }

  .station-item .form-check-input {
    margin-top: 4px;
    flex-shrink: 0;
    width: 1.2rem;
    height: 1.2rem;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    transition: 0.15s;
    cursor: pointer;
  }

  .station-item .form-check-input:checked {
    background-color: #008f40;
    border-color: #008f40;
  }

  .station-item .station-info {
    flex: 1;
    line-height: 1.4;
  }

  .station-item .station-name {
    font-weight: 600;
    font-size: 0.95rem;
    color: #0b1e33;
  }

  .station-item .station-address {
    font-size: 0.82rem;
    color: #475569;
    margin-top: 2px;
  }

  .station-item .station-meta {
    font-size: 0.75rem;
    color: #64748b;
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem 1.2rem;
    margin-top: 6px;
  }

  .station-item .station-meta i {
    margin-right: 4px;
    width: 16px;
  }

  .station-item .badge-pickup {
    background: #dff0d8;
    color: #1e6f3f;
    font-weight: 500;
    font-size: 0.7rem;
    padding: 0.2rem 0.7rem;
    border-radius: 30px;
    margin-left: 0.25rem;
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

    .station-item {
      padding: 0.65rem 0.75rem;
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

        <div id="guestTopActions">
          <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="bi bi-box-arrow-in-right me-1"></i> Login
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#registerModal">
            <i class="bi bi-person-plus me-1"></i> Register
          </button>
        </div>

        <div id="customerTopActions" class="d-none">
          <button type="button" class="btn btn-outline-danger btn-sm" id="logoutBtnTop">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
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
          <h5 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Pickup & dropoff locations</h5>
          <div class="row g-4">

            <!-- Pickup Section -->
            <div class="col-md-6">
              <!-- From Town -->
              <div class="mb-3">
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

              <!-- Pickup Stations -->
              <label class="fw-semibold mb-2"><i class="bi bi-box-arrow-up text-primary me-1"></i> Pickup Station <span class="text-danger">*</span></label>
              <div class="station-list" id="pickupStationList">
                <!-- Rendered directly from database via Blade -->
                @if(isset($pickupPoints) && count($pickupPoints) > 0)
                @foreach($pickupPoints as $point)
                <div class="station-item" data-id="{{ $point->id }}" data-type="pickup">
                  <input type="radio" class="form-check-input" name="pickupStation" value="{{ $point->id }}">
                  <div class="station-info">
                    <div class="station-name">{{ $point->name }} </div>
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
                @endforeach
                @else
                <div class="text-muted p-3">No pickup stations available</div>
                @endif
              </div>
              <input type="hidden" name="sender_pick_up_drop_off_point_id" id="pickupPointHidden" value="">
            </div>

            <!-- Dropoff Section -->
            <div class="col-md-6">
              <!-- To Town -->
              <div class="mb-3">
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

              <!-- Dropoff Stations -->
              <label class="fw-semibold mb-2"><i class="bi bi-box-arrow-down text-danger me-1"></i> Dropoff Station <span class="text-danger">*</span></label>
              <div class="station-list" id="dropoffStationList">
                <!-- Rendered directly from database via Blade -->
                @if(isset($dropoffPoints) && count($dropoffPoints) > 0)
                @foreach($dropoffPoints as $point)
                <div class="station-item" data-id="{{ $point->id }}" data-type="dropoff">
                  <input type="radio" class="form-check-input" name="dropoffStation" value="{{ $point->id }}">
                  <div class="station-info">
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
                @endforeach
                @else
                <div class="text-muted p-3">No dropoff stations available</div>
                @endif
              </div>
              <input type="hidden" name="delivery_pick_up_drop_off_point_id" id="dropoffPointHidden" value="">
            </div>

            <!-- Parcel Details -->
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
              <label class="form-label">Parcel category</label>
              <input type="hidden" name="parcel_category_id" value="{{ $parcelCategoryId }}">
              <select class="form-select" id="parcelCategoryId" disabled>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $parcelCategoryId ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>


            <div class="col-md-4">
              <label class="form-label">Declared Value (KES)</label>
              <input type="number" class="form-control" name="declared_value" id="declaredValue" placeholder="e.g., 5000" min="0" step="1" value="0">
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
              <input type="text" class="form-control" name="content_description" id="parcelContent" placeholder="Describe what you are sending" required>
            </div>
            <span class="text-muted small"> Karibu Parcels Limited and its agents has the right to reject the prohibited items</span>

            <div class="col-12">
              <label class="form-label">Special instructions (optional)</label>
              <input type="text" class="form-control" name="special_instructions" id="instructions" placeholder="Fragile, handle with care">
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

        <!-- STEP 3: Review & Confirm -->
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
              <div class="col-md-6"><span class="label">Pickup Station:</span> <span class="value" id="revPickup">-</span></div>
              <div class="col-md-6"><span class="label">Dropoff Station:</span> <span class="value" id="revDropoff">-</span></div>
              <div class="col-md-6"><span class="label">From:</span> <span class="value" id="revFrom">-</span></div>
              <div class="col-md-6"><span class="label">To:</span> <span class="value" id="revTo">-</span></div>
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

  <!-- Login Modal -->
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

  <!-- Register Modal -->
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
    (() => {
      'use strict';

      let currentStep = 1;
      let isLoggedIn = false;
      let currentUser = null;
      let selectedPickup = null;
      let selectedDropoff = null;

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

      const endpoints = {
        login: @json(url('/customer/login?type=api')),
        register: @json(url('/customer/register')),
        checkAuth: @json(url('/customer/check-auth')),
        logout: @json(url('/customer/logout')),
      };

      const element = (id) => document.getElementById(id);

      const stepPanels = {
        1: element('step1'),
        2: element('step2'),
        3: element('step3'),
      };

      const indicators = {
        1: element('stepIndicator1'),
        2: element('stepIndicator2'),
        3: element('stepIndicator3'),
      };

      function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
      }

      function normalizeCustomer(data) {
        return data?.user ?? data?.customer ?? null;
      }

      function customerPhone(customer) {
        return customer?.phone ?? customer?.phone_number ?? '';
      }

      function showMessage(container, message, type = 'danger') {
        container.className = `alert alert-${type}`;
        container.textContent = message;
        container.style.display = 'block';
      }

      function hideMessage(container) {
        container.style.display = 'none';
        container.textContent = '';
      }

      function setButtonLoading(button, textElement, spinnerElement, loading) {
        button.disabled = loading;
        textElement.style.display = loading ? 'none' : 'inline';
        spinnerElement.style.display = loading ? 'inline-block' : 'none';
      }

      async function parseResponse(response) {
        const contentType = response.headers.get('content-type') ?? '';
        const payload = contentType.includes('application/json') ?
          await response.json() : {
            message: await response.text()
          };

        if (!response.ok) {
          if (payload.errors) {
            const messages = Object.values(payload.errors).flat();
            throw new Error(messages.join(' '));
          }
          throw new Error(payload.message || `Request failed with status ${response.status}.`);
        }
        return payload;
      }

      // ---- Initialize station selection from Blade-rendered HTML ----
      function initializeStations() {
        // Setup pickup stations
        const pickupItems = document.querySelectorAll('#pickupStationList .station-item');
        pickupItems.forEach((item) => {
          const radio = item.querySelector('input[type="radio"]');
          if (!radio) return;

          // Click on whole item toggles radio
          item.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'radio') return;
            const radioInput = this.querySelector('input[type="radio"]');
            if (radioInput) {
              radioInput.checked = true;
              radioInput.dispatchEvent(new Event('change'));
            }
          });

          // Radio change event
          radio.addEventListener('change', function(e) {
            e.stopPropagation();
            const parent = this.closest('.station-list');
            parent.querySelectorAll('.station-item').forEach(it => it.classList.remove('selected'));
            const item = this.closest('.station-item');
            if (this.checked) {
              item.classList.add('selected');
              const id = parseInt(this.value);
              selectedPickup = id;
              element('pickupPointHidden').value = id;
            } else {
              item.classList.remove('selected');
              selectedPickup = null;
              element('pickupPointHidden').value = '';
            }
            calculateTotal();
            if (currentStep === 3) updateReviewDetails();
          });
        });

        // Setup dropoff stations
        const dropoffItems = document.querySelectorAll('#dropoffStationList .station-item');
        dropoffItems.forEach((item) => {
          const radio = item.querySelector('input[type="radio"]');
          if (!radio) return;

          item.addEventListener('click', function(e) {
            if (e.target.tagName === 'INPUT' && e.target.type === 'radio') return;
            const radioInput = this.querySelector('input[type="radio"]');
            if (radioInput) {
              radioInput.checked = true;
              radioInput.dispatchEvent(new Event('change'));
            }
          });

          radio.addEventListener('change', function(e) {
            e.stopPropagation();
            const parent = this.closest('.station-list');
            parent.querySelectorAll('.station-item').forEach(it => it.classList.remove('selected'));
            const item = this.closest('.station-item');
            if (this.checked) {
              item.classList.add('selected');
              const id = parseInt(this.value);
              selectedDropoff = id;
              element('dropoffPointHidden').value = id;
            } else {
              item.classList.remove('selected');
              selectedDropoff = null;
              element('dropoffPointHidden').value = '';
            }
            calculateTotal();
            if (currentStep === 3) updateReviewDetails();
          });
        });

        // Auto-select first station if available
        setTimeout(() => {
          const firstPickup = document.querySelector('#pickupStationList .station-item');
          if (firstPickup) {
            const radio = firstPickup.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change'));
            }
          }
          const firstDropoff = document.querySelector('#dropoffStationList .station-item');
          if (firstDropoff) {
            const radio = firstDropoff.querySelector('input[type="radio"]');
            if (radio && !radio.checked) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change'));
            }
          }
        }, 100);
      }

      function updateUIForLoggedInUser(customer) {
        if (!customer) {
          updateUIForLoggedOutUser();
          return;
        }

        const safeName = escapeHtml(customer.name || 'Customer');
        const safeEmail = escapeHtml(customer.email || '');

        element('userInfoDisplay').innerHTML = `
          <span class="user-name">
            <i class="bi bi-person-check-fill me-2"></i>Welcome, ${safeName}!
          </span>
          ${safeEmail ? `<span class="text-muted ms-2 small">(${safeEmail})</span>` : ''}
        `;

        element('guestTopActions').classList.add('d-none');
        element('customerTopActions').classList.remove('d-none');

        element('authStatusText').textContent = `You are logged in as ${customer.name || 'Customer'}`;
        element('authActionText').textContent = '';
        element('authButtons').style.display = 'none';
        element('loggedInActions').style.display = 'inline-block';
        element('loggedInName').textContent = customer.name || 'Customer';

        element('saveParcelBtn').disabled = false;
        element('saveParcelBtn').innerHTML = '<i class="bi bi-check-circle me-1"></i> Confirm & Book';
        element('customerId').value = customer.id ?? '';

        if (customer.name) element('senderName').value = customer.name;
        if (customer.email) element('senderEmail').value = customer.email;
        if (customerPhone(customer)) element('senderPhone').value = customerPhone(customer);

        currentUser = customer;
        isLoggedIn = true;
      }

      function updateUIForLoggedOutUser() {
        element('userInfoDisplay').innerHTML = `
          <span class="guest-text">
            <i class="bi bi-person-circle me-2"></i>You are browsing as a guest
          </span>
        `;

        element('guestTopActions').classList.remove('d-none');
        element('customerTopActions').classList.add('d-none');

        element('authStatusText').textContent = 'You are browsing as a guest';
        element('authActionText').textContent = ' - Please login or register to complete booking';
        element('authButtons').style.display = 'inline-block';
        element('loggedInActions').style.display = 'none';

        element('saveParcelBtn').disabled = true;
        element('saveParcelBtn').innerHTML = '<i class="bi bi-lock me-1"></i> Please Login to Book';
        element('customerId').value = '';

        currentUser = null;
        isLoggedIn = false;
      }

      async function handleLogin(event) {
        event?.preventDefault();

        const email = element('loginEmail').value.trim();
        const password = element('loginPassword').value;
        const message = element('loginMessage');
        const button = element('loginSubmitBtn');

        hideMessage(message);

        if (!email || !password) {
          showMessage(message, 'Please enter your email address and password.', 'warning');
          return;
        }

        setButtonLoading(button, element('loginBtnText'), element('loginBtnSpinner'), true);

        try {
          const response = await fetch(endpoints.login, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              email,
              password,
              remember: element('rememberMe').checked,
            }),
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (!data.success || !customer) {
            throw new Error(data.message || 'Login succeeded, but customer details were not returned.');
          }

          updateUIForLoggedInUser(customer);
          updateReviewDetails();
          showMessage(message, data.message || 'Login successful.', 'success');

          bootstrap.Modal.getOrCreateInstance(element('loginModal')).hide();
          element('loginForm').reset();
          hideMessage(message);
        } catch (error) {
          console.error('Login error:', error);
          showMessage(message, error.message || 'Login failed. Please try again.', 'danger');
        } finally {
          setButtonLoading(button, element('loginBtnText'), element('loginBtnSpinner'), false);
        }
      }

      async function handleRegister(event) {
        event?.preventDefault();

        const name = element('regName').value.trim();
        const email = element('regEmail').value.trim();
        const phone = element('regPhone').value.trim();
        const password = element('regPassword').value;
        const passwordConfirmation = element('regConfirmPassword').value;
        const termsAccepted = element('regTerms').checked;
        const message = element('registerMessage');
        const button = element('registerSubmitBtn');

        hideMessage(message);

        if (!name || !email || !phone || !password || !passwordConfirmation) {
          showMessage(message, 'Please fill in all required fields.', 'warning');
          return;
        }

        if (password !== passwordConfirmation) {
          showMessage(message, 'Passwords do not match.', 'warning');
          return;
        }

        if (password.length < 8) {
          showMessage(message, 'Password must contain at least 8 characters.', 'warning');
          return;
        }

        if (!/^(0|254|\+254)[0-9]{9}$/.test(phone)) {
          showMessage(message, 'Please enter a valid Kenyan phone number, for example 0712345678.', 'warning');
          return;
        }

        if (!termsAccepted) {
          showMessage(message, 'Please agree to the terms and conditions.', 'warning');
          return;
        }

        setButtonLoading(button, element('registerBtnText'), element('registerBtnSpinner'), true);

        try {
          const response = await fetch(endpoints.register, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({
              name,
              email,
              phone,
              password,
              password_confirmation: passwordConfirmation,
              terms: true,
            }),
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (!data.success || !customer) {
            throw new Error(data.message || 'Registration succeeded, but customer details were not returned.');
          }

          updateUIForLoggedInUser(customer);
          updateReviewDetails();
          showMessage(message, data.message || 'Registration successful.', 'success');

          bootstrap.Modal.getOrCreateInstance(element('registerModal')).hide();
          element('registerForm').reset();
          hideMessage(message);
        } catch (error) {
          console.error('Registration error:', error);
          showMessage(message, error.message || 'Registration failed. Please try again.', 'danger');
        } finally {
          setButtonLoading(button, element('registerBtnText'), element('registerBtnSpinner'), false);
        }
      }

      async function checkLoginStatus() {
        try {
          const response = await fetch(endpoints.checkAuth, {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
          });

          const data = await parseResponse(response);
          const customer = normalizeCustomer(data);

          if (data.authenticated && customer) {
            updateUIForLoggedInUser(customer);
          } else {
            updateUIForLoggedOutUser();
          }
        } catch (error) {
          console.error('Authentication check failed:', error);
          updateUIForLoggedOutUser();
        }
      }

      async function handleLogout() {
        try {
          const response = await fetch(endpoints.logout, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
          });

          await parseResponse(response);
        } catch (error) {
          console.error('Logout error:', error);
        } finally {
          updateUIForLoggedOutUser();
          element('loginForm').reset();
          element('registerForm').reset();
        }
      }

      function updateStep(step) {
        Object.values(stepPanels).forEach((panel) => panel.classList.remove('active'));
        stepPanels[step]?.classList.add('active');

        for (let index = 1; index <= 3; index++) {
          indicators[index].classList.remove('active', 'completed');

          if (index < step) indicators[index].classList.add('completed');
          if (index === step) indicators[index].classList.add('active');
        }

        currentStep = step;

        if (step === 3) {
          updateReviewDetails();
        }

        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }

      function calculateTotal() {
        const declaredValue = Number.parseFloat(element('declaredValue').value) || 0;
        const insuranceRequired = element('insuranceRequired').checked;
        const insuranceAmount = insuranceRequired ? declaredValue * 0.02 : 0;
        const basePrice = Number(@json((float) $price));
        const total = basePrice + insuranceAmount;

        element('insuranceAmount').textContent = insuranceAmount.toFixed(0);
        element('insuranceAmountHidden').value = insuranceAmount.toFixed(2);
        element('estimatedTotal').textContent = `KES ${total.toFixed(0)}`;
        element('totalAmount').value = total.toFixed(2);

        return {
          total,
          insuranceAmount,
          declaredValue,
          insuranceRequired
        };
      }

      function selectedText(selectId) {
        const select = element(selectId);
        return select.options[select.selectedIndex]?.text?.trim() || '—';
      }

      function getStationName(id) {
        if (!id) return '—';
        const allStations = [...document.querySelectorAll('#pickupStationList .station-item, #dropoffStationList .station-item')];
        for (let item of allStations) {
          if (parseInt(item.dataset.id) === id) {
            const nameEl = item.querySelector('.station-name');
            if (nameEl) {
              const badgeEl = nameEl.querySelector('.badge-pickup');
              if (badgeEl) badgeEl.remove();
              return nameEl.textContent.trim() || '—';
            }
          }
        }
        return '—';
      }

      function updateReviewDetails() {
        element('revFrom').textContent = selectedText('fromTown');
        element('revTo').textContent = selectedText('toTown');
        element('revPickup').textContent = getStationName(selectedPickup);
        element('revDropoff').textContent = getStationName(selectedDropoff);
        element('revType').textContent = selectedText('parcelType');
        element('revPackageType').textContent = selectedText('packageType');
        element('revWeight').textContent = element('weight').value ? `${element('weight').value} kg` : '—';

        const totals = calculateTotal();
        element('revValue').textContent = `KES ${totals.declaredValue.toFixed(0)}`;
        element('revInsurance').textContent = totals.insuranceRequired ? 'Yes' : 'No';
        element('revInsuranceAmount').textContent = `KES ${totals.insuranceAmount.toFixed(0)}`;
        element('revTotal').textContent = `KES ${totals.total.toFixed(0)}`;

        element('revContent').textContent = element('parcelContent').value.trim() || '—';
        element('revInstructions').textContent = element('instructions').value.trim() || 'None';
        element('revSender').textContent = element('senderName').value.trim() || '—';
        element('revSenderPhone').textContent = element('senderPhone').value.trim() || '—';
        element('revSenderEmail').textContent = element('senderEmail').value.trim() || '—';
        element('revReceiver').textContent = element('receiverName').value.trim() || '—';
        element('revReceiverPhone').textContent = element('receiverPhone').value.trim() || '—';
        element('revReceiverEmail').textContent = element('receiverEmail').value.trim() || '—';
      }

      function validKenyanPhone(phone) {
        return /^(0|254|\+254)[0-9]{9}$/.test(phone);
      }

      function bindEvents() {
        element('loginSubmitBtn').addEventListener('click', handleLogin);
        element('loginForm').addEventListener('submit', handleLogin);
        element('registerSubmitBtn').addEventListener('click', handleRegister);
        element('registerForm').addEventListener('submit', handleRegister);
        element('logoutBtn').addEventListener('click', handleLogout);
        element('logoutBtnTop').addEventListener('click', handleLogout);

        element('toStep2').addEventListener('click', () => {
          if (!selectedPickup || !selectedDropoff) {
            alert('Please select both a pickup and a dropoff station.');
            return;
          }

          if (!element('parcelContent').value.trim()) {
            alert('Please describe the parcel contents.');
            return;
          }

          updateStep(2);
        });

        element('backToStep1').addEventListener('click', () => updateStep(1));
        element('backToStep2').addEventListener('click', () => updateStep(2));

        element('toStep3').addEventListener('click', () => {
          const senderName = element('senderName').value.trim();
          const senderPhone = element('senderPhone').value.trim();
          const receiverName = element('receiverName').value.trim();
          const receiverPhone = element('receiverPhone').value.trim();

          if (!senderName || !senderPhone || !receiverName || !receiverPhone) {
            alert('Please enter the sender and receiver names and phone numbers.');
            return;
          }

          if (!validKenyanPhone(senderPhone)) {
            alert('Please enter a valid sender phone number, for example 0712345678.');
            return;
          }

          if (!validKenyanPhone(receiverPhone)) {
            alert('Please enter a valid receiver phone number, for example 0712345678.');
            return;
          }

          updateStep(3);
        });

        const reactiveFields = [
          'senderName', 'senderPhone', 'senderEmail',
          'receiverName', 'receiverPhone', 'receiverEmail',
          'parcelContent', 'instructions', 'fromTown', 'toTown',
          'parcelType', 'packageType',
          'weight', 'declaredValue', 'insuranceRequired',
        ];

        reactiveFields.forEach((id) => {
          ['input', 'change'].forEach((eventName) => {
            element(id).addEventListener(eventName, () => {
              calculateTotal();
              if (currentStep === 3) updateReviewDetails();
            });
          });
        });

        element('bookingForm').addEventListener('submit', (event) => {
          if (!isLoggedIn || !currentUser) {
            event.preventDefault();
            alert('Please login or register before booking the parcel.');
            bootstrap.Modal.getOrCreateInstance(element('loginModal')).show();
            return;
          }

          if (!element('termsCheck').checked) {
            event.preventDefault();
            alert('Please confirm the parcel information and accept the terms.');
            return;
          }

          element('customerId').value = currentUser.id ?? '';
          calculateTotal();
          element('saveParcelBtn').disabled = true;
          element('saveParcelBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Booking parcel...';
        });
      }

      // ---- Initialize ----
      function init() {
        // Initialize stations from Blade-rendered HTML (no API call needed)
        initializeStations();

        bindEvents();
        updateStep(1);
        calculateTotal();
        checkLoginStatus();
      }

      document.addEventListener('DOMContentLoaded', init);
    })();
  </script>
</body>

</html>--}}