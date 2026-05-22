<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Services\HotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct(protected HotelService $service)
    {
    }

    public function index(Request $request)
    {
        $hotels = $this->service->list([
            'city' => $request->query('city'),
        ], 10);

        return view('hotels.index', [
            'hotels' => $hotels,
            'city' => $request->query('city', ''),
        ]);
    }

    public function store(HotelRequest $request)
    {
        $this->service->create($request->validated());

        return back()->with('success', 'Hotel created successfully.');
    }
}
