<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// RF01 – Gerenciar Dados do Aluno
// RF02 – Consultar Situação de Estágio
class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'user_id',
        'curso_id',
        'matricula',
        'cpf',
        'telefone',
        'data_nascimento',
        'endereco',
        'situacao_estagio',  // sem_estagio | em_andamento | concluido
        'carga_horaria_cumprida',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento'     => 'date',
        'ativo'               => 'boolean',
        'carga_horaria_cumprida' => 'integer',
    ];

    protected $hidden = [
        'cpf',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // RF01 – Aluno pertence a um User (autenticação)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // RF01 – Aluno pertence a um Curso ativo
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    // RF03, RF04, RF05 – Solicitações de estágio do aluno
    public function solicitacoesEstagio(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    // RF06 – Contratos do aluno (via solicitações aprovadas)
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    // RF07, RF08 – Registros de atividades
    public function atividades(): HasMany
    {
        return $this->hasMany(AtividadeEstagio::class);
    }

    // RF09, RF10 – Documentos enviados
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    // RF11 – Avaliações recebidas
    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopePorCurso($query, int $cursoId)
    {
        return $query->where('curso_id', $cursoId);
    }

    public function scopeEmEstagio($query)
    {
        return $query->where('situacao_estagio', 'em_andamento');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    // RF02 – Retorna a solicitação de estágio ativa
    public function estagioAtivo(): ?SolicitacaoEstagio
    {
        return $this->solicitacoesEstagio()
            ->where('status', 'aprovada')
            ->latest()
            ->first();
    }

    // RF02 – Percentual de horas cumpridas
    public function getPercentualHorasAttribute(): float
    {
        $cargaObrigatoria = $this->curso->carga_horaria_estagio ?? 0;
        if ($cargaObrigatoria <= 0) {
            return 0.0;
        }
        return round(($this->carga_horaria_cumprida / $cargaObrigatoria) * 100, 1);
    }

    // RF02 – Retorna label da situação
    public function getSituacaoLabelAttribute(): string
    {
        return match ($this->situacao_estagio) {
            'sem_estagio'  => 'Sem estágio',
            'em_andamento' => 'Em andamento',
            'concluido'    => 'Concluído',
            default        => '-',
        };
    }

    // RF05 – Verifica se possui solicitação pendente
    public function temSolicitacaoPendente(): bool
    {
        return $this->solicitacoesEstagio()
            ->where('status', 'pendente')
            ->exists();
    }
}
