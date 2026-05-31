<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
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
        return view('alunos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'matricula' => 'required|string|unique:alunos,matricula',
            'curso' => 'required|string|max:255',
            'periodo' => 'required|integer|min:1|max:10',
            'carga_horaria_obrigatoria' => 'required|integer|min:1',
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

        return view('alunos.edit', compact('aluno'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $aluno->user_id,
            'matricula' => 'required|string|unique:alunos,matricula,' . $aluno->id,
            'curso' => 'required|string|max:255',
            'periodo' => 'required|integer|min:1|max:10',
            'carga_horaria_obrigatoria' => 'required|integer|min:1',
        ]);

        $this->service->atualizar($aluno, $dados);

        return redirect()->route('alunos.show', $aluno)->with('sucesso', 'Dados atualizados com sucesso.');
    }
}
