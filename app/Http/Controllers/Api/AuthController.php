<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function login(LoginRequest $request)
    {
        if (! Auth::attempt($request->validated())) {
            Log::warning('API login failed', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        Log::info('API login successful', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => $request->ip(),
        ]);

        $token = $this->service->createToken(Auth::user());

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => new UserResource(Auth::user()),
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('API logout', [
            'user_id' => $request->user()?->id,
            'email' => $request->user()?->email,
            'ip' => $request->ip(),
        ]);

        $request->user()?->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }
}
