<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;

class StoreSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('empresa');
    }

    public function rules(): array
    {
        return [
            'nome'      => 'required|string|max:255',
            'cargo'     => 'required|string|max:100',
            'email'     => 'required|email|max:255',
            'telefone'  => 'nullable|string|max:20',
            'cpf'       => ['nullable', 'string', 'max:14', new ValidCpf],
            'formacao'  => 'nullable|string|max:255',
            'status'    => 'sometimes|in:ativo,inativo',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'  => 'O nome do supervisor é obrigatório.',
            'cargo.required' => 'O cargo é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
        ];
    }
}