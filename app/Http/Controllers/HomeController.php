<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\County;
use App\Models\FAQ;
use App\Models\Item;
use App\Models\PickUpAndDropOffPoint;
use App\Models\SubCounty;
use App\Models\Town;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $towns = Town::where('status', true)->orderBy('name', 'ASC')->get();
        $pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('status', 'active')->get();
        $blogPosts = BlogPost::where('status', 'published')->limit(4)->get();
        $faqs = FAQ::where('status', true)->get();
        $parcelTypes = Item::all();
        $itemCategories = Item::where('status', true)->get();

        $counties = County::whereHas('subCounties.towns.pickUpAndDropOffPoint')
            ->with(['subCounties.towns.pickUpAndDropOffPoint'])
            ->orderBy('name')
            ->limit(5)
            ->get();
        return  view('frontend.home', compact('towns', 'pickUpAndDropOffPoints', 'blogPosts', 'faqs', 'counties', 'parcelTypes', 'itemCategories'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'from_town_id' => 'required|exists:towns,id',
            'to_town_id' => 'required|exists:towns,id|different:from_town_id',
            'weight' => 'required|numeric|min:0.1',
        ]);

        // return response()->json($request->all());

        try {
            // Get towns
            $fromTown = Town::findOrFail($request->from_town_id);
            $toTown = Town::findOrFail($request->to_town_id);

            // Calculate quote using your business logic
            $quote = $this->pricingService->calculateQuote(
                $fromTown,
                $toTown,
                $request->weight,
                $request->item_description
            );

            return response()->json([
                'success' => true,
                'quote_id' => uniqid(), // Generate or get from DB
                'from_town' => $fromTown->name,
                'to_town' => $toTown->name,
                'weight' => $request->weight,
                'item_description' => $request->item_description,
                'base_price' => $quote['base_price'],
                'weight_charge' => $quote['weight_charge'],
                'distance_charge' => $quote['distance_charge'],
                'tax_rate' => 16,
                'tax' => $quote['tax'],
                'additional_charges' => $quote['additional_charges'] ?? 0,
                'total' => $quote['total'],
                'estimated_delivery' => $quote['estimated_delivery'],
                'breakdown' => $quote['breakdown'] ?? []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate quote: ' . $e->getMessage()
            ], 500);
        }
    }
}
