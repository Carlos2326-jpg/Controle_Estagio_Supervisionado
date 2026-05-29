<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertaPrazo extends Model
{
  protected $table = 'alerta_prazo';
  protected $primaryKey = 'id_alerta';
  public $timestamps = false;

  protected $fillable = [
    'tipo_alerta',
    'id_referencia',
    'id_usuario_destino',
    'mensagem',
    'data_geracao',
    'data_vencimento',
    'data_leitura',
    'lido'
  ];

  protected $casts = [
    'data_geracao' => 'datetime',
    'data_vencimento' => 'date',
    'data_leitura' => 'datetime',
    'lido' => 'boolean'
  ];

  // Relacionamentos
  public function usuarioDestino()
  {
    return $this->belongsTo(User::class, 'id_usuario_destino', 'id_usuario');
  }

  // Métodos auxiliares
  public function marcarComoLido()
  {
    $this->lido = true;
    $this->data_leitura = now();
    return $this->save();
  }

  public function isVencido()
  {
    return $this->data_vencimento < now()->startOfDay();
  }

  public function getDiasRestantes()
  {
    if ($this->data_vencimento < now()) {
      return 0;
    }
    return now()->diffInDays($this->data_vencimento, false);
  }

  public function getTipoDisplay()
  {
    $tipos = [
      'VENCIMENTO_CONTRATO' => 'Vencimento de Contrato',
      'VENCIMENTO_CONVENIO' => 'Vencimento de Convênio',
      'DOCUMENTO_PENDENTE' => 'Documento Pendente',
      'AVALIACAO_PENDENTE' => 'Avaliação Pendente'
    ];
    return $tipos[$this->tipo_alerta] ?? $this->tipo_alerta;
  }

  public function getIcone()
  {
    $icones = [
      'VENCIMENTO_CONTRATO' => '📄',
      'VENCIMENTO_CONVENIO' => '🏢',
      'DOCUMENTO_PENDENTE' => '📎',
      'AVALIACAO_PENDENTE' => '⭐'
    ];
    return $icones[$this->tipo_alerta] ?? '🔔';
  }

  public function getCor()
  {
    if ($this->isVencido()) {
      return 'danger';
    }
    if ($this->getDiasRestantes() <= 3) {
      return 'warning';
    }
    return 'info';
  }

  public function getEntidadeReferencia()
  {
    switch ($this->tipo_alerta) {
      case 'VENCIMENTO_CONTRATO':
        return ContratoEstagio::find($this->id_referencia);
      case 'AVALIACAO_PENDENTE':
        return Avaliacao::find($this->id_referencia);
      default:
        return null;
    }
  }

  // Scopes
  public function scopeNaoLidos($query)
  {
    return $query->where('lido', false);
  }

  public function scopeVencidos($query)
  {
    return $query->where('data_vencimento', '<', now());
  }

  public function scopePorTipo($query, $tipo)
  {
    return $query->where('tipo_alerta', $tipo);
  }
}
