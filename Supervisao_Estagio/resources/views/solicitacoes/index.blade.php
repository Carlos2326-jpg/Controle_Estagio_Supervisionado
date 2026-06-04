@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Minhas Solicitações</h1>
        <a href="{{ route('solicitacoes.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            Nova Solicitação
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Empresa</th>
                    <th class="px-4 py-3 text-left">Início</th>
                    <th class="px-4 py-3 text-left">Fim</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($solicitacoes as $solicitacao)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $solicitacao->empresa }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($solicitacao->data_inicio)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($solicitacao->data_fim)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $solicitacao->status }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('solicitacoes.show', $solicitacao) }}"
                               class="text-blue-600 hover:underline">Ver</a>
                            @if($solicitacao->isPendente())
                                <form method="POST" action="{{ route('solicitacoes.destroy', $solicitacao) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('Cancelar esta solicitação?')">
                                        Cancelar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            Nenhuma solicitação encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t">
            {{ $solicitacoes->links() }}
        </div>
    </div>
@endsection
