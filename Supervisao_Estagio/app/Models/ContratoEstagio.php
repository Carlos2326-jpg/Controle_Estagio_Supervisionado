<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoEstagio extends Model
{
  protected $table = 'contrato_estagio';
  protected $primaryKey = 'id_contrato';
  public $timestamps = false;

  protected $fillable = [
    'id_solicitacao',
    'numero_contrato',
    'data_inicio',
    'data_fim',
    'data_fim_real',
    'valor_bolsa',
    'valor_auxilio_transporte',
    'status',
    'arquivo_contrato'
  ];

  protected $casts = [
    'data_inicio' => 'date',
    'data_fim' => 'date',
    'data_fim_real' => 'date',
    'valor_bolsa' => 'decimal:2',
    'valor_auxilio_transporte' => 'decimal:2'
  ];

  // Relacionamentos
  public function solicitacao()
  {
    return $this->belongsTo(SolicitacaoEstagio::class, 'id_solicitacao', 'id_solicitacao');
  }

  public function documentos()
  {
    return $this->hasMany(Documento::class, 'id_contrato', 'id_contrato');
  }

  public function registrosAtividade()
  {
    return $this->hasMany(RegistroAtividade::class, 'id_contrato', 'id_contrato');
  }

  public function avaliacoes()
  {
    return $this->hasMany(Avaliacao::class, 'id_contrato', 'id_contrato');
  }

  // Métodos auxiliares
  public function getAluno()
  {
    return $this->solicitacao->aluno;
  }

  public function getEmpresa()
  {
    return $this->solicitacao->empresa;
  }

  public function getSupervisor()
  {
    return $this->solicitacao->supervisor;
  }

  public function getCoordenador()
  {
    return $this->solicitacao->coordenador;
  }

  public function isAtivo()
  {
    return $this->status === 'ATIVO' && $this->data_fim >= now();
  }

  public function isEncerrado()
  {
    return $this->status === 'ENCERRADO';
  }

  public function isCancelado()
  {
    return $this->status === 'CANCELADO';
  }

  public function getDiasRestantes()
  {
    if ($this->status !== 'ATIVO') return 0;
    return max(0, now()->diffInDays($this->data_fim, false));
  }

  public function getVigenciaDisplay()
  {
    return date('d/m/Y', strtotime($this->data_inicio)) . ' até ' .
      date('d/m/Y', strtotime($this->data_fim));
  }

  public function getHorasCumpridas()
  {
    return $this->registrosAtividade()
      ->where('validado_supervisor', true)
      ->sum('horas_computadas');
  }

  public function getPercentualConclusao()
  {
    $cargaHoraria = $this->solicitacao->aluno->curso->carga_horaria_estagio;
    if ($cargaHoraria <= 0) return 0;
    return min(100, ($this->getHorasCumpridas() / $cargaHoraria) * 100);
  }

  public function getAvaliacaoFinal()
  {
    return $this->avaliacoes()
      ->where('tipo_avaliador', 'COORDENADOR')
      ->whereNotNull('situacao_final')
      ->latest('data_avaliacao')
      ->first();
  }

  public function getUltimaAvaliacao()
  {
    return $this->avaliacoes()->latest('data_avaliacao')->first();
  }

  public function scopeAtivos($query)
  {
    return $query->where('status', 'ATIVO')->where('data_fim', '>=', now());
  }

  public function scopeEncerrados($query)
  {
    return $query->where('status', 'ENCERRADO');
  }

  public function scopeVencendoEm($query, $dias)
  {
    return $query->where('status', 'ATIVO')
      ->where('data_fim', '<=', now()->addDays($dias))
      ->where('data_fim', '>=', now());
  }
}
