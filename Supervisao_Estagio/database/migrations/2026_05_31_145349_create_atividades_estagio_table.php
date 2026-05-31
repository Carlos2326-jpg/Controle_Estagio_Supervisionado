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
            $table->foreignId('solicitacao_id')->constrained('solicitacoes_estagio')->onDelete('cascade');
            $table->date('data_atividade');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->decimal('horas_computadas', 4, 2);
            $table->text('descricao');
            $table->boolean('validado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades_estagio');
    }
};
