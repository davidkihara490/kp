<?php

namespace App\Http\Resources\ParcelApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookedParcelResource extends JsonResource
{
    public static function paths($parcel): array
    {
        return json_decode($parcel->getRawOriginal('booking_image_paths') ?? '[]', true, 512, JSON_THROW_ON_ERROR);
    }

    public function toArray(Request $request): array
    {
        $fields = $this->resource->only([
            'id', 'parcel_id', 'item_id', 'item_name', 'package_type', 'content_description',
            'special_instructions', 'declared_value', 'insurance_required', 'insurance_charge',
            'weight', 'weight_unit', 'payment_on_delivery', 'payment_method', 'payment_status',
            'base_price', 'tax_amount', 'total_amount', 'current_status', 'driver_id',
            'sender_town_id', 'receiver_town_id', 'sender_pick_up_drop_off_point_id',
            'delivery_pick_up_drop_off_point_id', 'transport_partner_id', 'created_at',
        ]);
        $fields['images'] = array_map(fn ($index) => [
            'position' => $index + 1,
            'url' => route('parcel-api.image', [$this->id, $index + 1]),
        ], array_keys(self::paths($this->resource)));
        return $fields;
    }
}
