<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF20 – Realizar Avaliações
class Avaliacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'coordenador_id',
        'solicitacao_estagio_id',
        'tipo',
        'nota',
        'conceito',
        'parecer',
        'pontos_fortes',
        'pontos_melhoria',
        'data_avaliacao',
    ];

    protected $casts = [
        'data_avaliacao' => 'date',
        'nota' => 'decimal:2',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function coordenador(): BelongsTo
    {
        return $this->belongsTo(Coordenador::class);
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    // Helpers
    public function getConceitoLabelAttribute(): string
    {
        return match ($this->conceito) {
            'otimo'       => 'Ótimo',
            'bom'         => 'Bom',
            'regular'     => 'Regular',
            'insuficiente' => 'Insuficiente',
            default        => '-',
        };
    }

    public function isFinal(): bool
    {
        return $this->tipo === 'final';
    }
}