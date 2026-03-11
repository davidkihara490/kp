<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\api\v1\Mpesa\MpesaCallbackController;


require __DIR__ . '/admin_routes.php';
require __DIR__ . '/partner_routes.php';

// Admin Subdomain Root Redirect
Route::domain('admin.karibuparcels.com')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
});

// Partner Subdomain Root Redirect
Route::domain('partners.karibuparcels.com')->group(function () {
    Route::get('/', function () {
        return redirect()->route('partners.login');
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::domain('partners.karibuparcels.com')

Route::get('/marketplace', function () {
    return view('pages.marketplace.index');
})->name('marketplace')->middleware('partner.auth');



// routes/api.php or routes/web.php (for testing)
Route::get('/test-mpesa-credentials', function() {
    $consumerKey = config('services.mpesa.consumer_key');
    $consumerSecret = config('services.mpesa.consumer_secret');
    
    return response()->json([
        'consumer_key_exists' => !empty($consumerKey),
        'consumer_key_length' => strlen($consumerKey ?? ''),
        'consumer_secret_exists' => !empty($consumerSecret),
        'consumer_secret_length' => strlen($consumerSecret ?? ''),
        'environment' => config('services.mpesa.environment'),
        'shortcode' => config('services.mpesa.shortcode'),
    ]);
});


Route::get('/server-ip', function() {
    return response()->json([
        'server_ip' => request()->ip(),
        'headers' => [
            'x-forwarded-for' => request()->header('x-forwarded-for'),
            'x-real-ip' => request()->header('x-real-ip'),
        ]
    ]);
});