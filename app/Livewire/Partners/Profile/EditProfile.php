<?php

namespace App\Livewire\Partners\Profile;

use App\Models\PartnerTown;
use App\Models\Town;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Throwable;

class EditProfile extends Component
{
    use WithFileUploads;

    public $partner;

    // Basic Information
    public $partner_type;
    public $company_name;
    public $registration_number;
    public $kra_pin;

    // New document uploads
    public $registration_certificate;
    public $pin_certificate;
    public $compliance_certificate;
    public $insurance_certificate;
    public $drivers_certificate;

    public $phone_number;
    public $email;

    // Existing documents
    public $current_registration_certificate;
    public $current_pin_certificate;
    public $current_compliance_certificate;
    public $current_insurance_certificate;
    public $current_drivers_certificate;

    // Service areas
    public array $service_towns = [];
    public $availableTowns = [];
    public string $searchTerm = '';

    // System
    public $verification_status;

    protected function rules(): array
    {
        $ownerId = $this->partner->owner_id;
        return [
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('partners', 'registration_number')
                    ->ignore($this->partner->id),
            ],

            'kra_pin' => [
                'required',
                'string',
                'max:20',
                Rule::unique('partners', 'kra_pin')
                    ->ignore($this->partner->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($ownerId),
            ],

            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone_number')->ignore($ownerId),
            ],

            'registration_certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'pin_certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'compliance_certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'insurance_certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'drivers_certificate' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],

            'service_towns' => [
                'nullable',
                'array',
            ],

            'service_towns.*' => [
                'integer',
                'distinct',
                'exists:towns,id',
            ],
        ];
    }

    protected array $messages = [
        'company_name.required' => 'Please enter the company name.',

        'registration_number.required' => 'Please enter the registration number.',
        'registration_number.unique' => 'This registration number is already registered.',

        'kra_pin.required' => 'Please enter the KRA PIN.',
        'kra_pin.unique' => 'This KRA PIN is already registered.',

        'email.required' => 'Please enter the Email.',
        'email.unique' => 'This email is already registered.',

        'phone_number.required' => 'Please enter the phone number.',
        'phone_number.unique' => 'This phone number is already registered.',

        'registration_certificate.mimes' => 'The registration certificate must be a PDF, JPG, JPEG, or PNG file.',
        'registration_certificate.max' => 'The registration certificate must not exceed 5MB.',

        'pin_certificate.mimes' => 'The PIN certificate must be a PDF, JPG, JPEG, or PNG file.',
        'pin_certificate.max' => 'The PIN certificate must not exceed 5MB.',

        'compliance_certificate.mimes' => 'The compliance certificate must be a PDF, JPG, JPEG, or PNG file.',
        'compliance_certificate.max' => 'The compliance certificate must not exceed 5MB.',

        'insurance_certificate.mimes' => 'The insurance certificate must be a PDF, JPG, JPEG, or PNG file.',
        'insurance_certificate.max' => 'The insurance certificate must not exceed 5MB.',

        'drivers_certificate.mimes' => 'The drivers certificate must be a PDF, JPG, JPEG, or PNG file.',
        'drivers_certificate.max' => 'The drivers certificate must not exceed 5MB.',

        'service_towns.array' => 'The selected service areas are invalid.',
        'service_towns.*.exists' => 'One of the selected towns is invalid.',
        'service_towns.*.distinct' => 'The same town cannot be selected more than once.',
    ];

    public function mount(): void
    {
        $authenticatedPartner = Auth::guard('partner')->user()?->partner;

        abort_unless($authenticatedPartner, 403, 'Partner account not found.');

        $this->partner = $authenticatedPartner;

        $this->loadPartnerData();
        $this->loadAvailableTowns();
    }

    public function loadAvailableTowns(): void
    {
        $this->availableTowns = Town::query()
            ->with('subCounty.county')
            ->when(
                filled($this->searchTerm),
                fn($query) => $query->where(
                    'name',
                    'like',
                    '%' . trim($this->searchTerm) . '%'
                )
            )
            ->orderBy('name')
            ->get();
    }

    public function updatedSearchTerm(): void
    {
        $this->loadAvailableTowns();
    }

    /**
     * Clear a field's validation error after the user changes it.
     */
    public function updated(string $propertyName): void
    {
        $this->resetValidation($propertyName);
    }

    public function loadPartnerData(): void
    {
        $this->partner->refresh();
        $this->partner->load('towns');

        $this->partner_type = $this->partner->partner_type;
        $this->company_name = $this->partner->company_name;
        $this->registration_number = $this->partner->registration_number;
        $this->kra_pin = $this->partner->kra_pin;

        $this->email = $this->partner->owner?->email;
        $this->phone_number = $this->partner->owner?->phone_number;

        $this->current_registration_certificate =
            $this->partner->registration_certificate_path;

        $this->current_pin_certificate =
            $this->partner->pin_certificate_path;

        $this->current_compliance_certificate =
            $this->partner->compliance_certificate_path;

        $this->current_insurance_certificate =
            $this->partner->insurance_certificate_path;

        $this->current_drivers_certificate =
            $this->partner->drivers_certificate_path;

        $this->verification_status = $this->partner->verification_status;

        /*
     * Get saved town IDs, then keep only IDs that still exist
     * in the towns table.
     */
        $savedTownIds = PartnerTown::query()
            ->where('partner_id', $this->partner->id)
            ->pluck('town_id');

        $this->service_towns = Town::query()
            ->whereIn('id', $savedTownIds)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();
    }
    public function updateProfile(): void
    {
        $this->resetValidation();

        /*
     * Remove town IDs that no longer exist before validation.
     */
        $this->service_towns = Town::query()
            ->whereIn(
                'id',
                collect($this->service_towns)
                    ->filter()
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values()
                    ->toArray()
            )
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->toArray();

        try {
            $validated = $this->validate();

            $newlyStoredFiles = [];
            $oldFilesToDelete = [];

            $data = [
                'company_name' => trim($validated['company_name']),
                'registration_number' => trim($validated['registration_number']),
                'kra_pin' => strtoupper(trim($validated['kra_pin'])),
            ];

            $documents = [
                'registration_certificate' => 'registration_certificate_path',
                'pin_certificate' => 'pin_certificate_path',
                'compliance_certificate' => 'compliance_certificate_path',
                'insurance_certificate' => 'insurance_certificate_path',
                'drivers_certificate' => 'drivers_certificate_path',
            ];

            /*
             * Store the new documents first.
             *
             * Existing documents are only deleted after the database update
             * succeeds, preventing the current file from being lost when the
             * database update fails.
             */
            foreach ($documents as $uploadProperty => $databaseColumn) {
                if (!$this->{$uploadProperty}) {
                    continue;
                }

                $newPath = $this->{$uploadProperty}->store(
                    "documents/partners/{$this->partner->id}",
                    'public'
                );

                $newlyStoredFiles[] = $newPath;

                if ($this->partner->{$databaseColumn}) {
                    $oldFilesToDelete[] = $this->partner->{$databaseColumn};
                }

                $data[$databaseColumn] = $newPath;
            }

            try {
                DB::transaction(function () use ($data, $validated) {
                    $this->partner->update($data);
                    $this->partner->owner->update([
                        'email' => strtolower(trim($validated['email'])),
                        'phone_number' => trim($validated['phone_number']),
                    ]);

                    /*
                     * sync-style update using the PartnerTown model.
                     */
                    PartnerTown::query()
                        ->where('partner_id', $this->partner->id)
                        ->delete();

                    $townIds = collect($validated['service_towns'] ?? [])
                        ->map(fn($townId) => (int) $townId)
                        ->unique()
                        ->values();

                    if ($townIds->isNotEmpty()) {
                        PartnerTown::insert(
                            $townIds
                                ->map(fn($townId) => [
                                    'partner_id' => $this->partner->id,
                                    'town_id' => $townId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ])
                                ->toArray()
                        );
                    }
                });
            } catch (Throwable $exception) {
                /*
                 * Remove newly uploaded files when the database transaction
                 * fails.
                 */
                foreach ($newlyStoredFiles as $file) {
                    Storage::disk('public')->delete($file);
                }

                throw $exception;
            }

            /*
             * The database update succeeded, so the previous files can now
             * safely be deleted.
             */
            foreach ($oldFilesToDelete as $oldFile) {
                if (Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }

            $this->reset([
                'registration_certificate',
                'pin_certificate',
                'compliance_certificate',
                'insurance_certificate',
                'drivers_certificate',
            ]);

            $this->loadPartnerData();
            $this->loadAvailableTowns();

            session()->flash(
                'success',
                'Your partner profile was updated successfully.'
            );

            $this->dispatch(
                'profile-updated',
                message: 'Your partner profile was updated successfully.'
            );
        } catch (ValidationException $exception) {
            session()->flash(
                'validation_error',
                'Please correct the highlighted fields and submit the form again.'
            );

            $this->dispatch(
                'profile-validation-failed',
                message: 'Please correct the highlighted fields.'
            );

            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Partner profile update failed.', [
                'partner_id' => $this->partner?->id,
                'message' => $exception->getMessage(),
                'exception' => $exception,
            ]);

            session()->flash(
                'error',
                'The profile could not be updated. Please try again.'
            );

            $this->dispatch(
                'profile-update-failed',
                message: 'The profile could not be updated. Please try again.'
            );
        }
    }

    public function removeDocument(string $documentType): void
    {
        $documents = [
            'registration' => [
                'database_column' => 'registration_certificate_path',
                'current_property' => 'current_registration_certificate',
                'label' => 'Registration certificate',
            ],
            'pin' => [
                'database_column' => 'pin_certificate_path',
                'current_property' => 'current_pin_certificate',
                'label' => 'PIN certificate',
            ],
            'compliance' => [
                'database_column' => 'compliance_certificate_path',
                'current_property' => 'current_compliance_certificate',
                'label' => 'Compliance certificate',
            ],
            'insurance' => [
                'database_column' => 'insurance_certificate_path',
                'current_property' => 'current_insurance_certificate',
                'label' => 'Insurance certificate',
            ],
            'drivers' => [
                'database_column' => 'drivers_certificate_path',
                'current_property' => 'current_drivers_certificate',
                'label' => 'Drivers certificate',
            ],
        ];

        if (!array_key_exists($documentType, $documents)) {
            session()->flash('error', 'Invalid document type selected.');

            $this->dispatch(
                'profile-update-failed',
                message: 'Invalid document type selected.'
            );

            return;
        }

        $document = $documents[$documentType];
        $databaseColumn = $document['database_column'];
        $currentProperty = $document['current_property'];
        $documentLabel = $document['label'];

        try {
            $path = $this->partner->{$databaseColumn};

            if (!$path) {
                session()->flash(
                    'error',
                    "{$documentLabel} was not found."
                );

                return;
            }

            DB::transaction(function () use ($databaseColumn) {
                $this->partner->update([
                    $databaseColumn => null,
                ]);
            });

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $this->{$currentProperty} = null;

            session()->flash(
                'success',
                "{$documentLabel} removed successfully."
            );

            $this->dispatch(
                'profile-updated',
                message: "{$documentLabel} removed successfully."
            );
        } catch (Throwable $exception) {
            Log::error('Partner document removal failed.', [
                'partner_id' => $this->partner?->id,
                'document_type' => $documentType,
                'message' => $exception->getMessage(),
            ]);

            session()->flash(
                'error',
                "{$documentLabel} could not be removed. Please try again."
            );

            $this->dispatch(
                'profile-update-failed',
                message: "{$documentLabel} could not be removed."
            );
        }
    }

    public function selectAllTowns(): void
    {
        /*
         * Select only the currently displayed results when searching.
         * Without a search, this selects every town.
         */
        $this->service_towns = collect($this->service_towns)
            ->merge(collect($this->availableTowns)->pluck('id'))
            ->map(fn($townId) => (int) $townId)
            ->unique()
            ->values()
            ->toArray();

        $this->resetValidation('service_towns');

        session()->flash(
            'success',
            filled($this->searchTerm)
                ? 'All displayed towns were selected.'
                : 'All towns were selected.'
        );
    }

    public function deselectAllTowns(): void
    {
        if (filled($this->searchTerm)) {
            $displayedTownIds = collect($this->availableTowns)
                ->pluck('id')
                ->map(fn($townId) => (int) $townId)
                ->toArray();

            $this->service_towns = collect($this->service_towns)
                ->reject(fn($townId) => in_array(
                    (int) $townId,
                    $displayedTownIds,
                    true
                ))
                ->values()
                ->toArray();

            session()->flash(
                'success',
                'All displayed towns were deselected.'
            );

            return;
        }

        $this->service_towns = [];

        session()->flash(
            'success',
            'All towns were deselected.'
        );
    }

    public function resetProfileForm(): void
    {
        $this->resetValidation();

        $this->reset([
            'registration_certificate',
            'pin_certificate',
            'compliance_certificate',
            'insurance_certificate',
            'drivers_certificate',
            'searchTerm',
        ]);

        $this->loadPartnerData();
        $this->loadAvailableTowns();

        session()->flash(
            'success',
            'The form was reset to the saved profile information.'
        );

        $this->dispatch(
            'profile-updated',
            message: 'The form was reset.'
        );
    }

    public function render()
    {
        return view('livewire.partners.profile.edit-profile');
    }
}
