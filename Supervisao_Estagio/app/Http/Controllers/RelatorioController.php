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

    // Listar cursos
    public function index(Request $request)
    {
        $cursos = $this->service->listar(
            $request->only(['ativo', 'nome'])
        );

        return view('cursos.index', compact('cursos'));
    }

    // Formulário de cadastro
    public function create()
    {
        return view('cursos.create');
    }

    // Salvar curso
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:cursos,codigo',
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade' => 'required|in:Presencial,EAD,Hibrido',
        ]);

        $this->service->cadastrar($request->all());

        return redirect()
            ->route('cursos.index')
            ->with('success', 'Curso cadastrado com sucesso!');
    }

    // Detalhes
    public function show(Curso $curso)
    {
        $curso = $this->service->detalhes($curso);

        return view('cursos.show', compact('curso'));
    }

    // Formulário de edição
    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    // Atualizar
    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:cursos,codigo,' . $curso->id,
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade' => 'required|in:Presencial,EAD,Hibrido',
        ]);

        $this->service->atualizar(
            $curso,
            $request->all()
        );

        return redirect()
            ->route('cursos.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    // Inativar
    public function inativar(Curso $curso)
    {
        $this->service->inativar($curso);

        return redirect()
            ->route('cursos.index')
            ->with('success', 'Curso inativado com sucesso!');
    }
}