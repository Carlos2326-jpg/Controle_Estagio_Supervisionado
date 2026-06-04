<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'aluno_id',
        'solicitacao_id',
        'solicitacao_estagio_id',
        'nome',
        'tipo',
        'caminho_arquivo',
        'mime_type',
        'tamanho_bytes',
        'status',
        'observacao',
        'observacao_coordenador',
        'validado_por',
        'validado_em',
    ];

    protected $casts = [
        'validado_em'   => 'datetime',
        'tamanho_bytes' => 'integer',
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
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_id');
    }

    public function solicitacaoEstagio(): BelongsTo
    {
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(Coordenador::class, 'validado_por');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES & FILTROS
    |--------------------------------------------------------------------------
    */

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeReprovados($query)
    {
        return $query->where('status', 'reprovado');
    }

    public function scopePorAluno($query, int $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (MÉTODOS AUXILIARES)
    |--------------------------------------------------------------------------
    */

    public function isAprovado(): bool
    {
        return $this->status === 'aprovado';
    }

    public function isReprovado(): bool
    {
        return $this->status === 'reprovado';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendente'  => 'Pendente',
            'aprovado'  => 'Aprovado',
            'reprovado' => 'Reprovado',
            default     => '-',
        };
    }

    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho_bytes ?? 0;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
