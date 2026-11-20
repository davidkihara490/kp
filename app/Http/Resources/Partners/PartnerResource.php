<?php

namespace App\Http\Resources\Partners;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'partner_type'  => $this->partner_type,
            'owner_id'      => $this->owner_id,

            // Company Details
            'company' => [
                'name'                    => $this->company_name,
                'registration_number'     => $this->registration_number,
                'registration_certificate' => $this->registration_certificate_path,
                'kra_pin'                 => $this->kra_pin,
                'pin_certificate'         => $this->pin_certificate_path,
            ],

            // Point Details
            'points' => [
                'count'               => $this->points_count,
                'have_phone'          => (bool) $this->points_have_phone,
                'have_computer'       => (bool) $this->points_have_computer,
                'have_internet'       => (bool) $this->points_have_internet,
                'officers_knowledgeable' => (bool) $this->officers_knowledgeable,
                'compliant'           => (bool) $this->points_compliant,
                'compliance_certificate' => $this->compliance_certificate_path,
            ],

            // Operations / Capacity
            'operations' => [
                'operating_hours'         => $this->operating_hours,
                'maximum_capacity_per_day' => $this->maximum_capacity_per_day,
                'maximum_daily_capacity'  => $this->maximum_daily_capacity,
                'maximum_distance'        => $this->maximum_distance,
                'storage_facility_type'   => $this->storage_facility_type,
                'security_measures'       => $this->security_measures,
                'insurance_coverage'      => $this->insurance_coverage,
                'insurance_coverage_amount' => $this->insurance_coverage_amount,
                'years_in_operation'      => $this->years_in_operation,
                'previous_courier_experience' => $this->previous_courier_experience,
                'safety_measures'         => $this->safety_measures,
                'tracking_system'         => $this->tracking_system,
                'has_computer'            => (bool) $this->has_computer,
                'has_internet'            => (bool) $this->has_internet,
                'has_dedicated_allocator' => (bool) $this->has_dedicated_allocator,
                'allocator'               => $this->has_dedicated_allocator ? [
                    'name'  => $this->allocator_name,
                    'phone' => $this->allocator_phone,
                ] : null,
                'booking_emails'          => $this->booking_emails,
            ],

            // Coverage / Capabilities
            'coverage' => [
                'service_towns' => $this->service_towns,
                'can_handle_fragile'    => (bool) $this->can_handle_fragile,
                'can_handle_perishable' => (bool) $this->can_handle_perishable,
                'can_handle_valuables'  => (bool) $this->can_handle_valuables,
            ],

            // Fleet Details
            'fleet' => [
                'count'                => $this->fleet_count,
                'ownership'            => $this->fleet_ownership,
                'insured'              => (bool) $this->fleet_insured,
                'insurance_certificate' => $this->insurance_certificate_path,
                // NOTE: column is `fleets_compliant` in $fillable; cast is named `fleet_compliant`
                'compliant'            => (bool) $this->fleets_compliant,
                'compliance_certificate' => $this->compliance_certificate_path,
                'driver_count'         => $this->driver_count,
                'drivers_compliant'    => (bool) $this->drivers_compliant,
                'drivers_certificate'  => $this->drivers_certificate_path,
                'types' => [
                    'motorcycles'  => (bool) $this->has_motorcycles,
                    'vans'         => (bool) $this->has_vans,
                    'trucks'       => (bool) $this->has_trucks,
                    'refrigerated' => (bool) $this->has_refrigerated,
                    'other'        => $this->other_fleet_types,
                ],
            ],

            // Additional
            'additional_notes' => $this->additional_notes,

            // System fields
            'onboarding_step'     => $this->onboarding_step,
            'verification_status' => $this->verification_status,
            'approved_at'         => $this->approved_at?->toIso8601String(),
            'counts' => $this->mergeWhen(
                $this->relationLoaded('drivers') || $this->relationLoaded('fleets') || $this->relationLoaded('towns'),
                fn () => [
                    'counts' => [
                        'drivers' => $this->whenLoaded('drivers', fn () => $this->drivers->count()),
                        'fleets'  => $this->whenLoaded('fleets', fn () => $this->fleets->count()),
                        'towns'   => $this->whenLoaded('towns', fn () => $this->towns->count()),
                    ],
                ]
            ),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}