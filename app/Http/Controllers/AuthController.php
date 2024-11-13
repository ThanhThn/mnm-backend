<?php

namespace App\Http\Controllers;

use App\Http\Request\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function registerUser(RegisterRequest $request)
    {

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 0
        ]);

        if ($user) {
            $token = JWTAuth::attempt(['email' => $email, 'password' => $password]);
            return response()->json([
                'status' => JsonResponse::HTTP_CREATED,
                'body' => [
                    'message' => 'User successfully registered',
                    'token' => $token,
                ]
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => 'Something went wrong',
        ], JsonResponse::HTTP_OK);
    }


    public function loginUser(Request $request)
    {
        $token = JWTAuth::attempt($request->only(['email', 'password']));
        if (!$token) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Unauthorized'
                ]], JsonResponse::HTTP_OK);
        }

        return $this->respondWithToken($token);
    }

    public function loginAdmin(Request $request)
    {
        $token = JWTAuth::attempt($request->only(['email', 'password']));
        if (!$token) {
            return response()->json([
                'status' => JsonResponse::HTTP_UNAUTHORIZED,
                'body' => [
                    'message' => 'Unauthorized'
                ]], JsonResponse::HTTP_OK);
        }

        if (Auth::user()->role != 1) {
            return response()->json([
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'body' => [
                    'message' => 'Unauthorized'
                ]], JsonResponse::HTTP_OK);
        }

        return $this->respondWithToken($token);
    }


    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'user' => $user
            ]
        ], JsonResponse::HTTP_OK);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'body' => [
                'token' => $token,
            ]
        ], JsonResponse::HTTP_OK);
    }
}
