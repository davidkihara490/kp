<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneTown extends Model
{
    protected $fillable = [
        'zone_id',
        'town_id',
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    public function town(): BelongsTo
    {
        return $this->belongsTo(Town::class);
    }
}
