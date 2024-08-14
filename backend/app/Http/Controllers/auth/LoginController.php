<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            if ($remember) {
                $rememberToken = Str::random(60);
                cookie()->queue(cookie('remember_token', $rememberToken, 60 * 24 * 30)); // 30 days
                $user->remember_token = $rememberToken;
                $user->save();
            }

            return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        // Clear the remember me cookie
        cookie()->queue(cookie('remember_token', '', -1)); // Remove cookie

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function checkRememberMe(Request $request)
    {
        $token = $request->cookie('remember_token');

        if ($token) {
            $user = User::where('remember_token', $token)->first();

            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}
