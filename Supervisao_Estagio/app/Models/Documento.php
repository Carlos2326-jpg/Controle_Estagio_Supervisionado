<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF09 – Enviar Documentos
// RF10 – Consultar Status de Documentos
class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'aluno_id',
        'solicitacao_estagio_id',
        'nome',
        'tipo',           // contrato | termo_compromisso | declaracao | outro
        'caminho_arquivo',
        'mime_type',
        'tamanho_bytes',
        'status',         // pendente | aprovado | reprovado
        'observacao_coordenador',
        'validado_por',   // coordenador_id
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
        return $this->belongsTo(SolicitacaoEstagio::class, 'solicitacao_estagio_id');
    }

    public function validadoPor(): BelongsTo
    {
        return $this->belongsTo(Coordenador::class, 'validado_por');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // RF10 – Filtrar por status
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
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // RF10 – Label de status legível
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendente'  => 'Pendente',
            'aprovado'  => 'Aprovado',
            'reprovado' => 'Reprovado',
            default     => '-',
        };
    }

    // RF10 – Tamanho em formato legível
    public function getTamanhoFormatadoAttribute(): string
    {
        $bytes = $this->tamanho_bytes;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        return round($bytes / 1024, 2) . ' KB';
    }
}
