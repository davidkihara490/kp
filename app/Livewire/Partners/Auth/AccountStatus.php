<?php

namespace App\Livewire\Partners\Auth;

use App\Mail\VerificationEmail;
use Livewire\Component;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    // Computed properties
    public $verificationPercentage = 0;
    public $completedVerifications = 0;
    public $isFullyVerified = false;
    public $hasPendingVerifications = false;
    public $registrationDate = '';

    // Phone verification modal
    public $showPhoneModal = false;
    public $verificationType = '';
    public $verificationCode = '';
    public $generatedCode = '';

    protected $listeners = [
        'refreshVerificationStatus' => 'loadVerificationStatus',
        'verificationUpdated' => '$refresh'
    ];

    public function mount()
    {
        $this->partner = Auth::guard('partner')->user();

        if ($this->partner) {
            $this->loadVerificationStatus();
            $this->formatRegistrationDate();
        }
    }

    public function loadVerificationStatus()
    {
        $this->ownerEmailVerified = (bool) $this->partner->email_verified_at;
        $this->ownerPhoneVerified = (bool) $this->partner->phone_verified_at;

        // Get partner verification status
        $partner = match ($this->partner->user_type) {
            'pha' => $this->partner->parcelHandlingAssistant->partner ?? null,
            'driver' => $this->partner->driver->partner ?? null,
            default => $this->partner->partner ?? $this->partner,
        };

        $this->adminVerified = $partner && $partner->verification_status === 'verified';

        // Load contact information
        $this->ownerEmail = $this->partner->email;
        $this->ownerPhone = $this->partner->phone_number;

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
            // Generate verification token
            $token = sha1($this->partner->email . time());

            $this->partner->update([
                'email_verification_token' => $token,
                'email_verification_expires_at' => now()->addHours(24)
            ]);

            // Send verification email directly (without job)
            $verificationUrl = route('user.verify.email', ['id' => $this->partner->id, 'hash' => $token]);

            Mail::to($this->partner->email)->send(new VerificationEmail($this->partner, $verificationUrl));

            session()->flash('success', 'Verification email sent to ' . $this->ownerEmail);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send verification email: ' . $e->getMessage());
        }
    }

    public function sendOwnerPhoneVerification()
    {
        try {
            // Generate verification code
            $this->generatedCode = rand(100000, 999999);

            // Store verification code in cache (expires in 15 minutes)
            Cache::put(
                'partner_phone_verification_' . $this->partner->id,
                $this->generatedCode,
                now()->addMinutes(15)
            );

            // Send SMS using Africa's Talking or your SMS provider
            $this->sendSMS($this->ownerPhone, $this->generatedCode);

            // Show verification modal
            $this->verificationType = 'owner';
            $this->verificationCode = '';
            $this->showPhoneModal = true;
        } catch (\Exception $e) {
            $this->dispatch(
                'verificationError',
                message: 'Failed to send verification code: ' . $e->getMessage()
            );
        }
    }

    private function sendSMS($phone, $code)
    {
        // Using Africa's Talking or other SMS service
        // Example with Africa's Talking:
        /*
        $username = config('services.africastalking.username');
        $apiKey = config('services.africastalking.api_key');
        
        $client = new \AfricasTalking\SDK\AfricasTalking($username, $apiKey);
        $sms = $client->sms();
        
        $sms->send([
            'to' => $phone,
            'message' => "Your Karibu Parcels verification code is: $code"
        ]);
        */

        // For testing, log the code
        Log::info("SMS verification code for {$phone}: {$code}");

        // You can also use a simple HTTP request to your SMS provider
    }

    public function verifyPhone()
    {
        $this->validate([
            'verificationCode' => 'required|digits:6'
        ]);

        $cacheKey = 'partner_phone_verification_' . $this->partner->id;
        $storedCode = Cache::get($cacheKey);

        if ($storedCode && $storedCode == (int)$this->verificationCode) {
            // Update verification status
            $this->partner->phone_verified_at = now();
            $this->partner->save();

            // Clear the cache
            Cache::forget($cacheKey);

            // Close modal and refresh status
            $this->showPhoneModal = false;
            $this->verificationCode = '';
            $this->loadVerificationStatus();

            session()->flash('success', 'Phone number verified successfully!');
        } else {

            session()->flash('error', 'Invalid verification code. Please try again.');
        }
    }

    public function resendPhoneCode()
    {
        $this->sendOwnerPhoneVerification();
        $this->dispatch(
            'verificationSent',
            message: 'New verification code sent to ' . $this->ownerPhone
        );
    }

    public function closePhoneModal()
    {
        $this->showPhoneModal = false;
        $this->verificationCode = '';
        $this->verificationType = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.partners.auth.account-status');
    }
}
