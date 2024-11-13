<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->role != 1)
        {
            $response = [
                'status' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Unauthorized'
                ],
            ];
            return response()->json($response,JsonResponse::HTTP_OK);
        }
        return $next($request);
    }
}
