<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FleetOwner extends Model
{
    protected $fillable = [ 'fleet_id', 'partner_id', 'from', 'to', 'status'];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    
}
