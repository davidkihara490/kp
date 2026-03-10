<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_number',      // M-Pesa receipt number for M-Pesa payments
        'parcel_id',             // The parcel this payment is for
        'amount',                 // Payment amount
        'payment_method',         // cash, mpesa, card, bank_transfer, wallet
        'payment_date',           // When payment was made
        'status',                 // pending, completed, failed, refunded
        'phone',                  // Phone number used for payment
        'notes',                  // Additional notes
        'paid_by',                // User ID who made/received the payment
        'mpesa_transaction_id',   // Link to mpesa_transactions table
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the parcel associated with this payment
     */
    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }

    /**
     * Get the user who recorded/received this payment
     */
    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Get the associated M-Pesa transaction
     */
    public function mpesaTransaction(): BelongsTo
    {
        return $this->belongsTo(MpesaTransaction::class, 'mpesa_transaction_id');
    }

    /**
     * Scope a query to only include completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is M-Pesa payment
     */
    public function isMpesa(): bool
    {
        return $this->payment_method === 'mpesa';
    }
}