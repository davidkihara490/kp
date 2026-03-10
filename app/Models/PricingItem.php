<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingItem extends Model
{
    protected $fillable = [
        'pricing_id',
        'source_zone_id',
        'destination_zone_id',
        'cost',
    ];

    public function sourceZone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'source_zone_id');
    }

    public function destinationZone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'destination_zone_id');
    }

    public function pricing(): BelongsTo
    {
        return $this->belongsTo(Pricing::class);
    }
}

