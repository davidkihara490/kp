<?php

namespace App\Http\Controllers\api\v1\ParcelBooking;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParcelApi\CategoryResource;
use App\Models\Category;
use App\Services\ParcelApi\BookingOptions;

class ParcelCatalogController extends Controller
{
    public function index()
    {
        $items = fn ($q) => BookingOptions::items($q)->orderBy('name');
        $subs = fn ($q) => BookingOptions::active($q)->whereHas('items', $items)->orderBy('name');
        $categories = BookingOptions::active(Category::query())->whereHas('subCategories', $subs)
            ->with(['subCategories' => $subs, 'subCategories.items' => $items])->orderBy('name')->get();
        return CategoryResource::collection($categories);
    }
}
