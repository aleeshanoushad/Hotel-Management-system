<?php

namespace App\Services;

use App\Repositories\SearchRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    public function __construct(protected SearchRepository $searchRepository)
    {
    }

    public function search(array $data): array
    {
        $city = $data['city'] ?? '';
        $checkin = isset($data['checkin_date']) ? Carbon::parse($data['checkin_date']) : null;
        $checkout = isset($data['checkout_date']) ? Carbon::parse($data['checkout_date']) : null;
        $guests = (int) ($data['guests'] ?? 1);

        $nights = 1;

        if ($checkin && $checkout && $checkout->gt($checkin)) {
            $nights = abs($checkout->diffInDays($checkin));
        }

        $cacheKey = 'search:'.md5(serialize([$city, $guests, $checkin?->toDateString(), $checkout?->toDateString(), $nights]));

        $results = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($city, $guests, $nights) {
            $hotels = $this->searchRepository->searchAvailability($city, $guests);

            return $hotels->map(function ($hotel) use ($nights) {
                return [
                    'hotel' => [
                        'id' => $hotel->id,
                        'name' => $hotel->name,
                        'city_id' => $hotel->city_id,
                        'country_id' => $hotel->country_id,
                        'city' => $hotel->city->name ?? null,
                        'country' => $hotel->country->name ?? null,
                        'rating' => $hotel->rating,
                        'description' => $hotel->description,
                        'created_at' => $hotel->created_at?->toDateTimeString(),
                        'updated_at' => $hotel->updated_at?->toDateTimeString(),
                    ],
                    'rooms' => $hotel->rooms->map(function ($room) use ($nights) {
                        return array_merge($room->toArray(), [
                            'total_price' => $room->price_per_night * $nights,
                        ]);
                    })->toArray(),
                    'total_hotel_price' => $hotel->rooms->sum(fn ($room) => $room->price_per_night * $nights),
                ];
            })->values()->all();
        });

        return [
            'hotels' => $results,
            'nights' => $nights,
        ];
    }
}
