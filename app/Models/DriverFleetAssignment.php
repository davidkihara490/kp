<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DriverFleetAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFleetAssignmentFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'fleet_id', 'driver_id', 'from', 'to', 'status', 'assigned_by'];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime',
    ];

    public function fleet()
    {
        return $this->belongsTo(Fleet::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
