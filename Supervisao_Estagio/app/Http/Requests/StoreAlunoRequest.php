<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// RF01 – Gerenciar Dados do Aluno (validação de cadastro)
// RNF04 – Validação automática de CPF, e-mails e campos obrigatórios
class StoreAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'cpf'             => 'required|string|size:11|unique:alunos,cpf',
            'matricula'       => 'required|string|max:20|unique:alunos,matricula',
            'curso_id'        => 'required|exists:cursos,id',
            'telefone'        => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date|before:today',
            'endereco'        => 'nullable|string|max:500',
            'password'        => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'       => 'O nome do aluno é obrigatório.',
            'email.required'      => 'O e-mail é obrigatório.',
            'email.email'         => 'Informe um e-mail válido.',
            'email.unique'        => 'Este e-mail já está cadastrado.',
            'cpf.required'        => 'O CPF é obrigatório.',
            'cpf.size'            => 'O CPF deve conter 11 dígitos.',
            'cpf.unique'          => 'Este CPF já está cadastrado.',
            'matricula.required'  => 'A matrícula é obrigatória.',
            'matricula.unique'    => 'Esta matrícula já está cadastrada.',
            'curso_id.required'   => 'O curso é obrigatório.',
            'curso_id.exists'     => 'O curso informado não existe.',
        ];
    }
}
