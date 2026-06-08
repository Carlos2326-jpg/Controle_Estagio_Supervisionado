<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Avaliações — {{ $supervisor->nome }}</title>
</head>

<body>

    @if (session('sucesso'))
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
                <tr>
                    <td colspan="7" style="text-align:center;color:#888;">Nenhuma avaliação registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:16px;">{{ $avaliacoes->links() }}</div>

</body>

</html>
