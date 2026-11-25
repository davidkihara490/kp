<?php

namespace App\Http\Controllers\api\v1\ParcelBooking;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParcelApi\TransportPartnerResource;
use App\Services\ParcelApi\BookingOptions;
use Illuminate\Http\Request;

class TransportPartnerController extends Controller
{
    public function index(Request $request, BookingOptions $options)
    {
        return TransportPartnerResource::collection($options->quotes($request->validate(BookingOptions::quoteRules())));
    }
}
