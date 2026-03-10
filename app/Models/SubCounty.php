<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCounty extends Model
{
    protected $fillable = ['code', 'county_id', 'name'];

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function towns()
    {
        return $this->hasMany(Town::class);
    }

    public function pickupPoints()
    {
        return $this->hasManyThrough(
            PickUpAndDropOffPoint::class,
            Town::class,
            'sub_county_id', // FK on towns
            'town_id', // FK on pickup points
            'id', // local key
            'id' // towns key
        );
    }
}
