<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\SearchService;

class SearchController extends Controller
{
    public function __construct(protected SearchService $service)
    {
    }

    public function index(SearchRequest $request)
    {
        $result = $this->service->search($request->validated());

        return response()->json($result);
    }
}
