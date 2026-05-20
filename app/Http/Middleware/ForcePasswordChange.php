<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->must_change_password) {
            // Ensure they aren't already on allowed pages to avoid infinite redirects
            if (! $request->routeIs('profile.edit') &&
                ! $request->routeIs('password.update') &&
                ! $request->routeIs('logout')) {
                return redirect()->route('profile.edit')->with('warning', 'Please update your temporary password to secure your account before continuing.');
            }
        }

        return $next($request);
    }
}
