<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Services\CursoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursoController extends Controller
{
    public function __construct(
        protected CursoService $service
    ) {}

    /**
     * Listar cursos - Permitido para admin e coordenador
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'coordenador'])) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $cursos = $this->service->listar(
            $request->only(['ativo', 'nome'])
        );

        return response()->json($cursos);
    }

    /**
     * Formulário de cadastro - Apenas admin
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        
        return view('cursos.create');
    }

    /**
     * Salvar curso - Apenas admin
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $request->validate([
            'nome' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:cursos,codigo',
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade' => 'required|in:Presencial,EAD,Hibrido',
        ]);

        $curso = $this->service->cadastrar($request->all());

        return response()->json($curso, 201);
    }

    /**
     * Detalhes do curso - Permitido para admin e coordenador
     */
    public function show(Curso $curso)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'coordenador'])) {
            abort(403, 'Acesso não autorizado.');
        }
        
        $curso = $this->service->detalhes($curso);

        return response()->json($curso);
    }

    /**
     * Formulário de edição - Apenas admin
     */
    public function edit(Curso $curso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        
        return view('cursos.edit', compact('curso'));
    }

    /**
     * Atualizar curso - Apenas admin
     */
    public function update(Request $request, Curso $curso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $request->validate([
            'nome' => 'required|string|max:150',
            'codigo' => 'required|string|max:20|unique:cursos,codigo,' . $curso->id,
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade' => 'required|in:Presencial,EAD,Hibrido',
        ]);

        $this->service->atualizar($curso, $request->all());

        return response()->json(['message' => 'Curso atualizado com sucesso!']);
    }

    /**
     * Inativar/Ativar curso - Apenas admin
     */
    public function inativar(Curso $curso)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        
        $this->service->inativar($curso);

        return response()->json([
            'message' => $curso->ativo ? 'Curso ativado com sucesso!' : 'Curso inativado com sucesso!',
            'ativo' => $curso->ativo
        ]);
    }
}