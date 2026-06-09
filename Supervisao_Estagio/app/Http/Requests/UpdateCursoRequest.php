<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'coordenador']) ?? false;
    }

    public function rules(): array
    {
        $curso = $this->route('curso');

        return [
            'nome'                  => 'required|string|max:150',
            'codigo'                => [
                'required',
                'string',
                'max:20',
                Rule::unique('cursos', 'codigo')->ignore($curso->id),
            ],
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade'            => 'required|in:Presencial,EAD,Hibrido',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'    => 'O nome do curso é obrigatório.',
            'codigo.required'  => 'O código do curso é obrigatório.',
            'codigo.unique'    => 'Este código já está em uso por outro curso.',
            'modalidade.in'    => 'Modalidade inválida.',
        ];
    }
}