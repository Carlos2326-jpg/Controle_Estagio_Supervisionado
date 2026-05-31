<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF29 – Avaliar Estagiários (pelo supervisor)
class AvaliacaoSupervisor extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes_supervisor';

    protected $fillable = [
        'supervisor_id',
        'solicitacao_estagio_id',
        'pontualidade',
        'proatividade',
        'qualidade_trabalho',
        'relacionamento',
        'nota_geral',
        'observacoes',
        'data_avaliacao',
    ];

    protected $casts = [
        'data_avaliacao'   => 'date',
        'nota_geral'       => 'decimal:2',
        'pontualidade'     => 'decimal:2',
        'proatividade'     => 'decimal:2',
        'qualidade_trabalho' => 'decimal:2',
        'relacionamento'   => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }
}
