<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'county_id',
        'sub_county_id',
        'town_id',
    ];
}
