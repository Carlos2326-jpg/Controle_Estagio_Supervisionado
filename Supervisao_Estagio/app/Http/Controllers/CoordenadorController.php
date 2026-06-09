<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Models\SolicitacaoEstagio;
use App\Models\Documento;
use App\Models\Avaliacao;
use App\Http\Requests\StoreCoordenadorRequest;
use App\Http\Requests\UpdateCoordenadorRequest;
use App\Http\Requests\UpdateAvaliacaoRequest;
use App\Http\Requests\StoreAvaliacaoRequest;
use App\Services\CoordenadorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoordenadorController extends Controller
{
    protected CoordenadorService $service;

    public function __construct(CoordenadorService $service)
    {
        $this->service = $service;
    }

    /**
     * Listar coordenadores - Permitido para admin
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem listar coordenadores.');
        }

        $coordenadores = $this->service->listar($request->only(['status', 'curso_id', 'busca']));
        return response()->json($coordenadores);
    }

    /**
     * Criar coordenador - Permitido apenas para admin
     */
    public function store(StoreCoordenadorRequest $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem criar coordenadores.');
        }

        $coordenador = $this->service->cadastrar($request->validated());
        return response()->json($coordenador, 201);
    }

    /**
     * Inativar coordenador - Permitido apenas para admin
     */
    public function inativar(Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem inativar coordenadores.');
        }

        $this->service->inativar($coordenador);
        return response()->json(['message' => 'Coordenador inativado com sucesso.']);
    }

    /**
     * Informações acadêmicas - Permitido para admin e o próprio coordenador
     */
    public function informacoesAcademicas(Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->consultarInformacoesAcademicas($coordenador)
        );
    }

    /**
     * Listar solicitações - Permitido para admin e coordenador do curso
     */
    public function listarSolicitacoes(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->listarSolicitacoes($coordenador, $request->only(['status', 'busca']))
        );
    }

    /**
     * Histórico de análises - Permitido para admin e o próprio coordenador
     */
    public function historicoAnalises(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->historicoAnalises($coordenador, $request->only(['decisao', 'data_inicio', 'data_fim']))
        );
    }

    /**
     * Aprovar solicitação - Permitido para admin e coordenador do curso
     */
    public function aprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$solicitacao->isPendente()) {
            return response()->json(['message' => 'Apenas solicitações pendentes podem ser aprovadas.'], 422);
        }

        $request->validate(['justificativa' => 'nullable|string|max:1000']);

        $this->service->aprovarSolicitacao($coordenador, $solicitacao, $request->input('justificativa'));

        return response()->json(['message' => 'Solicitação aprovada com sucesso.']);
    }

    /**
     * Reprovar solicitação - Permitido para admin e coordenador do curso
     */
    public function reprovarSolicitacao(Request $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        if (!$solicitacao->isPendente()) {
            return response()->json(['message' => 'Apenas solicitações pendentes podem ser reprovadas.'], 422);
        }

        $request->validate(['justificativa' => 'required|string|min:10|max:1000']);

        $this->service->reprovarSolicitacao($coordenador, $solicitacao, $request->input('justificativa'));

        return response()->json(['message' => 'Solicitação reprovada com sucesso.']);
    }

    /**
     * Listar documentos - Permitido para admin e coordenador do curso
     */
    public function listarDocumentos(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->listarDocumentos($coordenador, $request->only(['status', 'tipo']))
        );
    }

    /**
     * Aprovar documento - Permitido para admin e coordenador do curso
     */
    public function aprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate(['observacao' => 'nullable|string|max:1000']);

        $this->service->aprovarDocumento($coordenador, $documento, $request->input('observacao'));

        return response()->json(['message' => 'Documento aprovado com sucesso.']);
    }

    /**
     * Reprovar documento - Permitido para admin e coordenador do curso
     */
    public function reprovarDocumento(Request $request, Coordenador $coordenador, Documento $documento)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate(['observacao' => 'required|string|min:10|max:1000']);

        $this->service->reprovarDocumento($coordenador, $documento, $request->input('observacao'));

        return response()->json(['message' => 'Documento reprovado com sucesso.']);
    }

    /**
     * Acompanhar atividades - Permitido para admin e coordenador do curso
     */
    public function acompanharAtividades(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->acompanharAtividades($coordenador, $request->only(['aluno_id']))
        );
    }

    /**
     * Pendências - Permitido para admin e coordenador do curso
     */
    public function pendencias(Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->consultarPendencias($coordenador)
        );
    }

    /**
     * Listar avaliações - Permitido para admin e coordenador do curso
     */
    public function listarAvaliacoes(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->listarAvaliacoes($coordenador, $request->only(['tipo', 'conceito']))
        );
    }

    /**
     * Registrar avaliação - Permitido para admin e coordenador do curso
     */
    public function registrarAvaliacao(StoreAvaliacaoRequest $request, Coordenador $coordenador, SolicitacaoEstagio $solicitacao)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->registrarAvaliacao($coordenador, $solicitacao, $request->validated()),
            201
        );
    }

    /**
     * Atualizar avaliação - Permitido para admin e coordenador que criou
     */
    public function atualizarAvaliacao(UpdateAvaliacaoRequest $request, Coordenador $coordenador, Avaliacao $avaliacao)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        if ($avaliacao->coordenador_id !== $coordenador->id) {
            abort(403, 'Esta avaliação não pertence a este coordenador.');
        }

        return response()->json(
            $this->service->atualizarAvaliacao($avaliacao, $request->validated())
        );
    }

    /**
     * Alertas - Permitido para admin e coordenador
     */
    public function alertas(Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        return response()->json(
            $this->service->alertas($coordenador)
        );
    }

    /**
     * Marcar alerta como lido - Permitido para admin e coordenador
     */
    public function marcarAlertaLido(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate(['notification_id' => 'required|string']);

        $notification = $coordenador->user->notifications()
            ->where('id', $request->notification_id)
            ->first();

        if (!$notification) {
            return response()->json(['message' => 'Notificação não encontrada.'], 404);
        }

        $this->service->marcarAlertaLido($coordenador, $request->notification_id);
        return response()->json(['message' => 'Alerta marcado como lido.']);
    }

    /**
     * Gerar relatório - Permitido para admin e coordenador
     */
    public function gerarRelatorio(Request $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->id !== $coordenador->user_id)) {
            abort(403, 'Acesso não autorizado.');
        }

        $request->validate(['tipo' => 'required|in:alunos,contratos,horas,avaliacoes']);

        return response()->json(
            $this->service->gerarRelatorio($coordenador, $request->input('tipo'), $request->except('tipo'))
        );
    }

    /**
     * Atualizar coordenador - Permitido apenas para admin
     */
    public function update(UpdateCoordenadorRequest $request, Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem editar coordenadores.');
        }

        $this->service->atualizar($coordenador, $request->validated());

        return response()->json(['message' => 'Coordenador atualizado com sucesso.']);
    }

    /**
     * Alternar status (ativar/inativar) - Permitido apenas para admin
     */
    public function toggleStatus(Coordenador $coordenador)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }

        $novoStatus = $coordenador->status === 'ativo' ? 'inativo' : 'ativo';
        $coordenador->update(['status' => $novoStatus]);

        return response()->json([
            'message' => "Coordenador {$novoStatus} com sucesso.",
            'status' => $novoStatus
        ]);
    }
}
