<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Payment $payment */
        $payment = $this->resource;
        $transaction = $payment->mpesa_transaction_id ? $payment->mpesaTransaction : null;

        $appStatus = match ($payment->status) {
            Payment::STATUS_COMPLETED => 'paid',
            Payment::STATUS_PROCESSING => 'pending',
            Payment::STATUS_CANCELLED, Payment::STATUS_EXPIRED => 'failed',
            default => $payment->status,
        };

        return [
            'id' => $payment->id,
            'parcel_id' => $payment->parcel_id,
            'reference_number' => $payment->reference_number,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'method' => $payment->payment_method,
            'payment_date' => $payment->payment_date?->toIso8601String(),
            'status' => $appStatus,
            'payment_status' => $payment->status,
            'phone' => $payment->phone,
            'notes' => $payment->notes,
            'paid_by' => $payment->paid_by,
            'mpesa_transaction_id' => $payment->mpesa_transaction_id,
            'receipt_number' => $transaction?->mpesa_receipt_number,
            'failure_reason' => $appStatus === 'failed'
                ? ($transaction?->error_message ?? $transaction?->result_description ?? 'Payment failed.')
                : null,
            'created_at' => $payment->created_at?->toIso8601String(),
            'updated_at' => $payment->updated_at?->toIso8601String(),
        ];
    }
}

