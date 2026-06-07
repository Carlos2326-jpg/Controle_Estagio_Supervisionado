<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\SolicitacaoEstagio;
use App\Models\Documento;
use App\Models\Avaliacao;
use App\Http\Requests\StoreCoordenadorRequest;
use App\Http\Requests\UpdateCoordenadorRequest;
use App\Http\Requests\UpdateAvaliacaoRequest;
use App\Services\CoordenadorService;
use Illuminate\Http\Request;
use App\Models\Instituicao;

class CoordenadorController extends Controller
{
    protected $service;

    public function __construct(CoordenadorService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Coordenador::class);
        $coordenadores = $this->service->listar(
            $request->only(['status', 'curso_id', 'busca'])
        );

        return response()->json($coordenadores);
    }

    public function store(StoreCoordenadorRequest $request)
    {
        $this->authorize('create', Coordenador::class);
        $coordenador = $this->service->cadastrar($request->validated());

        return response()->json($coordenador, 201);
    }

    public function inativar(Coordenador $coordenador)
    {
        $this->authorize('update', $coordenador);
        $this->service->inativar($coordenador);
        return response()->json(['message' => 'Coordenador inativado com sucesso.']);
    }

    public function informacoesAcademicas(Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->consultarInformacoesAcademicas($coordenador)
        );
    }

    public function listarSolicitacoes(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->listarSolicitacoes($coordenador, $request->only(['status', 'busca']))
        );
    }

    public function aprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('aprovar', [$solicitacao, $coordenador]);
        $this->service->aprovarSolicitacao($coordenador, $solicitacao, $request->justificativa);
        return response()->json(['message' => 'Solicitação aprovada com sucesso.']);
    }

    public function reprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('reprovar', [$solicitacao, $coordenador]);
        $request->validate(['justificativa' => 'required|string']);
        $this->service->reprovarSolicitacao($coordenador, $solicitacao, $request->justificativa);
        return response()->json(['message' => 'Solicitação reprovada com sucesso.']);
    }

    public function historicoAnalises(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->historicoAnalises($coordenador, $request->only(['decisao', 'data_inicio', 'data_fim']))
        );
    }

    public function listarDocumentos(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->listarDocumentos($coordenador, $request->only(['status', 'tipo']))
        );
    }

    public function aprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $this->authorize('aprovar', [$documento, $coordenador]);
        $this->service->aprovarDocumento($coordenador, $documento, $request->observacao);
        return response()->json(['message' => 'Documento aprovado com sucesso.']);
    }

    public function reprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $this->authorize('reprovar', [$documento, $coordenador]);
        $request->validate(['observacao' => 'required|string']);
        $this->service->reprovarDocumento($coordenador, $documento, $request->observacao);
        return response()->json(['message' => 'Documento reprovado com sucesso.']);
    }

    public function acompanharAtividades(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->acompanharAtividades($coordenador, $request->only(['aluno_id']))
        );
    }

    public function pendencias(Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->consultarPendencias($coordenador)
        );
    }

    public function listarAvaliacoes(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->listarAvaliacoes($coordenador, $request->only(['tipo', 'conceito']))
        );
    }

    public function registrarAvaliacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('create', [Avaliacao::class, $coordenador, $solicitacao]);
        
        $request->validate([
            'tipo'            => 'required|in:parcial,final',
            'parecer'         => 'required|string',
            'nota'            => 'nullable|numeric|min:0|max:10|required_without:conceito',
            'conceito'        => 'nullable|in:otimo,bom,regular,insuficiente|required_without:nota',
            'pontos_fortes'   => 'nullable|string',
            'pontos_melhoria' => 'nullable|string',
            'data_avaliacao'  => 'nullable|date',
        ]);

        return response()->json(
            $this->service->registrarAvaliacao($coordenador, $solicitacao, $request->all()),
            201
        );
    }

    public function atualizarAvaliacao(UpdateAvaliacaoRequest $request, Coordenador $coordenador, Avaliacao $avaliacao)
    {
        $this->authorize('update', [$avaliacao, $coordenador]);
        return response()->json(
            $this->service->atualizarAvaliacao($avaliacao, $request->validated())
        );
    }

    public function alertas(Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        return response()->json(
            $this->service->alertas($coordenador)
        );
    }

    public function marcarAlertaLido(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        $request->validate(['notification_id' => 'required|string']);
        
        $notification = $coordenador->user->notifications()->where('id', $request->notification_id)->first();
        if (!$notification) {
            return response()->json(['message' => 'Notificação não encontrada.'], 404);
        }
        
        $this->service->marcarAlertaLido($coordenador, $request->notification_id);
        return response()->json(['message' => 'Alerta marcado como lido.']);
    }

    public function gerarRelatorio(Request $request, Coordenador $coordenador)
    {
        $this->authorize('view', $coordenador);
        $request->validate([
            'tipo' => 'required|in:alunos,contratos,horas,avaliacoes',
        ]);

        return response()->json(
            $this->service->gerarRelatorio($coordenador, $request->tipo, $request->except('tipo'))
        );
    }
}