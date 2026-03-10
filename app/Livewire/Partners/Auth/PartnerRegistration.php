<?php

namespace App\Livewire\Partners\Auth;

use App\Jobs\SendWelcomeEmail;
use App\Models\Bank;
use App\Models\Town;
use App\Models\County;
use App\Models\Partner;
use App\Models\PartnerFinanceAccount;
use App\Models\PartnerInCharge;
use App\Models\PartnerOwner;
use App\Models\PartnerTown;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PartnerRegistration extends Component
{
    use WithFileUploads;
    public $step = 1;
    public $partnerType;
    // Common Fields
    public $owner_first_name;
    public $owner_second_name;
    public $owner_last_name;
    public $owner_email;
    public $owner_phone_number;
    public $terms_and_conditions = false;
    public $privacy_policy = false;
    // Company Details
    public $company_name;
    public $registration_number;
    public $registration_certificate;
    public $kra_pin;
    public $pin_certificate;
    public $responsible_officer_first_name;
    public $responsible_officer_last_name;
    public $responsible_officer_phone;
    public $responsible_officer_email;
    // Finance Details
    public $bank_account_number;
    public $bank_account_name;
    public $bank_name;
    public $bank_branch;
    public $finance_email;
    // Transport Partner Specific
    public $vehicle_count = 1;
    public $vehicle_ownership = 'owned';
    public $vehicles_insured = false;
    public $insurance_certificate;
    public $vehicles_compliant = false;
    public $compliance_certificate;
    public $driver_count = 1;
    public $drivers_compliant = false;
    public $drivers_certificate;
    public $has_motorcycles = false;
    public $has_vans = false;
    public $has_trucks = false;
    public $has_refrigerated = false;
    public $other_vehicle_types = '';
    public $has_computer = false;
    public $has_internet = false;
    public $booking_emails = [];
    public $booking_email_input = '';
    public $has_dedicated_allocator = false;
    public $allocator_name = '';
    public $allocator_phone = '';
    public $maximum_daily_capacity = 50;
    public $maximum_distance = 100;
    public $operating_radius = 50;
    public $can_handle_fragile = false;
    public $can_handle_perishable = false;
    public $can_handle_valuables = false;
    // Station Partner Specific
    public $points_count = 1;
    public $points_have_phone = false;
    public $points_have_computer = false;
    public $points_have_internet = false;
    public $officers_knowledgeable = false;
    public $points_compliant = false;
    public $station_compliance_certificate;
    public $operating_hours = '8:00 AM - 6:00 PM';
    public $maximum_capacity_per_day = 100;
    public $storage_facility_type = 'standard';
    public $security_measures = '';
    public $insurance_coverage = '';
    // Service Areas
    public $selectedCounties = [];
    public $selectedSubcounties = [];
    public $selectedTowns = [];
    public $availableTowns = [];
    public $serviceTowns = [];
    // Additional
    public $additional_notes = '';
    // System
    public $submissionSuccess = false;
    public $partnerId = null;
    public $banks = [];
    protected $rules = [
        // Step 1: Partner Type
        'partnerType' => 'required|in:transport,pickup-dropoff',

        // Step 2: Personal Information
        'owner_first_name' => 'required|min:2|max:50',
        'owner_second_name' => 'nullable|min:2|max:50',
        'owner_last_name' => 'required|min:2|max:50',
        'owner_email' => 'required|email|unique:users,email',
        'owner_phone_number' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed regex
        'terms_and_conditions' => 'accepted',
        'privacy_policy' => 'accepted',

        // Step 3: Company Details
        'company_name' => 'required|min:3|max:200',
        'registration_number' => 'required|min:3|max:50',
        'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'kra_pin' => ['required', 'min:11', 'max:11', 'regex:/^[A-Z]\d{9}[A-Z]$/'], // Fixed regex
        'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'responsible_officer_first_name' => 'required|min:3|max:100',
        'responsible_officer_last_name' => 'required|min:3|max:100',

        'responsible_officer_phone' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed regex
        'responsible_officer_email' => 'nullable|email',

        // Step 4: Finance Details
        'bank_account_number' => 'required|numeric|digits_between:10,20',
        'bank_account_name' => 'required|min:3|max:100',
        'bank_name' => 'required|min:3|max:100',
        'bank_branch' => 'required|min:3|max:100',
        'finance_email' => 'nullable|email',

        // Transport Specific Rules
        'vehicle_count' => 'nullable|integer|min:1|max:1000',
        'vehicle_ownership' => 'nullable|in:owned,subcontracted,both',
        'vehicles_insured' => 'nullable|boolean',
        'insurance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'vehicles_compliant' => 'nullable|boolean',
        'compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'driver_count' => 'nullable|integer|min:1|max:100',
        'drivers_compliant' => 'nullable|boolean',
        'drivers_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',

        // Station Specific Rules
        'points_count' => 'nullable|integer|min:1|max:100',
        'points_have_phone' => 'nullable|boolean',
        'points_have_computer' => 'nullable|boolean',
        'points_have_internet' => 'nullable|boolean',
        'officers_knowledgeable' => 'nullable|boolean',
        'points_compliant' => 'nullable|boolean',
        // 'station_compliance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ];
    protected $messages = [
        'phone_number.regex' => 'Please enter a valid Kenyan phone number (e.g., 0712345678 or +254712345678)',
        'kra_pin.regex' => 'Please enter a valid KRA PIN (format: A123456789Z)',
        'terms_and_conditions.accepted' => 'You must accept the terms and conditions',
        'privacy_policy.accepted' => 'You must accept the privacy policy',
        'registration_certificate.required' => 'Registration certificate is required',
        'pin_certificate.required' => 'KRA PIN certificate is required',
        'insurance_certificate.required_if' => 'Insurance certificate is required when vehicles are insured',
        'compliance_certificate.required_if' => 'Compliance certificate is required when vehicles are compliant',
        'drivers_certificate.required_if' => 'Drivers certificate is required when drivers are compliant',
        // 'station_compliance_certificate.required_if' => 'Compliance certificate is required when points are compliant',
        'selectedTowns.required' => 'Please select at least one service town',
    ];
    public function mount()
    {
        $this->banks = Bank::all();
        $this->selectedTowns = [];
        $this->availableTowns = Town::with('subCounty.county')
            ->orderBy('name')
            ->get()
            ->map(function ($town) {
                return [
                    'id' => $town->id,
                    'name' => $town->name,
                    'subcounty_id' => $town->subcounty_id,
                    'subcounty' => $town->subcounty ? [
                        'id' => $town->subcounty->id,
                        'name' => $town->subcounty->name,
                        'county_id' => $town->subcounty->county_id,
                        'county' => $town->subcounty->county ? [
                            'id' => $town->subcounty->county->id,
                            'name' => $town->subcounty->county->name,
                        ] : null,
                    ] : null,
                ];
            })
            ->groupBy(function ($town) {
                return $town['subCounty']['county']['name'] ?? 'Unknown';
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.partners.auth.partner-registration', [
            'counties' => County::with('subCounties.towns')->orderBy('name')->get(),
            'towns' => Town::orderBy('name')->get(),
            'availableTowns' => $this->availableTowns,
        ]);
    }

    public function nextStep()
    {
        $this->step++;
        // Scroll to top
        $this->dispatch('scroll-to-top');
    }

    public function previousStep()
    {
        $this->step--;
        $this->dispatch('scroll-to-top');
    }
    public function selectPartnerType($type)
    {
        $this->partnerType = $type;
        $this->nextStep();
    }

    public function addBookingEmail()
    {
        if (!empty($this->booking_email_input)) {
            $this->booking_emails[] = $this->booking_email_input;
            $this->booking_email_input = '';
        }
    }

    public function removeBookingEmail($index)
    {
        unset($this->booking_emails[$index]);
        $this->booking_emails = array_values($this->booking_emails);
    }

    public function updatedSelectedCounties($value)
    {
        $this->selectedSubcounties = [];
        $this->selectedTowns = [];
        $this->serviceTowns = [];
    }

    public function updatedSelectedSubcounties($value)
    {
        $this->selectedTowns = [];
        $this->serviceTowns = [];
    }

    public function updatedSelectedTowns($value)
    {
        $this->loadServiceTowns();
    }

    protected function loadServiceTowns()
    {
        if (empty($this->selectedTowns)) {
            $this->serviceTowns = [];
            return;
        }

        // Load towns from database based on selected IDs
        $this->serviceTowns = Town::whereIn('id', $this->selectedTowns)->get();
    }

    private function validateCurrentStep()
    {
        switch ($this->step) {
            case 1:
                $this->validateOnly('partnerType');
                break;

            case 2:
                $this->validate([
                    'owner_first_name' => 'required|min:2|max:50',
                    'owner_last_name' => 'required|min:2|max:50',
                    'owner_email' => 'required|email|unique:users,email',
                    'owner_phone_number' => 'required', // Fixed
                    // 'owner_phone_number' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed
                    'terms_and_conditions' => 'accepted',
                    'privacy_policy' => 'accepted',
                ]);
                break;

            case 3:
                $rules = [
                    'company_name' => 'required|min:3|max:200',
                    'registration_number' => 'required|min:3|max:50',
                    'registration_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'kra_pin' => ['required', 'min:11', 'max:11', 'regex:/^[A-Z]\d{9}[A-Z]$/'], // Fixed
                    'pin_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'responsible_officer_first_name' => 'required|min:3|max:100',
                    'responsible_officer_last_name' => 'required|min:3|max:100',

                    // 'responsible_officer_phone' => ['required', 'regex:/^(\+254|0)[1-9]\d{8}$/'], // Fixed
                    'responsible_officer_phone' => 'required', // Fixed

                    'responsible_officer_email' => 'nullable|email',
                ];

                $this->validate($rules);
                break;

            case 4:
                $rules = [
                    'bank_account_number' => 'required|numeric|digits_between:10,20',
                    'bank_account_name' => 'required|min:3|max:100',
                    'bank_name' => 'required|min:3|max:100',
                    'bank_branch' => 'required|min:3|max:100',
                    'finance_email' => 'nullable|email',
                ];

                $this->validate($rules);
                break;

            case 5:
                if ($this->partnerType === 'transport') {
                    $rules = [
                        'vehicle_count' => 'required|integer|min:1|max:1000',
                        'vehicle_ownership' => 'required|in:owned,subcontracted,both',
                        'vehicles_insured' => 'required|boolean',
                        'vehicles_compliant' => 'required|boolean',
                        'driver_count' => 'required|integer|min:1|max:100',
                        'drivers_compliant' => 'required|boolean',
                    ];

                    if ($this->vehicles_insured) {
                        $rules['insurance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }

                    if ($this->vehicles_compliant) {
                        $rules['compliance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }

                    if ($this->drivers_compliant) {
                        $rules['drivers_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                } else {
                    $rules = [
                        'points_count' => 'required|integer|min:1|max:100',
                        'points_have_phone' => 'required|boolean',
                        'points_have_computer' => 'required|boolean',
                        'points_have_internet' => 'required|boolean',
                        'officers_knowledgeable' => 'required|boolean',
                        'points_compliant' => 'required|boolean',
                    ];

                    if ($this->points_compliant) {
                        $rules['station_compliance_certificate'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
                    }
                }

                $this->validate($rules);
                break;

            case 6:
                $rules = [
                    'selectedTowns' => 'required|array|min:1',
                    'selectedTowns.*' => 'exists:towns,id',
                ];

                $this->validate($rules);
                break;
        }
    }
    public function submitRegistration()
    {
        $this->validateCurrentStep();

        // Check what type selectedTowns is
        if (!is_array($this->selectedTowns)) {
            Log::error('selectedTowns is not an array! Type: ' . gettype($this->selectedTowns));

            // Try to convert it to array if it's a string
            if (is_string($this->selectedTowns)) {
                $this->selectedTowns = json_decode($this->selectedTowns, true) ?? [];
                Log::info('Converted string to array:', $this->selectedTowns);
            } elseif ($this->selectedTowns === null) {
                $this->selectedTowns = [];
                Log::info('Set null to empty array');
            }
        }
        try {
            DB::beginTransaction();
            // Handle document uploads
            $documents = [];


            if ($this->registration_certificate) {
                $path = $this->registration_certificate->store(
                    'partners/' . $this->partnerType . '/documents',
                    'public'
                );

                $documents['registration_certificate_path'] = $path;
            }

            if ($this->pin_certificate) {
                $path = $this->pin_certificate->store(
                    'partners/' . $this->partnerType . '/documents',
                    'public'
                );

                $documents['pin_certificate_path'] = $path;
            }
            $data = [
                // Step 1: Partner Type
                'partner_type' => $this->partnerType,

                // Step 2: Personal Information
                'owner_first_name' => $this->owner_first_name,
                'owner_second_name' => $this->owner_second_name,
                'owner_last_name' => $this->owner_last_name,
                'owner_email' => $this->owner_email,
                'owner_phone_number' => $this->owner_phone_number,
                'terms_and_conditions' => $this->terms_and_conditions,
                'privacy_policy' => $this->privacy_policy,

                // Step 3: Company Details
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                'kra_pin' => $this->kra_pin,
                'responsible_officer_first_name' => $this->responsible_officer_first_name,
                'responsible_officer_last_name' => $this->responsible_officer_last_name,
                'responsible_officer_phone' => $this->responsible_officer_phone,
                'responsible_officer_email' => $this->responsible_officer_email,
                'registration_certificate' => $this->registration_certificate,
                'pin_certificate' => $this->pin_certificate,

                // Step 4: Finance Details
                'bank_account_number' => $this->bank_account_number,
                'bank_account_name' => $this->bank_account_name,
                'bank_name' => $this->bank_name,
                'bank_branch' => $this->bank_branch,
                'finance_email' => $this->finance_email,

                // Step 5: Fleet/Station Details
                'vehicle_count' => $this->vehicle_count ?? null,
                'vehicle_ownership' => $this->vehicle_ownership ?? null,
                'vehicles_insured' => $this->vehicles_insured ?? null,
                'vehicles_compliant' => $this->vehicles_compliant ?? null,
                'driver_count' => $this->driver_count ?? null,
                'drivers_compliant' => $this->drivers_compliant ?? null,
                'has_motorcycles' => $this->has_motorcycles ?? false,
                'has_vans' => $this->has_vans ?? false,
                'has_trucks' => $this->has_trucks ?? false,
                'has_refrigerated' => $this->has_refrigerated ?? false,
                'has_computer' => $this->has_computer ?? false,
                'has_internet' => $this->has_internet ?? false,
                'booking_emails' => $this->booking_emails ?? null,
                'has_dedicated_allocator' => $this->has_dedicated_allocator ?? false,
                'allocator_name' => $this->allocator_name ?? null,
                'allocator_phone' => $this->allocator_phone ?? null,
                'maximum_daily_capacity' => $this->maximum_daily_capacity ?? null,
                'maximum_distance' => $this->maximum_distance ?? null,
                'operating_radius' => $this->operating_radius ?? null,
                'can_handle_fragile' => $this->can_handle_fragile ?? false,
                'can_handle_perishable' => $this->can_handle_perishable ?? false,
                'can_handle_valuables' => $this->can_handle_valuables ?? false,

                // Station specific fields
                'points_count' => $this->points_count ?? null,
                'points_have_phone' => $this->points_have_phone ?? false,
                'points_have_computer' => $this->points_have_computer ?? false,
                'points_have_internet' => $this->points_have_internet ?? false,
                'officers_knowledgeable' => $this->officers_knowledgeable ?? false,
                'points_compliant' => $this->points_compliant ?? false,
                'operating_hours' => $this->operating_hours ?? null,
                'maximum_capacity_per_day' => $this->maximum_capacity_per_day ?? null,
                'storage_facility_type' => $this->storage_facility_type ?? null,
                'security_measures' => $this->security_measures ?? null,
                'insurance_coverage' => $this->insurance_coverage ?? null,
               

                // Step 6: Service Areas
                'selectedTowns' => $this->selectedTowns,

                // Additional fields that might be in blade
                'insurance_certificate' => $this->insurance_certificate ?? null,
                'compliance_certificate' => $this->compliance_certificate ?? null,
                'drivers_certificate' => $this->drivers_certificate ?? null,
                'station_compliance_certificate' => $this->station_compliance_certificate ?? null,
                'other_vehicle_types' => $this->other_vehicle_types ?? null,
                'additional_notes' => $this->additional_notes ?? null,
                'years_in_operation' => $this->years_in_operation ?? null,
                'previous_courier_experience' => $this->previous_courier_experience ?? null,
                'insurance_coverage_amount' => $this->insurance_coverage_amount ?? null,
                'safety_measures' => $this->safety_measures ?? null,
                'tracking_system' => $this->tracking_system ?? null,

                'registration_certificate_path' => $documents['registration_certificate_path'],
                'pin_certificate_path' => $documents['pin_certificate_path'],
            ];


            $ownerPassword = generate_random_string(12);
            $inChargePassword = generate_random_string(12);

            $owner = User::create([
                'first_name'  => $data['owner_first_name'],
                'second_name' => $data['owner_second_name'],
                'last_name' => $data['owner_last_name'],
                'user_name' => explode('@', $data['owner_email'])[0],
                'user_type' => $data['partner_type'],
                'email' => $data['owner_email'],
                'phone_number' => $data['owner_phone_number'],
                'password' => Hash::make($ownerPassword),
                'terms_and_conditions' => $data['terms_and_conditions'],
                'privacy_policy' => $data['privacy_policy'],
                'status' => 'active',
            ]);

            $inCharge = User::create([
                'first_name'  => $data['responsible_officer_first_name'],
                'last_name' => $data['responsible_officer_last_name'],
                'user_name' => explode('@', $data['responsible_officer_email'])[0],
                'user_type' => 'in-charge',
                'email' => $data['responsible_officer_email'],
                'phone_number' => $data['responsible_officer_phone'],
                'password' => Hash::make($inChargePassword),
                'terms_and_conditions' => $data['terms_and_conditions'],
                'privacy_policy' => $data['privacy_policy'],
                'status' => 'active',

            ]);

            $data['owner_id'] = $owner->id;
            $data['incharge_id'] = $inCharge->id;


            $partner = Partner::create($data);
            $partnerFinanceAccount = PartnerFinanceAccount::create([
                'partner_id' => $partner->id,
                'bank_account_number' => $this->bank_account_number ?? null,
                'bank_account_name' => $this->bank_account_name ?? null,
                'bank_name' => $this->bank_name ?? null,
                'bank_branch' => $this->bank_branch ?? null,
                'finance_email' => $this->finance_email ?? null,
            ]);
            if (is_array($this->selectedTowns) && count($this->selectedTowns) > 0) {
                foreach ($this->selectedTowns as $index => $townId) {
                    $serviceArea = new PartnerTown([
                        'partner_id' => $partner->id,
                        'town_id' => $townId,
                        'status' => 'active',
                    ]);
                    $serviceArea->save();
                }
            }

            SendWelcomeEmail::dispatch($owner,  true, $ownerPassword);
            SendWelcomeEmail::dispatch($inCharge, true, $inChargePassword);


            // TODO: Send email notification to Admin, Owner, Incharge

            // TODO: Send confirmation email to partner
            // TODO  Send sms notification to partner



            $this->submissionSuccess = true;
            $this->step = 7;

            DB::commit();

            return redirect()->route('partners.account-status', $partner->id)->with('success', 'Account created successfully. Please verify email to login');
        } catch (\Exception $e) {
            dd('Error:' . $e->getMessage());
            session()->flash('error', 'Registration failed: ' . $e->getMessage());
        }
    }

    public function restartRegistration()
    {
        $this->reset();
        $this->step = 1;
        $this->submissionSuccess = false;
        $this->partnerId = null;
    }

    public function getStepTitle()
    {
        $titles = [
            1 => 'Select Partner Type',
            2 => 'Personal Information',
            3 => 'Company Details',
            4 => 'Finance Details',
            5 => $this->partnerType === 'transport' ? 'Fleet & Operations' : 'PickUp And DropOff Point Details',
            6 => 'Service Areas',
            7 => 'Registration Complete',
        ];

        return $titles[$this->step] ?? 'Step ' . $this->step;
    }

    public function getProgressPercentage()
    {
        return round(($this->step / 6) * 100);
    }

    public function removeTown($townId)
    {
        // Ensure selectedTowns is an array
        if (!is_array($this->selectedTowns)) {
            $this->selectedTowns = [];
        }

        $this->selectedTowns = array_filter($this->selectedTowns, function ($id) use ($townId) {
            return $id != $townId;
        });

        // Re-index array
        $this->selectedTowns = array_values($this->selectedTowns);

        // Update service towns
        if (count($this->selectedTowns) > 0) {
            $this->serviceTowns = Town::whereIn('id', $this->selectedTowns)->get();
        } else {
            $this->serviceTowns = collect();
        }
    }
}
