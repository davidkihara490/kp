<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    /** @use HasFactory<\Database\Factories\PartnerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partner_type',
        'owner_id',
        'incharge_id',
        // Company Details
        'company_name',
        'registration_number',
        'registration_certificate_path',
        'kra_pin',
        'pin_certificate_path',

        // Point Details
        'points_count',
        'points_have_phone',
        'points_have_computer',
        'points_have_internet',
        'officers_knowledgeable',
        'points_compliant',
        'compliance_certificate_path',

        // Additional Information
        'operating_hours',
        'maximum_capacity_per_day',
        'storage_facility_type',
        'security_measures',
        'insurance_coverage',
        'additional_notes',

        // System Fields
        'onboarding_step',
        'verification_status',

        // Fleet Details
        'fleet_count',
        'fleet_ownership', // owned, subcontracted, both
        'fleet_insured',
        'insurance_certificate_path',
        'fleets_compliant',
        'compliance_certificate_path',
        'driver_count',
        'drivers_compliant',
        'drivers_certificate_path',

        // fleet Types
        'has_motorcycles',
        'has_vans',
        'has_trucks',
        'has_refrigerated',
        'other_fleet_types',

        // Operation Details
        'has_computer',
        'has_internet',
        'booking_emails',
        'has_dedicated_allocator',
        'allocator_name',
        'allocator_phone',

        // Capacity & Coverage
        'maximum_daily_capacity',
        'maximum_distance',
        'can_handle_fragile',
        'can_handle_perishable',
        'can_handle_valuables',

        // Additional Information
        'years_in_operation',
        'previous_courier_experience',
        'insurance_coverage_amount',
        'safety_measures',
        'tracking_system',
        'additional_notes',

        // System Fields
        'onboarding_step',
        'verification_status',

    ];

    protected $casts = [
        'terms_and_conditions' => 'boolean',
        'privacy_policy' => 'boolean',
        'points_have_phone' => 'boolean',
        'points_have_computer' => 'boolean',
        'points_have_internet' => 'boolean',
        'officers_knowledgeable' => 'boolean',
        'points_compliant' => 'boolean',
        'approved_at' => 'datetime',
        'service_towns' => 'array',
        'fleet_insured' => 'boolean',
        'fleet_compliant' => 'boolean',
        'drivers_compliant' => 'boolean',
        'has_computer' => 'boolean',
        'has_internet' => 'boolean',
        'has_dedicated_allocator' => 'boolean',
        'has_motorcycles' => 'boolean',
        'has_vans' => 'boolean',
        'has_trucks' => 'boolean',
        'has_refrigerated' => 'boolean',
        'can_handle_fragile' => 'boolean',
        'can_handle_perishable' => 'boolean',
        'can_handle_valuables' => 'boolean',
        'booking_emails' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function inCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'incharge_id');
    }
    public function towns(): HasMany
    {
        return $this->hasMany(PartnerTown::class);
    }
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'partner_id');
    }
    public function fleets(): HasMany
    {
        return $this->hasMany(Fleet::class,'partner_id');
    }

        public function pickUpAndDropOffPoints(): HasMany{
        return $this->hasMany(PickUpAndDropOffPoint::class, 'partner_id');
    }

    public function parcelHandlingAssistants(): HasMany{
        return $this->hasMany(ParcelHandlingAssistant::class, 'partner_id');
    }
}