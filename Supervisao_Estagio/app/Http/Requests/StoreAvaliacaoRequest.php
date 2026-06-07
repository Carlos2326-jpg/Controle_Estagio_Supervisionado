<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('coordenador');
    }

    public function rules(): array
    {
        return [
            'tipo'            => 'required|in:parcial,final',
            'parecer'         => 'required|string',
            'nota'            => 'nullable|numeric|min:0|max:10|required_without:conceito',
            'conceito'        => 'nullable|in:otimo,bom,regular,insuficiente|required_without:nota',
            'pontos_fortes'   => 'nullable|string',
            'pontos_melhoria' => 'nullable|string',
            'data_avaliacao'  => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required'           => 'O tipo da avaliação é obrigatório.',
            'tipo.in'                 => 'O tipo deve ser parcial ou final.',
            'parecer.required'        => 'O parecer é obrigatório.',
            'nota.numeric'            => 'A nota deve ser um número.',
            'nota.min'                => 'A nota mínima é 0.',
            'nota.max'                => 'A nota máxima é 10.',
            'nota.required_without'   => 'A nota ou o conceito deve ser informado.',
            'conceito.in'             => 'Conceito inválido.',
            'conceito.required_without' => 'A nota ou o conceito deve ser informado.',
            'data_avaliacao.date'     => 'Informe uma data válida.',
        ];
    }
}