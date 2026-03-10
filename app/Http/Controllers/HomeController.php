<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\County;
use App\Models\FAQ;
use App\Models\PickUpAndDropOffPoint;
use App\Models\SubCounty;
use App\Models\Town;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $towns = Town::where('status', true)->get();
        $pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', 'active')->get();
        $blogPosts = BlogPost::where('status', 'published')->limit(4)->get();
        $faqs = FAQ::where('status', true)->get();

        $counties = County::whereHas('subCounties.towns.pickUpAndDropOffPoint')
            ->with(['subCounties.towns.pickUpAndDropOffPoint'])
            ->orderBy('name')
            ->limit(5)
            ->get();
        return  view('frontend.home', compact('towns', 'pickUpAndDropOffPoints', 'blogPosts', 'faqs', 'counties'));
    }
}
