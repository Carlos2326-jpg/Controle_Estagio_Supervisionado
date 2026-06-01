@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nova Solicitação de Estágio</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" action="{{ route('solicitacoes.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                    <input type="text" name="empresa" value="{{ old('empresa') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('empresa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Supervisor</label>
                    <input type="text" name="supervisor_nome" value="{{ old('supervisor_nome') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('supervisor_nome')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email do Supervisor</label>
                    <input type="email" name="supervisor_email" value="{{ old('supervisor_email') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('supervisor_email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_inicio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Fim</label>
                    <input type="date" name="data_fim" value="{{ old('data_fim') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('data_fim')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horária Semanal</label>
                    <input type="number" name="carga_horaria_semanal" value="{{ old('carga_horaria_semanal') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('carga_horaria_semanal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carga Horária Total</label>
                    <input type="number" name="carga_horaria_total" value="{{ old('carga_horaria_total') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('carga_horaria_total')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descrição das Atividades</label>
                    <textarea name="descricao_atividades" rows="4"
                              class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao_atividades') }}</textarea>
                    @error('descricao_atividades')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Enviar Solicitação
                </button>
                <a href="{{ route('solicitacoes.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
