<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        Log::channel('custom')->info('Entering Authenticate middleware', ['guards' => $guards]);

        if ($this->authenticate($request, $guards) === false) {
            Log::channel('custom')->info('Unauthenticated access attempt', ['url' => $request->url()]);
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        Log::channel('custom')->info('User authenticated', ['user_id' => $request->user()->id ?? 'guest']);

        return $next($request);
    }

    protected function authenticate(Request $request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                return true;
            }
        }

        return false;
    }
}
