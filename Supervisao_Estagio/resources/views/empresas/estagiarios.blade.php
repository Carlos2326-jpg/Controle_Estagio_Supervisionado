<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Estagiários — {{ $empresa->razao_social }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .filtros { display: flex; gap: 8px; margin-bottom: 16px; }
        select { padding: 6px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 0.9rem; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
    </style>
</head>
<body>

<div class="topo">
    <div>
        <h1>Estagiários Vinculados</h1>
        <p class="subtitulo">{{ $empresa->razao_social }}</p>
    </div>
    <a href="{{ route('empresas.show', $empresa) }}" class="btn btn-secundario">← Voltar</a>
</div>

<form method="GET" action="{{ route('empresas.estagiarios', $empresa) }}" class="filtros">
    <select name="supervisor_id">
        <option value="">Todos os supervisores</option>
        @foreach($supervisores as $sup)
            <option value="{{ $sup->id }}" {{ request('supervisor_id') == $sup->id ? 'selected' : '' }}>
                {{ $sup->nome }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-secundario">Filtrar</button>
</form>

<table>
    <thead>
        <tr>
            <th>Aluno</th>
            <th>Supervisor</th>
            <th>Início</th>
            <th>Fim Previsto</th>
            <th>CH Semanal</th>
            <th>CH Total</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @forelse($estagiarios as $estagio)
        <tr>
            <td>{{ $estagio->aluno->user->name ?? '—' }}</td>
            <td>{{ $estagio->supervisor->nome ?? '—' }}</td>
            <td>{{ $estagio->data_inicio_prevista?->format('d/m/Y') }}</td>
            <td>{{ $estagio->data_fim_prevista?->format('d/m/Y') }}</td>
            <td>{{ $estagio->carga_horaria_semanal }}h</td>
            <td>{{ $estagio->carga_horaria_total }}h</td>
            <td>
                @if($estagio->supervisor)
                    <a href="{{ route('empresas.supervisores.avaliacoes.create', [$empresa, $estagio->supervisor, $estagio]) }}"
                       class="btn btn-primario">Avaliar</a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#888;">Nenhum estagiário vinculado.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">{{ $estagiarios->links() }}</div>

</body>
</html>
