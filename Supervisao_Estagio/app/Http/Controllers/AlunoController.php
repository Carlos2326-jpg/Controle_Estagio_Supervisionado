<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Curso;
use App\Services\AlunoService;
use Illuminate\Http\Request;

class AlunoController extends Controller
{
    public function __construct(protected AlunoService $service) {}

    public function index()
    {
        $alunos = $this->service->listar();
        return view('alunos.index', compact('alunos'));
    }

    public function create()
    {
        $cursos = Curso::all();
        return view('alunos.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8',
            'matricula' => 'required|string|unique:alunos,matricula',
            'curso_id'  => 'required|exists:cursos,id',
            'cpf'       => 'required|digits:11|unique:alunos,cpf',
            'telefone'  => 'nullable|string|max:20',
        ]);

        $this->service->criar($dados);

        return redirect()->route('alunos.index')->with('sucesso', 'Aluno cadastrado com sucesso.');
    }

    public function show(Aluno $aluno)
    {
        $aluno->load('user', 'solicitacoes', 'documentos');
        return view('alunos.show', compact('aluno'));
    }

    public function edit(Aluno $aluno)
    {
        $aluno->load('user');
        $cursos = Curso::all();
        return view('alunos.edit', compact('aluno', 'cursos'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $dados = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $aluno->user_id,
            'matricula' => 'required|string|unique:alunos,matricula,' . $aluno->id,
            'curso_id'  => 'required|exists:cursos,id',
            'cpf'       => 'required|digits:11|unique:alunos,cpf,' . $aluno->id,
            'telefone'  => 'nullable|string|max:20',
        ]);

        $this->service->atualizar($aluno, $dados);

        return redirect()->route('alunos.show', $aluno)->with('sucesso', 'Dados atualizados com sucesso.');
    }
}
