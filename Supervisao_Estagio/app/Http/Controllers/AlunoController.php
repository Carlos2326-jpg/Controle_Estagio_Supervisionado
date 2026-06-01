<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AtividadeEstagio;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\SolicitacaoEstagio;
use App\Http\Requests\StoreAlunoRequest;
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
    }

    /*
    |--------------------------------------------------------------------------
    | RF01 – GERENCIAR DADOS DO ALUNO
    |--------------------------------------------------------------------------
    */

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
        return response()->json(
            $aluno->load(['user', 'curso'])
        );
    }

    public function update(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->atualizar($aluno, $request->all())
        );
    }

    public function inativar(Aluno $aluno)
    {
        $this->service->inativar($aluno);
        return response()->json(['message' => 'Aluno inativado com sucesso.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF02 – CONSULTAR SITUAÇÃO DE ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function situacaoEstagio(Aluno $aluno)
    {
        return response()->json(
            $this->service->consultarSituacao($aluno)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF03 – SOLICITAR ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function solicitarEstagio(StoreSolicitacaoEstagioRequest $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->solicitarEstagio($aluno, $request->validated()),
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF04 – CONSULTAR SOLICITAÇÕES
    |--------------------------------------------------------------------------
    */

    public function listarSolicitacoes(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->listarSolicitacoes($aluno, $request->only(['status']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF05 – CANCELAR SOLICITAÇÃO
    |--------------------------------------------------------------------------
    */

    public function cancelarSolicitacao(Aluno $aluno, SolicitacaoEstagio $solicitacao)
    {
        $this->service->cancelarSolicitacao($aluno, $solicitacao);
        return response()->json(['message' => 'Solicitação cancelada com sucesso.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF06 – VISUALIZAR CONTRATO DE ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function listarContratos(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->listarContratos($aluno, $request->only(['status']))
        );
    }

    public function visualizarContrato(Aluno $aluno, Contrato $contrato)
    {
        return response()->json(
            $this->service->visualizarContrato($aluno, $contrato)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF07 – REGISTRAR ATIVIDADES DE ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function listarAtividades(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->listarAtividades($aluno, $request->only([
                'solicitacao_id', 'validado', 'data_inicio', 'data_fim',
            ]))
        );
    }

    public function registrarAtividade(StoreAtividadeEstagioRequest $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->registrarAtividade($aluno, $request->validated()),
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF08 – EDITAR REGISTROS DE ATIVIDADES
    |--------------------------------------------------------------------------
    */

    public function atualizarAtividade(Request $request, Aluno $aluno, AtividadeEstagio $atividade)
    {
        return response()->json(
            $this->service->atualizarAtividade($aluno, $atividade, $request->all())
        );
    }

    public function excluirAtividade(Aluno $aluno, AtividadeEstagio $atividade)
    {
        $this->service->excluirAtividade($aluno, $atividade);
        return response()->json(['message' => 'Registro de atividade excluído com sucesso.']);
    }

    /*
    |--------------------------------------------------------------------------
    | RF09 – ENVIAR DOCUMENTOS
    |--------------------------------------------------------------------------
    */

    public function enviarDocumento(StoreDocumentoRequest $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->enviarDocumento($aluno, $request->validated()),
            201
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF10 – CONSULTAR STATUS DE DOCUMENTOS
    |--------------------------------------------------------------------------
    */

    public function listarDocumentos(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->listarDocumentos($aluno, $request->only(['status', 'tipo']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF11 – CONSULTAR AVALIAÇÕES
    |--------------------------------------------------------------------------
    */

    public function listarAvaliacoes(Request $request, Aluno $aluno)
    {
        return response()->json(
            $this->service->listarAvaliacoes($aluno, $request->only(['tipo', 'conceito']))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RF12 – RECEBER ALERTAS
    |--------------------------------------------------------------------------
    */

    public function alertas(Aluno $aluno)
    {
        return response()->json(
            $this->service->listarAlertas($aluno)
        );
    }

    public function marcarAlertaLido(Request $request, Aluno $aluno)
    {
        $request->validate(['notification_id' => 'required|string']);
        $this->service->marcarAlertaLido($aluno, $request->notification_id);
        return response()->json(['message' => 'Alerta marcado como lido.']);
    }

    public function marcarTodosAlertasLidos(Aluno $aluno)
    {
        $this->service->marcarTodosAlertasLidos($aluno);
        return response()->json(['message' => 'Todos os alertas marcados como lidos.']);
    }
}
