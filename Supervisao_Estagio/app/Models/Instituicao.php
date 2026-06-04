<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// RF38 – Gerenciar Instituição
// RF39 – Vincular Cursos
// RF40 – Vincular Coordenadores
// RF41 – Consultar Estrutura Acadêmica
// RF42 – Emitir Relatório Institucional
// RF43 – Controlar Múltiplas Unidades
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
        'data_cadastro'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // RF39 – Uma instituição oferece muitos cursos (1:N)
    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'id_instituicao');
    }

    // RF40 – Uma instituição pode ter muitos coordenadores (1:N)
    public function coordenadores(): HasMany
    {
        return $this->hasMany(Coordenador::class, 'id_instituicao');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // RNF15 – Filtrar apenas instituições ativas (desativação lógica)
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

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // RF41 – Verifica se a instituição possui vínculos ativos (impede exclusão)
    public function possuiVinculosAtivos(): bool
    {
        return $this->cursos()->exists() || $this->coordenadores()->exists();
    }

    // RF42 – Retorna contagem de alunos ativos vinculados por curso
    public function getTotalAlunosAtivosAttribute(): int
    {
        return $this->cursos()
            ->withCount(['alunos' => fn($q) => $q->where('ativo', true)])
            ->get()
            ->sum('alunos_count');
    }

    // RF42 – Retorna contagem de estágios em andamento
    public function getTotalEstagiosAndamentoAttribute(): int
    {
        return $this->cursos()
            ->withCount(['alunos' => fn($q) => $q->where('situacao_estagio', 'em_andamento')])
            ->get()
            ->sum('alunos_count');
    }
}
