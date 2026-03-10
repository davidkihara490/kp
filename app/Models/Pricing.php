<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pricing extends Model
{
    protected $fillable = [
        'item_id',
        'min_weight',
        'max_weight',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PricingItem::class);
    }

    public function item(): BelongsTo{
        return $this->belongsTo(Item::class);
    }

}
