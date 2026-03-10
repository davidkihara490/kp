<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class PrivacyPolicy extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'content',
        'title',
        'version',
        'is_active',
        'locale',
        'created_by',
        'updated_by',
        'effective_date',
        'expiry_date',
        'requires_consent',
        'data_categories',
        'processing_purposes',
        'contact_email',
        'data_controller',
        'requires_acceptance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'requires_consent' => 'boolean',
        'requires_acceptance' => 'boolean',
        'effective_date' => 'datetime',
        'expiry_date' => 'datetime',
        'data_categories' => 'array',
        'processing_purposes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'locale' => 'en',
        'requires_consent' => true,
        'requires_acceptance' => true,
        'is_active' => false,
    ];

    /**
     * Get the user who created this privacy policy.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this privacy policy.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // /**
    //  * Get the consents for this privacy policy.
    //  */
    // public function consents()
    // {
    //     return $this->hasMany(PrivacyConsent::class);
    // }

    // /**
    //  * Get the acceptances for this privacy policy.
    //  */Prib
    // public function acceptances()
    // {
    //     return $this->hasMany(PrivacyAcceptance::class);
    // }

    /**
     * Scope a query to only include active policies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include current effective policies.
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now();
        
        return $query->where(function ($q) use ($now) {
            $q->whereNull('effective_date')
              ->orWhere('effective_date', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', $now);
        });
    }

    /**
     * Scope a query to filter by locale.
     */
    public function scopeLocale($query, $locale)
    {
        return $query->where('locale', $locale);
    }

    /**
     * Get the currently active privacy policy for a specific locale.
     */
    public static function getActive($locale = 'en')
    {
        return self::active()
            ->current()
            ->locale($locale)
            ->latest('effective_date')
            ->first();
    }

    /**
     * Check if this privacy policy is currently effective.
     */
    public function isEffective(): bool
    {
        $now = Carbon::now();
        
        $effectiveValid = is_null($this->effective_date) || 
                         $this->effective_date <= $now;
        
        $expiryValid = is_null($this->expiry_date) || 
                      $this->expiry_date >= $now;
        
        return $effectiveValid && $expiryValid;
    }

    /**
     * Activate this privacy policy (deactivate others).
     */
    public function activate(): bool
    {
        return $this->getConnection()->transaction(function () {
            // Deactivate all other policies for the same locale
            static::where('locale', $this->locale)
                ->where('id', '!=', $this->id)
                ->update(['is_active' => false]);
            
            // Activate this one
            $this->is_active = true;
            
            // Set effective date if not set
            if (is_null($this->effective_date)) {
                $this->effective_date = Carbon::now();
            }
            
            return $this->save();
        });
    }

    /**
     * Check if user has consented to this policy.
     */
    public function hasUserConsented($userId): bool
    {
        return $this->consents()
            ->where('user_id', $userId)
            ->where('consented', true)
            ->exists();
    }

    /**
     * Get data categories as formatted list.
     */
    public function getDataCategoriesListAttribute(): string
    {
        if (empty($this->data_categories)) {
            return '';
        }
        
        return implode(', ', $this->data_categories);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set effective date when activating if not set
        static::saving(function ($policy) {
            if ($policy->is_active && is_null($policy->effective_date)) {
                $policy->effective_date = Carbon::now();
            }
        });
    }
}