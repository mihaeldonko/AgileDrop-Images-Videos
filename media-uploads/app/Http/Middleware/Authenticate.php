<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $next($request);
    }

    protected function authenticate($request, array $guards)
    {
        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                auth()->shouldUse($guard);
                return;
            }
        }

        $this->unauthenticated($request, $guards);
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->expectsJson() || str_starts_with($request->path(), 'api/')) {
            abort(response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Please provide a valid Bearer token in the Authorization header.',
            ], 401));
        }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
