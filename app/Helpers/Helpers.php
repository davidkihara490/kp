<?php

use App\Models\Driver;
use App\Models\ParcelHandlingAssistant;
use App\Models\Partner;
use Illuminate\Support\Facades\Auth;

if (!function_exists('current_partner')) {
    function current_partner()
    {
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return Partner::whereHas('owners', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->whereNull('to')
                ->where('status', 'active');
        })->first();
    }
}


if (!function_exists('generate_random_string')) {
    /**
     * Generate a random string with letters, numbers, and symbols.
     *
     * @param int $length
     * @return string
     */
    function generate_random_string($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('current_user_type')) {
    /**
     * Returns the type of the currently authenticated user
     *
     * @return string|null
     */
    function current_user_type(): ?string
    {
        $map = [
            'driver' => Driver::class,
            'transport' => Partner::class,
            'pha' => ParcelHandlingAssistant::class,
            'pickup-dropoff' => Partner::class,
        ];

        $type = Auth::guard('partner')->user()?->user_type;
        return $map[$type] ?? null;
    }

    //$modelClass = current_user_model();
    // $user = $modelClass ? $modelClass::find(auth()->id()) : null;


}


if (! function_exists('formatKenyaNumber')) {
    /**
     * Convert a Kenyan phone number to international format (254xxxxxxx)
     *
     * @param string $number
     * @return string|null
     */
    function formatKenyaNumber(string $number): ?string
    {
        // Remove spaces, dashes, and any non-digit characters
        $number = preg_replace('/\D/', '', $number);

        if (strlen($number) == 10 && str_starts_with($number, '0')) {
            // Convert 07xxxxxxxx to 2547xxxxxxxx
            return '254' . substr($number, 1);
        } elseif (strlen($number) == 12 && str_starts_with($number, '254')) {
            // Already in international format
            return $number;
        } else {
            // Invalid number
            return null;
        }
    }
}