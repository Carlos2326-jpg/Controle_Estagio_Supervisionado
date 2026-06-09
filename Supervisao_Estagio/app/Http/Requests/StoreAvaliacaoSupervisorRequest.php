<?php
// app/Http/Requests/StoreAvaliacaoSupervisorRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        // CORRETO: Em FormRequest, $this->user() existe
        $user = $this->user();
        $supervisor = $this->route('supervisor');
        $empresa = $this->route('empresa');

        if (!$user) return false;

        return $user->hasRole('empresa') &&
            $supervisor && 
            $empresa &&
            $supervisor->empresa_id === $empresa->id;
    }

    public function rules(): array
    {
        return [
            'pontualidade' => 'nullable|numeric|min:0|max:10',
            'proatividade' => 'nullable|numeric|min:0|max:10',
            'qualidade_trabalho' => 'nullable|numeric|min:0|max:10',
            'relacionamento' => 'nullable|numeric|min:0|max:10',
            'observacoes' => 'nullable|string|max:1000',
            'data_avaliacao' => 'required|date|before_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'data_avaliacao.required' => 'A data da avaliação é obrigatória.',
            'data_avaliacao.date' => 'Informe uma data válida.',
            'data_avaliacao.before_or_equal' => 'A data da avaliação não pode ser futura.',
            'pontualidade.min' => 'A pontualidade mínima é 0.',
            'pontualidade.max' => 'A pontualidade máxima é 10.',
            'proatividade.min' => 'A proatividade mínima é 0.',
            'proatividade.max' => 'A proatividade máxima é 10.',
            'qualidade_trabalho.min' => 'A qualidade do trabalho mínima é 0.',
            'qualidade_trabalho.max' => 'A qualidade do trabalho máxima é 10.',
            'relacionamento.min' => 'O relacionamento mínimo é 0.',
            'relacionamento.max' => 'O relacionamento máximo é 10.',
            'observacoes.max' => 'As observações não podem exceder 1000 caracteres.',
        ];
    }
}