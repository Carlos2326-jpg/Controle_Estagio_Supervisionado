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
        'cpf',
        'telefone',
        'data_nascimento',
        'endereco',
        'situacao_estagio',
        'carga_horaria_cumprida',
        'ativo',
    ];

    protected $casts = [
        'carga_horaria_cumprida' => 'integer',
        'data_nascimento'        => 'date',
        'ativo'                  => 'boolean',
    ];

    protected $hidden = ['cpf'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function solicitacoes(): HasMany
    {
        return $this->hasMany(SolicitacaoEstagio::class);
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

    public function scopeEmEstagio($query)
    {
        return $query->where('situacao_estagio', 'em_andamento');
    }

    public function estagioAtivo(): ?SolicitacaoEstagio
    {
        return $this->solicitacoes()->where('status', 'aprovada')->latest()->first();
    }

    public function temSolicitacaoPendente(): bool
    {
        return $this->solicitacoes()->where('status', 'pendente')->exists();
    }
}
