<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\RelatorioController;

Route::get('/', function () {
    return view('welcome');
});
/*
|--------------------------------------------------------------------------
| RF01 – GERENCIAR DADOS DO ALUNO
|--------------------------------------------------------------------------
*/
Route::prefix('alunos')->group(function () {
    Route::get('/', [AlunoController::class, 'index']);
    Route::post('/', [AlunoController::class, 'store']);
    Route::get('/{aluno}', [AlunoController::class, 'show']);
    Route::put('/{aluno}', [AlunoController::class, 'update']);
    Route::patch('/{aluno}/inativar', [AlunoController::class, 'inativar']);

    /*
    |--------------------------------------------------------------------------
    | RF02 – CONSULTAR SITUAÇÃO DE ESTÁGIO
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/situacao-estagio', [AlunoController::class, 'situacaoEstagio']);

    /*
    |--------------------------------------------------------------------------
    | RF03 – SOLICITAR ESTÁGIO
    |--------------------------------------------------------------------------
    */
    Route::post('/{aluno}/solicitacoes', [AlunoController::class, 'solicitarEstagio']);
});
/*
|--------------------------------------------------------------------------
| RF01, RF02, RF03 – GERENCIAR COORDENADORES
|--------------------------------------------------------------------------
*/
Route::prefix('coordenadores')->group(function () {
    Route::get('/', [CoordenadorController::class, 'index']);
    Route::post('/', [CoordenadorController::class, 'store']);
    Route::put('/{coordenador}', [CoordenadorController::class, 'update']);
    Route::patch('/{coordenador}/inativar', [CoordenadorController::class, 'inativar']);

    // RF14 – Informações acadêmicas
    Route::get('/{coordenador}/informacoes-academicas', [CoordenadorController::class, 'informacoesAcademicas']);

    // RF04, RF05 – Solicitações
    Route::get('/{coordenador}/solicitacoes', [CoordenadorController::class, 'listarSolicitacoes']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/aprovar', [CoordenadorController::class, 'aprovarSolicitacao']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/reprovar', [CoordenadorController::class, 'reprovarSolicitacao']);
    Route::get('/{coordenador}/solicitacoes/historico', [CoordenadorController::class, 'historicoAnalises']);

    // RF17 – Documentos
    Route::get('/{coordenador}/documentos', [CoordenadorController::class, 'listarDocumentos']);
    Route::patch('/{coordenador}/documentos/{documento}/aprovar', [CoordenadorController::class, 'aprovarDocumento']);
    Route::patch('/{coordenador}/documentos/{documento}/reprovar', [CoordenadorController::class, 'reprovarDocumento']);

    // RF18 – Atividades
    Route::get('/{coordenador}/atividades', [CoordenadorController::class, 'acompanharAtividades']);

    // RF19 – Pendências
    Route::get('/{coordenador}/pendencias', [CoordenadorController::class, 'pendencias']);

    // RF20 – Avaliações
    Route::get('/{coordenador}/avaliacoes', [CoordenadorController::class, 'listarAvaliacoes']);
    Route::post('/{coordenador}/avaliacoes/{solicitacao}', [CoordenadorController::class, 'registrarAvaliacao']);
    Route::put('/avaliacoes/{avaliacao}', [CoordenadorController::class, 'atualizarAvaliacao']);

    // RF21 – Alertas
    Route::get('/{coordenador}/alertas', [CoordenadorController::class, 'alertas']);
    Route::patch('/{coordenador}/alertas/lido', [CoordenadorController::class, 'marcarAlertaLido']);

    // RF22 – Relatórios
    Route::get('/{coordenador}/relatorios', [CoordenadorController::class, 'gerarRelatorio']);

    // Mantido da outra dupla
    Route::post('/{id}/vincular-curso', [CoordenadorController::class, 'vincularCurso']);
});

/*
|--------------------------------------------------------------------------
| RF12 a RF15 – MATRÍCULAS DO CURSO
|--------------------------------------------------------------------------
*/
Route::prefix('cursos/{curso}/matriculas')->group(function () {
    // RF12 – Listar alunos matriculados
    Route::get('/', [MatriculaController::class, 'index']);

    // RF13 – Buscar aluno por matrícula ou CPF
    Route::get('/buscar', [MatriculaController::class, 'buscar']);

    // RF14 – Histórico de estágios do aluno
    Route::get('/{aluno}/historico', [MatriculaController::class, 'historico']);

    // RF15 – Alunos próximos do prazo sem carga horária
    Route::get('/alertas/sem-horas', [MatriculaController::class, 'alunosSemHoras']);
});

/*
|--------------------------------------------------------------------------
| RF07 a RF11 – RELATÓRIOS
|--------------------------------------------------------------------------
*/
Route::prefix('coordenadores/{coordenador}/relatorios')->group(function () {
    // RF07 – Relatório de alunos por situação
    Route::get('/alunos', [RelatorioController::class, 'alunos']);

    // RF08 – Relatório de contratos ativos
    Route::get('/contratos', [RelatorioController::class, 'contratos']);

    // RF09 – Relatório de horas cumpridas
    Route::get('/horas', [RelatorioController::class, 'horas']);

    // RF10 – Relatório de avaliações
    Route::get('/avaliacoes', [RelatorioController::class, 'avaliacoes']);

    // RF11 – Exportar relatório em PDF
    Route::get('/exportar-pdf', [RelatorioController::class, 'exportarPdf']);
});

