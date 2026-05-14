<?php

namespace App\Livewire\Partners\Profile;

use App\Models\Partner;
use App\Models\PartnerTown;
use App\Models\Town;
use App\Models\County;
use App\Models\SubCounty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    // Service Areas
    public $service_towns = [];
    public $availableTowns = [];
    public $searchTerm = '';

    // System
    public $verification_status;

    protected function rules()
    {
        $partnerId = $this->partner->id;
        return [
            // Basic Information
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

            // Service Areas
            'service_towns' => 'array',
            'service_towns.*' => 'exists:towns,id',
        ];
    }

    protected $messages = [
        'registration_number.unique' => 'This registration number is already registered.',
        'kra_pin.unique' => 'This KRA PIN is already registered.',
        'registration_certificate.max' => 'Registration certificate must not exceed 5MB.',
        'pin_certificate.max' => 'PIN certificate must not exceed 5MB.',
        'compliance_certificate.max' => 'Compliance certificate must not exceed 5MB.',
        'insurance_certificate.max' => 'Insurance certificate must not exceed 5MB.',
        'drivers_certificate.max' => 'Drivers certificate must not exceed 5MB.',
        'service_towns.*.exists' => 'Selected town is invalid.',
    ];

    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner;
        $this->loadPartnerData();
        $this->loadAvailableTowns();
    }

    public function loadAvailableTowns()
    {
        $query = Town::with('subCounty.county')->orderBy('name');
        
        if (!empty($this->searchTerm)) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }
        
        $this->availableTowns = $query->get();
    }

    public function updatedSearchTerm()
    {
        $this->loadAvailableTowns();
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

        $this->verification_status = $this->partner->verification_status;

        // Load service towns
        $this->service_towns = $this->partner->towns->pluck('town_id')->toArray();
    }

    public function updateProfile()
    {
        $this->validate();

        try {
            $data = [
                'company_name' => $this->company_name,
                'registration_number' => $this->registration_number,
                'kra_pin' => $this->kra_pin,
            ];

            // Handle document uploads
            if ($this->registration_certificate) {
                if ($this->current_registration_certificate && Storage::disk('public')->exists($this->current_registration_certificate)) {
                    Storage::disk('public')->delete($this->current_registration_certificate);
                }
                $path = $this->registration_certificate->store('documents/partners', 'public');
                $data['registration_certificate_path'] = $path;
            }

            if ($this->pin_certificate) {
                if ($this->current_pin_certificate && Storage::disk('public')->exists($this->current_pin_certificate)) {
                    Storage::disk('public')->delete($this->current_pin_certificate);
                }
                $path = $this->pin_certificate->store('documents/partners', 'public');
                $data['pin_certificate_path'] = $path;
            }

            if ($this->compliance_certificate) {
                if ($this->current_compliance_certificate && Storage::disk('public')->exists($this->current_compliance_certificate)) {
                    Storage::disk('public')->delete($this->current_compliance_certificate);
                }
                $path = $this->compliance_certificate->store('documents/partners', 'public');
                $data['compliance_certificate_path'] = $path;
            }

            if ($this->insurance_certificate) {
                if ($this->current_insurance_certificate && Storage::disk('public')->exists($this->current_insurance_certificate)) {
                    Storage::disk('public')->delete($this->current_insurance_certificate);
                }
                $path = $this->insurance_certificate->store('documents/partners', 'public');
                $data['insurance_certificate_path'] = $path;
            }

            if ($this->drivers_certificate) {
                if ($this->current_drivers_certificate && Storage::disk('public')->exists($this->current_drivers_certificate)) {
                    Storage::disk('public')->delete($this->current_drivers_certificate);
                }
                $path = $this->drivers_certificate->store('documents/partners', 'public');
                $data['drivers_certificate_path'] = $path;
            }

            // Update partner
            $this->partner->update($data);

            // Update service towns - Delete existing and add new ones
            PartnerTown::where('partner_id', $this->partner->id)->delete();
            
            foreach ($this->service_towns as $townId) {
                PartnerTown::create([
                    'partner_id' => $this->partner->id,
                    'town_id' => $townId,
                ]);
            }

            // Reload data
            $this->loadPartnerData();
            $this->loadAvailableTowns();

            session()->flash('success', 'Profile updated successfully!');
            
            // Dispatch event for browser notification
            $this->dispatch('profile-updated');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function removeDocument($documentType)
    {
        $field = $documentType . '_certificate_path';
        $currentField = 'current_' . $documentType . '_certificate';
        
        if ($this->partner->$field && Storage::disk('public')->exists($this->partner->$field)) {
            Storage::disk('public')->delete($this->partner->$field);
            $this->partner->update([$field => null]);
            $this->$currentField = null;
            session()->flash('success', ucfirst($documentType) . ' certificate removed successfully!');
        }
    }

    public function selectAllTowns()
    {
        $this->service_towns = Town::pluck('id')->toArray();
        session()->flash('success', 'All towns selected successfully!');
    }

    public function deselectAllTowns()
    {
        $this->service_towns = [];
        session()->flash('success', 'All towns deselected successfully!');
    }

    public function render()
    {
        return view('livewire.partners.profile.edit-profile');
    }
}