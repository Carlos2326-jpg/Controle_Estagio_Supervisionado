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
            $table->string('empresa');
            $table->string('supervisor_nome');
            $table->string('supervisor_email')->nullable();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->integer('carga_horaria_semanal');
            $table->integer('carga_horaria_total');
            $table->text('descricao_atividades');
            $table->enum('status', ['pendente', 'aprovada', 'reprovada', 'cancelada'])->default('pendente');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_estagio');
    }
};
