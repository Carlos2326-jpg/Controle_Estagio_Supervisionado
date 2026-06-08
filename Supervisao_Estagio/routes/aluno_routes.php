<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlunoController;

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

    /*
    |--------------------------------------------------------------------------
    | RF04 – CONSULTAR SOLICITAÇÕES
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/solicitacoes', [AlunoController::class, 'listarSolicitacoes']);

    /*
    |--------------------------------------------------------------------------
    | RF05 – CANCELAR SOLICITAÇÃO
    |--------------------------------------------------------------------------
    */
    Route::patch('/{aluno}/solicitacoes/{solicitacao}/cancelar', [AlunoController::class, 'cancelarSolicitacao']);

    /*
    |--------------------------------------------------------------------------
    | RF06 – VISUALIZAR CONTRATO DE ESTÁGIO
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/contratos', [AlunoController::class, 'listarContratos']);
    Route::get('/{aluno}/contratos/{contrato}', [AlunoController::class, 'visualizarContrato']);

    /*
    |--------------------------------------------------------------------------
    | RF07 – REGISTRAR ATIVIDADES DE ESTÁGIO
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/atividades', [AlunoController::class, 'listarAtividades']);
    Route::post('/{aluno}/atividades', [AlunoController::class, 'registrarAtividade']);

    /*
    |--------------------------------------------------------------------------
    | RF08 – EDITAR REGISTROS DE ATIVIDADES (apenas não validados)
    |--------------------------------------------------------------------------
    */
    Route::put('/{aluno}/atividades/{atividade}', [AlunoController::class, 'atualizarAtividade']);
    Route::delete('/{aluno}/atividades/{atividade}', [AlunoController::class, 'excluirAtividade']);

    /*
    |--------------------------------------------------------------------------
    | RF09 – ENVIAR DOCUMENTOS
    |--------------------------------------------------------------------------
    */
    Route::post('/{aluno}/documentos', [AlunoController::class, 'enviarDocumento']);

    /*
    |--------------------------------------------------------------------------
    | RF10 – CONSULTAR STATUS DE DOCUMENTOS
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/documentos', [AlunoController::class, 'listarDocumentos']);

    /*
    |--------------------------------------------------------------------------
    | RF11 – CONSULTAR AVALIAÇÕES
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/avaliacoes', [AlunoController::class, 'listarAvaliacoes']);

    /*
    |--------------------------------------------------------------------------
    | RF12 – RECEBER ALERTAS
    |--------------------------------------------------------------------------
    */
    Route::get('/{aluno}/alertas', [AlunoController::class, 'alertas']);
    Route::patch('/{aluno}/alertas/lido', [AlunoController::class, 'marcarAlertaLido']);
    Route::patch('/{aluno}/alertas/marcar-todos-lidos', [AlunoController::class, 'marcarTodosAlertasLidos']);
});