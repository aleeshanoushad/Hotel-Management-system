<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'hotel_name' => $this->whenLoaded('hotel', fn () => $this->hotel->name),
            'name' => $this->name,
            'price_per_night' => $this->price_per_night,
            'max_occupancy' => $this->max_occupancy,
            'available_rooms' => $this->available_rooms,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
