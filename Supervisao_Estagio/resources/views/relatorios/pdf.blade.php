<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
</head>

<body>

    <div class="cabecalho">
        <h2>{{ $dados['titulo'] }}</h2>
        <p>Curso: {{ $curso->nome }}</p>
        <p>Coordenador: {{ $coordenador->user->name }}</p>
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
                        <td>{{ $aluno->user->name ?? '-' }}</td>
                        <td>{{ $aluno->matricula }}</td>
                        <td>{{ $aluno->periodo_atual }}</td>
                        <td>{{ $aluno->situacao_estagio }}</td>
                        <td>{{ $aluno->horas_cumpridas }}</td>
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
                        <td>{{ $contrato->aluno->user->name ?? '-' }}</td>
                        <td>{{ $contrato->empresa->razao_social ?? '-' }}</td>
                        <td>{{ $contrato->data_inicio_prevista }}</td>
                        <td>{{ $contrato->data_fim_prevista }}</td>
                        <td>{{ $contrato->status }}</td>
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
                        <td>{{ $item['horas_previstas'] }}</td>
                        <td>{{ $item['horas_cumpridas'] }}</td>
                        <td>{{ $item['percentual'] }}</td>
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
                        <td>{{ $avaliacao->aluno->user->name ?? '-' }}</td>
                        <td>{{ $avaliacao->tipo }}</td>
                        <td>{{ $avaliacao->nota ?? '-' }}</td>
                        <td>{{ $avaliacao->conceito ?? '-' }}</td>
                        <td>{{ $avaliacao->parecer }}</td>
                        <td>{{ $avaliacao->data_avaliacao }}</td>
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
