{{-- resources/views/emails/parcels/new-parcel.blade.php --}}
@extends('emails.components.layout')

@section('email-content')
<div class="text-center mb-3">
    <div class="status-icon">
        <i class="bi bi-box-seam"></i>
    </div>

    <h1>New Parcel Created!</h1>
    <p class="text-muted">A new parcel has been registered with <strong>Karibu Parcels</strong></p>
</div>

<div class="info-card text-center" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.05), rgba(32, 201, 151, 0.05));">
    <span class="badge badge-success" style="margin-bottom: 10px;">Tracking Number</span>
    <h2 style="font-size: 28px; letter-spacing: 2px; margin: 10px 0;">{{ $parcel->parcel_id }}</h2>
</div>

<!-- Parcel Details Grid -->
<div class="info-grid">
    <div class="info-item">
        <i class="bi bi-box"></i>
        <h4>Parcel Type</h4>
        <p>{{ $parcel->parcel_type ?? 'Standard Package' }}</p>
    </div>
    <div class="info-item">
        <i class="bi bi-arrow-left-right"></i>
        <h4>Weight</h4>
        <p>{{ $parcel->weight ?? 'N/A' }} kg</p>
    </div>
    <div class="info-item">
        <i class="bi bi-rulers"></i>
        <h4>Dimensions</h4>
        <p>{{ $parcel->dimensions ?? 'N/A' }}</p>
    </div>
    <div class="info-item">
        <i class="bi bi-tag"></i>
        <h4>Value</h4>
        <p>KES {{ number_format($parcel->declared_value ?? 0) }}</p>
    </div>
</div>

<!-- Sender & Recipient Information -->
<table class="data-table" style="margin-top: 10px;">
    <thead>
        <tr>
            <th colspan="2" style="text-align: center;"> Shipment Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 40%; background: #f8f9fa; font-weight: 600;">From (Sender)</td>
            <td>
                <strong>{{ $parcel->sender_name }}</strong><br>
                {{ $parcel->sender_phone }}<br>
                @if($parcel->sender_email)
                {{ $parcel->sender_email }}<br>
                @endif
                {{ $parcel->senderTown?->county?->name }}
                {{ $parcel->senderTown?->name }}
            </td>
        </tr>
        <tr>
            <td style="background: #f8f9fa; font-weight: 600;">To (Recipient)</td>
            <td>
                <strong>{{ $parcel->receiver_name }}</strong><br>
                {{ $parcel->receiver_phone }}<br>
                @if($parcel->receiver_email)
                {{ $parcel->receiver_email }}<br>
                @endif
                {{ $parcel->receiverTown?->county?->name }}
                {{ $parcel->receiverTown?->name }}
            </td>
        </tr>
        @if($parcel->special_instructions)
        <tr>
            <td style="background: #f8f9fa; font-weight: 600;">Special Instructions</td>
            <td>{{ $parcel->special_instructions }}</td>
        </tr>
        @endif
    </tbody>
</table>

<!-- Cost Breakdown -->
<div class="info-card" style="margin-top: 20px;">
    <h3 style="margin-top: 0; margin-bottom: 15px;"> Cost Breakdown</h3>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px 0;">Delivery Fee</td>
            <td style="padding: 8px 0; text-align: right;">KES {{ number_format($parcel->base_price ?? 0) }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">Tax</td>
            <td style="padding: 8px 0; text-align: right;">KES {{ number_format($parcel->tax_amount) }}</td>
        </tr>

        @if($parcel->discount > 0)
        <tr>
            <td style="padding: 8px 0;">Discount</td>
            <td style="padding: 8px 0; text-align: right; color: #28a745;">- KES {{ number_format($parcel->discount_amount) }}</td>
        </tr>
        @endif
        <tr style="border-top: 2px solid #28a745;">
            <td style="padding: 12px 0; font-weight: 700; font-size: 16px;">Total Amount</td>
            <td style="padding: 12px 0; text-align: right; font-weight: 700; font-size: 18px; color: #28a745;">
                KES {{ number_format($parcel->total_amount) }}
            </td>
        </tr>
    </table>
</div>

<!-- Payment Status -->
@if($parcel->payment_status)
<div class="alert alert-success" style="margin: 20px 0;">
    <i class="bi bi-check-circle-fill"></i>
    <strong>Payment Status:</strong> {{ ucfirst($parcel->payment_status) }}
    @if($parcel->paid_at)
    <br><small>Paid on: {{ $parcel->paid_at->format('d M Y, h:i A') }}</small>
    @endif
</div>
@endif


<!-- Action Buttons -->
<div style="text-align: center; margin: 30px 0;">
    <!-- <a href="#" class="btn btn-primary" style="display: inline-block;">
        <i class="bi bi-eye"></i> Track Parcel
    </a> -->

    <a href="{{ route('admin.parcels.view', $parcel->id) }}" class="btn btn-outline" style="display: inline-block; margin-left: 10px;">
        <i class="bi bi-file-text"></i> View Details
    </a>
</div>

<!-- Printable Receipt Link -->
<!-- <div style="text-align: center; margin: 20px 0;">
    <a href="#" style="color: #666; font-size: 13px; text-decoration: none;">
        <i class="bi bi-printer"></i> Download Receipt
    </a>
</div> -->

<!-- Need Help Section -->
<div class="divider"></div>

<div style="margin-top: 20px; text-align: center;">
    <h3 style="color: #28a745;">Need Help?</h3>
    <p style="font-size: 14px;">
        <i class="bi bi-headset"></i> Contact our support team:<br>
        📞 +254 700 000 000 | 📧 support@karibuparcels.com
    </p>
    <p style="font-size: 13px; color: #999;">
        We're available Mon-Sat, 8:00 AM - 8:00 PM
    </p>
</div>

<!-- Sign-off -->
<div style="margin-top: 30px;">
    <p>Thank you for choosing Karibu Parcels,</p>
    <p><strong>The Delivery Team</strong></p>
</div>
@endsection