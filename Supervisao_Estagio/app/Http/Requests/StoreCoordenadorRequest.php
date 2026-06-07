<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCoordenadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'nome'                  => 'required|string|max:150',
            'email'                 => 'required|email|max:200|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'curso_id'              => 'required|integer|exists:cursos,id,ativo,1',
            'matricula_institucional' => 'required|string|max:30|unique:coordenadores,matricula_institucional',
            'telefone'              => 'nullable|string|max:20',
            'data_inicio_funcao'    => 'required|date',
            'instituicao_id'        => 'required|exists:instituicoes,id,ativa,1',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'                     => 'O nome é obrigatório.',
            'email.required'                    => 'O e-mail é obrigatório.',
            'email.unique'                      => 'Este e-mail já está cadastrado.',
            'password.required'                 => 'A senha é obrigatória.',
            'password.min'                      => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'                => 'A confirmação da senha não corresponde.',
            'curso_id.required'                 => 'O curso é obrigatório.',
            'curso_id.exists'                   => 'O curso informado não existe ou está inativo.',
            'matricula_institucional.required'  => 'A matrícula institucional é obrigatória.',
            'matricula_institucional.unique'    => 'Esta matrícula já está cadastrada.',
            'data_inicio_funcao.required'       => 'A data de início da função é obrigatória.',
            'instituicao_id.required'           => 'A instituição é obrigatória.',
            'instituicao_id.exists'             => 'A instituição informada não existe ou está inativa.',
        ];
    }
}