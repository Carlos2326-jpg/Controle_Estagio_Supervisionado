<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    /**
     * Mostrar o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processar o login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Verifica se o usuário tentou muitas vezes
        $key = 'login-attempts:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->regenerate();

            // Obtém o usuário autenticado
            $user = Auth::user();
            
            // Verifica se o usuário existe e tem role
            if ($user) {
                $role = $user->role;
                
                if ($role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                } elseif ($role === 'coordenador') {
                    return redirect()->intended('/coordenador/dashboard');
                } elseif ($role === 'aluno') {
                    return redirect()->intended('/aluno/dashboard');
                } elseif ($role === 'empresa') {
                    return redirect()->intended('/empresa/dashboard');
                }
            }

            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($key, 60);
        return back()->withErrors([
            'email' => 'As credenciais informadas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Realizar logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}