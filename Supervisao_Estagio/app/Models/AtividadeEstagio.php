<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtividadeEstagio extends Model
{
    use HasFactory;

    protected $table = 'atividades_estagio';

    protected $fillable = [
        'aluno_id',
        'solicitacao_id',
        'solicitacao_estagio_id',
        'data_atividade',
        'data',
        'hora_inicio',
        'hora_fim',
        'horas_computadas',
        'horas',
        'descricao',
        'validado',
        'validado_supervisor',
        'validado_em',
        'observacao_supervisor',
    ];

    protected $casts = [
        'data_atividade' => 'date',
        'data' => 'date',
        'horas_computadas' => 'decimal:2',
        'horas' => 'decimal:2',
        'validado' => 'boolean',
        'validado_supervisor' => 'boolean',
        'validado_em' => 'datetime',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_id');
    }

    public function solicitacaoEstagio(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    public function scopeNaoValidados($query)
    {
        return $query->where('validado_supervisor', false)->where('validado', false);
    }

    public function scopePorAluno($query, int $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    public function scopePorSolicitacao($query, int $solicitacaoId)
    {
        return $query->where(function ($q) use ($solicitacaoId) {
            $q->where('solicitacao_id', $solicitacaoId)
              ->orWhere('solicitacao_estagio_id', $solicitacaoId);
        });
    }

    public function podeEditar(): bool
    {
        $statusValidado = $this->validado_supervisor ?? $this->validado ?? false;
        return !$statusValidado;
    }
}
