<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Solicitações — {{ $empresa->razao_social }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .filtros { display: flex; gap: 8px; margin-bottom: 16px; }
        select, input { padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        .badge { padding: 2px 8px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
        .badge-pendente  { background: #fef3c7; color: #92400e; }
        .badge-aprovada  { background: #dcfce7; color: #16a34a; }
        .badge-reprovada { background: #fee2e2; color: #dc2626; }
        .badge-cancelada { background: #f3f4f6; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
    </style>
</head>
<body>

<div class="topo">
    <div>
        <h1>Solicitações de Estágio Recebidas</h1>
        <p class="subtitulo">{{ $empresa->razao_social }}</p>
    </div>
    <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
</div>

<form method="GET" action="{{ route('empresas.solicitacoes', $empresa) }}" class="filtros">
    <select name="status">
        <option value="">Todos os status</option>
        <option value="pendente"   {{ request('status') === 'pendente'   ? 'selected' : '' }}>Pendente</option>
        <option value="aprovada"   {{ request('status') === 'aprovada'   ? 'selected' : '' }}>Aprovada</option>
        <option value="reprovada"  {{ request('status') === 'reprovada'  ? 'selected' : '' }}>Reprovada</option>
        <option value="cancelada"  {{ request('status') === 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
    </select>
    <button type="submit" class="btn btn-secundario">Filtrar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Supervisor</th>
            <th>Início Previsto</th>
            <th>Fim Previsto</th>
            <th>CH Total</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($solicitacoes as $solicitacao)
        <tr>
            <td>{{ $solicitacao->aluno->user->name ?? '—' }}</td>
            <td>{{ $solicitacao->supervisor->nome ?? '—' }}</td>
            <td>{{ $solicitacao->data_inicio_prevista?->format('d/m/Y') }}</td>
            <td>{{ $solicitacao->data_fim_prevista?->format('d/m/Y') }}</td>
            <td>{{ $solicitacao->carga_horaria_total }}h</td>
            <td>
                @php $s = $solicitacao->status @endphp
                <span class="badge badge-{{ $s }}">{{ ucfirst($s) }}</span>
            </td>
            <td>
                @if($solicitacao->status === 'aprovada')
                    <a href="{{ route('empresas.contrato', [$empresa, $solicitacao]) }}" class="btn btn-primario">Ver Contrato</a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#888;">Nenhuma solicitação encontrada.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">{{ $solicitacoes->links() }}</div>

</body>
</html>
