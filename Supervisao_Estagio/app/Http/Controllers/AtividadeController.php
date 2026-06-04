<?php

namespace App\Http\Controllers;

use App\Models\AtividadeEstagio;
use App\Models\SolicitacaoEstagio;
use App\Services\AtividadeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AtividadeController extends Controller
{
    public function __construct(protected AtividadeService $service) {}

    public function index()
    {
        $aluno = Auth::user()->aluno;
        $atividades = $this->service->listarPorAluno($aluno);

        return view('atividades.index', compact('atividades'));
    }

    public function create()
    {
        $aluno = Auth::user()->aluno;
        $solicitacoes = SolicitacaoEstagio::where('aluno_id', $aluno->id)
            ->aprovadas()
            ->get();

        return view('atividades.create', compact('solicitacoes'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'solicitacao_id' => 'required|exists:solicitacoes_estagio,id',
            'data_atividade' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required|after:hora_inicio',
            'horas_computadas' => 'required|numeric|min:0.1|max:99.99',
            'descricao' => 'required|string',
        ]);

        $aluno = Auth::user()->aluno;
        $solicitacao = SolicitacaoEstagio::findOrFail($dados['solicitacao_id']);
        $this->service->criar($aluno, $solicitacao, $dados);

        return redirect()->route('atividades.index')->with('sucesso', 'Atividade registrada com sucesso.');
    }

    public function edit(AtividadeEstagio $atividade)
    {
        $aluno = Auth::user()->aluno;
        $solicitacoes = SolicitacaoEstagio::where('aluno_id', $aluno->id)
            ->aprovadas()
            ->get();

        return view('atividades.edit', compact('atividade', 'solicitacoes'));
    }

    public function update(Request $request, AtividadeEstagio $atividade)
    {
        $dados = $request->validate([
            'data_atividade' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'required|after:hora_inicio',
            'horas_computadas' => 'required|numeric|min:0.1|max:99.99',
            'descricao' => 'required|string',
        ]);

        $this->service->atualizar($atividade, $dados);

        return redirect()->route('atividades.index')->with('sucesso', 'Atividade atualizada com sucesso.');
    }

    public function destroy(AtividadeEstagio $atividade)
    {
        $this->service->excluir($atividade);

        return redirect()->route('atividades.index')->with('sucesso', 'Atividade excluída com sucesso.');
    }
}
