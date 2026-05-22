<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Middleware\ApiRequestLogger;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiRequestLogger::class])->group(function () {
    // Debug route to check headers
    Route::get('/debug-headers', function (Illuminate\Http\Request $request) {
        return response()->json([
            'all_headers' => $request->headers->all(),
            'authorization' => $request->header('Authorization'),
            'has_auth' => $request->hasHeader('Authorization'),
            'server' => [
                'HTTP_AUTHORIZATION' => $_SERVER['HTTP_AUTHORIZATION'] ?? 'NOT SET',
            ],
        ]);
    });

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/hotels', [HotelController::class, 'index']);
        Route::post('/hotels', [HotelController::class, 'store']);
        Route::get('/hotels/{hotel}', [HotelController::class, 'show']);

        Route::get('/rooms', [RoomController::class, 'index']);
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::get('/rooms/{room}', [RoomController::class, 'show']);

        Route::get('/search', [SearchController::class, 'index'])
            ->middleware('throttle:search');
    });
});
