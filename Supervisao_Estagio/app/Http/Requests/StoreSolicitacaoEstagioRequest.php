<?php
// app/Http/Requests/StoreSolicitacaoEstagioRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Empresa;
use App\Models\Supervisor;

class StoreSolicitacaoEstagioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // CORRETO: Usar $this->user() em FormRequest (existe!)
        // Em FormRequests, o método user() existe e retorna o usuário autenticado
        $user = $this->user();
        
        if (!$user || !$user->hasRole('aluno')) {
            return false;
        }
        
        // Verificar se o aluno está ativo
        $aluno = $user->aluno;
        if (!$aluno || !$aluno->ativo || $aluno->situacao_estagio === 'em_andamento') {
            return false;
        }
        
        return true;
    }

    public function rules(): array
    {
        return [
            'empresa_id' => [
                'required', 
                'exists:empresas,id,status,ativa',
                function ($attribute, $value, $fail) {
                    $empresa = Empresa::find($value);
                    if ($empresa && !$empresa->possuiConvenioAtivo()) {
                        $fail('A empresa selecionada não possui convênio ativo.');
                    }
                }
            ],
            'supervisor_id' => [
                'required',
                'exists:supervisores,id,status,ativo',
                function ($attribute, $value, $fail) {
                    $supervisor = Supervisor::find($value);
                    if ($supervisor && $supervisor->empresa_id != $this->empresa_id) {
                        $fail('O supervisor informado não pertence à empresa selecionada.');
                    }
                }
            ],
            'data_inicio_prevista' => 'required|date|after_or_equal:today',
            'data_fim_prevista' => 'required|date|after:data_inicio_prevista',
            'carga_horaria_semanal' => 'required|integer|min:4|max:44',
            'carga_horaria_total' => 'required|integer|min:1',
            'descricao_atividades' => 'required|string|min:20|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'empresa_id.required' => 'A empresa é obrigatória.',
            'empresa_id.exists' => 'A empresa informada não existe ou está inativa.',
            'supervisor_id.required' => 'O supervisor é obrigatório.',
            'supervisor_id.exists' => 'O supervisor informado não existe ou está inativo.',
            'data_inicio_prevista.required' => 'A data de início é obrigatória.',
            'data_inicio_prevista.after_or_equal' => 'A data de início não pode ser no passado.',
            'data_fim_prevista.required' => 'A data de fim é obrigatória.',
            'data_fim_prevista.after' => 'A data de fim deve ser posterior à data de início.',
            'carga_horaria_semanal.required' => 'A carga horária semanal é obrigatória.',
            'carga_horaria_semanal.min' => 'A carga horária semanal mínima é de 4 horas.',
            'carga_horaria_semanal.max' => 'A carga horária semanal não pode exceder 44 horas.',
            'carga_horaria_total.required' => 'A carga horária total é obrigatória.',
            'descricao_atividades.required' => 'A descrição das atividades é obrigatória.',
            'descricao_atividades.min' => 'A descrição deve ter ao menos 20 caracteres.',
            'descricao_atividades.max' => 'A descrição não pode exceder 5000 caracteres.',
        ];
    }
}