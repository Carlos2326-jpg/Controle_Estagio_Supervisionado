<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * EXCEP-02: Adiciona valor padrão para carga_horaria_cumprida
     * para evitar valores NULL que causam erros de cálculo.
     */
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // Atualiza registros existentes com NULL para 0
            DB::table('alunos')
                ->whereNull('carga_horaria_cumprida')
                ->update(['carga_horaria_cumprida' => 0]);
            
            // Altera a coluna para NOT NULL com default 0
            $table->integer('carga_horaria_cumprida')
                ->default(0)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            $table->integer('carga_horaria_cumprida')
                ->nullable()
                ->default(null)
                ->change();
        });
    }
};