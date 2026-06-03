<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return view('welcome');
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
Route::prefix('cursos/{curso}/matriculas')->name('matriculas.')->group(function () {

        // RF12 – Listar alunos matriculados
        Route::get('/', [MatriculaController::class, 'index'])
            ->name('index');

        // RF13 – Buscar aluno por matrícula ou CPF
        Route::get('/buscar', [MatriculaController::class, 'buscar'])
            ->name('buscar');

        // RF14 – Histórico de estágios do aluno
        Route::get('/{aluno}/historico', [MatriculaController::class, 'historico'])
            ->name('historico');

        // RF15 – Alunos sem horas suficientes
        Route::get('/alertas/sem-horas', [MatriculaController::class, 'alunosSemHoras'])
            ->name('alertas');
    });

/*
|--------------------------------------------------------------------------
| RF07 a RF11 – RELATÓRIOS
|--------------------------------------------------------------------------
*/
Route::prefix('coordenadores/{coordenador}/relatorios')->name('relatorios.')->group(function () {

        Route::get('/alunos', [RelatorioController::class, 'alunos'])
            ->name('alunos');

        Route::get('/contratos', [RelatorioController::class, 'contratos'])
            ->name('contratos');

        Route::get('/horas', [RelatorioController::class, 'horas'])
            ->name('horas');

        Route::get('/avaliacoes', [RelatorioController::class, 'avaliacoes'])
            ->name('avaliacoes');

        Route::get('/exportar-pdf', [RelatorioController::class, 'exportarPdf'])
            ->name('pdf');
    });

/*
|--------------------------------------------------------------------------
| RF31 – GERENCIAR CURSOS
|--------------------------------------------------------------------------
*/

Route::prefix('cursos')->name('cursos.')->group(function () {

    Route::get('/', [CursoController::class, 'index'])->name('index');

    Route::get('/create', [CursoController::class, 'create'])->name('create');

    Route::post('/', [CursoController::class, 'store'])->name('store');

    Route::get('/{curso}', [CursoController::class, 'show'])->name('show');

    Route::get('/{curso}/edit', [CursoController::class, 'edit'])->name('edit');

    Route::put('/{curso}', [CursoController::class, 'update'])->name('update');

    Route::patch('/{curso}/inativar', [CursoController::class, 'inativar'])->name('inativar');
});