<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $dados['titulo'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            padding: 40px;
            color: #333;
        }
        .cabecalho {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        .cabecalho h2 {
            color: #667eea;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .cabecalho p {
            font-size: 11px;
            color: #666;
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .rodape {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #999;
        }
        .badge-ativo { color: #28a745; }
        .badge-pendente { color: #ffc107; }
        .badge-inativo { color: #dc3545; }
    </style>
</head>
<body>

    <div class="cabecalho">
        <h2>{{ $dados['titulo'] }}</h2>
        <p>Curso: {{ $curso->nome }}</p>
        <p>Coordenador: {{ $coordenador->user->name ?? $coordenador->nome ?? 'Sistema' }}</p>
        <p>Gerado em: {{ $gerado_em }}</p>
    </div>

    @if ($tipo === 'alunos')
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Período</th>
                    <th>Situação de Estágio</th>
                    <th>Horas Cumpridas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['dados'] as $aluno)
                    <tr>
                        <td>{{ $aluno->user->name ?? $aluno->nome ?? '-' }}</td>
                        <td>{{ $aluno->matricula }}</td>
                        <td>{{ $aluno->periodo_atual }}°</td>
                        <td>{{ $aluno->situacao_estagio ?? 'Sem Estágio' }}</td>
                        <td>{{ $aluno->horas_cumpridas ?? $aluno->carga_horaria_cumprida ?? 0 }}h</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo === 'contratos')
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Empresa</th>
                    <th>Início Previsto</th>
                    <th>Fim Previsto</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['dados'] as $contrato)
                    <tr>
                        <td>{{ $contrato->aluno->user->name ?? $contrato->aluno->nome ?? '-' }}</td>
                        <td>{{ $contrato->empresa->razao_social ?? '-' }}</td>
                        <td>{{ $contrato->data_inicio_prevista?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $contrato->data_fim_prevista?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ ucfirst($contrato->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo === 'horas')
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Matrícula</th>
                    <th>Empresa</th>
                    <th>Horas Previstas</th>
                    <th>Horas Cumpridas</th>
                    <th>Percentual</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['dados'] as $item)
                    <tr>
                        <td>{{ $item['aluno'] }}</td>
                        <td>{{ $item['matricula'] }}</td>
                        <td>{{ $item['empresa'] }}</td>
                        <td>{{ $item['horas_previstas'] }}h</td>
                        <td>{{ $item['horas_cumpridas'] }}h</td>
                        <td>{{ $item['percentual'] }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo === 'avaliacoes')
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Tipo</th>
                    <th>Nota</th>
                    <th>Conceito</th>
                    <th>Parecer</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dados['dados'] as $avaliacao)
                    <tr>
                        <td>{{ $avaliacao->aluno->user->name ?? $avaliacao->aluno->nome ?? '-' }}</td>
                        <td>{{ ucfirst($avaliacao->tipo) }}</td>
                        <td>{{ $avaliacao->nota ?? '-' }}</td>
                        <td>{{ $avaliacao->conceito ?? '-' }}</td>
                        <td>{{ Str::limit($avaliacao->parecer, 50) ?? '-' }}</td>
                        <td>{{ $avaliacao->data_avaliacao?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="rodape">
        Documento gerado automaticamente pelo Sistema de Controle de Estágio Supervisionado.
    </div>

</body>
</html>