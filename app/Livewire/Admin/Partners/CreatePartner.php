<?php

namespace App\Livewire\Admin\Partners;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreatePartner extends Component
{
    use WithFileUploads;

    // Owner Information
    public $owner_id;
    public $new_owner_email;
    public $new_owner_name;
    public $new_owner_phone;
    
    // Basic Information
    public $partner_type;
    public $company_name;
    public $registration_number;
    public $kra_pin;
    
    // File Uploads
    public $registration_certificate;
    public $pin_certificate;
    public $compliance_certificate;
    public $insurance_certificate;
    public $drivers_certificate;
    
    // Point Details
    public $points_count;
    public $points_have_phone = false;
    public $points_have_computer = false;
    public $points_have_internet = false;
    public $officers_knowledgeable = false;
    public $points_compliant = false;
    
    // Additional Information
    public $operating_hours;
    public $maximum_capacity_per_day;
    public $storage_facility_type;
    public $security_measures;
    public $insurance_coverage;
    public $additional_notes;
    
    // System Fields
    public $onboarding_step = 1;
    public $verification_status = 'pending';
    
    // Fleet Details
    public $fleet_count;
    public $fleet_ownership;
    public $fleet_insured = false;
    public $fleets_compliant = false;
    public $driver_count;
    public $drivers_compliant = false;
    
    // Fleet Types
    public $has_motorcycles = false;
    public $has_vans = false;
    public $has_trucks = false;
    public $has_refrigerated = false;
    public $other_fleet_types;
    
    // Operation Details
    public $has_computer = false;
    public $has_internet = false;
    public $booking_emails = [];
    public $has_dedicated_allocator = false;
    public $allocator_name;
    public $allocator_phone;
    
    // Capacity & Coverage
    public $maximum_daily_capacity;
    public $maximum_distance;
    public $can_handle_fragile = false;
    public $can_handle_perishable = false;
    public $can_handle_valuables = false;
    
    // Additional Information
    public $years_in_operation;
    public $previous_courier_experience;
    public $insurance_coverage_amount;
    public $safety_measures;
    public $tracking_system;
    
    // Email field for dynamic input
    public $email_input = '';
    
    protected $rules = [
        // Owner Information
        'owner_id' => 'nullable|exists:users,id',
        // 'new_owner_email' => 'required_without:owner_id|email|unique:users,email',
        // 'new_owner_name' => 'required_without:owner_id|string|max:255',
        // 'new_owner_phone' => 'required_without:owner_id|string|max:20',
        
        // Basic Information
        // 'partner_type' => 'required|in:individual,company,organization',
        'company_name' => 'required|string|max:255',
        'registration_number' => 'required|string|max:100|unique:partners,registration_number',
        'kra_pin' => 'required|string|max:50|unique:partners,kra_pin',
        
        // File Uploads
        // 'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        // 'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        // 'compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        // 'insurance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        // 'drivers_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        
        // Point Details
        // 'points_count' => 'nullable|integer|min:0',
        // 'points_have_phone' => 'boolean',
        // 'points_have_computer' => 'boolean',
        // 'points_have_internet' => 'boolean',
        // 'officers_knowledgeable' => 'boolean',
        // 'points_compliant' => 'boolean',
        
        // // Additional Information
        // 'operating_hours' => 'nullable|string|max:255',
        // 'maximum_capacity_per_day' => 'nullable|integer|min:0',
        // 'storage_facility_type' => 'nullable|string|max:255',
        // 'security_measures' => 'nullable|string',
        // 'insurance_coverage' => 'nullable|string|max:255',
        // 'additional_notes' => 'nullable|string',
        
        // // System Fields
        // 'onboarding_step' => 'integer|min:1|max:5',
        // 'verification_status' => 'in:pending,verified,rejected,suspended',
        
        // // Fleet Details
        // 'fleet_count' => 'nullable|integer|min:0',
        // 'fleet_ownership' => 'nullable|in:owned,subcontracted,both',
        // 'fleet_insured' => 'boolean',
        // 'fleets_compliant' => 'boolean',
        // 'driver_count' => 'nullable|integer|min:0',
        // 'drivers_compliant' => 'boolean',
        
        // // Fleet Types
        // 'has_motorcycles' => 'boolean',
        // 'has_vans' => 'boolean',
        // 'has_trucks' => 'boolean',
        // 'has_refrigerated' => 'boolean',
        // 'other_fleet_types' => 'nullable|string|max:255',
        
        // // Operation Details
        // 'has_computer' => 'boolean',
        // 'has_internet' => 'boolean',
        // 'booking_emails' => 'nullable|array',
        // 'booking_emails.*' => 'email',
        // 'has_dedicated_allocator' => 'boolean',
        // 'allocator_name' => 'required_if:has_dedicated_allocator,true|nullable|string|max:255',
        // 'allocator_phone' => 'required_if:has_dedicated_allocator,true|nullable|string|max:20',
        
        // // Capacity & Coverage
        // 'maximum_daily_capacity' => 'nullable|integer|min:0',
        // 'maximum_distance' => 'nullable|integer|min:0',
        // 'can_handle_fragile' => 'boolean',
        // 'can_handle_perishable' => 'boolean',
        // 'can_handle_valuables' => 'boolean',
        
        // // Additional Information
        // 'years_in_operation' => 'nullable|integer|min:0',
        // 'previous_courier_experience' => 'nullable|string',
        // 'insurance_coverage_amount' => 'nullable|string|max:255',
        // 'safety_measures' => 'nullable|string',
        // 'tracking_system' => 'nullable|string|max:255',
    ];

    protected $messages = [
        // 'partner_type.required' => 'Please select the partner type.',
        'company_name.required' => 'Company name is required.',
        // 'registration_number.required' => 'Registration number is required.',
        // 'registration_number.unique' => 'This registration number is already registered.',
        // 'kra_pin.required' => 'KRA PIN is required.',
        // 'kra_pin.unique' => 'This KRA PIN is already registered.',
        // 'new_owner_email.required_without' => 'Either select an existing user or provide owner email.',
        // 'new_owner_email.unique' => 'This email is already registered.',
        // 'allocator_name.required_if' => 'Allocator name is required when dedicated allocator is selected.',
        // 'allocator_phone.required_if' => 'Allocator phone is required when dedicated allocator is selected.',
        // 'booking_emails.*.email' => 'Each booking email must be a valid email address.',
    ];

    public function mount()
    {
        $this->booking_emails = [];
    }

    public function addEmail()
    {
        if ($this->email_input && filter_var($this->email_input, FILTER_VALIDATE_EMAIL)) {
            if (!in_array($this->email_input, $this->booking_emails)) {
                $this->booking_emails[] = $this->email_input;
            }
            $this->email_input = '';
        }
    }

    public function removeEmail($index)
    {
        unset($this->booking_emails[$index]);
        $this->booking_emails = array_values($this->booking_emails);
    }

    public function save()
    {
        $this->validate();

        try {
            // Create or get owner
            // $owner = null;
            // if ($this->owner_id) {
            //     $owner = User::find($this->owner_id);
            // } elseif ($this->new_owner_email) {
            //     $owner = User::create([
            //         'name' => $this->new_owner_name,
            //         'email' => $this->new_owner_email,
            //         'phone_number' => $this->new_owner_phone,
            //         'password' => Hash::make(Str::random(12)),
            //         'email_verified_at' => now(),
            //     ]);
            //     $owner->assignRole('partner');
            // }

            // Upload files
            // $registrationPath = $this->registration_certificate ? $this->registration_certificate->store('partners/registrations', 'public') : null;
            // $pinPath = $this->pin_certificate ? $this->pin_certificate->store('partners/pins', 'public') : null;
            // $compliancePath = $this->compliance_certificate ? $this->compliance_certificate->store('partners/compliance', 'public') : null;
            // $insurancePath = $this->insurance_certificate ? $this->insurance_certificate->store('partners/insurance', 'public') : null;
            // $driversPath = $this->drivers_certificate ? $this->drivers_certificate->store('partners/drivers', 'public') : null;

            // Create partner
            $partner = Partner::create([
                'user_id' => $this->owner_id,
                'partner_type' => $this->partner_type,
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                // 'registration_certificate_path' => $registrationPath,
                'kra_pin' => $this->kra_pin,
                // 'pin_certificate_path' => $pinPath,
                
                // Point Details
                // 'points_count' => $this->points_count,
                // 'points_have_phone' => $this->points_have_phone,
                // 'points_have_computer' => $this->points_have_computer,
                // 'points_have_internet' => $this->points_have_internet,
                // 'officers_knowledgeable' => $this->officers_knowledgeable,
                // 'points_compliant' => $this->points_compliant,
                // 'compliance_certificate_path' => $compliancePath,
                
                // Additional Information
                // 'operating_hours' => $this->operating_hours,
                // 'maximum_capacity_per_day' => $this->maximum_capacity_per_day,
                // 'storage_facility_type' => $this->storage_facility_type,
                // 'security_measures' => $this->security_measures,
                // 'insurance_coverage' => $this->insurance_coverage,
                // 'additional_notes' => $this->additional_notes,
                
                // System Fields
                // 'onboarding_step' => $this->onboarding_step,
                'verification_status' => $this->verification_status,
                
                // Fleet Details
                // 'fleet_count' => $this->fleet_count,
                // 'fleet_ownership' => $this->fleet_ownership,
                // 'fleet_insured' => $this->fleet_insured,
                // 'insurance_certificate_path' => $insurancePath,
                // 'fleets_compliant' => $this->fleets_compliant,
                // 'driver_count' => $this->driver_count,
                // 'drivers_compliant' => $this->drivers_compliant,
                // 'drivers_certificate_path' => $driversPath,
                
                // Fleet Types
                // 'has_motorcycles' => $this->has_motorcycles,
                // 'has_vans' => $this->has_vans,
                // 'has_trucks' => $this->has_trucks,
                // 'has_refrigerated' => $this->has_refrigerated,
                // 'other_fleet_types' => $this->other_fleet_types,
                
                // Operation Details
                // 'has_computer' => $this->has_computer,
                // 'has_internet' => $this->has_internet,
                // 'booking_emails' => $this->booking_emails,
                // 'has_dedicated_allocator' => $this->has_dedicated_allocator,
                // 'allocator_name' => $this->allocator_name,
                // 'allocator_phone' => $this->allocator_phone,
                
                // Capacity & Coverage
                // 'maximum_daily_capacity' => $this->maximum_daily_capacity,
                // 'maximum_distance' => $this->maximum_distance,
                // 'can_handle_fragile' => $this->can_handle_fragile,
                // 'can_handle_perishable' => $this->can_handle_perishable,
                // 'can_handle_valuables' => $this->can_handle_valuables,
                
                // Additional Information
                // 'years_in_operation' => $this->years_in_operation,
                // 'previous_courier_experience' => $this->previous_courier_experience,
                // 'insurance_coverage_amount' => $this->insurance_coverage_amount,
                // 'safety_measures' => $this->safety_measures,
                // 'tracking_system' => $this->tracking_system,
            ]);

            session()->flash('success', 'Partner created successfully!');
            return redirect()->route('admin.partners.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating partner: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $existingUsers = User::where('user_type','admin')->orderBy('first_name')->get();
        
        return view('livewire.admin.partners.create-partner', [
            'existingUsers' => $existingUsers
        ]);
    }
}