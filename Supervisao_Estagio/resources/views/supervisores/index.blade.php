@extends('layouts.app')

@section('title', 'Supervisores')
@section('header', '👔 Supervisores')

@section('content')
    <div class="card">
        <div class="topo">
            <div>
                <h2>Supervisores</h2>
                <p class="subtitulo">{{ $empresa->razao_social }}</p>
            </div>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('empresas.supervisores.create', $empresa) }}" class="btn btn-primary">+ Novo Supervisor</a>
                <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secondary">← Voltar</a>
            </div>
        </div>

        @if (session('sucesso'))
            <div class="alert alert-success">{{ session('sucesso') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Cargo</th>
                    <th>E-mail</th>
                    <th>Formação</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($supervisores as $supervisor)
                    <tr>
                        <td>{{ $supervisor->nome }}<\ /span>
                        </td>
                        <td>{{ $supervisor->cargo }}<\ /span>
                        </td>
                        <td>{{ $supervisor->email }}<\ /span>
                        </td>
                        <td>{{ $supervisor->formacao ?? '—' }}<\ /span>
                        </td>
                        <td><span
                                class="badge {{ $supervisor->status === 'ativo' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($supervisor->status) }}</span>
                            <\ /span>
                        </td>
                        <td>
                            <div class="acoes">
                                <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}"
                                    class="btn btn-secondary">Avaliações</a>
                                <a href="{{ route('empresas.supervisores.edit', [$empresa, $supervisor]) }}"
                                    class="btn btn-warning">Editar</a>
                                @if ($supervisor->isAtivo())
                                    <form method="POST"
                                        action="{{ route('empresas.supervisores.desativar', [$empresa, $supervisor]) }}"
                                        style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-danger"
                                            onclick="return confirm('Desativar supervisor?')">Desativar</button>
                                    </form>
                                @endif
                            </div>
                            <\ /span>
                        </td>
                    </tr>
                @empty
                    <td>
                    <td colspan="6" style="text-align:center;">Nenhum supervisor cadastrado.<\ /span>
                    </td>
                @endforelse
            </tbody>
        </table>

        <div class="paginacao">{{ $supervisores->links() }}</div>
    </div>
@endsection
