<?php

use App\Http\Controllers\api\v1\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\Mpesa\MpesaCallbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParcelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Callbacks (these are called by Safaricom)
Route::prefix('mpesa')->group(function () {
    Route::post('/stk-callback', [MpesaCallbackController::class, 'stkCallback']);
    Route::post('/c2b-callback', [MpesaCallbackController::class, 'c2bCallback']);
    Route::post('/b2c-callback', [MpesaCallbackController::class, 'b2cCallback']);
});

Route::post('/calculate-quote', [HomeController::class, 'calculate']);


// Payment routes
Route::post('/process-payment', [PaymentController::class, 'processPayment']);
Route::get('/payment-status/{parcelId}', [PaymentController::class, 'getPaymentStatus']);
Route::get('/generate-receipt/{parcelId}', [PaymentController::class, 'generateReceipt']);
Route::post('/check-payment-status', [PaymentController::class, 'checkPaymentStatus']);

Route::get('/track-parcel', [ParcelController::class, 'trackParcel']);


Route::post('/mpesa/test-callback', function (Request $request) {
    \Log::info('MPESA TEST CALLBACK', [
        'time' => now()->toDateTimeString(),
        'ip' => $request->ip(),
        'headers' => $request->headers->all(),
        'raw' => $request->getContent(),
        'data' => $request->all(),
    ]);

    return response()->json([
        'ResultCode' => 0,
        'ResultDesc' => 'Accepted',
    ]);
});