<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('perfil', ['aluno', 'coordenador', 'supervisor', 'admin'])
                ->default('aluno')
                ->after('email');
            $table->boolean('ativo')->default(true)->after('perfil');
            $table->timestamp('ultimo_acesso')->nullable()->after('ativo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['perfil', 'ativo', 'ultimo_acesso']);
        });
    }
};
