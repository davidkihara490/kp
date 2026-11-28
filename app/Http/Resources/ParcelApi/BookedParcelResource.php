<?php

namespace App\Http\Resources\ParcelApi;

use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\ParcelPaymentResource;

class BookedParcelResource extends JsonResource
{
    public static function paths(Parcel $parcel): array
    {
        $raw = $parcel->getRawOriginal('booking_image_paths');
        if ($raw === null || $raw === '') {
            return [];
        }

        $paths = is_array($raw) ? $raw : json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        return array_values($paths ?? []);
    }

    public function toArray(Request $request): array
    {
        // The delivery accessors call Carbon methods on these three columns.
        $parcel = clone $this->resource;
        $parcel->mergeCasts([
            'estimated_delivery_date' => 'datetime',
            'actual_delivery_date' => 'datetime',
            'pickup_at' => 'datetime',
        ]);

        $fields = $parcel->only([
            'id',
            'parcel_id',
            'customer_id',
            'booking_type',
            'booking_source',
            'date',
            'sender_id',
            'sender_name',
            'sender_phone',
            'sender_email',
            'sender_address',
            'sender_town_id',
            'sender_notes',
            'receiver_id',
            'receiver_name',
            'receiver_phone',
            'receiver_email',
            'receiver_address',
            'receiver_town_id',
            'receiver_notes',
            'pha_id',
            'sender_partner_id',
            'sender_pick_up_drop_off_point_id',
            'delivery_partner_id',
            'delivery_pick_up_drop_off_point_id',
            'delivery_flow',
            'warehouse_id',
            'transport_partner_id',
            'driver_id',
            'transporter_id',
            'transporter_type',
            'creator_id',
            'creator_type',
            'created_by',
            'item_id',
            'item_name',
            'parcel_type',
            'package_type',
            'content_description',
            'special_instructions',
            'weight',
            'weight_unit',
            'length',
            'width',
            'height',
            'dimension_unit',
            'parcel_dimensions',
            'parcel_volume',
            'parcel_size_category',
            'declared_value',
            'insurance_amount',
            'insurance_required',
            'insurance_charge',
            'base_price',
            'weight_charge',
            'distance_charge',
            'special_handling_charge',
            'tax_amount',
            'discount_amount',
            'total_amount',
            'payout',
            'payment_method',
            'payment_status',
            'paid_at',
            'payment_on_delivery',
            'payment_on_delivery_status',
            'current_status',
            'parcel_status_badge',
            'estimated_delivery_time',
            'is_overdue',
            'is_delayed',
            'days_in_transit',
            'created_at',
            'updated_at',
        ]);

        $fields['images'] = [];
        $paths = self::paths($parcel);
        if ($paths !== []) {
            $route = Route::has('parcel-api.image') ? 'parcel-api.image' : 'image';
            foreach (array_keys($paths) as $index) {
                $position = $index + 1;
                $fields['images'][] = [
                    'position' => $position,
                    'url' => route($route, ['parcel' => $parcel->id, 'position' => $position]),
                ];
            }
        }
        $fields['payments'] = ParcelPaymentResource::collection(
            $this->whenLoaded('payments')
        );

        return $fields;
    }
}




// namespace App\Http\Resources\ParcelApi;

// use Illuminate\Http\Request;
// use Illuminate\Http\Resources\Json\JsonResource;

// class BookedParcelResource extends JsonResource
// {
//     public static function paths($parcel): array
//     {
//         return json_decode($parcel->getRawOriginal('booking_image_paths') ?? '[]', true, 512, JSON_THROW_ON_ERROR);
//     }

//     public function toArray(Request $request): array
//     {
//         $fields = $this->resource->only([
//             'id', 'parcel_id', 'item_id', 'item_name', 'package_type', 'content_description',
//             'special_instructions', 'declared_value', 'insurance_required', 'insurance_charge',
//             'weight', 'weight_unit', 'payment_on_delivery', 'payment_method', 'payment_status',
//             'base_price', 'tax_amount', 'total_amount', 'current_status', 'driver_id',
//             'sender_town_id', 'receiver_town_id', 'sender_pick_up_drop_off_point_id',
//             'delivery_pick_up_drop_off_point_id', 'transport_partner_id', 'created_at',
//         ]);
//         $fields['images'] = array_map(fn ($index) => [
//             'position' => $index + 1,
//             'url' => route('parcel-api.image', [$this->id, $index + 1]),
//         ], array_keys(self::paths($this->resource)));
//         return $fields;
//     }
// } 
