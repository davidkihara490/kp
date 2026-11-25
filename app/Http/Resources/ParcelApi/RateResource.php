<?php

namespace App\Http\Resources\ParcelApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->item_id,
            'source_zone_id' => $this->source_zone_id,
            'destination_zone_id' => $this->destination_zone_id,
            'cost' => $this->cost,
        ];
    }
}
