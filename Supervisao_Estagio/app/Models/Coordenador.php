<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordenador extends Model {
    protected $table = 'coordenadores';
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricula',
        'departamento',
        'instituicao_id',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(
            Curso::class,
            'coordenador_curso'
        );
    }

    public function solicitacoes()
    {
        return $this->hasMany(
            SolicitacaoEstagio::class
        );
    }

    public function documentos()
    {
        return $this->hasMany(
            Documento::class,
            'aprovado_por'
        );
    }

    public function avaliacoes()
    {
        return $this->hasMany(
            Avaliacao::class,
            'avaliador_id'
        );
    }

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class);
    }
}