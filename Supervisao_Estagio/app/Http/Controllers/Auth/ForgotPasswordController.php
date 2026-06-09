<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Rate limiting: máximo 3 tentativas por e-mail por hora
        $key = 'forgot-password:' . $request->ip() . '|' . $request->input('email');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }
        RateLimiter::hit($key, 3600);

        // Sempre envia a mesma mensagem para evitar enumeração de e-mails
        Password::sendResetLink($request->only('email'));

        return back()->with([
            'status' => 'Se este e-mail estiver cadastrado, você receberá as instruções de recuperação.',
        ]);
    }
}