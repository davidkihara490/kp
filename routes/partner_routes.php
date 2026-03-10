<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verifyEmail'])
    ->name('user.verify.email');

Route::domain('partners.karibuparcels.com')->as('partners.')->group(function () {

        Route::get('/login', function () {
            return view('pages.partners.auth.login');
        })->name('login');
        Route::get('/onboarding/registration', function () {
            return view('pages.partners.auth.onboarding');
        })->name('onboarding');
        Route::get('/account-status/{id}', function ($id) {
            return view('pages.partners.auth.account-status', compact('id'));
        })->name('account-status');

    Route::middleware(['partner.auth'])->group(function(){
        Route::get('/edit-profile', function () {
            return view('pages.partners.profile.edit-profile');
        })->name('profile.edit');
        Route::prefix('dashboard')->group(function () {
            Route::get('/pick-up-and-drop-off/dashboard', function () {
                return view('pages.partners.dashboard.dashboard');
            })->name('pd.dashboard');

            Route::get('/transport/dashboard', function () {
                return view('pages.partners.dashboard.dashboard');
            })->name('transport.dashboard');

            Route::get('/driver/dashboard', function () {
                return view('pages.partners.dashboard.dashboard');
            })->name('driver.dashboard');

            Route::get('/pha/dashboard', function () {
                return view('pages.partners.dashboard.dashboard');
            })->name('pha.dashboard');
        });
        Route::prefix('pick-up-and-drop-offs')->group(function () {
            Route::get('/', function () {
                return view('pages.partners.points.index');
            })->name('pd.index');
            Route::get('create', function () {
                return view('pages.partners.points.create');
            })->name('pd.create');
            Route::get('edit/{id}', function ($id) {
                return view('pages.partners.points.edit', compact('id'));
            })->name('pd.edit');
            Route::get('view/{id}', function ($id) {
                return view('pages.partners.points.view', compact('id'));
            })->name('pd.view');
        });
        Route::prefix('parcel-handling-assistants')->group(function () {
            Route::get('/', function () {
                return view('pages.partners.parcel-handling-assistants.index');
            })->name('pha.index');
            Route::get('create', function () {
                return view('pages.partners.parcel-handling-assistants.create');
            })->name('pha.create');
            Route::get('edit/{id}', function ($id) {
                return view('pages.partners.parcel-handling-assistants.edit', compact('id'));
            })->name('pha.edit');
            Route::get('view/{id}', function ($id) {
                return view('pages.partners.parcel-handling-assistants.view', compact('id'));
            })->name('pha.view');
        });
        Route::prefix('fleet')->group(function () {
            Route::get('/', function () {
                return view('pages.partners.fleet.index');
            })->name('fleet.index');
            Route::get('create', function () {
                return view('pages.partners.fleet.create');
            })->name('fleet.create');
            Route::get('edit/{id}', function ($id) {
                return view('pages.partners.fleet.edit', compact('id'));
            })->name('fleet.edit');
            Route::get('view/{id}', function ($id) {
                return view('pages.partners.fleet.view', compact('id'));
            })->name('fleet.view');
        });
        Route::prefix('drivers')->group(function () {
            Route::get('/', function () {
                return view('pages.partners.drivers.index');
            })->name('drivers.index');
            Route::get('create', function () {
                return view('pages.partners.drivers.create');
            })->name('drivers.create');
            Route::get('edit/{id}', function ($id) {
                return view('pages.partners.drivers.edit', compact('id'));
            })->name('drivers.edit');
            Route::get('view/{id}', function ($id) {
                return view('pages.partners.drivers.view', compact('id'));
            })->name('drivers.view');
        });
        Route::post('partners/logout', function () {
            Auth::guard('partner')->logout();
            return redirect()->route('partners.login');
        })->name('logout');
        Route::prefix('parcels')->group(function () {
            Route::get('/', function () {
                return view('pages.partners.parcels.index');
            })->name('parcels.index');
            Route::get('create', function () {
                return view('pages.partners.parcels.create');
            })->name('parcels.create');
            Route::get('edit/{id}', function ($id) {
                return view('pages.partners.parcels.edit', compact('id'));
            })->name('parcels.edit');
            Route::get('view/{id}', function ($id) {
                return view('pages.partners.parcels.view', compact('id'));
            })->name('parcels.view');
        });
    });
});