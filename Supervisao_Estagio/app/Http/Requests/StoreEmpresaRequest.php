<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCnpj;

class StoreEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'razao_social'   => 'required|string|max:255',
            'nome_fantasia'  => 'nullable|string|max:255',
            'cnpj'           => ['required', 'string', 'size:14', 'unique:empresas,cnpj', new ValidCnpj],
            'email'          => 'required|email|max:255|unique:empresas,email',
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
            'razao_social.required' => 'A razão social é obrigatória.',
            'cnpj.required'         => 'O CNPJ é obrigatório.',
            'cnpj.size'             => 'O CNPJ deve conter 14 dígitos.',
            'cnpj.unique'           => 'Este CNPJ já está cadastrado.',
            'email.required'        => 'O e-mail é obrigatório.',
            'email.unique'          => 'Este e-mail já está cadastrado.',
            'estado.size'           => 'O estado deve ter 2 caracteres (UF).',
        ];
    }
}
