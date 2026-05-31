<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AlunoService
{
    public function listar()
    {
        return Aluno::with('user')->paginate(20);
    }

    public function buscarPorUser(User $user)
    {
        return Aluno::where('user_id', $user->id)->with('user')->firstOrFail();
    }

    public function criar(array $dados)
    {
        return DB::transaction(function () use ($dados) {
            $user = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => $dados['password'],
                'perfil' => 'aluno',
            ]);

            return Aluno::create([
                'user_id' => $user->id,
                'matricula' => $dados['matricula'],
                'curso' => $dados['curso'],
                'periodo' => $dados['periodo'],
                'carga_horaria_obrigatoria' => $dados['carga_horaria_obrigatoria'],
            ]);
        });
    }

    public function atualizar(Aluno $aluno, array $dados)
    {
        return DB::transaction(function () use ($aluno, $dados) {
            $aluno->user->update(array_filter([
                'name' => $dados['name'] ?? null,
                'email' => $dados['email'] ?? null,
            ]));

            $aluno->update(array_filter([
                'matricula' => $dados['matricula'] ?? null,
                'curso' => $dados['curso'] ?? null,
                'periodo' => $dados['periodo'] ?? null,
                'carga_horaria_obrigatoria' => $dados['carga_horaria_obrigatoria'] ?? null,
            ]));

            return $aluno->fresh();
        });
    }
}
