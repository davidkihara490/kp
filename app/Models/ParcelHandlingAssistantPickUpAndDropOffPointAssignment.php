<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelHandlingAssistantPickUpAndDropOffPointAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\ParcelHandlingAssistantPickUpAndDropOffPointAssignmentFactory> */
    use HasFactory;

    protected $fillable = [
        'parcel_handling_assistant_id',
        'pick_up_and_drop_off_point_id',
        'partner_id',
        'from',
        'to',
        'assigned_by',
        'status'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function pickUpAndDropOffPoint()
    {
        return $this->belongsTo(PickUpAndDropOffPoint::class, 'pick_up_and_drop_off_point_id');
    }
    public function parcelHandlingAssistant()
    {
        return $this->belongsTo(ParcelHandlingAssistant::class, 'parcel_handling_assistant_id');
    }
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}