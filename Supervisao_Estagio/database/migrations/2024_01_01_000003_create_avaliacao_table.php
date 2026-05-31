<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacaoTable extends Migration
{
  public function up()
  {
    Schema::create('avaliacao', function (Blueprint $table) {
      $table->id('id_avaliacao');
      $table->unsignedBigInteger('id_contrato');
      $table->enum('tipo_avaliador', ['SUPERVISOR', 'COORDENADOR']);
      $table->unsignedBigInteger('id_avaliador');
      $table->string('periodo_referencia', 20);
      $table->decimal('nota_desempenho', 4, 2);
      $table->decimal('nota_comportamento', 4, 2);
      $table->decimal('nota_pontualidade', 4, 2);
      $table->decimal('media_final', 4, 2);
      $table->text('parecer')->nullable();
      $table->enum('situacao_final', ['APROVADO', 'REPROVADO'])->nullable();
      $table->timestamp('data_avaliacao')->useCurrent();

      $table->foreign('id_contrato')->references('id_contrato')->on('contrato_estagio');
      $table->foreign('id_avaliador')->references('id')->on('users');
      $table->index('tipo_avaliador');
      $table->index('situacao_final');
    });
  }

  public function down()
  {
    Schema::dropIfExists('avaliacao');
  }
}
