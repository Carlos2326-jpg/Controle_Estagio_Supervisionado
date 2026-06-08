<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoSupervisorRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $supervisor = $this->route('supervisor');
        $empresa = $this->route('empresa');

        if (!$user) return false;

        return $user->hasRole('empresa') &&
            $supervisor->empresa_id === $empresa->id;
    }

    public function rules(): array
    {
        return [
            'pontualidade'       => 'nullable|numeric|min:0|max:10',
            'proatividade'       => 'nullable|numeric|min:0|max:10',
            'qualidade_trabalho' => 'nullable|numeric|min:0|max:10',
            'relacionamento'     => 'nullable|numeric|min:0|max:10',
            'observacoes'        => 'nullable|string',
            'data_avaliacao'     => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'data_avaliacao.required' => 'A data da avaliação é obrigatória.',
            'data_avaliacao.date'     => 'Informe uma data válida.',
            'pontualidade.min'        => 'A pontualidade mínima é 0.',
            'pontualidade.max'        => 'A pontualidade máxima é 10.',
        ];
    }
}
