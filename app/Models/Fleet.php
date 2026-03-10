<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fleet extends Model
{
    /** @use HasFactory<\Database\Factories\FleetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partner_id',
        'registration_number',
        'make',
        'model',
        'vehicle_type',
        'color',
        'status',
        'is_available',
        'registration_expiry',
        'insurance_expiry',
        'last_service_date',
        'next_service_date',
        'year',
        'capacity',
        'fuel_type',
        'notes',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'registration_expiry' => 'date',
        'insurance_expiry' => 'date',
        'last_service_date' => 'date',
        'next_service_date' => 'date',
        'capacity' => 'decimal:2',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->registration_number})";
    }

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'active' => 'success',
            'maintenance' => 'warning',
            'inactive' => 'secondary',
            'accident' => 'danger',
            default => 'info',
        };
    }

    public function getStatusIconAttribute()
    {
        return match ($this->status) {
            'active' => 'fa-check-circle',
            'maintenance' => 'fa-tools',
            'inactive' => 'fa-times-circle',
            'accident' => 'fa-car-crash',
            default => 'fa-question-circle',
        };
    }

    public function getIsRegistrationValidAttribute()
    {
        return $this->registration_expiry && $this->registration_expiry->isFuture();
    }

    public function getIsInsuranceValidAttribute()
    {
        return $this->insurance_expiry && $this->insurance_expiry->isFuture();
    }

    public function getNeedsServiceAttribute()
    {
        return $this->next_service_date && $this->next_service_date->isPast();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')->where('is_available', true);
    }

    public function scopeNeedsService($query)
    {
        return $query->where('next_service_date', '<', now());
    }

    public function scopeWithExpiredRegistration($query)
    {
        return $query->where('registration_expiry', '<', now());
    }

    public function scopeByTransportPartner($query, $transportPartnerId)
    {
        return $query->where('partner_id', $transportPartnerId);
    }

    public function markAsAvailable()
    {
        $this->update([
            'is_available' => true,
            'status' => 'active',
        ]);
    }

    public function markAsUnavailable($status = 'maintenance')
    {
        $this->update([
            'is_available' => false,
            'status' => $status,
        ]);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function drivers()
    {
        return $this->hasMany(DriverFleetAssignment::class, 'fleet_id');
    }
    public function currentDriver()
    {
        return $this->drivers()->where('status', 'active')->whereNull('to')->latest()->first();
    }

    public function getCurrentDriverAttribute()
    {
        return $this->drivers()
            ->where('status', 'active')
            ->whereNull('to')
            ->latest()
            ->first()?->driver;
    }

    public function assignedDrivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_fleet_assignments')
            ->withPivot(['from', 'to', 'status'])
            ->withTimestamps();
    }
}