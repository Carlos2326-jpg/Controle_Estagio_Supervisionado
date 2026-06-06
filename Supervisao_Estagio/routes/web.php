<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return redirect('/empresas');
});

/*
|--------------------------------------------------------------------------
| Rotas – Módulo Empresas (RF24–RF30)
|--------------------------------------------------------------------------
*/
Route::prefix('empresas')->name('empresas.')->group(function () {

    // RF24 – Gerenciar Empresas
    Route::get('/',              [EmpresaController::class, 'index'])->name('index');
    Route::get('/criar',         [EmpresaController::class, 'create'])->name('create');
    Route::post('/',             [EmpresaController::class, 'store'])->name('store');
    Route::get('/{empresa}',     [EmpresaController::class, 'show'])->name('show');
    Route::get('/{empresa}/editar',  [EmpresaController::class, 'edit'])->name('edit');
    Route::put('/{empresa}',     [EmpresaController::class, 'update'])->name('update');
    Route::patch('/{empresa}/desativar', [EmpresaController::class, 'desativar'])->name('desativar');
    Route::patch('/{empresa}/reativar',  [EmpresaController::class, 'reativar'])->name('reativar');

    // RF25 – Gerenciar Convênios
    Route::get('/{empresa}/convenios',                   [EmpresaController::class, 'convenios'])->name('convenios');
    Route::get('/{empresa}/convenios/criar',             [EmpresaController::class, 'convenioCreate'])->name('convenios.create');
    Route::post('/{empresa}/convenios',                  [EmpresaController::class, 'convenioStore'])->name('convenios.store');
    Route::get('/{empresa}/convenios/{convenio}/editar', [EmpresaController::class, 'convenioEdit'])->name('convenios.edit');
    Route::put('/{empresa}/convenios/{convenio}',        [EmpresaController::class, 'convenioUpdate'])->name('convenios.update');

    // RF26 – Gerenciar Supervisores
    Route::get('/{empresa}/supervisores',                    [EmpresaController::class, 'supervisores'])->name('supervisores');
    Route::get('/{empresa}/supervisores/criar',              [EmpresaController::class, 'supervisorCreate'])->name('supervisores.create');
    Route::post('/{empresa}/supervisores',                   [EmpresaController::class, 'supervisorStore'])->name('supervisores.store');
    Route::get('/{empresa}/supervisores/{supervisor}/editar',[EmpresaController::class, 'supervisorEdit'])->name('supervisores.edit');
    Route::put('/{empresa}/supervisores/{supervisor}',       [EmpresaController::class, 'supervisorUpdate'])->name('supervisores.update');
    Route::patch('/{empresa}/supervisores/{supervisor}/desativar', [EmpresaController::class, 'supervisorDesativar'])->name('supervisores.desativar');

    // RF27 – Receber Solicitações de Estágio
    Route::get('/{empresa}/solicitacoes', [EmpresaController::class, 'solicitacoes'])->name('solicitacoes');

    // RF28 – Formalização do Contrato
    Route::get('/{empresa}/solicitacoes/{solicitacao}/contrato', [EmpresaController::class, 'contrato'])->name('contrato');

    // RF29 – Avaliar Estagiários
    Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes',                    [EmpresaController::class, 'avaliacoes'])->name('supervisores.avaliacoes');
    Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}/criar', [EmpresaController::class, 'avaliacaoCreate'])->name('supervisores.avaliacoes.create');
    Route::post('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}',      [EmpresaController::class, 'avaliacaoStore'])->name('supervisores.avaliacoes.store');

    // RF30 – Consultar Estagiários Vinculados
    Route::get('/{empresa}/estagiarios', [EmpresaController::class, 'estagiarios'])->name('estagiarios');
});
/*
|--------------------------------------------------------------------------
| RF01, RF02, RF03 – GERENCIAR COORDENADORES
|--------------------------------------------------------------------------
*/
Route::prefix('coordenadores')->group(function () {
    Route::get('/', [CoordenadorController::class, 'index']);

    Route::get('/criar', [CoordenadorController::class, 'create']);
    Route::post('/', [CoordenadorController::class, 'store']);

    Route::get('/{coordenador}/editar', [CoordenadorController::class, 'edit']);
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