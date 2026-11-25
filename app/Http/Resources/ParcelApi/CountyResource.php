<?php

namespace App\Http\Resources\ParcelApi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'towns' => TownResource::collection($this->towns),
        ];
    }
}
