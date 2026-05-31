<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\SolicitacaoEstagio;

class SolicitacaoService
{
    public function listarPorAluno(Aluno $aluno)
    {
        return SolicitacaoEstagio::where('aluno_id', $aluno->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function criar(Aluno $aluno, array $dados)
    {
        return SolicitacaoEstagio::create([
            'aluno_id' => $aluno->id,
            'empresa' => $dados['empresa'],
            'supervisor_nome' => $dados['supervisor_nome'],
            'supervisor_email' => $dados['supervisor_email'] ?? null,
            'data_inicio' => $dados['data_inicio'],
            'data_fim' => $dados['data_fim'],
            'carga_horaria_semanal' => $dados['carga_horaria_semanal'],
            'carga_horaria_total' => $dados['carga_horaria_total'],
            'descricao_atividades' => $dados['descricao_atividades'],
        ]);
    }

    public function cancelar(SolicitacaoEstagio $solicitacao)
    {
        abort_if(!$solicitacao->isPendente(), 403, 'Apenas solicitações pendentes podem ser canceladas.');

        $solicitacao->update(['status' => 'cancelada']);

        return $solicitacao->fresh();
    }
}
