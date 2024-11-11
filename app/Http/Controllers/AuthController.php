<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['registerUser', 'loginUser', 'loginAdmin']]);
    }

    function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => JsonResponse::HTTP_BAD_REQUEST,
                'errors' => [
                    $validator->errors()
                ]
            ], 400);
        }

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
                'message' => 'User successfully registered',
                'token' => $token,
            ], JsonResponse::HTTP_OK);
        }

        return response()->json([
            'status' => JsonResponse::HTTP_BAD_REQUEST,
            'message' => 'Something went wrong',
        ], JsonResponse::HTTP_OK);
    }


    public function loginUser(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $remember = $request->has('remember');
        if (! $token = auth()->attempt($credentials, $remember)) {
            return response()->json(['error' => 'Email hoặc mật khẩu không hợp lệ'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logoutUser()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $remember = $request->has('remember');
        if (! $token = auth()->attempt($credentials, $remember)) {
            return response()->json(['error' => 'Email hoặc mật khẩu không hợp lệ'], 401);
        }

        if (auth()->user()->role != 1) {
            return response()->json(['error' => 'Bạn không có quyền truy cập trang này'], 403);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
