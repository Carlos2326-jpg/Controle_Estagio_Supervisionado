<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Convenio;
use App\Models\Supervisor;
use App\Models\SolicitacaoEstagio;
use App\Services\EmpresaService;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    protected EmpresaService $service;

    public function __construct(EmpresaService $service)
    {
        $this->service = $service;
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Empresa::class);
        $empresas = $this->service->listar($request->only(['busca', 'status']));
        return response()->json($empresas);
    }

    public function store(StoreEmpresaRequest $request)
    {
        $this->authorize('create', Empresa::class);
        $empresa = $this->service->cadastrar($request->validated());
        return response()->json($empresa, 201);
    }

    public function show(Empresa $empresa)
    {
        $this->authorize('view', $empresa);
        $empresa = $this->service->consultar($empresa);
        return response()->json($empresa);
    }

    public function update(UpdateEmpresaRequest $request, Empresa $empresa)
    {
        $this->authorize('update', $empresa);
        $this->service->atualizar($empresa, $request->validated());
        return response()->json(['message' => 'Empresa atualizada com sucesso.']);
    }

    public function desativar(Empresa $empresa)
    {
        $this->authorize('update', $empresa);
        $this->service->desativar($empresa);
        return response()->json(['message' => 'Empresa desativada.']);
    }

    public function reativar(Empresa $empresa)
    {
        $this->authorize('update', $empresa);
        $this->service->reativar($empresa);
        return response()->json(['message' => 'Empresa reativada.']);
    }

    public function convenios(Request $request, Empresa $empresa)
    {
        $this->authorize('view', $empresa);
        $convenios = $this->service->listarConvenios($empresa, $request->only(['status']));
        return response()->json($convenios);
    }

    public function convenioStore(StoreConvenioRequest $request, Empresa $empresa)
    {
        $this->authorize('create', Convenio::class);
        $this->service->cadastrarConvenio($empresa, $request->validated());
        return response()->json(['message' => 'Convênio cadastrado com sucesso.'], 201);
    }

    public function convenioUpdate(UpdateConvenioRequest $request, Empresa $empresa, Convenio $convenio)
    {
        $this->authorize('update', $convenio);
        $this->service->atualizarConvenio($convenio, $request->validated());
        return response()->json(['message' => 'Convênio atualizado com sucesso.']);
    }

    public function supervisores(Request $request, Empresa $empresa)
    {
        $this->authorize('view', $empresa);
        $supervisores = $this->service->listarSupervisores($empresa, $request->only(['status']));
        return response()->json($supervisores);
    }

    public function supervisorStore(StoreSupervisorRequest $request, Empresa $empresa)
    {
        $this->authorize('create', Supervisor::class);
        $this->service->cadastrarSupervisor($empresa, $request->validated());
        return response()->json(['message' => 'Supervisor cadastrado com sucesso.'], 201);
    }

    public function supervisorUpdate(UpdateSupervisorRequest $request, Empresa $empresa, Supervisor $supervisor)
    {
        $this->authorize('update', $supervisor);
        $this->service->atualizarSupervisor($supervisor, $request->validated());
        return response()->json(['message' => 'Supervisor atualizado com sucesso.']);
    }

    public function supervisorDesativar(Empresa $empresa, Supervisor $supervisor)
    {
        $this->authorize('update', $supervisor);
        $this->service->desativarSupervisor($supervisor);
        return response()->json(['message' => 'Supervisor desativado.']);
    }

    public function solicitacoes(Request $request, Empresa $empresa)
    {
        $this->authorize('view', $empresa);
        $solicitacoes = $this->service->listarSolicitacoesRecebidas($empresa, $request->only(['status']));
        return response()->json($solicitacoes);
    }

    public function contrato(Empresa $empresa, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('view', $empresa);
        $contrato = $this->service->consultarContrato($solicitacao);
        return response()->json($contrato);
    }

    public function avaliacoes(Request $request, Empresa $empresa, Supervisor $supervisor)
    {
        $this->authorize('view', $supervisor);
        $avaliacoes = $this->service->listarAvaliacoes($supervisor);
        return response()->json($avaliacoes);
    }

    public function avaliacaoStore(StoreAvaliacaoSupervisorRequest $request, Empresa $empresa, Supervisor $supervisor, SolicitacaoEstagio $solicitacao)
    {
        $this->authorize('create', [AvaliacaoSupervisor::class, $supervisor]);
        $this->service->registrarAvaliacao($supervisor, $solicitacao, $request->validated());
        return response()->json(['message' => 'Avaliação registrada com sucesso.'], 201);
    }

    public function estagiarios(Request $request, Empresa $empresa)
    {
        $this->authorize('view', $empresa);
        $estagiarios = $this->service->listarEstagiarios($empresa, $request->only(['supervisor_id']));
        return response()->json($estagiarios);
    }
}