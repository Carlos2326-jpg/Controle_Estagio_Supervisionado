<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'coordenador']) ?? false;
    }

    public function rules(): array
    {
        return [
            'nome'                  => 'required|string|max:150',
            'codigo'                => 'required|string|max:20|unique:cursos,codigo',
            'carga_horaria_estagio' => 'required|integer|min:1',
            'modalidade'            => 'required|in:Presencial,EAD,Hibrido',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'                  => 'O nome do curso é obrigatório.',
            'codigo.required'                => 'O código do curso é obrigatório.',
            'codigo.unique'                  => 'Este código de curso já está cadastrado.',
            'carga_horaria_estagio.required' => 'A carga horária de estágio é obrigatória.',
            'modalidade.required'            => 'A modalidade é obrigatória.',
            'modalidade.in'                  => 'Modalidade inválida. Use: Presencial, EAD ou Hibrido.',
        ];
    }
}