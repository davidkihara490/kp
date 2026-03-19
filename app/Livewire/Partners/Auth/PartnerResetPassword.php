<?php

namespace App\Livewire\Partners\Auth;

use Livewire\Component;
use App\Models\Partner;
use App\Models\PartnerPasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PartnerResetPassword extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;
    public $errorMessage = '';
    public $successMessage = '';
    public $showPassword = false;
    public $showConfirmPassword = false;
    public $validToken = false;

    protected function rules()
    {
        return [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'password.required' => 'Please enter a new password',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }

    public function mount($token)
    {
        $this->token = $token;
        $this->validateToken();
    }

    public function validateToken()
    {
        $reset =  DB::table('password_reset_tokens')->where('token', $this->token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$reset) {
            $this->validToken = false;
            $this->errorMessage = 'Invalid or expired password reset link. Please request a new one.';
            return;
        }

        $this->validToken = true;
        $this->email = $reset->email;
    }

    public function resetPassword()
    {
        if (!$this->validToken) {
            $this->errorMessage = 'Invalid reset link. Please request a new one.';
            return;
        }

        $this->validate();

        // Verify token again
        $reset =  DB::table('password_reset_tokens')->where('email', $this->email)
            ->where('token', $this->token)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$reset) {
            $this->validToken = false;
            $this->errorMessage = 'Invalid or expired reset link. Please request a new one.';
            return;
        }

        // Update password
        $partner = User::where('email', $this->email)->first();
        $partner->password = Hash::make($this->password);
        $partner->save();

        // Delete used reset token
        DB::table('password_reset_tokens')
            ->where('email', $this->email)
            ->delete();

        $this->successMessage = 'Your password has been successfully reset.';

        // Redirect to login after 3 seconds
        $this->dispatch('redirect-to-login');
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleConfirmPasswordVisibility()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function goToLogin()
    {
        return redirect()->route('partners.login');
    }

    public function goToForgotPassword()
    {
        return redirect()->route('partners.forgot-password');
    }

    public function render()
    {
        return view('livewire.partners.auth.partner-reset-password');
    }
}
