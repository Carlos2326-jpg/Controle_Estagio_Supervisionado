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
    }

    /*
    |--------------------------------------------------------------------------
    | RF24 – GERENCIAR EMPRESAS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $empresas = $this->service->listar($request->only(['busca', 'status']));
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'razao_social' => 'required|string|max:255',
            'cnpj'         => 'required|string|size:18|unique:empresas,cnpj',
            'email'        => 'required|email|max:255',
            'telefone'     => 'nullable|string|max:20',
            'cep'          => 'nullable|string|max:9',
            'logradouro'   => 'nullable|string|max:255',
            'numero'       => 'nullable|string|max:10',
            'cidade'       => 'nullable|string|max:100',
            'estado'       => 'nullable|string|size:2',
        ]);

        $empresa = $this->service->cadastrar($request->all());

        return redirect()
            ->route('empresas.show', $empresa)
            ->with('sucesso', 'Empresa cadastrada com sucesso.');
    }

    public function show(Empresa $empresa)
    {
        $empresa = $this->service->consultar($empresa);
        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa)
    {
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'razao_social' => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'cnpj'         => "required|string|size:18|unique:empresas,cnpj,{$empresa->id}",
        ]);

        $this->service->atualizar($empresa, $request->all());

        return redirect()
            ->route('empresas.show', $empresa)
            ->with('sucesso', 'Empresa atualizada com sucesso.');
    }

    public function desativar(Empresa $empresa)
    {
        $this->service->desativar($empresa);
        return redirect()->route('empresas.index')->with('sucesso', 'Empresa desativada.');
    }

    public function reativar(Empresa $empresa)
    {
        $this->service->reativar($empresa);
        return redirect()->route('empresas.show', $empresa)->with('sucesso', 'Empresa reativada.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF25 – GERENCIAR CONVÊNIOS
    |--------------------------------------------------------------------------
    */

    public function convenios(Request $request, Empresa $empresa)
    {
        $convenios = $this->service->listarConvenios($empresa, $request->only(['status']));
        return view('convenios.index', compact('empresa', 'convenios'));
    }

    public function convenioCreate(Empresa $empresa)
    {
        return view('convenios.create', compact('empresa'));
    }

    public function convenioStore(Request $request, Empresa $empresa)
    {
        $request->validate([
            'numero_convenio' => 'required|string|unique:convenios,numero_convenio',
            'data_inicio'     => 'required|date',
            'data_fim'        => 'required|date|after:data_inicio',
            'observacoes'     => 'nullable|string',
        ]);

        $this->service->cadastrarConvenio($empresa, $request->all());

        return redirect()
            ->route('empresas.convenios', $empresa)
            ->with('sucesso', 'Convênio cadastrado com sucesso.');
    }

    public function convenioEdit(Empresa $empresa, Convenio $convenio)
    {
        return view('convenios.edit', compact('empresa', 'convenio'));
    }

    public function convenioUpdate(Request $request, Empresa $empresa, Convenio $convenio)
    {
        $request->validate([
            'data_fim'   => 'required|date|after:data_inicio',
            'status'     => 'required|in:ativo,inativo,vencido',
            'observacoes' => 'nullable|string',
        ]);

        $this->service->atualizarConvenio($convenio, $request->all());

        return redirect()
            ->route('empresas.convenios', $empresa)
            ->with('sucesso', 'Convênio atualizado com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF26 – GERENCIAR SUPERVISORES
    |--------------------------------------------------------------------------
    */

    public function supervisores(Request $request, Empresa $empresa)
    {
        $supervisores = $this->service->listarSupervisores($empresa, $request->only(['status']));
        return view('supervisores.index', compact('empresa', 'supervisores'));
    }

    public function supervisorCreate(Empresa $empresa)
    {
        return view('supervisores.create', compact('empresa'));
    }

    public function supervisorStore(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nome'     => 'required|string|max:255',
            'cargo'    => 'required|string|max:100',
            'email'    => 'required|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf'      => 'nullable|string|max:14',
            'formacao' => 'nullable|string|max:255',
        ]);

        $this->service->cadastrarSupervisor($empresa, $request->all());

        return redirect()
            ->route('empresas.supervisores', $empresa)
            ->with('sucesso', 'Supervisor cadastrado com sucesso.');
    }

    public function supervisorEdit(Empresa $empresa, Supervisor $supervisor)
    {
        return view('supervisores.edit', compact('empresa', 'supervisor'));
    }

    public function supervisorUpdate(Request $request, Empresa $empresa, Supervisor $supervisor)
    {
        $request->validate([
            'nome'  => 'required|string|max:255',
            'cargo' => 'required|string|max:100',
            'email' => 'required|email|max:255',
        ]);

        $this->service->atualizarSupervisor($supervisor, $request->all());

        return redirect()
            ->route('empresas.supervisores', $empresa)
            ->with('sucesso', 'Supervisor atualizado com sucesso.');
    }

    public function supervisorDesativar(Empresa $empresa, Supervisor $supervisor)
    {
        $this->service->desativarSupervisor($supervisor);
        return redirect()
            ->route('empresas.supervisores', $empresa)
            ->with('sucesso', 'Supervisor desativado.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF27 – RECEBER SOLICITAÇÕES DE ESTÁGIO
    |--------------------------------------------------------------------------
    */

    public function solicitacoes(Request $request, Empresa $empresa)
    {
        $solicitacoes = $this->service->listarSolicitacoesRecebidas($empresa, $request->only(['status']));
        return view('empresas.solicitacoes', compact('empresa', 'solicitacoes'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF28 – PARTICIPAR DA FORMALIZAÇÃO DO CONTRATO
    |--------------------------------------------------------------------------
    */

    public function contrato(Empresa $empresa, SolicitacaoEstagio $solicitacao)
    {
        $contrato = $this->service->consultarContrato($solicitacao);
        return view('empresas.contrato', compact('empresa', 'solicitacao', 'contrato'));
    }

    /*
    |--------------------------------------------------------------------------
    | RF29 – AVALIAR ESTAGIÁRIOS
    |--------------------------------------------------------------------------
    */

    public function avaliacoes(Request $request, Empresa $empresa, Supervisor $supervisor)
    {
        $avaliacoes = $this->service->listarAvaliacoes($supervisor);
        return view('supervisores.avaliacoes', compact('empresa', 'supervisor', 'avaliacoes'));
    }

    public function avaliacaoCreate(Empresa $empresa, Supervisor $supervisor, SolicitacaoEstagio $solicitacao)
    {
        return view('supervisores.avaliacao_create', compact('empresa', 'supervisor', 'solicitacao'));
    }

    public function avaliacaoStore(Request $request, Empresa $empresa, Supervisor $supervisor, SolicitacaoEstagio $solicitacao)
    {
        $request->validate([
            'pontualidade'       => 'nullable|numeric|min:0|max:10',
            'proatividade'       => 'nullable|numeric|min:0|max:10',
            'qualidade_trabalho' => 'nullable|numeric|min:0|max:10',
            'relacionamento'     => 'nullable|numeric|min:0|max:10',
            'observacoes'        => 'nullable|string',
            'data_avaliacao'     => 'required|date',
        ]);

        $this->service->registrarAvaliacao($supervisor, $solicitacao, $request->all());

        return redirect()
            ->route('empresas.supervisores.avaliacoes', [$empresa, $supervisor])
            ->with('sucesso', 'Avaliação registrada com sucesso.');
    }

    /*
    |--------------------------------------------------------------------------
    | RF30 – CONSULTAR ESTAGIÁRIOS VINCULADOS
    |--------------------------------------------------------------------------
    */

    public function estagiarios(Request $request, Empresa $empresa)
    {
        $estagiarios = $this->service->listarEstagiarios($empresa, $request->only(['supervisor_id']));
        $supervisores = $empresa->supervisores()->ativos()->get();
        return view('empresas.estagiarios', compact('empresa', 'estagiarios', 'supervisores'));
    }
}
