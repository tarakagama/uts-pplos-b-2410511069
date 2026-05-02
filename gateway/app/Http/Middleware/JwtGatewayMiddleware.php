<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtGatewayMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->hasHeader('Authorization')) {
            return response()->json([
                'message' => 'Unauthorized: No Token Provided (Gateway)'
            ], 401);
        }

        return $next($request);
    }
}