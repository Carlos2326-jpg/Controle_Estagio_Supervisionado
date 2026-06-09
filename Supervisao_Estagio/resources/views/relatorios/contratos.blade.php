<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Contratos - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="relatorio-header">
                <h1>Relatório de Contratos de Estágio</h1>
                <p>Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Empresa</th>
                            <th>Supervisor</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dados['dados'] as $contrato)
                            <tr>
                                <td>{{ $contrato->aluno->user->name ?? $contrato->aluno->nome ?? '-' }}</td>
                                <td>{{ $contrato->empresa->razao_social ?? '-' }}</td>
                                <td>{{ $contrato->supervisor->nome ?? '-' }}</td>
                                <td>{{ $contrato->data_inicio_prevista?->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $contrato->data_fim_prevista?->format('d/m/Y') ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-{{ $contrato->status }}">{{ ucfirst($contrato->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#888;">Nenhum contrato encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="relatorio-footer">
                <p>Total de contratos: {{ $dados['dados']->count() }}</p>
            </div>

            <div class="rodape no-print">
                <button class="btn btn-primario" onclick="window.print()">🖨️ Imprimir</button>
                <button class="btn btn-secundario" onclick="window.close()">Fechar</button>
            </div>
        </div>
    </div>
</body>
</html>