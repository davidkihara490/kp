<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class ParcelHandlingAssistant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'phone_number',
        'email',
        'role',
        'id_number',
        'status',
        'created_by',
        'user_id',
        'partner_id'
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    /**
     * Relationship with employments
     */

    public function assignments()
    {
        return $this->hasMany(ParcelHandlingAssistantPickUpAndDropOffPointAssignment::class, 'parcel_handling_assistant_id');
    }

    public function assignment()
    {
        return $this->assignments()->latest()->first();
    }

    public function getFullNameAttribute()
    {
        $names = [$this->first_name];
        if ($this->second_name) {
            $names[] = $this->second_name;
        }
        $names[] = $this->last_name;

        return implode(' ', $names);
    }
}
