<?php

namespace App\Http\Controllers;
use App\Models\Instituicao;
use App\Http\Requests\StoreInstituicaoRequest;
use App\Services\InstituicaoService;
use Illuminate\Http\Request;

class InstituicaoController
{
    protected InstituicaoService $service;

    public function __construct(InstituicaoService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | RF38 – GERENCIAR INSTITUIÇÃO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $instituicoes = $this->service->listar(
            $request->only(['ativa', 'cidade', 'estado', 'busca'])
        );

        return view('instituicoes.index', compact('instituicoes'));
    }

    public function create()
    {
        return view('instituicoes.create');
    }

    public function store(StoreInstituicaoRequest $request)
    {
        $this->service->cadastrar($request->validated());

        return redirect()->route('instituicoes.index')
            ->with('success', 'Instituição cadastrada com sucesso.');
    }

    public function show(Instituicao $instituicao)
    {
        $detalhes = $this->service->detalhes($instituicao);

        return view('instituicoes.show', compact('detalhes', 'instituicao'));
    }

    public function edit(Instituicao $instituicao)
    {
        return view('instituicoes.edit', compact('instituicao'));
    }

    public function update(StoreInstituicaoRequest $request, Instituicao $instituicao)
    {
        $this->service->atualizar($instituicao, $request->validated());

        return redirect()->route('instituicoes.index')
            ->with('success', 'Instituição atualizada com sucesso.');
    }

    public function toggleAtiva(Instituicao $instituicao)
    {
        $this->service->toggleAtiva($instituicao);

        return redirect()->route('instituicoes.index')
            ->with('success', 'Status da instituição atualizado com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF39 – VINCULAR CURSOS
    |--------------------------------------------------------------------------
    */

    public function vincularCurso(Request $request, Instituicao $instituicao)
    {
        $request->validate(['curso_id' => 'required|exists:cursos,id']);
        $this->service->vincularCurso($instituicao, $request->input('curso_id'));

        return redirect()->back()->with('success', 'Curso vinculado com sucesso.');
    }

    public function desvincularCurso(Instituicao $instituicao, int $cursoId)
    {
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
        $request->validate(['coordenador_id' => 'required|exists:coordenadores,id']);
        $this->service->vincularCoordenador($instituicao, $request->input('coordenador_id'));

        return redirect()->back()->with('success', 'Coordenador vinculado com sucesso.');
    }

    public function desvincularCoordenador(Instituicao $instituicao, int $coordenadorId)
    {
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
        $cursos = $this->service->listarCursos($instituicao);

        return view('instituicoes.cursos', compact('instituicao', 'cursos'));
    }

    public function listarCoordenadores(Instituicao $instituicao)
    {
        $coordenadores = $this->service->listarCoordenadores($instituicao);

        return view('instituicoes.coordenadores', compact('instituicao', 'coordenadores'));
    }

    public function estruturaAcademica(Instituicao $instituicao)
    {
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
        $relatorio = $this->service->gerarRelatorio($instituicao);

        return view('instituicoes.relatorio', compact('instituicao', 'relatorio'));
    }

    public function exportar(Request $request, Instituicao $instituicao)
    {
        $request->validate(['formato' => 'required|in:csv,pdf']);

        return $this->service->exportar($instituicao, $request->input('formato'));
    }
}