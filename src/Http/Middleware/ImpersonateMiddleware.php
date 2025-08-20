<?php

namespace TCG\Voyager\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ImpersonateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is impersonating another user
        if (session()->has('impersonate')) {
            $impersonatedUserId = session()->get('impersonate');
            Auth::onceUsingId($impersonatedUserId);
        }

        // Proceed with the request
        return $next($request);
    }
}
