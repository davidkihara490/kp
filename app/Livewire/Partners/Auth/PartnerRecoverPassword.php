<?php

namespace App\Livewire\Partners\Auth;

use Livewire\Component;
use App\Models\Partner;
use App\Models\PartnerPasswordReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\Partner\Auth\PartnerPasswordResetMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartnerRecoverPassword extends Component
{
    public $email;
    public $errorMessage = '';
    public $successMessage = '';
    public $step = 'request'; // request, verify
    public $resendCooldown = 60;
    public $canResend = true;

    protected function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    protected function messages()
    {
        return [
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.exists' => 'No account found with this email address',
        ];
    }

    public function sendResetLink()
    {
        $this->validate();

        // Check if there's a recent reset request
        $recentReset = DB::table('password_reset_tokens')->where('email', $this->email)
            ->where('created_at', '>', Carbon::now()->subMinutes(5))
            ->first();

        if ($recentReset) {
            $waitTime = round(5 - Carbon::now()->diffInMinutes($recentReset->created_at));
            $this->errorMessage = "A reset link was recently sent. Please wait {$waitTime} minutes before requesting again.";
            return;
        }

        // Generate token
        $token = Str::random(64);

        // Delete old tokens
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        // Create new reset record
        DB::table('password_reset_tokens')->insert([
            'email' => $this->email,
            'token' => $token,
            'created_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addHours(1),
        ]);

        // Send email
        try {
            $partner = User::where('email', $this->email)->first();
            Mail::to($this->email)->send(new PartnerPasswordResetMail($partner, $token));

            $this->step = 'verify';
            $this->successMessage = 'Password reset link has been sent to your email address.';
            $this->startResendCooldown();
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to send reset email. Please try again.';
            Log::error('Password reset email failed: ' . $e->getMessage());
        }
    }
    public function resendResetLink()
    {
        if (!$this->canResend) {
            return;
        }

        $this->sendResetLink();
    }
    public function startResendCooldown()
    {
        $this->canResend = false;
        $this->resendCooldown = 60;
        $this->dispatch('start-cooldown');
    }
    public function updateCooldown()
    {
        if ($this->resendCooldown > 0) {
            $this->resendCooldown--;
        } else {
            $this->canResend = true;
        }
    }
    public function goToLogin()
    {
        return redirect()->route('partners.login');
    }

    public function render()
    {
        return view('livewire.partners.auth.partner-recover-password');
    }
}
