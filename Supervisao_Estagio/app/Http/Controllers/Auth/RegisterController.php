<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Mostrar formulário de registro - DESABILITADO
     * Cadastro de usuários só pode ser feito por ADMIN
     */
    public function showRegistrationForm()
    {
        // Redireciona para login com mensagem
        return redirect('/login')->withErrors([
            'email' => 'Cadastro de novos usuários só pode ser feito pelo administrador.',
        ]);
    }

    /**
     * Registrar novo usuário - DESABILITADO
     */
    public function register(Request $request)
    {
        return redirect('/login')->withErrors([
            'email' => 'Cadastro de novos usuários só pode ser feito pelo administrador.',
        ]);
    }
}