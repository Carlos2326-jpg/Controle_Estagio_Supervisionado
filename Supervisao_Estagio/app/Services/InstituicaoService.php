<?php

namespace App\Services;

use App\Models\Coordenador;
use App\Models\Curso;
use App\Models\Instituicao;
use App\Notifications\RelatorioInstituicaoNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstituicaoService
{
    // ────────────────────────────────────────────
    // RF38 – Gerenciar Instituição (Funções Básicas)
    // ────────────────────────────────────────────

    /**
     * RF38 – Listar instituições com filtros opcionais.
     */
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Instituicao::query()
            ->when(isset($filtros['ativa']), fn($q) => $q->where('ativa', $filtros['ativa']))
            ->when(isset($filtros['cidade']),  fn($q) => $q->where('cidade', $filtros['cidade']))
            ->when(isset($filtros['estado']),  fn($q) => $q->where('estado', $filtros['estado']))
            ->when(
                isset($filtros['busca']),
                fn($q) =>
                $q->where('nome_instituicao', 'like', "%{$filtros['busca']}%")
                    ->orWhere('sigla', 'like', "%{$filtros['busca']}%")
            )
            ->orderBy('nome_instituicao')
            ->paginate(20);
    }

    /**
     * RF38 – Cadastrar nova instituição.
     * RNF03/RNF04 – Validação de CNPJ e campos obrigatórios (feita no FormRequest).
     * RNF08 – Log de auditoria na criação.
     */
    public function cadastrar(array $dados): Instituicao
    {
        return DB::transaction(function () use ($dados) {
            $dados['ativa'] = $dados['ativa'] ?? true;

            $instituicao = Instituicao::create($dados);

            Log::info("Instituição {$instituicao->id} ({$instituicao->sigla}) cadastrada.");

            return $instituicao;
        });
    }

    /**
     * RF38 – Atualizar dados cadastrais.
     * RNF08 – Log de auditoria na edição.
     */
    public function atualizar(Instituicao $instituicao, array $dados): Instituicao
    {
        DB::transaction(function () use ($instituicao, $dados) {
            $instituicao->update($dados);

            Log::info("Instituição {$instituicao->id} atualizada.");
        });

        return $instituicao->fresh();
    }

    /**
     * RF38 – Ativar ou desativar a instituição (desativação lógica – RNF15).
     * Impede desativação se houver cursos ou coordenadores ativos vinculados.
     * RNF08 – Log de auditoria.
     */
    public function toggleAtiva(Instituicao $instituicao): Instituicao
    {
        // Apenas loga os vínculos, mas não impede a desativação
        if ($instituicao->ativa) {
            $vinculosMessage = $instituicao->getVinculosMessage();
            Log::info("Desativando instituição {$instituicao->id} ({$instituicao->sigla})", [
                'vinculos' => $vinculosMessage
            ]);
        }

        $instituicao->update(['ativa' => !$instituicao->ativa]);

        $acao = $instituicao->ativa ? 'ativada' : 'desativada';
        Log::info("Instituição {$instituicao->id} {$acao}.");

        return $instituicao->fresh();
    }

    /**
     * RF38 / RF41 – Retornar ficha completa da instituição (Função de Saída).
     */
    public function detalhes(Instituicao $instituicao): Instituicao
    {
        return $instituicao->load(['cursos', 'coordenadores']);
    }

    // ────────────────────────────────────────────
    // RF39 – Vincular Cursos (Funções Fundamentais)
    // ────────────────────────────────────────────

    /**
     * RF39 – Associar curso existente à instituição,
     * atualizando a FK id_instituicao em CURSO.
     * RNF08 – Log de auditoria.
     */
    public function vincularCurso(Instituicao $instituicao, int $cursoId): Curso
    {
        $curso = Curso::findOrFail($cursoId);

        $curso->update(['id_instituicao' => $instituicao->id]);

        Log::info("Curso {$cursoId} vinculado à Instituição {$instituicao->id}.");

        return $curso->fresh();
    }

    /**
     * RF39 – Desvincular curso da instituição.
     * RNF08 – Log de auditoria.
     */
    public function desvincularCurso(Instituicao $instituicao, int $cursoId): array
    {
        $curso = Curso::where('id', $cursoId)
            ->where('id_instituicao', $instituicao->id)
            ->firstOrFail();

        $curso->update(['id_instituicao' => null]);

        Log::info("Curso {$cursoId} desvinculado da Instituição {$instituicao->id}.");

        return ['message' => 'Curso desvinculado com sucesso.'];
    }

    // ────────────────────────────────────────────
    // RF40 – Vincular Coordenadores (Funções Fundamentais)
    // ────────────────────────────────────────────

    /**
     * RF40 – Associar coordenador à instituição,
     * atualizando a FK id_instituicao em COORDENADOR.
     * RNF08 – Log de auditoria.
     */
    public function vincularCoordenador(Instituicao $instituicao, int $coordenadorId): Coordenador
    {
        $coordenador = Coordenador::findOrFail($coordenadorId);

        $coordenador->update(['id_instituicao' => $instituicao->id]);

        Log::info("Coordenador {$coordenadorId} vinculado à Instituição {$instituicao->id}.");

        return $coordenador->fresh();
    }

    /**
     * RF40 – Desvincular coordenador da instituição.
     * RNF08 – Log de auditoria.
     */
    public function desvincularCoordenador(Instituicao $instituicao, int $coordenadorId): array
    {
        $coordenador = Coordenador::where('id', $coordenadorId)
            ->where('id_instituicao', $instituicao->id)
            ->firstOrFail();

        $coordenador->update(['id_instituicao' => null]);

        Log::info("Coordenador {$coordenadorId} desvinculado da Instituição {$instituicao->id}.");

        return ['message' => 'Coordenador desvinculado com sucesso.'];
    }

    // ────────────────────────────────────────────
    // RF41 – Consultar Estrutura Acadêmica (Funções de Saída)
    // ────────────────────────────────────────────

    /**
     * RF41 – Listar todos os cursos pertencentes à instituição.
     */
    public function listarCursos(Instituicao $instituicao): \Illuminate\Database\Eloquent\Collection
    {
        return $instituicao->cursos()
            ->withCount(['alunos' => fn($q) => $q->where('ativo', true)])
            ->orderBy('nome')
            ->get();
    }

    /**
     * RF41 – Listar todos os coordenadores vinculados à instituição.
     */
    public function listarCoordenadores(Instituicao $instituicao): \Illuminate\Database\Eloquent\Collection
    {
        return $instituicao->coordenadores()
            ->with('curso')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * RF41 – Estrutura acadêmica completa: cursos + coordenadores vinculados.
     */
    public function estruturaAcademica(Instituicao $instituicao): array
    {
        $cursos = $this->listarCursos($instituicao);
        $coordenadores = $this->listarCoordenadores($instituicao);

        return [
            'instituicao'   => $instituicao->only([
                'id',
                'nome_instituicao',
                'sigla',
                'cidade',
                'estado',
                'ativa',
            ]),
            'cursos'        => $cursos,
            'coordenadores' => $coordenadores,
        ];
    }

    // ────────────────────────────────────────────
    // RF42 – Emitir Relatório Institucional (Funções de Saída)
    // ────────────────────────────────────────────

    /**
     * RF42 – Gerar relatório consolidado com dados da instituição,
     * cursos, coordenadores e estatísticas de estágios.
     */
    public function gerarRelatorio(Instituicao $instituicao): array
    {
        $cursos = $instituicao->cursos()
            ->withCount([
                'alunos as total_alunos_ativos'    => fn($q) => $q->where('ativo', true),
                'alunos as total_em_estagio'        => fn($q) => $q->where('situacao_estagio', 'em_andamento'),
                'alunos as total_estagio_concluido' => fn($q) => $q->where('situacao_estagio', 'concluido'),
            ])
            ->get();

        $coordenadores = $instituicao->coordenadores()->with('curso')->get();

        return [
            'instituicao'               => $instituicao,
            'total_cursos'              => $cursos->count(),
            'total_coordenadores'       => $coordenadores->count(),
            'total_alunos_ativos'       => $cursos->sum('total_alunos_ativos'),
            'total_em_estagio'          => $cursos->sum('total_em_estagio'),
            'total_estagio_concluido'   => $cursos->sum('total_estagio_concluido'),
            'cursos'                    => $cursos,
            'coordenadores'             => $coordenadores,
        ];
    }

    /**
     * RF42 – Exportar dados da instituição para CSV ou acionar geração de PDF.
     */
    public function exportar(Instituicao $instituicao, string $formato): StreamedResponse|\Illuminate\Http\JsonResponse
    {
        $relatorio = $this->gerarRelatorio($instituicao);

        if ($formato === 'csv') {
            return $this->exportarCsv($instituicao, $relatorio);
        }

        // Para PDF, retorna os dados e delega a renderização para a view (blade + DomPDF)
        return response()->json([
            'message'   => 'Dados prontos para geração de PDF.',
            'relatorio' => $relatorio,
        ]);
    }

    /**
     * RF42 – Montar resposta CSV para exportação.
     */
    private function exportarCsv(Instituicao $instituicao, array $relatorio): StreamedResponse
    {
        $nomeArquivo = "instituicao_{$instituicao->sigla}_" . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($relatorio) {
            $handle = fopen('php://output', 'w');

            // Cabeçalho geral
            fputcsv($handle, ['RELATÓRIO INSTITUCIONAL — ' . $relatorio['instituicao']->nome_instituicao]);
            fputcsv($handle, ['Gerado em:', now()->format('d/m/Y H:i')]);
            fputcsv($handle, []);

            // Dados da instituição
            fputcsv($handle, ['CNPJ', 'Sigla', 'Cidade', 'Estado', 'E-mail', 'Telefone']);
            fputcsv($handle, [
                $relatorio['instituicao']->cnpj,
                $relatorio['instituicao']->sigla,
                $relatorio['instituicao']->cidade,
                $relatorio['instituicao']->estado,
                $relatorio['instituicao']->email_contato ?? '-',
                $relatorio['instituicao']->telefone ?? '-',
            ]);
            fputcsv($handle, []);

            // Estatísticas
            fputcsv($handle, ['Total de Cursos', 'Total de Coordenadores', 'Alunos Ativos', 'Em Estágio', 'Estágio Concluído']);
            fputcsv($handle, [
                $relatorio['total_cursos'],
                $relatorio['total_coordenadores'],
                $relatorio['total_alunos_ativos'],
                $relatorio['total_em_estagio'],
                $relatorio['total_estagio_concluido'],
            ]);
            fputcsv($handle, []);

            // Cursos
            fputcsv($handle, ['CURSOS VINCULADOS']);
            fputcsv($handle, ['Nome', 'Alunos Ativos', 'Em Estágio', 'Concluídos']);
            foreach ($relatorio['cursos'] as $curso) {
                fputcsv($handle, [
                    $curso->nome,
                    $curso->total_alunos_ativos,
                    $curso->total_em_estagio,
                    $curso->total_estagio_concluido,
                ]);
            }

            fclose($handle);
        }, $nomeArquivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
