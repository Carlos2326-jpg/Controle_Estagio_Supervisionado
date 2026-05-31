@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Perfil do Aluno</h1>
        <div class="flex gap-2">
            <a href="{{ route('alunos.edit', $aluno) }}"
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 text-sm">
                Editar
            </a>
            <a href="{{ route('alunos.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Dados Pessoais</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Nome</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->user->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Matrícula</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->matricula }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Curso</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->curso }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Período</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->periodo }}º</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Situação do Estágio</h2>
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Status</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->status_estagio }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Carga Horária Obrigatória</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->carga_horaria_obrigatoria }}h</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Carga Horária Cumprida</dt>
                    <dd class="font-medium text-gray-800">{{ $aluno->carga_horaria_cumprida }}h</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Solicitações</h2>
            <a href="{{ route('solicitacoes.create') }}"
               class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 text-sm">
                Nova Solicitação
            </a>
        </div>
        @forelse($aluno->solicitacoes as $solicitacao)
            <div class="flex items-center justify-between py-2 border-b last:border-0 text-sm">
                <span>{{ $solicitacao->empresa }}</span>
                <span class="text-gray-500">{{ $solicitacao->data_inicio->format('d/m/Y') }}</span>
                <span>{{ $solicitacao->status }}</span>
                <a href="{{ route('solicitacoes.show', $solicitacao) }}"
                   class="text-blue-600 hover:underline">Ver</a>
            </div>
        @empty
            <p class="text-gray-400 text-sm">Nenhuma solicitação registrada.</p>
        @endforelse
    </div>

    <div class="mt-6 bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Documentos</h2>
            <a href="{{ route('documentos.create') }}"
               class="bg-blue-600 text-white px-3 py-1.5 rounded hover:bg-blue-700 text-sm">
                Enviar Documento
            </a>
        </div>
        @forelse($aluno->documentos as $documento)
            <div class="flex items-center justify-between py-2 border-b last:border-0 text-sm">
                <span>{{ $documento->nome }}</span>
                <span class="text-gray-500">{{ $documento->tipo }}</span>
                <span>{{ $documento->status }}</span>
                <a href="{{ route('documentos.show', $documento) }}"
                   class="text-blue-600 hover:underline">Ver</a>
            </div>
        @empty
            <p class="text-gray-400 text-sm">Nenhum documento enviado.</p>
        @endforelse
    </div>
@endsection
