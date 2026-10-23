<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerRate extends Model
{
    protected $fillable = [
        'partner_id',
        'item_id',
        'source_zone_id',
        'destination_zone_id',
        'cost',
        'status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function sourceZone()
    {
        return $this->belongsTo(Zone::class, 'source_zone_id');
    }

    public function destinationZone()
    {
        return $this->belongsTo(Zone::class, 'destination_zone_id');
    }
}
