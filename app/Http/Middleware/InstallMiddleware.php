<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware for install routes
        if ($request->is('install*')) {
            return $next($request);
        }

        if ($request->is('requirements*')) {
            return $next($request);
        }

        if ($request->is('api/requirements*')) {
            return $next($request);
        }


        // Check if app is not installed
        if (!file_exists(storage_path('installed'))) {
            return redirect('/install');
        }

        return $next($request);
    }
}