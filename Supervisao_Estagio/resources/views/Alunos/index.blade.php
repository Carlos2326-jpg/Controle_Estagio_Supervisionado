@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Alunos</h1>
        <a href="{{ route('alunos.create') }}"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Novo Aluno
        </a>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nome</th>
                    <th class="px-4 py-3 text-left">Matrícula</th>
                    <th class="px-4 py-3 text-left">Curso</th>
                    <th class="px-4 py-3 text-left">Período</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($alunos as $aluno)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $aluno->user->name }}</td>
                        <td class="px-4 py-3">{{ $aluno->matricula }}</td>
                        <td class="px-4 py-3">{{ $aluno->curso }}</td>
                        <td class="px-4 py-3">{{ $aluno->periodo }}º</td>
                        <td class="px-4 py-3">{{ $aluno->status_estagio }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('alunos.show', $aluno) }}"
                              class="text-blue-600 hover:underline">Ver</a>
                            <a href="{{ route('alunos.edit', $aluno) }}"
                              class="text-yellow-600 hover:underline">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">
                            Nenhum aluno cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-4 py-3 border-t">
            {{ $alunos->links() }}
        </div>
    </div>
@endsection
