<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cambia esta línea:
        // if (Auth::user()->rol !== $role) {
        
        // Por esto (con Spatie Permission):
        if (!Auth::user()->hasRole($role)) {
            abort(403, 'Acceso denegado.');
        }

        return $next($request);
    }
}