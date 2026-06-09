<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Avaliações - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="relatorio-header">
                <h1>Relatório de Avaliações</h1>
                <p>Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Tipo</th>
                            <th>Nota</th>
                            <th>Conceito</th>
                            <th>Avaliador</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dados['dados'] as $avaliacao)
                            <tr>
                                <td>{{ $avaliacao->aluno->user->name ?? $avaliacao->aluno->nome ?? '-' }}</td>
                                <td>{{ ucfirst($avaliacao->tipo) }}</td>
                                <td>{{ $avaliacao->nota_geral ?? $avaliacao->nota ?? '-' }}</td>
                                <td>{{ $avaliacao->conceito ?? '-' }}</td>
                                <td>{{ $avaliacao->supervisor->nome ?? $avaliacao->coordenador->nome ?? '-' }}</td>
                                <td>{{ $avaliacao->data_avaliacao?->format('d/m/Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhuma avaliação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="rodape no-print">
                <button class="btn btn-primario" onclick="window.print()">🖨️ Imprimir</button>
                <button class="btn btn-secundario" onclick="window.close()">Fechar</button>
            </div>
        </div>
    </div>
</body>
</html>