<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Aluno;
use App\Models\Coordenador;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:aluno,empresa',
            'matricula' => 'required_if:role,aluno|nullable|string|max:20',
            'cpf'       => 'required_if:role,aluno|nullable|string|size:11',
            'curso_id'  => 'required_if:role,aluno|nullable|exists:cursos,id,ativo,1',
            'razao_social' => 'required_if:role,empresa|nullable|string|max:255',
            'cnpj'      => 'required_if:role,empresa|nullable|string|size:14',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->role === 'aluno') {
                $user->assignRole('aluno');
                
                Aluno::create([
                    'user_id'   => $user->id,
                    'curso_id'  => $request->curso_id,
                    'matricula' => $request->matricula,
                    'cpf'       => $request->cpf,
                    'ativo'     => true,
                ]);
            } elseif ($request->role === 'empresa') {
                $user->assignRole('empresa');
                
                Empresa::create([
                    'user_id'      => $user->id,
                    'razao_social' => $request->razao_social,
                    'cnpj'         => $request->cnpj,
                    'email'        => $request->email,
                    'status'       => 'ativa',
                ]);
            }

            Auth::login($user);

            if ($request->role === 'aluno') {
                return redirect('/aluno/dashboard');
            } else {
                return redirect('/empresa/dashboard');
            }
        });
    }
}