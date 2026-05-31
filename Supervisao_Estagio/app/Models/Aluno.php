<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricula',
        'curso',
        'periodo',
        'carga_horaria_obrigatoria',
        'carga_horaria_cumprida',
        'status_estagio',
    ];

    protected $casts = [
        'periodo' => 'integer',
        'carga_horaria_obrigatoria' => 'integer',
        'carga_horaria_cumprida' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function solicitacoes()
    {
        return $this->hasMany(SolicitacaoEstagio::class);
    }

    public function atividades()
    {
        return $this->hasMany(AtividadeEstagio::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }
}
