<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'codigo',
        'carga_horaria_estagio',
        'modalidade',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    // Curso possui vários coordenadores
    public function coordenadores()
    {
        return $this->hasMany(Coordenador::class);
    }

    // Curso possui vários alunos
    public function alunos()
    {
        return $this->hasMany(Aluno::class);
    }
}