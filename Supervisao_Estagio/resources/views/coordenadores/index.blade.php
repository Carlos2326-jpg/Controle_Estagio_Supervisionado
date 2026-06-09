<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordenadores - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Coordenadores</h1>
                <a href="{{ route('coordenadores.create') }}" class="btn btn-primario">+ Novo Coordenador</a>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Curso</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coordenadores as $coordenador)
                            <tr>
                                <td>{{ $coordenador->nome }}</td>
                                <td>{{ $coordenador->email }}</td>
                                <td>{{ $coordenador->curso->nome ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $coordenador->status === 'ativo' ? 'badge-ativo' : 'badge-inativo' }}">
                                        {{ ucfirst($coordenador->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ route('coordenadores.edit', $coordenador) }}" class="btn btn-primario">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:#888;">Nenhum coordenador cadastrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $coordenadores->links() }}
            </div>
        </div>
    </div>
</body>
</html>