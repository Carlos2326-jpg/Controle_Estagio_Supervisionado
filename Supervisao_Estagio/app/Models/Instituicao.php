<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instituicao extends Model
{
    use HasFactory;

    protected $table = 'instituicoes';

    protected $fillable = [
        'nome_instituicao',
        'sigla',
        'cnpj',
        'endereco',
        'cidade',
        'estado',
        'telefone',
        'email_contato',
        'site',
        'ativa',
    ];

    protected $casts = [
        'ativa'          => 'boolean',
    ];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'id_instituicao');
    }

    public function coordenadores(): HasMany
    {
        return $this->hasMany(Coordenador::class, 'id_instituicao');
    }

    public function scopeAtivas($query)
    {
        return $query->where('ativa', true);
    }

    public function scopePorCidade($query, string $cidade)
    {
        return $query->where('cidade', $cidade);
    }

    public function scopePorEstado($query, string $uf)
    {
        return $query->where('estado', $uf);
    }

    // PERF-03: Otimizado com eager loading
    public function getTotalAlunosAtivosAttribute(): int
    {
        return $this->cursos()
            ->withCount(['alunos' => fn($q) => $q->where('ativo', true)])
            ->get()
            ->sum('alunos_count');
    }

    public function getTotalEstagiosAndamentoAttribute(): int
    {
        return $this->cursos()
            ->withCount(['alunos' => fn($q) => $q->where('situacao_estagio', 'em_andamento')])
            ->get()
            ->sum('alunos_count');
    }
}