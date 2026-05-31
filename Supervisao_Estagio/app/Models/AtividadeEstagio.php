<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// RF07 – Registrar Atividades de Estágio
// RF08 – Editar Registros de Atividades
class AtividadeEstagio extends Model
{
    use HasFactory;

    protected $table = 'atividades_estagio';

    protected $fillable = [
        'aluno_id',
        'solicitacao_estagio_id',
        'data',
        'descricao',
        'horas',
        'validado_supervisor',  // RF08 – só pode editar se não validado
        'validado_em',
        'observacao_supervisor',
    ];

    protected $casts = [
        'data'                => 'date',
        'validado_supervisor' => 'boolean',
        'validado_em'         => 'datetime',
        'horas'               => 'decimal:2',
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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // RF08 – Apenas registros ainda não validados podem ser editados/excluídos
    public function scopeNaoValidados($query)
    {
        return $query->where('validado_supervisor', false);
    }

    public function scopePorAluno($query, int $alunoId)
    {
        return $query->where('aluno_id', $alunoId);
    }

    public function scopePorSolicitacao($query, int $solicitacaoId)
    {
        return $query->where('solicitacao_estagio_id', $solicitacaoId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // RF08 – Verifica se o registro ainda pode ser editado/excluído
    public function podeEditar(): bool
    {
        return !$this->validado_supervisor;
    }
}
