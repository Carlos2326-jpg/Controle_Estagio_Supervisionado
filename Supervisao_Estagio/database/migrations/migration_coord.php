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
            $table->string('matricula_institucional', 30)->unique();
            $table->string('telefone', 20)->nullable();
            $table->date('data_inicio_funcao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordenadores');
    }
};