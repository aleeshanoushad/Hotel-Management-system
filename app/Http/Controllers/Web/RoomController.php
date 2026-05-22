<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Hotel;
use App\Services\RoomService;

class RoomController extends Controller
{
    public function __construct(protected RoomService $service)
    {
    }

    public function index()
    {
        return view('rooms.index', [
            'rooms' => $this->service->list(15),
            'hotels' => Hotel::orderBy('name')->get(),
        ]);
    }

    public function store(RoomRequest $request)
    {
        $this->service->create($request->validated());

        return back()->with('success', 'Room created successfully.');
    }
}
