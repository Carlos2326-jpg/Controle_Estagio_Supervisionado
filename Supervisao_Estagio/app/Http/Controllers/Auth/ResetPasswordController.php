<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                // Invalida todas as sessões ativas do usuário (session fixation prevention)
                // Requer que User implemente MustVerifyEmail ou use remember_token
                $user->setRememberToken(\Illuminate\Support\Str::random(60));
                $user->save();

                Log::info("Senha redefinida para o usuário {$user->id}.");
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Invalida a sessão atual antes de redirecionar
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
        }

        return back()->withErrors([
            'email' => 'Não foi possível redefinir a senha. O link pode ter expirado.',
        ]);
    }
}