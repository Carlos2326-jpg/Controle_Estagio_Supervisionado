<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAtividadeEstagioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $atividade = $this->route('atividade');

        if (!$user) return false;

        return $user->hasRole('aluno') &&
            $atividade->aluno->user_id === $user->id &&
            !$atividade->validado_supervisor;
    }

    public function rules(): array
    {
        return [
            'data'      => 'sometimes|date|before_or_equal:today',
            'descricao' => 'sometimes|string|min:10',
            'horas'     => 'sometimes|numeric|min:0.5|max:12',
        ];
    }

    public function messages(): array
    {
        return [
            'data.before_or_equal' => 'Não é possível registrar atividades futuras.',
            'descricao.min'        => 'A descrição deve ter ao menos 10 caracteres.',
            'horas.min'            => 'O mínimo é 0,5 hora por registro.',
            'horas.max'            => 'O máximo é 12 horas por registro.',
        ];
    }
}
