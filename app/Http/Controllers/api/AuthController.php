<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email|max:50',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where(['email' => $fields['email']])->first();
        if (!$user ){
            return response()->json(['status' => 'false' , 'msg' => 'the credentials are incorrect']);
        }

        $token = $user->createToken('apptoken')->plainTextToken;

        return response()->json(['status' => 'true' , 'msg' => 'Logged in successfully' , 'data' => [
            'user'=> $user,
            'auth-token' => $token,
        ]]);
    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;


        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

}
