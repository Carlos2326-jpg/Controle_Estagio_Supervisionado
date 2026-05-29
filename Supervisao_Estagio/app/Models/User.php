<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email',
        'senha_hash',
        'perfil',
        'ativo',
        'data_criacao',
        'ultimo_acesso'
    ];

    protected $hidden = [
        'senha_hash',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_criacao' => 'datetime',
        'ultimo_acesso' => 'datetime'
    ];

    // Mutator para senha
    public function setSenhaHashAttribute($value)
    {
        $this->attributes['senha_hash'] = bcrypt($value);
    }

    // Relacionamentos
    public function aluno()
    {
        return $this->hasOne(Aluno::class, 'id_usuario', 'id_usuario');
    }

    public function coordenador()
    {
        return $this->hasOne(Coordenador::class, 'id_usuario', 'id_usuario');
    }

    public function supervisor()
    {
        return $this->hasOne(SupervisorEmpresa::class, 'id_usuario', 'id_usuario');
    }

    public function alertas()
    {
        return $this->hasMany(AlertaPrazo::class, 'id_usuario_destino', 'id_usuario');
    }

    public function alertasNaoLidos()
    {
        return $this->alertas()->whereNull('data_leitura')->orderBy('data_geracao', 'desc');
    }

    // Métodos auxiliares
    public function isAluno()
    {
        return $this->perfil === 'ALUNO' && $this->aluno;
    }

    public function isCoordenador()
    {
        return $this->perfil === 'COORDENADOR' && $this->coordenador;
    }

    public function isSupervisor()
    {
        return $this->perfil === 'SUPERVISOR' && $this->supervisor;
    }

    public function getContratos()
    {
        if ($this->isAluno()) {
            return $this->aluno->contratos()->with('solicitacao')->get();
        } elseif ($this->isCoordenador()) {
            return ContratoEstagio::whereHas('solicitacao', function ($q) {
                $q->where('id_coordenador', $this->coordenador->id_coordenador);
            })->get();
        } elseif ($this->isSupervisor()) {
            return ContratoEstagio::whereHas('solicitacao', function ($q) {
                $q->where('id_supervisor', $this->supervisor->id_supervisor);
            })->get();
        }
        return collect();
    }

    public function getAvaliacoes()
    {
        if ($this->isAluno()) {
            return $this->aluno->avaliacoes;
        } elseif ($this->isCoordenador() || $this->isSupervisor()) {
            return Avaliacao::where('id_avaliador', $this->id_usuario)->get();
        }
        return collect();
    }

    public function getAlertasNaoLidos()
    {
        return $this->alertas()->whereNull('data_leitura')->orderBy('data_geracao', 'desc')->get();
    }

    public function getAlertasCount()
    {
        return $this->alertas()->whereNull('data_leitura')->count();
    }
}
