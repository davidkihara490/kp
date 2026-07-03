<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    /**
     * Show the customer login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    /**
     * Show the customer registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $towns = Town::orderBy('name')->get();
        return view('auth.customer-register', compact('towns'));
    }

    /**
     * Handle a customer login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'remember' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Attempt to log the customer in
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            // Update last login timestamp
            $customer = Auth::guard('customer')->user();
            $customer->updateLastLogin();

            // Regenerate session to prevent fixation
            $request->session()->regenerate();

            // Redirect to intended page or dashboard
            $intended = session()->pull('url.intended', route('customer.dashboard'));
            
            return redirect()->intended($intended)
                ->with('success', 'Welcome back ' . $customer->name . '! You have been logged in successfully.');
        }

        // If login fails
        return redirect()->back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->withInput($request->except('password'));
    }

    /**
     * Handle a customer registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'required|string|regex:/^(0|254|\+254)[0-9]{9}$/|unique:customers,phone',
            'town_id' => 'nullable|exists:towns,id',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // Create the customer
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'town_id' => $request->town_id,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        // Log the customer in
        Auth::guard('customer')->login($customer);

        // Regenerate session
        $request->session()->regenerate();

        // Redirect to dashboard
        return redirect()->route('customer.dashboard')
            ->with('success', 'Account created successfully! Welcome to Karibu Parcels, ' . $customer->name . '!');
    }

    /**
     * Handle a customer logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        $parcels = $customer->parcels()->latest()->take(10)->get();
        $totalParcels = $customer->parcels()->count();
        $pendingParcels = $customer->parcels()->where('current_status', 'pending')->count();
        $inTransitParcels = $customer->parcels()->where('current_status', 'in_transit')->count();
        $deliveredParcels = $customer->parcels()->where('current_status', 'delivered')->count();

        return view('customer.dashboard', compact(
            'customer',
            'parcels',
            'totalParcels',
            'pendingParcels',
            'inTransitParcels',
            'deliveredParcels'
        ));
    }

    /**
     * Show the customer profile edit form.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        $customer = Auth::guard('customer')->user();
        $towns = Town::orderBy('name')->get();
        return view('customer.profile', compact('customer', 'towns'));
    }

    /**
     * Update the customer profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|regex:/^(0|254|\+254)[0-9]{9}$/|unique:customers,phone,' . $customer->id,
            'town_id' => 'nullable|exists:towns,id',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update basic info
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->town_id = $request->town_id;
        $customer->address = $request->address;

        // Update password if provided
        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $customer->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }
            $customer->password = Hash::make($request->new_password);
        }

        $customer->save();

        return redirect()->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Verify customer email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request)
    {
        $customer = Customer::where('email', $request->email)->firstOrFail();
        
        if (!$customer->email_verified_at) {
            $customer->email_verified_at = now();
            $customer->save();
            return redirect()->route('customer.dashboard')
                ->with('success', 'Email verified successfully!');
        }

        return redirect()->route('customer.dashboard')
            ->with('info', 'Email already verified.');
    }

    /**
     * Resend email verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendVerification(Request $request)
    {
        // Logic to send verification email
        // You can implement this using Laravel's notification system

        return redirect()->back()
            ->with('success', 'Verification email sent successfully!');
    }

    /**
     * Get the login response in JSON format for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginApi(Request $request)
    {
        $type = $request->query('type'); // Default to 'customer' if not provided
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::guard('customer')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $customer = Auth::guard('customer')->user();
            $customer->updateLastLogin();
            
            // Create token for API
            $token = $customer->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'customer' => $customer,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Handle registration via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerApi(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'phone' => 'required|string|unique:customers,phone',
            'town_id' => 'nullable|exists:towns,id',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'town_id' => $request->town_id,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        Auth::guard('customer')->login($customer);

        $token = $customer->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'customer' => $customer,
            'token' => $token,
        ], 201);
    }

    /**
     * Check if customer is authenticated.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuth()
    {
        if (Auth::guard('customer')->check()) {
            return response()->json([
                'authenticated' => true,
                'customer' => Auth::guard('customer')->user()
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
}