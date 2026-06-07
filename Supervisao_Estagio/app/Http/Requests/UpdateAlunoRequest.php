<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAlunoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $aluno = $this->route('aluno');
        
        return auth()->check() && (
            auth()->user()->hasRole('admin') ||
            auth()->user()->hasRole('coordenador') ||
            (auth()->user()->hasRole('aluno') && auth()->id() === $aluno->user_id)
        );
    }

    public function rules(): array
    {
        $aluno = $this->route('aluno');
        
        return [
            'nome'            => 'sometimes|string|max:255',
            'email'           => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($aluno->user_id),
            ],
            'telefone'        => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date|before:today',
            'endereco'        => 'nullable|string|max:500',
            'curso_id'        => 'sometimes|exists:cursos,id,ativo,1',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'             => 'Este e-mail já está cadastrado.',
            'data_nascimento.before'   => 'A data de nascimento não pode ser futura.',
            'curso_id.exists'          => 'O curso informado não existe ou está inativo.',
        ];
    }
}