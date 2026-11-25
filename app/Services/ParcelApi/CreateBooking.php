<?php

namespace App\Services\ParcelApi;

use App\Models\Contact;
use App\Models\Item;
use App\Models\Parcel;
use App\Models\PickUpAndDropOffPoint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CreateBooking
{
    public function __construct(private BookingOptions $options) {}

    public function run($user, $partner, array $data): array
    {
        $files = [];
        $canonical = $data;
        foreach ($canonical['items'] as &$row) {
            $row['images'] = array_map(fn($file) => hash_file('sha256', $file->getRealPath()), $row['images'] ?? []);
            ksort($row);
        }
        unset($row);
        ksort($canonical);
        $fingerprint = hash('sha256', json_encode($canonical, JSON_THROW_ON_ERROR));
        try {
            return DB::transaction(function () use ($user, $partner, $data, $fingerprint, &$files) {
                $user->newQuery()->whereKey($user->getKey())->lockForUpdate()->firstOrFail();
                $key = ['actor' => $user->getMorphClass() . '#' . $user->getKey(), 'request_id' => $data['request_id']];
                $previous = DB::table('parcel_booking_requests')->where($key)->first();
                if ($previous) {
                    abort_unless(hash_equals($previous->fingerprint, $fingerprint), 409, 'Booking request ID already used with different content.');
                    $ids = json_decode($previous->parcel_ids, true);
                    return [Parcel::whereIn('id', $ids)->where('sender_partner_id', $partner->id)->orderBy('id')->get(), 200];
                }
                $quote = $this->options->quotes($data, (int) $data['transport_partner_id'])->first();
                if (!$quote) {
                    throw ValidationException::withMessages(['transport_partner_id' => 'No complete quote for this route and its items.']);
                }

                $origin = $this->station($data['sender_pick_up_drop_off_point_id'], $data['sender_town_id']);
                abort_unless((string) $origin->partner_id === (string) $partner->id, 403);
                $destination = $this->station($data['delivery_pick_up_drop_off_point_id'], $data['receiver_town_id']);
                $contacts = [];
                foreach (['sender' => $origin, 'receiver' => $destination] as $role => $station) {
                    $contacts[$role] = Contact::create([
                        'name' => $data[$role . '_name'],
                        'phone' => $data[$role . '_phone'],
                        'email' => $data[$role . '_email'] ?? null,
                        'address' => $data[$role . '_address'] ?? null,
                        'town_id' => $station->town_id,
                        'sub_county_id' => $station->town->sub_county_id,
                        'county_id' => $station->town->subCounty->county_id,
                    ]);
                }

                $items = Item::whereKey(array_column($data['items'], 'item_id'))->get()->keyBy('id');
                $parcels = collect();
                foreach ($data['items'] as $index => $row) {
                    $item = $items->get($row['item_id']);
                    $price = $quote['price']['items'][$index];
                    $parcel = new Parcel();
                    $parcel->mergeFillable(['current_status']);
                    $parcel->forceFill([
                        'booking_type' => 'instant',
                        'booking_source' => 'partner-mobile',
                        'delivery_flow' => 'final_destination',
                        'transport_partner_id' => $quote['partner']->id,
                        'sender_partner_id' => $partner->id,
                        'delivery_partner_id' => $destination->partner_id,
                        'sender_pick_up_drop_off_point_id' => $origin->id,
                        'delivery_pick_up_drop_off_point_id' => $destination->id,
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                        'parcel_type' => 'package',
                        'package_type' => $row['package_type'],
                        'content_description' => $row['content_description'],
                        'special_instructions' => $row['special_notes'] ?? null,
                        'payment_on_delivery' => $row['payment_on_delivery'] ?? false,
                        'weight' => collect([$item->weight, $item->default_weight, $item->unit_weight])->first(fn($w) => is_numeric($w) && $w > 0) ?? 1,
                        'weight_unit' => 'kg',
                        'dimension_unit' => 'cm',
                        'declared_value' => $price['declared_value'],
                        'insurance_required' => $row['insurance_required'] ?? false,
                        'insurance_charge' => $price['insurance'],
                        'insurance_amount' => $price['insurance'],
                        'base_price' => $price['courier_price'],
                        'tax_amount' => $price['tax_amount'],
                        'total_amount' => $price['total'],
                        'payment_method' => 'mpesa',
                        'payment_status' => 'pending',
                        'current_status' => Parcel::STATUS_BOOKED,
                        'created_by' => $user->id,
                        'creator_id' => $user->id,
                        'creator_type' => $user->getMorphClass(),
                    ]);
                    foreach ($contacts as $role => $contact) {
                        foreach (['id', 'name', 'phone', 'email', 'town_id', 'address'] as $field) {
                            $parcel->{$role . '_' . $field} = $contact->{$field};
                        }
                    }
                    $parcel->save();
                    $paths = [];
                    foreach ($row['images'] ?? [] as $image) {
                        $path = $image->store('parcel-bookings/' . $parcel->id, 'local');
                        if (!$path) {
                            throw new \RuntimeException('Could not store parcel image.');
                        }
                        $files[] = $paths[] = $path;
                    }
                    $parcel->booking_image_paths = json_encode($paths, JSON_THROW_ON_ERROR);
                    $parcel->save();
                    $parcel->updateParcelStatus(Parcel::STATUS_CREATED, $origin->id, $user->id, $user->getMorphClass(), 'Parcel created');
                    $parcel->updateParcelStatus(Parcel::STATUS_BOOKED, $origin->id, $user->id, $user->getMorphClass(), 'Parcel booked');
                    $parcel->addTracking(Parcel::STATUS_BOOKED, $user->id, $user->getMorphClass());
                    $payout = $parcel->calculateParcelPayout($parcel->base_price + $parcel->tax_amount, 'direct');
                    $parcel->parcelPayouts()->create([
                        'partner_id' => $partner->id,
                        'type' => 'pickup-dropoff',
                        'origin_id' => $origin->id,
                        'amount' => $payout['pick_up_drop_off_partner']['amount'],
                        'status' => 'pending',
                    ]);
                    $parcels->push($parcel);
                }
                DB::table('parcel_booking_requests')->insert($key + [
                    'fingerprint' => $fingerprint,
                    'parcel_ids' => $parcels->pluck('id')->toJson(),
                ]);
                return [$parcels, 201];
            });
        } catch (\Throwable $e) {
            if ($files) {
                Storage::disk('local')->delete($files);
            }
            throw $e;
        }
    }

    private function station($id, $townId)
    {
        $station = BookingOptions::active(PickUpAndDropOffPoint::query())->whereKey($id)
            ->where('town_id', $townId)->with('town.subCounty')->first();
        if (!$station || !$station->partner_id) {
            throw ValidationException::withMessages(['stations' => 'Select active partner stations in the specified towns.']);
        }
        return $station;
    }
}
