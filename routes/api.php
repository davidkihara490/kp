<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\Mpesa\MpesaCallbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Callbacks (these are called by Safaricom)
Route::prefix('mpesa')->group(function () {
    Route::post('/stk-callback', [MpesaCallbackController::class, 'stkCallback']);
    Route::post('/c2b-callback', [MpesaCallbackController::class, 'c2bCallback']);
    Route::post('/b2c-callback', [MpesaCallbackController::class, 'b2cCallback']);
});
