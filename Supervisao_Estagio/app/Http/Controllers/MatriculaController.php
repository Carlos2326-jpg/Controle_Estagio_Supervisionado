<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aluno;
use Illuminate\Http\Request;

class MatriculaController
{
    /*
    |--------------------------------------------------------------------------
    | RF12 – LISTAR ALUNOS MATRICULADOS NO CURSO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, Curso $curso)
    {
        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->when(
                $request->situacao_estagio,
                fn($q) => $q->where('situacao_estagio', $request->situacao_estagio)
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('matriculas.index', compact('alunos', 'curso'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF13 – BUSCAR ALUNO POR MATRÍCULA OU CPF
    |--------------------------------------------------------------------------
    */

    public function buscar(Request $request, Curso $curso)
    {
        $request->validate([
            'busca' => 'required|string'
        ]);

        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->where(function ($q) use ($request) {
                $q->where('matricula', $request->busca)
                  ->orWhere('cpf', $request->busca);
            })
            ->paginate(20);

        return view('matriculas.index', compact('alunos', 'curso'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF14 – HISTÓRICO DE ESTÁGIOS DO ALUNO
    |--------------------------------------------------------------------------
    */

    public function historico(Curso $curso, Aluno $aluno)
    {
        if ($aluno->curso_id !== $curso->id) {
            abort(403, 'Aluno não pertence a este curso.');
        }

        $aluno->load([
            'user',
            'solicitacoesEstagio.empresa',
            'solicitacoesEstagio.contrato',
        ]);

        return view('matriculas.historico', compact('aluno', 'curso'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF15 – ALERTAR COORDENADOR SOBRE PRAZO DE CONCLUSÃO
    |--------------------------------------------------------------------------
    */

    public function alunosSemHoras(Curso $curso)
    {
        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->where('ativo', true)
            ->whereRaw(
                'carga_horaria_cumprida < ?',
                [$curso->carga_horaria_estagio]
            )
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('matriculas.alertas', compact('alunos', 'curso'));
    }
}