<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $dados['titulo'] }} - Sistema de Estágios</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
                padding: 20px;
            }
            .card {
                box-shadow: none;
                padding: 0;
            }
        }
        .relatorio-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .relatorio-header h1 {
            color: #667eea;
            margin-bottom: 5px;
        }
        .relatorio-footer {
            margin-top: 30px;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="relatorio-header">
                <h1>{{ $dados['titulo'] }}</h1>
                <p>Gerado em: {{ \Carbon\Carbon::parse($dados['gerado_em'])->format('d/m/Y H:i:s') }}</p>
                <p>Usuário: {{ auth()->user()->name }}</p>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Matrícula</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dados['dados'] as $aluno)
                            <tr>
                                <td>{{ $aluno->user->name ?? $aluno->nome ?? '-' }}</td>
                                <td>{{ $aluno->matricula }}</td>
                                <td>{{ $aluno->situacao_estagio ?? 'Sem Estágio' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="relatorio-footer">
                <p>Documento gerado automaticamente pelo Sistema de Controle de Estágio Supervisionado.</p>
                <p>Sistema de Estágios - Todos os direitos reservados</p>
            </div>

            <div class="rodape no-print">
                <button class="btn btn-primario" onclick="window.print()">🖨️ Imprimir</button>
                <button class="btn btn-secundario" onclick="window.close()">Fechar</button>
            </div>
        </div>
    </div>
</body>
</html>