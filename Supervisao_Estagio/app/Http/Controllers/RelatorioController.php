<?php

namespace App\Http\Controllers;

use App\Models\Coordenador;
use App\Services\CoordenadorService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    protected $service;

    public function __construct(CoordenadorService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | RF07 – RELATÓRIO DE ALUNOS POR SITUAÇÃO
    |--------------------------------------------------------------------------
    */

    public function alunos(Request $request, Coordenador $coordenador)
    {
        $dados = $this->service->gerarRelatorio($coordenador, 'alunos', $request->only([
            'status_estagio',
            'data_inicio',
            'data_fim'
        ]));

        return response()->json($dados);
    }

    /*
    |--------------------------------------------------------------------------
    | RF08 – RELATÓRIO DE CONTRATOS ATIVOS
    |--------------------------------------------------------------------------
    */

    public function contratos(Request $request, Coordenador $coordenador)
    {
        $dados = $this->service->gerarRelatorio($coordenador, 'contratos', $request->only([
            'data_inicio',
            'data_fim'
        ]));

        return response()->json($dados);
    }

    /*
    |--------------------------------------------------------------------------
    | RF09 – RELATÓRIO DE HORAS CUMPRIDAS
    |--------------------------------------------------------------------------
    */

    public function horas(Request $request, Coordenador $coordenador)
    {
        $dados = $this->service->gerarRelatorio($coordenador, 'horas', $request->only([
            'data_inicio',
            'data_fim'
        ]));

        return response()->json($dados);
    }

    /*
    |--------------------------------------------------------------------------
    | RF10 – RELATÓRIO DE AVALIAÇÕES
    |--------------------------------------------------------------------------
    */

    public function avaliacoes(Request $request, Coordenador $coordenador)
    {
        $dados = $this->service->gerarRelatorio($coordenador, 'avaliacoes', $request->only([
            'tipo',
            'conceito',
            'data_inicio',
            'data_fim'
        ]));

        return response()->json($dados);
    }

    /*
    |--------------------------------------------------------------------------
    | RF11 – EXPORTAR RELATÓRIO EM PDF
    |--------------------------------------------------------------------------
    */

    public function exportarPdf(Request $request, Coordenador $coordenador)
    {
        $request->validate([
            'tipo' => 'required|in:alunos,contratos,horas,avaliacoes',
        ]);

        $dados = $this->service->gerarRelatorio(
            $coordenador,
            $request->tipo,
            $request->except('tipo')
        );

        $pdf = Pdf::loadView('relatorios.pdf', [
            'dados'       => $dados,
            'tipo'        => $request->tipo,
            'coordenador' => $coordenador,
            'curso'       => $coordenador->curso,
            'gerado_em'   => now()->format('d/m/Y H:i'),
        ]);

        return $pdf->download("relatorio_{$request->tipo}_{now()->format('Y-m-d')}.pdf");
    }
}
