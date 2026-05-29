<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// RF15, RF16 – Analisar Solicitações / Histórico
class SolicitacaoEstagio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitacoes_estagio';

    protected $fillable = [
        'aluno_id',
        'empresa_id',
        'supervisor_id',
        'curso_id',
        'data_inicio_prevista',
        'data_fim_prevista',
        'carga_horaria_semanal',
        'carga_horaria_total',
        'descricao_atividades',
        'status',
    ];

    protected $casts = [
        'data_inicio_prevista' => 'date',
        'data_fim_prevista'    => 'date',
    ];

    // Relacionamentos
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function historicoAnalises(): HasMany
    {
        return $this->hasMany(HistoricoAnalise::class);
    }

    public function contrato(): HasOne
    {
        return $this->hasOne(Contrato::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function atividades(): HasMany
    {
        return $this->hasMany(AtividadeEstagio::class);
    }

    public function avaliacao(): HasOne
    {
        return $this->hasOne(Avaliacao::class);
    }

    // Scopes
    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeAprovadas($query)
    {
        return $query->where('status', 'aprovada');
    }

    public function scopePorCurso($query, int $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    // Helpers
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    public function isAprovada(): bool
    {
        return $this->status === 'aprovada';
    }

    public function getDuracaoEmDiasAttribute(): int
    {
        return $this->data_inicio_prevista->diffInDays($this->data_fim_prevista);
    }
}