<?php
// app/Http/Requests/StoreConvenioRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConvenioRequest extends FormRequest
{
    public function authorize(): bool
    {
        // CORRETO: Em FormRequest, $this->user() existe
        $user = $this->user();
        $empresa = $this->route('empresa');

        if (!$user) return false;

        return $user->hasRole('admin') ||
            ($user->hasRole('empresa') && $empresa && $empresa->user_id === $user->id);
    }

    public function rules(): array
    {
        return [
            'numero_convenio' => 'required|string|max:50|unique:convenios,numero_convenio',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
            'status' => 'sometimes|in:ativo,inativo,vencido',
            'observacoes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'numero_convenio.required' => 'O número do convênio é obrigatório.',
            'numero_convenio.max' => 'O número do convênio não pode exceder 50 caracteres.',
            'numero_convenio.unique' => 'Este número de convênio já está cadastrado.',
            'data_inicio.required' => 'A data de início é obrigatória.',
            'data_fim.required' => 'A data de fim é obrigatória.',
            'data_fim.after' => 'A data de fim deve ser posterior à data de início.',
            'observacoes.max' => 'As observações não podem exceder 1000 caracteres.',
        ];
    }
}