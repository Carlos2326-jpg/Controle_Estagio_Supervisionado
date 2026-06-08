<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitacoes_estagio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('supervisores')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->date('data_inicio_prevista');
            $table->date('data_fim_prevista');
            $table->integer('carga_horaria_semanal');
            $table->integer('carga_horaria_total');
            $table->text('descricao_atividades');
            $table->enum('status', [
                'pendente',
                'em_analise',
                'aprovada',
                'reprovada',
                'cancelada',
            ])->default('pendente');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('curso_id');
            $table->index('aluno_id');
            $table->index(['empresa_id', 'status']);
            $table->index(['aluno_id', 'status']);
        });

        Schema::create('historico_analises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitacao_estagio_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->foreignId('coordenador_id')->constrained('coordenadores')->onDelete('cascade');
            $table->enum('decisao', ['aprovada', 'reprovada']);
            $table->text('justificativa')->nullable();
            $table->timestamp('analisado_em');
            $table->timestamps();
            
            $table->index('solicitacao_estagio_id');
            $table->index('coordenador_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_analises');
        Schema::dropIfExists('solicitacoes_estagio');
    }
};