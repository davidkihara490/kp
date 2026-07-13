<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking · Karibu Parcels</title>
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
      <link rel="icon" type="image/png" href="{{ asset('logo.jpeg') }}"> <!-- jQuery -->

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #f2f5f9;
      color: #1e293b;
      padding-top: 80px;
    }
    .success-container { max-width: 1000px; margin: 2rem auto; }
    .success-card {
      background: #ffffff;
      border-radius: 40px;
      padding: 2.8rem 2.8rem 2.5rem;
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.02);
      backdrop-filter: blur(2px);
      transition: all 0.2s;
    }
    /* header badge */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1.4rem;
      border-radius: 60px;
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 0.3px;
      background: #f1f5f9;
      color: #1e293b;
      border: 1px solid #e2e8f0;
    }
    .status-badge .dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      display: inline-block;
    }
    .dot.pending { background: #f59e0b; }
    .dot.paid { background: #22c55e; }
    .dot.failed { background: #ef4444; }
    .dot.processing { background: #3b82f6; }

    .tracking-number {
      background: #f8fafc;
      padding: 0.9rem 2rem;
      border-radius: 60px;
      border: 1.5px dashed #cbd5e1;
      display: inline-block;
    }
    .tracking-number h4 {
      font-weight: 700;
      color: #0f172a;
      letter-spacing: 2px;
      font-size: 1.6rem;
      margin: 0;
    }

    .detail-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.25rem;
    }
    .detail-item {
      background: #f8fafc;
      border-radius: 20px;
      padding: 1.25rem 1.25rem 1rem;
      border: 1px solid #eef2f6;
      transition: 0.1s ease;
    }
    .detail-item .label {
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #64748b;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .detail-item .value {
      font-weight: 600;
      font-size: 1rem;
      margin-top: 4px;
      color: #0f172a;
    }
    .detail-item .sub {
      font-size: 0.85rem;
      color: #475569;
    }
    .btn-pay {
      background: linear-gradient(145deg, #0b2b1e, #1a4d33);
      border: none;
      padding: 14px 36px;
      border-radius: 60px;
      font-weight: 600;
      color: white;
      transition: all 0.25s;
      box-shadow: 0 8px 20px -8px rgba(26, 77, 51, 0.3);
      width: 100%;  /* full width */
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 14px 28px -8px rgba(26, 77, 51, 0.4); color: white; }
    .btn-pay:disabled { opacity: 0.6; transform: none; box-shadow: none; }
    .btn-print {
      background: #06d3fc;
      border: 1px solid #e2e8f0;
      padding: 14px 30px;
      border-radius: 60px;
      font-weight: 600;
      color: #1e293b;
      transition: 0.2s;
      width: 100%;  /* full width */
    }
    .btn-print:hover { background: #06d3fc; color: #0f172a; }
    .btn-outline-secondary-custom {
      border: 1px solid #e2e8f0;
      background: transparent;
      border-radius: 60px;
      padding: 0.65rem 1.5rem;
      font-weight: 500;
      color: #475569;
      transition: 0.2s;
    }
    .btn-outline-secondary-custom:hover { background: #f1f5f9; border-color: #cbd5e1; }

    /* ---------- STEPS with RED BORDER (reduced spacing) ---------- */
    .post-payment-steps {
      border: 2px solid #dc2626;
      border-radius: 24px;
      padding: 0.8rem 1.5rem 0.5rem;
      background: #fefaf9;
      margin-top: 1.5rem;
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.05);
    }
    .post-payment-steps .steps-title {
      font-weight: 700;
      font-size: 0.95rem;
      color: #0b3b2a;
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 0.5rem;
    }
    .post-payment-steps .steps-title i {
      color: #0b3b2a;
      background: #e2f0eb;
      padding: 4px 8px;
      border-radius: 40px;
      font-size: 1rem;
    }
    .step-list {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
      padding-left: 0.1rem;
    }
    .step-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 0.88rem;
      color: #1e293b;
      padding: 2px 0;
      border-bottom: 1px dashed #f1e6e6;
    }
    .step-item:last-child { border-bottom: 0; }
    .step-number {
      background: #dc2626;
      color: white;
      font-weight: 700;
      font-size: 0.7rem;
      width: 22px;
      height: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 30px;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .step-item .step-text {
      line-height: 1.4;
      padding-top: 1px;
    }
    .step-item .step-text strong { color: #0b3b2a; }

    /* MODAL STYLING */
    .stk-modal .modal-content {
      border-radius: 32px;
      border: none;
      box-shadow: 0 40px 80px -20px rgba(0,0,0,0.25);
      overflow: hidden;
    }
    .stk-header {
      background: linear-gradient(145deg, #0f2a1e, #1b3f2c);
      color: white;
      padding: 2rem 2rem 1.5rem;
      text-align: center;
    }
    .stk-header i { font-size: 2.8rem; color: #86efac; background: rgba(134, 239, 172, 0.12); padding: 0.8rem; border-radius: 60px; }
    .stk-header h4 { font-weight: 700; letter-spacing: -0.3px; }
    .stk-body { padding: 2rem 2rem 1rem; }
    .stk-amount-display {
      background: #f0fdf4;
      border-radius: 20px;
      padding: 0.9rem 1.5rem;
      display: flex;
      justify-content: space-between;
      font-weight: 600;
      border: 1px solid #bbf7d0;
    }
    .stk-field label { font-weight: 600; font-size: 0.85rem; color: #1e293b; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 6px; }
    .stk-field .form-control {
      border-radius: 20px;
      padding: 12px 18px;
      border: 1.5px solid #e2e8f0;
      font-size: 1rem;
      transition: 0.15s;
    }
    .stk-field .form-control:focus { border-color: #1a4d33; box-shadow: 0 0 0 4px rgba(26, 77, 51, 0.08); }
    .stk-field .input-group-text {
      background: white;
      border: 1.5px solid #e2e8f0;
      border-right: none;
      border-radius: 20px 0 0 20px;
      font-weight: 600;
    }
    .stk-field .input-group .form-control { border-left: none; border-radius: 0 20px 20px 0; }

    /* manual payment steps */
    .manual-payment-steps {
      background: #fafcff;
      border-radius: 24px;
      padding: 1.25rem 1.5rem;
      border: 1px solid #e9edf2;
      margin-top: 1.5rem;
    }
    .manual-payment-steps h6 {
      font-weight: 700;
      font-size: 0.85rem;
      color: #1e293b;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .manual-payment-steps .step-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 0.9rem;
      padding: 6px 0;
      color: #334155;
    }
    .manual-payment-steps .step-item .step-num {
      background: #eef2f6;
      width: 22px;
      height: 22px;
      border-radius: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.7rem;
      font-weight: 700;
      color: #1e293b;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .manual-payment-steps .highlight {
      background: #f1f5f9;
      padding: 0.15rem 0.6rem;
      border-radius: 30px;
      font-weight: 600;
      color: #0f172a;
      font-size: 0.85rem;
      border: 1px solid #e2e8f0;
    }
    .stk-footer {
      padding: 1.5rem 2rem 2rem;
      border-top: 1px solid #eef2f6;
      display: flex;
      gap: 12px;
      justify-content: flex-end;
      flex-wrap: wrap;
    }
    .stk-footer .btn { padding: 12px 32px; border-radius: 60px; font-weight: 600; }
    .stk-footer .btn-primary {
      background: linear-gradient(145deg, #0b2b1e, #1a4d33);
      border: none;
    }
    .stk-footer .btn-primary:hover { background: #1a4d33; transform: scale(0.98); }

    /* overlay */
    .payment-overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(8px);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      padding: 1.5rem;
    }
    .payment-overlay.active { display: flex; }
    .payment-overlay .spinner-container {
      background: white;
      padding: 2.8rem 2.5rem;
      border-radius: 40px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      box-shadow: 0 40px 80px -20px rgba(0,0,0,0.4);
    }
    .payment-overlay .spinner-container .spinner-border { width: 3.8rem; height: 3.8rem; color: #1a4d33; }
    .payment-overlay .spinner-container .status-icon { font-size: 4.2rem; }
    .payment-overlay .spinner-container .status-icon.success { color: #22c55e; }
    .payment-overlay .spinner-container .status-icon.failed { color: #ef4444; }
    .payment-overlay .spinner-container .status-icon.waiting { color: #f59e0b; }

    @media print {
      body { padding-top: 0; background: white; }
      .no-print { display: none !important; }
      .success-card { box-shadow: none; border: 1px solid #ddd; padding: 1.5rem; }
    }
    @media (max-width: 640px) {
      .success-card { padding: 1.5rem; }
      .stk-header { padding: 1.5rem; }
      .stk-body { padding: 1.5rem; }
      .stk-footer { flex-direction: column; }
      .stk-footer .btn { width: 100%; }
      .detail-grid { grid-template-columns: 1fr 1fr; }
      .post-payment-steps { padding: 0.8rem 1rem 0.5rem; }
    }
  </style>
</head>
<body>
<div class="container">
  <div class="success-container">
    <div class="success-card">
      <!-- header: dynamic title + status -->
      <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
          <h2 class="fw-bold mb-0" id="pageTitle">Booking Being Processed</h2>
          <p class="text-muted small" id="pageSubtitle">Please complete payment to confirm your booking</p>
        </div>
        <span class="status-badge" id="statusBadge">
          <span class="dot pending"></span>
          <span id="statusLabel">Pending</span>
        </span>
      </div>

      <!-- tracking number -->
      <div class="text-center mt-2">
        <div class="tracking-number">
          <small class="text-muted d-block" style="font-size:0.7rem; letter-spacing:0.5px;">TRACKING NUMBER</small>
          <h4>{{ $parcel->parcel_id }}</h4>
        </div>
      </div>

      <!-- parcel details grid -->
      <div class="detail-grid mt-4">
        <div class="detail-item"><span class="label"><i class="bi bi-geo-alt-fill text-primary"></i> From</span><div class="value">{{ $parcel->senderTown->name ?? 'N/A' }}</div><div class="sub">{{ $parcel->senderPickUpDropOffPoint->name ?? 'N/A' }}</div><div class="sub">{{ $parcel->senderPickUpDropOffPoint->address ?? 'N/A' }}</div></div>
        <div class="detail-item"><span class="label"><i class="bi bi-geo-alt-fill text-danger"></i> To</span><div class="value">{{ $parcel->receiverTown->name ?? 'N/A' }}</div><div class="sub">{{ $parcel->deliveryStation->name ?? 'N/A' }}</div><div class="sub">{{ $parcel->deliveryStation->address ?? 'N/A' }}</div></div>
        <div class="detail-item"><span class="label"><i class="bi bi-box"></i> Type</span><div class="value">{{ ucfirst($parcel->parcel_type) }}</div></div>
        <div class="detail-item"><span class="label"><i class="bi bi-weight-scale"></i> Weight</span><div class="value">{{ $parcel->weight }} kg</div></div>
        <div class="detail-item"><span class="label"><i class="bi bi-coin"></i> Amount</span><div class="value text-success fw-bold">KES {{ number_format($parcel->total_amount, 2) }}</div></div>
      </div>

      <!-- sender & receiver -->
      <div class="row g-3 mt-3">
        <div class="col-md-6"><div class="detail-item"><span class="label"><i class="bi bi-person text-primary"></i> Sender</span><div class="value">{{ $parcel->sender_name }}</div><div class="sub">{{ $parcel->sender_phone }}</div><div class="sub">{{ $parcel->sender_email }}</div></div></div>
        <div class="col-md-6"><div class="detail-item"><span class="label"><i class="bi bi-person text-danger"></i> Receiver</span><div class="value">{{ $parcel->receiver_name }}</div><div class="sub">{{ $parcel->receiver_phone }}</div><div class="sub">{{ $parcel->receiver_email }}</div></div></div>
      </div>

      @if($parcel->content_description)
      <div class="mt-3"><div class="detail-item"><span class="label"><i class="bi bi-file-text"></i> Content</span><div class="value">{{ $parcel->content_description }}</div>@if($parcel->special_instructions)<div class="sub"><i class="bi bi-info-circle me-1"></i>{{ $parcel->special_instructions }}</div>@endif</div></div>
      @endif

      <!-- ========== PAYMENT BUTTON (full width) ========== -->
      <div class="row g-3 mt-4 no-print">
        <div class="col-12">
          @if($parcel->payment_status == 'pending')
            <button class="btn btn-pay" id="payNowBtn"><i class="bi bi-phone me-2"></i> Pay with M-PESA</button>
          @else
            <button class="btn btn-success w-100" disabled><i class="bi bi-check-circle me-2"></i> Payment Completed</button>
          @endif
        </div>
      </div>

      <!-- ========== STEPS with RED BORDER ========== -->
      <div class="post-payment-steps no-print">
        <div class="steps-title">
          <i class="bi bi-check-circle-fill"></i>
          <span>What to do next</span>
        </div>
        <div class="step-list">
          <div class="step-item"><span class="step-number">1</span><span class="step-text"><strong>You have successfully booked your parcel</strong> — keep your tracking number safe.</span></div>
          <div class="step-item"><span class="step-number">2</span><span class="step-text"><strong>Kindly package it properly.</strong> Karibu parcels will not accept improperly packaged parcels.</span></div>
          <div class="step-item"><span class="step-number">3</span><span class="step-text"><strong>Print the sticker</strong> by clicking the <strong>“Print Sticker”</strong> button below. If you do not have a printer, write a paper with the exact details.</span></div>
          <div class="step-item"><span class="step-number">4</span><span class="step-text"><strong>Drop it at your origin Pick up and Drop off point.</strong> (see address above)</span></div>
          <div class="step-item"><span class="step-number">5</span><span class="step-text"><strong>Allow it to be inspected</strong> and then leave.</span></div>
          <div class="step-item"><span class="step-number">6</span><span class="step-text"><strong>We will take care of the rest.</strong> 🚚</span></div>
        </div>
      </div>

      <!-- ========== PRINT STICKER (full width, below steps) ========== -->
      @if ($parcel->payment_status == 'paid') 
      <div class="row g-3 mt-3 no-print">
        <div class="col-12">
          <a target="_blank" href="{{ route('print-customer-receipt', $parcel->id) }}" class="btn btn-print"><i class="bi bi-printer me-2"></i> Print Sticker</a>
        </div>
      </div>
      @endif

      <!-- Track & New Booking -->
      <div class="text-center mt-3 no-print">
        <a href="#" class="btn btn-outline-secondary-custom me-2"><i class="bi bi-box-seam me-2"></i>Track</a>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary-custom"><i class="bi bi-plus-circle me-2"></i>New Booking</a>
      </div>
    </div>
  </div>
</div>

<!-- Payment Overlay -->
<div class="payment-overlay" id="paymentOverlay">
  <div class="spinner-container">
    <div id="paymentStatusIcon"><div class="spinner-border" role="status"></div></div>
    <h5 id="paymentOverlayTitle" class="mt-3">Processing Payment</h5>
    <p id="paymentOverlayMessage" class="text-muted">Please wait while we initiate your payment…</p>
    <div id="paymentOverlayAction" class="mt-3" style="display:none;">
      <button class="btn btn-primary" id="paymentRetryBtn"><i class="bi bi-arrow-repeat me-2"></i>Try Again</button>
      <button class="btn btn-outline-secondary ms-2" id="paymentCancelBtn"><i class="bi bi-x-circle me-2"></i>Cancel</button>
    </div>
  </div>
</div>

<!-- STK Modal -->
<div class="modal fade stk-modal" id="stkModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="stk-header">
        <i class="bi bi-phone"></i>
        <h4>M-PESA Payment</h4>
        <p class="mb-0 opacity-75">You will receive a prompt on your phone</p>
      </div>
      <div class="stk-body">
        <div class="stk-amount-display"><span><i class="bi bi-coin me-1"></i> Amount</span><span id="stkAmountDisplay">KES {{ number_format($parcel->total_amount, 2) }}</span></div>
        <div class="stk-field mt-3">
          <label><i class="bi bi-cash-coin"></i> Amount (KES)</label>
          <div class="input-group"><span class="input-group-text">KES</span><input type="number" class="form-control" id="stkAmount" value="{{ $parcel->total_amount }}" min="1" step="1" readonly></div>
        </div>
        <div class="stk-field">
          <label><i class="bi bi-phone"></i> M-PESA Phone Number</label>
          <input type="tel" class="form-control" id="stkPhone" placeholder="0712345678" value="{{ $parcel->sender_phone ?? '0712345678' }}">
          <small class="text-muted">Ensure this number is registered with M-PESA</small>
        </div>
        <div id="paymentMessage" class="mt-2"></div>

        <!-- manual payment steps (new) -->
        <div class="manual-payment-steps">
          <h6><i class="bi bi-info-circle-fill text-success"></i> Manual M-PESA Payment</h6>
          <div class="step-item"><span class="step-num">1</span> Go to M-PESA <strong>Lipa Na M-PESA</strong> &amp; select <strong>Paybill</strong></div>
          <div class="step-item"><span class="step-num">2</span> Enter Business number <span class="highlight">4563911</span></div>
          <div class="step-item"><span class="step-num">3</span> Enter Account number <span class="highlight">{{ $parcel->parcel_id }}</span></div>
          <div class="step-item"><span class="step-num">4</span> Enter Amount <span class="highlight">KES {{ number_format($parcel->total_amount, 2) }}</span> and complete</div>
          <div class="step-item"><span class="step-num">5</span> Payment will be confirmed automatically</div>
        </div>
      </div>
      <div class="stk-footer">
        <button type="button" class="btn btn-outline-secondary" id="stkCancelBtn" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="stkPayBtn"><i class="bi bi-check-circle me-2"></i> Pay Now</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // DOM refs
  const pageTitle = document.getElementById('pageTitle');
  const pageSubtitle = document.getElementById('pageSubtitle');
  const statusBadge = document.getElementById('statusBadge');
  const statusLabel = document.getElementById('statusLabel');
  const dot = statusBadge.querySelector('.dot');

  const payBtn = document.getElementById('payNowBtn');
  const stkModalEl = document.getElementById('stkModal');
  const stkModal = new bootstrap.Modal(stkModalEl);
  const stkPayBtn = document.getElementById('stkPayBtn');
  const stkCancelBtn = document.getElementById('stkCancelBtn');
  const stkAmount = document.getElementById('stkAmount');
  const stkPhone = document.getElementById('stkPhone');
  const stkAmountDisplay = document.getElementById('stkAmountDisplay');
  const paymentMessage = document.getElementById('paymentMessage');

  const overlay = document.getElementById('paymentOverlay');
  const overlayTitle = document.getElementById('paymentOverlayTitle');
  const overlayMsg = document.getElementById('paymentOverlayMessage');
  const overlayIcon = document.getElementById('paymentStatusIcon');
  const overlayAction = document.getElementById('paymentOverlayAction');
  const retryBtn = document.getElementById('paymentRetryBtn');
  const cancelOverlayBtn = document.getElementById('paymentCancelBtn');

  let checkoutRequestId = null;
  let pollingInterval = null;
  let isPolling = false;
  let statusCheckCount = 0;
  const MAX_CHECKS = 60;

  // helper: update header based on status
  function updateHeader(status) {
    const map = {
      pending: { title: 'Booking Being Processed', subtitle: 'Complete payment to confirm your booking', label: 'Pending', dot: 'pending' },
      paid: { title: 'Booking Confirmed', subtitle: 'Payment completed – your parcel is on the way', label: 'Paid', dot: 'paid' },
      failed: { title: 'Booking Payment Failed', subtitle: 'Please try again or contact support', label: 'Failed', dot: 'failed' },
      processing: { title: 'Booking Being Processed', subtitle: 'Payment in progress…', label: 'Processing', dot: 'processing' }
    };
    const s = map[status] || map.pending;
    pageTitle.textContent = s.title;
    pageSubtitle.textContent = s.subtitle;
    statusLabel.textContent = s.label;
    dot.className = 'dot ' + s.dot;
    // also badge background
    statusBadge.style.background = status === 'paid' ? '#f0fdf4' : status === 'failed' ? '#fef2f2' : '#f1f5f9';
  }

  // initial status
  let currentStatus = '{{ $parcel->payment_status }}';
  updateHeader(currentStatus);

  // show/hide overlay
  function showOverlay(title, message, status = 'loading') {
    overlay.classList.add('active');
    overlayAction.style.display = 'none';
    if (status === 'loading') {
      overlayIcon.innerHTML = `<div class="spinner-border" role="status"></div>`;
      overlayTitle.textContent = title || 'Processing';
      overlayMsg.textContent = message || 'Please wait…';
    } else if (status === 'success') {
      overlayIcon.innerHTML = `<i class="bi bi-check-circle-fill status-icon success"></i>`;
      overlayTitle.textContent = title || 'Payment Successful!';
      overlayMsg.textContent = message || 'Your booking is confirmed.';
      overlayAction.style.display = 'none';
    } else if (status === 'failed') {
      overlayIcon.innerHTML = `<i class="bi bi-x-circle-fill status-icon failed"></i>`;
      overlayTitle.textContent = title || 'Payment Failed';
      overlayMsg.textContent = message || 'Please try again.';
      overlayAction.style.display = 'block';
    } else if (status === 'waiting') {
      overlayIcon.innerHTML = `<i class="bi bi-phone status-icon waiting"></i>`;
      overlayTitle.textContent = title || 'Waiting for PIN';
      overlayMsg.textContent = message || 'Check your phone and enter PIN.';
      overlayAction.style.display = 'none';
    }
  }
  function hideOverlay() { overlay.classList.remove('active'); }

  // polling
  function checkStatus() {
    if (!isPolling || !checkoutRequestId) return;
    statusCheckCount++;
    fetch('/api/check-payment-status', {
      method: 'POST',
      headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken,'X-Requested-With':'XMLHttpRequest' },
      credentials: 'same-origin',
      body: JSON.stringify({ parcel_id: '{{ $parcel->parcel_id }}', checkout_request_id: checkoutRequestId })
    })
    .then(r => r.json())
    .then(data => {
      const rc = data.result_code;
      if (rc === 0 || data.success) {
        handleSuccess(data);
      } else if (rc === 1032) {
        handleCancelled(data);
      } else if (rc === 1037) {
        if (statusCheckCount >= MAX_CHECKS) handleTimeout(data);
      } else if (rc === 1 || rc === 1019 || rc === 1036 || rc === 2001 || rc === 1031 || rc === 1026) {
        handleFailure(data);
      } else {
        if (statusCheckCount >= MAX_CHECKS) {
          showOverlay('Payment Unknown', 'Unable to verify. Check transaction history.', 'failed');
          stopPolling();
        } else {
          overlayMsg.textContent = `Waiting for confirmation… (${statusCheckCount}/${MAX_CHECKS})`;
        }
      }
    })
    .catch(() => { if (statusCheckCount >= MAX_CHECKS) { showOverlay('Error', 'Network issue. Please retry.', 'failed'); stopPolling(); } });
  }

  function startPolling() {
    if (isPolling) return;
    isPolling = true;
    statusCheckCount = 0;
    showOverlay('Waiting for PIN', 'Check your phone and enter M-PESA PIN.', 'waiting');
    if (pollingInterval) clearInterval(pollingInterval);
    pollingInterval = setInterval(checkStatus, 5000);
    setTimeout(checkStatus, 1200);
  }
  function stopPolling() {
    isPolling = false;
    if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
  }

  function handleSuccess(data) {
    showOverlay('Payment Successful!', data.message || 'Your booking is confirmed.', 'success');
    updateHeader('paid');
    payBtn.disabled = true;
    payBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Payment Completed';
    stopPolling();
    setTimeout(() => window.location.reload(), 2800);
  }
  function handleFailure(data) { showOverlay('Payment Failed', data.message || 'Please try again.', 'failed'); stopPolling(); }
  function handleCancelled(data) { showOverlay('Payment Cancelled', data.message || 'You did not enter PIN.', 'failed'); stopPolling(); }
  function handleTimeout(data) { showOverlay('Payment Timeout', data.message || 'Too long. Please retry.', 'failed'); stopPolling(); }

  // event: pay button
  payBtn?.addEventListener('click', () => stkModal.show());

  // stk pay
  stkPayBtn.addEventListener('click', function() {
    const amount = stkAmount.value.trim();
    const phone = stkPhone.value.trim();
    const msgDiv = paymentMessage;
    if (!amount || parseFloat(amount) <= 0) { showPaymentMsg(msgDiv, 'Enter valid amount.', 'warning'); return; }
    if (!phone.match(/^(0|254|\+254)[0-9]{9}$/)) { showPaymentMsg(msgDiv, 'Enter valid M-PESA number (e.g. 0712345678)', 'warning'); return; }

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing…';
    msgDiv.innerHTML = '';
    showOverlay('Initiating Payment', 'Please wait…', 'loading');

    fetch('/api/process-payment', {
      method: 'POST',
      headers: { 'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrfToken,'X-Requested-With':'XMLHttpRequest' },
      credentials: 'same-origin',
      body: JSON.stringify({ parcel_id: '{{ $parcel->parcel_id }}', amount: parseFloat(amount), phone, payment_method: 'mpesa' })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        checkoutRequestId = data.checkout_request_id;
        stkModal.hide();
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
        startPolling();
      } else {
        hideOverlay();
        showPaymentMsg(msgDiv, data.message || 'Payment initiation failed.', 'danger');
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
      }
    })
    .catch(err => {
      hideOverlay();
      showPaymentMsg(msgDiv, err.message || 'Network error.', 'danger');
      this.disabled = false;
      this.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
    });
  });

  // cancel modal & overlay
  stkCancelBtn.addEventListener('click', () => { stopPolling(); });
  cancelOverlayBtn.addEventListener('click', () => { stopPolling(); hideOverlay(); });
  retryBtn.addEventListener('click', () => { hideOverlay(); stkModal.show(); });

  function showPaymentMsg(container, message, type) {
    const cls = { success:'alert-success', danger:'alert-danger', warning:'alert-warning', info:'alert-info' }[type] || 'alert-info';
    container.innerHTML = `<div class="alert ${cls} alert-dismissible fade show"><i class="bi ${type==='success'?'bi-check-circle-fill':type==='danger'?'bi-exclamation-triangle-fill':'bi-info-circle-fill'} me-2"></i> ${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
  }

  // auto-poll if pending payment exists
  @if($parcel->payment_status == 'pending')
  fetch('/api/get-payment-status/{{ $parcel->parcel_id }}', {
    headers: { 'Accept':'application/json','X-CSRF-TOKEN':csrfToken,'X-Requested-With':'XMLHttpRequest' },
    credentials: 'same-origin'
  })
  .then(r => r.json())
  .then(data => {
    if (data.success && data.payment_status === 'pending' && data.checkout_request_id) {
      checkoutRequestId = data.checkout_request_id;
      startPolling();
    }
  }).catch(() => {});
  @endif

  window.addEventListener('beforeunload', stopPolling);
});
</script>
</body>
</html>