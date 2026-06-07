<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConvenioRequest extends FormRequest
{
    public function authorize(): bool
    {
        $convenio = $this->route('convenio');
        $empresa = $this->route('empresa');
        
        return auth()->check() && (
            auth()->user()->hasRole('admin') ||
            (auth()->user()->hasRole('empresa') && $convenio->empresa_id === $empresa->id)
        );
    }

    public function rules(): array
    {
        $convenio = $this->route('convenio');
        
        return [
            'numero_convenio' => [
                'sometimes',
                'string',
                Rule::unique('convenios', 'numero_convenio')->ignore($convenio->id),
            ],
            'data_inicio' => 'sometimes|date',
            'data_fim'    => 'required_with:data_inicio|date|after:data_inicio',
            'status'      => 'sometimes|in:ativo,inativo,vencido',
            'observacoes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_convenio.unique' => 'Este número de convênio já está cadastrado.',
            'data_fim.after'         => 'A data de fim deve ser posterior à data de início.',
            'status.in'              => 'Status deve ser ativo, inativo ou vencido.',
        ];
    }
}