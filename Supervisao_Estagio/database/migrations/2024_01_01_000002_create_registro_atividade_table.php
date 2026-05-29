<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroAtividadeTable extends Migration
{
    public function up()
    {
        Schema::create('registro_atividade', function (Blueprint $table) {
            $table->id('id_registro');
            $table->unsignedBigInteger('id_contrato');
            $table->date('data_atividade');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->decimal('horas_computadas', 4, 2);
            $table->text('descricao');
            $table->boolean('validado_supervisor')->default(false);
            $table->text('observacao_supervisor')->nullable();
            $table->timestamps();

            $table->foreign('id_contrato')->references('id_contrato')->on('contrato_estagio');
            $table->index('data_atividade');
            $table->index('validado_supervisor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('registro_atividade');
    }
}
