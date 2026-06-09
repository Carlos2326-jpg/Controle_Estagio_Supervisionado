<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
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

    protected $casts = [
        'status' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function supervisores()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function solicitacoes()
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    public function isAtiva(): bool
    {
        return $this->status === 'ativa';
    }

    public function possuiConvenioAtivo(): bool
    {
        return $this->convenios()
            ->where('status', 'ativo')
            ->where('data_fim', '>=', now())
            ->exists();
    }

    public function convenioAtivo()
    {
        return $this->convenios()
            ->where('status', 'ativo')
            ->where('data_fim', '>=', now())
            ->first();
    }
}