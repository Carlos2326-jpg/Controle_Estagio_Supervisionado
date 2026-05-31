<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF25 – Gerenciar Convênios
class Convenio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'empresa_id',
        'numero_convenio',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim'    => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
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

    public function scopeVencidos($query)
    {
        return $query->where('status', 'vencido')
            ->orWhere('data_fim', '<', now());
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAtivo(): bool
    {
        return $this->status === 'ativo' && $this->data_fim->gte(now());
    }

    public function getDiasParaVencimentoAttribute(): int
    {
        return (int) now()->diffInDays($this->data_fim, false);
    }

    public function estaVencendo(int $diasAviso = 30): bool
    {
        return $this->dias_para_vencimento <= $diasAviso && $this->dias_para_vencimento >= 0;
    }
}
