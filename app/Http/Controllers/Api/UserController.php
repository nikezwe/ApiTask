<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function login(Request $request)
    {
        // return response()->json(['message' => $request->all()], 501);
        // Validate the request data
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        try {

            // Attempt to log the user in
            if (auth()->attempt($request->only('email', 'password'))) {

                $user =  auth()->user();
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Login successful',
                        'data' => [
                            'user' => $user,
                            'token' => $token
                        ],
                        'errors' => null,
                    ],
                    200
                );
            }
            return response()->json(['message' => 'Invalid credentials'], 401);
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Login successful',
                    'data' => null,
                    'errors' => $th->getMessage(),
                ],
                500
            );
        }
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        try {
            //code...
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User registered successfully',
                    'data' => $user,
                    'errors' => null
                ],
                201
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
                    'success' => false,
                    'message' => '',
                    'data' => null,
                    'errors' => $th->getMessage()
                ],
                500
            );
        }
    }
    public function logout(Request $request)
    {
        try {
            // Revoke the user's token
            auth()->user()->tokens()->delete();

            return response()->json(
                [
                    'success' => true,
                    'message' => 'Logout successful',
                    'data' => null,
                    'errors' => null
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Logout failed',
                    'data' => null,
                    'errors' => $th->getMessage()
                ],
                500
            );
        }
    }
}
