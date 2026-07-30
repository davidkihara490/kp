<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Parcel;
use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ParcelController extends Controller
{
    public function bookOnline(Request $request)
    {
        $fromTownId = $request->query('from_town_id');
        $toTownId = $request->query('to_town_id');
        $parcelWeight = $request->query('weight');
        $price = $request->query('price');

        $pickupPoints = PickUpAndDropOffPoint::with('town')->where('status', 'active')->where('town_id', $fromTownId)->get();
        $dropoffPoints = PickUpAndDropOffPoint::with('town')->where('status', 'active')->where('town_id', $toTownId)->get();

        $towns = Town::with('subCounty.county')->orderBy('name')->get();
        return view('frontend.book', compact('towns', 'fromTownId', 'toTownId', 'parcelWeight', 'price', 'pickupPoints', 'dropoffPoints'));
    }

    // In your controller method
    public function create()
    {
        $towns = Town::orderBy('name')->get();


        return view('booking.create', compact('towns', 'pickupPoints', 'dropoffPoints'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated =  Validator::make($request->all(), [

            // Sender Information
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string',
            'sender_email' => 'nullable|email|max:255',
            'sender_town_id' => 'required|exists:towns,id',
            'sender_address' => 'nullable|string|max:500',
            'sender_notes' => 'nullable|string|max:500',
            'sender_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            // Receiver Information
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_town_id' => 'required|exists:towns,id',
            'receiver_address' => 'nullable|string|max:500',
            'receiver_notes' => 'nullable|string|max:500',
            'delivery_pick_up_drop_off_point_id' => 'required|exists:pick_up_and_drop_off_points,id',

            // Parcel Details
            'parcel_type' => 'required|in:document,package,envelope,box,pallet',
            'package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous',
            'weight' => 'required|numeric|min:0.1',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'dimension_unit' => 'nullable|in:cm,inches',
            'weight_unit' => 'nullable|in:kg,g,lb',
            'declared_value' => 'nullable|numeric|min:0',
            'insurance_required' => 'boolean',
            'content_description' => 'required|string|max:500',
            'special_instructions' => 'nullable|string|max:500',

            // Booking Type
            'booking_type' => 'nullable|in:instant,scheduled,bulk',
            'booking_source' => 'nullable|string',

            // Payment
            'insuarance_amount' => 'nullable|numeric',
            'total_amount' => 'nullable|numeric|min:0',

            // Terms
            'terms' => 'required|accepted',
        ]);


        if ($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validated->errors()
            ], 422);
        }
        // Begin transaction
        DB::beginTransaction();

        try {
            $senderPoint = PickUpAndDropOffPoint::findOrFail($request->sender_pick_up_drop_off_point_id);
            $receivingPoint = PickUpAndDropOffPoint::findOrFail($request->delivery_pick_up_drop_off_point_id);

            $parcelData = [
                // Basic Information
                'customer_id' => Auth::guard('customer')->user()->id,
                'booking_type' => $request->booking_type ?? 'instant',
                'booking_source' => 'web',

                // Sender Information
                'sender_name' => $request->sender_name,
                'sender_phone' => $request->sender_phone,
                'sender_email' => $request->sender_email ?? null,
                'sender_town_id' => $request->sender_town_id,
                'sender_address' => $request->sender_address ?? null,
                'sender_notes' => $request->sender_notes ?? null,

                // Receiver Information
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email ?? null,
                'receiver_town_id' => $request->receiver_town_id,
                'receiver_address' => 'NULL',
                'receiver_notes' => $request->receiver_notes ?? null,

                // Pickup Information (default values)
                'pha_id' => null,
                'sender_partner_id' => $senderPoint->partner->id,
                'sender_pick_up_drop_off_point_id' => $request->sender_pick_up_drop_off_point_id,
                'date' => now(),

                // Parcel Details
                'parcel_id' => Parcel::generateParcelNumber(),
                'parcel_type' => $request->parcel_type,
                'package_type' => $request->package_type,
                'weight' => $request->weight,
                'length' => $request->length ?? null,
                'width' => $request->width ?? null,
                'height' => $request->height ?? null,
                'dimension_unit' => $request->dimension_unit ?? 'cm',
                'weight_unit' => $request->weight_unit ?? 'kg',
                'declared_value' => $request->declared_value ?? null,
                'insurance_amount' => $request->insurance_required ? ($request->declared_value * 0.02) : 0,
                'insurance_required' => $request->insurance_required ?? false,
                'content_description' => $request->content_description,
                'special_instructions' => $request->special_instructions ?? null,

                // Delivery Information (default values)
                'delivery_partner_id' => $receivingPoint->partner->id,
                'delivery_pick_up_drop_off_point_id' => $request->delivery_pick_up_drop_off_point_id,
                'delivery_flow' => null,
                'warehouse_id' => null,

                // Pricing
                'base_price' => round($request->total_amount * 0.84),
                'insurance_charge' => $request->insuarance_amount ?? 0,
                'tax_amount' => round($request->total_amount * 0.16),
                'total_amount' => $request->total_amount,
                'payment_method' => 'mpesa',
                'payment_status' => 'pending',
                'current_status' => Parcel::STATUS_CREATED,

                // Creator
                'creator_id' => Auth::guard('customer')->user()->id,
                'creator_type' => Customer::class,
            ];

            // Create the parcel
            $parcel = Parcel::create($parcelData);
            $payout = $parcel->calculateParcelPayout((float)($parcel->base_price + $parcel->tax_amount), 'direct');

            $parcel->updateParcelStatus(
                Parcel::STATUS_CREATED,
                null,
                Auth::guard('customer')->user()->id,
                Customer::class,
                'Parcel created',
                null,
                null,
            );

            // Create initial tracking record
            if ($parcel) {
                $parcel->addTracking(Parcel::STATUS_CREATED, Auth::guard('customer')->user()->id, Customer::class);
            }

            DB::commit();

            // Redirect with success message
            return redirect()
                ->route('booking.success', $parcel->parcel_id)
                ->with('success', 'Parcel booked successfully! Your tracking ID is: ' . $parcel->parcel_id);
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());

            // Log the error
            Log::info('Parcel booking failed: ' . $e->getMessage(), [
                'request' => $request->all(),
                'error' => $e
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while processing your booking. Please try again.');
        }
    }

    public function success($parcelId)
    {
        $parcel = Parcel::with(['senderTown', 'receiverTown'])->where('parcel_id', $parcelId)->firstOrFail();
        return view('frontend.success', compact('parcel'));
    }


    /**
     * Track a parcel by phone number and tracking ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackParcel(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'tracking_id' => 'required|string|max:50'
            ]);

            $trackingId = $request->input('tracking_id');

            // Find the parcel
            $parcel = Parcel::with([
                'senderTown',
                'receiverTown',
                'senderPickUpDropOffPoint',
                'deliveryStation',
                'statuses' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])
                ->where(function ($query) use ($trackingId) {
                    $query->where('parcel_id', $trackingId)
                        ->orWhere('id', $trackingId);
                })
                ->first();

            if (!$parcel) {
                return response()->json([
                    'success' => false,
                    'message' => 'No parcel found with the provided details. Please check your phone number and tracking ID.'
                ], 404);
            }

            // Format the parcel data for the frontend
            $formattedData = $this->formatParcelData($parcel);

            return response()->json([
                'success' => true,
                'data' => $formattedData
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input provided.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Parcel tracking error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'An error occurred while tracking your parcel. Please try again later.'
            ], 500);
        }
    }


    /**
     * Format parcel data for API response
     *
     * @param Parcel $parcel
     * @return array
     */
    private function formatParcelData($parcel)
    {
        // Get status history
        $statusHistory = $parcel->statuses->map(function ($status) {
            return [
                'status' => $status->status,
                'created_at' => $status->created_at->toDateTimeString(),
                'location' => $this->getStatusLocation($status),
                'notes' => $status->notes
            ];
        })->toArray();

        // If no status history, create one with current status
        if (empty($statusHistory)) {
            $statusHistory = [
                [
                    'status' => $parcel->current_status ?? 'created',
                    'created_at' => $parcel->created_at->toDateTimeString(),
                    'location' => $parcel->senderTown->name ?? 'N/A',
                    'notes' => 'Parcel created'
                ]
            ];
        }

        // Get current location
        $currentLocation = $this->getCurrentLocation($parcel);

        // Check if there's a latest status with driver
        $latestStatus = $parcel->statuses()
            ->whereNotNull('driver_id')
            ->latest()
            ->first();

        return [
            'parcel_id' => $parcel->parcel_id ?? $parcel->id,
            'tracking_id' => $parcel->parcel_id,
            'sender_phone' => $parcel->sender_phone,
            'receiver_phone' => $parcel->receiver_phone,
            'sender_name' => $parcel->sender_name,
            'receiver_name' => $parcel->receiver_name,
            'sender_town' => $parcel->senderTown->name ?? 'N/A',
            'receiver_town' => $parcel->receiverTown->name ?? 'N/A',
            'from_location' => $parcel->senderPickUpDropOffPoint->name ?? $parcel->senderTown->name ?? 'N/A',
            'to_location' => $parcel->deliveryStation->name ?? $parcel->receiverTown->name ?? 'N/A',
            'current_location' => $currentLocation,
            'current_status' => $parcel->current_status ?? 'created',
            'payment_status' => $parcel->payment_status ?? 'pending',
            'last_updated' => $parcel->updated_at->toDateTimeString(),
            'status_history' => $statusHistory,
            'weight' => $parcel->weight ?? null,
            'weight_unit' => $parcel->weight_unit ?? 'kg',
            'parcel_type' => $parcel->parcel_type ?? null,
            'total_amount' => $parcel->total_amount ?? 0
        ];
    }

    /**
     * Get current location of the parcel
     *
     * @param Parcel $parcel
     * @return string
     */
    private function getCurrentLocation($parcel)
    {
        // Check for latest status location
        $latestStatus = $parcel->statuses()->latest()->first();

        if ($latestStatus && $latestStatus->location) {
            return $latestStatus->location;
        }

        // Determine based on current status
        switch ($parcel->current_status) {
            case 'created':
            case 'booked':
            case 'accepted':
                return $parcel->senderPickUpDropOffPoint->name ?? $parcel->senderTown->name ?? 'N/A';

            case 'assigned':
            case 'in_transit':
                return 'In transit to destination';

            case 'warehouse':
                return $parcel->warehouse->name ?? 'Warehouse';

            case 'arrived_at_destination':
            case 'picked':
            case 'delivered':
                return $parcel->deliveryStation->name ?? $parcel->receiverTown->name ?? 'Destination';

            default:
                return $parcel->senderTown->name ?? 'N/A';
        }
    }

    /**
     * Get location from status
     *
     * @param ParcelStatus $status
     * @return string
     */
    private function getStatusLocation($status)
    {
        if ($status->location) {
            return $status->location;
        }

        // If the status has a driver, try to get location from driver's current location
        if ($status->driver && $status->driver->current_location) {
            return $status->driver->current_location;
        }

        // Default based on status
        switch ($status->status) {
            case 'created':
            case 'booked':
            case 'accepted':
                return $status->parcel->senderPickUpDropOffPoint->name ?? 'N/A';

            case 'in_transit':
                return 'In transit';

            case 'warehouse':
                return $status->parcel->warehouse->name ?? 'Warehouse';

            case 'arrived_at_destination':
                return $status->parcel->deliveryStation->name ?? 'Destination';

            default:
                return 'N/A';
        }
    }

    public function printReceipt($id)
    {
        $parcel = Parcel::findOrFail($id);

        $data = [
            'parcel' => $parcel,
            'logo' => public_path('logo.jpeg')
        ];

        // Load view and generate PDF in landscape
        $pdf = Pdf::loadView('pdfs.parcel-label', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ]);

        // Download PDF
        return $pdf->stream('Karibu_Parcels_Label_' . now()->format('Y-m-d') . '.pdf');
    }
}
