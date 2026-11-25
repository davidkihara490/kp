<?php

namespace App\Http\Resources\ParcelApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransportPartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $partner = $this->resource['partner'];
        return [
            'id' => $partner->id,
            'name' => $partner->company_name,
            'logo' => $this->resource['logo'],
            'currency' => 'KES',
            'total' => $this->resource['price']['totals']['total'],
            'rates' => RateResource::collection($partner->partnerRates),
        ];
    }
}
