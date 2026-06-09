<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coordenadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('instituicao_id')->nullable()->constrained('instituicoes')->onDelete('restrict');
            $table->string('matricula_institucional', 30)->unique();
            $table->string('telefone', 20)->nullable();
            $table->date('data_inicio_funcao');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();

            $table->index(['curso_id', 'status']);
            $table->index('instituicao_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordenadores');
    }
};
