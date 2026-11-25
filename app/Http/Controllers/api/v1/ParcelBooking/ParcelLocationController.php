<?php

namespace App\Http\Controllers\api\v1\ParcelBooking;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParcelApi\CountyResource;
use App\Models\County;
use App\Services\ParcelApi\BookingOptions;

class ParcelLocationController extends Controller
{
    public function index()
    {
        $points = fn ($q) => BookingOptions::active($q)->orderBy('name');
        $towns = fn ($q) => $q->whereHas('pickUpAndDropOffPoint', $points)->orderBy('towns.name');
        $counties = County::whereHas('towns', $towns)
            ->with(['towns' => $towns, 'towns.pickUpAndDropOffPoint' => $points])->orderBy('name')->get();
        return CountyResource::collection($counties);
    }
}
