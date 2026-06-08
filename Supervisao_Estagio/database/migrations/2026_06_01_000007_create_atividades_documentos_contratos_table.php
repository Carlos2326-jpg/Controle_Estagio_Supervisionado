<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atividades_estagio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->date('data');
            $table->text('descricao');
            $table->decimal('horas', 4, 2);
            $table->boolean('validado_supervisor')->default(false);
            $table->timestamp('validado_em')->nullable();
            $table->text('observacao_supervisor')->nullable();
            $table->timestamps();
            
            $table->index(['aluno_id', 'data']);
            $table->index(['validado_supervisor', 'data']);
            $table->index('data');
            $table->index('solicitacao_estagio_id');
        });

        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->nullable()->constrained('solicitacoes_estagio')->onDelete('set null');
            $table->string('nome');
            $table->enum('tipo', [
                'contrato',
                'termo_compromisso',
                'declaracao',
                'outro',
            ]);
            $table->string('caminho_arquivo');
            $table->string('mime_type', 100)->nullable();
            $table->integer('tamanho_bytes')->nullable();
            $table->enum('status', ['pendente', 'aprovado', 'reprovado'])->default('pendente');
            $table->text('observacao_coordenador')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('coordenadores')->onDelete('set null');
            $table->timestamp('validado_em')->nullable();
            $table->timestamps();
            
            $table->index(['aluno_id', 'status']);
            $table->index(['tipo', 'status']);
            $table->index('status');
        });

        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('restrict');
            $table->foreignId('supervisor_id')->constrained('supervisores')->onDelete('restrict');
            $table->string('numero_contrato')->unique()->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->integer('carga_horaria_semanal');
            $table->integer('carga_horaria_total');
            $table->decimal('valor_bolsa', 10, 2)->nullable();
            $table->enum('status', ['ativo', 'encerrado', 'cancelado'])->default('ativo');
            $table->string('caminho_arquivo')->nullable();
            $table->timestamp('assinado_em')->nullable();
            $table->timestamps();
            
            $table->index(['aluno_id', 'status']);
            $table->index(['empresa_id', 'status']);
            $table->index('data_inicio');
            $table->index('data_fim');
            $table->index('numero_contrato');
            $table->index('solicitacao_estagio_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('atividades_estagio');
    }
};