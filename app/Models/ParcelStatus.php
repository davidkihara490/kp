<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParcelStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'parcel_id',
        'status',
        'changed_by',        // user or driver ID
        'changed_by_type',   // 'admin', 'partner', 'driver', 'system'
        'notes',
        'otp',
        'otp_verified',
        'driver_id',
    ];

    protected $casts = [
        'otp_verified' => 'boolean',
    ];

    public function parcel(): BelongsTo
    {
        return $this->belongsTo(Parcel::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function driver(){
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}