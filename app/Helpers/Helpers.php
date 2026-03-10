<?php

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
    function generate_random_string($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
