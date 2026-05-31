<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF06 – Visualizar Contrato de Estágio
class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'aluno_id',
        'solicitacao_estagio_id',
        'empresa_id',
        'supervisor_id',
        'numero_contrato',
        'data_inicio',
        'data_fim',
        'carga_horaria_semanal',
        'carga_horaria_total',
        'valor_bolsa',
        'status',         // ativo | encerrado | cancelado
        'caminho_arquivo',
        'assinado_em',
    ];

    protected $casts = [
        'data_inicio'   => 'date',
        'data_fim'      => 'date',
        'assinado_em'   => 'datetime',
        'valor_bolsa'   => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopeEncerrados($query)
    {
        return $query->where('status', 'encerrado');
    }

    public function scopePorAluno($query, int $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'ativo'     => 'Ativo',
            'encerrado' => 'Encerrado',
            'cancelado' => 'Cancelado',
            default     => '-',
        };
    }
}
