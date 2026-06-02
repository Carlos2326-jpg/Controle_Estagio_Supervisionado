@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Documento</h1>
        <a href="{{ route('documentos.index') }}"
           class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
            Voltar
        </a>
    </div>

    <div class="bg-white rounded shadow p-6">
        <dl class="space-y-4 text-sm">
            <div>
                <dt class="text-gray-500">Nome</dt>
                <dd class="font-medium text-gray-800">{{ $documento->nome }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Tipo</dt>
                <dd class="font-medium text-gray-800">{{ $documento->tipo }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd class="font-medium">
                    @if($documento->isAprovado())
                        <span class="text-green-600">Aprovado</span>
                    @elseif($documento->isReprovado())
                        <span class="text-red-600">Reprovado</span>
                    @else
                        <span class="text-yellow-600">Pendente</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Enviado em</dt>
                <dd class="font-medium text-gray-800">{{ $documento->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            @if($documento->solicitacao)
                <div>
                    <dt class="text-gray-500">Solicitação Vinculada</dt>
                    <dd class="font-medium text-gray-800">{{ $documento->solicitacao->empresa }}</dd>
                </div>
            @endif
            @if($documento->isReprovado() && $documento->observacao)
                <div class="border-l-4 border-red-400 pl-4 py-2 bg-red-50 rounded">
                    <dt class="text-red-600 font-medium mb-1">Justificativa da Reprovação</dt>
                    <dd class="text-red-700">{{ $documento->observacao }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-gray-500 mb-1">Arquivo</dt>
                <dd>
                    <a href="{{ Storage::url($documento->caminho_arquivo) }}"
                       target="_blank"
                       class="text-blue-600 hover:underline text-sm">
                        Visualizar arquivo
                    </a>
                </dd>
            </div>
        </dl>
    </div>
@endsection
