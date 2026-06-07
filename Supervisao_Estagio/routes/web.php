<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InstituicaoController;

Route::get('/', function () {
    return response()->json(['message' => 'Sistema de Gestão de Estágios API']);
});

/*
|--------------------------------------------------------------------------
| Rotas – Módulo Empresas (RF24–RF30)
|--------------------------------------------------------------------------
*/
Route::prefix('empresas')->name('empresas.')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EmpresaController::class, 'index'])->name('index');
    Route::post('/', [EmpresaController::class, 'store'])->name('store');
    Route::get('/{empresa}', [EmpresaController::class, 'show'])->name('show');
    Route::put('/{empresa}', [EmpresaController::class, 'update'])->name('update');
    Route::patch('/{empresa}/desativar', [EmpresaController::class, 'desativar'])->name('desativar');
    Route::patch('/{empresa}/reativar', [EmpresaController::class, 'reativar'])->name('reativar');

    Route::get('/{empresa}/convenios', [EmpresaController::class, 'convenios'])->name('convenios');
    Route::post('/{empresa}/convenios', [EmpresaController::class, 'convenioStore'])->name('convenios.store');
    Route::put('/{empresa}/convenios/{convenio}', [EmpresaController::class, 'convenioUpdate'])->name('convenios.update');

    Route::get('/{empresa}/supervisores', [EmpresaController::class, 'supervisores'])->name('supervisores');
    Route::post('/{empresa}/supervisores', [EmpresaController::class, 'supervisorStore'])->name('supervisores.store');
    Route::put('/{empresa}/supervisores/{supervisor}', [EmpresaController::class, 'supervisorUpdate'])->name('supervisores.update');
    Route::patch('/{empresa}/supervisores/{supervisor}/desativar', [EmpresaController::class, 'supervisorDesativar'])->name('supervisores.desativar');

    Route::get('/{empresa}/solicitacoes', [EmpresaController::class, 'solicitacoes'])->name('solicitacoes');
    Route::get('/{empresa}/solicitacoes/{solicitacao}/contrato', [EmpresaController::class, 'contrato'])->name('contrato');

    Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes', [EmpresaController::class, 'avaliacoes'])->name('supervisores.avaliacoes');
    Route::post('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}', [EmpresaController::class, 'avaliacaoStore'])->name('supervisores.avaliacoes.store');

    Route::get('/{empresa}/estagiarios', [EmpresaController::class, 'estagiarios'])->name('estagiarios');
});

/*
|--------------------------------------------------------------------------
| Rotas – Coordenadores
|--------------------------------------------------------------------------
*/
Route::prefix('coordenadores')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CoordenadorController::class, 'index']);
    Route::post('/', [CoordenadorController::class, 'store']);
    Route::patch('/{coordenador}/inativar', [CoordenadorController::class, 'inativar']);
    
    Route::get('/{coordenador}/informacoes-academicas', [CoordenadorController::class, 'informacoesAcademicas']);
    
    // INT-03: Rota estática ANTES das rotas dinâmicas
    Route::get('/{coordenador}/solicitacoes/historico', [CoordenadorController::class, 'historicoAnalises']);
    Route::get('/{coordenador}/solicitacoes', [CoordenadorController::class, 'listarSolicitacoes']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/aprovar', [CoordenadorController::class, 'aprovarSolicitacao']);
    Route::patch('/{coordenador}/solicitacoes/{solicitacao}/reprovar', [CoordenadorController::class, 'reprovarSolicitacao']);
    
    Route::get('/{coordenador}/documentos', [CoordenadorController::class, 'listarDocumentos']);
    Route::patch('/{coordenador}/documentos/{documento}/aprovar', [CoordenadorController::class, 'aprovarDocumento']);
    Route::patch('/{coordenador}/documentos/{documento}/reprovar', [CoordenadorController::class, 'reprovarDocumento']);
    
    Route::get('/{coordenador}/atividades', [CoordenadorController::class, 'acompanharAtividades']);
    Route::get('/{coordenador}/pendencias', [CoordenadorController::class, 'pendencias']);
    
    Route::get('/{coordenador}/avaliacoes', [CoordenadorController::class, 'listarAvaliacoes']);
    Route::post('/{coordenador}/avaliacoes/{solicitacao}', [CoordenadorController::class, 'registrarAvaliacao']);
    // INT-02: Rota corrigida com parâmetro de coordenador
    Route::put('/{coordenador}/avaliacoes/{avaliacao}', [CoordenadorController::class, 'atualizarAvaliacao']);
    
    Route::get('/{coordenador}/alertas', [CoordenadorController::class, 'alertas']);
    Route::patch('/{coordenador}/alertas/lido', [CoordenadorController::class, 'marcarAlertaLido']);
    
    Route::get('/{coordenador}/relatorios', [CoordenadorController::class, 'gerarRelatorio']);
});

/*
|--------------------------------------------------------------------------
| Rotas – Matrículas
|--------------------------------------------------------------------------
*/
Route::prefix('cursos/{curso}/matriculas')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MatriculaController::class, 'index']);
    Route::get('/buscar', [MatriculaController::class, 'buscar']);
    Route::get('/{aluno}/historico', [MatriculaController::class, 'historico']);
    Route::get('/alertas/sem-horas', [MatriculaController::class, 'alunosSemHoras']);
});

/*
|--------------------------------------------------------------------------
| Rotas – Cursos
|--------------------------------------------------------------------------
*/
Route::prefix('cursos')->name('cursos.')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('index');
    Route::post('/', [CursoController::class, 'store'])->name('store');
    Route::get('/{curso}', [CursoController::class, 'show'])->name('show');
    Route::put('/{curso}', [CursoController::class, 'update'])->name('update');
    Route::patch('/{curso}/inativar', [CursoController::class, 'inativar'])->name('inativar');
});

/*
|--------------------------------------------------------------------------
| Rotas – Instituições
|--------------------------------------------------------------------------
*/
Route::prefix('instituicoes')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/', [InstituicaoController::class, 'index']);
    Route::post('/', [InstituicaoController::class, 'store']);
    Route::get('/{instituicao}', [InstituicaoController::class, 'show']);
    Route::put('/{instituicao}', [InstituicaoController::class, 'update']);
    Route::patch('/{instituicao}/toggle-ativa', [InstituicaoController::class, 'toggleAtiva']);
    
    Route::post('/{instituicao}/cursos/vincular', [InstituicaoController::class, 'vincularCurso']);
    Route::delete('/{instituicao}/cursos/{cursoId}/desvincular', [InstituicaoController::class, 'desvincularCurso']);
    Route::get('/{instituicao}/cursos', [InstituicaoController::class, 'listarCursos']);
    
    Route::post('/{instituicao}/coordenadores/vincular', [InstituicaoController::class, 'vincularCoordenador']);
    Route::delete('/{instituicao}/coordenadores/{coordenadorId}/desvincular', [InstituicaoController::class, 'desvincularCoordenador']);
    Route::get('/{instituicao}/coordenadores', [InstituicaoController::class, 'listarCoordenadores']);
    
    Route::get('/{instituicao}/estrutura', [InstituicaoController::class, 'estruturaAcademica']);
    Route::get('/{instituicao}/relatorio', [InstituicaoController::class, 'relatorio']);
    Route::get('/{instituicao}/exportar', [InstituicaoController::class, 'exportar']);
});

/*
|--------------------------------------------------------------------------
| Rotas – Alunos (API)
|--------------------------------------------------------------------------
*/
Route::prefix('alunos')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [App\Http\Controllers\AlunoController::class, 'index']);
    Route::post('/', [App\Http\Controllers\AlunoController::class, 'store']);
    Route::get('/{aluno}', [App\Http\Controllers\AlunoController::class, 'show']);
    Route::put('/{aluno}', [App\Http\Controllers\AlunoController::class, 'update']);
    Route::patch('/{aluno}/inativar', [App\Http\Controllers\AlunoController::class, 'inativar']);
    Route::get('/{aluno}/situacao-estagio', [App\Http\Controllers\AlunoController::class, 'situacaoEstagio']);
    Route::post('/{aluno}/solicitacoes', [App\Http\Controllers\AlunoController::class, 'solicitarEstagio']);
    Route::get('/{aluno}/solicitacoes', [App\Http\Controllers\AlunoController::class, 'listarSolicitacoes']);
    Route::patch('/{aluno}/solicitacoes/{solicitacao}/cancelar', [App\Http\Controllers\AlunoController::class, 'cancelarSolicitacao']);
    Route::get('/{aluno}/contratos', [App\Http\Controllers\AlunoController::class, 'listarContratos']);
    Route::get('/{aluno}/contratos/{contrato}', [App\Http\Controllers\AlunoController::class, 'visualizarContrato']);
    Route::get('/{aluno}/atividades', [App\Http\Controllers\AlunoController::class, 'listarAtividades']);
    Route::post('/{aluno}/atividades', [App\Http\Controllers\AlunoController::class, 'registrarAtividade']);
    Route::put('/{aluno}/atividades/{atividade}', [App\Http\Controllers\AlunoController::class, 'atualizarAtividade']);
    Route::delete('/{aluno}/atividades/{atividade}', [App\Http\Controllers\AlunoController::class, 'excluirAtividade']);
    Route::post('/{aluno}/documentos', [App\Http\Controllers\AlunoController::class, 'enviarDocumento']);
    Route::get('/{aluno}/documentos', [App\Http\Controllers\AlunoController::class, 'listarDocumentos']);
    Route::get('/{aluno}/avaliacoes', [App\Http\Controllers\AlunoController::class, 'listarAvaliacoes']);
    Route::get('/{aluno}/alertas', [App\Http\Controllers\AlunoController::class, 'alertas']);
    Route::patch('/{aluno}/alertas/lido', [App\Http\Controllers\AlunoController::class, 'marcarAlertaLido']);
    Route::patch('/{aluno}/alertas/marcar-todos-lidos', [App\Http\Controllers\AlunoController::class, 'marcarTodosAlertasLidos']);
});