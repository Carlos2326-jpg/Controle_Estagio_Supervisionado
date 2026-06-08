<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration final para garantir todas as conexões entre tabelas
 * Versão compatível com SQLite/MySQL/PostgreSQL
 */
return new class extends Migration
{
    public function up(): void
    {
        // ==========================================
        // 1. ADICIONAR FK id_instituicao NAS TABELAS
        // ==========================================
        
        // Adiciona FK id_instituicao na tabela cursos
        if (Schema::hasTable('cursos') && !Schema::hasColumn('cursos', 'id_instituicao')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->foreignId('id_instituicao')
                    ->nullable()
                    ->after('id')
                    ->constrained('instituicoes')
                    ->onDelete('restrict');
            });
        }

        // Adiciona FK id_instituicao na tabela coordenadores
        if (Schema::hasTable('coordenadores') && !Schema::hasColumn('coordenadores', 'id_instituicao')) {
            Schema::table('coordenadores', function (Blueprint $table) {
                $table->foreignId('id_instituicao')
                    ->nullable()
                    ->after('id')
                    ->constrained('instituicoes')
                    ->onDelete('restrict');
            });
        }
        
        // ==========================================
        // 2. CRIAR ÍNDICES DE PERFORMANCE
        // ==========================================
        
        // Índices para tabela alunos
        if (Schema::hasTable('alunos')) {
            Schema::table('alunos', function (Blueprint $table) {
                if (!Schema::hasIndex('alunos', 'idx_alunos_curso_situacao')) {
                    $table->index(['curso_id', 'situacao_estagio'], 'idx_alunos_curso_situacao');
                }
                if (!Schema::hasIndex('alunos', 'idx_alunos_user_ativo')) {
                    $table->index(['user_id', 'ativo'], 'idx_alunos_user_ativo');
                }
            });
        }
        
        // Índices para tabela atividades_estagio
        if (Schema::hasTable('atividades_estagio')) {
            Schema::table('atividades_estagio', function (Blueprint $table) {
                if (!Schema::hasIndex('atividades_estagio', 'idx_atividades_aluno_data')) {
                    $table->index(['aluno_id', 'data'], 'idx_atividades_aluno_data');
                }
                if (!Schema::hasIndex('atividades_estagio', 'idx_atividades_validacao')) {
                    $table->index(['validado_supervisor', 'data'], 'idx_atividades_validacao');
                }
            });
        }
        
        // Índices para tabela documentos
        if (Schema::hasTable('documentos')) {
            Schema::table('documentos', function (Blueprint $table) {
                if (!Schema::hasIndex('documentos', 'idx_documentos_aluno_status')) {
                    $table->index(['aluno_id', 'status'], 'idx_documentos_aluno_status');
                }
                if (!Schema::hasIndex('documentos', 'idx_documentos_tipo_status')) {
                    $table->index(['tipo', 'status'], 'idx_documentos_tipo_status');
                }
            });
        }
        
        // Índices para tabela contratos
        if (Schema::hasTable('contratos')) {
            Schema::table('contratos', function (Blueprint $table) {
                if (!Schema::hasIndex('contratos', 'idx_contratos_aluno_status')) {
                    $table->index(['aluno_id', 'status'], 'idx_contratos_aluno_status');
                }
                if (!Schema::hasIndex('contratos', 'idx_contratos_empresa_status')) {
                    $table->index(['empresa_id', 'status'], 'idx_contratos_empresa_status');
                }
            });
        }
        
        // Índices para tabela empresas
        if (Schema::hasTable('empresas')) {
            Schema::table('empresas', function (Blueprint $table) {
                if (!Schema::hasIndex('empresas', 'idx_empresas_cidade')) {
                    $table->index('cidade', 'idx_empresas_cidade');
                }
                if (!Schema::hasIndex('empresas', 'idx_empresas_status_cidade')) {
                    $table->index(['status', 'cidade'], 'idx_empresas_status_cidade');
                }
            });
        }
        
        // Índices para tabela supervisores
        if (Schema::hasTable('supervisores')) {
            Schema::table('supervisores', function (Blueprint $table) {
                if (!Schema::hasIndex('supervisores', 'idx_supervisores_empresa_status')) {
                    $table->index(['empresa_id', 'status'], 'idx_supervisores_empresa_status');
                }
            });
        }
        
        // Índices para tabela convenios
        if (Schema::hasTable('convenios')) {
            Schema::table('convenios', function (Blueprint $table) {
                if (!Schema::hasIndex('convenios', 'idx_convenios_empresa_status')) {
                    $table->index(['empresa_id', 'status'], 'idx_convenios_empresa_status');
                }
            });
        }
        
        // Índices para tabela cursos
        if (Schema::hasTable('cursos')) {
            Schema::table('cursos', function (Blueprint $table) {
                if (!Schema::hasIndex('cursos', 'idx_cursos_ativo_modalidade')) {
                    $table->index(['ativo', 'modalidade'], 'idx_cursos_ativo_modalidade');
                }
            });
        }
        
        // Índices para tabela solicitacoes_estagio
        if (Schema::hasTable('solicitacoes_estagio')) {
            Schema::table('solicitacoes_estagio', function (Blueprint $table) {
                if (!Schema::hasIndex('solicitacoes_estagio', 'idx_solicitacoes_status_data')) {
                    $table->index(['status', 'data_inicio_prevista'], 'idx_solicitacoes_status_data');
                }
                if (!Schema::hasIndex('solicitacoes_estagio', 'idx_solicitacoes_aluno_status')) {
                    $table->index(['aluno_id', 'status'], 'idx_solicitacoes_aluno_status');
                }
                if (!Schema::hasIndex('solicitacoes_estagio', 'idx_solicitacoes_empresa_status')) {
                    $table->index(['empresa_id', 'status'], 'idx_solicitacoes_empresa_status');
                }
            });
        }
    }
    
    public function down(): void
    {
        // Remover FKs adicionadas
        if (Schema::hasTable('cursos') && Schema::hasColumn('cursos', 'id_instituicao')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->dropForeign(['id_instituicao']);
                $table->dropColumn('id_instituicao');
            });
        }

        if (Schema::hasTable('coordenadores') && Schema::hasColumn('coordenadores', 'id_instituicao')) {
            Schema::table('coordenadores', function (Blueprint $table) {
                $table->dropForeign(['id_instituicao']);
                $table->dropColumn('id_instituicao');
            });
        }
        
        // Remover índices da tabela alunos
        if (Schema::hasTable('alunos')) {
            Schema::table('alunos', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_alunos_curso_situacao');
                $table->dropIndexIfExists('idx_alunos_user_ativo');
            });
        }
        
        // Remover índices da tabela atividades_estagio
        if (Schema::hasTable('atividades_estagio')) {
            Schema::table('atividades_estagio', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_atividades_aluno_data');
                $table->dropIndexIfExists('idx_atividades_validacao');
            });
        }
        
        // Remover índices da tabela documentos
        if (Schema::hasTable('documentos')) {
            Schema::table('documentos', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_documentos_aluno_status');
                $table->dropIndexIfExists('idx_documentos_tipo_status');
            });
        }
        
        // Remover índices da tabela contratos
        if (Schema::hasTable('contratos')) {
            Schema::table('contratos', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_contratos_aluno_status');
                $table->dropIndexIfExists('idx_contratos_empresa_status');
            });
        }
        
        // Remover índices da tabela empresas
        if (Schema::hasTable('empresas')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_empresas_cidade');
                $table->dropIndexIfExists('idx_empresas_status_cidade');
            });
        }
        
        // Remover índices da tabela supervisores
        if (Schema::hasTable('supervisores')) {
            Schema::table('supervisores', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_supervisores_empresa_status');
            });
        }
        
        // Remover índices da tabela convenios
        if (Schema::hasTable('convenios')) {
            Schema::table('convenios', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_convenios_empresa_status');
            });
        }
        
        // Remover índices da tabela cursos
        if (Schema::hasTable('cursos')) {
            Schema::table('cursos', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_cursos_ativo_modalidade');
            });
        }
        
        // Remover índices da tabela solicitacoes_estagio
        if (Schema::hasTable('solicitacoes_estagio')) {
            Schema::table('solicitacoes_estagio', function (Blueprint $table) {
                $table->dropIndexIfExists('idx_solicitacoes_status_data');
                $table->dropIndexIfExists('idx_solicitacoes_aluno_status');
                $table->dropIndexIfExists('idx_solicitacoes_empresa_status');
            });
        }
    }
};