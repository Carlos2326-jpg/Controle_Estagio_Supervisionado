<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Supervisores — {{ $empresa->razao_social }}</title>
</head>

<body>

    @if (session('sucesso'))
        <div class="alerta-sucesso">{{ session('sucesso') }}</div>
    @endif

    <div class="topo">
        <div>
            <h1>Supervisores</h1>
            <p class="subtitulo">{{ $empresa->razao_social }}</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('empresas.supervisores.create', $empresa) }}" class="btn btn-primario">+ Novo Supervisor</a>
            <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
        </div>
    </div>

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
                    <td>{{ $supervisor->nome }}</td>
                    <td>{{ $supervisor->cargo }}</td>
                    <td>{{ $supervisor->email }}</td>
                    <td>{{ $supervisor->formacao ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $supervisor->status === 'ativo' ? 'badge-ativo' : 'badge-inativo' }}">
                            {{ ucfirst($supervisor->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="acoes">
                            <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}"
                                class="btn btn-secundario">Avaliações</a>
                            <a href="{{ route('empresas.supervisores.edit', [$empresa, $supervisor]) }}"
                                class="btn btn-primario">Editar</a>
                            @if ($supervisor->isAtivo())
                                <form method="POST"
                                    action="{{ route('empresas.supervisores.desativar', [$empresa, $supervisor]) }}"
                                    style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-perigo"
                                        onclick="return confirm('Desativar supervisor?')">Desativar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;color:#888;">Nenhum supervisor cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">{{ $supervisores->links() }}</div>

</body>

</html>
