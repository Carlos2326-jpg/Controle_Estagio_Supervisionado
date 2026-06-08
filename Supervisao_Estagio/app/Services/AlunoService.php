<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AtividadeEstagio;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\SolicitacaoEstagio;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AlunoService
{
    // ────────────────────────────────────────────
    // RF01 – Gerenciar e Consultar Dados do Aluno
    // ────────────────────────────────────────────

    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Aluno::with(['user', 'curso'])
            ->when(isset($filtros['curso_id']), fn($q) => $q->where('curso_id', $filtros['curso_id']))
            ->when(isset($filtros['situacao']), fn($q) => $q->where('situacao_estagio', $filtros['situacao']))
            ->when(isset($filtros['ativo']), fn($q) => $q->where('ativo', $filtros['ativo']))
            ->when(isset($filtros['busca']), function ($q) use ($filtros) {
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$filtros['busca']}%")
                      ->orWhere('email', 'like', "%{$filtros['busca']}%")
                )->orWhere('matricula', 'like', "%{$filtros['busca']}%");
            });

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function buscarPorUser(User $user): Aluno
    {
        return Aluno::where('user_id', $user->id)->with(['user', 'curso'])->firstOrFail();
    }

    public function criar(array $dados): Aluno
    {
        return DB::transaction(function () use ($dados) {
            $user = User::create([
                'name'     => $dados['name'] ?? $dados['nome'],
                'email'    => $dados['email'],
                'password' => isset($dados['password']) ? Hash::make($dados['password']) : Hash::make('Estagio@2024'),
                'perfil'   => 'aluno',
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('aluno');
            }

            return Aluno::create([
                'user_id'                => $user->id,
                'curso_id'               => $dados['curso_id'] ?? null,
                'matricula'              => $dados['matricula'],
                'cpf'                    => $dados['cpf'] ?? null,
                'telefone'               => $dados['telefone'] ?? null,
                'data_nascimento'        => $dados['data_nascimento'] ?? null,
                'endereco'               => $dados['endereco'] ?? null,
                'situacao_estagio'       => 'sem_estagio',
                'carga_horaria_cumprida' => 0,
                'ativo'                  => true,
            ]);
        });
    }

    public function cadastrar(array $dados): Aluno
    {
        return $this->criar($dados);
    }

    public function atualizar(Aluno $aluno, array $dados): Aluno
    {
        DB::transaction(function () use ($aluno, $dados) {
            $aluno->user->update(array_filter([
                'name'  => $dados['name'] ?? $dados['nome'] ?? null,
                'email' => $dados['email'] ?? null,
            ]));

            $aluno->update(array_filter([
                'curso_id'        => $dados['curso_id'] ?? null,
                'matricula'       => $dados['matricula'] ?? null,
                'cpf'             => $dados['cpf'] ?? null,
                'telefone'        => $dados['telefone'] ?? null,
                'data_nascimento' => $dados['data_nascimento'] ?? null,
                'endereco'        => $dados['endereco'] ?? null,
            ]));
        });

        return $aluno->fresh(['user', 'curso']);
    }

    public function inativar(Aluno $aluno): void
    {
        $aluno->update(['ativo' => false]);
        Log::info("Aluno {$aluno->id} inativado.");
    }

    // ────────────────────────────────────────────
    // RF02 – Consultar Situação de Estágio
    // ────────────────────────────────────────────

    public function consultarSituacao(Aluno $aluno): array
    {
        $estagioAtivo = $aluno->estagioAtivo();
        $cargaObrigatoria = $aluno->carga_horaria_obrigatoria ?? $aluno->cursoRelacionado->carga_horaria_estagio ?? 0;

        return [
            'situacao'               => $aluno->situacao_estagio ?? $aluno->status_estagio,
            'situacao_label'         => $aluno->situacao_label,
            'carga_horaria_cumprida' => $aluno->carga_horaria_cumprida,
            'carga_horaria_total'    => $cargaObrigatoria,
            'percentual_cumprido'    => $aluno->percentual_horas,
            'estagio_ativo'          => $estagioAtivo ? $estagioAtivo->load(['empresa', 'supervisor', 'contrato']) : null,
        ];
    }

    // ────────────────────────────────────────────
    // RF03 – Solicitar Estágio
    // ────────────────────────────────────────────

    public function solicitarEstagio(Aluno $aluno, array $dados): SolicitacaoEstagio
    {
        abort_if(
            $aluno->temSolicitacaoPendente(),
            422,
            'Você já possui uma solicitação de estágio pendente.'
        );

        $solicitacao = SolicitacaoEstagio::create([
            'aluno_id'              => $aluno->id,
            'empresa_id'            => $dados['empresa_id'],
            'supervisor_id'         => $dados['supervisor_id'],
            'curso_id'              => $aluno->curso_id,
            'data_inicio_prevista'  => $dados['data_inicio_prevista'],
            'data_fim_prevista'     => $dados['data_fim_prevista'],
            'carga_horaria_semanal' => $dados['carga_horaria_semanal'],
            'carga_horaria_total'   => $dados['carga_horaria_total'],
            'descricao_atividades'  => $dados['descricao_atividades'],
            'status'                => 'pendente',
        ]);

        Log::info("Solicitação {$solicitacao->id} criada pelo aluno {$aluno->id}.");

        return $solicitacao->load(['empresa', 'supervisor']);
    }

    // ────────────────────────────────────────────
    // RF04 – Consultar Solicitações
    // ────────────────────────────────────────────

    public function listarSolicitacoes(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return SolicitacaoEstagio::with(['empresa', 'supervisor', 'historicoAnalises'])
            ->where('aluno_id', $aluno->id)
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF05 – Cancelar Solicitação
    // ────────────────────────────────────────────

    public function cancelarSolicitacao(Aluno $aluno, SolicitacaoEstagio $solicitacao): void
    {
        abort_if(
            $solicitacao->aluno_id !== $aluno->id,
            403,
            'Esta solicitação não pertence ao aluno informado.'
        );

        abort_if(
            !$solicitacao->isPendente(),
            422,
            'Apenas solicitações com status pendente podem ser canceladas.'
        );

        $solicitacao->update(['status' => 'cancelada']);

        Log::info("Solicitação {$solicitacao->id} cancelada pelo aluno {$aluno->id}.");
    }

    // ────────────────────────────────────────────
    // RF06 – Visualizar Contrato de Estágio
    // ────────────────────────────────────────────

    public function listarContratos(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return Contrato::with(['empresa', 'supervisor', 'solicitacao'])
            ->where('aluno_id', $aluno->id)
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function visualizarContrato(Aluno $aluno, Contrato $contrato): Contrato
    {
        abort_if(
            $contrato->aluno_id !== $aluno->id,
            403,
            'Este contrato não pertence ao aluno informado.'
        );

        return $contrato->load(['empresa', 'supervisor', 'solicitacao']);
    }

    // ────────────────────────────────────────────
    // RF07 – Registrar Atividades de Estágio
    // ────────────────────────────────────────────

    public function registrarAtividade(Aluno $aluno, array $dados): AtividadeEstagio
    {
        $solicitacao = SolicitacaoEstagio::findOrFail($dados['solicitacao_estagio_id'] ?? $dados['solicitacao_id']);

        abort_if(
            $solicitacao->aluno_id !== $aluno->id,
            403,
            'Esta solicitação não pertence ao aluno informado.'
        );

        $atividade = AtividadeEstagio::create([
            'aluno_id'               => $aluno->id,
            'solicitacao_id'         => $solicitacao->id,
            'solicitacao_estagio_id' => $solicitacao->id,
            'data_atividade'         => $dados['data'] ?? $dados['data_atividade'] ?? null,
            'data'                   => $dados['data'] ?? $dados['data_atividade'] ?? null,
            'hora_inicio'            => $dados['hora_inicio'] ?? null,
            'hora_fim'               => $dados['hora_fim'] ?? null,
            'descricao'              => $dados['descricao'],
            'horas_computadas'       => $dados['horas'] ?? $dados['horas_computadas'] ?? 0,
            'horas'                  => $dados['horas'] ?? $dados['horas_computadas'] ?? 0,
            'validado'               => false,
            'validado_supervisor'    => false,
        ]);

        $this->recalcularCargaHoraria($aluno);

        return $atividade;
    }

    // ────────────────────────────────────────────
    // RF08 – Editar Registros de Atividades
    // ────────────────────────────────────────────

    public function atualizarAtividade(Aluno $aluno, AtividadeEstagio $atividade, array $dados): AtividadeEstagio
    {
        abort_if(
            $atividade->aluno_id !== $aluno->id,
            403,
            'Este registro não pertence ao aluno informado.'
        );

        abort_if(
            !$atividade->podeEditar(),
            422,
            'Este registro já foi validado e não pode ser editado.'
        );

        $atividade->update(array_filter([
            'data'             => $dados['data'] ?? null,
            'data_atividade'   => $dados['data'] ?? null,
            'descricao'        => $dados['descricao'] ?? null,
            'horas'            => $dados['horas'] ?? null,
            'horas_computadas' => $dados['horas'] ?? null,
        ]));

        $this->recalcularCargaHoraria($aluno);

        return $atividade->fresh();
    }

    public function excluirAtividade(Aluno $aluno, AtividadeEstagio $atividade): void
    {
        abort_if(
            $atividade->aluno_id !== $aluno->id,
            403,
            'Este registro não pertence ao aluno informado.'
        );

        abort_if(
            !$atividade->podeEditar(),
            422,
            'Este registro já foi validado e não pode ser excluído.'
        );

        $atividade->delete();

        $this->recalcularCargaHoraria($aluno);
    }

    public function listarAtividades(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return AtividadeEstagio::with('solicitacao')
            ->where('aluno_id', $aluno->id)
            ->when(isset($filtros['solicitacao_id']), fn($q) =>
                $q->where('solicitacao_estagio_id', $filtros['solicitacao_id'])->orWhere('solicitacao_id', $filtros['solicitacao_id'])
            )
            ->when(isset($filtros['validado']), fn($q) =>
                $q->where('validado_supervisor', filter_var($filtros['validado'], FILTER_VALIDATE_BOOLEAN))
                  ->orWhere('validado', filter_var($filtros['validado'], FILTER_VALIDATE_BOOLEAN))
            )
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF09 / RF10 – Documentos
    // ────────────────────────────────────────────

    public function enviarDocumento(Aluno $aluno, array $dados): Documento
    {
        $arquivo = $dados['arquivo'];
        $caminho = $arquivo->store("documentos/aluno_{$aluno->id}", 'private');

        return Documento::create([
            'aluno_id'               => $aluno->id,
            'solicitacao_id'         => $dados['solicitacao_id'] ?? $dados['solicitacao_estagio_id'] ?? null,
            'solicitacao_estagio_id' => $dados['solicitacao_id'] ?? $dados['solicitacao_estagio_id'] ?? null,
            'nome'                   => $dados['nome'] ?? $arquivo->getClientOriginalName(),
            'tipo'                   => $dados['tipo'],
            'caminho_arquivo'        => $caminho,
            'mime_type'              => $arquivo->getMimeType(),
            'tamanho_bytes'          => $arquivo->getSize(),
            'status'                 => 'pendente',
        ]);
    }

    public function listarDocumentos(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return Documento::with('solicitacao')
            ->where('aluno_id', $aluno->id)
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    // ────────────────────────────────────────────
    // RF11 / RF12 – Avaliações e Alertas
    // ────────────────────────────────────────────

    public function listarAvaliacoes(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return $aluno->avaliacoes()
            ->with(['coordenador.user', 'solicitacao.empresa'])
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function listarAlertas(Aluno $aluno): Collection
    {
        return $aluno->user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    public function marcarAlertaLido(Aluno $aluno, string $notificationId): void
    {
        $aluno->user->notifications()->where('id', $notificationId)->update(['read_at' => now()]);
    }

    public function marcarTodosAlertasLidos(Aluno $aluno): void
    {
        $aluno->user->unreadNotifications()->update(['read_at' => now()]);
    }

    // ────────────────────────────────────────────
    // HELPERS INTERNOS
    // ────────────────────────────────────────────

    private function recalcularCargaHoraria(Aluno $aluno): void
    {
        $total = AtividadeEstagio::where('aluno_id', $aluno->id)
            ->where(function($q) {
                $q->where('validado_supervisor', true)->orWhere('validado', true);
            })->sum('horas');

        $aluno->update(['carga_horaria_cumprida' => (int) $total]);
    }
}
