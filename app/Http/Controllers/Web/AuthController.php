<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if (! $this->service->login($request->validated())) {
            Log::warning('Web login failed', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);

            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        Log::info('Web login successful', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => $request->ip(),
        ]);

        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }

    public function logout(Request $request)
    {
        Log::info('Web logout', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => $request->ip(),
        ]);

        $this->service->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
