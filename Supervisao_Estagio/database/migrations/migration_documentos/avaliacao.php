<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF17 – Validar Documentos | RF20 – Realizar Avaliações
return new class extends Migration
{
    public function up(): void
    {
        // Documentos enviados pelos alunos
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nome');
            $table->string('tipo'); // contrato, termo_compromisso, relatorio, etc.
            $table->string('caminho_arquivo');
            $table->string('mime_type');
            $table->bigInteger('tamanho_bytes');
            $table->enum('status', ['pendente', 'aprovado', 'reprovado'])->default('pendente');
            $table->text('observacao_coordenador')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('coordenadores')->onDelete('set null');
            $table->timestamp('validado_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Avaliações dos estagiários (RF20)
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->foreignId('coordenador_id')->constrained()->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained()->onDelete('cascade');
            $table->enum('tipo', ['parcial', 'final']);
            $table->decimal('nota', 4, 2)->nullable();
            $table->enum('conceito', ['otimo', 'bom', 'regular', 'insuficiente'])->nullable();
            $table->text('parecer');
            $table->text('pontos_fortes')->nullable();
            $table->text('pontos_melhoria')->nullable();
            $table->date('data_avaliacao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes');
        Schema::dropIfExists('documentos');
    }
};