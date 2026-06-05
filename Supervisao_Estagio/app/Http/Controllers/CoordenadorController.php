<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\SolicitacaoEstagio;
use App\Models\Documento;
use App\Models\Avaliacao;
use App\Http\Requests\StoreCoordenadorRequest;
use App\Services\CoordenadorService;
use Illuminate\Http\Request;


class CoordenadorController extends Controller
{
    protected $service;

    public function __construct(CoordenadorService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | RF13 – GERENCIAR COORDENADORES
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $coordenadores = $this->service->listar(
            $request->only(['status', 'curso_id', 'busca'])
        );

        return view('coordenadores.index', compact('coordenadores'));
    }

    public function store(StoreCoordenadorRequest $request)
    {
        $coordenador = $this->service->cadastrar(
            $request->validated()
        );

        return redirect('/coordenadores')
            ->with('sucesso', 'Coordenador cadastrado com sucesso.');
    }

    public function create()
    {
        return view('coordenadores.create');
    }

    public function edit(Coordenador $coordenador)
    {
        return view('coordenadores.edit', compact('coordenador'));
    }

    public function inativar(Coordenador $coordenador)
    {
        $this->service->inativar($coordenador);
        return response()->json(['message' => 'Coordenador inativado com sucesso.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF14 – CONSULTAR INFORMAÇÕES ACADÊMICAS
    |--------------------------------------------------------------------------
    */

    public function informacoesAcademicas(Coordenador $coordenador)
    {
        return response()->json(
            $this->service->consultarInformacoesAcademicas($coordenador)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF15 e RF16 – ANALISAR SOLICITAÇÕES E REGISTRAR HISTÓRICO
    |--------------------------------------------------------------------------
    */

    public function listarSolicitacoes(Request $request, Coordenador $coordenador)
    {
        return response()->json(
            $this->service->listarSolicitacoes($coordenador, $request->only(['status', 'busca']))
        );
    }

    public function aprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $this->service->aprovarSolicitacao($coordenador, $solicitacao, $request->justificativa);
        return response()->json(['message' => 'Solicitação aprovada com sucesso.']);
    }

    public function reprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $request->validate(['justificativa' => 'required|string']);
        $this->service->reprovarSolicitacao($coordenador, $solicitacao, $request->justificativa);
        return response()->json(['message' => 'Solicitação reprovada com sucesso.']);
    }

    public function historicoAnalises(Request $request, Coordenador $coordenador)
    {
        return response()->json(
            $this->service->historicoAnalises($coordenador, $request->only(['decisao', 'data_inicio', 'data_fim']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF17 – VALIDAR DOCUMENTOS
    |--------------------------------------------------------------------------
    */

    public function listarDocumentos(Request $request, Coordenador $coordenador)
    {
        return response()->json(
            $this->service->listarDocumentos($coordenador, $request->only(['status', 'tipo']))
        );
    }

    public function aprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $this->service->aprovarDocumento($coordenador, $documento, $request->observacao);
        return response()->json(['message' => 'Documento aprovado com sucesso.']);
    }

    public function reprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $request->validate(['observacao' => 'required|string']);
        $this->service->reprovarDocumento($coordenador, $documento, $request->observacao);
        return response()->json(['message' => 'Documento reprovado com sucesso.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF18 – ACOMPANHAR ATIVIDADES DE ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function acompanharAtividades(Request $request, Coordenador $coordenador)
    {
        return response()->json(
            $this->service->acompanharAtividades($coordenador, $request->only(['aluno_id']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF19 – CONSULTAR PENDÊNCIAS
    |--------------------------------------------------------------------------
    */

    public function pendencias(Coordenador $coordenador)
    {
        return response()->json(
            $this->service->consultarPendencias($coordenador)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF20 – REALIZAR AVALIAÇÕES
    |--------------------------------------------------------------------------
    */

    public function listarAvaliacoes(Request $request, Coordenador $coordenador)
    {
        return response()->json(
            $this->service->listarAvaliacoes($coordenador, $request->only(['tipo', 'conceito']))
        );
    }

    public function registrarAvaliacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $request->validate([
            'tipo'            => 'required|in:parcial,final',
            'parecer'         => 'required|string',
            'nota'            => 'nullable|numeric|min:0|max:10',
            'conceito'        => 'nullable|in:otimo,bom,regular,insuficiente',
            'pontos_fortes'   => 'nullable|string',
            'pontos_melhoria' => 'nullable|string',
            'data_avaliacao'  => 'nullable|date',
        ]);

        return response()->json(
            $this->service->registrarAvaliacao($coordenador, $solicitacao, $request->all()),
            201
        );
    }

    public function atualizarAvaliacao(Request $request, Avaliacao $avaliacao)
    {
        return response()->json(
            $this->service->atualizarAvaliacao($avaliacao, $request->all())
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF21 – RECEBER ALERTAS
    |--------------------------------------------------------------------------
    */

    public function alertas(Coordenador $coordenador)
    {
        return response()->json(
            $this->service->alertas($coordenador)
        );
    }

    public function marcarAlertaLido(Request $request, Coordenador $coordenador)
    {
        $request->validate(['notification_id' => 'required|string']);
        $this->service->marcarAlertaLido($coordenador, $request->notification_id);
        return response()->json(['message' => 'Alerta marcado como lido.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF22 – GERAR RELATÓRIOS
    |--------------------------------------------------------------------------
    */

    public function gerarRelatorio(Request $request, Coordenador $coordenador)
    {
        $request->validate([
            'tipo' => 'required|in:alunos,contratos,horas,avaliacoes',
        ]);

        return response()->json(
            $this->service->gerarRelatorio($coordenador, $request->tipo, $request->except('tipo'))
        );
    }


    public function vincularCurso(Request $request, $id)
    {
        $this->service->vincularCurso(
            $id,
            $request->curso_id
        );

        return response()->json([
            'message' => 'Curso vinculado'
        ]);
    }
}