<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\ValidCnpj;

class StoreInstituicaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('admin');
    }

    /**
     * Prepare the data for validation.
     * Remove formatação do CNPJ antes da validação
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cnpj')) {
            // Remove formatação (pontos, barras, traços, espaços)
            $cnpjLimpo = preg_replace('/[^0-9]/', '', $this->cnpj);
            $this->merge([
                'cnpj' => $cnpjLimpo,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
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
                'size:14',  // Após limpeza, deve ter exatamente 14 dígitos
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

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nome_instituicao.required' => 'O nome da instituição é obrigatório.',
            'nome_instituicao.max'      => 'O nome deve ter no máximo 200 caracteres.',
            'sigla.required'            => 'A sigla da instituição é obrigatória.',
            'sigla.max'                 => 'A sigla deve ter no máximo 20 caracteres.',
            'sigla.unique'              => 'Esta sigla já está cadastrada.',
            'cnpj.required'             => 'O CNPJ é obrigatório.',
            'cnpj.size'                 => 'O CNPJ deve conter exatamente 14 dígitos.',
            'cnpj.unique'               => 'Este CNPJ já está cadastrado.',
            'endereco.required'         => 'O endereço é obrigatório.',
            'cidade.required'           => 'A cidade é obrigatória.',
            'estado.required'           => 'O estado (UF) é obrigatório.',
            'estado.size'               => 'O estado deve ser informado como UF (2 letras, ex: SP).',
            'email_contato.email'       => 'Informe um e-mail de contato válido.',
            'site.url'                  => 'Informe uma URL válida para o site.',
        ];
    }
}