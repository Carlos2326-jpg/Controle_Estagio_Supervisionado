<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social');
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj', 18)->unique();
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('ramo_atividade')->nullable();
            $table->enum('status', ['ativa', 'inativa'])->default('ativa');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('numero_convenio')->unique();
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('status', ['ativo', 'inativo', 'vencido'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['empresa_id', 'status']);
            $table->index('data_fim');
        });

        Schema::create('supervisores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nome');
            $table->string('cargo');
            $table->string('email');
            $table->string('telefone')->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('formacao')->nullable();
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['empresa_id', 'status']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervisores');
        Schema::dropIfExists('convenios');
        Schema::dropIfExists('empresas');
    }
};