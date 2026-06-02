@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Meus Documentos</h1>
        <a href="{{ route('documentos.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            Enviar Documento
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nome</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Enviado em</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($documentos as $documento)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $documento->nome }}</td>
                        <td class="px-4 py-3">{{ $documento->tipo }}</td>
                        <td class="px-4 py-3">
                            @if($documento->status === 'aprovado')
                                <span class="text-green-600 font-medium">Aprovado</span>
                            @elseif($documento->status === 'reprovado')
                                <span class="text-red-600 font-medium">Reprovado</span>
                            @else
                                <span class="text-yellow-600 font-medium">Pendente</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $documento->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('documentos.show', $documento) }}"
                               class="text-blue-600 hover:underline">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            Nenhum documento enviado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t">
            {{ $documentos->links() }}
        </div>
    </div>
@endsection
