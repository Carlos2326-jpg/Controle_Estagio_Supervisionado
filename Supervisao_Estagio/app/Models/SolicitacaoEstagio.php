<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoEstagio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'aluno_id',
        'empresa',
        'supervisor_nome',
        'supervisor_email',
        'data_inicio',
        'data_fim',
        'carga_horaria_semanal',
        'carga_horaria_total',
        'descricao_atividades',
        'status',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'carga_horaria_semanal' => 'integer',
        'carga_horaria_total' => 'integer',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function atividades()
    {
        return $this->hasMany(AtividadeEstagio::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeAprovadas($query)
    {
        return $query->where('status', 'aprovada');
    }

    public function isPendente()
    {
        return $this->status === 'pendente';
    }

    public function isAprovada()
    {
        return $this->status === 'aprovada';
    }
}
