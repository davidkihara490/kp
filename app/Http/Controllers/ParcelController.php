<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Parcel;
use App\Models\PickUpAndDropOffPoint;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParcelController extends Controller
{
    public function bookOnline(Request $request)
    {
        $fromTownId = $request->query('from_town_id');
        $toTownId = $request->query('to_town_id');
        $parcelWeight = $request->query('parcel_weight');
        $price = $request->query('price');

        $pickupPoints = PickUpAndDropOffPoint::with('town')->where('town_id', $fromTownId)->get();
        $dropoffPoints = PickUpAndDropOffPoint::with('town')->where('town_id', $toTownId)->get();

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
        $validated = $request->validate([
            // Sender Information
            'sender_name' => 'required|string|max:255',
            'sender_phone' => 'required|string',
            'sender_email' => 'nullable|email|max:255',
            'sender_town_id' => 'required|exists:towns,id',
            'sender_address' => 'nullable|string|max:500',
            'sender_notes' => 'nullable|string|max:500',

            // Receiver Information
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string',
            'receiver_email' => 'nullable|email|max:255',
            'receiver_town_id' => 'required|exists:towns,id',
            'receiver_address' => 'nullable|string|max:500',
            'receiver_notes' => 'nullable|string|max:500',

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
            'payment_method' => 'nullable|in:mpesa,cash,card,bank_transfer',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'total_amount' => 'nullable|numeric|min:0',

            // Terms
            'terms' => 'required|accepted',
        ]);

        // Begin transaction
        DB::beginTransaction();

        try {
            // Prepare parcel data
            $parcelData = [
                // Basic Information
                'customer_id' => Auth::guard('customer')->user()->id,
                'booking_type' => $validated['booking_type'] ?? 'instant',
                'booking_source' => $validated['booking_source'] ?? 'web',

                // Sender Information
                'sender_name' => $validated['sender_name'],
                'sender_phone' => $validated['sender_phone'],
                'sender_email' => $validated['sender_email'] ?? null,
                'sender_town_id' => $validated['sender_town_id'],
                'sender_address' => $validated['sender_address'] ?? null,
                'sender_notes' => $validated['sender_notes'] ?? null,

                // Receiver Information
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'receiver_email' => $validated['receiver_email'] ?? null,
                'receiver_town_id' => $validated['receiver_town_id'],
                'receiver_address' => $validated['receiver_address'] ?? null,
                'receiver_notes' => $validated['receiver_notes'] ?? null,

                // Pickup Information (default values)
                'pha_id' => null,
                'sender_partner_id' => null,
                'sender_pick_up_drop_off_point_id' => null,
                'date' => now(),

                // Parcel Details
                'parcel_id' => Parcel::generateParcelNumber(),
                'parcel_type' => $validated['parcel_type'],
                'package_type' => $validated['package_type'],
                'weight' => $validated['weight'],
                'length' => $validated['length'] ?? null,
                'width' => $validated['width'] ?? null,
                'height' => $validated['height'] ?? null,
                'dimension_unit' => $validated['dimension_unit'] ?? 'cm',
                'weight_unit' => $validated['weight_unit'] ?? 'kg',
                'declared_value' => $validated['declared_value'] ?? null,
                'insurance_amount' => $validated['insurance_required'] ? ($validated['declared_value'] * 0.02) : null,
                'insurance_required' => $validated['insurance_required'] ?? false,
                'content_description' => $validated['content_description'],
                'special_instructions' => $validated['special_instructions'] ?? null,

                // Delivery Information (default values)
                'delivery_partner_id' => null,
                'delivery_pick_up_drop_off_point_id' => null,
                'delivery_flow' => 'standard',
                'warehouse_id' => null,

                // Pricing
                'base_price' => round($validated['total_amount'] * 0.84),
                'insurance_charge' => $validated['insuarance_amount'],
                'tax_amount' => round($validated['total_amount'] * 0.16),
                'discount_amount' => $charges['discount_amount'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'payment_method' => $validated['payment_method'] ?? 'cash',
                'payment_status' => $validated['payment_status'] ?? 'pending',

                // Creator
                'creator_id' => Auth::guard('customer')->user()->id,
                'creator_type' => Customer::class,
            ];

            // Create the parcel
            $parcel = Parcel::create($parcelData);

            dd($parcel);
            // Add initial tracking status
            // $parcel->addTracking(
            //     Parcel::STATUS_CREATED,
            //     auth()->id() ?? null,
            //     'Parcel booked via online booking form'
            // );


            // $parcel->updateParcelStatus(
            //     Parcel::STATUS_CREATED,
            //     $this->sender_pick_up_drop_off_point_id,
            //     Auth::guard('partner')->user()->id,
            //     current_user_type(),
            //     'Parcel created',
            //     null,
            //     null,
            // );

            DB::commit();

            // Redirect with success message
            return redirect()
                ->route('booking.success', $parcel->parcel_id)
                ->with('success', 'Parcel booked successfully! Your tracking ID is: ' . $parcel->parcel_id);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error
            \Log::error('Parcel booking failed: ' . $e->getMessage(), [
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
        return view('booking.success', compact('parcel'));
    }
}
