<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoordenadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('admin');
    }

    public function rules(): array
    {
        $coordenador = $this->route('coordenador');
        
        return [
            'nome'                  => 'sometimes|string|max:150',
            'email'                 => [
                'sometimes',
                'email',
                'max:200',
                Rule::unique('users', 'email')->ignore($coordenador->user_id),
            ],
            'curso_id'              => 'sometimes|integer|exists:cursos,id,ativo,1',
            'telefone'              => 'nullable|string|max:20',
            'status'                => 'sometimes|in:ativo,inativo',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já está cadastrado.',
            'curso_id.exists' => 'O curso informado não existe ou está inativo.',
            'status.in' => 'Status deve ser ativo ou inativo.',
        ];
    }
}