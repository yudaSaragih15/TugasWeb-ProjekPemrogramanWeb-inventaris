<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class IsSuperAdmin {
    public function handle(Request $request, Closure $next) {
        if (!auth()->check() || auth()->user()->role !== 'super_admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
        }
        return $next($request);
    }
}