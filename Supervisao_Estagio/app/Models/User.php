<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
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
        'role', // ← IMPORTANTE: campo role deve existir
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Verifica se o usuário tem uma role específica
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Verifica se o usuário tem alguma das roles informadas
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    /**
     * Atribui uma role ao usuário
     */
    public function assignRole(string $role): void
    {
        $this->update(['role' => $role]);
    }

    // Relacionamentos
    public function aluno()
    {
        return $this->hasOne(Aluno::class);
    }

    public function coordenador()
    {
        return $this->hasOne(Coordenador::class);
    }

    public function empresa()
    {
        return $this->hasOne(Empresa::class);
    }
}