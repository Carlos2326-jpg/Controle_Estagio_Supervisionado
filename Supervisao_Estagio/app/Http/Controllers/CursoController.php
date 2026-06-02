<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function __construct(
        protected CursoService $service
    ) {}

    public function index(Request $request)
    {
        return response()->json(
            $this->service->listar(
                $request->only(['ativo', 'nome'])
            )
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:cursos,codigo',
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade' => 'required|in:Presencial,EAD,Hibrido',
        ]);

        return response()->json(
            $this->service->cadastrar($request->all()),
            201
        );
    }

    public function show(Curso $curso)
    {
        return response()->json(
            $this->service->detalhes($curso)
        );
    }

    public function update(Request $request, Curso $curso)
    {
        return response()->json(
            $this->service->atualizar(
                $curso,
                $request->all()
            )
        );
    }

    public function inativar(Curso $curso)
    {
        $this->service->inativar($curso);

        return response()->json([
            'message' => 'Curso inativado com sucesso.'
        ]);
    }
}