<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckClientBlocked
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('client')->check() && Auth::guard('client')->user()->IsBlocked) {
            Auth::guard('client')->logout();
            return redirect()->route('login')->with('error', 'Ваш аккаунт заблокирован');
        }

        return $next($request);
    }
}