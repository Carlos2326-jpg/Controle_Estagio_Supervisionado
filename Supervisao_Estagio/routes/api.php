<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CoordenadorController;

/*
|--------------------------------------------------------------------------
| API Routes - Para o Dashboard Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('api')->group(function () {
    
    // Counts para dashboard
    Route::get('/instituicoes/count', function() {
        return response()->json(['total' => \App\Models\Instituicao::count()]);
    });
    
    Route::get('/cursos/count', function() {
        return response()->json(['total' => \App\Models\Curso::where('ativo', true)->count()]);
    });
    
    // Dados para tabelas
    Route::get('/instituicoes', [InstituicaoController::class, 'index']);
    Route::post('/instituicoes', [InstituicaoController::class, 'store']);
    
    Route::get('/cursos', [CursoController::class, 'index']);
    Route::post('/cursos', [CursoController::class, 'store']);
    
    Route::get('/coordenadores', [CoordenadorController::class, 'index']);
    Route::post('/coordenadores', [CoordenadorController::class, 'store']);
});