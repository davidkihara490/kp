<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'user_name',
        'user_type',
        'email',
        'phone_number',
        'password',
        'status',
        'terms_and_conditions',
        'privacy_policy',
        'login_attempts',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'terms_and_conditions' => 'boolean',
            'privacy_policy' => 'boolean',
            'login_attempts' => 'integer',
            'last_login_at' => 'datetime',
            'last_login_ip' => 'string',

        ];
    }
    public function partner(): HasOne
    {
        return $this->hasOne(Partner::class, 'owner_id');
    }
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'user_id');
    }
    public function parcelHandlingAssistant(): HasOne
    {
        return $this->hasOne(ParcelHandlingAssistant::class, 'user_id');
    }
    public function isPartner(): bool
    {
        return $this->user_type === 'transport' || $this->user_type === 'pickup-dropoff' || $this->user_type === 'driver' || $this->user_type === 'pha';
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->last_name;
    }
}
