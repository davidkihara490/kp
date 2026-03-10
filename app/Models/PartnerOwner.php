<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerOwner extends Model
{
    protected $fillable = [
        'partner_id',
        'user_id',
        'from',
        'to',
        'status'
    ];

    protected $casts = [
        'from' => 'datetime',
        'to'   => 'datetime',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
