<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('restrict');
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
            $table->integer('carga_horaria_cumprida')->default(0); // JÁ COM DEFAULT
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};