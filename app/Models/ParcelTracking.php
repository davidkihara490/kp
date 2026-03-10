<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelTracking extends Model
{
    protected $fillable = [
        'parcel_id',
        'status',
        'notes',
        'updated_by',
    ];

    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
