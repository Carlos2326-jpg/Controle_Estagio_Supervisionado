<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AtividadeEstagio;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\SolicitacaoEstagio;
use App\Http\Requests\StoreAlunoRequest;
use App\Http\Requests\UpdateAlunoRequest;
use App\Http\Requests\StoreSolicitacaoEstagioRequest;
use App\Http\Requests\StoreAtividadeEstagioRequest;
use App\Http\Requests\StoreDocumentoRequest;
use App\Services\AlunoService;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    protected AlunoService $service;

    public function __construct(AlunoService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum');
        $this->authorizeResource(Aluno::class, 'aluno');
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->service->listar($request->only(['curso_id', 'situacao', 'ativo', 'busca']))
        );
    }

    public function store(StoreAlunoRequest $request)
    {
        return response()->json(
            $this->service->cadastrar($request->validated()),
            201
        );
    }

    public function show(Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $aluno->load(['user', 'curso'])
        );
    }

    public function update(UpdateAlunoRequest $request, Aluno $aluno)
    {
        $this->authorize('update', $aluno);
        return response()->json(
            $this->service->atualizar($aluno, $request->validated())
        );
    }

    public function inativar(Aluno $aluno)
    {
        $this->authorize('inativar', $aluno);
        $this->service->inativar($aluno);
        return response()->json(['message' => 'Aluno inativado com sucesso.']);
    }

    public function situacaoEstagio(Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->consultarSituacao($aluno)
        );
    }

    public function solicitarEstagio(StoreSolicitacaoEstagioRequest $request, Aluno $aluno)
    {
        $this->authorize('solicitarEstagio', $aluno);
        return response()->json(
            $this->service->solicitarEstagio($aluno, $request->validated()),
            201
        );
    }

    public function listarSolicitacoes(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarSolicitacoes($aluno, $request->only(['status']))
        );
    }

    public function cancelarSolicitacao(Aluno $aluno, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('cancelar', $solicitacao);
        $this->service->cancelarSolicitacao($aluno, $solicitacao);
        return response()->json(['message' => 'Solicitação cancelada com sucesso.']);
    }

    public function listarContratos(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarContratos($aluno, $request->only(['status']))
        );
    }

    public function visualizarContrato(Aluno $aluno, Contrato $contrato)
    {
        $this->authorize('view', $contrato);
        return response()->json(
            $this->service->visualizarContrato($aluno, $contrato)
        );
    }

    public function listarAtividades(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarAtividades($aluno, $request->only([
                'solicitacao_id', 'validado', 'data_inicio', 'data_fim',
            ]))
        );
    }

    public function registrarAtividade(StoreAtividadeEstagioRequest $request, Aluno $aluno)
    {
        $this->authorize('create', AtividadeEstagio::class);
        return response()->json(
            $this->service->registrarAtividade($aluno, $request->validated()),
            201
        );
    }

    public function atualizarAtividade(UpdateAtividadeEstagioRequest $request, Aluno $aluno, AtividadeEstagio $atividade)
    {
        $this->authorize('update', $atividade);
        return response()->json(
            $this->service->atualizarAtividade($aluno, $atividade, $request->validated())
        );
    }

    public function excluirAtividade(Aluno $aluno, AtividadeEstagio $atividade)
    {
        $this->authorize('delete', $atividade);
        $this->service->excluirAtividade($aluno, $atividade);
        return response()->json(['message' => 'Registro de atividade excluído com sucesso.']);
    }

    public function enviarDocumento(StoreDocumentoRequest $request, Aluno $aluno)
    {
        $this->authorize('create', Documento::class);
        return response()->json(
            $this->service->enviarDocumento($aluno, $request->validated()),
            201
        );
    }

    public function listarDocumentos(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarDocumentos($aluno, $request->only(['status', 'tipo']))
        );
    }

    public function listarAvaliacoes(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarAvaliacoes($aluno, $request->only(['tipo', 'conceito']))
        );
    }

    public function alertas(Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        return response()->json(
            $this->service->listarAlertas($aluno)
        );
    }

    public function marcarAlertaLido(Request $request, Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        $request->validate(['notification_id' => 'required|string']);
        
        $notification = $aluno->user->notifications()->where('id', $request->notification_id)->first();
        if (!$notification) {
            return response()->json(['message' => 'Notificação não encontrada.'], 404);
        }
        
        $this->service->marcarAlertaLido($aluno, $request->notification_id);
        return response()->json(['message' => 'Alerta marcado como lido.']);
    }

    public function marcarTodosAlertasLidos(Aluno $aluno)
    {
        $this->authorize('view', $aluno);
        $this->service->marcarTodosAlertasLidos($aluno);
        return response()->json(['message' => 'Todos os alertas marcados como lidos.']);
    }
}