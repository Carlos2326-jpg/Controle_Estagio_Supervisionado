@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Minhas Atividades</h1>
        <a href="{{ route('atividades.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            Registrar Atividade
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Data</th>
                    <th class="px-4 py-3 text-left">Início</th>
                    <th class="px-4 py-3 text-left">Fim</th>
                    <th class="px-4 py-3 text-left">Horas</th>
                    <th class="px-4 py-3 text-left">Validado</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($atividades as $atividade)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $atividade->data_atividade->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $atividade->hora_inicio }}</td>
                        <td class="px-4 py-3">{{ $atividade->hora_fim }}</td>
                        <td class="px-4 py-3">{{ $atividade->horas_computadas }}h</td>
                        <td class="px-4 py-3">
                            @if($atividade->validado)
                                <span class="text-green-600 font-medium">Sim</span>
                            @else
                                <span class="text-yellow-600 font-medium">Não</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            @if($atividade->podeEditar())
                                <a href="{{ route('atividades.edit', $atividade) }}"
                                   class="text-yellow-600 hover:underline">Editar</a>
                                <form method="POST" action="{{ route('atividades.destroy', $atividade) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:underline"
                                            onclick="return confirm('Excluir esta atividade?')">
                                        Excluir
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                            Nenhuma atividade registrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t">
            {{ $atividades->links() }}
        </div>
    </div>
@endsection
