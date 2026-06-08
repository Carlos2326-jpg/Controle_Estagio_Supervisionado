<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCoordenadorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome'                  => 'required|string|max:150',
            'email'                 => 'required|email|max:200|unique:users,email',
            'password'              => 'nullable|string|min:8',
            'curso_id'              => 'required|integer|exists:cursos,id',
            'matricula_institucional' => 'required|string|max:30|unique:coordenadores,matricula_institucional',
            'telefone'              => 'nullable|string|max:20',
            'data_inicio_funcao'    => 'required|date',
            'instituicao_id' => 'required|exists:instituicoes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'                     => 'O nome é obrigatório.',
            'email.required'                    => 'O e-mail é obrigatório.',
            'email.unique'                      => 'Este e-mail já está cadastrado.',
            'curso_id.required'                 => 'O curso é obrigatório.',
            'curso_id.exists'                   => 'O curso informado não existe.',
            'matricula_institucional.required'  => 'A matrícula institucional é obrigatória.',
            'matricula_institucional.unique'    => 'Esta matrícula já está cadastrada.',
            'data_inicio_funcao.required'       => 'A data de início da função é obrigatória.',
            'data_inicio_funcao.date'           => 'A data de início da função é inválida.',
        ];
    }
}