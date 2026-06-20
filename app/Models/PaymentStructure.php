<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStructure extends Model
{
    protected $fillable = [
        'delivery_type',
        'tax_percentage',
        'pick_up_drop_off_partner_amount',
        'transport_partner_percentage',
        'platform_percentage',
    ];

    protected $casts = [
        'tax_percentage' => 'decimal:2',
        'pickup_partner_amount' => 'decimal:2',
        'delivery_partner_amount' => 'decimal:2',
        'transport_percentage' => 'decimal:2',
        'platform_percentage' => 'decimal:2',
    ];
}
