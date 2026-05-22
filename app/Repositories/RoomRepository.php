<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository
{
    public function create(array $data): Room
    {
        return Room::create($data);
    }

    public function list(int $perPage = 15)
    {
        return Room::with('hotel')->paginate($perPage);
    }

    public function find(int $id): ?Room
    {
        return Room::with('hotel')->find($id);
    }
}
