<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coordenador extends Model
{
    use HasFactory;

    protected $table = 'coordenadores';

    // CORRIGIDO: alinhado com StoreCoordenadorRequest
    protected $fillable = [
        'user_id',
        'matricula_institucional', // era 'matricula' — inconsistente com o Form Request
        'curso_id',                // FK para curso principal (se modelo 1-N for usado)
        'instituicao_id',          // era 'id_instituicao' no Service, corrigido para consistência
        'telefone',
        'data_inicio_funcao',
        'status',                  // ativo | inativo
    ];

    protected $casts = [
        'data_inicio_funcao' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Curso principal do coordenador (FK direta).
     * Se o sistema usa N-N (pivot coordenador_curso), use cursos() abaixo.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Cursos vinculados (N-N via pivot).
     * Mantido para compatibilidade, mas deve-se escolher um modelo: FK ou pivot.
     */
    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'coordenador_curso');
    }

    public function solicitacoes(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'validado_por');
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class, 'coordenador_id');
    }

    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
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