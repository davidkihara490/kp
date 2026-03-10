<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickUpAndDropOffPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partner_id',
        'name',
        'code',
        'town_id',
        'building',
        'room_number',
        'address',
        'status',
        'contact_person',
        'contact_email',
        'contact_phone_number',
        'opening_hours',
        'closing_hours',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}

