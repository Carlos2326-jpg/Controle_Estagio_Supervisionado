<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CoordenadorController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Auth;

// ==========================================
// ROTAS PÚBLICAS (NÃO PROTEGIDAS)
// ==========================================

// Rotas de autenticação (login, registro, recuperação de senha)
require __DIR__ . '/auth.php';

// Rota padrão
Route::get('/', fn() => redirect('/login'));

// ==========================================
// ROTAS PROTEGIDAS — TODOS OS USUÁRIOS AUTENTICADOS
// ==========================================

Route::middleware(['auth'])->group(function () {

    // Dashboard com redirecionamento por role
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        $role = $user->role;

        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'coordenador') {
            return redirect('/coordenador/dashboard');
        } elseif ($role === 'aluno') {
            return redirect('/aluno/dashboard');
        } elseif ($role === 'empresa') {
            return redirect('/empresa/dashboard');
        }

        return view('dashboard');
    })->name('dashboard');

    // Dashboards por role
    Route::get('/admin/dashboard',      [DashboardController::class, 'admin'])->name('admin.dashboard')->middleware('role:admin');
    Route::get('/coordenador/dashboard', [DashboardController::class, 'coordenador'])->name('coordenador.dashboard')->middleware('role:coordenador');
    Route::get('/aluno/dashboard',      [DashboardController::class, 'aluno'])->name('aluno.dashboard')->middleware('role:aluno');
    Route::get('/empresa/dashboard',    [DashboardController::class, 'empresa'])->name('empresa.dashboard')->middleware('role:empresa');

    // ==========================================
    // ROTAS DE ADMIN (UC06, UC19, UC28)
    // ==========================================

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        // UC06 – Cadastrar Usuário
        Route::post('/usuarios', [UserController::class, 'store'])->name('admin.usuarios.store');

        // UC19 – Cadastrar Aluno
        Route::post('/alunos', [UserController::class, 'storeAluno'])->name('admin.alunos.store');

        // UC28 – Cadastrar Convênio
        Route::post('/convenios', [UserController::class, 'storeConvenio'])->name('admin.convenios.store');
    });

    // ==========================================
    // ROTAS DE ADMIN PARA INSTITUIÇÕES
    // ==========================================

    Route::prefix('instituicoes')->middleware('role:admin')->name('instituicoes.')->group(function () {
        Route::get('/',                                          [InstituicaoController::class, 'index'])->name('index');
        Route::get('/criar',                                     [InstituicaoController::class, 'create'])->name('create');
        Route::post('/',                                         [InstituicaoController::class, 'store'])->name('store');
        Route::get('/{instituicao}',                             [InstituicaoController::class, 'show'])->name('show');
        Route::get('/{instituicao}/editar',                      [InstituicaoController::class, 'edit'])->name('edit');
        Route::put('/{instituicao}',                             [InstituicaoController::class, 'update'])->name('update');
        Route::patch('/{instituicao}/toggle-ativa',              [InstituicaoController::class, 'toggleAtiva'])->name('toggleAtiva');
        Route::post('/{instituicao}/cursos/vincular',            [InstituicaoController::class, 'vincularCurso'])->name('cursos.vincular');
        Route::delete('/{instituicao}/cursos/{cursoId}/desvincular', [InstituicaoController::class, 'desvincularCurso'])->name('cursos.desvincular');
        Route::get('/{instituicao}/cursos',                      [InstituicaoController::class, 'listarCursos'])->name('cursos.index');
        Route::post('/{instituicao}/coordenadores/vincular',     [InstituicaoController::class, 'vincularCoordenador'])->name('coordenadores.vincular');
        Route::delete('/{instituicao}/coordenadores/{coordenadorId}/desvincular', [InstituicaoController::class, 'desvincularCoordenador'])->name('coordenadores.desvincular');
        Route::get('/{instituicao}/coordenadores',               [InstituicaoController::class, 'listarCoordenadores'])->name('coordenadores.index');
        Route::get('/{instituicao}/estrutura',                   [InstituicaoController::class, 'estruturaAcademica'])->name('estrutura');
        Route::get('/{instituicao}/relatorio',                   [InstituicaoController::class, 'relatorio'])->name('relatorio');
        Route::get('/{instituicao}/exportar',                    [InstituicaoController::class, 'exportar'])->name('exportar');
    });

    // ==========================================
    // ROTAS DE CURSOS (ADMIN e COORDENADOR)
    // ==========================================

    Route::prefix('cursos')->middleware('role:admin,coordenador')->name('cursos.')->group(function () {
        Route::get('/',              [CursoController::class, 'index'])->name('index');
        Route::get('/criar',         [CursoController::class, 'create'])->name('create');
        Route::post('/',             [CursoController::class, 'store'])->name('store');
        Route::get('/{curso}',       [CursoController::class, 'show'])->name('show');
        Route::get('/{curso}/editar', [CursoController::class, 'edit'])->name('edit');
        Route::put('/{curso}',       [CursoController::class, 'update'])->name('update');
        Route::patch('/{curso}/inativar', [CursoController::class, 'inativar'])->name('inativar');
    });

    // ==========================================
    // ROTAS DE MATRÍCULAS (ADMIN e COORDENADOR)
    // ==========================================

    Route::prefix('cursos/{curso}/matriculas')->middleware('role:admin,coordenador')->group(function () {
        Route::get('/',                   [MatriculaController::class, 'index']);
        Route::get('/buscar',             [MatriculaController::class, 'buscar']);
        Route::get('/{aluno}/historico',  [MatriculaController::class, 'historico']);
        Route::get('/alertas/sem-horas',  [MatriculaController::class, 'alunosSemHoras']);
    });

    // ==========================================
    // ROTAS DE COORDENADORES
    // ==========================================
    Route::prefix('coordenadores')->middleware(['auth'])->group(function () {
        // Listar e criar
        Route::get('/', [CoordenadorController::class, 'index']);
        Route::post('/', [CoordenadorController::class, 'store']);

        // Editar e inativar (PUT e PATCH)
        Route::put('/{coordenador}', [CoordenadorController::class, 'update']);
        Route::patch('/{coordenador}/inativar', [CoordenadorController::class, 'inativar']);

        // Demais rotas
        Route::get('/{coordenador}/informacoes-academicas', [CoordenadorController::class, 'informacoesAcademicas']);
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
        Route::put('/{coordenador}/avaliacoes/{avaliacao}', [CoordenadorController::class, 'atualizarAvaliacao']);
        Route::get('/{coordenador}/alertas', [CoordenadorController::class, 'alertas']);
        Route::patch('/{coordenador}/alertas/lido', [CoordenadorController::class, 'marcarAlertaLido']);
        Route::get('/{coordenador}/relatorios', [CoordenadorController::class, 'gerarRelatorio']);
    });

    // ==========================================
    // ROTAS DE CURSOS
    // ==========================================
    Route::prefix('cursos')->middleware(['auth'])->group(function () {
        Route::get('/', [CursoController::class, 'index']);
        Route::post('/', [CursoController::class, 'store']);
        Route::get('/{curso}', [CursoController::class, 'show']);
        Route::put('/{curso}', [CursoController::class, 'update']);
        Route::patch('/{curso}/inativar', [CursoController::class, 'inativar']);
    });
    // ==========================================
    // ROTAS DE EMPRESAS
    // ==========================================

    Route::prefix('empresas')->middleware('role:admin,empresa')->group(function () {
        Route::get('/',                                                                    [EmpresaController::class, 'index']);
        Route::post('/',                                                                   [EmpresaController::class, 'store']);
        Route::get('/{empresa}',                                                           [EmpresaController::class, 'show']);
        Route::put('/{empresa}',                                                           [EmpresaController::class, 'update']);
        Route::patch('/{empresa}/desativar',                                               [EmpresaController::class, 'desativar']);
        Route::patch('/{empresa}/reativar',                                                [EmpresaController::class, 'reativar']);
        Route::get('/{empresa}/convenios',                                                 [EmpresaController::class, 'convenios']);
        Route::post('/{empresa}/convenios',                                                [EmpresaController::class, 'convenioStore']);
        Route::put('/{empresa}/convenios/{convenio}',                                      [EmpresaController::class, 'convenioUpdate']);
        Route::get('/{empresa}/supervisores',                                              [EmpresaController::class, 'supervisores']);
        Route::post('/{empresa}/supervisores',                                             [EmpresaController::class, 'supervisorStore']);
        Route::put('/{empresa}/supervisores/{supervisor}',                                 [EmpresaController::class, 'supervisorUpdate']);
        Route::patch('/{empresa}/supervisores/{supervisor}/desativar',                     [EmpresaController::class, 'supervisorDesativar']);
        Route::get('/{empresa}/solicitacoes',                                              [EmpresaController::class, 'solicitacoes']);
        Route::get('/{empresa}/solicitacoes/{solicitacao}/contrato',                       [EmpresaController::class, 'contrato']);
        Route::get('/{empresa}/supervisores/{supervisor}/avaliacoes',                      [EmpresaController::class, 'avaliacoes']);
        Route::post('/{empresa}/supervisores/{supervisor}/avaliacoes/{solicitacao}',       [EmpresaController::class, 'avaliacaoStore']);
        Route::get('/{empresa}/estagiarios',                                               [EmpresaController::class, 'estagiarios']);
    });

    // ==========================================
    // ROTAS DE SUPERVISOR (UC54 – Validar Atividades)
    // ==========================================

    Route::prefix('supervisores')->middleware('role:empresa')->group(function () {
        Route::get('/{supervisor}/atividades-pendentes', [SupervisorController::class, 'atividadesPendentes']);
        Route::patch('/{supervisor}/atividades/{atividade}/validar', [SupervisorController::class, 'validarAtividade']);
    });

    // ==========================================
    // ROTAS DE ALUNOS
    // ==========================================

    Route::prefix('alunos')->middleware('role:admin,coordenador,aluno')->group(function () {
        // UC36 – Abrir Solicitação de Estágio
        Route::get('/',                                          [AlunoController::class, 'index']);
        Route::post('/',                                         [AlunoController::class, 'store']);
        Route::get('/{aluno}',                                   [AlunoController::class, 'show']);
        Route::put('/{aluno}',                                   [AlunoController::class, 'update']);
        Route::patch('/{aluno}/inativar',                        [AlunoController::class, 'inativar']);
        Route::get('/{aluno}/situacao-estagio',                  [AlunoController::class, 'situacaoEstagio']);
        Route::post('/{aluno}/solicitacoes',                     [AlunoController::class, 'solicitarEstagio']);
        Route::get('/{aluno}/solicitacoes',                      [AlunoController::class, 'listarSolicitacoes']);
        Route::patch('/{aluno}/solicitacoes/{solicitacao}/cancelar', [AlunoController::class, 'cancelarSolicitacao']);
        Route::get('/{aluno}/contratos',                         [AlunoController::class, 'listarContratos']);
        Route::get('/{aluno}/contratos/{contrato}',              [AlunoController::class, 'visualizarContrato']);

        // UC52 – Registrar Atividade
        Route::get('/{aluno}/atividades',                        [AlunoController::class, 'listarAtividades']);
        Route::post('/{aluno}/atividades',                       [AlunoController::class, 'registrarAtividade']);
        Route::put('/{aluno}/atividades/{atividade}',            [AlunoController::class, 'atualizarAtividade']);
        Route::delete('/{aluno}/atividades/{atividade}',         [AlunoController::class, 'excluirAtividade']);

        // UC47 – Enviar Documento
        Route::post('/{aluno}/documentos',                       [AlunoController::class, 'enviarDocumento']);
        Route::get('/{aluno}/documentos',                        [AlunoController::class, 'listarDocumentos']);

        // UC60 – Consultar Avaliações
        Route::get('/{aluno}/avaliacoes',                        [AlunoController::class, 'listarAvaliacoes']);

        // UC62-65 – Alertas
        Route::get('/{aluno}/alertas',                           [AlunoController::class, 'alertas']);
        Route::patch('/{aluno}/alertas/lido',                    [AlunoController::class, 'marcarAlertaLido']);
        Route::patch('/{aluno}/alertas/marcar-todos-lidos',      [AlunoController::class, 'marcarTodosAlertasLidos']);
    });

    // ==========================================
    // ROTAS DE API
    // ==========================================

    // Rotas de API para o Dashboard Admin
    Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
        Route::get('/instituicoes/count', function () {
            return response()->json(['total' => \App\Models\Instituicao::count()]);
        });
        Route::get('/cursos/count', function () {
            return response()->json(['total' => \App\Models\Curso::where('ativo', true)->count()]);
        });
        Route::get('/coordenadores/count', function () {
            return response()->json(['total' => \App\Models\Coordenador::where('status', 'ativo')->count()]);
        });
        Route::get('/alunos/count', function () {
            return response()->json(['total' => \App\Models\Aluno::where('ativo', true)->count()]);
        });
    });

    // Rotas para API do dashboard da empresa (AJAX)
    Route::middleware(['auth', 'role:empresa'])->prefix('api/empresa')->group(function () {
        Route::get('/dashboard-data', [DashboardController::class, 'empresaDadosApi']);
        Route::get('/solicitacoes', [DashboardController::class, 'empresaSolicitacoesApi']);
        Route::get('/supervisores', [DashboardController::class, 'empresaSupervisoresApi']);
        Route::get('/convenios', [DashboardController::class, 'empresaConveniosApi']);
        Route::get('/avaliacoes', [DashboardController::class, 'empresaAvaliacoesApi']);
        Route::get('/estagiarios', [DashboardController::class, 'empresaEstagiariosApi']);
    });

    // Rotas de API adicionais para empresa (simplificadas)
    Route::middleware(['auth', 'role:empresa'])->prefix('api/empresa')->group(function () {
        Route::get('/supervisores-list', function () {
            $user = Auth::user();
            $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
            return response()->json(\App\Models\Supervisor::where('empresa_id', $empresa?->id)->paginate(20));
        });

        Route::get('/estagiarios-list', function () {
            $user = Auth::user();
            $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
            return response()->json(\App\Models\SolicitacaoEstagio::where('empresa_id', $empresa?->id)
                ->with(['aluno.user', 'curso', 'supervisor'])
                ->paginate(20));
        });

        Route::get('/solicitacoes-list', function () {
            $user = Auth::user();
            $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
            return response()->json(\App\Models\SolicitacaoEstagio::where('empresa_id', $empresa?->id)
                ->with(['aluno.user', 'curso'])
                ->paginate(20));
        });

        Route::get('/convenios-list', function () {
            $user = Auth::user();
            $empresa = \App\Models\Empresa::where('user_id', $user->id)->first();
            return response()->json(\App\Models\Convenio::where('empresa_id', $empresa?->id)->paginate(20));
        });
    });
});
