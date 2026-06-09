<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSpecificCashier
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('toast_error', 'Akses ditolak. Anda belum login.');
        }

        if ($user->email !== 'kasir@gmail.com') {
            $fallback = $user->role === 'Admin' ? route('dashboard') : '/';

            return redirect($fallback)->with('toast_error', 'Halaman kasir hanya bisa diakses akun kasir@gmail.com.');
        }

        return $next($request);
    }
}
