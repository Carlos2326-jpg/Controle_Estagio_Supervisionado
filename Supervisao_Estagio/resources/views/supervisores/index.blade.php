<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Supervisores — {{ $empresa->razao_social }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .btn-perigo { background: #dc2626; color: #fff; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-ativo  { background: #dcfce7; color: #16a34a; }
        .badge-inativo { background: #fee2e2; color: #dc2626; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
        .acoes { display: flex; gap: 6px; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 14px; }
    </style>
</head>
<body>

@if(session('sucesso'))
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
                    <a href="{{ route('empresas.supervisores.avaliacoes', [$empresa, $supervisor]) }}" class="btn btn-secundario">Avaliações</a>
                    <a href="{{ route('empresas.supervisores.edit', [$empresa, $supervisor]) }}" class="btn btn-primario">Editar</a>
                    @if($supervisor->isAtivo())
                    <form method="POST" action="{{ route('empresas.supervisores.desativar', [$empresa, $supervisor]) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button class="btn btn-perigo" onclick="return confirm('Desativar supervisor?')">Desativar</button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#888;">Nenhum supervisor cadastrado.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">{{ $supervisores->links() }}</div>

</body>
</html>
