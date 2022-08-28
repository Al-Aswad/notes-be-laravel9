<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return ResponseFormatter::error('Unauthorized', null, 401);
        }

        $user = Auth::guard('api')->user();

        $data = [
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'expires' => Auth::guard('api')->factory()->getTTL() * 60,
            ]
        ];

        return ResponseFormatter::success($data, 'login success');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('api')->login($user);

        $data = [
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ];

        return ResponseFormatter::success($data, "User created successfully");
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return ResponseFormatter::success(null, 'Logout Successful');
    }

    public function refresh()
    {
        $data = [
            'user' => Auth::guard('api')->user(),
            'authorisation' => [
                'token' => Auth::guard('api')->refresh(),
                'type' => 'bearer',
            ]
        ];
        return ResponseFormatter::success($data, 'Token refreshed');
        // return response()->json([
        //     'status' => 'success',
        //     'user' => Auth::guard('api')->user(),
        //     'authorisation' => [
        //         'token' => Auth::guard('api')->refresh(),
        //         'type' => 'bearer',
        //     ]
        // ]);
    }
}
