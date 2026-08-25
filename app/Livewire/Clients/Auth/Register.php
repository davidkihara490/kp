<?php

namespace App\Livewire\Clients\Auth;

use Livewire\Component;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Register extends Component
{
    public $accountType = 'individual';
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $passwordConfirmation = '';
    public $companyName = '';
    public $companyRegistrationNumber = '';
    public $address = '';
    public $city = '';
    public $country = '';
    public $postalCode = '';
    public $showPassword = false;
    public $showConfirmPassword = false;
    public $agreeTerms = false;
    public $errorMessage = '';

    protected function rules(): array
    {
        return [
            'accountType' => 'required|in:individual,corporate',
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
            'passwordConfirmation' => 'required|same:password',
            'companyName' => 'required_if:accountType,corporate|nullable|string|max:100',
            'companyRegistrationNumber' => 'required_if:accountType,corporate|nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:50',
            'postalCode' => 'nullable|string|max:20',
            'agreeTerms' => 'accepted',
        ];
    }

    protected $messages = [
        'firstName.required' => 'Please enter your first name.',
        'lastName.required' => 'Please enter your last name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered. Please login or use a different email.',
        'password.required' => 'Please enter a password.',
        'password.confirmed' => 'Passwords do not match.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.mixedCase' => 'Password must contain both uppercase and lowercase letters.',
        'password.numbers' => 'Password must contain at least one number.',
        'password.symbols' => 'Password must contain at least one special character.',
        'passwordConfirmation.required' => 'Please confirm your password.',
        'passwordConfirmation.same' => 'Passwords do not match.',
        'companyName.required_if' => 'Please enter your company name for corporate account.',
        'companyRegistrationNumber.required_if' => 'Please enter your company registration number.',
        'agreeTerms.accepted' => 'You must agree to the terms and conditions.',
    ];

    public function mount()
    {
        $this->errorMessage = '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedAccountType($value)
    {
        $this->resetErrorBag('companyName');
        $this->resetErrorBag('companyRegistrationNumber');

        if ($value === 'individual') {
            $this->companyName = '';
            $this->companyRegistrationNumber = '';
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function toggleConfirmPasswordVisibility()
    {
        $this->showConfirmPassword = !$this->showConfirmPassword;
    }

    public function register()
    {
        $this->validate();

        $this->errorMessage = '';

        $throttleKey = 'register:' . strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => 'Too many registration attempts. Please try again in ' . $seconds . ' seconds.',
            ]);
        }

        try {
            $customer = Customer::create([
                'name' => $this->firstName . $this->lastName,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'user_type' => $this->accountType,
                'company_name' => $this->accountType === 'corporate' ? $this->companyName : null,
                'company_registration_number' => $this->accountType === 'corporate' ? $this->companyRegistrationNumber : null,
                'address' => $this->address,
                'city' => $this->city,
                'country' => $this->country,
                'postal_code' => $this->postalCode,
            ]);

            RateLimiter::clear($throttleKey);

            // event(new Registered($customer));

            // Log the customer in
            Auth::guard('customer')->login($customer, true);

            session()->regenerate();

            return redirect()->intended(route('pudo.dashboard'));
        } catch (\Exception $e) {
            $this->errorMessage = 'Registration failed. Please try again.'. $e->getMessage();
            Log::error('Registration error: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.clients.auth.register');
    }
}
