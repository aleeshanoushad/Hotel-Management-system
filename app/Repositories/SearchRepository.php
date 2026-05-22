<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

class SearchRepository
{
    public function __construct(protected HotelRepository $hotelRepository)
    {
    }

    public function searchAvailability(string $city, int $guests): Collection
    {
        return $this->hotelRepository->searchAvailability($city, $guests);
    }
}
