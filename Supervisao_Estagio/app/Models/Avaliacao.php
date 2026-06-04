<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacao';
    protected $primaryKey = 'id_avaliacao';
    public $timestamps = true; // Ativado para suportar o padrão do grupo, mas tolerando o legado

    protected $fillable = [
        // Campos do Carlos (Legado)
        'id_contrato',
        'tipo_avaliador',
        'id_avaliador',
        'periodo_referencia',
        'nota_desempenho',
        'nota_comportamento',
        'nota_pontualidade',
        'media_final',
        'situacao_final',
        
        // Campos da Main (Coordenador / Novas Funcionalidades)
        'aluno_id',
        'coordenador_id',
        'solicitacao_estagio_id',
        'tipo',
        'nota',
        'conceito',
        'parecer',
        'pontos_fortes',
        'pontos_melhoria',
        'data_avaliacao'
    ];

    protected $casts = [
        'nota_desempenho' => 'decimal:2',
        'nota_comportamento' => 'decimal:2',
        'nota_pontualidade' => 'decimal:2',
        'media_final' => 'decimal:2',
        'nota' => 'decimal:2',
        'data_avaliacao' => 'datetime'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS MESCLADOS
    |--------------------------------------------------------------------------
    */

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(ContratoEstagio::class, 'id_contrato', 'id_contrato');
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_avaliador', 'id_usuario');
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class, 'aluno_id');
    }

    public function coordenador(): BelongsTo
    {
        return $this->belongsTo(Coordenador::class, 'coordenador_id');
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS AUXILIARES (CARLOS)
    |--------------------------------------------------------------------------
    */

    public function calcularMedia()
    {
        $media = ($this->nota_desempenho + $this->nota_comportamento + $this->nota_pontualidade) / 3;
        $this->media_final = round($media, 2);
        return $this->media_final;
    }

    public function getMediaFormatada()
    {
        return number_format($this->media_final ?? $this->nota ?? 0, 2, ',', '.');
    }

    public function getNotasFormatadas()
    {
        return [
            'desempenho' => number_format($this->nota_desempenho, 2, ',', '.'),
            'comportamento' => number_format($this->nota_comportamento, 2, ',', '.'),
            'pontualidade' => number_format($this->nota_pontualidade, 2, ',', '.'),
            'media' => number_format($this->media_final ?? $this->nota ?? 0, 2, ',', '.')
        ];
    }

    public function getStatusBadge()
    {
        if ($this->situacao_final) {
            return $this->situacao_final === 'APROVADO' 
                ? '<span class="badge bg-success">Aprovado</span>'
                : '<span class="badge bg-danger">Reprovado</span>';
        }
        
        $media = $this->media_final ?? $this->nota ?? 0;
        if ($media >= 7) {
            return '<span class="badge bg-info">Bom desempenho</span>';
        } elseif ($media >= 5) {
            return '<span class="badge bg-warning">Atenção</span>';
        }
        return '<span class="badge bg-danger">Necessita melhora</span>';
    }

    public function isAprovado()
    {
        if ($this->situacao_final) {
            return $this->situacao_final === 'APROVADO';
        }
        $media = $this->media_final ?? $this->nota ?? 0;
        return $media >= 7;
    }

    public function scopeAvaliacoesPendentes($query, $userId, $tipo = null)
    {
        $query->where('id_avaliador', $userId)->whereNull('parecer');
        if ($tipo) {
            $query->where('tipo_avaliador', $tipo);
        }
        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (MAIN / COORDENADOR)
    |--------------------------------------------------------------------------
    */

    public function getConceitoLabelAttribute(): string
    {
        return match ($this->conceito) {
            'otimo'        => 'Ótimo',
            'bom'          => 'Bom',
            'regular'      => 'Regular',
            'insuficiente' => 'Insuficiente',
            default        => '-',
        };
    }

    public function isFinal(): bool
    {
        return $this->tipo === 'final';
    }
}
