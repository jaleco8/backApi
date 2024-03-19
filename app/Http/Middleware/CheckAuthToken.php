<?php

namespace App\Http\Middleware;

use Closure;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Verificar si el token de autenticación está presente en la solicitud
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'No token provided'], 401);
        }

        // Verificar si el token de autenticación es válido
        if (!$request->user('api')) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
