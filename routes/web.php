<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

require __DIR__ . '/admin_routes.php';
require __DIR__ . '/partner_routes.php';


Route::get('admin/login', function () {
    return view('pages.admin.auth.login');
})->name('admin.login');

Route::get('/', [HomeController::class, 'index'])->name('home');

// Route::domain('partners.karibuparcels.com')

Route::get('/marketplace', function () {
    return view('pages.marketplace.index');
})->name('marketplace')->middleware('partner.auth');


Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');

    return response()->json([
        'status' => 'success',
        'message' => 'Application cache cleared'
    ]);
});
