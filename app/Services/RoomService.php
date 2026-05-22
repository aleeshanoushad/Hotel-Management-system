<?php

namespace App\Services;

use App\Repositories\RoomRepository;
use App\Models\Room;

class RoomService
{
    public function __construct(protected RoomRepository $repository)
    {
    }

    public function create(array $data): Room
    {
        return $this->repository->create($data);
    }

    public function list(int $perPage = 15)
    {
        return $this->repository->list($perPage);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}
