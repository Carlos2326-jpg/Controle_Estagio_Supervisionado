<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitacaoEstagioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && $user->hasRole('aluno');
    }

    public function rules(): array
    {
        return [
            'empresa_id'            => 'required|exists:empresas,id,status,ativa',
            'supervisor_id'         => 'required|exists:supervisores,id,status,ativo',
            'data_inicio_prevista'  => 'required|date|after_or_equal:today',
            'data_fim_prevista'     => 'required|date|after:data_inicio_prevista',
            'carga_horaria_semanal' => 'required|integer|min:1|max:44',
            'carga_horaria_total'   => 'required|integer|min:1',
            'descricao_atividades'  => 'required|string|min:20',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $empresaId = $this->input('empresa_id');
            $supervisorId = $this->input('supervisor_id');

            if ($empresaId && $supervisorId) {
                $supervisor = \App\Models\Supervisor::find($supervisorId);
                if ($supervisor && $supervisor->empresa_id != $empresaId) {
                    $validator->errors()->add(
                        'supervisor_id',
                        'O supervisor informado não pertence à empresa selecionada.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'empresa_id.required'               => 'A empresa é obrigatória.',
            'empresa_id.exists'                 => 'A empresa informada não existe ou está inativa.',
            'supervisor_id.required'            => 'O supervisor é obrigatório.',
            'supervisor_id.exists'              => 'O supervisor informado não existe ou está inativo.',
            'data_inicio_prevista.required'     => 'A data de início é obrigatória.',
            'data_inicio_prevista.after_or_equal' => 'A data de início não pode ser no passado.',
            'data_fim_prevista.required'        => 'A data de fim é obrigatória.',
            'data_fim_prevista.after'           => 'A data de fim deve ser posterior à data de início.',
            'carga_horaria_semanal.required'    => 'A carga horária semanal é obrigatória.',
            'carga_horaria_semanal.max'         => 'A carga horária semanal não pode exceder 44h.',
            'carga_horaria_total.required'      => 'A carga horária total é obrigatória.',
            'descricao_atividades.required'     => 'A descrição das atividades é obrigatória.',
            'descricao_atividades.min'          => 'A descrição deve ter ao menos 20 caracteres.',
        ];
    }
}
