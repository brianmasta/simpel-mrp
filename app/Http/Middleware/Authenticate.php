<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Redirect jika user belum login.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
        return null;
    }


    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if (
            Auth::check() &&
            Auth::user()->must_change_password &&
            !$request->routeIs('ganti-password')
        ) {
            return redirect()->route('ganti-password');
        }

        return $next($request);
    }
}
