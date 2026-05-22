<?php

namespace App\Services;

use App\Repositories\HotelRepository;
use App\Models\Hotel;

class HotelService
{
    public function __construct(protected HotelRepository $repository)
    {
    }

    public function create(array $data): Hotel
    {
        return $this->repository->create($data);
    }

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->repository->list($filters, $perPage);
    }

    public function find(int $id): ?Hotel
    {
        return $this->repository->find($id);
    }
}
