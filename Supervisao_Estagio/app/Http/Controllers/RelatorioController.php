<?php
// app/Http/Controllers/RelatorioController.php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aluno;
use App\Models\SolicitacaoEstagio;
use App\Models\Contrato;
use App\Models\Avaliacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RelatorioController extends Controller
{
    /**
     * Gerar relatório de alunos por curso
     */
    public function alunos(Curso $curso, Request $request)
    {
        $this->authorize('viewAny', Aluno::class);
        
        $alunos = Aluno::with(['user'])
            ->where('curso_id', $curso->id)
            ->when($request->situacao, fn($q) => $q->where('situacao_estagio', $request->situacao))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('relatorios.alunos', compact('curso', 'alunos'));
    }
    
    /**
     * Gerar relatório de contratos ativos
     */
    public function contratos(Request $request)
    {
        $this->authorize('viewAny', Contrato::class);
        
        $contratos = Contrato::with(['aluno.user', 'empresa'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->data_inicio, fn($q) => $q->whereDate('data_inicio', '>=', $request->data_inicio))
            ->when($request->data_fim, fn($q) => $q->whereDate('data_fim', '<=', $request->data_fim))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('relatorios.contratos', compact('contratos'));
    }
    
    /**
     * Exportar relatório em CSV
     */
    public function exportar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:alunos,contratos,horas,avaliacoes',
            'formato' => 'required|in:csv,pdf'
        ]);
        
        // Lógica de exportação
        $dados = $this->gerarDadosRelatorio($request->tipo, $request->all());
        
        if ($request->formato === 'csv') {
            return $this->exportarCsv($dados, $request->tipo);
        }
        
        return $this->exportarPdf($dados, $request->tipo);
    }
    
    private function gerarDadosRelatorio(string $tipo, array $filtros): array
    {
        return match($tipo) {
            'alunos' => $this->dadosAlunos($filtros),
            'contratos' => $this->dadosContratos($filtros),
            'horas' => $this->dadosHoras($filtros),
            'avaliacoes' => $this->dadosAvaliacoes($filtros),
            default => []
        };
    }
    
    private function dadosAlunos(array $filtros): array
    {
        return Aluno::with(['user', 'curso'])
            ->when(isset($filtros['curso_id']), fn($q) => $q->where('curso_id', $filtros['curso_id']))
            ->get()
            ->toArray();
    }
    
    private function dadosContratos(array $filtros): array
    {
        return Contrato::with(['aluno.user', 'empresa'])
            ->when(isset($filtros['status']), fn($q) => $q->where('status', $filtros['status']))
            ->get()
            ->toArray();
    }
    
    private function dadosHoras(array $filtros): array
    {
        return SolicitacaoEstagio::with(['aluno.user', 'empresa'])
            ->where('status', 'aprovada')
            ->withSum('atividades', 'horas')
            ->get()
            ->map(fn($e) => [
                'aluno' => $e->aluno->user->name,
                'matricula' => $e->aluno->matricula,
                'empresa' => $e->empresa->razao_social,
                'horas_previstas' => $e->carga_horaria_total,
                'horas_cumpridas' => $e->atividades_sum_horas ?? 0,
                'percentual' => $e->carga_horaria_total > 0 
                    ? round((($e->atividades_sum_horas ?? 0) / $e->carga_horaria_total) * 100, 2)
                    : 0
            ])
            ->toArray();
    }
    
    private function dadosAvaliacoes(array $filtros): array
    {
        return Avaliacao::with(['aluno.user', 'coordenador.user'])
            ->when(isset($filtros['coordenador_id']), fn($q) => $q->where('coordenador_id', $filtros['coordenador_id']))
            ->get()
            ->toArray();
    }
    
    private function exportarCsv(array $dados, string $tipo)
    {
        $fileName = "relatorio_{$tipo}_" . now()->format('Ymd_His') . '.csv';
        
        return response()->streamDownload(function() use ($dados, $tipo) {
            $handle = fopen('php://output', 'w');
            
            // Cabeçalhos baseados no tipo
            $headers = $this->getCsvHeaders($tipo);
            fputcsv($handle, $headers, ';');
            
            // Dados
            foreach ($dados as $row) {
                $line = $this->formatCsvRow($row, $tipo);
                fputcsv($handle, $line, ';');
            }
            
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
    
    private function getCsvHeaders(string $tipo): array
    {
        return match($tipo) {
            'alunos' => ['Nome', 'E-mail', 'Matrícula', 'Curso', 'Situação Estágio', 'Horas Cumpridas'],
            'contratos' => ['Aluno', 'Empresa', 'Data Início', 'Data Fim', 'Status'],
            'horas' => ['Aluno', 'Matrícula', 'Empresa', 'Horas Previstas', 'Horas Cumpridas', 'Percentual'],
            'avaliacoes' => ['Aluno', 'Coordenador', 'Tipo', 'Nota', 'Conceito', 'Data'],
            default => []
        };
    }
    
    private function formatCsvRow(array $row, string $tipo): array
    {
        return match($tipo) {
            'alunos' => [
                $row['user']['name'] ?? '-',
                $row['user']['email'] ?? '-',
                $row['matricula'] ?? '-',
                $row['curso']['nome'] ?? '-',
                $row['situacao_estagio'] ?? '-',
                $row['carga_horaria_cumprida'] ?? 0
            ],
            'contratos' => [
                $row['aluno']['user']['name'] ?? '-',
                $row['empresa']['razao_social'] ?? '-',
                $row['data_inicio'] ?? '-',
                $row['data_fim'] ?? '-',
                $row['status'] ?? '-'
            ],
            'horas' => [
                $row['aluno'] ?? '-',
                $row['matricula'] ?? '-',
                $row['empresa'] ?? '-',
                $row['horas_previstas'] ?? 0,
                $row['horas_cumpridas'] ?? 0,
                $row['percentual'] ?? 0 . '%'
            ],
            'avaliacoes' => [
                $row['aluno']['user']['name'] ?? '-',
                $row['coordenador']['user']['name'] ?? '-',
                $row['tipo'] ?? '-',
                $row['nota'] ?? '-',
                $row['conceito'] ?? '-',
                $row['data_avaliacao'] ?? '-'
            ],
            default => []
        };
    }
    
    private function exportarPdf(array $dados, string $tipo)
    {
        // Implementar com barryvdh/laravel-dompdf ou similar
        return response()->json([
            'message' => 'Funcionalidade de PDF será implementada',
            'data' => $dados
        ]);
    }
}