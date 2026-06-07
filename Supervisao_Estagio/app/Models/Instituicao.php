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

    // INT-06: Verificação completa de vínculos ativos
    public function possuiVinculosAtivos(): bool
    {
        // Verifica cursos com alunos ativos
        $cursosComAlunosAtivos = $this->cursos()
            ->whereHas('alunos', fn($q) => $q->where('ativo', true))
            ->exists();
            
        // Verifica cursos com estágios em andamento
        $cursosComEstagiosAtivos = $this->cursos()
            ->whereHas('alunos', fn($q) => $q->where('situacao_estagio', 'em_andamento'))
            ->exists();
            
        // Verifica coordenadores ativos
        $coordenadoresAtivos = $this->coordenadores()
            ->where('status', 'ativo')
            ->exists();
            
        return $cursosComAlunosAtivos || $cursosComEstagiosAtivos || $coordenadoresAtivos;
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