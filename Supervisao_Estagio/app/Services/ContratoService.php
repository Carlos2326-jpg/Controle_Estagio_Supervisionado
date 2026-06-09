<?php

namespace App\Services;

use App\Models\SolicitacaoEstagio;
use App\Models\Contrato;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContratoService
{
    /**
     * UC41 – Gerar Contrato automaticamente após aprovação
     */
    public function gerarContrato(SolicitacaoEstagio $solicitacao): Contrato
    {
        return DB::transaction(function () use ($solicitacao) {
            // Verifica se já existe contrato
            if ($solicitacao->contrato) {
                return $solicitacao->contrato;
            }

            // Gera número único do contrato
            $numeroContrato = $this->gerarNumeroContrato($solicitacao);

            $contrato = Contrato::create([
                'aluno_id'               => $solicitacao->aluno_id,
                'solicitacao_estagio_id' => $solicitacao->id,
                'empresa_id'             => $solicitacao->empresa_id,
                'supervisor_id'          => $solicitacao->supervisor_id,
                'numero_contrato'        => $numeroContrato,
                'data_inicio'            => $solicitacao->data_inicio_prevista,
                'data_fim'               => $solicitacao->data_fim_prevista,
                'carga_horaria_semanal'  => $solicitacao->carga_horaria_semanal,
                'carga_horaria_total'    => $solicitacao->carga_horaria_total,
                'status'                 => 'ativo',
                'assinado_em'            => null,
            ]);

            Log::info("Contrato {$contrato->id} gerado para solicitação {$solicitacao->id}");

            return $contrato;
        });
    }

    /**
     * Gerar número único do contrato
     */
    private function gerarNumeroContrato(SolicitacaoEstagio $solicitacao): string
    {
        $ano = date('Y');
        $mes = date('m');
        $sequencial = str_pad($solicitacao->id, 5, '0', STR_PAD_LEFT);
        
        return "CTR-{$ano}{$mes}-{$sequencial}";
    }
}