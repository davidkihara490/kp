<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class County extends Model
{
    protected $fillable = ['code', 'name'];

    public function subCounties()
    {
        return $this->hasMany(SubCounty::class);
    }

    public function towns()
    {
        return $this->hasManyThrough(Town::class, SubCounty::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

public function getPickupPointsAttribute()
{
    return $this->subCounties
        ->flatMap(fn($subCounty) => $subCounty->towns)
        ->flatMap(fn($town) => $town->pickUpAndDropOffPoint);
}
}
