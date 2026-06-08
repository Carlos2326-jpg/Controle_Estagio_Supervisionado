<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('aluno');
    }

    public function rules(): array
    {
        return [
            'arquivo'                => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'tipo'                   => 'required|in:contrato,termo_compromisso,declaracao,outro',
            'nome'                   => 'nullable|string|max:255',
            'solicitacao_estagio_id' => 'nullable|exists:solicitacoes_estagio,id',
        ];
    }

    public function messages(): array
    {
        return [
            'arquivo.required' => 'O arquivo é obrigatório.',
            'arquivo.mimes'    => 'Formatos aceitos: PDF, JPG, JPEG, PNG.',
            'arquivo.max'      => 'O arquivo não pode exceder 10 MB.',
            'tipo.required'    => 'O tipo de documento é obrigatório.',
            'tipo.in'          => 'Tipo de documento inválido.',
        ];
    }
}
