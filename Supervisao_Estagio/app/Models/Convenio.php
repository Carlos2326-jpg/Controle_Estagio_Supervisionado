<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    // EXCEP-03: Corrigida lógica OR com agrupamento
    public function scopeVencidos($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'vencido')
                ->orWhere('data_fim', '<', now());
        });
    }

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
        $dias = $this->dias_para_vencimento;
        return $dias <= $diasAviso && $dias >= 0;
    }
}
