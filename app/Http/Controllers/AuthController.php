<?php

namespace App\Http\Controllers;

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
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'user' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|min:6',
            'password' => 'required|string|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'message' => "User created successfully",
            'user' => $user
        ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => "Logout successfully"]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authoirization' => [
                'token' > Auth::refresh(),
                'type' => 'bearar',
            ]
        ]);

    }

    public function test()
    {
        return response()->json([
            'message' => 'Test is okay'
        ]);
    }

    public function redirectTo()
    {

    }
}