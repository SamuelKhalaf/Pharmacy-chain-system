<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * Handles user authentication (login, registration, and logout).
 */
class AuthController extends Controller
{
    /**
     * Login user and issue authentication token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'email' => 'required|email|exists:users,email|max:50',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'msg' => 'The credentials are incorrect',
            ], 401);
        }

        $token = $user->createToken('app_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'msg' => 'Logged in successfully',
            'data' => [
                'user' => $user,
                'auth-token' => $token,
            ],
        ]);
    }

    /**
     * Register a new user and issue authentication token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
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

        $token = $user->createToken('myapp_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    /**
     * Logout the authenticated user and revoke tokens.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
