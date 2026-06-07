<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidCnpj;

class StoreInstituicaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $instituicaoId = $this->route('instituicao')?->id;

        return [
            'nome_instituicao' => 'required|string|max:200',
            'sigla'            => [
                'required',
                'string',
                'max:20',
                Rule::unique('instituicoes', 'sigla')->ignore($instituicaoId),
            ],
            'cnpj'             => [
                'required',
                'string',
                'size:14',
                Rule::unique('instituicoes', 'cnpj')->ignore($instituicaoId),
                new ValidCnpj,
            ],
            'endereco'         => 'required|string|max:255',
            'cidade'           => 'required|string|max:100',
            'estado'           => 'required|string|size:2',
            'telefone'         => 'nullable|string|max:20',
            'email_contato'    => 'nullable|email|max:200',
            'site'             => 'nullable|url|max:200',
            'ativa'            => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nome_instituicao.required' => 'O nome da instituição é obrigatório.',
            'sigla.required'            => 'A sigla da instituição é obrigatória.',
            'sigla.unique'              => 'Esta sigla já está cadastrada.',
            'cnpj.required'             => 'O CNPJ é obrigatório.',
            'cnpj.size'                 => 'O CNPJ deve conter exatamente 14 dígitos.',
            'cnpj.unique'               => 'Este CNPJ já está cadastrado.',
            'endereco.required'         => 'O endereço é obrigatório.',
            'cidade.required'           => 'A cidade é obrigatória.',
            'estado.required'           => 'O estado (UF) é obrigatório.',
            'email_contato.email'       => 'Informe um e-mail de contato válido.',
            'site.url'                  => 'Informe uma URL válida para o site.',
        ];
    }
}