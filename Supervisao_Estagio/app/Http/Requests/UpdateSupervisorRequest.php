<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;
use Illuminate\Validation\Rule;

class UpdateSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $supervisor = $this->route('supervisor');
        $empresa = $this->route('empresa');

        if (!$user) return false;

        return $user->hasRole('admin') ||
            ($user->hasRole('empresa') && $supervisor->empresa_id === $empresa->id);
    }

    public function rules(): array
    {
        $supervisor = $this->route('supervisor');

        return [
            'nome'      => 'sometimes|string|max:255',
            'cargo'     => 'sometimes|string|max:100',
            'email'     => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('supervisores', 'email')->ignore($supervisor->id),
            ],
            'telefone'  => 'nullable|string|max:20',
            'cpf'       => [
                'nullable',
                'string',
                'max:14',
                Rule::unique('supervisores', 'cpf')->ignore($supervisor->id),
                new ValidCpf,
            ],
            'formacao'  => 'nullable|string|max:255',
            'status'    => 'sometimes|in:ativo,inativo',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Este e-mail já está cadastrado para outro supervisor.',
            'cpf.unique'   => 'Este CPF já está cadastrado para outro supervisor.',
            'status.in'    => 'Status deve ser ativo ou inativo.',
        ];
    }
}
