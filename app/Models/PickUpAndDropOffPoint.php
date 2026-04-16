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
        'operating_days',
    ];

    protected $casts = [
        'operating_days' => 'array',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function town()
    {
        return $this->belongsTo(Town::class);
    }
    public function senderParcels()
    {
        return $this->hasMany(Parcel::class, 'sender_pick_up_drop_off_point_id');
    }
    public function deliveryParcels()
    {
        return $this->hasMany(Parcel::class, 'delivery_pick_up_drop_off_point_id');
    }
    public function getAllParcelsAttribute()
    {
        return $this->senderParcels->merge($this->deliveryParcels);
    }

    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'sender_pick_up_drop_off_point_id')
            ->orWhere('delivery_pick_up_drop_off_point_id', $this->id);
    }
}
