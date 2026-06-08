<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

// RF24 – Gerenciar Empresas
class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'ramo_atividade',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // RF25 – Convênios
    public function convenios(): HasMany
    {
        return $this->hasMany(Convenio::class);
    }

    // RF26 – Supervisores
    public function supervisores(): HasMany
    {
        return $this->hasMany(Supervisor::class);
    }

    // RF27/RF30 – Solicitações e estagiários vinculados
    public function solicitacoes(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativa');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAtiva(): bool
    {
        return $this->status === 'ativa';
    }

    public function convenioAtivo()
    {
        return $this->convenios()->where('status', 'ativo')->latest()->first();
    }

    public function possuiConvenioAtivo(): bool
    {
        return $this->convenios()->where('status', 'ativo')->exists();
    }
}