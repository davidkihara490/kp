<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Livewire\Partners\Auth\AccountStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('clients')->as('pudo.')->group(function () {
    Route::get('/login', function () {
        return view('pages.clients.auth.login');
    })->name('login');
    Route::get('/registration', function () {
        return view('pages.clients.auth.register');
    })->name('register');

    // Route::get('/recover-password', function () {
    //     return view('pages.partners.auth.recover-password');
    // })->name('recover-password');
    // Route::get('/reset-password/{token}', function ($token) {
    //     return view('pages.partners.auth.reset-password', compact('token'));
    // })->name('reset-password');

    Route::middleware(['pudo.auth'])->group(function () {
        //     Route::get('/account-status/{id}', function ($id) {
        //         return view('pages.partners.auth.account-status', compact('id'));
        //     })->name('account-status');

        //     Route::get('/edit-profile', function () {
        //         return view('pages.partners.profile.edit-profile');
        //     })->name('profile.edit');

        Route::prefix('dashboard')->group(function () {
            Route::get('/', function () {
                return view('pages.clients.parcels.parcels');
            })->name('dashboard');
        });

        Route::post('/customer/logout', function () {
            Auth::guard('customer')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('pudo.login');
        })->name('logout');
    });
});
