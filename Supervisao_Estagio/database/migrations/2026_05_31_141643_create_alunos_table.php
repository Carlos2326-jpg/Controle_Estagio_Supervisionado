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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('matricula')->unique();
            $table->string('curso');
            $table->integer('periodo');
            $table->integer('carga_horaria_obrigatoria');
            $table->integer('carga_horaria_cumprida')->default(0);
            $table->enum('status_estagio', ['sem_estagio', 'em_andamento', 'concluido'])->default('sem_estagio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
