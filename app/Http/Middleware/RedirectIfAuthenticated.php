<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {

            if (auth()->user()->hasRole('admin')) {
                return redirect('/admin/dashboard');
            }

            if (auth()->user()->hasRole('kepala_divisi')) {
                return redirect('/kadiv/dashboard');
            }

            if (auth()->user()->hasRole('mahasiswa')) {
                return redirect('/mahasiswa/dashboard');
            }

            if (auth()->user()->hasRole('pembimbing')) {
                return redirect('/pembimbing/dashboard');
            }

            return redirect('/dashboard');
        }

        return $next($request);
    }
}
