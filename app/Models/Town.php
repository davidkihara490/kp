<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $fillable = ['code', 'sub_county_id', 'name', 'status'];

    public function subCounty()
    {
        return $this->belongsTo(SubCounty::class);
    }

    public function pickUpAndDropOffPoint(){
        return $this->hasMany(PickUpAndDropOffPoint::class, 'town_id');
    }
}


