<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $type)
{
    $user = Auth::guard('usuarios')->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ((string) $user->user_tipo !== (string) $type) {
        abort(403); // ⛔ sin loop
    }

    return $next($request);
}
}
