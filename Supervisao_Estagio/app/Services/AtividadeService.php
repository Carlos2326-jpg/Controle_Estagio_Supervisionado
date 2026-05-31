<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AtividadeEstagio;
use App\Models\SolicitacaoEstagio;

class AtividadeService
{
    public function listarPorAluno(Aluno $aluno)
    {
        return AtividadeEstagio::where('aluno_id', $aluno->id)
            ->orderBy('data_atividade', 'desc')
            ->paginate(20);
    }

    public function criar(Aluno $aluno, SolicitacaoEstagio $solicitacao, array $dados)
    {
        $atividade = AtividadeEstagio::create([
            'aluno_id' => $aluno->id,
            'solicitacao_id' => $solicitacao->id,
            'data_atividade' => $dados['data_atividade'],
            'hora_inicio' => $dados['hora_inicio'],
            'hora_fim' => $dados['hora_fim'],
            'horas_computadas' => $dados['horas_computadas'],
            'descricao' => $dados['descricao'],
        ]);

        $aluno->increment('carga_horaria_cumprida', $atividade->horas_computadas);

        return $atividade;
    }

    public function atualizar(AtividadeEstagio $atividade, array $dados)
    {
        abort_if(!$atividade->podeEditar(), 403, 'Esta atividade já foi validada e não pode ser editada.');

        $atividade->update($dados);

        return $atividade->fresh();
    }

    public function excluir(AtividadeEstagio $atividade)
    {
        abort_if(!$atividade->podeEditar(), 403, 'Esta atividade já foi validada e não pode ser excluída.');

        $atividade->aluno->decrement('carga_horaria_cumprida', $atividade->horas_computadas);

        $atividade->delete();
    }
}
