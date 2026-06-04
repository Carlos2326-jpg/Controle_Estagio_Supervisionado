<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// RF38 – Gerenciar Instituição (validação de cadastro e edição)
// RNF03/RNF04 – Validação automática de CNPJ, e-mail e campos obrigatórios
class StoreInstituicaoRequest extends FormRequest
{
    // RNF01/RNF02 – Apenas perfil ADMIN pode cadastrar/editar instituição
    public function authorize(): bool
    {
        return true; // Restringir via middleware de role (ADMIN) nas rotas
    }

    public function rules(): array
    {
        // Permite reutilização do Request em update (ignora o próprio registro no UNIQUE)
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
            'nome_instituicao.max'      => 'O nome deve ter no máximo 200 caracteres.',
            'sigla.required'            => 'A sigla da instituição é obrigatória.',
            'sigla.unique'              => 'Esta sigla já está cadastrada.',
            'sigla.max'                 => 'A sigla deve ter no máximo 20 caracteres.',
            'cnpj.required'             => 'O CNPJ é obrigatório.',
            'cnpj.size'                 => 'O CNPJ deve conter exatamente 14 dígitos (sem pontuação).',
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
