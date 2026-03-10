<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverEmployment extends Model
{
    /** @use HasFactory<\Database\Factories\DriverEmploymentFactory> */
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'partner_id',
        'from',
        'to',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class,);
    }
}
