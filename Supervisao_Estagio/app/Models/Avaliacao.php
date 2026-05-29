<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $table = 'avaliacao';
    protected $primaryKey = 'id_avaliacao';
    public $timestamps = false;

    protected $fillable = [
        'id_contrato',
        'tipo_avaliador',
        'id_avaliador',
        'periodo_referencia',
        'nota_desempenho',
        'nota_comportamento',
        'nota_pontualidade',
        'media_final',
        'parecer',
        'situacao_final',
        'data_avaliacao'
    ];

    protected $casts = [
        'nota_desempenho' => 'decimal:2',
        'nota_comportamento' => 'decimal:2',
        'nota_pontualidade' => 'decimal:2',
        'media_final' => 'decimal:2',
        'data_avaliacao' => 'datetime'
    ];

    // Relacionamentos
    public function contrato()
    {
        return $this->belongsTo(ContratoEstagio::class, 'id_contrato', 'id_contrato');
    }

    public function avaliador()
    {
        return $this->belongsTo(User::class, 'id_avaliador', 'id_usuario');
    }

    // Métodos auxiliares
    public function calcularMedia()
    {
        $media = ($this->nota_desempenho + $this->nota_comportamento + $this->nota_pontualidade) / 3;
        $this->media_final = round($media, 2);
        return $this->media_final;
    }

    public function getMediaFormatada()
    {
        return number_format($this->media_final, 2, ',', '.');
    }

    public function getNotasFormatadas()
    {
        return [
            'desempenho' => number_format($this->nota_desempenho, 2, ',', '.'),
            'comportamento' => number_format($this->nota_comportamento, 2, ',', '.'),
            'pontualidade' => number_format($this->nota_pontualidade, 2, ',', '.'),
            'media' => number_format($this->media_final, 2, ',', '.')
        ];
    }

    public function getStatusBadge()
    {
        if ($this->situacao_final) {
            return $this->situacao_final === 'APROVADO' 
                ? '<span class="badge bg-success">Aprovado</span>'
                : '<span class="badge bg-danger">Reprovado</span>';
        }
        
        if ($this->media_final >= 7) {
            return '<span class="badge bg-info">Bom desempenho</span>';
        } elseif ($this->media_final >= 5) {
            return '<span class="badge bg-warning">Atenção</span>';
        }
        return '<span class="badge bg-danger">Necessita melhora</span>';
    }

    public function isAprovado()
    {
        if ($this->situacao_final) {
            return $this->situacao_final === 'APROVADO';
        }
        return $this->media_final >= 7;
    }

    public function scopeAvaliacoesPendentes($query, $userId, $tipo = null)
    {
        $query->where('id_avaliador', $userId)
              ->whereNull('parecer');
        
        if ($tipo) {
            $query->where('tipo_avaliador', $tipo);
        }
        
        return $query;
    }
}