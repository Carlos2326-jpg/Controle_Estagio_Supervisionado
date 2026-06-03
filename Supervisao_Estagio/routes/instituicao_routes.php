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

    Route::get('/', [InstituicaoController::class, 'index'])->name('index');
    Route::get('/criar', [InstituicaoController::class, 'create'])->name('create');
    Route::post('/', [InstituicaoController::class, 'store'])->name('store');
    Route::get('/{instituicao}', [InstituicaoController::class, 'show'])->name('show');
    Route::get('/{instituicao}/editar', [InstituicaoController::class, 'edit'])->name('edit');
    Route::put('/{instituicao}', [InstituicaoController::class, 'update'])->name('update');
    Route::patch('/{instituicao}/toggle-ativa', [InstituicaoController::class, 'toggleAtiva'])->name('toggleAtiva');

    Route::post('/{instituicao}/cursos/vincular', [InstituicaoController::class, 'vincularCurso'])->name('cursos.vincular');
    Route::patch('/{instituicao}/cursos/{cursoId}/desvincular', [InstituicaoController::class, 'desvincularCurso'])->name('cursos.desvincular');
    Route::get('/{instituicao}/cursos', [InstituicaoController::class, 'listarCursos'])->name('cursos.index');

    Route::post('/{instituicao}/coordenadores/vincular', [InstituicaoController::class, 'vincularCoordenador'])->name('coordenadores.vincular');
    Route::patch('/{instituicao}/coordenadores/{coordenadorId}/desvincular', [InstituicaoController::class, 'desvincularCoordenador'])->name('coordenadores.desvincular');
    Route::get('/{instituicao}/coordenadores', [InstituicaoController::class, 'listarCoordenadores'])->name('coordenadores.index');

    Route::get('/{instituicao}/estrutura', [InstituicaoController::class, 'estruturaAcademica'])->name('estrutura');
    Route::get('/{instituicao}/relatorio', [InstituicaoController::class, 'relatorio'])->name('relatorio');
    Route::get('/{instituicao}/exportar', [InstituicaoController::class, 'exportar'])->name('exportar');
});