@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Registrar Atividade</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" action="{{ route('atividades.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Solicitação</label>
                    <select name="solicitacao_id"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione</option>
                        @foreach($solicitacoes as $solicitacao)
                            <option value="{{ $solicitacao->id }}" {{ old('solicitacao_id') == $solicitacao->id ? 'selected' : '' }}>
                                {{ $solicitacao->empresa }} — {{ $solicitacao->data_inicio->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('solicitacao_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data da Atividade</label>
                    <input type="date" name="data_atividade" value="{{ old('data_atividade') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_atividade')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Horas Computadas</label>
                    <input type="number" name="horas_computadas" value="{{ old('horas_computadas') }}" step="0.1" min="0.1"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('horas_computadas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Início</label>
                    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('hora_inicio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora Fim</label>
                    <input type="time" name="hora_fim" value="{{ old('hora_fim') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('hora_fim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                    <textarea name="descricao" rows="4"
                              class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao') }}</textarea>
                    @error('descricao')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Registrar
                </button>
                <a href="{{ route('atividades.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
