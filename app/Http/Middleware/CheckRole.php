<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<string>|string  $roles  Role atau array of roles yang diizinkan
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (empty($roles) || in_array($request->user()->role, $roles, true)) {
            return $next($request);
        }

        return redirect()->route('dashboard')
            ->with('error', 'Akses tidak diizinkan. Anda tidak memiliki izin untuk halaman tersebut.');
    }
}
