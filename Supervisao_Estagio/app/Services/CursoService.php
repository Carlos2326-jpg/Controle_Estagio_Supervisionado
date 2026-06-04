<?php

namespace App\Services;

use App\Models\Curso;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CursoService
{
    public function listar(array $filtros = []): LengthAwarePaginator
    {
        return Curso::query()
            ->when(isset($filtros['ativo']), function ($q) use ($filtros) {
                $q->where('ativo', $filtros['ativo']);
            })
            ->when(isset($filtros['nome']), function ($q) use ($filtros) {
                $q->where('nome', 'like', "%{$filtros['nome']}%");
            })
            ->paginate(20);
    }

    public function cadastrar(array $dados): Curso
    {
        return Curso::create($dados);
    }

    public function atualizar(Curso $curso, array $dados): Curso
    {
        $curso->update($dados);

        return $curso->fresh();
    }

    public function inativar(Curso $curso): void
    {
        $curso->update([
            'ativo' => false
        ]);
    }

    public function detalhes(Curso $curso): Curso
    {
        return $curso->load([
            'alunos.user',
            'coordenadores.user'
        ]);
    }
}