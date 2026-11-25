<?php

namespace App\Http\Resources\ParcelApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'partner_id' => $this->partner_id,
            'town_id' => $this->town_id,
            'type' => $this->type,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'building' => $this->building,
            'room_number' => $this->room_number,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'operating_days' => $this->operating_days,
            'opening_hours' => $this->opening_hours,
            'closing_hours' => $this->closing_hours,
        ];
    }
}
