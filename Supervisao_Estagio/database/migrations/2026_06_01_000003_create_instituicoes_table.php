<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// RF38 – Gerenciar Instituição (tabela principal)
// RF39 – Vincular Cursos      (adiciona FK id_instituicao em cursos)
// RF40 – Vincular Coordenadores (adiciona FK id_instituicao em coordenadores)
// RNF13 – Persistência em banco relacional com integridade referencial
// RNF15 – Campo 'ativa' para desativação lógica (sem exclusão física)
return new class extends Migration
{
    public function up(): void
    {
        // ── Tabela principal de instituições ──────────────────────────────
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();                                                   // id_instituicao INT PK autoincremento
            $table->string('nome_instituicao', 200);                        // NOT NULL
            $table->string('sigla', 20)->unique();                          // NOT NULL, UNIQUE
            $table->char('cnpj', 14)->unique();                             // NOT NULL, UNIQUE
            $table->string('endereco', 255);                                // NOT NULL
            $table->string('cidade', 100);                                  // NOT NULL
            $table->char('estado', 2);                                      // NOT NULL – UF
            $table->string('telefone', 20)->nullable();                     // NULLABLE
            $table->string('email_contato', 200)->nullable();               // NULLABLE
            $table->string('site', 200)->nullable();                        // NULLABLE
            $table->boolean('ativa')->default(true);                        // DEFAULT TRUE
            $table->timestamp('data_cadastro')->useCurrent();               // DEFAULT NOW()
            $table->timestamps();
        });

        // ── RF39 – FK id_instituicao na tabela cursos ──────────────────────
        Schema::table('cursos', function (Blueprint $table) {
            $table->foreignId('id_instituicao')
                ->nullable()
                ->after('id')
                ->constrained('instituicoes')
                ->onDelete('restrict');   // RNF15 – impede exclusão da instituição com cursos
        });

        // ── RF40 – FK id_instituicao na tabela coordenadores ───────────────
        Schema::table('coordenadores', function (Blueprint $table) {
            $table->foreignId('id_instituicao')
                ->nullable()
                ->after('id')
                ->constrained('instituicoes')
                ->onDelete('restrict');   // RNF15 – impede exclusão da instituição com coordenadores
        });
    }

    public function down(): void
    {
        // Remove FKs antes de dropar a tabela (integridade referencial)
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
