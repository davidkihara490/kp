<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful - Karibu Parcels</title>
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #008f40;
            --primary-dark: #007a36;
            --primary-light: #e8f5e9;
            --accent-color: #ff3519;
            --light-bg: #f8f9fa;
            --text-dark: #343a40;
            --text-light: #6c757d;
            --border-color: #e9ecef;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: var(--text-dark);
            padding-top: 80px;
        }
        .success-container {
            max-width: 900px;
            margin: 2rem auto;
        }
        .success-card {
            background: white;
            border-radius: 28px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid var(--border-color);
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .success-icon i {
            font-size: 3rem;
            color: var(--primary-color);
        }
        .tracking-number {
            background: var(--primary-light);
            padding: 1rem 2rem;
            border-radius: 16px;
            border: 2px solid var(--primary-color);
            display: inline-block;
        }
        .tracking-number h4 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }
        .detail-card {
            background: var(--light-bg);
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid var(--border-color);
        }
        .detail-card .label {
            color: var(--text-light);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .detail-card .value {
            font-weight: 600;
            font-size: 1rem;
            margin-top: 0.25rem;
        }
        .payment-status {
            padding: 0.5rem 1rem;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }
        .payment-status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .payment-status.paid {
            background: #d4edda;
            color: #155724;
        }
        .payment-status.failed {
            background: #f8d7da;
            color: #721c24;
        }
        .payment-status.processing {
            background: #cce5ff;
            color: #004085;
        }
        .btn-pay {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
            color: white;
        }
        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        .btn-print {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 117, 125, 0.3);
            color: white;
        }
        /* Modal Styles */
        .stk-modal .modal-content {
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .stk-header {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            color: white;
            border-radius: 24px 24px 0 0;
            padding: 2rem;
            text-align: center;
        }
        .stk-header i {
            font-size: 3.5rem;
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.15);
            padding: 1rem;
            border-radius: 50%;
            margin-bottom: 0.5rem;
        }
        .stk-header h4 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        .stk-header p {
            opacity: 0.8;
            margin-bottom: 0;
            font-size: 0.9rem;
        }
        .stk-body {
            padding: 2rem;
        }
        .stk-field {
            margin-bottom: 1.5rem;
        }
        .stk-field label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .stk-field label i {
            color: var(--primary-color);
        }
        .stk-field .form-control {
            border-radius: 16px;
            padding: 14px 18px;
            font-size: 1rem;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }
        .stk-field .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,143,64,0.1);
        }
        .stk-field .input-group-text {
            background: white;
            border: 2px solid var(--border-color);
            border-right: none;
            border-radius: 16px 0 0 16px;
            font-weight: 600;
        }
        .stk-field .input-group .form-control {
            border-left: none;
            border-radius: 0 16px 16px 0;
        }
        .stk-amount-display {
            background: var(--primary-light);
            border-radius: 16px;
            padding: 0.8rem 1.2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .stk-amount-display span:first-child {
            font-weight: 500;
            color: var(--text-light);
        }
        .stk-amount-display span:last-child {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        .stk-footer {
            padding: 1.5rem 2rem 2rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
        .stk-footer .btn {
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
        }
        .stk-footer .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            border: none;
        }
        .stk-footer .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }
        .stk-footer .btn-primary:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        /* Payment Processing Overlay */
        .payment-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .payment-overlay.active {
            display: flex;
        }
        .payment-overlay .spinner-container {
            background: white;
            padding: 3rem;
            border-radius: 24px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 90%;
        }
        .payment-overlay .spinner-container .spinner-border {
            width: 4rem;
            height: 4rem;
            color: var(--primary-color);
        }
        .payment-overlay .spinner-container h5 {
            margin-top: 1.5rem;
            font-weight: 600;
        }
        .payment-overlay .spinner-container p {
            color: var(--text-light);
            margin-bottom: 0;
        }
        .payment-overlay .spinner-container .status-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .payment-overlay .spinner-container .status-icon.success {
            color: #28a745;
        }
        .payment-overlay .spinner-container .status-icon.failed {
            color: #dc3545;
        }
        .payment-overlay .spinner-container .status-icon.waiting {
            color: #ffc107;
        }
        @media print {
            body { padding-top: 0; background: white; }
            .no-print { display: none !important; }
            .success-container { margin: 0; max-width: 100%; }
            .success-card { box-shadow: none; border: none; padding: 1rem; }
            .receipt-container { border: none; }
        }
        @media (max-width: 640px) {
            .success-card { padding: 1.5rem; }
            .stk-header { padding: 1.5rem; }
            .stk-body { padding: 1.5rem; }
            .stk-footer { flex-direction: column; }
            .stk-footer .btn { width: 100%; }
            .payment-overlay .spinner-container { padding: 2rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-card">
                <!-- Success Header -->
                <div class="text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Booking Confirmed!</h2>
                    <p class="text-muted">Your parcel has been booked successfully. Please complete payment to proceed.</p>
                </div>

                <!-- Tracking Number -->
                <div class="text-center mt-4">
                    <div class="tracking-number">
                        <small class="text-muted d-block">Tracking Number</small>
                        <h4>{{ $parcel->parcel_id }}</h4>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="text-center mt-3">
                    <span class="payment-status {{ $parcel->payment_status }}" id="paymentStatusBadge">
                        <i class="bi 
                            {{ $parcel->payment_status == 'paid' ? 'bi-check-circle-fill' : 
                               ($parcel->payment_status == 'pending' ? 'bi-clock-fill' : 'bi-x-circle-fill') }} 
                            me-1">
                        </i>
                        {{ ucfirst($parcel->payment_status) }}
                    </span>
                </div>

                <!-- Parcel Details -->
                <div class="row g-4 mt-4">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-geo-alt-fill me-1 text-primary"></i> From</div>
                            <div class="value">{{ $parcel->senderTown->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $parcel->sender_pick_up_drop_off_point->name ?? 'N/A' }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-geo-alt-fill me-1 text-danger"></i> To</div>
                            <div class="value">{{ $parcel->receiverTown->name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $parcel->delivery_pick_up_drop_off_point->name ?? 'N/A' }}</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-box me-1"></i> Parcel Type</div>
                            <div class="value">{{ ucfirst($parcel->parcel_type) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-weight-scale me-1"></i> Weight</div>
                            <div class="value">{{ $parcel->weight }} kg</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-coin me-1"></i> Total Amount</div>
                            <div class="value text-success fw-bold">KES {{ number_format($parcel->total_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Sender & Receiver -->
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-person me-1 text-primary"></i> Sender</div>
                            <div class="value">{{ $parcel->sender_name }}</div>
                            <small class="text-muted d-block">{{ $parcel->sender_phone }}</small>
                            <small class="text-muted d-block">{{ $parcel->sender_email }}</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-card">
                            <div class="label"><i class="bi bi-person me-1 text-danger"></i> Receiver</div>
                            <div class="value">{{ $parcel->receiver_name }}</div>
                            <small class="text-muted d-block">{{ $parcel->receiver_phone }}</small>
                            <small class="text-muted d-block">{{ $parcel->receiver_email }}</small>
                        </div>
                    </div>
                </div>

                <!-- Content Description -->
                @if($parcel->content_description)
                <div class="mt-3">
                    <div class="detail-card">
                        <div class="label"><i class="bi bi-file-text me-1"></i> Content Description</div>
                        <div class="value">{{ $parcel->content_description }}</div>
                        @if($parcel->special_instructions)
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i> 
                                {{ $parcel->special_instructions }}
                            </small>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="row g-3 mt-4 no-print">
                    <div class="col-md-6">
                        @if($parcel->payment_status == 'pending')
                            <button class="btn btn-pay w-100" id="payNowBtn">
                                <i class="bi bi-phone me-2"></i> Pay with M-PESA
                            </button>
                        @else
                            <button class="btn btn-success w-100" disabled>
                                <i class="bi bi-check-circle me-2"></i> Payment Completed
                            </button>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-print w-100" onclick="window.print()">
                            <i class="bi bi-printer me-2"></i> Print Receipt
                        </button>
                    </div>
                </div>

                <!-- Track Button -->
                <div class="text-center mt-3 no-print">
                    <a href="#" class="btn btn-outline-primary">
                        <i class="bi bi-box-seam me-2"></i> Track Your Parcel
                    </a>
                    <a href="{{ route('booking.create') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-plus-circle me-2"></i> Book Another
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Processing Overlay -->
    <div class="payment-overlay" id="paymentOverlay">
        <div class="spinner-container">
            <div id="paymentStatusIcon">
                <div class="spinner-border" role="status"></div>
            </div>
            <h5 id="paymentOverlayTitle">Processing Payment</h5>
            <p id="paymentOverlayMessage">Please wait while we process your payment...</p>
            <div id="paymentOverlayAction" class="mt-3" style="display: none;">
                <button class="btn btn-primary" id="paymentRetryBtn">
                    <i class="bi bi-arrow-repeat me-2"></i> Try Again
                </button>
                <button class="btn btn-outline-secondary ms-2" id="paymentCancelBtn">
                    <i class="bi bi-x-circle me-2"></i> Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- M-PESA STK PUSH MODAL -->
    <div class="modal fade stk-modal" id="stkModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="stk-header">
                    <i class="bi bi-phone"></i>
                    <h4>M-PESA Payment</h4>
                    <p>You will receive a prompt on your phone to confirm</p>
                </div>
                <div class="stk-body">
                    <div class="stk-amount-display">
                        <span><i class="bi bi-coin me-1"></i> Amount</span>
                        <span id="stkAmountDisplay">KES {{ number_format($parcel->total_amount, 2) }}</span>
                    </div>
                    <div class="stk-field">
                        <label><i class="bi bi-cash-coin"></i> Amount (KES)</label>
                        <div class="input-group">
                            <span class="input-group-text">KES</span>
                            <input type="number" class="form-control" id="stkAmount" 
                                value="{{ $parcel->total_amount }}" min="1" step="1">
                        </div>
                    </div>
                    <div class="stk-field">
                        <label><i class="bi bi-phone me-1"></i> M-PESA Phone Number</label>
                        <input type="tel" class="form-control" id="stkPhone" 
                            placeholder="0712345678" value="{{ $parcel->sender_phone ?? '0712345678' }}">
                        <small class="text-muted">Ensure this number is registered with M-PESA</small>
                    </div>
                    <div id="paymentMessage" class="mt-2"></div>
                </div>
                <div class="stk-footer">
                    <button type="button" class="btn btn-outline-secondary" id="stkCancelBtn" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="stkPayBtn">
                        <i class="bi bi-check-circle me-2"></i> Pay Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get CSRF token
            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            }

            let checkoutRequestId = null;
            let pollingInterval = null;
            let isPolling = false;
            let statusCheckCount = 0;
            const maxStatusChecks = 60; // 5 minutes (60 * 5 seconds)

            // Open STK Modal
            document.getElementById('payNowBtn').addEventListener('click', function() {
                const stkModal = new bootstrap.Modal(document.getElementById('stkModal'));
                stkModal.show();
            });

            // Update STK amount display when changed
            document.getElementById('stkAmount').addEventListener('input', function() {
                const val = parseFloat(this.value) || 0;
                document.getElementById('stkAmountDisplay').textContent = 'KES ' + val.toFixed(2);
            });

            // Show payment overlay
            function showPaymentOverlay(title, message, status = 'loading') {
                const overlay = document.getElementById('paymentOverlay');
                const icon = document.getElementById('paymentStatusIcon');
                const titleEl = document.getElementById('paymentOverlayTitle');
                const messageEl = document.getElementById('paymentOverlayMessage');
                const actionEl = document.getElementById('paymentOverlayAction');

                overlay.classList.add('active');

                if (status === 'loading') {
                    icon.innerHTML = `<div class="spinner-border" role="status"></div>`;
                    titleEl.textContent = title || 'Processing Payment';
                    messageEl.textContent = message || 'Please wait while we process your payment...';
                    actionEl.style.display = 'none';
                } else if (status === 'success') {
                    icon.innerHTML = `<i class="bi bi-check-circle-fill status-icon success"></i>`;
                    titleEl.textContent = title || 'Payment Successful!';
                    messageEl.textContent = message || 'Your payment has been completed successfully.';
                    actionEl.style.display = 'none';
                } else if (status === 'failed') {
                    icon.innerHTML = `<i class="bi bi-x-circle-fill status-icon failed"></i>`;
                    titleEl.textContent = title || 'Payment Failed';
                    messageEl.textContent = message || 'Payment could not be completed. Please try again.';
                    actionEl.style.display = 'block';
                } else if (status === 'waiting') {
                    icon.innerHTML = `<i class="bi bi-phone status-icon waiting"></i>`;
                    titleEl.textContent = title || 'Waiting for PIN';
                    messageEl.textContent = message || 'Please check your phone and enter your M-PESA PIN.';
                    actionEl.style.display = 'none';
                }
            }

            function hidePaymentOverlay() {
                document.getElementById('paymentOverlay').classList.remove('active');
            }

            // Handle payment success
            function handlePaymentSuccess(data) {
                showPaymentOverlay(
                    'Payment Successful!',
                    data.message || 'Your payment has been completed successfully.',
                    'success'
                );

                // Update UI
                const statusBadge = document.getElementById('paymentStatusBadge');
                statusBadge.className = 'payment-status paid';
                statusBadge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Paid';

                // Disable pay button
                const payBtn = document.getElementById('payNowBtn');
                payBtn.disabled = true;
                payBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Payment Completed';

                // Reload after 3 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            }

            // Handle payment failure
            function handlePaymentFailure(data) {
                const message = data.message || 'Payment failed. Please try again.';
                showPaymentOverlay(
                    'Payment Failed',
                    message,
                    'failed'
                );
            }

            // Handle payment cancellation
            function handlePaymentCancelled(data) {
                const message = data.message || 'Transaction cancelled. You did not enter your M-PESA PIN.';
                showPaymentOverlay(
                    'Payment Cancelled',
                    message,
                    'failed'
                );
            }

            // Handle payment timeout
            function handlePaymentTimeout(data) {
                const message = data.message || 'Payment timeout. You took too long to enter your PIN. Please try again.';
                showPaymentOverlay(
                    'Payment Timeout',
                    message,
                    'failed'
                );
            }

            // Check M-PESA status (matching the Livewire function)
            function checkMpesaStatus() {
                if (!isPolling) return;

                if (!checkoutRequestId) {
                    console.warn('No checkout request ID for status check');
                    stopPolling();
                    return;
                }

                statusCheckCount++;

                console.log('Checking M-Pesa payment status', {
                    checkout_request_id: checkoutRequestId,
                    attempt: statusCheckCount
                });

                // Make API call to check payment status
                fetch('/api/check-payment-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        parcel_id: '{{ $parcel->parcel_id }}',
                        checkout_request_id: checkoutRequestId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Check result_code from the response (matching Livewire logic)
                    if (data.result_code !== undefined) {
                        const resultCode = data.result_code;

                        switch (resultCode) {
                            case 0:
                                // Success
                                handlePaymentSuccess(data);
                                stopPolling();
                                break;

                            case 1032:
                                // Cancelled by user
                                handlePaymentCancelled(data);
                                stopPolling();
                                break;

                            case 1037:
                                // Timeout
                                if (statusCheckCount >= maxStatusChecks) {
                                    handlePaymentTimeout(data);
                                    stopPolling();
                                }
                                break;

                            case 1:
                            case 1019:
                            case 1036:
                            case 2001:
                            case 1031:
                            case 1026:
                                // Failed
                                handlePaymentFailure(data);
                                stopPolling();
                                break;

                            default:
                                // Still pending or unknown
                                if (statusCheckCount >= maxStatusChecks) {
                                    showPaymentOverlay(
                                        'Payment Status Unknown',
                                        'Unable to verify payment status. Please check transaction history or contact support.',
                                        'failed'
                                    );
                                    stopPolling();
                                } else {
                                    // Update waiting message with attempt count
                                    document.getElementById('paymentOverlayMessage').textContent = 
                                        `Waiting for payment confirmation... (${statusCheckCount}/${maxStatusChecks})`;
                                }
                                break;
                        }
                    } else if (data.success) {
                        // Payment completed successfully (alternative response format)
                        handlePaymentSuccess(data);
                        stopPolling();
                    } else if (data.payment_status === 'failed') {
                        // Payment failed
                        handlePaymentFailure(data);
                        stopPolling();
                    } else if (data.payment_status === 'pending') {
                        // Still pending - update message
                        document.getElementById('paymentOverlayMessage').textContent = 
                            `Waiting for payment confirmation... (${statusCheckCount}/${maxStatusChecks})`;
                    } else {
                        // Unknown status
                        if (statusCheckCount >= maxStatusChecks) {
                            showPaymentOverlay(
                                'Payment Status Unknown',
                                'Unable to verify payment status. Please check transaction history or contact support.',
                                'failed'
                            );
                            stopPolling();
                        }
                    }
                })
                .catch(error => {
                    console.error('Polling error:', error);
                    if (statusCheckCount >= maxStatusChecks) {
                        showPaymentOverlay(
                            'Payment Status Check Failed',
                            'Unable to verify payment status. Please check transaction history or contact support.',
                            'failed'
                        );
                        stopPolling();
                    }
                });
            }

            function startPolling() {
                if (isPolling) return;
                isPolling = true;
                statusCheckCount = 0;

                // Show waiting status
                showPaymentOverlay(
                    'Waiting for PIN',
                    'Please check your phone and enter your M-PESA PIN.',
                    'waiting'
                );

                if (pollingInterval) {
                    clearInterval(pollingInterval);
                }

                // Start polling every 5 seconds
                pollingInterval = setInterval(function() {
                    checkMpesaStatus();
                }, 5000);

                // Immediately check first time
                setTimeout(function() {
                    checkMpesaStatus();
                }, 1000);
            }

            function stopPolling() {
                isPolling = false;
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }

            // STK Pay Button Handler
            document.getElementById('stkPayBtn').addEventListener('click', function() {
                const amount = document.getElementById('stkAmount').value.trim();
                const phone = document.getElementById('stkPhone').value.trim();
                const messageDiv = document.getElementById('paymentMessage');

                // Validate
                if (!amount || parseFloat(amount) <= 0) {
                    showPaymentMessage(messageDiv, 'Please enter a valid amount.', 'warning');
                    return;
                }
                if (!phone.match(/^(0|254|\+254)[0-9]{9}$/)) {
                    showPaymentMessage(messageDiv, 'Please enter a valid M-PESA phone number (e.g. 0712345678)', 'warning');
                    return;
                }

                // Disable button and show loading
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
                messageDiv.innerHTML = '';

                // Get parcel ID
                const parcelId = '{{ $parcel->parcel_id }}';

                // Show payment overlay with loading state
                showPaymentOverlay(
                    'Initiating Payment',
                    'Please wait while we initiate your M-PESA payment...',
                    'loading'
                );

                // Make API call to process payment
                fetch('/api/process-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        parcel_id: parcelId,
                        amount: parseFloat(amount),
                        phone: phone,
                        payment_method: 'mpesa'
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Payment failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Payment initiated successfully - start polling
                        checkoutRequestId = data.checkout_request_id;
                        
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('stkModal'));
                        modal.hide();
                        
                        // Reset button
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
                        
                        // Start polling
                        startPolling();
                    } else {
                        // Payment initiation failed
                        hidePaymentOverlay();
                        showPaymentMessage(messageDiv, data.message || 'Payment failed. Please try again.', 'danger');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
                    }
                })
                .catch(error => {
                    console.error('Payment error:', error);
                    hidePaymentOverlay();
                    showPaymentMessage(messageDiv, error.message || 'Network error. Please try again.', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Pay Now';
                });
            });

            // Retry payment
            document.getElementById('paymentRetryBtn').addEventListener('click', function() {
                hidePaymentOverlay();
                const stkModal = new bootstrap.Modal(document.getElementById('stkModal'));
                stkModal.show();
            });

            // Cancel payment
            document.getElementById('paymentCancelBtn').addEventListener('click', function() {
                stopPolling();
                hidePaymentOverlay();
            });

            // Cancel from modal
            document.getElementById('stkCancelBtn').addEventListener('click', function() {
                stopPolling();
            });

            function showPaymentMessage(container, message, type) {
                const alertClass = {
                    'success': 'alert-success',
                    'danger': 'alert-danger',
                    'warning': 'alert-warning',
                    'info': 'alert-info'
                }[type] || 'alert-info';

                container.innerHTML = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 
                                   type === 'danger' ? 'bi-exclamation-triangle-fill' : 
                                   'bi-info-circle-fill'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
            }

            // Check if payment is already processing on page load
            @if($parcel->payment_status == 'pending')
                // Check if there's a pending payment
                fetch('/api/get-payment-status/{{ $parcel->parcel_id }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.payment_status === 'pending' && data.checkout_request_id) {
                        // There's a pending payment, start polling
                        checkoutRequestId = data.checkout_request_id;
                        startPolling();
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
            @endif

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                stopPolling();
            });
        });
    </script>
</body>
</html>