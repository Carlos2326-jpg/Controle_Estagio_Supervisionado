<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertaPrazoTable extends Migration
{
  public function up()
  {
    Schema::create('alerta_prazo', function (Blueprint $table) {
      $table->id('id_alerta');
      $table->enum('tipo_alerta', [
        'VENCIMENTO_CONTRATO',
        'VENCIMENTO_CONVENIO',
        'DOCUMENTO_PENDENTE',
        'AVALIACAO_PENDENTE'
      ]);
      $table->unsignedBigInteger('id_referencia');
      $table->unsignedBigInteger('id_usuario_destino');
      $table->text('mensagem');
      $table->timestamp('data_geracao')->useCurrent();
      $table->date('data_vencimento');
      $table->boolean('lido')->default(false);
      $table->timestamp('data_leitura')->nullable();

      $table->foreign('id_usuario_destino')->references('id')->on('users');
      $table->index(['tipo_alerta', 'lido']);
      $table->index('data_vencimento');
      $table->index('id_referencia');
    });
  }

  public function down()
  {
    Schema::dropIfExists('alerta_prazo');
  }
}
