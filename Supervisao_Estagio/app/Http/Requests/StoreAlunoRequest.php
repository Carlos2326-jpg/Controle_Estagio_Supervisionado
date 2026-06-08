<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;
use Illuminate\Validation\Rule;

class StoreAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('coordenador'));
    }

    public function rules(): array
    {
        return [
            'nome'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'cpf'             => ['required', 'string', 'size:11', 'unique:alunos,cpf', new ValidCpf],
            'matricula'       => 'required|string|max:20|unique:alunos,matricula',
            'curso_id'        => 'required|exists:cursos,id,ativo,1',
            'telefone'        => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date|before:today',
            'endereco'        => 'nullable|string|max:500',
            'password'        => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'       => 'O nome do aluno é obrigatório.',
            'email.required'      => 'O e-mail é obrigatório.',
            'email.unique'        => 'Este e-mail já está cadastrado.',
            'cpf.required'        => 'O CPF é obrigatório.',
            'cpf.size'            => 'O CPF deve conter 11 dígitos.',
            'cpf.unique'          => 'Este CPF já está cadastrado.',
            'matricula.required'  => 'A matrícula é obrigatória.',
            'matricula.unique'    => 'Esta matrícula já está cadastrada.',
            'curso_id.required'   => 'O curso é obrigatório.',
            'curso_id.exists'     => 'O curso informado não existe ou está inativo.',
            'password.required'   => 'A senha é obrigatória.',
            'password.min'        => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'  => 'A confirmação da senha não corresponde.',
        ];
    }
}