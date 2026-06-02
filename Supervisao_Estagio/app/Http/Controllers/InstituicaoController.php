<?php

namespace App\Http\Controllers;

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

    /*
    |--------------------------------------------------------------------------
    | RF38 – GERENCIAR INSTITUIÇÃO (Funções Básicas)
    |--------------------------------------------------------------------------
    */

    // RF38 – Listar / consultar instituições com filtros
    public function index(Request $request)
    {
        return response()->json(
            $this->service->listar(
                $request->only(['ativa', 'cidade', 'estado', 'busca'])
            )
        );
    }

    // RF38 – Cadastrar nova instituição
    public function store(StoreInstituicaoRequest $request)
    {
        return response()->json(
            $this->service->cadastrar($request->validated()),
            201
        );
    }

    // RF38 – Exibir ficha completa da instituição (Função de Saída)
    public function show(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->detalhes($instituicao)
        );
    }

    // RF38 – Atualizar dados cadastrais da instituição
    public function update(StoreInstituicaoRequest $request, Instituicao $instituicao)
    {
        return response()->json(
            $this->service->atualizar($instituicao, $request->validated())
        );
    }

    // RF38 – Ativar/desativar instituição (desativação lógica – RNF15)
    public function toggleAtiva(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->toggleAtiva($instituicao)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF39 – VINCULAR CURSOS (Funções Fundamentais)
    |--------------------------------------------------------------------------
    */

    // RF39 – Associar curso existente à instituição
    public function vincularCurso(Request $request, Instituicao $instituicao)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        return response()->json(
            $this->service->vincularCurso($instituicao, $request->input('curso_id'))
        );
    }

    // RF39 – Desvincular curso da instituição
    public function desvincularCurso(Instituicao $instituicao, int $cursoId)
    {
        return response()->json(
            $this->service->desvincularCurso($instituicao, $cursoId)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF40 – VINCULAR COORDENADORES (Funções Fundamentais)
    |--------------------------------------------------------------------------
    */

    // RF40 – Associar coordenador à instituição
    public function vincularCoordenador(Request $request, Instituicao $instituicao)
    {
        $request->validate([
            'coordenador_id' => 'required|exists:coordenadores,id',
        ]);

        return response()->json(
            $this->service->vincularCoordenador($instituicao, $request->input('coordenador_id'))
        );
    }

    // RF40 – Desvincular coordenador da instituição
    public function desvincularCoordenador(Instituicao $instituicao, int $coordenadorId)
    {
        return response()->json(
            $this->service->desvincularCoordenador($instituicao, $coordenadorId)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF41 – CONSULTAR ESTRUTURA ACADÊMICA (Função de Saída)
    |--------------------------------------------------------------------------
    */

    // RF41 – Listar todos os cursos da instituição
    public function listarCursos(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->listarCursos($instituicao)
        );
    }

    // RF41 – Listar todos os coordenadores da instituição
    public function listarCoordenadores(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->listarCoordenadores($instituicao)
        );
    }

    // RF41 – Visualizar estrutura acadêmica completa (cursos + coordenadores)
    public function estruturaAcademica(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->estruturaAcademica($instituicao)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF42 – EMITIR RELATÓRIO INSTITUCIONAL (Função de Saída)
    |--------------------------------------------------------------------------
    */

    // RF42 – Gerar relatório consolidado (dados + cursos + coord. + estágios)
    public function relatorio(Instituicao $instituicao)
    {
        return response()->json(
            $this->service->gerarRelatorio($instituicao)
        );
    }

    // RF42 – Exportar dados da instituição (CSV/PDF)
    public function exportar(Request $request, Instituicao $instituicao)
    {
        $request->validate([
            'formato' => 'required|in:csv,pdf',
        ]);

        return $this->service->exportar($instituicao, $request->input('formato'));
    }
}
