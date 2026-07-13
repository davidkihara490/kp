<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\County;
use App\Models\FAQ;
use App\Models\Item;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\PrivacyPolicy;
use App\Models\SubCounty;
use App\Models\TermsAndCondition;
use App\Models\Town;
use App\Models\Zone;
use App\Models\ZoneCounty;
use App\Models\ZoneTown;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $towns = Town::whereHas('pickUpAndDropOffPoint')
            ->where('status', true)
            ->orderBy('name', 'ASC')
            ->get();

        $pickUpAndDropOffPoints = PickUpAndDropOffPoint::where('type', 'pickup-dropoff')->where('status', 'active')->get();
        $blogPosts = BlogPost::where('status', 'published')->limit(4)->get();
        $faqs = FAQ::where('status', true)->get();
        $parcelTypes = Item::all();
        $itemCategories = Item::where('status', true)->get();

        $counties = County::whereHas('subCounties.towns.pickUpAndDropOffPoint')
            ->with(['subCounties.towns.pickUpAndDropOffPoint'])
            ->orderBy('name')
            ->limit(5)
            ->get();
        foreach ($counties as $county) {
            $county->points_count = $county->subCounties->sum(function ($subCounty) {
                return $subCounty->towns->sum(fn($town) => $town->pickUpAndDropOffPoint->where('status', 'active')->count());
            });
        }

        $countiesCovered = County::count();
        $totalPickUpPoints = PickUpAndDropOffPoint::where('status', 'active')->count();

        return  view('frontend.home', compact('totalPickUpPoints', 'countiesCovered', 'towns', 'pickUpAndDropOffPoints', 'blogPosts', 'faqs', 'counties', 'parcelTypes', 'itemCategories'));
    }


    public function calculate(Request $request)
    {
        // TODO::Add insuarance
        $request->validate([
            'from_town_id' => 'required|exists:towns,id',
            'to_town_id' => 'required|exists:towns,id|different:from_town_id',
            'parcel_weight' => 'required|numeric|min:0.1',
        ]);

        try {
            $fromTown = Town::findOrFail($request->from_town_id);
            $toTown = Town::findOrFail($request->to_town_id);

            $fromZone = ZoneTown::where('town_id', $fromTown->id)->first();
            $fromZoneId = $fromZone->zone_id;

            $toZone = ZoneTown::where('town_id', $toTown->id)->first();
            $toZoneId = $toZone->zone_id;


            $pricing = PricingItem::where('source_zone_id', $fromZoneId)
                ->where('destination_zone_id', $toZoneId)->first();

            if (!$pricing) {
                return;
            }
            $basePrice = (float) ($pricing->cost ?? 0);
            $extraKgCost = (float) ($pricing->extra ?? 0);


            if ($request->parcel_weight <= 5) {
                $base_price = round($basePrice, 2);
            } else {
                $extraWeight = $request->parcel_weight - 5;
                $base_price = round(
                    $basePrice + ($extraWeight * $extraKgCost),
                    2
                );
            }

            $tax_amount = round(
                $base_price * 0.16,
                2
            );

            $total_amount = round(
                $base_price +
                    $tax_amount,
                2
            );

            $calculatedPrice = $total_amount;

            return response()->json([
                'success' => true,
                'from_town' => $fromTown->name,
                'to_town' => $toTown->name,
                'from_town_id' => $fromTown->id,
                'to_town_id' => $toTown->id,
                'weight' => $request->parcel_weight,
                // 'item_category' => $item->name,
                'total' => $calculatedPrice,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to calculate quote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPoints()
    {
        $counties = County::whereHas('subCounties.towns.pickUpAndDropOffPoint')
            ->with(['subCounties.towns.pickUpAndDropOffPoint'])
            ->orderBy('name')
            ->get();
        foreach ($counties as $county) {
            $county->points_count = $county->subCounties->sum(function ($subCounty) {
                return $subCounty->towns->sum(fn($town) => $town->pickUpAndDropOffPoint->where('status', 'active')->count());
            });
        }
        return view('frontend.points', compact('counties'));
    }

    public function terms()
    {
        $terms = TermsAndCondition::where('is_active', true)->orderBy('id', 'DESC')->first();
        return view('frontend.terms', compact('terms'));
    }

    public function policy()
    {
        $policy = PrivacyPolicy::where('is_active', true)->orderBy('id', 'DESC')->first();
        return view('frontend.policy', compact('policy'));
    }

    public function sendContactEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $toEmail = 'davidkihara490@gmail.com';

        Mail::raw(
            "Name: {$request->name}\n" .
                "Email: {$request->email}\n" .
                "Subject: {$request->subject}\n\n" .
                "Message:\n{$request->message}",
            function ($message) use ($request, $toEmail) {
                $message->to($toEmail)
                    ->subject($request->subject)
                    ->replyTo($request->email, $request->name);
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully.',
        ]);
    }
    public function getPricing(Request $request)
    {
        // Get search and filter values from request
        $search = $request->get('search', '');
        $itemFilter = $request->get('item_filter', '');
        $zoneFilter = $request->get('zone_filter', '');

        // Get the pricing record with ID 1
        $pricing = Pricing::with(['items.sourceZone', 'items.destinationZone'])
            ->where('id', 1)
            ->first();

        // Get pricing items query
        $query = PricingItem::with(['sourceZone', 'destinationZone'])
            ->where('pricing_id', 1);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('sourceZone', function ($zoneQuery) use ($search) {
                    $zoneQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('destinationZone', function ($zoneQuery) use ($search) {
                    $zoneQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // Apply item filter
        if ($itemFilter) {
            $query->where('item_id', $itemFilter);
        }

        // Apply zone filter
        if ($zoneFilter) {
            $query->where(function ($q) use ($zoneFilter) {
                $q->where('source_zone_id', $zoneFilter)
                    ->orWhere('destination_zone_id', $zoneFilter);
            });
        }

        // Get all results (no pagination)
        $pricingItems = $query->orderBy('source_zone_id')
            ->orderBy('destination_zone_id')
            ->get();

        // Get all items and zones for filters
        $items = Item::orderBy('name')->get();
        $zones = Zone::orderBy('name')->get();

        // Return view with data
        return view('frontend.pricing', compact('pricing', 'pricingItems', 'items', 'zones', 'search', 'itemFilter', 'zoneFilter'));
    }

    public function downloadPDF(Request $request)
    {
        // Fetch data
        $zones = Zone::with('towns')->orderBy('name')->get();

        $pricingItems = PricingItem::with(['sourceZone', 'destinationZone'])
            ->where('pricing_id', 1)
            ->where('status', 'active')
            ->orderBy('source_zone_id')
            ->orderBy('destination_zone_id')
            ->get();

        // Prepare data for view
        $data = [
            'zones' => $zones,
            'pricingItems' => $pricingItems,
            'generated_date' => now()->format('F d, Y'),
            'company_name' => 'Karibu Parcels',
            'logo' => public_path('logo.jpeg')
        ];

        // Load view and generate PDF in landscape
        $pdf = Pdf::loadView('pdfs.tariffs', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        // Download PDF
        return $pdf->stream('Karibu_Parcels_Tariffs_' . now()->format('Y-m-d') . '.pdf');
    }

    public function prohibitedItems()
    {
        $path = storage_path("app/public/prohibited_items.pdf");
        abort_unless(file_exists($path), 404);
        return response()->file($path);
    }
}
