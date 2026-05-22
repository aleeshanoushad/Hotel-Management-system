<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info('API request', [
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'user_id' => optional($request->user())->id,
            'user_email' => optional($request->user())->email,
            'ip' => $request->ip(),
            'query' => $request->query(),
            'payload' => $request->except([
                'password',
                'password_confirmation',
                'current_password',
                'current_password_confirmation',
            ]),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
