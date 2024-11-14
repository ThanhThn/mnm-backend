<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            $token = explode(' ',$request->header('Authorization'));
        } catch (\Exception $e) {

            // Check token invalid
            if ($e instanceof TokenInvalidException){
                $response = [
                    'status' => JsonResponse::HTTP_UNAUTHORIZED,
                    'body' => [
                        'message' => 'Token is invalid',
                    ],
                ];
                return response()->json($response,JsonResponse::HTTP_OK);
            }

            // Check token expired
            if ($e instanceof TokenExpiredException){
                $response = [
                    'status' => JsonResponse::HTTP_UNAUTHORIZED,
                    'body' => [
                        'message' => 'Token is expired',
                    ],
                ];
                return response()->json($response,JsonResponse::HTTP_OK);
            }
            // Check token exist
            $response = [
                'status' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Unauthorized',
                ]];
            return response()->json($response,JsonResponse::HTTP_OK);
        }
        return $next($request);
    }
}
