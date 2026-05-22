<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Http\Resources\HotelResource;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct(protected HotelService $service)
    {
    }

    public function index(Request $request)
    {
        $hotels = $this->service->list(
            [
                'city' => $request->query('city'),
                'rating' => $request->query('rating'),
            ],
            (int) $request->query('per_page', 15)
        );

        return HotelResource::collection($hotels);
    }

    public function store(HotelRequest $request)
    {
        $hotel = $this->service->create($request->validated());

        return (new HotelResource($hotel))->additional([
            'message' => 'Hotel created successfully.',
        ]);
    }

    public function show(string $id)
    {
        $hotel = $this->service->find((int) $id);

        if (! $hotel) {
            return response()->json(['message' => 'Hotel not found.'], 404);
        }

        return new HotelResource($hotel->load('rooms'));
    }
}
