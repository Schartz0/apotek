<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        // Pastikan user login dulu
        if (!Auth::check()) {
            // Bisa redirect ke login
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Pastikan role admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang bisa mengakses halaman ini.');
        }

        return $next($request);
    }
}
