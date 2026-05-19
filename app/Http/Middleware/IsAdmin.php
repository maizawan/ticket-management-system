<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 🔐 check login
        if (!$user) {
            return redirect('/login');
        }

        // 🔐 check role exists + admin
        if (!$user->role || $user->role !== 'admin') {
            abort(403, 'Unauthorized Access - Admin Only');
        }

        return $next($request);
    }
}