<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'user_id',
        'curso_id',
        'matricula',
        'curso',
        'periodo',
        'cpf',
        'telefone',
        'data_nascimento',
        'endereco',
        'status_estagio',
        'situacao_estagio',
        'carga_horaria_obrigatoria',
        'carga_horaria_cumprida',
        'ativo',
    ];

    protected $casts = [
        'periodo' => 'integer',
        'carga_horaria_obrigatoria' => 'integer',
        'carga_horaria_cumprida' => 'integer',
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    protected $hidden = [
        'cpf',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cursoRelacionado(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function solicitacoes(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    public function solicitacoesEstagio(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class);
    }

    public function atividades(): HasMany
    {
        return $this->hasMany(AtividadeEstagio::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function avaliacoes(): HasMany
    {
        return $this->hasMany(Avaliacao::class);
    }

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

    public function estagioAtivo(): ?SolicitacaoEstagio
    {
        return $this->solicitacoesEstagio()
            ->where('status', 'aprovada')
            ->latest()
            ->first();
    }

    public function getPercentualHorasAttribute(): float
    {
        $cargaObrigatoria = $this->carga_horaria_obrigatoria ?? $this->cursoRelacionado->carga_horaria_estagio ?? 0;
        if ($cargaObrigatoria <= 0) {
            return 0.0;
        }
        return round(($this->carga_horaria_cumprida / $cargaObrigatoria) * 100, 1);
    }

    public function getSituacaoLabelAttribute(): string
    {
        $situacao = $this->situacao_estagio ?? $this->status_estagio;
        return match ($situacao) {
            'sem_estagio', 'pendente' => 'Sem estágio',
            'em_andamento', 'aprovada' => 'Em andamento',
            'concluido' => 'Concluído',
            default => '-',
        };
    }

    public function temSolicitacaoPendente(): bool
    {
        return $this->solicitacoesEstagio()
            ->where('status', 'pendente')
            ->exists();
    }
}
