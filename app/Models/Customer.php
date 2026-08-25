<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'account_type',
        'email',
        'phone',
        'password',
        'address',
        'town_id',
        'is_active',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
        'company_name',
        'company_registration_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the parcels for the customer.
     */
    public function parcels()
    {
        return $this->hasMany(Parcel::class, 'customer_id');
    }

    /**
     * Get the town for the customer.
     */
    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Update last login timestamp.
     */
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAccountTypeLabelAttribute()
    {
        return $this->account_type === 'corporate' ? 'Corporate' : 'Individual';
    }

    // Scopes
    public function scopeIndividual($query)
    {
        return $query->where('account_type', 'individual');
    }

    public function scopeCorporate($query)
    {
        return $query->where('account_type', 'corporate');
    }
}