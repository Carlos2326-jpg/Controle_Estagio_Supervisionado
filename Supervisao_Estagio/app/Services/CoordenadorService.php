<?php

namespace App\Services;

use App\Models\Coordenador;
use App\Models\SolicitacaoEstagio;
use App\Models\HistoricoAnalise;
use App\Models\Documento;
use App\Models\Avaliacao;
use App\Models\User;
use App\Notifications\AlertaCoordenadorNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CoordenadorService
{
    // ────────────────────────────────────────────
    // RF13 – Gerenciar Coordenadores
    // ────────────────────────────────────────────

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Coordenador::with(['user', 'curso'])
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->when(isset($filtros['curso_id']), fn($q) => $q->where('curso_id', $filtros['curso_id']))
            ->when(isset($filtros['busca']), function ($q) use ($filtros) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$filtros['busca']}%")
                    ->orWhere('email', 'like', "%{$filtros['busca']}%"));
            });

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function cadastrar(array $dados): Coordenador
    {
        return DB::transaction(function () use ($dados) {
            $user = User::create([
                'name'     => $dados['nome'],
                'email'    => $dados['email'],
                'password' => Hash::make($dados['password'] ?? 'Estagio@2024'),
            ]);

            $user->assignRole('coordenador');

            return Coordenador::create([
                'user_id'   => $user->id,
                'curso_id'  => $dados['curso_id'],
                'matricula' => $dados['matricula'],
                'telefone'  => $dados['telefone'] ?? null,
                'lattes'    => $dados['lattes'] ?? null,
                'status'    => 'ativo',
            ]);
        });
    }

    public function atualizar(Coordenador $coordenador, array $dados): Coordenador
    {
        DB::transaction(function () use ($coordenador, $dados) {
            $coordenador->user->update(array_filter([
                'name'  => $dados['nome'] ?? null,
                'email' => $dados['email'] ?? null,
            ]));

            $coordenador->update(array_filter([
                'curso_id'  => $dados['curso_id'] ?? null,
                'matricula' => $dados['matricula'] ?? null,
                'telefone'  => $dados['telefone'] ?? null,
                'lattes'    => $dados['lattes'] ?? null,
                'status'    => $dados['status'] ?? null,
            ]));
        });

        return $coordenador->fresh();
    }

    public function inativar(Coordenador $coordenador): void
    {
        $coordenador->update(['status' => 'inativo']);
        Log::info("Coordenador {$coordenador->id} inativado.");
    }

    // ────────────────────────────────────────────
    // RF14 – Consultar Informações Acadêmicas
    // ────────────────────────────────────────────

    public function consultarInformacoesAcademicas(Coordenador $coordenador): array
    {
        $curso = $coordenador->curso()->with(['alunos', 'coordenadores'])->first();

        return [
            'curso'         => $curso,
            'total_alunos'  => $curso->alunos->count(),
            'estagios_ativos' => SolicitacaoEstagio::where('curso_id', $curso->id)
                ->where('status', 'aprovada')->count(),
            'pendencias'    => $this->contarPendencias($coordenador),
        ];
    }

    // ────────────────────────────────────────────
    // RF15 – Analisar Solicitações de Estágio
    // ────────────────────────────────────────────

    public function listarSolicitacoes(Coordenador $coordenador, array $filtros = []): LengthAwarePaginator
    {
        return SolicitacaoEstagio::with(['aluno.user', 'empresa', 'supervisor'])
            ->where('curso_id', $coordenador->curso_id)
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->when(isset($filtros['busca']), function ($q) use ($filtros) {
                $q->whereHas('aluno.user', fn($u) => $u->where('name', 'like', "%{$filtros['busca']}%"));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function aprovarSolicitacao(Coordenador $coordenador, SolicitacaoEstagio $solicitacao, ?string $justificativa = null): void
    {
        $this->analisarSolicitacao($coordenador, $solicitacao, 'aprovada', $justificativa);
    }

    public function reprovarSolicitacao(Coordenador $coordenador, SolicitacaoEstagio $solicitacao, string $justificativa): void
    {
        $this->analisarSolicitacao($coordenador, $solicitacao, 'reprovada', $justificativa);
    }

    private function analisarSolicitacao(
        Coordenador $coordenador,
        SolicitacaoEstagio $solicitacao,
        string $decisao,
        ?string $justificativa
    ): void {
        DB::transaction(function () use ($coordenador, $solicitacao, $decisao, $justificativa) {
            $solicitacao->update(['status' => $decisao]);

            // RF16 – Registrar Histórico
            HistoricoAnalise::create([
                'solicitacao_estagio_id' => $solicitacao->id,
                'coordenador_id'         => $coordenador->id,
                'decisao'                => $decisao,
                'justificativa'          => $justificativa,
                'analisado_em'           => now(),
            ]);

            // RF12/RF21 – Notificar aluno
            $solicitacao->aluno->user->notify(
                new AlertaCoordenadorNotification(
                    "Sua solicitação de estágio foi {$decisao}.",
                    $justificativa
                )
            );
        });
    }

    // ────────────────────────────────────────────
    // RF16 – Registrar Histórico de Análises
    // ────────────────────────────────────────────

    public function historicoAnalises(Coordenador $coordenador, array $filtros = []): LengthAwarePaginator
    {
        return HistoricoAnalise::with(['solicitacao.aluno.user', 'coordenador.user'])
            ->where('coordenador_id', $coordenador->id)
            ->when(isset($filtros['decisao']), fn($q) => $q->where('decisao', $filtros['decisao']))
            ->when(isset($filtros['data_inicio']), fn($q) => $q->whereDate('analisado_em', '>=', $filtros['data_inicio']))
            ->when(isset($filtros['data_fim']), fn($q) => $q->whereDate('analisado_em', '<=', $filtros['data_fim']))
            ->orderBy('analisado_em', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF17 – Validar Documentos
    // ────────────────────────────────────────────

    public function listarDocumentos(Coordenador $coordenador, array $filtros = []): LengthAwarePaginator
    {
        return Documento::with(['aluno.user', 'solicitacao'])
            ->whereHas('aluno', fn($q) => $q->where('curso_id', $coordenador->curso_id))
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function aprovarDocumento(Coordenador $coordenador, Documento $documento, ?string $observacao = null): void
    {
        $documento->update([
            'status'                  => 'aprovado',
            'observacao_coordenador'  => $observacao,
            'validado_por'            => $coordenador->id,
            'validado_em'             => now(),
        ]);

        $documento->aluno->user->notify(
            new AlertaCoordenadorNotification("Documento '{$documento->nome}' aprovado.")
        );
    }

    public function reprovarDocumento(Coordenador $coordenador, Documento $documento, string $observacao): void
    {
        $documento->update([
            'status'                 => 'reprovado',
            'observacao_coordenador' => $observacao,
            'validado_por'           => $coordenador->id,
            'validado_em'            => now(),
        ]);

        $documento->aluno->user->notify(
            new AlertaCoordenadorNotification("Documento '{$documento->nome}' reprovado. Motivo: {$observacao}")
        );
    }

    // ────────────────────────────────────────────
    // RF18 – Acompanhar Atividades de Estágio
    // ────────────────────────────────────────────

    public function acompanharAtividades(Coordenador $coordenador, array $filtros = []): array
    {
        $query = SolicitacaoEstagio::with(['aluno.user', 'atividades'])
            ->where('curso_id', $coordenador->curso_id)
            ->where('status', 'aprovada');

        if (isset($filtros['aluno_id'])) {
            $query->where('aluno_id', $filtros['aluno_id']);
        }

        $estagios = $query->get();

        return $estagios->map(function ($estagio) {
            return [
                'estagio'            => $estagio,
                'horas_cumpridas'    => $estagio->atividades->sum('horas'),
                'horas_totais'       => $estagio->carga_horaria_total,
                'percentual'         => $estagio->carga_horaria_total > 0
                    ? round(($estagio->atividades->sum('horas') / $estagio->carga_horaria_total) * 100, 1)
                    : 0,
                'total_registros'    => $estagio->atividades->count(),
                'ultimo_registro'    => $estagio->atividades->sortByDesc('data')->first(),
            ];
        })->toArray();
    }

    // ────────────────────────────────────────────
    // RF19 – Consultar Pendências
    // ────────────────────────────────────────────

    public function consultarPendencias(Coordenador $coordenador): array
    {
        return [
            'solicitacoes_pendentes' => SolicitacaoEstagio::where('curso_id', $coordenador->curso_id)
                ->where('status', 'pendente')
                ->with('aluno.user')
                ->get(),

            'documentos_pendentes' => Documento::whereHas('aluno', fn($q) => $q->where('curso_id', $coordenador->curso_id))
                ->where('status', 'pendente')
                ->with(['aluno.user', 'solicitacao'])
                ->get(),

            'avaliacoes_pendentes' => SolicitacaoEstagio::where('curso_id', $coordenador->curso_id)
                ->where('status', 'aprovada')
                ->whereDoesntHave('avaliacao', fn($q) => $q->where('tipo', 'final'))
                ->whereDate('data_fim_prevista', '<=', now()->addDays(30))
                ->with('aluno.user')
                ->get(),
        ];
    }

    private function contarPendencias(Coordenador $coordenador): int
    {
        $pendencias = $this->consultarPendencias($coordenador);
        return $pendencias['solicitacoes_pendentes']->count()
            + $pendencias['documentos_pendentes']->count()
            + $pendencias['avaliacoes_pendentes']->count();
    }

    // ────────────────────────────────────────────
    // RF20 – Realizar Avaliações
    // ────────────────────────────────────────────

    public function listarAvaliacoes(Coordenador $coordenador, array $filtros = []): LengthAwarePaginator
    {
        return Avaliacao::with(['aluno.user', 'solicitacao'])
            ->where('coordenador_id', $coordenador->id)
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->when(isset($filtros['conceito']), fn($q) => $q->where('conceito', $filtros['conceito']))
            ->orderBy('data_avaliacao', 'desc')
            ->paginate(20);
    }

    public function registrarAvaliacao(Coordenador $coordenador, SolicitacaoEstagio $solicitacao, array $dados): Avaliacao
    {
        $avaliacao = Avaliacao::create([
            'aluno_id'               => $solicitacao->aluno_id,
            'coordenador_id'         => $coordenador->id,
            'solicitacao_estagio_id' => $solicitacao->id,
            'tipo'                   => $dados['tipo'],
            'nota'                   => $dados['nota'] ?? null,
            'conceito'               => $dados['conceito'] ?? null,
            'parecer'                => $dados['parecer'],
            'pontos_fortes'          => $dados['pontos_fortes'] ?? null,
            'pontos_melhoria'        => $dados['pontos_melhoria'] ?? null,
            'data_avaliacao'         => $dados['data_avaliacao'] ?? now()->toDateString(),
        ]);

        // Notificar aluno (RF12/RF21)
        $solicitacao->aluno->user->notify(
            new AlertaCoordenadorNotification("Avaliação {$dados['tipo']} registrada pelo coordenador.")
        );

        return $avaliacao;
    }

    public function atualizarAvaliacao(Avaliacao $avaliacao, array $dados): Avaliacao
    {
        $avaliacao->update($dados);
        return $avaliacao->fresh();
    }

    // ────────────────────────────────────────────
    // RF21 – Receber Alertas
    // ────────────────────────────────────────────

    public function alertas(Coordenador $coordenador): Collection
    {
        return $coordenador->user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function marcarAlertaLido(Coordenador $coordenador, string $notificationId): void
    {
        $coordenador->user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
    }

    // ────────────────────────────────────────────
    // RF22 – Gerar Relatórios
    // ────────────────────────────────────────────

    public function gerarRelatorio(Coordenador $coordenador, string $tipo, array $filtros = []): array
    {
        return match ($tipo) {
            'alunos'    => $this->relatorioAlunos($coordenador, $filtros),
            'contratos' => $this->relatorioContratos($coordenador, $filtros),
            'horas'     => $this->relatorioHoras($coordenador, $filtros),
            'avaliacoes' => $this->relatorioAvaliacoes($coordenador, $filtros),
            default     => throw new \InvalidArgumentException("Tipo de relatório inválido: {$tipo}"),
        };
    }

    private function relatorioAlunos(Coordenador $coordenador, array $filtros): array
    {
        $alunos = $coordenador->curso->alunos()
            ->with(['solicitacoesEstagio' => fn($q) => $q->latest()])
            ->when(isset($filtros['status_estagio']), function ($q) use ($filtros) {
                $q->whereHas('solicitacoesEstagio', fn($s) => $s->where('status', $filtros['status_estagio']));
            })
            ->paginate(20);

        return [
            'tipo'       => 'alunos',
            'titulo'     => 'Relatório de Alunos — ' . $coordenador->curso->nome,
            'gerado_em'  => now(),
            'coordenador' => $coordenador->nome,
            'dados'       => $alunos,
        ];
    }

    private function relatorioContratos(Coordenador $coordenador, array $filtros): array
    {
        $contratos = SolicitacaoEstagio::with(['aluno.user', 'empresa', 'contrato'])
            ->where('curso_id', $coordenador->curso_id)
            ->where('status', 'aprovada')
            ->when(isset($filtros['data_inicio']), fn($q) => $q->whereDate('data_inicio_prevista', '>=', $filtros['data_inicio']))
            ->when(isset($filtros['data_fim']), fn($q) => $q->whereDate('data_fim_prevista', '<=', $filtros['data_fim']))
            ->paginate(20);

        return [
            'tipo'       => 'contratos',
            'titulo'     => 'Relatório de Contratos Ativos',
            'gerado_em'  => now(),
            'coordenador' => $coordenador->nome,
            'dados'       => $contratos,
        ];
    }

    private function relatorioHoras(Coordenador $coordenador, array $filtros): array
    {
        $estagios = SolicitacaoEstagio::with(['aluno.user', 'atividades'])
            ->where('curso_id', $coordenador->curso_id)
            ->where('status', 'aprovada')
            ->get()
            ->map(fn($e) => [
                'aluno'           => $e->aluno->user->name,
                'matricula'       => $e->aluno->matricula,
                'empresa'         => $e->empresa->razao_social ?? '-',
                'horas_previstas' => $e->carga_horaria_total,
                'horas_cumpridas' => $e->atividades->sum('horas'),
                'percentual'      => $e->carga_horaria_total > 0
                    ? round(($e->atividades->sum('horas') / $e->carga_horaria_total) * 100, 1) . '%'
                    : '0%',
            ]);

        return [
            'tipo'       => 'horas',
            'titulo'     => 'Relatório de Horas Cumpridas',
            'gerado_em'  => now(),
            'coordenador' => $coordenador->nome,
            'dados'       => $estagios,
        ];
    }

    private function relatorioAvaliacoes(Coordenador $coordenador, array $filtros): array
    {
        $avaliacoes = Avaliacao::with(['aluno.user', 'solicitacao.empresa'])
            ->where('coordenador_id', $coordenador->id)
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->when(isset($filtros['conceito']), fn($q) => $q->where('conceito', $filtros['conceito']))
            ->get();

        return [
            'tipo'       => 'avaliacoes',
            'titulo'     => 'Relatório de Avaliações',
            'gerado_em'  => now(),
            'coordenador' => $coordenador->nome,
            'dados'       => $avaliacoes,
        ];
    }
}