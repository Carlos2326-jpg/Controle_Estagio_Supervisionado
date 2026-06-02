<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;

/*
|--------------------------------------------------------------------------
| RF38 – GERENCIAR INSTITUIÇÃO (Funções Básicas)
| RNF01/RNF02 – Operações restritas ao perfil ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('instituicoes')->middleware(['auth', 'role:admin'])->group(function () {

    // RF38 – Listar / consultar instituições (com filtros: ativa, cidade, estado, busca)
    Route::get('/', [InstituicaoController::class, 'index']);

    // RF38 – Cadastrar nova instituição
    Route::post('/', [InstituicaoController::class, 'store']);

    // RF38 – Exibir ficha completa (Função de Saída)
    Route::get('/{instituicao}', [InstituicaoController::class, 'show']);

    // RF38 – Atualizar dados cadastrais
    Route::put('/{instituicao}', [InstituicaoController::class, 'update']);

    // RF38 – Ativar / desativar instituição (toggle — RNF15 desativação lógica)
    Route::patch('/{instituicao}/toggle-ativa', [InstituicaoController::class, 'toggleAtiva']);

    /*
    |--------------------------------------------------------------------------
    | RF39 – VINCULAR / DESVINCULAR CURSOS (Funções Fundamentais)
    |--------------------------------------------------------------------------
    */
    Route::post('/{instituicao}/cursos/vincular',          [InstituicaoController::class, 'vincularCurso']);
    Route::patch('/{instituicao}/cursos/{cursoId}/desvincular', [InstituicaoController::class, 'desvincularCurso']);

    /*
    |--------------------------------------------------------------------------
    | RF40 – VINCULAR / DESVINCULAR COORDENADORES (Funções Fundamentais)
    |--------------------------------------------------------------------------
    */
    Route::post('/{instituicao}/coordenadores/vincular',                    [InstituicaoController::class, 'vincularCoordenador']);
    Route::patch('/{instituicao}/coordenadores/{coordenadorId}/desvincular', [InstituicaoController::class, 'desvincularCoordenador']);

    /*
    |--------------------------------------------------------------------------
    | RF41 – CONSULTAR ESTRUTURA ACADÊMICA (Funções de Saída)
    |--------------------------------------------------------------------------
    */
    // Listar todos os cursos da instituição
    Route::get('/{instituicao}/cursos',         [InstituicaoController::class, 'listarCursos']);

    // Listar todos os coordenadores da instituição
    Route::get('/{instituicao}/coordenadores',  [InstituicaoController::class, 'listarCoordenadores']);

    // Estrutura acadêmica consolidada (cursos + coordenadores)
    Route::get('/{instituicao}/estrutura',      [InstituicaoController::class, 'estruturaAcademica']);

    /*
    |--------------------------------------------------------------------------
    | RF42 – EMITIR RELATÓRIO INSTITUCIONAL (Funções de Saída)
    |--------------------------------------------------------------------------
    */
    // Relatório consolidado (JSON): dados + cursos + coordenadores + estágios
    Route::get('/{instituicao}/relatorio',          [InstituicaoController::class, 'relatorio']);

    // Exportar em CSV ou PDF: /instituicoes/{id}/exportar?formato=csv|pdf
    Route::get('/{instituicao}/exportar',           [InstituicaoController::class, 'exportar']);
});
