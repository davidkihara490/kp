<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelHandlingAssistantEmployment extends Model
{
    use HasFactory;
    protected $fillable = [
        'pha_id',
        'partner_id',
        'from',
        'to',
        'status',
    ];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    /**
     * Relationship with parcel handling assistant
     */
    public function assistant()
    {
        return $this->belongsTo(ParcelHandlingAssistant::class, 'pha_id');
    }

    /**
     * Relationship with station partner
     */
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
