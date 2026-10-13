<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFakultas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Opsional: Tambahkan logika khusus jika perlu
        // Misalnya: memblokir akses jika user belum memilih fakultas
        
        return $next($request);
    }
}