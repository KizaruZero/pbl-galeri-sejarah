<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;




class DualAuth
{
    public function handle($request, Closure $next)
    {
        // Cek autentikasi session
        if (Auth::check()) {
            return $next($request);
        }

        // Jika tidak ada session, coba autentikasi token
        if ($request->bearerToken()) {
            // Gunakan sanctum untuk autentikasi
            $user = Auth::guard('sanctum')->user();
            if ($user) {
                return $next($request);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}