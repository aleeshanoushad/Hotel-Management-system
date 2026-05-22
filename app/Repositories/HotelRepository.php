<?php

namespace App\Repositories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Collection;

class HotelRepository
{
    public function create(array $data): Hotel
    {
        return Hotel::create($data);
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        $query = Hotel::with(['city', 'country'])->withCount('rooms');

        if (! empty($filters['city'])) {
            $search = $filters['city'];
            $query->whereHas('city', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        if (! empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Hotel
    {
        return Hotel::with(['city', 'country', 'rooms'])->find($id);
    }

    public function searchAvailability(string $city, int $guests)
    {
        return Hotel::with(['rooms' => function ($query) use ($guests) {
            $query->where('available_rooms', '>', 0)
                  ->where('max_occupancy', '>=', $guests);
        }, 'city', 'country'])->whereHas('city', function ($q) use ($city) {
            $q->where('name', 'like', '%'.$city.'%');
        })->whereHas('rooms', function ($query) use ($guests) {
              $query->where('available_rooms', '>', 0)
                    ->where('max_occupancy', '>=', $guests);
          })
          ->get();
    }
}
