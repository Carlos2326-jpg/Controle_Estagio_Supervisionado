<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Aluno;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function __construct()
    {
        // Proteção garantida pelo grupo de rotas; autorização fina aqui
    }

    /*
    |--------------------------------------------------------------------------
    | RF12 – LISTAR ALUNOS MATRICULADOS NO CURSO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request, Curso $curso)
    {
        // Apenas admin e coordenador podem listar alunos matriculados
        $this->authorize('viewAny', Aluno::class);

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
        $this->authorize('viewAny', Aluno::class);

        $request->validate([
            'busca' => 'required|string|max:100',
        ]);

        // Usa validated() para evitar uso de $request->all()
        $busca = $request->input('busca');

        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->where(function ($q) use ($busca) {
                $q->where('matricula', $busca)
                  ->orWhere('cpf', $busca);
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
        $this->authorize('view', $aluno);

        // Verifica que o aluno pertence ao curso da rota
        if ($aluno->curso_id !== $curso->id) {
            abort(403, 'Este aluno não pertence ao curso informado.');
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
        $this->authorize('viewAny', Aluno::class);

        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->where('ativo', true)
            ->whereRaw('carga_horaria_cumprida < ?', [$curso->carga_horaria_estagio])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('matriculas.alertas', compact('alunos', 'curso'));
    }
}