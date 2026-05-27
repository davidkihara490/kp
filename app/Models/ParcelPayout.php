<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParcelPayout extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'parcel_id',
        'partner_id',
        'type',
        'destination',
        'destination_id',
        'warehouse_id',
        'origin_id',
        'amount',
        'status',
        'paid_out_on',
        'cancelation_reason'
    ];
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_out_on' => 'timestamp'
    ];

    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
    public function parcelDestination(): BelongsTo
    {
        return $this->belongsTo(PickUpAndDropOffPoint::class, 'destination_id');
    }
    public function origin(): BelongsTo
    {
        return $this->belongsTo(PickUpAndDropOffPoint::class, 'origin_id');
    }
}
