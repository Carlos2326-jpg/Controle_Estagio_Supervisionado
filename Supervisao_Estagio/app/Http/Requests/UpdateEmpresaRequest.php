<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCnpj;
use Illuminate\Validation\Rule;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $empresa = $this->route('empresa');

        if (!$user) return false;

        return $user->hasRole('admin') ||
            ($user->hasRole('empresa') && $empresa->user_id === $user->id);
    }

    public function rules(): array
    {
        $empresa = $this->route('empresa');

        return [
            'razao_social'   => 'sometimes|string|max:255',
            'nome_fantasia'  => 'nullable|string|max:255',
            'cnpj'           => [
                'sometimes',
                'string',
                'size:14',
                Rule::unique('empresas', 'cnpj')->ignore($empresa->id),
                new ValidCnpj,
            ],
            'email'          => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('empresas', 'email')->ignore($empresa->id),
            ],
            'telefone'       => 'nullable|string|max:20',
            'cep'            => 'nullable|string|max:9',
            'logradouro'     => 'nullable|string|max:255',
            'numero'         => 'nullable|string|max:10',
            'complemento'    => 'nullable|string|max:100',
            'bairro'         => 'nullable|string|max:100',
            'cidade'         => 'nullable|string|max:100',
            'estado'         => 'nullable|string|size:2',
            'ramo_atividade' => 'nullable|string|max:255',
            'status'         => 'sometimes|in:ativa,inativa',
        ];
    }

    public function messages(): array
    {
        return [
            'cnpj.size'       => 'O CNPJ deve conter 14 dígitos.',
            'cnpj.unique'     => 'Este CNPJ já está cadastrado.',
            'email.unique'    => 'Este e-mail já está cadastrado.',
            'estado.size'     => 'O estado deve ter 2 caracteres (UF).',
            'status.in'       => 'Status deve ser ativa ou inativa.',
        ];
    }
}
