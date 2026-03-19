<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'partner_id',
        'first_name',
        'second_name',
        'last_name',
        'email',
        'phone_number',
        'alternate_phone_number',
        'gender',
        'id_number',
        'driving_license_number',
        'driving_license_issue_date',
        'driving_license_expiry_date',
        'license_class',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'is_available',
        'notes',
        'status'
    ];

    protected $casts = [
        'driving_license_issue_date' => 'date',
        'driving_license_expiry_date' => 'date',
        'is_available' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }


    public function parcels(): MorphMany
    {
        return $this->morphMany(Parcel::class, 'transporter');
    }


    public function fleets()
    {
        return $this->hasMany(DriverFleetAssignment::class, 'driver_id');
    }

    public function currentFleet()
    {
        return $this->fleets()->where('status', 'active')->whereNull('to')->latest()->first();
    }

    public function scopeEmployed($query)
    {
        return $query->whereHas('currentEmployment');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->second_name} {$this->last_name}");
    }

    public function getContactInfoAttribute()
    {
        return [
            'phone' => $this->phone_number,
            'email' => $this->email,
            'alternate_phone' => $this->alternate_phone_number
        ];
    }

    public function getLicenseInfoAttribute()
    {
        return [
            'number' => $this->driving_license_number,
            'class' => $this->license_class,
            'issue_date' => $this->driving_license_issue_date,
            'expiry_date' => $this->driving_license_expiry_date,
            'is_valid' => $this->driving_license_expiry_date > now()
        ];
    }

    public function isLicenseValid()
    {
        return $this->driving_license_expiry_date > now();
    }

    public function __toString()
    {
        return $this->getFullNameAttribute() . " (" . $this->driving_license_number . ")";
    }

    public function getLicenseValidityColor()
    {
        if (!$this->driving_license_expiry_date) return 'secondary';

        if ($this->driving_license_expiry_date->isPast()) {
            return 'danger';
        } elseif ($this->driving_license_expiry_date->diffInDays(now()) <= 30) {
            return 'warning';
        } else {
            return 'success';
        }
    }

    public function getStatusBadge()
    {
        $badges = [
            'active' => ['color' => 'success', 'icon' => 'fa-check-circle'],
            'inactive' => ['color' => 'secondary', 'icon' => 'fa-times-circle'],
            'suspended' => ['color' => 'danger', 'icon' => 'fa-ban'],
            'on_leave' => ['color' => 'warning', 'icon' => 'fa-umbrella-beach'],
            'terminated' => ['color' => 'dark', 'icon' => 'fa-user-slash'],
        ];

        return $badges[$this->status] ?? ['color' => 'info', 'icon' => 'fa-question-circle'];
    }

    /**
     * Check if license is expiring soon (within 30 days)
     */
    public function isLicenseExpiringSoon(): bool
    {
        if (!$this->driving_license_expiry_date) {
            return false;
        }

        $now = Carbon::now();
        $expiryDate = Carbon::parse($this->driving_license_expiry_date);

        // License is expiring soon if expiry date is within 30 days but not yet expired
        return $expiryDate->greaterThan($now) && $expiryDate->lessThanOrEqualTo($now->copy()->addDays(30));
    }
}
