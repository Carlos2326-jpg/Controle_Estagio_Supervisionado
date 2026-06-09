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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não autenticado. Faça login para continuar.',
                    'code' => 401
                ], 401);
            }
            return redirect('/login');
        }

        $user = Auth::user();

        // Verifica se o usuário tem alguma das roles permitidas
        foreach ($roles as $role) {
            // Verifica se o usuário tem a role
            $hasRole = false;
            
            if (method_exists($user, 'hasRole')) {
                $hasRole = $user->hasRole($role);
            } elseif (isset($user->role)) {
                $hasRole = $user->role === $role;
            }
            
            // Se encontrou a role, permite o acesso
            if ($hasRole) {
                return $next($request);
            }
        }

        // Se chegou aqui, o usuário não tem permissão
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado. Você não tem permissão para acessar este recurso.',
                'required_roles' => $roles,
                'user_role' => $user->role ?? null,
                'code' => 403
            ], 403);
        }

        abort(403, 'Acesso não autorizado. Você não tem permissão para acessar este recurso.');
    }
}