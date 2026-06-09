<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\AtividadeEstagio;
use App\Models\SolicitacaoEstagio;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:empresa']);
    }

    /**
     * UC54 – Validar Atividade
     */
    public function validarAtividade(Request $request, Supervisor $supervisor, AtividadeEstagio $atividade)
    {
        // Verifica se o supervisor é responsável pelo estagiário
        $solicitacao = $atividade->solicitacao;
        if ($solicitacao->supervisor_id !== $supervisor->id) {
            abort(403, 'Você não é o supervisor deste estagiário.');
        }

        $request->validate([
            'validado'    => 'required|boolean',
            'observacao'  => 'required_if:validado,false|nullable|string',
        ]);

        $atividade->update([
            'validado_supervisor'   => $request->validado,
            'validado_em'           => now(),
            'observacao_supervisor' => $request->observacao,
        ]);

        // Se validado, atualiza carga horária do aluno
        if ($request->validado) {
            $this->atualizarCargaHorariaAluno($solicitacao->aluno_id);
        }

        return response()->json([
            'message' => $request->validado ? 'Atividade validada com sucesso' : 'Atividade rejeitada',
            'atividade' => $atividade
        ]);
    }

    /**
     * Listar atividades pendentes para validação
     */
    public function atividadesPendentes(Supervisor $supervisor)
    {
        $atividades = AtividadeEstagio::whereHas('solicitacao', function($q) use ($supervisor) {
            $q->where('supervisor_id', $supervisor->id);
        })
        ->where('validado_supervisor', false)
        ->with(['aluno.user', 'solicitacao'])
        ->orderBy('data', 'desc')
        ->paginate(20);

        return response()->json($atividades);
    }

    private function atualizarCargaHorariaAluno($alunoId)
    {
        $total = AtividadeEstagio::where('aluno_id', $alunoId)
            ->where('validado_supervisor', true)
            ->sum('horas');
            
        \App\Models\Aluno::where('id', $alunoId)->update([
            'carga_horaria_cumprida' => $total
        ]);
    }
}