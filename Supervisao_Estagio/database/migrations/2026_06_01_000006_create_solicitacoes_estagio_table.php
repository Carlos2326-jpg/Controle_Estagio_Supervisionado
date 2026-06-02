<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF15, RF16 – Analisar Solicitações e Registrar Histórico
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitacoes_estagio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained()->onDelete('cascade');
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
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
        });

        // Histórico de análises (RF16)
        Schema::create('historico_analises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitacao_estagio_id')->constrained()->onDelete('cascade');
            $table->foreignId('coordenador_id')->constrained()->onDelete('cascade');
            $table->enum('decisao', ['aprovada', 'reprovada']);
            $table->text('justificativa')->nullable();
            $table->timestamp('analisado_em');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_analises');
        Schema::dropIfExists('solicitacoes_estagio');
    }
};