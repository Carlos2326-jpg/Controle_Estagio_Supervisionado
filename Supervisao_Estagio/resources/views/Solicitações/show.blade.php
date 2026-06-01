@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Solicitação de Estágio</h1>
        <a href="{{ route('solicitacoes.index') }}"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Dados da Empresa</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Empresa</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->empresa }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Supervisor</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->supervisor_nome }}</dd>
                </div>
                @if($solicitacao->supervisor_email)
                    <div>
                        <dt class="text-gray-500">Email do Supervisor</dt>
                        <dd class="font-medium text-gray-800">{{ $solicitacao->supervisor_email }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Dados do Estágio</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->status }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Início</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->data_inicio->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Fim</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->data_fim->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Carga Horária Semanal</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->carga_horaria_semanal }}h</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Carga Horária Total</dt>
                    <dd class="font-medium text-gray-800">{{ $solicitacao->carga_horaria_total }}h</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Descrição das Atividades</h2>
        <p class="text-sm text-gray-700">{{ $solicitacao->descricao_atividades }}</p>
    </div>

    @if($solicitacao->isPendente())
        <div class="mt-4 flex justify-end">
            <form method="POST" action="{{ route('solicitacoes.destroy', $solicitacao) }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm"
                        onclick="return confirm('Cancelar esta solicitação?')">
                    Cancelar Solicitação
                </button>
            </form>
        </div>
    @endif
@endsection
