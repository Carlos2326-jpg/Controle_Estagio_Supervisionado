<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtividadeEstagio extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'solicitacao_id',
        'data_atividade',
        'hora_inicio',
        'hora_fim',
        'horas_computadas',
        'descricao',
        'validado',
    ];

    protected $casts = [
        'data_atividade' => 'date',
        'horas_computadas' => 'decimal:2',
        'validado' => 'boolean',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function solicitacao()
    {
        return $this->belongsTo(SolicitacaoEstagio::class);
    }

    public function podeEditar()
    {
        return $this->validado === false;
    }
}
