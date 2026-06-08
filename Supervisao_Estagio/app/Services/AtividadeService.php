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
            ->orderBy('data', 'desc')
            ->paginate(20);
    }

    public function criar(Aluno $aluno, SolicitacaoEstagio $solicitacao, array $dados)
    {
        $atividade = AtividadeEstagio::create([
            'aluno_id'               => $aluno->id,
            'solicitacao_estagio_id' => $solicitacao->id,
            'data'                   => $dados['data'],
            'horas'                  => $dados['horas'],
            'descricao'              => $dados['descricao'],
            'validado_supervisor'    => false,
        ]);

        $aluno->increment('carga_horaria_cumprida', $atividade->horas);

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

        $atividade->aluno->decrement('carga_horaria_cumprida', $atividade->horas);

        $atividade->delete();
    }
}
