<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class TermsAndCondition extends Model
{
    /** @use HasFactory<\Database\Factories\TermsAndConditionFactory> */
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
        'requires_acceptance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'requires_acceptance' => 'boolean',
        'effective_date' => 'datetime',
        'expiry_date' => 'datetime',
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
        'requires_acceptance' => true,
        'is_active' => false,
    ];

    /**
     * Get the user who created this terms and condition.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this terms and condition.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the acceptances for this terms and condition.
     */
    // public function acceptances()
    // {
    //     return $this->hasMany(TermsAcceptance::class);
    // }

    /**
     * Scope a query to only include active terms.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include current effective terms.
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
     * Get the currently active terms for a specific locale.
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
     * Check if this terms and condition is currently effective.
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
     * Activate this terms and condition (deactivate others).
     */
    public function activate(): bool
    {
        return $this->getConnection()->transaction(function () {
            // Deactivate all other terms for the same locale
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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-set effective date when activating if not set
        static::saving(function ($terms) {
            if ($terms->is_active && is_null($terms->effective_date)) {
                $terms->effective_date = Carbon::now();
            }
        });
    }
}