<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        $topCityIds = Hotel::select('city_id')
            ->selectRaw('count(*) as hotel_count')
            ->groupBy('city_id')
            ->orderByDesc('hotel_count')
            ->limit(5)
            ->pluck('city_id')
            ->toArray();

        $hotelsByCity = Hotel::with(['rooms', 'city'])
            ->whereIn('city_id', $topCityIds)
            ->get()
            ->groupBy(fn ($hotel) => $hotel->city->name ?? 'Unknown');

        $orderedTopCities = collect($topCityIds)
            ->map(fn ($cityId) => \App\Models\City::find($cityId)?->name ?? 'Unknown')
            ->mapWithKeys(fn ($cityName) => [$cityName => $hotelsByCity->get($cityName, collect())]);

        return view('dashboard', [
            'totalHotels' => Hotel::count(),
            'totalRooms' => Room::count(),
            'availableRooms' => Room::sum('available_rooms'),
            'topCities' => $orderedTopCities,
        ]);
    }
}
