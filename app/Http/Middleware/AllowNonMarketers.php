<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowNonMarketers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['status' => false, 'error' => 'Unauthorized'], 401);
        }

        if (!in_array(Auth::user()->type_id, [1, 2, 3])) {
            return response()->json(['status' => false, 'error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
