<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'situacao_estagio',
        'carga_horaria_cumprida',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento'         => 'date',
        'ativo'                   => 'boolean',
        'carga_horaria_cumprida'  => 'integer',
    ];

    protected $hidden = [
        'cpf',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
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

    // EXCEP-02: Corrigido para evitar divisão por zero e null
    public function getPercentualHorasAttribute(): float
    {
        $cargaObrigatoria = $this->curso->carga_horaria_estagio ?? 0;
        $cargaCumprida = $this->carga_horaria_cumprida ?? 0;
        
        if ($cargaObrigatoria <= 0) {
            return 0.0;
        }
        return round(($cargaCumprida / $cargaObrigatoria) * 100, 1);
    }

    public function getSituacaoLabelAttribute(): string
    {
        return match ($this->situacao_estagio) {
            'sem_estagio'  => 'Sem estágio',
            'em_andamento' => 'Em andamento',
            'concluido'    => 'Concluído',
            default        => '-',
        };
    }

    public function temSolicitacaoPendente(): bool
    {
        return $this->solicitacoesEstagio()
            ->where('status', 'pendente')
            ->exists();
    }
}