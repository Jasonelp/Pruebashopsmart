<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth; // <-- Esta línea es clave

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        // 'role' debe valer 'admin' en el usuario logueado
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Redirige a Home si NO es admin
            return redirect('/')->with('error', '¡No tienes acceso a esta sección!');
        }
        return $next($request);
    }
}
