<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF16 – Registrar Histórico de Análises
class HistoricoAnalise extends Model
{
    protected $table = 'historico_analises';

    protected $fillable = [
        'solicitacao_estagio_id',
        'coordenador_id',
        'decisao',
        'justificativa',
        'analisado_em',
    ];

    protected $casts = [
        'analisado_em' => 'datetime',
    ];

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    public function coordenador(): BelongsTo
    {
        return $this->belongsTo(Coordenador::class);
    }

    public function isAprovada(): bool
    {
        return $this->decisao === 'aprovada';
    }
}