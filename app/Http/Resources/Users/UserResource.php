<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Partners\PartnerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'first_name'   => $this->first_name,
            'second_name'  => $this->second_name,
            'last_name'    => $this->last_name,
            'user_name'    => $this->user_name,
            'user_type'    => $this->user_type,
            'full_name'    => trim("{$this->first_name} {$this->last_name}"),
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'status'       => $this->status,

            // Partner relation — only included when eager-loaded
            'partner' => $this->whenLoaded('partner', fn () => new PartnerResource($this->partner)),

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}