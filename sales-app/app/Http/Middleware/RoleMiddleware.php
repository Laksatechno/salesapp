<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle($request, Closure $next, ...$roles)
    // {
    //     if (!in_array(auth()->user()->role, $roles)) {
    //         return redirect('/')->with('error', 'Access Denied');
    //     }
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (!in_array($userRole, $roles)) {
            // Simpan URL sebelumnya ke dalam session
            session()->put('previousUrl', url()->previous());

            // Redirect ke halaman sebelumnya
            return redirect()->back()->with('error', 'Unauthorized action.');
        }


        return $next($request);
    }
    
}
