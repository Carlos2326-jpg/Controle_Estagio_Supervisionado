<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Empresas</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .filtros { display: flex; gap: 8px; margin-bottom: 16px; }
        input, select { padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .btn-perigo { background: #dc2626; color: #fff; }
        .btn-sucesso { background: #16a34a; color: #fff; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-ativo  { background: #dcfce7; color: #16a34a; }
        .badge-inativo { background: #fee2e2; color: #dc2626; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
        .acoes { display: flex; gap: 6px; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 14px; }
        .paginacao { margin-top: 16px; display: flex; gap: 4px; }
    </style>
</head>
<body>

<div class="topo">
    <h1>Empresas Concedentes</h1>
    <a href="{{ route('empresas.create') }}" class="btn btn-primario">+ Nova Empresa</a>
</div>

@if(session('sucesso'))
    <div class="alerta-sucesso">{{ session('sucesso') }}</div>
@endif

<form method="GET" action="{{ route('empresas.index') }}" class="filtros">
    <input type="text" name="busca" placeholder="Buscar por nome ou CNPJ..." value="{{ request('busca') }}">
    <select name="status">
        <option value="">Todos os status</option>
        <option value="ativa"  {{ request('status') === 'ativa'  ? 'selected' : '' }}>Ativa</option>
        <option value="inativa" {{ request('status') === 'inativa' ? 'selected' : '' }}>Inativa</option>
    </select>
    <button type="submit" class="btn btn-secundario">Filtrar</button>
</form>

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
                @if($empresa->possuiConvenioAtivo())
                    <span class="badge badge-ativo">Ativo</span>
                @else
                    <span class="badge badge-inativo">Sem convênio</span>
                @endif
            </td>
            <td>
                <span class="badge {{ $empresa->status === 'ativa' ? 'badge-ativo' : 'badge-inativo' }}">
                    {{ ucfirst($empresa->status) }}
                </span>
            </td>
            <td>
                <div class="acoes">
                    <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">Ver</a>
                    <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-primario">Editar</a>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center; color:#888;">Nenhuma empresa encontrada.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="paginacao">
    {{ $empresas->withQueryString()->links() }}
</div>

</body>
</html>
