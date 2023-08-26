<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->is_active == 0) {
            session()->flush();
            return redirect()->route('login')->with('error', 'Akun anda tidak aktif. Silahkan hubungi admin untuk proses aktivasi.');
        }
        return $next($request);
    }
}
