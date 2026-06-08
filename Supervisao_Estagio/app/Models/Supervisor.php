<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// RF26 – Gerenciar Supervisores
class Supervisor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'supervisores';

    protected $fillable = [
        'empresa_id',
        'nome',
        'cargo',
        'email',
        'telefone',
        'cpf',
        'formacao',
        'status',
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

    // RF26/RF29 – Estagiários supervisionados
    public function solicitacoes(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    // RF29 – Avaliações realizadas pelo supervisor
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(AvaliacaoSupervisor::class);
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

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }
}
