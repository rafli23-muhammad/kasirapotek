<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Jika belum login
        if (!$user) {
            return redirect('/login')->with('toast_error', 'Akses ditolak. Anda belum login.');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn ($role) => preg_split('/[|,]/', (string) $role))
            ->map(fn ($r) => trim($r))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!in_array($user->role, $allowedRoles, true)) {
            $fallback = $user->role === 'Cashier' ? route('cashier') : '/';

            return redirect($fallback)->with('toast_error', 'Akses ditolak. Anda tidak memiliki akses ke halaman ini.');
        }

        // Jika lolos, lanjutkan request
        return $next($request);
    }
}
