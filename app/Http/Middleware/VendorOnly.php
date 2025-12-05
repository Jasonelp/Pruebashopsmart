<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VendorOnly
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, ['vendedor', 'admin'])) {
            return redirect('/')->with('error', 'Acceso solo para vendedores');
        }
        return $next($request);
    }
}