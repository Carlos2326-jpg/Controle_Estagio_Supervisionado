<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Avaliações — {{ $supervisor->nome }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; color: #333; }
        h1 { font-size: 1.4rem; margin-bottom: 4px; }
        .subtitulo { color: #6b7280; font-size: 0.9rem; margin-bottom: 16px; }
        .topo { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .btn { padding: 7px 14px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .btn-primario { background: #2563eb; color: #fff; }
        .btn-secundario { background: #e5e7eb; color: #333; }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th { text-align: left; padding: 10px 12px; background: #f3f4f6; border-bottom: 2px solid #e5e7eb; }
        td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
        tr:hover td { background: #f9fafb; }
        .alerta-sucesso { background: #dcfce7; color: #166534; padding: 10px 14px; border-radius: 4px; margin-bottom: 14px; }
    </style>
</head>
<body>

@if(session('sucesso'))
    <div class="alerta-sucesso">{{ session('sucesso') }}</div>
@endif

<div class="topo">
    <div>
        <h1>Avaliações realizadas</h1>
        <p class="subtitulo">Supervisor: {{ $supervisor->nome }} · {{ $empresa->razao_social }}</p>
    </div>
    <a href="{{ route('empresas.supervisores', $empresa) }}" class="btn btn-secundario">← Voltar</a>
</div>

<table>
    <thead>
        <tr>
            <th>Estagiário</th>
            <th>Data</th>
            <th>Pontualidade</th>
            <th>Proatividade</th>
            <th>Qualidade</th>
            <th>Relacionamento</th>
            <th>Nota Geral</th>
        </tr>
    </thead>
    <tbody>
        @forelse($avaliacoes as $avaliacao)
        <tr>
            <td>{{ $avaliacao->solicitacao->aluno->user->name ?? '—' }}</td>
            <td>{{ $avaliacao->data_avaliacao->format('d/m/Y') }}</td>
            <td>{{ $avaliacao->pontualidade ?? '—' }}</td>
            <td>{{ $avaliacao->proatividade ?? '—' }}</td>
            <td>{{ $avaliacao->qualidade_trabalho ?? '—' }}</td>
            <td>{{ $avaliacao->relacionamento ?? '—' }}</td>
            <td><strong>{{ $avaliacao->nota_geral ?? '—' }}</strong></td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#888;">Nenhuma avaliação registrada.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:16px;">{{ $avaliacoes->links() }}</div>

</body>
</html>
