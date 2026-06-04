<?php

namespace App\Http\Controllers;

use App\Models\SolicitacaoEstagio;
use App\Services\SolicitacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitacaoController extends Controller
{
    public function __construct(protected SolicitacaoService $service) {}

    public function index()
    {
        $aluno = \App\Models\Aluno::first();
        $solicitacoes = $this->service->listarPorAluno($aluno);

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function create()
    {
        return view('solicitacoes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'empresa' => 'required|string|max:255',
            'supervisor_nome' => 'required|string|max:255',
            'supervisor_email' => 'nullable|email|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'carga_horaria_semanal' => 'required|integer|min:1',
            'carga_horaria_total' => 'required|integer|min:1',
            'descricao_atividades' => 'required|string',
        ]);

        $aluno = \App\Models\Aluno::first();
        $this->service->criar($aluno, $dados);

        return redirect()->route('solicitacoes.index')->with('sucesso', 'Solicitação enviada com sucesso.');
    }

    public function show(SolicitacaoEstagio $solicitacao)
    {
        return view('solicitacoes.show', compact('solicitacao'));
    }

    public function destroy(SolicitacaoEstagio $solicitacao)
    {
        $this->service->cancelar($solicitacao);

        return redirect()->route('solicitacoes.index')->with('sucesso', 'Solicitação cancelada com sucesso.');
    }
}
