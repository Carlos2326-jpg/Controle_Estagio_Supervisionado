<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCpf;

class StoreSupervisorRequest extends FormRequest
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
        return [
            'nome'      => 'required|string|max:255',
            'cargo'     => 'required|string|max:100',
            'email'     => 'required|email|max:255|unique:supervisores,email',
            'telefone'  => 'nullable|string|max:20',
            'cpf'       => ['nullable', 'string', 'max:14', 'unique:supervisores,cpf', new ValidCpf],
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
            'email.unique'   => 'Este e-mail já está cadastrado.',
            'cpf.unique'     => 'Este CPF já está cadastrado.',
        ];
    }
}
