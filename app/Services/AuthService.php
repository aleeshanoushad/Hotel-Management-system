<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials): bool
    {
        return Auth::attempt($credentials, true);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function createToken(User $user, string $deviceName = 'api-token')
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}
