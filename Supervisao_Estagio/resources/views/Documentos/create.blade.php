@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Enviar Documento</h1>
    </div>

    <div class="bg-white rounded shadow p-6">
        <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Documento</label>
                    <input type="text" name="nome" value="{{ old('nome') }}"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('nome')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select name="tipo"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecione</option>
                        <option value="contrato" {{ old('tipo') == 'contrato' ? 'selected' : '' }}>Contrato</option>
                        <option value="termo_compromisso" {{ old('tipo') == 'termo_compromisso' ? 'selected' : '' }}>Termo de Compromisso</option>
                        <option value="declaracao" {{ old('tipo') == 'declaracao' ? 'selected' : '' }}>Declaração</option>
                        <option value="outro" {{ old('tipo') == 'outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                    @error('tipo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Solicitação Vinculada (opcional)</label>
                    <select name="solicitacao_estagio_id"
                            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Nenhuma</option>
                        @foreach($solicitacoes as $solicitacao)
                            <option value="{{ $solicitacao->id }}" {{ old('solicitacao_estagio_id') == $solicitacao->id ? 'selected' : '' }}>
                                {{ $solicitacao->empresa }} — {{ $solicitacao->data_inicio->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('solicitacao_estagio_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo</label>
                    <input type="file" name="arquivo"
                           class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-400 text-xs mt-1">Formatos aceitos: PDF, DOC, DOCX, JPG, PNG. Máximo 5MB.</p>
                    @error('arquivo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Enviar
                </button>
                <a href="{{ route('documentos.index') }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection
