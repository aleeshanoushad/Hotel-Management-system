<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\City;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(protected SearchService $service)
    {
    }

    public function index(SearchRequest $request)
    {
        $results = [];
        $searchExecuted = false;

        if ($request->filled('city_id') && $request->filled('checkin_date') && $request->filled('checkout_date') && $request->filled('guests')) {
            $results = $this->service->search($request->validated());
            $searchExecuted = true;
        }

        return view('search', [
            'results' => $results,
            'filters' => $request->validated(),
            'searchExecuted' => $searchExecuted,
            'cities' => City::with('country')->orderBy('name')->get(),
        ]);
    }
}
