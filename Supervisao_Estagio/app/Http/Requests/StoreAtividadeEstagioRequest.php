<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtividadeEstagioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasRole('aluno');
    }

    public function rules(): array
    {
        return [
            'solicitacao_estagio_id' => 'required|exists:solicitacoes_estagio,id,status,aprovada',
            'data'                   => 'required|date|before_or_equal:today',
            'descricao'              => 'required|string|min:10',
            'horas'                  => 'required|numeric|min:0.5|max:12',
        ];
    }

    public function messages(): array
    {
        return [
            'solicitacao_estagio_id.required' => 'A solicitação de estágio é obrigatória.',
            'solicitacao_estagio_id.exists'   => 'Solicitação de estágio não encontrada ou não aprovada.',
            'data.required'                   => 'A data da atividade é obrigatória.',
            'data.before_or_equal'            => 'Não é possível registrar atividades futuras.',
            'descricao.required'              => 'A descrição da atividade é obrigatória.',
            'descricao.min'                   => 'A descrição deve ter ao menos 10 caracteres.',
            'horas.required'                  => 'A quantidade de horas é obrigatória.',
            'horas.min'                       => 'O mínimo é 0,5 hora por registro.',
            'horas.max'                       => 'O máximo é 12 horas por registro.',
        ];
    }
}