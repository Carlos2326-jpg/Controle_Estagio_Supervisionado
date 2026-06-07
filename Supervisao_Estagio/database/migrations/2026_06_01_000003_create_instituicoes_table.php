<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_instituicao', 200);
            $table->string('sigla', 20)->unique();
            $table->char('cnpj', 14)->unique();
            $table->string('endereco', 255);
            $table->string('cidade', 100);
            $table->char('estado', 2);
            $table->string('telefone', 20)->nullable();
            $table->string('email_contato', 200)->nullable();
            $table->string('site', 200)->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps(); // created_at e updated_at apenas - sem data_cadastro duplicado
        });

        // Adiciona FK id_instituicao na tabela cursos
        Schema::table('cursos', function (Blueprint $table) {
            $table->foreignId('id_instituicao')
                ->nullable()
                ->after('id')
                ->constrained('instituicoes')
                ->onDelete('restrict');
        });

        // Adiciona FK id_instituicao na tabela coordenadores
        Schema::table('coordenadores', function (Blueprint $table) {
            $table->foreignId('id_instituicao')
                ->nullable()
                ->after('id')
                ->constrained('instituicoes')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('coordenadores', function (Blueprint $table) {
            $table->dropForeign(['id_instituicao']);
            $table->dropColumn('id_instituicao');
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->dropForeign(['id_instituicao']);
            $table->dropColumn('id_instituicao');
        });

        Schema::dropIfExists('instituicoes');
    }
};