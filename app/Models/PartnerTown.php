<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerTown extends Model
{
    protected $fillable = [
        'partner_id',
        'town_id',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
