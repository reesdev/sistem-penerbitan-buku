<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
        public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika belum login, ATAU pangkatnya (role) tidak ada di daftar yang diizinkan
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json([
                'message' => 'Akses Ditolak! Anda tidak memiliki izin (Forbidden).'
            ], 403);
        }

        // Jika aman, silakan masuk
        return $next($request);
    }
}
