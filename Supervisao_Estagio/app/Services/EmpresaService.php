<?php

namespace App\Services;

use App\Models\Empresa;
use App\Models\Convenio;
use App\Models\Supervisor;
use App\Models\AvaliacaoSupervisor;
use App\Models\SolicitacaoEstagio;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaService
{
    // ────────────────────────────────────────────
    // RF24 – Gerenciar Empresas
    // ────────────────────────────────────────────

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Empresa::with(['convenios', 'supervisores'])
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->when(isset($filtros['busca']), function ($q) use ($filtros) {
                $q->where('razao_social', 'like', "%{$filtros['busca']}%")
                  ->orWhere('nome_fantasia', 'like', "%{$filtros['busca']}%")
                  ->orWhere('cnpj', 'like', "%{$filtros['busca']}%");
            });

        return $query->orderBy('razao_social')->paginate(20);
    }

    public function cadastrar(array $dados): Empresa
    {
        return DB::transaction(function () use ($dados) {
            $empresa = Empresa::create($dados);
            Log::info("Empresa cadastrada: {$empresa->id} - {$empresa->razao_social}");
            return $empresa;
        });
    }

    public function atualizar(Empresa $empresa, array $dados): Empresa
    {
        DB::transaction(function () use ($empresa, $dados) {
            $empresa->update(array_filter($dados, fn($v) => $v !== null));
        });

        return $empresa->fresh(['convenios', 'supervisores']);
    }

    public function desativar(Empresa $empresa): void
    {
        $empresa->update(['status' => 'inativa']);
        Log::info("Empresa {$empresa->id} desativada.");
    }

    public function reativar(Empresa $empresa): void
    {
        $empresa->update(['status' => 'ativa']);
        Log::info("Empresa {$empresa->id} reativada.");
    }

    public function consultar(Empresa $empresa): Empresa
    {
        return $empresa->load(['convenios', 'supervisores', 'solicitacoes.aluno.user']);
    }

    // ────────────────────────────────────────────
    // RF25 – Gerenciar Convênios
    // ────────────────────────────────────────────

    public function listarConvenios(Empresa $empresa, array $filtros = []): LengthAwarePaginator
    {
        return $empresa->convenios()
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('data_fim', 'desc')
            ->paginate(20);
    }

    public function cadastrarConvenio(Empresa $empresa, array $dados): Convenio
    {
        return DB::transaction(function () use ($empresa, $dados) {
            $convenio = $empresa->convenios()->create($dados);
            Log::info("Convênio {$convenio->id} cadastrado para empresa {$empresa->id}.");
            return $convenio;
        });
    }

    public function atualizarConvenio(Convenio $convenio, array $dados): Convenio
    {
        $convenio->update(array_filter($dados, fn($v) => $v !== null));
        return $convenio->fresh();
    }

    public function verificarVencimentosConvenios(): void
    {
        $convenios = Convenio::where('status', 'ativo')
            ->where('data_fim', '<', now())
            ->get();

        foreach ($convenios as $convenio) {
            $convenio->update(['status' => 'vencido']);
            Log::info("Convênio {$convenio->id} marcado como vencido.");
        }
    }

    // ────────────────────────────────────────────
    // RF26 – Gerenciar Supervisores
    // ────────────────────────────────────────────

    public function listarSupervisores(Empresa $empresa, array $filtros = []): LengthAwarePaginator
    {
        return $empresa->supervisores()
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('nome')
            ->paginate(20);
    }

    public function cadastrarSupervisor(Empresa $empresa, array $dados): Supervisor
    {
        return DB::transaction(function () use ($empresa, $dados) {
            $supervisor = $empresa->supervisores()->create($dados);
            Log::info("Supervisor {$supervisor->id} cadastrado para empresa {$empresa->id}.");
            return $supervisor;
        });
    }

    public function atualizarSupervisor(Supervisor $supervisor, array $dados): Supervisor
    {
        $supervisor->update(array_filter($dados, fn($v) => $v !== null));
        return $supervisor->fresh();
    }

    public function desativarSupervisor(Supervisor $supervisor): void
    {
        $supervisor->update(['status' => 'inativo']);
        Log::info("Supervisor {$supervisor->id} desativado.");
    }

    // ────────────────────────────────────────────
    // RF27 – Receber Solicitações de Estágio
    // ────────────────────────────────────────────

    public function listarSolicitacoesRecebidas(Empresa $empresa, array $filtros = []): LengthAwarePaginator
    {
        return $empresa->solicitacoes()
            ->with(['aluno.user', 'supervisor'])
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF28 – Participar da Formalização do Contrato
    // ────────────────────────────────────────────

    public function consultarContrato(SolicitacaoEstagio $solicitacao): ?object
    {
        // Retorna o contrato vinculado à solicitação aprovada
        return $solicitacao->load('contrato')->contrato;
    }

    // ────────────────────────────────────────────
    // RF29 – Avaliar Estagiários
    // ────────────────────────────────────────────

    public function registrarAvaliacao(Supervisor $supervisor, SolicitacaoEstagio $solicitacao, array $dados): AvaliacaoSupervisor
    {
        return DB::transaction(function () use ($supervisor, $solicitacao, $dados) {
            // Calcula nota geral como média dos critérios informados
            $criterios = array_filter([
                $dados['pontualidade']      ?? null,
                $dados['proatividade']      ?? null,
                $dados['qualidade_trabalho'] ?? null,
                $dados['relacionamento']    ?? null,
            ]);

            if (count($criterios) > 0) {
                $dados['nota_geral'] = round(array_sum($criterios) / count($criterios), 2);
            }

            $avaliacao = AvaliacaoSupervisor::create(array_merge($dados, [
                'supervisor_id'          => $supervisor->id,
                'solicitacao_estagio_id' => $solicitacao->id,
                'data_avaliacao'         => $dados['data_avaliacao'] ?? now()->toDateString(),
            ]));

            Log::info("Avaliação do supervisor {$supervisor->id} registrada para solicitação {$solicitacao->id}.");
            return $avaliacao;
        });
    }

    public function listarAvaliacoes(Supervisor $supervisor, array $filtros = []): LengthAwarePaginator
    {
        return $supervisor->avaliacoes()
            ->with('solicitacao.aluno.user')
            ->orderBy('data_avaliacao', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF30 – Consultar Estagiários Vinculados
    // ────────────────────────────────────────────

    public function listarEstagiarios(Empresa $empresa, array $filtros = []): LengthAwarePaginator
    {
        return $empresa->solicitacoes()
            ->with(['aluno.user', 'supervisor'])
            ->where('status', 'aprovada')
            ->when(isset($filtros['supervisor_id']), fn($q) => $q->where('supervisor_id', $filtros['supervisor_id']))
            ->orderBy('data_inicio_prevista', 'desc')
            ->paginate(20);
    }
}
