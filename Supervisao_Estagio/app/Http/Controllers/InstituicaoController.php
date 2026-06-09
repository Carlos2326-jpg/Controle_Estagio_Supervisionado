<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use App\Http\Requests\StoreInstituicaoRequest;
use App\Services\InstituicaoService;
use Illuminate\Http\Request;

class InstituicaoController extends Controller
{
    protected InstituicaoService $service;

    public function __construct(InstituicaoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $instituicoes = $this->service->listar(
            $request->only(['ativa', 'cidade', 'estado', 'busca'])
        );
        return response()->json($instituicoes);
    }

    public function store(StoreInstituicaoRequest $request)
    {
        $instituicao = $this->service->cadastrar($request->validated());
        return response()->json($instituicao, 201);
    }

    public function show(Instituicao $instituicao)
    {
        return response()->json($instituicao);
    }

    public function update(StoreInstituicaoRequest $request, Instituicao $instituicao)
    {
        $this->service->atualizar($instituicao, $request->validated());
        return response()->json(['message' => 'Instituição atualizada com sucesso.']);
    }

    public function toggleAtiva(Instituicao $instituicao)
    {
        $instituicao->update(['ativa' => !$instituicao->ativa]);

        return response()->json([
            'message' => 'Status da instituição atualizado com sucesso.',
            'ativa' => $instituicao->ativa
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RF39 – VINCULAR CURSOS
    |--------------------------------------------------------------------------
    */

    public function vincularCurso(Request $request, Instituicao $instituicao)
    {
        $this->authorize('update', $instituicao);
        $request->validate(['curso_id' => 'required|exists:cursos,id']);
        $this->service->vincularCurso($instituicao, $request->input('curso_id'));

        return redirect()->back()->with('success', 'Curso vinculado com sucesso.');
    }

    public function desvincularCurso(Instituicao $instituicao, int $cursoId)
    {
        $this->authorize('update', $instituicao);
        $this->service->desvincularCurso($instituicao, $cursoId);

        return redirect()->back()->with('success', 'Curso desvinculado com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF40 – VINCULAR COORDENADORES
    |--------------------------------------------------------------------------
    */

    public function vincularCoordenador(Request $request, Instituicao $instituicao)
    {
        $this->authorize('update', $instituicao);
        $request->validate(['coordenador_id' => 'required|exists:coordenadores,id']);
        $this->service->vincularCoordenador($instituicao, $request->input('coordenador_id'));

        return redirect()->back()->with('success', 'Coordenador vinculado com sucesso.');
    }

    public function desvincularCoordenador(Instituicao $instituicao, int $coordenadorId)
    {
        $this->authorize('update', $instituicao);
        $this->service->desvincularCoordenador($instituicao, $coordenadorId);

        return redirect()->back()->with('success', 'Coordenador desvinculado com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF41 – CONSULTAR ESTRUTURA ACADÊMICA
    |--------------------------------------------------------------------------
    */

    public function listarCursos(Instituicao $instituicao)
    {
        $this->authorize('view', $instituicao);
        $cursos = $this->service->listarCursos($instituicao);

        return view('instituicoes.cursos', compact('instituicao', 'cursos'));
    }

    public function listarCoordenadores(Instituicao $instituicao)
    {
        $this->authorize('view', $instituicao);
        $coordenadores = $this->service->listarCoordenadores($instituicao);

        return view('instituicoes.coordenadores', compact('instituicao', 'coordenadores'));
    }

    public function estruturaAcademica(Instituicao $instituicao)
    {
        $this->authorize('view', $instituicao);
        $estrutura = $this->service->estruturaAcademica($instituicao);

        return view('instituicoes.estrutura', compact('instituicao', 'estrutura'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF42 – RELATÓRIO INSTITUCIONAL
    |--------------------------------------------------------------------------
    */

    public function relatorio(Instituicao $instituicao)
    {
        $this->authorize('view', $instituicao);
        $relatorio = $this->service->gerarRelatorio($instituicao);

        return view('instituicoes.relatorio', compact('instituicao', 'relatorio'));
    }

    public function exportar(Request $request, Instituicao $instituicao)
    {
        $this->authorize('view', $instituicao);
        $request->validate(['formato' => 'required|in:csv,pdf']);

        return $this->service->exportar($instituicao, $request->input('formato'));
    }
}
