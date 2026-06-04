<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF29 – Avaliar Estagiários (supervisor)
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_supervisor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervisor_id')->constrained('supervisores')->onDelete('cascade');
            $table->foreignId('solicitacao_estagio_id')->constrained()->onDelete('cascade');
            $table->decimal('pontualidade', 4, 2)->nullable();
            $table->decimal('proatividade', 4, 2)->nullable();
            $table->decimal('qualidade_trabalho', 4, 2)->nullable();
            $table->decimal('relacionamento', 4, 2)->nullable();
            $table->decimal('nota_geral', 4, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->date('data_avaliacao');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_supervisor');
    }
};
