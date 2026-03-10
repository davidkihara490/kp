<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParcelPickUp extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parcel_pick_ups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parcel_id',
        'pickup_person_type',
        'pickup_person_name',
        'pickup_person_phone',
        'pickup_person_id_number',
        'pickup_person_relationship',
        'pickup_reason',
        'verified_by',
        'verified_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the parcel that owns the pickup.
     */
    public function parcel()
    {
        return $this->belongsTo(Parcel::class);
    }
    /**
     * Get the user who verified the pickup.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope a query to only include pickups by owner.
     */
    public function scopeOwnerPickups($query)
    {
        return $query->where('pickup_person_type', 'owner');
    }

    /**
     * Scope a query to only include pickups by other persons.
     */
    public function scopeOtherPickups($query)
    {
        return $query->where('pickup_person_type', 'other');
    }

    /**
     * Scope a query to only include verified pickups.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Check if pickup was done by owner.
     */
    public function isOwnerPickup(): bool
    {
        return $this->pickup_person_type === 'owner';
    }

    /**
     * Check if pickup was done by other person.
     */
    public function isOtherPickup(): bool
    {
        return $this->pickup_person_type === 'other';
    }

    /**
     * Check if pickup is verified.
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * Get the full name of the pickup person.
     */
    public function getPickupPersonFullNameAttribute(): string
    {
        return $this->pickup_person_name ?? 'N/A';
    }

    /**
     * Get formatted pickup person details.
     */
    public function getPickupPersonDetailsAttribute(): string
    {
        $details = "Name: {$this->pickup_person_name}, Phone: {$this->pickup_person_phone}";

        if ($this->pickup_person_id_number) {
            $details .= ", ID: {$this->pickup_person_id_number}";
        }

        if ($this->isOtherPickup() && $this->pickup_person_relationship) {
            $details .= " ({$this->pickup_person_relationship})";
        }

        return $details;
    }
}