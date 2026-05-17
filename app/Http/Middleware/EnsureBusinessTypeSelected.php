<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessTypeSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->business_type) {
            // Allow access to select-business, logout routes
            if (!$request->is('select-business*') && !$request->is('logout')) {
                return redirect()->route('business.select');
            }
        }

        return $next($request);
    }
}
