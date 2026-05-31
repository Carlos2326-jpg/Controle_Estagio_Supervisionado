<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'solicitacao_id',
        'nome',
        'tipo',
        'caminho_arquivo',
        'status',
        'observacao',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function solicitacao()
    {
        return $this->belongsTo(SolicitacaoEstagio::class);
    }

    public function isAprovado()
    {
        return $this->status === 'aprovado';
    }

    public function isReprovado()
    {
        return $this->status === 'reprovado';
    }
}
