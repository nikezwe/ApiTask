<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Connexion utilisateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Login successful',
                    'data'    => [
                        'user'  => $user,
                        'token' => $token,
                    ],
                    'errors'  => null,
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'data'    => null,
                'errors'  => null,
            ], 401);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'data'    => null,
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Enregistrement utilisateur
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data'    => $user,
                'errors'  => null,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'data'    => null,
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                // Supprimer uniquement le token courant
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
                'data'    => null,
                'errors'  => null,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'data'    => null,
                'errors'  => $th->getMessage(),
            ], 500);
        }
    }
}
