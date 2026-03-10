<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = [
        'name',
    ];

    public function counties(): HasMany
    {
        return $this->hasMany(ZoneCounty::class);
    }
}

