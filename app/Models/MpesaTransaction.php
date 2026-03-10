<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpesaTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'merchant_request_id',
        'checkout_request_id',
        'result_code',
        'result_description',
        'response_code',
        'response_description',
        'customer_message',
        'mpesa_receipt_number',
        'transaction_date',
        'amount',
        'amount_paid',
        'phone_number',
        'payer_phone',
        'account_reference',
        'transaction_desc',
        'status',
        'user_id',
        'transaction_type',
        'related_id',
        'related_type',
        'callback_data',
        'request_data',
        'raw_response',
        'error_message',
        'retry_count',
        'last_retry_at',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'callback_data' => 'array',
        'request_data' => 'array',
        'raw_response' => 'array',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_retry_at' => 'datetime',
        
    ];

    protected $attributes = [
        'status' => 'pending',
        'transaction_type' => 'subscription',
        'retry_count' => 0,
        'amount' => 0,
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    // Transaction type constants
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_SALE = 'sale';
    const TYPE_EXPENSE = 'expense';
    const TYPE_TOPUP = 'topup';
    const TYPE_REFUND = 'refund';

    // Result code constants
    const RESULT_SUCCESS = '0';
    const RESULT_CANCELLED = '1032';
    const RESULT_TIMEOUT = '1037';
    const RESULT_INSUFFICIENT_FUNDS = '1';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED)
            ->orWhere(function ($q) {
                $q->where('status', self::STATUS_PENDING)
                    ->where('expires_at', '<', now());
            });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForOutlet($query, $outletId)
    {
        return $query->where('outlet_id', $outletId);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isFailed()
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED ||
            ($this->isPending() && $this->expires_at && $this->expires_at->lt(now()));
    }

    public function isSuccessful()
    {
        return $this->isCompleted() && $this->result_code === self::RESULT_SUCCESS;
    }

    public function markAsCompleted($mpesaReceiptNumber = null, $transactionDate = null)
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'mpesa_receipt_number' => $mpesaReceiptNumber ?: $this->mpesa_receipt_number,
            'transaction_date' => $transactionDate ?: $this->transaction_date,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
        ]);
    }

    public function markAsExpired()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED,
        ]);
    }

    public function incrementRetry()
    {
        $this->increment('retry_count');
        $this->update(['last_retry_at' => now()]);
    }

    public function getFormattedAmountAttribute()
    {
        return 'KES ' . number_format($this->amount, 2);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    public function getPaymentStatusColorAttribute()
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => 'success',
            self::STATUS_FAILED, self::STATUS_EXPIRED => 'danger',
            self::STATUS_PENDING => 'warning',
            default => 'secondary',
        };
    }

    public function getPaymentStatusTextAttribute()
    {
        return match ($this->status) {
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_EXPIRED => 'Expired',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Unknown',
        };
    }

    // Static methods
    public static function findByCheckoutId($checkoutRequestId)
    {
        return static::where('checkout_request_id', $checkoutRequestId)->first();
    }

    public static function findByMpesaReceipt($mpesaReceiptNumber)
    {
        return static::where('mpesa_receipt_number', $mpesaReceiptNumber)->first();
    }

    public static function getTotalAmountByUser($userId, $status = null)
    {
        $query = static::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('amount');
    }

    public static function cleanupExpiredTransactions($days = 7)
    {
        return static::expired()
            ->where('created_at', '<', now()->subDays($days))
            ->delete();
    }
}