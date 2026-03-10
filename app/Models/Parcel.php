<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Parcel extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        // Basic Information
        'parcel_id',
        'customer_id',
        'booking_type', // instant, scheduled, bulk

        // Sender Information
        'sender_name',
        'sender_phone',
        'sender_email',
        'sender_town_id',
        'sender_notes',

        // Receiver Information
        'receiver_name',
        'receiver_phone',
        'receiver_email',
        'receiver_address',
        'receiver_town_id',
        'receiver_notes',

        // Pickup Information
        'pha_id',
        'sender_partner_id',
        'sender_pick_up_drop_off_point_id',
        'date',

        // Parcel Details
        'parcel_type', // document, package, envelope, box, pallet
        'package_type', // regular, fragile, perishable, valuable, hazardous
        'weight',
        'length',
        'width',
        'height',
        'dimension_unit', // cm, inches
        'weight_unit', // kg, g, lb
        'declared_value',
        'insurance_amount',
        'insurance_required',
        'content_description',
        'special_instructions',

        // Delivery Information
        'delivery_partner_id',
        'delivery_pick_up_drop_off_point_id',

        // Pricing
        'base_price',
        'weight_charge',
        'distance_charge',
        'special_handling_charge',
        'insurance_charge',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method', // cash, mpesa, card, bank_transfer
        'payment_status', // pending, paid, failed, refunded
        'paid_at',

        'payout',
        'transport_partner_id',
        'driver_id',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'declared_value' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
        'insurance_required' => 'boolean',
        'base_price' => 'decimal:2',
        'weight_charge' => 'decimal:2',
        'distance_charge' => 'decimal:2',
        'special_handling_charge' => 'decimal:2',
        'insurance_charge' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'parcel_dimensions',
        'parcel_volume',
        'parcel_size_category',
        'parcel_status_badge',
        'estimated_delivery_time',
        'is_overdue',
        'is_delayed',
        'days_in_transit',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'current_status' => 'pending',
        'payment_status' => 'pending',
        'parcel_type' => 'package',
        'package_type' => 'regular',
        'dimension_unit' => 'cm',
        'weight_unit' => 'kg',
        'booking_type' => 'instant',
        'booking_source' => 'web',
    ];

    const STATUS_CREATED = 'created';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_TRANSIT = 'in_transit';
    const STATUS_PENDING = 'pending';
    const STATUS_WAREHOUSE = 'warehouse';
    const STATUS_ARRIVED_AT_DESTINATION = 'arrived_at_destination';
    const STATUS_PICKED = 'picked';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_FAILED = 'failed';
    const STATUS_RETURNED = 'returned';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($parcel) {
            if (!$parcel->parcel_id) {
                $parcel->parcel_id = static::generateParcelNumber();
            }
        });
    }

    /**
     * Generate a unique parcel number.
     *
     * @return string
     */
    public static function generateParcelNumber(): string
    {
        $prefix = 'KP';
        $date = date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));

        return $prefix . $date . $random;
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the customer who booked the parcel.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function transportPartner()
    {
        return $this->belongsTo(Partner::class, 'transport_partner_id');
    }

    public function parcelPickUp(){
        return $this->hasOne(ParcelPickUp::class, 'parcel_id');
    }

    public function parcelHandlingAssistant(){
        return $this->belongsTo(ParcelHandlingAssistant::class, 'pha_id');
    }

    /**
     * Get the sender contact.
     */
    public function sender()
    {
        return $this->belongsTo(Contact::class, 'sender_id');
    }

    // /**
    //  * Get the receiver contact.
    //  */
    public function receiver()
    {
        return $this->belongsTo(Contact::class, 'receiver_id');
    }
    /**
     * Get the sender's town.
     */
    public function senderTown()
    {
        return $this->belongsTo(Town::class, 'sender_town_id');
    }

    public function senderCounty()
    {
        return $this->senderTown?->county;
    }
    /**
     * Get the receiver's town.
     */
    public function receiverTown()
    {
        return $this->belongsTo(Town::class, 'receiver_town_id');
    }

    public function receiverCounty()
    {
        return $this->receiverTown?->county;
    }

    /**
     * Get the pickup partner.
     */
    public function senderPartner()
    {
        return $this->belongsTo(Partner::class, 'sender_partner_id');
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Get the pickup station.
     */
    public function senderPickUpDropOffPoint()
    {
        return $this->belongsTo(PickUpAndDropOffPoint::class, 'sender_pick_up_drop_off_point_id');
    }

    /**
     * Get the delivery partner.
     */
    public function receivingPartner()
    {
        return $this->belongsTo(Partner::class, 'receiving_partner_id');
    }

    /**
     * Get the delivery station.
     */
    public function deliveryStation()
    {
        return $this->belongsTo(PickUpAndDropOffPoint::class, 'delivery_pick_up_drop_off_point_id');
    }



    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function payments()
    {
        return $this->hasMany(Payment::class, 'parcel_id');
    }


    public function trackings()
    {
        return $this->hasMany(ParcelTracking::class);
    }

    public function latestTracking()
    {
        return $this->hasOne(ParcelTracking::class)->latestOfMany();
    }

    public function addTracking($status, $userId,  $notes = null)
    {
        $this->update(['status' => $status]);

        return $this->trackings()->create([
            'status' => $status,
            'updated_by' => $userId,
            'notes' => $notes
        ]);
    }


    // ==================== ACCESSORS ====================

    /**
     * Get the parcel dimensions as a string.
     */
    public function getParcelDimensionsAttribute(): string
    {
        return sprintf(
            '%s x %s x %s %s',
            $this->length,
            $this->width,
            $this->height,
            $this->dimension_unit
        );
    }

    /**
     * Get the parcel volume.
     */
    public function getParcelVolumeAttribute(): float
    {
        if ($this->length && $this->width && $this->height) {
            return round($this->length * $this->width * $this->height, 2);
        }
        return 0;
    }

    /**
     * Get the parcel size category.
     */
    public function getParcelSizeCategoryAttribute(): string
    {
        $volume = $this->parcel_volume;

        if ($volume == 0) return 'unknown';
        if ($volume < 1000) return 'small';
        if ($volume < 5000) return 'medium';
        if ($volume < 10000) return 'large';
        return 'extra_large';
    }

    /**
     * Get the parcel status badge information.
     */
    public function getParcelStatusBadgeAttribute(): array
    {
        $statusConfig = [
            'pending' => ['color' => 'secondary', 'icon' => 'bi-clock'],
            'confirmed' => ['color' => 'info', 'icon' => 'bi-check-circle'],
            'processing' => ['color' => 'primary', 'icon' => 'bi-gear'],
            'assigned' => ['color' => 'warning', 'icon' => 'bi-person-check'],
            'picked_up' => ['color' => 'info', 'icon' => 'bi-box-arrow-in-down'],
            'in_transit' => ['color' => 'primary', 'icon' => 'bi-truck'],
            'at_warehouse' => ['color' => 'info', 'icon' => 'bi-building'],
            'out_for_delivery' => ['color' => 'warning', 'icon' => 'bi-box-arrow-up'],
            'delivered' => ['color' => 'success', 'icon' => 'bi-check-circle-fill'],
            'failed' => ['color' => 'danger', 'icon' => 'bi-x-circle'],
            'returned' => ['color' => 'warning', 'icon' => 'bi-arrow-return-left'],
            'cancelled' => ['color' => 'dark', 'icon' => 'bi-x-octagon'],
        ];

        return $statusConfig[$this->current_status] ?? ['color' => 'secondary', 'icon' => 'bi-question-circle'];
    }

    /**
     * Get the estimated delivery time as string.
     */
    public function getEstimatedDeliveryTimeAttribute(): string
    {
        if ($this->estimated_delivery_date) {
            $date = $this->estimated_delivery_date->format('D, M j, Y');
            if ($this->delivery_time_window) {
                return $date . ' (' . $this->delivery_time_window . ')';
            }
            return $date;
        }
        return 'Not estimated yet';
    }

    /**
     * Check if the parcel is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->estimated_delivery_date) {
            return false;
        }

        return now()->isAfter($this->estimated_delivery_date) &&
            !in_array($this->current_status, ['delivered', 'cancelled', 'returned']);
    }

    /**
     * Check if the parcel is delayed.
     */
    public function getIsDelayedAttribute(): bool
    {
        if (!$this->estimated_delivery_date || $this->actual_delivery_date) {
            return false;
        }

        $daysOverdue = now()->diffInDays($this->estimated_delivery_date, false);
        return $daysOverdue < -1 &&
            !in_array($this->current_status, ['delivered', 'cancelled', 'returned']);
    }

    /**
     * Get the number of days in transit.
     */
    public function getDaysInTransitAttribute(): int
    {
        if ($this->pickup_at && $this->actual_delivery_date) {
            return $this->pickup_at->diffInDays($this->actual_delivery_date);
        }

        if ($this->pickup_at) {
            return $this->pickup_at->diffInDays(now());
        }

        return 0;
    }

    // ==================== SCOPES ====================

    /**
     * Scope a query to only include active parcels.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('current_status', ['delivered', 'cancelled', 'returned']);
    }

    /**
     * Scope a query to only include delivered parcels.
     */
    public function scopeDelivered($query)
    {
        return $query->where('current_status', 'delivered');
    }

    /**
     * Scope a query to only include pending parcels.
     */
    public function scopePending($query)
    {
        return $query->where('current_status', 'pending');
    }

    /**
     * Scope a query to only include in-transit parcels.
     */
    public function scopeInTransit($query)
    {
        return $query->where('current_status', 'in_transit');
    }

    /**
     * Scope a query to only include overdue parcels.
     */
    public function scopeOverdue($query)
    {
        return $query->where('estimated_delivery_date', '<', now())
            ->whereNotIn('current_status', ['delivered', 'cancelled', 'returned']);
    }

    /**
     * Scope a query to only include paid parcels.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include parcels for a specific driver.
     */
    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * Scope a query to only include parcels for a specific transport partner.
     */
    public function scopeForTransportPartner($query, $transportPartnerId)
    {
        return $query->where('transport_partner_id', $transportPartnerId);
    }

    /**
     * Scope a query to only include parcels by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // ==================== METHODS ====================

    /**
     * Update parcel status and create tracking history.
     */
    // public function updateStatus(string $status, ?string $notes = null, ?int $userId = null): self
    // {
    //     $oldStatus = $this->current_status;
        
    //     $this->update([
    //         'last_status' => $oldStatus,
    //         'current_status' => $status,
    //         'status_updated_at' => now(),
    //     ]);

    //     // Create tracking history
    //     ParcelTracking::create([
    //         'parcel_id' => $this->id,
    //         'status' => $status,
    //         'notes' => $notes,
    //         'location' => $this->getCurrentLocation(),
    //         'created_by' => $userId ?? auth()->id(),
    //     ]);

    //     return $this;
    // }

    /**
     * Mark parcel as picked up.
     */
    // public function markAsPickedUp(?int $pickedBy = null, ?string $notes = null): self
    // {
    //     return $this->updateStatus('picked_up', $notes, $pickedBy)
    //                 ->update(['pickup_at' => now(), 'pickup_by' => $pickedBy]);
    // }

    /**
     * Mark parcel as delivered.
     */
    // public function markAsDelivered(?int $deliveredBy = null, ?string $proofUrl = null, ?string $signatureUrl = null): self
    // {
    //     return $this->updateStatus('delivered', 'Parcel delivered successfully', $deliveredBy)
    //                 ->update([
    //                     'delivery_at' => now(),
    //                     'delivery_by' => $deliveredBy,
    //                     'actual_delivery_date' => now(),
    //                     'delivery_proof_url' => $proofUrl,
    //                     'signature_url' => $signatureUrl,
    //                 ]);
    // }

    /**
     * Assign parcel to driver and transport partner.
     */
    public function assignToDriver(int $driverId, int $transportPartnerId, ?int $vehicleId = null, ?int $assignedBy = null): self
    {
        $this->update([
            'driver_id' => $driverId,
            'transport_partner_id' => $transportPartnerId,
            'vehicle_id' => $vehicleId,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy,
        ]);

        return $this->updateStatus('assigned', 'Parcel assigned to driver', $assignedBy);
    }

    /**
     * Calculate total amount including all charges.
     */
    public function calculateTotalAmount(): float
    {
        $total = $this->base_price
            + $this->weight_charge
            + $this->distance_charge
            + $this->special_handling_charge
            + $this->insurance_charge
            + $this->tax_amount
            - $this->discount_amount;

        return max(0, $total);
    }

    /**
     * Get the current location of the parcel.
     */
    public function getCurrentLocation(): ?string
    {
        $status = $this->current_status;

        if ($status === 'picked_up' && $this->driver) {
            return $this->driver->current_location;
        }

        if ($status === 'at_warehouse' && $this->warehouse) {
            return $this->warehouse->name;
        }

        if (in_array($status, ['pending', 'confirmed', 'processing']) && $this->pickupStation) {
            return $this->pickupStation->name;
        }

        return null;
    }

    /**
     * Check if parcel requires special handling.
     */
    public function requiresSpecialHandling(): bool
    {
        return in_array($this->package_type, ['fragile', 'perishable', 'valuable', 'hazardous']);
    }

    /**
     * Get the delivery address as a formatted string.
     */
    public function getDeliveryAddress(): string
    {
        $parts = [];

        if ($this->receiver_address) $parts[] = $this->receiver_address;
        if ($this->receiverTown) $parts[] = $this->receiverTown->name;
        if ($this->receiverCounty) $parts[] = $this->receiverCounty->name;

        return implode(', ', $parts);
    }

    /**
     * Get the pickup address as a formatted string.
     */
    public function getPickupAddress(): string
    {
        $parts = [];

        if ($this->sender_address) $parts[] = $this->sender_address;
        if ($this->senderTown) $parts[] = $this->senderTown->name;
        if ($this->senderCounty) $parts[] = $this->senderCounty->name;

        return implode(', ', $parts);
    }

    /**
     * Check if parcel can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        $nonCancellableStatuses = ['delivered', 'cancelled', 'returned', 'in_transit', 'out_for_delivery'];

        return !in_array($this->current_status, $nonCancellableStatuses);
    }

    /**
     * Cancel the parcel.
     */
    public function cancel(?string $reason = null, ?int $cancelledBy = null): self
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('Parcel cannot be cancelled in its current status.');
        }

        $this->update([
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $reason,
        ]);

        return $this->updateStatus('cancelled', 'Parcel cancelled: ' . $reason, $cancelledBy);
    }


    public function statuses()
    {
        return $this->hasMany(ParcelStatus::class);
    }


    public function currentStatus()
    {
        return $this->statuses()->latest()->first();
    }



    // protected function isValidTransition(string $newStatus): bool
    // {
    //     $allowedTransitions = [

    //         self::STATUS_CREATED => [
    //             self::STATUS_ACCEPTED
    //         ],

    //         self::STATUS_ACCEPTED => [
    //             self::STATUS_ASSIGNED
    //         ],

    //         self::STATUS_ASSIGNED => [
    //             self::STATUS_DRIVER_ENROUTE
    //         ],

    //         self::STATUS_DRIVER_ENROUTE => [
    //             self::STATUS_PICKED
    //         ],

    //         self::STATUS_PICKED => [
    //             self::STATUS_IN_TRANSIT
    //         ],

    //         self::STATUS_IN_TRANSIT => [
    //             self::STATUS_ARRIVED_AT_DESTINATION
    //         ],

    //         self::STATUS_ARRIVED_AT_DESTINATION => [
    //             self::STATUS_OUT_FOR_DELIVERY
    //         ],

    //         self::STATUS_OUT_FOR_DELIVERY => [
    //             self::STATUS_DELIVERED,
    //             self::STATUS_FAILED
    //         ],

    //         self::STATUS_FAILED => [
    //             self::STATUS_RETURNED
    //         ],

    //     ];

    //     return in_array(
    //         $newStatus,
    //         $allowedTransitions[$this->current_status] ?? []
    //     );
    // }


    public function updateParcelStatus(
        string $newStatus,
        ?int $changedBy = null,
        ?string $changedByType = null,
        ?string $notes = null,
        ?int $driverId = null,
        ?string $otp = null
    ): void {
        DB::transaction(function () use (
            $newStatus,
            $changedBy,
            $changedByType,
            $notes,
            $driverId,
            $otp
        ) {

            // 🔐 Validate Transition
            // if (! $this->isValidTransition($newStatus)) {
            //     throw ValidationException::withMessages([
            //         'status' => 'Invalid status transition from ' . $this->current_status . ' to ' . $newStatus
            //     ]);
            // }

            // 🔢 OTP Verification Logic
            $otpVerified = false;

            if ($newStatus === self::STATUS_DELIVERED) {

                if ($this->delivery_otp && $otp !== $this->delivery_otp) {
                    throw ValidationException::withMessages([
                        'otp' => 'Invalid delivery OTP.'
                    ]);
                }

                $otpVerified = true;
            }

            // 📝 Create Status History Record
            $this->statuses()->create([
                'status'          => $newStatus,
                'changed_by'      => $changedBy,
                'changed_by_type' => $changedByType,
                'notes'           => $notes,
                'driver_id'       => $driverId,
                'otp'             => $otp,
                'otp_verified'    => $otpVerified,
            ]);

            // 🔄 Update Current Status
            $this->update([
                'current_status' => $newStatus
            ]);
        });
    }

    public function generateDeliveryOtp(int $length = 6): string
    {
        $otp = '';

        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }

        // Save hashed OTP for security
        $this->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addHours(12),
        ]);

        return $otp; // return plain OTP to send via SMS
    }

    public function verifyDeliveryOtp(string $otp): bool
    {
        if (! $this->delivery_otp) {
            return false;
        }

        return Hash::check($otp, $this->delivery_otp);
    }
}








// $otp = $parcel->generateDeliveryOtp();



// if (! $parcel->verifyDeliveryOtp($request->otp)) {
//     throw ValidationException::withMessages([
//         'otp' => 'Invalid OTP provided.'
//     ]);
// }

// if (now()->greaterThan($this->otp_expires_at)) {
//     return false;
// }
