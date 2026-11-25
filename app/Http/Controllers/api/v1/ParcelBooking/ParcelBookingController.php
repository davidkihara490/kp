<?php

namespace App\Http\Controllers\api\v1\ParcelBooking;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParcelApi\BookedParcelResource;
use App\Http\Resources\ParcelApi\ParcelStatusResource;
use App\Jobs\SendParcelPickupCode;
use App\Models\Parcel;
use App\Models\PickUpAndDropOffPoint;
use App\Services\ParcelApi\BookingOptions;
use App\Services\ParcelApi\CreateBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ParcelBookingController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'search' => 'nullable|string|max:100',
            'direction' => 'nullable|in:incoming,outgoing',
            'status' => 'nullable|string|max:50',
            'payment_status' => 'nullable|string|max:50',
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
            'per_page' => 'sometimes|integer|between:1,100',
        ]);
        $partner = $this->partner($request);
        $query = $this->visible($request, $partner);
        if (isset($data['direction'])) {
            $column = $data['direction'] === 'incoming' ? 'delivery_partner_id' : 'sender_partner_id';
            $query->where($column, $partner->id);
        }
        foreach (['status' => 'current_status', 'payment_status' => 'payment_status'] as $key => $column) {
            if (isset($data[$key])) {
                $query->where($column, $data[$key]);
            }
        }
        $query->when($data['search'] ?? null, fn($q, $term) => $q->where('parcel_id', 'like', '%' . $term . '%'))
            ->when($data['from'] ?? null, fn($q, $day) => $q->whereDate('created_at', '>=', $day))
            ->when($data['to'] ?? null, fn($q, $day) => $q->whereDate('created_at', '<=', $day));
        return BookedParcelResource::collection($query->latest('id')->paginate($data['per_page'] ?? 25)->withQueryString());
    }

    public function store(Request $request, CreateBooking $booking)
    {
        $partner = $this->partner($request);
        abort_unless(
            in_array($request->user()->user_type, ['pickup-dropoff', 'pha'], true),
            403,
            'Only a pickup/drop-off partner or parcel handling assistant can create a booking.'
        );
        $rules = BookingOptions::quoteRules() + [
            'request_id' => 'required|uuid',
            'transport_partner_id' => 'required|integer',
            'sender_pick_up_drop_off_point_id' => 'required|integer',
            'delivery_pick_up_drop_off_point_id' => 'required|integer',
            'items.*.package_type' => 'required|in:regular,fragile,perishable,valuable,hazardous,oversized',
            'items.*.content_description' => 'required|string|max:1000',
            'items.*.special_notes' => 'nullable|string|max:1000',
            'items.*.payment_on_delivery' => 'sometimes|boolean',
            'items.*.image_count' => 'sometimes|integer|between:0,5',
            'items.*.images' => 'sometimes|array|list|max:5',
            'items.*.images.*' => 'bail|required|file|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
        foreach (['sender', 'receiver'] as $role) {
            $rules[$role . '_name'] = 'required|string|max:255';
            $rules[$role . '_phone'] = 'required|string|max:20';
            $rules[$role . '_email'] = 'nullable|email|max:255';
            $rules[$role . '_address'] = 'nullable|string|max:255';
        }
        $data = $request->validate($rules);
        foreach ($data['items'] as $i => $row) {
            if (isset($row['image_count']) && (int) $row['image_count'] !== count($row['images'] ?? [])) {
                throw ValidationException::withMessages(["items.$i.images" => 'The number of uploaded images differs from image_count.']);
            }
        }
        [$parcels, $status] = $booking->run($request->user(), $partner, $data);
        return response()->json(['data' => ['parcels' => BookedParcelResource::collection($parcels)]], $status);
    }

    public function show(Request $request, int $parcel)
    {
        $record = $this->visible($request, $this->partner($request))->findOrFail($parcel);
        $fields = $record->only(['sender_name', 'sender_phone', 'receiver_name', 'receiver_phone','receiver_address']);
        return response()->json(['data' => (new BookedParcelResource($record))->resolve($request) + $fields]);
    }

    public function timeline(Request $request, int $parcel)
    {
        $record = $this->visible($request, $this->partner($request))->findOrFail($parcel);
        return ParcelStatusResource::collection($record->statuses()->reorder('id')->get());
    }

    public function image(Request $request, int $parcel, int $position)
    {
        $record = $this->visible($request, $this->partner($request))->findOrFail($parcel);
        $path = BookedParcelResource::paths($record)[$position - 1] ?? null;
        $disk = Storage::disk('local');
        abort_unless($path && $disk->exists($path), 404);
        return $disk->response($path, null, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }

    public function accept(Request $request, int $parcel)
    {
        return $this->transition($request, $parcel, function ($p, $partner, $user) {
            $this->requireStation($user, $partner, $p->sender_pick_up_drop_off_point_id);
            abort_unless($p->current_status === Parcel::STATUS_CREATED && $p->canBeAccepted() && $p->payment_status === 'paid', 409, 'A paid customer web booking in created status is required.');
            $p->pha_id = $user->parcelHandlingAssistant?->id;
            $this->record($p, $user, Parcel::STATUS_BOOKED, $p->sender_pick_up_drop_off_point_id, 'Accepted by origin station');
        });
    }

    public function assignDriver(Request $request, int $parcel)
    {
        $data = $request->validate(['driver_id' => 'required|integer', 'vehicle_id' => 'nullable|integer']);
        return $this->transition($request, $parcel, function ($p, $partner, $user) use ($data) {
            abort_unless($user->user_type === 'transport' && (string) $p->transport_partner_id === (string) $partner->id, 403);
            abort_unless(in_array($p->current_status, [Parcel::STATUS_BOOKED, Parcel::STATUS_WAREHOUSE], true), 409, 'Parcel is not awaiting assignment.');
            $driver = BookingOptions::active($partner->drivers())->findOrFail($data['driver_id']);
            if (isset($data['vehicle_id'])) {
                $partner->fleets()->findOrFail($data['vehicle_id']);
            }
            $station = $p->current_status === Parcel::STATUS_WAREHOUSE ? $p->warehouse_id : $p->sender_pick_up_drop_off_point_id;
            $p->driver_id = $driver->id;
            $p->vehicle_id = $data['vehicle_id'] ?? null;
            $p->assigned_at = now();
            $p->assigned_by = $user->id;
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->record($p, $user, Parcel::STATUS_ASSIGNED, $station, 'Driver assigned', $code);
            return ['driver_code' => $code];
        });
    }

    public function releaseToDriver(Request $request, int $parcel)
    {
        $data = $request->validate(['driver_code' => 'required|string|size:6']);
        return $this->transition($request, $parcel, function ($p, $partner, $user) use ($data) {
            abort_unless($p->current_status === Parcel::STATUS_ASSIGNED, 409, 'Parcel is not assigned.');
            $assignment = $p->statuses()->reorder('id', 'desc')->firstOrFail();
            $this->requireStation($user, $partner, $assignment->current_location);
            abort_unless($assignment->status === Parcel::STATUS_ASSIGNED && $assignment->driver_id && !$assignment->otp_verified, 409);
            if (!hash_equals((string) $assignment->otp, $data['driver_code'])) {
                throw ValidationException::withMessages(['driver_code' => 'Incorrect driver handover code.']);
            }
            $assignment->otp_verified = true;
            $assignment->save();
            $p->driver_id = $assignment->driver_id;
            $this->record($p, $user, Parcel::STATUS_IN_TRANSIT, $assignment->current_location, 'Released to assigned driver');
            $p->parcelPayouts()->where('type', 'pickup-dropoff')->where('origin_id', $assignment->current_location)
                ->where('status', 'pending')->update(['status' => 'approved']);
        });
    }

    public function receiveFromDriver(Request $request, int $parcel)
    {
        return $this->transition($request, $parcel, function ($p, $partner, $user) {
            abort_unless($p->current_status === Parcel::STATUS_IN_TRANSIT, 409, 'Parcel is not in transit.');
            $leg = $p->statuses()->reorder('id', 'desc')->firstOrFail();
            abort_unless($leg->status === Parcel::STATUS_IN_TRANSIT && $leg->driver_id, 409);
            $warehouseLeg = $p->delivery_flow === 'warehouse' && (string) $leg->current_location === (string) $p->sender_pick_up_drop_off_point_id;

            Log::info('DD'. $warehouseLeg );

            $station = $warehouseLeg ? $p->warehouse_id : $p->delivery_pick_up_drop_off_point_id;
            $this->requireStation($user, $partner, $station);
            $status = $warehouseLeg ? Parcel::STATUS_WAREHOUSE : Parcel::STATUS_ARRIVED_AT_DESTINATION;
            $this->record($p, $user, $status, $station, 'Received from driver');
            if (!$warehouseLeg) {
                $p->parcelPayouts()->where('type', 'transport')->where('status', 'pending')->update(['status' => 'approved']);
                $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $p->otp = Hash::make($code);
                $p->otp_expires_at = now()->addHours(12);
                $p->save();
                SendParcelPickupCode::dispatch($p->id, $code)->onConnection('database')->beforeCommit();
            }
        });
    }

    public function confirmPickup(Request $request, int $parcel)
    {
        $data = $request->validate([
            'pickup_code' => 'required|string|size:6',
            'pickup_person_type' => 'required|in:owner,other',
            'confirm_terms' => 'required|accepted',
            'picker_name' => 'required_if:pickup_person_type,other|nullable|string|min:3|max:255',
            'picker_phone' => 'required_if:pickup_person_type,other|nullable|string|min:10|max:20',
            'picker_id_number' => 'required_if:pickup_person_type,other|nullable|string|min:3|max:50',
            'picker_relationship' => 'nullable|string|max:100',
            'picker_reason' => 'nullable|string|max:500',
        ]);
        return $this->transition($request, $parcel, function ($p, $partner, $user) use ($data) {
            $this->requireStation($user, $partner, $p->delivery_pick_up_drop_off_point_id);
            abort_unless($p->current_status === Parcel::STATUS_ARRIVED_AT_DESTINATION && !$p->parcelPickUp()->exists(), 409, 'Parcel is not awaiting pickup.');
            if (!$p->otp || !$p->otp_expires_at || Carbon::parse($p->otp_expires_at)->isPast() || !Hash::check($data['pickup_code'], $p->otp)) {
                throw ValidationException::withMessages(['pickup_code' => 'Invalid or expired pickup code.']);
            }
            $owner = $data['pickup_person_type'] === 'owner';
            $p->parcelPickUp()->create([
                'pickup_verified_by' => $user->id,
                'pickup_verified_at' => now(),
                'pickup_person_type' => $data['pickup_person_type'],
                'pickup_code' => $data['pickup_code'],
                'pickup_person_name' => $owner ? $p->receiver_name : $data['picker_name'],
                'pickup_person_phone' => $owner ? $p->receiver_phone : $data['picker_phone'],
                'pickup_person_id' => $owner ? $p->receiver_id_number : $data['picker_id_number'],
                'pickup_person_relationship' => $data['picker_relationship'] ?? null,
                'pickup_reason' => $data['picker_reason'] ?? null,
            ]);
            $p->otp = null;
            $p->otp_expires_at = null;
            $this->record($p, $user, Parcel::STATUS_PICKED, $p->delivery_pick_up_drop_off_point_id, 'Recipient pickup confirmed');
            $payout = $p->calculateParcelPayout($p->base_price + $p->tax_amount, 'direct');
            $p->parcelPayouts()->create([
                'partner_id' => $partner->id,
                'origin_id' => $p->sender_pick_up_drop_off_point_id,
                'destination_id' => $p->delivery_pick_up_drop_off_point_id,
                'destination' => 'final',
                'type' => 'pickup-dropoff',
                'status' => 'approved',
                'amount' => $payout['pick_up_drop_off_partner']['amount'],
            ]);
        });
    }

    private function partner(Request $request)
    {
        $user = $request->user();
        $partner = match ($user->user_type) {
            'pha' => $user->parcelHandlingAssistant?->partner,
            'transport', 'pickup-dropoff' => $user->partner,
            default => null,
        };
        abort_unless($partner && $partner->verification_status === 'verified', 403, 'Verified partner access required.');
        if ($user->user_type === 'pha') {
            abort_if($user->parcelHandlingAssistant->status === 'suspended', 403);
        }
        return $partner;
    }

    private function visible(Request $request, $partner)
    {
        if ($request->user()->user_type === 'transport') {
            return Parcel::where('transport_partner_id', $partner->id);
        }
        return Parcel::where(function ($q) use ($partner) {
            $q->where('sender_partner_id', $partner->id)->orWhere('delivery_partner_id', $partner->id)
                ->orWhereHas('warehouse', fn($station) => $station->where('partner_id', $partner->id));
        });
    }

    private function requireStation($user, $partner, $stationId): void
    {
        abort_unless(in_array($user->user_type, ['pickup-dropoff', 'pha'], true)
            && BookingOptions::active(PickUpAndDropOffPoint::query())->whereKey($stationId)->where('partner_id', $partner->id)->exists(), 403);
    }

    private function transition(Request $request, int $id, callable $operation)
    {
        $partner = $this->partner($request);
        return DB::transaction(function () use ($request, $partner, $id, $operation) {
            $parcel = $this->visible($request, $partner)->lockForUpdate()->findOrFail($id);
            $extra = $operation($parcel, $partner, $request->user()) ?? [];
            return response()->json(['data' => (new BookedParcelResource($parcel))->resolve($request) + $extra]);
        });
    }

    private function record($parcel, $user, string $status, $station, string $note, ?string $driverCode = null): void
    {
        $parcel->mergeFillable(['current_status']);
        $parcel->current_status = $status;
        $parcel->save();
        $parcel->updateParcelStatus($status, $station, $user->id, $user->getMorphClass(), $note, $parcel->driver_id, $driverCode);
        $parcel->addTracking($status, $user->id, $user->getMorphClass(), $note);
    }
}
