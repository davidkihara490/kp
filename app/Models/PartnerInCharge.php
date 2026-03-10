<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerInCharge extends Model
{
    protected $fillable = [
        'partner_id',
        'user_id',
        'from',
        'to',
        'status'
    ];
}
