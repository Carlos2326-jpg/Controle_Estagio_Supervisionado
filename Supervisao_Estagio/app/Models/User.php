<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil',
        'ativo',
        'ultimo_acesso',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'ultimo_acesso' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function aluno()
    {
        return $this->hasOne(Aluno::class);
    }

    public function coordenador()
    {
        return $this->hasOne(Coordenador::class);
    }

    public function supervisor()
    {
        return $this->hasOne(SupervisorEmpresa::class);
    }

    public function alertas()
    {
        return $this->hasMany(AlertaPrazo::class, 'id_usuario_destino');
    }

    public function alertasNaoLidos()
    {
        return $this->alertas()->whereNull('data_leitura')->orderBy('data_geracao', 'desc');
    }

    public function isAluno()
    {
        return $this->perfil === 'aluno';
    }

    public function isCoordenador()
    {
        return $this->perfil === 'coordenador';
    }

    public function isSupervisor()
    {
        return $this->perfil === 'supervisor';
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
            return Avaliacao::where('id_avaliador', $this->id)->get();
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
