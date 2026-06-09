<?php
// app/Http/Requests/StoreAtividadeEstagioRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAtividadeEstagioRequest extends FormRequest
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
            'solicitacao_estagio_id' => 'required|exists:solicitacoes_estagio,id,status,aprovada',
            'data' => 'required|date|before_or_equal:today',
            'descricao' => 'required|string|min:10|max:5000',
            'horas' => 'required|numeric|min:0.5|max:12',
        ];
    }

    public function messages(): array
    {
        return [
            'solicitacao_estagio_id.required' => 'A solicitação de estágio é obrigatória.',
            'solicitacao_estagio_id.exists' => 'Solicitação de estágio não encontrada ou não aprovada.',
            'data.required' => 'A data da atividade é obrigatória.',
            'data.before_or_equal' => 'Não é possível registrar atividades futuras.',
            'descricao.required' => 'A descrição da atividade é obrigatória.',
            'descricao.min' => 'A descrição deve ter ao menos 10 caracteres.',
            'descricao.max' => 'A descrição não pode exceder 5000 caracteres.',
            'horas.required' => 'A quantidade de horas é obrigatória.',
            'horas.min' => 'O mínimo é 0,5 hora por registro.',
            'horas.max' => 'O máximo é 12 horas por registro.',
        ];
    }
    
    // VALIDAÇÃO ADICIONAL PÓS-VALIDAÇÃO
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $solicitacaoId = $this->input('solicitacao_estagio_id');
            $data = $this->input('data');
            $horas = $this->input('horas');
            
            if ($solicitacaoId && $data) {
                $solicitacao = \App\Models\SolicitacaoEstagio::find($solicitacaoId);
                
                if ($solicitacao) {
                    // Validar data dentro do período
                    $dataCarbon = \Carbon\Carbon::parse($data);
                    if ($dataCarbon->lt($solicitacao->data_inicio_prevista) || 
                        $dataCarbon->gt($solicitacao->data_fim_prevista)) {
                        $validator->errors()->add('data', 'A data da atividade deve estar dentro do período de vigência do estágio.');
                    }
                    
                    // Validar carga horária semanal
                    if ($horas) {
                        $inicioSemana = $dataCarbon->copy()->startOfWeek();
                        $fimSemana = $dataCarbon->copy()->endOfWeek();
                        $horasSemanaAtual = \App\Models\AtividadeEstagio::where('solicitacao_estagio_id', $solicitacao->id)
                            ->whereBetween('data', [$inicioSemana, $fimSemana])
                            ->sum('horas');
                            
                        if (($horasSemanaAtual + $horas) > $solicitacao->carga_horaria_semanal) {
                            $validator->errors()->add('horas', "A carga horária semanal não pode exceder {$solicitacao->carga_horaria_semanal} horas.");
                        }
                    }
                }
            }
        });
    }
}