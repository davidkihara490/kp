<?php

namespace App\Livewire\Partners\Auth;

use Livewire\Component;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AccountStatus extends Component
{
    public $partner;
    
    // Verification status properties
    public $ownerEmailVerified = false;
    public $ownerPhoneVerified = false;
    public $officerEmailVerified = false;
    public $officerPhoneVerified = false;
    public $adminVerified = false;
    
    // Contact information
    public $ownerEmail = '';
    public $ownerPhone = '';
    public $responsibleOfficerEmail = '';
    public $responsibleOfficerPhone = '';
    
    // Computed properties
    public $verificationPercentage = 0;
    public $completedVerifications = 0;
    public $isFullyVerified = false;
    public $hasPendingVerifications = false;
    public $registrationDate = '';
    
    // For adding missing email
    public $newOfficerEmail = '';
    public $showAddEmailForm = false;

    protected $listeners = [
        'refreshVerificationStatus' => 'loadVerificationStatus',
        'verificationUpdated' => '$refresh'
    ];

    public function mount()
    {
        // Get the authenticated partner
        $this->partner = Auth::guard('partner')->user();
        
        if ($this->partner) {
            $this->loadVerificationStatus();
            $this->formatRegistrationDate();
        }
    }

    public function loadVerificationStatus()
    {
        // Load verification status from partner model
        $this->ownerEmailVerified = (bool) $this->partner->owner_email_verified_at;
        $this->ownerPhoneVerified = (bool) $this->partner->owner_phone_verified_at;
        $this->officerEmailVerified = (bool) $this->partner->responsible_officer_email_verified_at;
        $this->officerPhoneVerified = (bool) $this->partner->responsible_officer_phone_verified_at;
        $this->adminVerified = (bool) $this->partner->admin_verified_at;
        
        // Load contact information
        $this->ownerEmail = $this->partner->owner_email;
        $this->ownerPhone = $this->partner->owner_phone_number;
        $this->responsibleOfficerEmail = $this->partner->responsible_officer_email;
        $this->responsibleOfficerPhone = $this->partner->responsible_officer_phone;
        
        // Calculate verification progress
        $this->calculateVerificationProgress();
    }

    private function calculateVerificationProgress()
    {
        $verifications = [
            $this->ownerEmailVerified,
            $this->ownerPhoneVerified,
            $this->adminVerified,
        ];
        
        // Add officer email verification if email exists
        if (!empty($this->responsibleOfficerEmail)) {
            $verifications[] = $this->officerEmailVerified;
        }
        
        // Officer phone is always required
        $verifications[] = $this->officerPhoneVerified;
        
        $this->completedVerifications = count(array_filter($verifications));
        $totalVerifications = count($verifications);
        
        $this->verificationPercentage = $totalVerifications > 0 
            ? round(($this->completedVerifications / $totalVerifications) * 100) 
            : 0;
        
        $this->isFullyVerified = $this->completedVerifications === $totalVerifications;
        $this->hasPendingVerifications = $this->completedVerifications < $totalVerifications;
    }

    private function formatRegistrationDate()
    {
        $date = $this->partner->created_at ?? Carbon::now();
        $this->registrationDate = $date->diffForHumans();
    }

    public function resendOwnerEmailVerification()
    {
        try {
            // Dispatch job to send verification email
            \App\Jobs\SendPartnerVerificationEmail::dispatch(
                $this->partner,
                'owner_email',
                'owner_email_verification'
            );
            
            $this->dispatch('verificationSent', 
                message: 'Verification email sent to ' . $this->ownerEmail
            );
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to send verification email. Please try again.'
            );
        }
    }

    public function sendOwnerPhoneVerification()
    {
        try {
            // Generate verification code
            $verificationCode = rand(100000, 999999);
            
            // Store verification code (you might want to use cache or a dedicated table)
            cache()->put(
                'partner_phone_verification_' . $this->partner->id,
                $verificationCode,
                now()->addMinutes(15)
            );
            
            // Dispatch SMS job
            \App\Jobs\SendPartnerVerificationSMS::dispatch(
                $this->ownerPhone,
                $verificationCode
            );
            
            // Show verification code input modal
            $this->dispatch('showPhoneVerificationModal', 
                type: 'owner',
                phone: $this->ownerPhone
            );
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to send verification SMS. Please try again.'
            );
        }
    }

    public function resendOfficerEmailVerification()
    {
        try {
            if (empty($this->responsibleOfficerEmail)) {
                $this->dispatch('verificationError', 
                    message: 'Officer email not found. Please add an email first.'
                );
                return;
            }
            
            \App\Jobs\SendPartnerVerificationEmail::dispatch(
                $this->partner,
                'responsible_officer_email',
                'officer_email_verification'
            );
            
            $this->dispatch('verificationSent', 
                message: 'Verification email sent to officer'
            );
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to send verification email. Please try again.'
            );
        }
    }

    public function sendOfficerPhoneVerification()
    {
        try {
            $verificationCode = rand(100000, 999999);
            
            cache()->put(
                'partner_officer_phone_verification_' . $this->partner->id,
                $verificationCode,
                now()->addMinutes(15)
            );
            
            \App\Jobs\SendPartnerVerificationSMS::dispatch(
                $this->responsibleOfficerPhone,
                $verificationCode
            );
            
            $this->dispatch('showPhoneVerificationModal', 
                type: 'officer',
                phone: $this->responsibleOfficerPhone
            );
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to send verification SMS. Please try again.'
            );
        }
    }

    public function addOfficerEmail()
    {
        $this->showAddEmailForm = true;
    }

    public function saveOfficerEmail()
    {
        $this->validate([
            'newOfficerEmail' => 'required|email|unique:partners,responsible_officer_email,' . $this->partner->id,
        ]);
        
        try {
            $this->partner->update([
                'responsible_officer_email' => $this->newOfficerEmail,
            ]);
            
            $this->responsibleOfficerEmail = $this->newOfficerEmail;
            $this->newOfficerEmail = '';
            $this->showAddEmailForm = false;
            
            // Send verification email
            $this->resendOfficerEmailVerification();
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to save email. Please try again.'
            );
        }
    }

    public function requestExpeditedReview()
    {
        try {
            // Dispatch notification to admin
            \App\Notifications\PartnerExpeditedReviewRequest::dispatch(
                $this->partner
            );
            
            // Log the request
            activity()
                ->causedBy($this->partner)
                ->performedOn($this->partner)
                ->log('requested_expedited_review');
            
            $this->dispatch('verificationSent', 
                message: 'Expedited review request sent to admin team'
            );
            
        } catch (\Exception $e) {
            $this->dispatch('verificationError', 
                message: 'Failed to send request. Please try again.'
            );
        }
    }

    public function verifyPhone($type, $code)
    {
        $cacheKey = 'partner_' . ($type === 'owner' ? 'phone' : 'officer_phone') . '_verification_' . $this->partner->id;
        $storedCode = cache()->get($cacheKey);
        
        if ($storedCode && $storedCode == $code) {
            $field = $type === 'owner' ? 'owner_phone_verified_at' : 'responsible_officer_phone_verified_at';
            
            $this->partner->update([
                $field => now(),
            ]);
            
            cache()->forget($cacheKey);
            
            $this->loadVerificationStatus();
            $this->dispatch('verificationUpdated');
            $this->dispatch('verificationSent', 
                message: ucfirst($type) . ' phone number verified successfully!'
            );
            
            return true;
        }
        
        $this->dispatch('verificationError', 
            message: 'Invalid verification code. Please try again.'
        );
        return false;
    }

    public function getProgressColor()
    {
        if ($this->verificationPercentage >= 80) {
            return 'success';
        } elseif ($this->verificationPercentage >= 50) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    public function getStatusBadgeClass()
    {
        if ($this->isFullyVerified) {
            return 'bg-success text-white';
        } elseif ($this->verificationPercentage >= 50) {
            return 'bg-warning text-dark';
        } else {
            return 'bg-danger text-white';
        }
    }

    public function render()
    {
        return view('livewire.partners.auth.account-status');
    }
}