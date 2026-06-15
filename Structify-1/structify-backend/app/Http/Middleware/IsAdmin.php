<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array($request->user()->role, ['admin', 'developer'])) {
            return response()->json([
                'message' => 'Unauthorized. Admin only.'
            ], 403);
        }

        return $next($request);
    }
}