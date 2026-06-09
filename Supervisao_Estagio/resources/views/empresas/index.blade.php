<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresas - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Empresas Concedentes</h1>
                <a href="{{ route('empresas.create') }}" class="btn btn-primario">+ Nova Empresa</a>
            </div>

            @if (session('sucesso'))
                <div class="alerta-sucesso">{{ session('sucesso') }}</div>
            @endif

            <form method="GET" action="{{ route('empresas.index') }}" class="filtros">
                <input type="text" name="busca" placeholder="Buscar por nome ou CNPJ..."
                    value="{{ request('busca') }}">
                <select name="status">
                    <option value="">Todos os status</option>
                    <option value="ativa" {{ request('status') === 'ativa' ? 'selected' : '' }}>Ativa</option>
                    <option value="inativa" {{ request('status') === 'inativa' ? 'selected' : '' }}>Inativa</option>
                </select>
                <button type="submit" class="btn btn-secundario">Filtrar</button>
            </form>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Razão Social</th>
                            <th>CNPJ</th>
                            <th>E-mail</th>
                            <th>Cidade/UF</th>
                            <th>Convênio</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresas as $empresa)
                            <tr>
                                <td>{{ $empresa->razao_social }}</td>
                                <td>{{ $empresa->cnpj }}</td>
                                <td>{{ $empresa->email }}</td>
                                <td>{{ $empresa->cidade }}{{ $empresa->estado ? '/' . $empresa->estado : '' }}</td>
                                <td>
                                    @if ($empresa->possuiConvenioAtivo())
                                        <span class="badge badge-ativo">Ativo</span>
                                    @else
                                        <span class="badge badge-inativo">Sem convênio</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $empresa->status === 'ativa' ? 'badge-ativo' : 'badge-inativo' }}">
                                        {{ ucfirst($empresa->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="acoes">
                                        <a href="{{ route('empresas.show', $empresa) }}"
                                            class="btn btn-secundario">Ver</a>
                                        <a href="{{ route('empresas.edit', $empresa) }}"
                                            class="btn btn-primario">Editar</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:#888;">Nenhuma empresa encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="paginacao">
                {{ $empresas->withQueryString()->links() }}
            </div>
        </div>
    </div>
</body>

</html>
