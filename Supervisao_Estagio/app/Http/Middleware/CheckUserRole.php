<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if (empty($roles)) {
            return $next($request);
        }
        
        if (in_array($user->perfil, $roles)) {
            return $next($request);
        }
        
        abort(403, 'Acesso não autorizado para este perfil.');
    }
}