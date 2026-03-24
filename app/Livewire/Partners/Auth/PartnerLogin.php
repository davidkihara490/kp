<?php

namespace App\Livewire\Partners\Auth;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class PartnerLogin extends Component
{
    public string $identifier = '';
    public string $password = '';
    public bool $remember = false;
    public bool $showPassword = false;
    public string $errorMessage = '';

    public Partner $partner;

    protected $rules = [
        'identifier' => 'required|min:3',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'identifier.required' => 'Please enter your email, phone number, or username',
        'password.required' => 'Password is required',
    ];

    public function login()
    {
        $this->validate();
        $this->errorMessage = '';

        try {

            $credentials = $this->buildCredentials();

            if (! Auth::guard('partner')->attempt($credentials,)) {
                throw ValidationException::withMessages([
                    'identifier' => 'Invalid credentials.',
                ]);
            }

            $user = Auth::guard('partner')->user();

            if ($user->user_type == 'pha') {
                $this->partner = $user->parcelHandlingAssistant->partner;
            } elseif ($user->user_type == 'driver') {
                $this->partner = $user->driver->partner;
            } else {
                $this->partner = $user->partner;
            }

            if ($this->partner->verification_status != 'verified') {
                request()->session()->regenerate();

                return redirect()->route('partners.account-status', $this->partner->id)->with('Error', 'Your account has not been verified.  ');
                // Auth::guard('partner')->logout();

                // throw ValidationException::withMessages([
                //     'identifier' => 'Your account has been deactivated. Please contact support.',
                // ]);
            }

            if ($user->status != 'active') {
                Auth::guard('partner')->logout();

                throw ValidationException::withMessages([
                    'identifier' => 'Your account has been deactivated. Please contact support.',
                ]);
            }

            request()->session()->regenerate();

            $this->logLoginActivity($user);

            return $this->redirectUser($user);
        } catch (ValidationException $e) {
            $this->errorMessage = $e->validator->errors()->first();
        } catch (\Throwable $e) {
            dd($e->getMessage());
            Log::error('Login error', ['error' => $e->getMessage()]);
            $this->errorMessage = 'Something went wrong. Please try again.';
        }
    }

    /**
     * Determine login field dynamically
     */
    private function buildCredentials(): array
    {
        $identifier = trim($this->identifier);

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $identifier, 'password' => $this->password];
        }

        if ($this->isPhoneNumber($identifier)) {
            return [
                'phone' => $this->normalizePhoneNumber($identifier),
                'password' => $this->password,
            ];
        }

        return ['user_name' => $identifier, 'password' => $this->password];
    }

    private function isPhoneNumber(string $value): bool
    {
        return preg_match('/^(\+254|0|254)?[1-9]\d{8}$/', $value);
    }

    private function normalizePhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            return '254' . substr($phone, 1);
        }

        if (strlen($phone) === 9) {
            return '254' . $phone;
        }

        return $phone;
    }

    private function logLoginActivity($user): void
    {
        activity()
            ->causedBy($user)
            ->performedOn($user)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('logged_in');
    }

    private function redirectUser($user)
    {
        return match ($user->user_type) {
            'driver'    => redirect()->route('partners.driver.dashboard'),
            'pha'       => redirect()->route('partners.pha.dashboard'),
            'transport' => redirect()->route('partners.transport.dashboard'),
            'pickup-dropoff'   => redirect()->route('partners.pd.dashboard'),
            default     => redirect()->route('home'),
        };
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = ! $this->showPassword;
    }

    public function render()
    {
        return view('livewire.partners.auth.partner-login');
    }
}
