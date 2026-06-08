<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\CursoController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== CONTRATOS ====================
    Route::prefix('contratos')->name('contratos.')->group(function () {
        Route::get('/', [ContratoController::class, 'index'])->name('index');
        Route::get('/{id}', [ContratoController::class, 'show'])->name('show');
        Route::post('/{id}/registrar-atividade', [ContratoController::class, 'registrarAtividade'])->name('registrar-atividade');
        Route::post('/{id}/validar-atividade/{registroId}', [ContratoController::class, 'validarAtividade'])->name('validar-atividade');
        Route::post('/{id}/encerrar', [ContratoController::class, 'encerrar'])->name('encerrar');
    });

    // ==================== AVALIAÇÕES ====================
    Route::prefix('avaliacoes')->name('avaliacoes.')->group(function () {
        Route::get('/', [AvaliacaoController::class, 'index'])->name('index');
        Route::get('/create', [AvaliacaoController::class, 'create'])->name('create');
        Route::post('/', [AvaliacaoController::class, 'store'])->name('store');
        Route::get('/{id}', [AvaliacaoController::class, 'show'])->name('show');
        Route::get('/final/{contratoId}', [AvaliacaoController::class, 'avaliacaoFinal'])->name('final');
        Route::post('/final/{contratoId}', [AvaliacaoController::class, 'storeAvaliacaoFinal'])->name('store-final');
    });

    // ==================== ALERTAS ====================
    Route::prefix('alertas')->name('alertas.')->group(function () {
        Route::get('/', [AlertaController::class, 'index'])->name('index');
        Route::get('/api/nao-lidos', [AlertaController::class, 'getNaoLidos'])->name('api.nao-lidos');
        Route::post('/{id}/marcar-lido', [AlertaController::class, 'marcarLido'])->name('marcar-lido');
        Route::post('/marcar-todos-lidos', [AlertaController::class, 'marcarTodosLidos'])->name('marcar-todos-lidos');
        Route::delete('/{id}', [AlertaController::class, 'destroy'])->name('destroy');
        Route::post('/gerar', [AlertaController::class, 'gerarAlertas'])->name('gerar')->middleware('can:admin');
        Route::post('/limpar-antigos', [AlertaController::class, 'limparAntigos'])->name('limpar-antigos')->middleware('can:admin');
    });

    // ==================== ALUNOS ====================
    Route::resource('alunos', AlunoController::class);

    // ==================== SOLICITAÇÕES ====================
    Route::resource('solicitacoes', SolicitacaoController::class)->except(['edit', 'update'])->parameters(['solicitacoes' => 'solicitacao']);

    // ==================== ATIVIDADES ====================
    Route::resource('atividades', AtividadeController::class);

    // ==================== DOCUMENTOS ====================
    Route::resource('documentos', DocumentoController::class)->except(['edit', 'update']);

});

// ==================== EMPRESAS ====================
Route::prefix('empresas')->name('empresas.')->group(function () {
    Route::get('/', [EmpresaController::class, 'index'])->name('index');
    Route::get('/criar', [EmpresaController::class, 'create'])->name('create');
    Route::post('/', [EmpresaController::class, 'store'])->name('store');
    Route::get('/{empresa}', [EmpresaController::class, 'show'])->name('show');
    Route::get('/{empresa}/editar', [EmpresaController::class, 'edit'])->name('edit');
    Route::put('/{empresa}', [EmpresaController::class, 'update'])->name('update');
    Route::patch('/{empresa}/desativar', [EmpresaController::class, 'desativar'])->name('desativar');
    Route::patch('/{empresa}/reativar', [EmpresaController::class, 'reativar'])->name('reativar');
    Route::get('/{empresa}/convenios', [EmpresaController::class, 'convenios'])->name('convenios');
    Route::get('/{empresa}/convenios/criar', [EmpresaController::class, 'convenioCreate'])->name('convenios.create');
    Route::post('/{empresa}/convenios', [EmpresaController::class, 'convenioStore'])->name('convenios.store');
    Route::get('/{empresa}/convenios/{convenio}/editar', [EmpresaController::class, 'convenioEdit'])->name('convenios.edit');
    Route::put('/{empresa}/convenios/{convenio}', [EmpresaController::class, 'convenioUpdate'])->name('convenios.update');
    Route::get('/{empresa}/supervisores', [EmpresaController::class, 'supervisores'])->name('supervisores');
    Route::get('/{empresa}/supervisores/criar', [EmpresaController::class, 'supervisorCreate'])->name('supervisores.create');
    Route::post('/{empresa}/supervisores', [EmpresaController::class, 'supervisorStore'])->name('supervisores.store');
    Route::get('/{empresa}/supervisores/{supervisor}/editar', [EmpresaController::class, 'supervisorEdit'])->name('supervisores.edit');
    Route::put('/{empresa}/supervisores/{supervisor}', [EmpresaController::class, 'supervisorUpdate'])->name('supervisores.update');
    Route::patch('/{empresa}/supervisores/{supervisor}/desativar', [EmpresaController::class, 'supervisorDesativar'])->name('supervisores.desativar');
    Route::get('/{empresa}/solicitacoes', [EmpresaController::class, 'solicitacoes'])->name('solicitacoes');
    Route::get('/{empresa}/solicitacoes/{solicitacao}/contrato', [EmpresaController::class, 'contrato'])->name('contrato');
    Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes', [EmpresaController::class, 'avaliacoes'])->name('supervisores.avaliacoes');
    Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}/criar', [EmpresaController::class, 'avaliacaoCreate'])->name('supervisores.avaliacoes.create');
    Route::post('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}', [EmpresaController::class, 'avaliacaoStore'])->name('supervisores.avaliacoes.store');
    Route::get('/{empresa}/estagiarios', [EmpresaController::class, 'estagiarios'])->name('estagiarios');
});

// ==================== COORDENADORES ====================
Route::prefix('coordenadores')->group(function () {
    Route::get('/', [CoordenadorController::class, 'index']);

    Route::get('/criar', [CoordenadorController::class, 'create']);
    Route::post('/', [CoordenadorController::class, 'store']);

    Route::get('/{coordenador}/editar', [CoordenadorController::class, 'edit']);
    Route::put('/{coordenador}', [CoordenadorController::class, 'update']);
    
    Route::patch('/{coordenador}/inativar', [CoordenadorController::class, 'inativar']);
    Route::get('/{coordenador}/informacoes-academicas', [CoordenadorController::class, 'informacoesAcademicas']);
    Route::get('/{coordenador}/solicitacoes', [CoordenadorController::class, 'listarSolicitacoes']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/aprovar', [CoordenadorController::class, 'aprovarSolicitacao']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/reprovar', [CoordenadorController::class, 'reprovarSolicitacao']);
    Route::get('/{coordenador}/solicitacoes/historico', [CoordenadorController::class, 'historicoAnalises']);
    Route::get('/{coordenador}/documentos', [CoordenadorController::class, 'listarDocumentos']);
    Route::patch('/{coordenador}/documentos/{documento}/aprovar', [CoordenadorController::class, 'aprovarDocumento']);
    Route::patch('/{coordenador}/documentos/{documento}/reprovar', [CoordenadorController::class, 'reprovarDocumento']);
    Route::get('/{coordenador}/atividades', [CoordenadorController::class, 'acompanharAtividades']);
    Route::get('/{coordenador}/pendencias', [CoordenadorController::class, 'pendencias']);
    Route::get('/{coordenador}/avaliacoes', [CoordenadorController::class, 'listarAvaliacoes']);
    Route::post('/{coordenador}/avaliacoes/{solicitacao}', [CoordenadorController::class, 'registrarAvaliacao']);
    Route::put('/avaliacoes/{avaliacao}', [CoordenadorController::class, 'atualizarAvaliacao']);
    Route::get('/{coordenador}/alertas', [CoordenadorController::class, 'alertas']);
    Route::patch('/{coordenador}/alertas/lido', [CoordenadorController::class, 'marcarAlertaLido']);
    Route::get('/{coordenador}/relatorios', [CoordenadorController::class, 'gerarRelatorio']);
    Route::post('/{id}/vincular-curso', [CoordenadorController::class, 'vincularCurso']);
});

Route::prefix('cursos/{curso}/matriculas')->group(function () {
    Route::get('/', [MatriculaController::class, 'index']);
    Route::get('/buscar', [MatriculaController::class, 'buscar']);
    Route::get('/{aluno}/historico', [MatriculaController::class, 'historico']);
    Route::get('/alertas/sem-horas', [MatriculaController::class, 'alunosSemHoras']);
});

Route::prefix('coordenadores/{coordenador}/relatorios')->group(function () {
    Route::get('/alunos', [RelatorioController::class, 'alunos']);
    Route::get('/contratos', [RelatorioController::class, 'contratos']);
    Route::get('/horas', [RelatorioController::class, 'horas']);
    Route::get('/avaliacoes', [RelatorioController::class, 'avaliacoes']);
    Route::get('/exportar-pdf', [RelatorioController::class, 'exportarPdf']);
});
Route::prefix('cursos')->name('cursos.')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('index');
    Route::get('/create', [CursoController::class, 'create'])->name('create');
    Route::post('/', [CursoController::class, 'store'])->name('store');
    Route::get('/{curso}', [CursoController::class, 'show'])->name('show');
    Route::get('/{curso}/edit', [CursoController::class, 'edit'])->name('edit');
    Route::put('/{curso}', [CursoController::class, 'update'])->name('update');
    Route::patch('/{curso}/inativar', [CursoController::class, 'inativar'])->name('inativar');
});

require __DIR__.'/auth.php';
