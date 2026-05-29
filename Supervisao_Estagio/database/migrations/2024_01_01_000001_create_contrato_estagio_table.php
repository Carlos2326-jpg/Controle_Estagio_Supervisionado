<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoEstagioTable extends Migration
{
  public function up()
  {
    Schema::create('contrato_estagio', function (Blueprint $table) {
      $table->id('id_contrato');
      $table->unsignedBigInteger('id_solicitacao');
      $table->string('numero_contrato', 50)->unique();
      $table->date('data_inicio');
      $table->date('data_fim');
      $table->date('data_fim_real')->nullable();
      $table->decimal('valor_bolsa', 10, 2)->nullable();
      $table->decimal('valor_auxilio_transporte', 10, 2)->nullable();
      $table->enum('status', ['ATIVO', 'ENCERRADO', 'CANCELADO', 'RENOVADO']);
      $table->string('arquivo_contrato', 255)->nullable();
      $table->timestamps();

      $table->foreign('id_solicitacao')->references('id_solicitacao')->on('solicitacao_estagio');
      $table->index('status');
      $table->index('data_fim');
    });
  }

  public function down()
  {
    Schema::dropIfExists('contrato_estagio');
  }
}
