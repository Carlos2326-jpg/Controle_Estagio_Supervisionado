<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;

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
