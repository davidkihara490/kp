<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStructure extends Model
{
    protected $fillable = [
        'delivery_type',
        'tax_percentage',
        'pick_up_drop_off_partner_percentage',
        'transport_partner_percentage',
        'platform_percentage',
    ];
}
