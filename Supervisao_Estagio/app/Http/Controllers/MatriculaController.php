<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aluno;
use Illuminate\Http\Request;

class MatriculaController extends Controller
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
            ->when($request->situacao_estagio, fn($q) => $q->where('situacao_estagio', $request->situacao_estagio))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($alunos);
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

        $aluno = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->where(function ($q) use ($request) {
                $q->where('matricula', $request->busca)
                  ->orWhere('cpf', $request->busca);
            })
            ->first();

        if (!$aluno) {
            return response()->json(['message' => 'Aluno não encontrado.'], 404);
        }

        return response()->json($aluno);
    }

    /*
    |--------------------------------------------------------------------------
    | RF14 – HISTÓRICO DE ESTÁGIOS DO ALUNO
    |--------------------------------------------------------------------------
    */

    public function historico(Curso $curso, Aluno $aluno)
    {
        if ($aluno->curso_id !== $curso->id) {
            return response()->json(['message' => 'Aluno não pertence a este curso.'], 403);
        }

        $historico = $aluno->load([
            'solicitacoesEstagio.empresa',
            'solicitacoesEstagio.contrato',
        ]);

        return response()->json($historico);
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
            ->whereRaw('carga_horaria_cumprida < ?', [$curso->carga_horaria_estagio])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return response()->json($alunos);
    }
}