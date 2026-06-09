<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AtividadeEstagio;
use App\Models\Contrato;
use App\Models\Documento;
use App\Models\SolicitacaoEstagio;
use App\Models\User;
use App\Notifications\AlertaAlunoNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AlunoService
{
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        $query = Aluno::with(['user', 'curso'])
            ->when(isset($filtros['curso_id']), fn($q) => $q->where('curso_id', $filtros['curso_id']))
            ->when(isset($filtros['situacao']), fn($q) => $q->where('situacao_estagio', $filtros['situacao']))
            ->when(isset($filtros['ativo']), fn($q) => $q->where('ativo', $filtros['ativo']))
            ->when(isset($filtros['busca']), function ($q) use ($filtros) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', "%{$filtros['busca']}%")
                        ->orWhere('email', 'like', "%{$filtros['busca']}%")
                )->orWhere('matricula', 'like', "%{$filtros['busca']}%");
            });

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function cadastrar(array $dados): Aluno
    {
        return DB::transaction(function () use ($dados) {
            $user = User::create([
                'name'     => $dados['nome'],
                'email'    => $dados['email'],
                'password' => Hash::make($dados['password']),
            ]);

            $user->assignRole('aluno');

            $aluno = Aluno::create([
                'user_id'         => $user->id,
                'curso_id'        => $dados['curso_id'],
                'matricula'       => $dados['matricula'],
                'cpf'             => $dados['cpf'],
                'telefone'        => $dados['telefone'] ?? null,
                'data_nascimento' => $dados['data_nascimento'] ?? null,
                'endereco'        => $dados['endereco'] ?? null,
                'situacao_estagio'       => 'sem_estagio',
                'carga_horaria_cumprida' => 0,
                'ativo'           => true,
            ]);

            Log::info("Aluno {$aluno->id} cadastrado.");

            return $aluno;
        });
    }

    public function atualizar(Aluno $aluno, array $dados): Aluno
    {
        DB::transaction(function () use ($aluno, $dados) {
            if (isset($dados['nome']) || isset($dados['email'])) {
                $aluno->user->update([
                    'name'  => $dados['nome'] ?? $aluno->user->name,
                    'email' => $dados['email'] ?? $aluno->user->email,
                ]);
            }

            $aluno->update([
                'telefone'        => $dados['telefone'] ?? $aluno->telefone,
                'data_nascimento' => $dados['data_nascimento'] ?? $aluno->data_nascimento,
                'endereco'        => $dados['endereco'] ?? $aluno->endereco,
            ]);
        });

        return $aluno->fresh(['user', 'curso']);
    }

    public function inativar(Aluno $aluno): void
    {
        $aluno->update(['ativo' => false]);
        Log::info("Aluno {$aluno->id} inativado.");
    }

    public function consultarSituacao(Aluno $aluno): array
    {
        $estagioAtivo = $aluno->estagioAtivo();
        $cargaObrigatoria = $aluno->curso->carga_horaria_estagio ?? 0;

        return [
            'situacao'               => $aluno->situacao_estagio,
            'situacao_label'         => $aluno->situacao_label,
            'carga_horaria_cumprida' => $aluno->carga_horaria_cumprida,
            'carga_horaria_total'    => $cargaObrigatoria,
            'percentual_cumprido'    => $aluno->percentual_horas,
            'estagio_ativo'          => $estagioAtivo ? $estagioAtivo->load(['empresa', 'supervisor', 'contrato']) : null,
        ];
    }

    /**
     * RF03 – Solicitar Estágio (UC36)
     */
    public function solicitarEstagio(Aluno $aluno, array $dados): SolicitacaoEstagio
    {
        // NEG-01: Verifica se já possui solicitação pendente
        if ($aluno->temSolicitacaoPendente()) {
            abort(422, 'Você já possui uma solicitação de estágio pendente.');
        }

        // NEG-04: Verifica se o aluno já está em estágio
        if ($aluno->situacao_estagio === 'em_andamento') {
            abort(422, 'Você já possui um estágio em andamento.');
        }

        // UC36: Verifica se a empresa possui convênio ativo
        $empresa = \App\Models\Empresa::findOrFail($dados['empresa_id']);
        if (!$empresa->possuiConvenioAtivo()) {
            abort(422, 'A empresa selecionada não possui convênio ativo com a instituição.');
        }

        // UC36: Verifica se o supervisor pertence à empresa
        $supervisor = \App\Models\Supervisor::findOrFail($dados['supervisor_id']);
        if ($supervisor->empresa_id != $empresa->id) {
            abort(422, 'O supervisor informado não pertence à empresa selecionada.');
        }

        // UC36: Verifica carga horária semanal dentro do limite
        if ($dados['carga_horaria_semanal'] > 30) {
            abort(422, 'A carga horária semanal não pode exceder 30 horas para estagiários.');
        }

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

        // Disparar notificação para o coordenador
        $coordenadores = \App\Models\Coordenador::where('curso_id', $aluno->curso_id)->get();
        foreach ($coordenadores as $coordenador) {
            $coordenador->user->notify(new \App\Notifications\AlertaCoordenadorNotification(
                "Nova solicitação de estágio de {$aluno->user->name} aguardando análise."
            ));
        }

        Log::info("Solicitação {$solicitacao->id} criada pelo aluno {$aluno->id}.");

        return $solicitacao->load(['empresa', 'supervisor']);
    }

    public function listarSolicitacoes(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return SolicitacaoEstagio::with(['empresa', 'supervisor', 'historicoAnalises'])
            ->where('aluno_id', $aluno->id)
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    public function cancelarSolicitacao(Aluno $aluno, SolicitacaoEstagio $solicitacao): void
    {
        if ($solicitacao->aluno_id !== $aluno->id) {
            abort(403, 'Esta solicitação não pertence ao aluno informado.');
        }

        if (!$solicitacao->isPendente()) {
            abort(422, 'Apenas solicitações com status pendente podem ser canceladas.');
        }

        $solicitacao->update(['status' => 'cancelada']);

        Log::info("Solicitação {$solicitacao->id} cancelada pelo aluno {$aluno->id}.");
    }

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
        if ($contrato->aluno_id !== $aluno->id) {
            abort(403, 'Este contrato não pertence ao aluno informado.');
        }

        return $contrato->load(['empresa', 'supervisor', 'solicitacao']);
    }

    public function registrarAtividade(Aluno $aluno, array $dados): AtividadeEstagio
    {
        $solicitacao = SolicitacaoEstagio::findOrFail($dados['solicitacao_estagio_id']);

        if ($solicitacao->aluno_id !== $aluno->id) {
            abort(403, 'Esta solicitação não pertence ao aluno informado.');
        }

        if (!$solicitacao->isAprovada()) {
            abort(422, 'Só é possível registrar atividades em estágios aprovados.');
        }

        // VAL-04: Valida data dentro do período do contrato
        $data = \Carbon\Carbon::parse($dados['data']);
        if ($data->lt($solicitacao->data_inicio_prevista) || $data->gt($solicitacao->data_fim_prevista)) {
            abort(422, 'A data da atividade deve estar dentro do período de vigência do estágio.');
        }

        // NEG-07: Valida carga horária semanal
        $inicioSemana = $data->copy()->startOfWeek();
        $fimSemana = $data->copy()->endOfWeek();
        $horasSemanaAtual = AtividadeEstagio::where('solicitacao_estagio_id', $solicitacao->id)
            ->whereBetween('data', [$inicioSemana, $fimSemana])
            ->sum('horas');

        if (($horasSemanaAtual + $dados['horas']) > $solicitacao->carga_horaria_semanal) {
            abort(422, "A carga horária semanal não pode exceder {$solicitacao->carga_horaria_semanal} horas.");
        }

        $atividade = AtividadeEstagio::create([
            'aluno_id'               => $aluno->id,
            'solicitacao_estagio_id' => $solicitacao->id,
            'data'                   => $dados['data'],
            'descricao'              => $dados['descricao'],
            'horas'                  => $dados['horas'],
            'validado_supervisor'    => false,
        ]);

        $this->recalcularCargaHoraria($aluno);

        return $atividade;
    }

    public function atualizarAtividade(Aluno $aluno, AtividadeEstagio $atividade, array $dados): AtividadeEstagio
    {
        if ($atividade->aluno_id !== $aluno->id) {
            abort(403, 'Este registro não pertence ao aluno informado.');
        }

        if (!$atividade->podeEditar()) {
            abort(422, 'Este registro já foi validado pelo supervisor e não pode ser editado.');
        }

        // VAL-03: Substitui array_filter por verificação explícita
        $updateData = [];
        if (isset($dados['data'])) $updateData['data'] = $dados['data'];
        if (isset($dados['descricao'])) $updateData['descricao'] = $dados['descricao'];
        if (isset($dados['horas'])) $updateData['horas'] = $dados['horas'];

        $atividade->update($updateData);

        $this->recalcularCargaHoraria($aluno);

        return $atividade->fresh();
    }

    public function excluirAtividade(Aluno $aluno, AtividadeEstagio $atividade): void
    {
        if ($atividade->aluno_id !== $aluno->id) {
            abort(403, 'Este registro não pertence ao aluno informado.');
        }

        if (!$atividade->podeEditar()) {
            abort(422, 'Este registro já foi validado pelo supervisor e não pode ser excluído.');
        }

        $atividade->delete();

        $this->recalcularCargaHoraria($aluno);

        Log::info("Atividade {$atividade->id} excluída pelo aluno {$aluno->id}.");
    }

    public function listarAtividades(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return AtividadeEstagio::with('solicitacao')
            ->where('aluno_id', $aluno->id)
            ->when(
                isset($filtros['solicitacao_id']),
                fn($q) =>
                $q->where('solicitacao_estagio_id', $filtros['solicitacao_id'])
            )
            ->when(
                isset($filtros['validado']),
                fn($q) =>
                $q->where('validado_supervisor', filter_var($filtros['validado'], FILTER_VALIDATE_BOOLEAN))
            )
            ->when(
                isset($filtros['data_inicio']),
                fn($q) =>
                $q->whereDate('data', '>=', $filtros['data_inicio'])
            )
            ->when(
                isset($filtros['data_fim']),
                fn($q) =>
                $q->whereDate('data', '<=', $filtros['data_fim'])
            )
            ->orderBy('data', 'desc')
            ->paginate(20);
    }

    public function enviarDocumento(Aluno $aluno, array $dados): Documento
    {
        $arquivo = $dados['arquivo'];

        // Validar MIME type real (não apenas extensão)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $arquivo->getRealPath());
        finfo_close($finfo);

        $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Exception('Tipo de arquivo não permitido. Envie PDF, JPG ou PNG.');
        }

        // Sanitizar nome do arquivo
        $originalName = pathinfo($arquivo->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = preg_replace('/[^a-zA-Z0-9áéíóúãõç\s-]/u', '', $originalName);
        $sanitizedName = str_replace(' ', '_', $sanitizedName);
        $extension = $arquivo->getClientOriginalExtension();
        $fileName = $sanitizedName . '_' . time() . '.' . $extension;

        // Armazenar fora da pasta pública
        $caminho = $arquivo->storeAs(
            "documentos/aluno_{$aluno->id}",
            $fileName,
            'private' // Disco privado, fora do public
        );

        if (!$caminho) {
            throw new \Exception('Erro ao armazenar o arquivo.');
        }

        $documento = Documento::create([
            'aluno_id' => $aluno->id,
            'solicitacao_estagio_id' => $dados['solicitacao_estagio_id'] ?? null,
            'nome' => $dados['nome'] ?? $originalName,
            'tipo' => $dados['tipo'],
            'caminho_arquivo' => $caminho,
            'mime_type' => $mimeType,
            'tamanho_bytes' => $arquivo->getSize(),
            'status' => 'pendente',
        ]);

        Log::info("Documento {$documento->id} enviado pelo aluno {$aluno->id}", [
            'tamanho' => $arquivo->getSize(),
            'mime_type' => $mimeType,
            'nome_original' => $originalName
        ]);

        return $documento;
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

    public function listarAvaliacoes(Aluno $aluno, array $filtros = []): LengthAwarePaginator
    {
        return $aluno->avaliacoes()
            ->with(['coordenador.user', 'solicitacao.empresa'])
            ->when(isset($filtros['tipo']), fn($q) => $q->where('tipo', $filtros['tipo']))
            ->when(isset($filtros['conceito']), fn($q) => $q->where('conceito', $filtros['conceito']))
            ->orderBy('data_avaliacao', 'desc')
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
        $aluno->user->notifications()
            ->where('id', $notificationId)
            ->update(['read_at' => now()]);
    }

    public function marcarTodosAlertasLidos(Aluno $aluno): void
    {
        $aluno->user->unreadNotifications()->update(['read_at' => now()]);
    }

    private function recalcularCargaHoraria(Aluno $aluno): void
    {
        $total = AtividadeEstagio::where('aluno_id', $aluno->id)
            ->where('validado_supervisor', true)
            ->sum('horas');

        $aluno->update(['carga_horaria_cumprida' => (int) $total]);
    }
}
