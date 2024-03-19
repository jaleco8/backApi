<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Verificar si el usuario está activo
            if (!$user->active) {
                Auth::logout(); // Cerrar sesión
                return response()->json(['error' => 'Unauthorized. User is inactive'], 401);
            }

            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Refresh a token.
     */
    public function refresh(Request $request)
    {
        $request->user()->token()->revoke();

        $newToken = $request->user()->createToken('Personal Access Token')->accessToken;

        return response()->json([
            'token' => $newToken
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
