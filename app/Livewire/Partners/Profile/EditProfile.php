<?php

namespace App\Livewire\Partners\Profile;

use App\Models\Partner;
use App\Models\PartnerOwner;
use App\Models\PartnerTown;
use App\Models\Town;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditProfile extends Component
{
    use WithFileUploads;

    public $partner;

    // Basic Information
    public $partner_type;
    public $company_name;
    public $registration_number;
    public $kra_pin;

    // Contact Information
    public $phone;
    public $email;
    public $address;

    // Document Uploads
    public $registration_certificate;
    public $pin_certificate;
    public $compliance_certificate;
    public $insurance_certificate;
    public $drivers_certificate;

    // Current documents (for display)
    public $current_registration_certificate;
    public $current_pin_certificate;
    public $current_compliance_certificate;
    public $current_insurance_certificate;
    public $current_drivers_certificate;

    // Transport Partner Specific Fields
    public $fleet_count;
    public $fleet_ownership;
    public $fleet_insured;
    public $fleets_compliant;
    public $driver_count;
    public $drivers_compliant;

    public $has_motorcycles = false;
    public $has_vans = false;
    public $has_trucks = false;
    public $has_refrigerated = false;
    public $other_fleet_types;

    public $maximum_daily_capacity;
    public $maximum_distance;
    public $can_handle_fragile = false;
    public $can_handle_perishable = false;
    public $can_handle_valuables = false;

    // Pickup-Dropoff Partner Specific Fields
    public $points_count;
    public $points_have_phone = false;
    public $points_have_computer = false;
    public $points_have_internet = false;
    public $officers_knowledgeable = false;
    public $points_compliant = false;

    public $operating_hours;
    public $storage_facility_type;
    public $security_measures;
    public $insurance_coverage;

    // Common Operational Fields
    public $has_computer = false;
    public $has_internet = false;
    public $booking_emails = [];
    public $has_dedicated_allocator = false;
    public $allocator_name;
    public $allocator_phone;

    // Additional Information
    public $years_in_operation;
    public $previous_courier_experience;
    public $insurance_coverage_amount;
    public $safety_measures;
    public $tracking_system;
    public $additional_notes;

    // Service Areas
    public $service_towns = [];
    public $availableTowns = [];

    // System
    public $verification_status;

    // Temporary variables for new email
    public $new_email = '';

    protected function rules()
    {
        $partnerId = $this->partner->id;
        $rules = [
            // Basic Information
            'partner_type' => 'required|in:transport,pickup_dropoff',
            'company_name' => 'required|string|max:255',
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('partners')->ignore($partnerId)
            ],
            'kra_pin' => [
                'required',
                'string',
                'max:20',
                Rule::unique('partners')->ignore($partnerId)
            ],

            // Document uploads
            'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'insurance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'drivers_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

            // Common Operational Fields
            'has_computer' => 'boolean',
            'has_internet' => 'boolean',
            'booking_emails' => 'array',
            'booking_emails.*' => 'email',
            'has_dedicated_allocator' => 'boolean',
            'allocator_name' => 'nullable|string|max:255',
            'allocator_phone' => 'nullable|string|max:20',

            // Additional Information
            'years_in_operation' => 'nullable|integer|min:0|max:100',
            'previous_courier_experience' => 'nullable|string',
            'insurance_coverage_amount' => 'nullable|numeric|min:0',
            'safety_measures' => 'nullable|string',
            'tracking_system' => 'nullable|string',
            'additional_notes' => 'nullable|string',

            // Service Areas
            'service_towns' => 'array',

            // System
            'verification_status' => 'required|in:pending,verified,rejected,suspended',
        ];

        // Transport Partner Specific Rules
        if ($this->partner_type === 'transport') {
            $rules = array_merge($rules, [
                'fleet_count' => 'required|integer|min:0',
                'fleet_ownership' => 'required|in:owned,subcontracted,both',
                'fleet_insured' => 'boolean',
                'fleets_compliant' => 'boolean',
                'driver_count' => 'required|integer|min:0',
                'drivers_compliant' => 'boolean',

                'has_motorcycles' => 'boolean',
                'has_vans' => 'boolean',
                'has_trucks' => 'boolean',
                'has_refrigerated' => 'boolean',
                'other_fleet_types' => 'nullable|string|max:500',

                'maximum_daily_capacity' => 'nullable|integer|min:0',
                'maximum_distance' => 'nullable|numeric|min:0',
                'can_handle_fragile' => 'boolean',
                'can_handle_perishable' => 'boolean',
                'can_handle_valuables' => 'boolean',
            ]);
        }

        // Pickup-Dropoff Partner Specific Rules
        if ($this->partner_type === 'pickup_dropoff') {
            $rules = array_merge($rules, [
                'points_count' => 'required|integer|min:1',
                'points_have_phone' => 'boolean',
                'points_have_computer' => 'boolean',
                'points_have_internet' => 'boolean',
                'officers_knowledgeable' => 'boolean',
                'points_compliant' => 'boolean',

                'operating_hours' => 'nullable|string|max:255',
                'storage_facility_type' => 'nullable|string|max:255',
                'security_measures' => 'nullable|string',
                'insurance_coverage' => 'nullable|string',

                'maximum_capacity_per_day' => 'nullable|integer|min:0',
            ]);
        }

        return $rules;
    }

    protected $messages = [
        'registration_number.unique' => 'This registration number is already registered.',
        'kra_pin.unique' => 'This KRA PIN is already registered.',
        'booking_emails.*.email' => 'Each booking email must be a valid email address.',
        'registration_certificate.max' => 'Registration certificate must not exceed 5MB.',
        'pin_certificate.max' => 'PIN certificate must not exceed 5MB.',
    ];

    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner;
        $this->loadPartnerData();
        $this->availableTowns = Town::orderBy('name')->get();
    }

    public function loadPartnerData()
    {
        $this->partner_type = $this->partner->partner_type;
        $this->company_name = $this->partner->company_name;
        $this->registration_number = $this->partner->registration_number;
        $this->kra_pin = $this->partner->kra_pin;

        // Load current documents
        $this->current_registration_certificate = $this->partner->registration_certificate_path;
        $this->current_pin_certificate = $this->partner->pin_certificate_path;
        $this->current_compliance_certificate = $this->partner->compliance_certificate_path;
        $this->current_insurance_certificate = $this->partner->insurance_certificate_path;
        $this->current_drivers_certificate = $this->partner->drivers_certificate_path;

        // Transport Partner Data
        if ($this->partner_type === 'transport') {
            $this->fleet_count = $this->partner->fleet_count;
            $this->fleet_ownership = $this->partner->fleet_ownership;
            $this->fleet_insured = $this->partner->fleet_insured ?? false;
            $this->fleets_compliant = $this->partner->fleets_compliant ?? false;
            $this->driver_count = $this->partner->driver_count;
            $this->drivers_compliant = $this->partner->drivers_compliant ?? false;

            $this->has_motorcycles = $this->partner->has_motorcycles ?? false;
            $this->has_vans = $this->partner->has_vans ?? false;
            $this->has_trucks = $this->partner->has_trucks ?? false;
            $this->has_refrigerated = $this->partner->has_refrigerated ?? false;
            $this->other_fleet_types = $this->partner->other_fleet_types;

            $this->maximum_daily_capacity = $this->partner->maximum_daily_capacity;
            $this->maximum_distance = $this->partner->maximum_distance;
            $this->can_handle_fragile = $this->partner->can_handle_fragile ?? false;
            $this->can_handle_perishable = $this->partner->can_handle_perishable ?? false;
            $this->can_handle_valuables = $this->partner->can_handle_valuables ?? false;
        }

        // Pickup-Dropoff Partner Data
        if ($this->partner_type === 'pickup_dropoff') {
            $this->points_count = $this->partner->points_count;
            $this->points_have_phone = $this->partner->points_have_phone ?? false;
            $this->points_have_computer = $this->partner->points_have_computer ?? false;
            $this->points_have_internet = $this->partner->points_have_internet ?? false;
            $this->officers_knowledgeable = $this->partner->officers_knowledgeable ?? false;
            $this->points_compliant = $this->partner->points_compliant ?? false;

            $this->operating_hours = $this->partner->operating_hours;
            $this->storage_facility_type = $this->partner->storage_facility_type;
            $this->security_measures = $this->partner->security_measures;
            $this->insurance_coverage = $this->partner->insurance_coverage;
        }

        // Common Data
        $this->has_computer = $this->partner->has_computer ?? false;
        $this->has_internet = $this->partner->has_internet ?? false;
        $this->booking_emails = $this->partner->booking_emails ?? [];
        $this->has_dedicated_allocator = $this->partner->has_dedicated_allocator ?? false;
        $this->allocator_name = $this->partner->allocator_name;
        $this->allocator_phone = $this->partner->allocator_phone;

        $this->years_in_operation = $this->partner->years_in_operation;
        $this->previous_courier_experience = $this->partner->previous_courier_experience;
        $this->insurance_coverage_amount = $this->partner->insurance_coverage_amount;
        $this->safety_measures = $this->partner->safety_measures;
        $this->tracking_system = $this->partner->tracking_system;
        $this->additional_notes = $this->partner->additional_notes;

        $this->verification_status = $this->partner->verification_status;

        // Load service towns
        $this->service_towns = $this->partner->towns->pluck('town_id')->toArray();
    }

    public function addBookingEmail()
    {
        if ($this->new_email && filter_var($this->new_email, FILTER_VALIDATE_EMAIL)) {
            if (!in_array($this->new_email, $this->booking_emails)) {
                $this->booking_emails[] = $this->new_email;
                $this->new_email = '';
            }
        }
    }

    public function removeBookingEmail($index)
    {
        unset($this->booking_emails[$index]);
        $this->booking_emails = array_values($this->booking_emails);
    }

    public function updateProfile()
    {
        $this->validate();

        try {
            $data = [
                // Basic Information
                'partner_type' => $this->partner_type,
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                'kra_pin' => $this->kra_pin,

                // Common Operational Fields
                'has_computer' => $this->has_computer,
                'has_internet' => $this->has_internet,
                'booking_emails' => $this->booking_emails,
                'has_dedicated_allocator' => $this->has_dedicated_allocator,
                'allocator_name' => $this->allocator_name,
                'allocator_phone' => $this->allocator_phone,

                // Additional Information
                'years_in_operation' => $this->years_in_operation,
                'previous_courier_experience' => $this->previous_courier_experience,
                'insurance_coverage_amount' => $this->insurance_coverage_amount,
                'safety_measures' => $this->safety_measures,
                'tracking_system' => $this->tracking_system,
                'additional_notes' => $this->additional_notes,

                // System
                'verification_status' => $this->verification_status,
            ];

            // Handle document uploads
            if ($this->registration_certificate) {
                $path = $this->registration_certificate->store('documents/partners', 'public');
                $data['registration_certificate_path'] = $path;
            }

            if ($this->pin_certificate) {
                $path = $this->pin_certificate->store('documents/partners', 'public');
                $data['pin_certificate_path'] = $path;
            }

            if ($this->compliance_certificate) {
                $path = $this->compliance_certificate->store('documents/partners', 'public');
                $data['compliance_certificate_path'] = $path;
            }

            if ($this->insurance_certificate) {
                $path = $this->insurance_certificate->store('documents/partners', 'public');
                $data['insurance_certificate_path'] = $path;
            }

            if ($this->drivers_certificate) {
                $path = $this->drivers_certificate->store('documents/partners', 'public');
                $data['drivers_certificate_path'] = $path;
            }

            // Transport Partner Specific Data
            if ($this->partner_type === 'transport') {
                $data = array_merge($data, [
                    'fleet_count' => $this->fleet_count,
                    'fleet_ownership' => $this->fleet_ownership,
                    'fleet_insured' => $this->fleet_insured,
                    'fleets_compliant' => $this->fleets_compliant,
                    'driver_count' => $this->driver_count,
                    'drivers_compliant' => $this->drivers_compliant,

                    'has_motorcycles' => $this->has_motorcycles,
                    'has_vans' => $this->has_vans,
                    'has_trucks' => $this->has_trucks,
                    'has_refrigerated' => $this->has_refrigerated,
                    'other_fleet_types' => $this->other_fleet_types,

                    'maximum_daily_capacity' => $this->maximum_daily_capacity,
                    'maximum_distance' => $this->maximum_distance,
                    'can_handle_fragile' => $this->can_handle_fragile,
                    'can_handle_perishable' => $this->can_handle_perishable,
                    'can_handle_valuables' => $this->can_handle_valuables,
                ]);
            }

            // Pickup-Dropoff Partner Specific Data
            if ($this->partner_type === 'pickup_dropoff') {
                $data = array_merge($data, [
                    'points_count' => $this->points_count,
                    'points_have_phone' => $this->points_have_phone,
                    'points_have_computer' => $this->points_have_computer,
                    'points_have_internet' => $this->points_have_internet,
                    'officers_knowledgeable' => $this->officers_knowledgeable,
                    'points_compliant' => $this->points_compliant,

                    'operating_hours' => $this->operating_hours,
                    'storage_facility_type' => $this->storage_facility_type,
                    'security_measures' => $this->security_measures,
                    'insurance_coverage' => $this->insurance_coverage,
                ]);
            }

            // Update partner
            $this->partner->update($data);

            // Update service towns
            $this->partner->towns()->delete();
            foreach ($this->service_towns as $townId) {
                PartnerTown::create([
                    'partner_id' => $this->partner->id,
                    'town_id' => $townId,
                ]);
            }

            session()->flash('success', 'Profile updated successfully!');
            $this->loadPartnerData();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.profile.edit-profile');
    }
}
