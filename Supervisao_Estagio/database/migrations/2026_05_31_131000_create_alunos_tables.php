<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF01 – Gerenciar Dados do Aluno
// RF02 – Consultar Situação de Estágio (campos situacao_estagio e carga_horaria_cumprida)
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Tabela principal de Alunos
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('curso_id')->constrained()->onDelete('restrict');
            $table->string('matricula', 20)->unique();
            $table->string('cpf', 11)->unique();
            $table->string('telefone', 20)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->text('endereco')->nullable();
            $table->enum('situacao_estagio', [
                'sem_estagio',
                'em_andamento',
                'concluido',
            ])->default('sem_estagio');
            $table->integer('carga_horaria_cumprida')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // RF07, RF08 – Atividades diárias de estágio
        Schema::create('atividades_estagio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->date('data');
            $table->text('descricao');
            $table->decimal('horas', 4, 2);
            $table->boolean('validado_supervisor')->default(false);
            $table->timestamp('validado_em')->nullable();
            $table->text('observacao_supervisor')->nullable();
            $table->timestamps();
        });

        // RF09, RF10 – Documentos enviados pelo aluno
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
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
        });

        // RF06 – Contratos de estágio vinculados ao aluno
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained()->onDelete('restrict');
            $table->foreignId('supervisor_id')->constrained()->onDelete('restrict');
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
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('contratos');
        Schema::dropIfExists('documentos');
        Schema::dropIfExists('atividades_estagio');
        Schema::dropIfExists('alunos');

        Schema::enableForeignKeyConstraints();
    }
};
