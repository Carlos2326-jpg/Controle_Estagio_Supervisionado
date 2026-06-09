<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Aluno;
use App\Models\Coordenador;
use App\Models\Empresa;
use App\Models\Curso;
use App\Models\Instituicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    /**
     * UC06 – Cadastrar Usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,coordenador,aluno,empresa',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            return response()->json([
                'message' => 'Usuário criado com sucesso',
                'user' => $user
            ], 201);
        });
    }

    /**
     * UC19 – Cadastrar Aluno (completo)
     */
    public function storeAluno(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'curso_id'        => 'required|exists:cursos,id,ativo,1',
            'matricula'       => 'required|string|max:20|unique:alunos',
            'cpf'             => 'required|string|size:11|unique:alunos',
            'telefone'        => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date',
            'endereco'        => 'nullable|string|max:500',
            'periodo_atual'   => 'nullable|string|max:10',
        ]);

        return DB::transaction(function () use ($request) {
            // Atualiza role do usuário para aluno
            User::where('id', $request->user_id)->update(['role' => 'aluno']);
            
            $aluno = Aluno::create([
                'user_id'              => $request->user_id,
                'curso_id'             => $request->curso_id,
                'matricula'            => $request->matricula,
                'cpf'                  => $request->cpf,
                'telefone'             => $request->telefone,
                'data_nascimento'      => $request->data_nascimento,
                'endereco'             => $request->endereco,
                'periodo_atual'        => $request->periodo_atual,
                'situacao_estagio'     => 'sem_estagio',
                'carga_horaria_cumprida' => 0,
                'ativo'                => true,
            ]);

            return response()->json([
                'message' => 'Aluno cadastrado com sucesso',
                'aluno' => $aluno
            ], 201);
        });
    }

    /**
     * UC28 – Cadastrar Convênio (apenas ADMIN)
     */
    public function storeConvenio(Request $request)
    {
        $request->validate([
            'empresa_id'     => 'required|exists:empresas,id',
            'numero_convenio' => 'required|string|unique:convenios',
            'data_inicio'    => 'required|date',
            'data_fim'       => 'required|date|after:data_inicio',
            'documento'      => 'nullable|file|mimes:pdf|max:5120',
        ]);

        return DB::transaction(function () use ($request) {
            $data = $request->only([
                'empresa_id', 'numero_convenio', 'data_inicio', 'data_fim', 'observacoes'
            ]);
            $data['status'] = 'ativo';

            if ($request->hasFile('documento')) {
                $data['caminho_documento'] = $request->file('documento')->store('convenios', 'private');
            }

            $convenio = \App\Models\Convenio::create($data);

            return response()->json([
                'message' => 'Convênio cadastrado com sucesso',
                'convenio' => $convenio
            ], 201);
        });
    }
}