<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Http\Resources\RoomResource;
use App\Services\RoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function __construct(protected RoomService $service)
    {
    }

    public function index(Request $request)
    {
        $rooms = $this->service->list((int) $request->query('per_page', 15));

        return RoomResource::collection($rooms);
    }

    public function store(RoomRequest $request)
    {
        $room = $this->service->create($request->validated());

        return (new RoomResource($room))->additional([
            'message' => 'Room created successfully.',
        ]);
    }

    public function show(string $id)
    {
        $room = $this->service->find((int) $id);

        if (! $room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }

        return new RoomResource($room);
    }
}
