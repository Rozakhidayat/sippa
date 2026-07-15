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
     *
     * @param  Closure(Request): (Response)  $next
     * @param int ...$roles 
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            abort(403);
        }

        if (! in_array((string) Auth::user()->role?->name, $roles)) {
            abort(403, 'Akses ditolak! Anda tidak memiliki wewenang membuka halaman ini.');
        }
        
        return $next($request);
    }
}
