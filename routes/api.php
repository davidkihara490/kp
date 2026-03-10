<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\v1\Mpesa\MpesaCallbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Callbacks (these are called by Safaricom)
// Route::post('mpesa/stk-callback', [MpesaCallbackController::class, 'stkCallback']);
// Route::post('mpesa/c2b-callback', [MpesaCallbackController::class, 'c2bCallback']);
// Route::post('mpesa/b2c-callback', [MpesaCallbackController::class, 'b2cCallback']);



Route::get('test', function() { return 'ok'; });