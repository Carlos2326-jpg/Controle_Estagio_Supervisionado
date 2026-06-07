<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        $avaliacao = $this->route('avaliacao');
        $coordenador = $this->route('coordenador');
        
        return auth()->check() && 
               auth()->user()->hasRole('coordenador') &&
               $avaliacao->coordenador_id === $coordenador->id;
    }

    public function rules(): array
    {
        return [
            'nota'            => 'nullable|numeric|min:0|max:10',
            'conceito'        => 'nullable|in:otimo,bom,regular,insuficiente',
            'parecer'         => 'sometimes|string',
            'pontos_fortes'   => 'nullable|string',
            'pontos_melhoria' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nota.min'       => 'A nota mínima é 0.',
            'nota.max'       => 'A nota máxima é 10.',
            'conceito.in'    => 'Conceito inválido.',
        ];
    }
}