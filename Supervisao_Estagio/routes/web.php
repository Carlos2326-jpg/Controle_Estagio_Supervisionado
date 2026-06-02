<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\AtividadeController;
use App\Http\Controllers\DocumentoController;

Route::middleware(['auth'])->group(function () {

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
    Route::resource('solicitacoes', SolicitacaoController::class)->except(['edit', 'update']);

    // ==================== ATIVIDADES ====================
    Route::resource('atividades', AtividadeController::class);

    // ==================== DOCUMENTOS ====================
    Route::resource('documentos', DocumentoController::class)->except(['edit', 'update']);
});
