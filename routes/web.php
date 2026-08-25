<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ParcelController;
use App\Http\Controllers\Auth\CustomerAuthController;

require __DIR__ . '/admin_routes.php';
require __DIR__ . '/partner_routes.php';
require __DIR__ . '/pudo_routes.php';


Route::get('/', function () {
    return redirect()->route('partners.login');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/search', [BlogController::class, 'search'])->name('blogs.search');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blogs.category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('blogs.tag');
    Route::get('/{id}/{slug?}', [BlogController::class, 'show'])->name('blogs.show');
});

// Route::get('/marketplace', function () {
//     return view('pages.marketplace.index');
// })->name('marketplace')->middleware('partner.auth');


Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy-policy', [HomeController::class, 'policy'])->name('policy');

Route::get('pick-up-and-drop-off-points',[HomeController::class, 'getPoints'])->name('points');

Route::post('/send-contact-email', [HomeController::class, 'sendContactEmail'])->name('contact.send');

Route::get('pricing', [HomeController::class, 'getPricing'])->name('pricing');

Route::get('/pricing/download-pdf', [HomeController::class, 'downloadPDF'])->name('pricing.download');

Route::get('/book/online', [ParcelController::class, 'bookOnline'])->name('online-booking');

Route::get('/book-parcel', [ParcelController::class, 'bookOnline'])->name('booking.create');
Route::post('/book-parcel', [ParcelController::class, 'store'])->name('booking.store');
Route::get('/booking-success/{parcelId}', [ParcelController::class, 'success'])->name('booking.success');

// Customer AJAX routes
Route::prefix('customer')->group(function () {
    Route::post('/login', [CustomerAuthController::class, 'loginApi'])->name('customer.login.api');
    Route::post('/register', [CustomerAuthController::class, 'registerApi'])->name('customer.register.api');
    Route::post('/logout', [CustomerAuthController::class, 'logoutApi'])->name('customer.logout.api');
    Route::get('/check-auth', [CustomerAuthController::class, 'checkAuth'])->name('customer.check.auth');
});

Route::get('/prohibited-items', [HomeController::class, 'prohibitedItems'])->name('prohibited-items');
Route::get('parcel/{id}/receipt', [ParcelController::class, 'printReceipt'])->name('print-customer-receipt');
