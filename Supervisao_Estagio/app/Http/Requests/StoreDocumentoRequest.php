<?php
// app/Http/Requests/StoreDocumentoRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreDocumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // CORRETO: Em FormRequest, $this->user() existe
        $user = $this->user();
        return $user && $user->hasRole('aluno');
    }

    public function rules(): array
    {
        return [
            'arquivo' => [
                'required',
                File::types(['pdf', 'jpg', 'jpeg', 'png'])
                    ->max(10 * 1024), // 10MB
            ],
            'tipo' => 'required|in:contrato,termo_compromisso,declaracao,outro',
            'nome' => 'nullable|string|max:255',
            'solicitacao_estagio_id' => [
                'nullable',
                'exists:solicitacoes_estagio,id,status,aprovada',
                function ($attribute, $value, $fail) {
                    // CORRETO: Em FormRequest, $this->user() existe
                    $user = $this->user();
                    $solicitacao = \App\Models\SolicitacaoEstagio::find($value);
                    
                    if ($solicitacao && $user && $solicitacao->aluno_id !== $user->aluno->id) {
                        $fail('A solicitação de estágio não pertence ao aluno autenticado.');
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'arquivo.required' => 'O arquivo é obrigatório.',
            'arquivo.types' => 'Formatos aceitos: PDF, JPG, JPEG, PNG.',
            'arquivo.max' => 'O arquivo não pode exceder 10 MB.',
            'tipo.required' => 'O tipo de documento é obrigatório.',
            'tipo.in' => 'Tipo de documento inválido.',
            'nome.max' => 'O nome do documento não pode exceder 255 caracteres.',
            'solicitacao_estagio_id.exists' => 'A solicitação de estágio informada não existe ou não está aprovada.',
        ];
    }
}