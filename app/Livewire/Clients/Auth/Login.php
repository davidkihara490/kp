<?php

namespace  App\Livewire\Clients\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class Login extends Component
{
    public $identifier = 'johnmwangi12@gmail.com';
    public $password = 'password';
    public $remember = false;
    public $showPassword = false;
    public $errorMessage = '';

    protected $rules = [
        'identifier' => 'required|string|max:255',
        'password' => 'required|string|min:8',
    ];

    protected $messages = [
        'identifier.required' => 'Please enter your email, phone number, or username.',
        'password.required' => 'Please enter your password.',
        'password.min' => 'Password must be at least 8 characters.',
    ];

    public function mount()
    {
        // Clear any previous error messages
        $this->errorMessage = '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate();

        $this->errorMessage = '';

        $throttleKey = Str::lower($this->identifier) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'identifier' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
            ]);
        }

        // Determine login field (email, phone, or username)
        $credentials = $this->getCredentials();

        // if (Auth::attempt($credentials, $this->remember)) {
        if (Auth::guard('customer')->attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            request()->session()->regenerate();

            // Redirect based on user role
            $user = Auth::guard('customer')->user();

            return redirect()->intended(route('pudo.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        $this->errorMessage = 'The provided credentials do not match our records.';

        throw ValidationException::withMessages([
            'identifier' => 'The provided credentials do not match our records.',
        ]);
    }

    protected function getCredentials()
    {
        $field = $this->determineLoginField($this->identifier);

        return [
            $field => $this->identifier,
            'password' => $this->password,
        ];
    }

    protected function determineLoginField($input)
    {
        // Check if input is an email
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }

        // Check if input is a phone number (matches common phone patterns)
        if (preg_match('/^[0-9+\-\s()]+$/', $input) && strlen(preg_replace('/[^0-9]/', '', $input)) >= 10) {
            return 'phone';
        }

        // Default to username
        return 'username';
    }

    public function render()
    {
        return view('livewire.clients.auth.login');
    }
}
