<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ANDREW FIX: Memberitahu VS Code / Intelephense bahwa variabel ini adalah model User
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. SUPERUSER BYPASS: Admin selalu bisa mengakses semua halaman!
        if ($user->hasRole('Admin')) {
            return $next($request);
        }

        // 2. Jika bukan Admin, cek apakah user punya salah satu dari role yang diminta
        if (!$user->hasAnyRole($roles)) {
            // Ubah format array role menjadi string (misal: "Supervisor, Operator")
            $roleNames = strtoupper(implode(' ATAU ', $roles));
            abort(403, 'UNAUTHORIZED. ANDA TIDAK MEMILIKI ROLE: ' . $roleNames);
        }

        return $next($request);
    }
}