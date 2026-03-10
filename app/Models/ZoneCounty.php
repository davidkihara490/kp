<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneCounty extends Model
{
    protected $fillable = [
        'zone_id',
        'county_id',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }
}


