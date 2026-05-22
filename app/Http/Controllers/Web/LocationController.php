<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function cities(Country $country)
    {
        $cities = $country->cities()->orderBy('name')->get(['id', 'name']);

        return response()->json($cities);
    }

    public function countries()
    {
        return Country::orderBy('name')->get(['id', 'name']);
    }
}
