<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Horas - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .progress-cell {
            min-width: 150px;
        }
        .progress-bar-container {
            background-color: #e9ecef;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
        }
        .progress-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="relatorio-header">
                <h1>Relatório de Horas de Estágio</h1>
                <p>Gerado em: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Aluno</th>
                            <th>Matrícula</th>
                            <th>Empresa</th>
                            <th>Horas Previstas</th>
                            <th>Horas Cumpridas</th>
                            <th>Percentual</th>
                            <th>Progresso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dados['dados'] as $item)
                            <tr>
                                <td>{{ $item['aluno'] }}</td>
                                <td>{{ $item['matricula'] }}</td>
                                <td>{{ $item['empresa'] }}</td>
                                <td>{{ $item['horas_previstas'] }}h</td>
                                <td>{{ $item['horas_cumpridas'] }}h</td>
                                <td>{{ $item['percentual'] }}%</td>
                                <td class="progress-cell">
                                    <div class="progress-bar-container">
                                        <div class="progress-bar" style="width: {{ $item['percentual'] }}%"></div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center;color:#888;">Nenhum dado encontrado.</td>
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